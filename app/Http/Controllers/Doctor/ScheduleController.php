<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        if (!$doctor) {
            return redirect()->route('dashboard')->with('error', 'Doctor profile not found. Please contact the administrator.');
        }
        $schedules = Schedule::where('doctor_id', $doctor->id)->latest()->paginate(10);
        return view('doctor.schedules.index', compact('schedules'));
    }

    public function verify(Request $request, Schedule $schedule)
    {
        $doctor = auth()->user()->doctor;
        if (!$doctor || $schedule->doctor_id !== $doctor->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $schedule->update([
            'status' => $request->status,
        ]);

        return redirect()->route('doctor.schedules.index')->with('success', 'Schedule status updated successfully.');
    }
}
