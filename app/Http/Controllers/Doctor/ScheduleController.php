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
        $schedules = Schedule::where('doctor_id', $doctor->id)->latest()->paginate(10);
        return view('doctor.schedules.index', compact('schedules'));
    }

    public function verify(Request $request, Schedule $schedule)
    {
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
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
