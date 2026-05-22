<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Log;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        $schedules = Schedule::where('doctor_id', $doctor->id)->latest()->paginate(10);
        return view('doctor.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('doctor.schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_day' => 'required|string',
            'start_hour' => 'required',
            'end_hour' => 'required',
        ]);

        $doctor = auth()->user()->doctor;

        $start = \Carbon\Carbon::parse($request->start_hour);
        $end = \Carbon\Carbon::parse($request->end_hour);
        $quota = intval($start->diffInMinutes($end) / 20);

        $data = $request->all();
        $data['doctor_id'] = $doctor->id;
        $data['quota'] = $quota;

        $schedule = Schedule::create($data);

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Added new schedule on ' . $schedule->schedule_day,
            'date' => now(),
        ]);

        return redirect()->route('doctor.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        return view('doctor.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'schedule_day' => 'required|string',
            'start_hour' => 'required',
            'end_hour' => 'required',
        ]);

        $start = \Carbon\Carbon::parse($request->start_hour);
        $end = \Carbon\Carbon::parse($request->end_hour);
        $quota = intval($start->diffInMinutes($end) / 20);

        $data = $request->all();
        $data['quota'] = $quota;

        $schedule->update($data);

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated schedule on ' . $schedule->schedule_day,
            'date' => now(),
        ]);

        return redirect()->route('doctor.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $day = $schedule->schedule_day;
        $schedule->delete();

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Deleted schedule on ' . $day,
            'date' => now(),
        ]);
        
        return redirect()->route('doctor.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
