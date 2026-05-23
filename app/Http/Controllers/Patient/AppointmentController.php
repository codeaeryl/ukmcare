<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Enums\RegistrationStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        
        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Please complete your patient profile first.');
        }

        $appointments = Registration::with(['schedule.doctor'])
            ->where('patient_id', $patient->id)
            ->latest()
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
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

    public function create()
    {
        $doctors = Doctor::where('is_active', true)->with('schedules')->get();
        return view('patient.appointments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'registration_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
        ]);

        $patient = auth()->user()->patient;
        $schedule = Schedule::findOrFail($request->schedule_id);

        $selectedDay = Carbon::parse($request->registration_date)->format('l');
        if (strcasecmp($selectedDay, $schedule->schedule_day->value) !== 0) {
            return back()->with('error', "This schedule is for {$schedule->schedule_day->value}s. You selected a {$selectedDay}. Please choose a date that falls on a {$schedule->schedule_day->value}.");
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
            return back()->with('error', 'The selected time slot is already booked. Please choose another one.');
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

        Registration::create([
            'patient_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'queue_number' => $queueNumber,
            'time_slot' => $request->time_slot,
            'status' => RegistrationStatus::REGISTERED,
            'registration_date' => $request->registration_date,
        ]);

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment booked successfully. Your queue number is #' . $queueNumber);
    }

    public function cancel(Registration $appointment)
    {
        if ($appointment->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        if ($appointment->status !== RegistrationStatus::REGISTERED) {
            return back()->with('error', 'Only registered appointments can be cancelled.');
        }

        $appointment->update(['status' => RegistrationStatus::CANCELLED]);

        return back()->with('success', 'Appointment cancelled successfully.');
    }
}
