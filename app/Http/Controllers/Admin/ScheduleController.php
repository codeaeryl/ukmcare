<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Log;
use App\Enums\DayName;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Carbon\Carbon;

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
            'schedule_day' => ['required', new Enum(DayName::class)],
            'start_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
        ]);

        $overlapExists = Schedule::where('doctor_id', $request->doctor_id)
            ->where('schedule_day', $request->schedule_day)
            ->where(function ($query) use ($request) {
                $query->where('start_hour', '<', $request->end_hour)
                      ->where('end_hour', '>', $request->start_hour);
            })
            ->exists();

        if ($overlapExists) {
            return back()->withErrors(['start_hour' => 'The schedule overlaps with an existing schedule for this doctor on the selected day.'])->withInput();
        }

        $start = Carbon::createFromFormat('H:i', $request->start_hour);
        $end = Carbon::createFromFormat('H:i', $request->end_hour);
        $quota = intval($start->diffInMinutes($end) / 20);

        $data = $request->all();
        $data['quota'] = $quota;

        $schedule = Schedule::create($data);

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
            'schedule_day' => ['required', new Enum(DayName::class)],
            'start_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
        ]);

        $overlapExists = Schedule::where('doctor_id', $request->doctor_id)
            ->where('schedule_day', $request->schedule_day)
            ->where('id', '!=', $schedule->id)
            ->where(function ($query) use ($request) {
                $query->where('start_hour', '<', $request->end_hour)
                      ->where('end_hour', '>', $request->start_hour);
            })
            ->exists();

        if ($overlapExists) {
            return back()->withErrors(['start_hour' => 'The schedule overlaps with an existing schedule for this doctor on the selected day.'])->withInput();
        }

        $start = Carbon::createFromFormat('H:i', $request->start_hour);
        $end = Carbon::createFromFormat('H:i', $request->end_hour);
        $quota = intval($start->diffInMinutes($end) / 20);

        $data = $request->all();
        $data['quota'] = $quota;

        $schedule->update($data);

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
