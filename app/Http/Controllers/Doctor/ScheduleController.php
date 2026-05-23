<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Log;
use App\Enums\DayName;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Carbon\Carbon;

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
            'schedule_day' => ['required', new Enum(DayName::class)],
            'start_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
        ]);

        $doctor = auth()->user()->doctor;

        $overlapExists = Schedule::where('doctor_id', $doctor->id)
            ->where('schedule_day', $request->schedule_day)
            ->where(function ($query) use ($request) {
                $query->where('start_hour', '<', $request->end_hour)
                      ->where('end_hour', '>', $request->start_hour);
            })
            ->exists();

        if ($overlapExists) {
            return back()->withErrors(['start_hour' => 'The schedule overlaps with one of your existing schedules on the selected day.'])->withInput();
        }

        $start = Carbon::createFromFormat('H:i', $request->start_hour);
        $end = Carbon::createFromFormat('H:i', $request->end_hour);
        $quota = intval($start->diffInMinutes($end) / 20);

        $data = $request->except('doctor_id');
        $data['doctor_id'] = $doctor->id;
        $data['quota'] = $quota;

        $schedule = Schedule::create($data);

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Added new schedule on ' . $schedule->schedule_day->value,
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
            'schedule_day' => ['required', new Enum(DayName::class)],
            'start_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
        ]);

        $overlapExists = Schedule::where('doctor_id', $schedule->doctor_id)
            ->where('schedule_day', $request->schedule_day)
            ->where('id', '!=', $schedule->id)
            ->where(function ($query) use ($request) {
                $query->where('start_hour', '<', $request->end_hour)
                      ->where('end_hour', '>', $request->start_hour);
            })
            ->exists();

        if ($overlapExists) {
            return back()->withErrors(['start_hour' => 'The schedule overlaps with one of your existing schedules on the selected day.'])->withInput();
        }

        $start = Carbon::createFromFormat('H:i', $request->start_hour);
        $end = Carbon::createFromFormat('H:i', $request->end_hour);
        $quota = intval($start->diffInMinutes($end) / 20);

        $data = $request->except('doctor_id');
        $data['quota'] = $quota;

        $schedule->update($data);

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Updated schedule on ' . $schedule->schedule_day->value,
            'date' => now(),
        ]);

        return redirect()->route('doctor.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $day = $schedule->schedule_day->value;
        $schedule->delete();

        Log::create([
            'user_id' => auth()->id(),
            'activity' => 'Deleted schedule on ' . $day,
            'date' => now(),
        ]);

        return redirect()->route('doctor.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
