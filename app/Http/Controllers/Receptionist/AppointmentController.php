<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Patient;
use App\Enums\RegistrationStatus;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::with(['patient', 'schedule.doctor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('registration_date', $request->date);
        }

        $appointments = $query->latest('registration_date')->paginate(10)->withQueryString();

        return view('receptionist.appointments.index', compact('appointments'));
    }

    public function availableSlots(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        $date = $request->date;

        $start = Carbon::parse($schedule->start_hour);
        $end = Carbon::parse($schedule->end_hour);

        $slots = [];
        $current = $start->copy();

        while ($current->lt($end)) {
            $slotStart = $current->format('H:i');
            $current->addMinutes(20);
            if ($current->gt($end)) break;
            $slotEnd = $current->format('H:i');
            $slots[] = "$slotStart - $slotEnd";
        }

        $bookedSlots = Registration::where('schedule_id', $schedule->id)
            ->whereDate('registration_date', $date)
            ->where('status', '!=', RegistrationStatus::CANCELLED)
            ->pluck('time_slot')
            ->toArray();

        $availableSlots = array_values(array_diff($slots, $bookedSlots));

        return response()->json([
            'available_slots' => $availableSlots
        ]);
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('full_name')->get();
        $doctors = Doctor::with('schedules')->get();
        $selectedPatientId = $request->query('patient_id');

        return view('receptionist.appointments.create', compact('patients', 'doctors', 'selectedPatientId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'schedule_id' => 'required|exists:schedules,id',
            'registration_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        // Check if patient already has an appointment at this exact date and time slot
        $hasDuplicate = Registration::where('patient_id', $patient->id)
            ->whereDate('registration_date', $request->registration_date)
            ->where('time_slot', $request->time_slot)
            ->where('status', '!=', RegistrationStatus::CANCELLED)
            ->exists();

        if ($hasDuplicate) {
            return back()->with('error', 'The patient already has another appointment scheduled at this exact date and time slot.');
        }

        $schedule = Schedule::findOrFail($request->schedule_id);

        $selectedDay = Carbon::parse($request->registration_date)->format('l');
        if (strcasecmp($selectedDay, $schedule->schedule_day->value) !== 0) {
            return back()->with('error', "This schedule is for {$schedule->schedule_day->value}s. You selected a {$selectedDay}.");
        }

        $count = Registration::where('schedule_id', $schedule->id)
            ->whereDate('registration_date', $request->registration_date)
            ->where('status', '!=', RegistrationStatus::CANCELLED)
            ->count();

        if ($count >= $schedule->quota) {
            return back()->with('error', 'The quota for this doctor on selected date is full.');
        }

        // Check if the specific time slot is already booked
        $isSlotBooked = Registration::where('schedule_id', $schedule->id)
            ->whereDate('registration_date', $request->registration_date)
            ->where('time_slot', $request->time_slot)
            ->where('status', '!=', RegistrationStatus::CANCELLED)
            ->exists();

        if ($isSlotBooked) {
            return back()->with('error', 'The selected time slot is already booked.');
        }

        $start = Carbon::parse($schedule->start_hour);
        $end = Carbon::parse($schedule->end_hour);
        $slots = [];
        $current = $start->copy();
        while ($current->lt($end)) {
            $slotStart = $current->format('H:i');
            $current->addMinutes(20);
            if ($current->gt($end)) break;
            $slotEnd = $current->format('H:i');
            $slots[] = "$slotStart - $slotEnd";
        }

        $queueNumber = array_search($request->time_slot, $slots);
        if ($queueNumber === false) {
            return back()->with('error', 'Invalid time slot selected.');
        }
        $queueNumber += 1;

        // Check if selected time slot has already passed for today
        $selectedDate = Carbon::parse($request->registration_date);
        if ($selectedDate->isToday()) {
            $slotStartStr = explode(' - ', $request->time_slot)[0] ?? null;
            if ($slotStartStr) {
                $slotStart = Carbon::parse($request->registration_date . ' ' . $slotStartStr);
                if ($slotStart->isPast()) {
                    return back()->with('error', 'The selected time slot has already passed for today.');
                }
            }
        }

        Registration::create([
            'patient_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'queue_number' => $queueNumber,
            'time_slot' => $request->time_slot,
            'status' => RegistrationStatus::REGISTERED,
            'registration_date' => $request->registration_date,
        ]);

        return redirect()->route('receptionist.appointments.index')->with('success', 'Appointment booked successfully. Queue number is #' . $queueNumber);
    }

    public function cancel(Registration $appointment)
    {
        if ($appointment->status !== RegistrationStatus::REGISTERED) {
            return back()->with('error', 'Only registered appointments can be cancelled.');
        }

        // Prevent cancelling past appointments
        $appointmentStartStr = explode(' - ', $appointment->time_slot)[0] ?? null;
        if ($appointmentStartStr) {
            $appointmentStart = Carbon::parse($appointment->registration_date->format('Y-m-d') . ' ' . $appointmentStartStr);
            if ($appointmentStart->isPast()) {
                return back()->with('error', 'You cannot cancel an appointment that has already started or passed.');
            }
        }

        $appointment->update(['status' => RegistrationStatus::CANCELLED]);

        return back()->with('success', 'Appointment cancelled successfully.');
    }
}
