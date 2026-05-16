@extends('layouts.master')

@section('content')
<div class="mb-6">
    <a href="{{ route('patient.appointments.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to My Appointments
    </a>
    <h2 class="text-2xl font-semibold text-gray-800">Book New Appointment</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-4xl">
    <form action="{{ route('patient.appointments.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor & Schedule</label>
                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                    @foreach($doctors as $doctor)
                        @foreach($doctor->schedules as $schedule)
                            <label class="relative flex items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all">
                                <input type="radio" name="schedule_id" value="{{ $schedule->id }}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                                <div class="ml-4 flex-1">
                                    <div class="font-bold text-gray-800">{{ $doctor->full_name }}</div>
                                    <div class="text-xs text-gray-500 mb-1">{{ $doctor->specialist }}</div>
                                    <div class="flex items-center gap-2 text-xs font-medium text-blue-600">
                                        <i data-lucide="calendar-days" class="w-3 h-3"></i>
                                        {{ $schedule->schedule_day }} ({{ $schedule->start_hour }} - {{ $schedule->end_hour }})
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="registration_date" class="block text-sm font-medium text-gray-700 mb-2">Appointment Date</label>
                    <input type="date" name="registration_date" id="registration_date" min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-bold shadow-md transition-colors">
                        Confirm Appointment
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
