<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Enums\RegistrationStatus;
use Illuminate\Http\Request;

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
        ]);

        $patient = auth()->user()->patient;
        $schedule = Schedule::findOrFail($request->schedule_id);

        $selectedDay = \Carbon\Carbon::parse($request->registration_date)->format('l');
        if ($selectedDay !== $schedule->schedule_day) {
            return back()->with('error', "This schedule is for {$schedule->schedule_day}s. You selected a {$selectedDay}. Please choose a date that falls on a {$schedule->schedule_day}.");
        }

        $count = Registration::where('schedule_id', $schedule->id)
            ->whereDate('registration_date', $request->registration_date)
            ->where('status', '!=', RegistrationStatus::CANCELLED)
            ->count();

        if ($count >= $schedule->quota) {
            return back()->with('error', 'The quota for this doctor on selected date is full.');
        }

        $queueNumber = $count + 1;

        Registration::create([
            'patient_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'queue_number' => $queueNumber,
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
