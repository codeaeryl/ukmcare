@extends('layouts.master')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            Appointments List
        </h2>
        <p class="text-sm text-gray-500 mt-1">View and manage patient clinic bookings.</p>
    </div>
    <a href="{{ route('receptionist.appointments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm transition-all hover:shadow-md">
        <i data-lucide="plus" class="w-4 h-4"></i>
        New Walk-in Appointment
    </a>
</div>

<!-- Search and Filter Panel -->
<div class="mb-6 bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
    <form action="{{ route('receptionist.appointments.index') }}" method="GET" class="w-full flex flex-col md:flex-row gap-3">
        <div class="relative flex-1">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by patient name or ID..." class="pl-9 w-full border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-700">
        </div>
        <div class="w-full md:w-48">
            <input type="date" name="date" value="{{ request('date') }}" class="w-full border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-700">
        </div>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-1.5 rounded-lg text-sm font-medium transition-colors">
            Filter
        </button>
        @if(request('search') || request('date'))
            <a href="{{ route('receptionist.appointments.index') }}" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors flex items-center">
                Clear
            </a>
        @endif
    </form>
</div>

@if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl shadow-sm flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm flex items-center gap-3">
        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
        <p class="font-medium">{{ session('error') }}</p>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500">
                <tr>
                    <th class="px-6 py-4 font-medium">Patient</th>
                    <th class="px-6 py-4 font-medium">Doctor / Schedule</th>
                    <th class="px-6 py-4 font-medium">Appointment Date</th>
                    <th class="px-6 py-4 font-medium">Time Slot</th>
                    <th class="px-6 py-4 font-medium">Queue No</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($appointments as $app)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $app->patient->full_name }}
                            <div class="text-xs text-gray-500">MRN: {{ $app->patient->id }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $app->schedule->doctor->full_name }}
                            <div class="text-xs text-gray-500">{{ $app->schedule->doctor->specialist }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $app->registration_date->format('l, d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $app->time_slot }}
                        </td>
                        <td class="px-6 py-4 text-blue-600 font-bold text-lg">
                            #{{ $app->queue_number }}
                        </td>
                        <td class="px-6 py-4">
                            @php $statusVal = $app->status->value ?? $app->status; @endphp
                            <span class="px-2.5 py-1 text-xs font-medium rounded-md inline-flex items-center gap-1.5
                                {{ $statusVal === 'completed' ? 'bg-green-50 text-green-700 border border-green-100' : '' }}
                                {{ $statusVal === 'cancelled' ? 'bg-red-50 text-red-700 border border-red-100' : '' }}
                                {{ $statusVal === 'pending' ? 'bg-yellow-50 text-yellow-700 border border-yellow-100' : '' }}
                                {{ $statusVal === 'registered' ? 'bg-blue-50 text-blue-700 border border-blue-100' : '' }}
                            ">
                                <span class="w-1.5 h-1.5 rounded-full
                                    {{ $statusVal === 'completed' ? 'bg-green-500' : '' }}
                                    {{ $statusVal === 'cancelled' ? 'bg-red-500' : '' }}
                                    {{ $statusVal === 'pending' ? 'bg-yellow-500' : '' }}
                                    {{ $statusVal === 'registered' ? 'bg-blue-500' : '' }}
                                "></span>
                                {{ ucfirst($statusVal) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                @if($statusVal === 'registered')
                                    <form action="{{ route('receptionist.appointments.cancel', $app->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-semibold transition-colors">
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs italic">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="calendar" class="w-8 h-8 text-gray-400"></i>
                                </div>
                                <p class="text-base font-medium text-gray-900 mb-1">No appointments found</p>
                                <p class="text-sm">No bookings match the current filter criteria.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($appointments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $appointments->links() }}
        </div>
    @endif
</div>
@endsection
