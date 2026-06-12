@extends('layouts.master')

@section('content')
<div class="mb-6">
    <a href="{{ route('receptionist.appointments.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Appointments List
    </a>
    <h2 class="text-2xl font-semibold text-gray-800">Schedule Walk-In Appointment</h2>
</div>

@if (session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm flex items-center gap-3">
        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
        <p class="font-medium">{{ session('error') }}</p>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-5xl">
    <form action="{{ route('receptionist.appointments.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="mb-4">
            <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Select Patient</label>
            <select name="patient_id" id="patient_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">-- Select Patient --</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" {{ (old('patient_id') == $patient->id || $selectedPatientId == $patient->id) ? 'selected' : '' }}>
                        {{ $patient->full_name }} (MRN: {{ $patient->id }} - NIK: {{ $patient->nik }})
                    </option>
                @endforeach
            </select>
            @error('patient_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor & Schedule</label>
                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                    @foreach($doctors as $doctor)
                        @foreach($doctor->schedules as $schedule)
                            <label class="relative flex items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all">
                                <input type="radio" name="schedule_id" value="{{ $schedule->id }}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" {{ old('schedule_id') == $schedule->id ? 'checked' : '' }} required>
                                <div class="ml-4 flex-1">
                                    <div class="font-bold text-gray-800">{{ $doctor->full_name }}</div>
                                    <div class="text-xs text-gray-500 mb-1">{{ $doctor->specialist }}</div>
                                    <div class="flex items-center gap-2 text-xs font-medium text-blue-600">
                                        <i data-lucide="calendar-days" class="w-3 h-3"></i>
                                        {{ $schedule->schedule_day->value ?? $schedule->schedule_day }} ({{ $schedule->start_hour }} - {{ $schedule->end_hour }})
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    @endforeach
                </div>
                @error('schedule_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-4">
                <div>
                    <label for="registration_date" class="block text-sm font-medium text-gray-700 mb-2">Appointment Date</label>
                    <input type="date" name="registration_date" id="registration_date" min="{{ date('Y-m-d') }}" value="{{ old('registration_date') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    @error('registration_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div id="time_slots_container" class="hidden mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Time Slot</label>
                    <div id="time_slots_list" class="grid grid-cols-3 gap-2">
                        <!-- Slots will be rendered here by JS -->
                    </div>
                    <input type="hidden" name="time_slot" id="selected_time_slot" value="{{ old('time_slot') }}" required>
                    @error('time_slot') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scheduleRadios = document.querySelectorAll('input[name="schedule_id"]');
    const dateInput = document.getElementById('registration_date');
    const slotsContainer = document.getElementById('time_slots_container');
    const slotsList = document.getElementById('time_slots_list');
    const selectedSlotInput = document.getElementById('selected_time_slot');

    function fetchSlots() {
        const selectedSchedule = document.querySelector('input[name="schedule_id"]:checked');
        const selectedDate = dateInput.value;

        if (selectedSchedule && selectedDate) {
            slotsContainer.classList.remove('hidden');
            slotsList.innerHTML = '<p class="text-gray-500 col-span-3 text-sm">Loading available slots...</p>';
            selectedSlotInput.value = '';

            fetch(`{{ route('receptionist.appointments.available-slots') }}?schedule_id=${selectedSchedule.value}&date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    slotsList.innerHTML = '';
                    if (data.available_slots.length === 0) {
                        slotsList.innerHTML = '<p class="text-red-500 col-span-3 text-sm">No available slots for this date.</p>';
                        return;
                    }

                    data.available_slots.forEach(slot => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'slot-btn px-3 py-2 border border-blue-200 rounded-lg text-sm text-blue-700 hover:bg-blue-50 transition-colors';
                        btn.textContent = slot;
                        btn.onclick = () => selectSlot(btn, slot);
                        slotsList.appendChild(btn);
                    });
                })
                .catch(err => {
                    slotsList.innerHTML = '<p class="text-red-500 col-span-3 text-sm">Error loading slots.</p>';
                });
        } else {
            slotsContainer.classList.add('hidden');
            selectedSlotInput.value = '';
        }
    }

    function selectSlot(btn, slot) {
        document.querySelectorAll('.slot-btn').forEach(b => {
            b.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            b.classList.add('text-blue-700', 'hover:bg-blue-50');
        });
        btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
        btn.classList.remove('text-blue-700', 'hover:bg-blue-50');
        selectedSlotInput.value = slot;
    }

    scheduleRadios.forEach(radio => radio.addEventListener('change', fetchSlots));
    dateInput.addEventListener('change', fetchSlots);
});
</script>
@endsection
