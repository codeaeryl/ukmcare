<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Log;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('doctor')->latest()->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $doctors = Doctor::where('is_active', true)->get();
        return view('admin.schedules.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_day' => 'required|string',
            'start_hour' => 'required',
            'end_hour' => 'required',
            'quota' => 'required|integer|min:1',
        ]);

        $schedule = Schedule::create($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Added new schedule for Doctor ID ' . $schedule->doctor_id,
            'date' => now(),
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        $doctors = Doctor::where('is_active', true)->get();
        return view('admin.schedules.edit', compact('schedule', 'doctors'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_day' => 'required|string',
            'start_hour' => 'required',
            'end_hour' => 'required',
            'quota' => 'required|integer|min:1',
        ]);

        $schedule->update($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated schedule for Doctor ID ' . $schedule->doctor_id,
            'date' => now(),
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $doctorId = $schedule->doctor_id;
        $schedule->delete();

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Deleted schedule for Doctor ID ' . $doctorId,
            'date' => now(),
        ]);
        
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
