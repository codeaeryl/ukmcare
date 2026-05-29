@extends('layouts.master')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            My Appointments
        </h2>
        <p class="text-sm text-gray-500">View and manage your doctor visits.</p>
    </div>
    @if (!$hasActiveAppointment)
    <a href="{{ route('patient.appointments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Book Appointment
    </a>
    @endif
</div>

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
        <p>{{ session('success') }}</p>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Doctor</th>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Queue No.</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($appointments as $app)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $app->schedule->doctor->full_name }}
                            <div class="text-xs text-gray-500">{{ $app->schedule->doctor->specialist }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $app->registration_date->format('l, d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-blue-600 font-bold text-lg">#{{ $app->queue_number }}</td>
                        <td class="px-6 py-4">
                            @php $statusVal = $app->status->value ?? $app->status; @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $statusVal === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $statusVal === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $statusVal === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $statusVal === 'registered' ? 'bg-blue-100 text-blue-700' : '' }}
                            ">
                                {{ ucfirst($statusVal) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex justify-end gap-2">
                            @if($statusVal === 'registered')
                                <form action="{{ route('patient.appointments.cancel', $app->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded text-xs font-medium transition-colors">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No appointments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
