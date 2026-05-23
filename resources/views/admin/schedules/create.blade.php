@use('App\Enums\DayName')
@extends('layouts.master')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.schedules.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Schedules
    </a>
    <h2 class="text-2xl font-semibold text-gray-800">Add New Schedule</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form action="{{ route('admin.schedules.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <div>
            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1">Doctor</label>
            <select name="doctor_id" id="doctor_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                <option value="">Select Doctor</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>{{ $doctor->full_name }} ({{ $doctor->specialist }})</option>
                @endforeach
            </select>
            @error('doctor_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="schedule_day" class="block text-sm font-medium text-gray-700 mb-1">Day</label>
            <select name="schedule_day" id="schedule_day" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                @foreach(DayName::cases() as $day)
                    <option value="{{ $day->value }}" @selected(old('schedule_day') == $day->value)>{{ $day->value }}</option>
                @endforeach
            </select>
            @error('schedule_day') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="start_hour" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                <input type="time" name="start_hour" id="start_hour" value="{{ old('start_hour') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                @error('start_hour') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="end_hour" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                <input type="time" name="end_hour" id="end_hour" value="{{ old('end_hour') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                @error('end_hour') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="bg-blue-50 text-blue-800 p-4 rounded-lg text-sm flex items-start gap-3">
            <i data-lucide="info" class="w-5 h-5 shrink-0"></i>
            <p>Patient quota is automatically calculated based on the 20-minute interval between Start Time and End Time.</p>
        </div>

        <div class="pt-4 flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                Save Schedule
            </button>
        </div>
    </form>
</div>
@endsection
