@extends('layouts.master')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            Welcome, {{ auth()->user()->name }}!
        </h2>
        <p class="text-sm text-gray-500">Here is an overview of your medical records and appointments.</p>
    </div>
</div>

@if(isset($missingProfile) && $missingProfile)
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 text-center text-orange-700">
        <i data-lucide="alert-circle" class="w-12 h-12 mx-auto mb-3 text-orange-400"></i>
        <h3 class="text-lg font-medium mb-1">Incomplete Profile</h3>
        <p class="text-sm">Your patient identity data is not fully configured yet. Please contact the administrator to complete your registration.</p>
    </div>
@else
    <!-- Patient Info Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 flex flex-col md:flex-row items-center gap-6">
        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 text-xl font-bold">
            {{ strtoupper(substr($patient->full_name, 0, 2)) }}
        </div>
        <div class="flex-1">
            <h3 class="text-xl font-bold text-gray-800">{{ $patient->full_name }}</h3>
            <p class="text-sm text-gray-500">MRN: <span class="font-medium text-gray-700">{{ $patient->id }}</span></p>
        </div>
        <div class="flex gap-4">
            <div class="text-right">
                <p class="text-sm text-gray-500">Blood Type</p>
                <p class="font-bold text-red-500">{{ $patient->blood_type ?? '-' }}</p>
            </div>
            <div class="w-px h-10 bg-gray-200 mx-2"></div>
            <div class="text-left">
                <p class="text-sm text-gray-500">BPJS</p>
                @if($patient->bpjs_status === 'verified')
                    <p class="font-medium text-green-600"><i data-lucide="check-circle" class="w-4 h-4 inline"></i> Verified</p>
                @elseif($patient->bpjs_status === 'pending')
                    <p class="font-medium text-yellow-600"><i data-lucide="clock" class="w-4 h-4 inline"></i> Pending</p>
                @elseif($patient->bpjs_status === 'rejected')
                    <p class="font-medium text-red-600"><i data-lucide="x-circle" class="w-4 h-4 inline"></i> Rejected</p>
                @else
                    <p class="font-medium text-gray-800">Not Registered</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <i data-lucide="activity" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Visits</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalVisits }}</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 xl:col-span-2">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Next Appointment</p>
                @if($upcomingAppointment)
                    <h3 class="text-lg font-bold text-gray-800">{{ $upcomingAppointment->registration_date->format('d M Y') }}, {{ \Carbon\Carbon::parse($upcomingAppointment->schedule->start_hour)->format('H:i') }}</h3>
                    <p class="text-sm text-blue-600">Queue: #{{ $upcomingAppointment->queue_number }}</p>
                @else
                    <h3 class="text-lg font-bold text-gray-800">No Upcoming Appointments</h3>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent History Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Recent Medical History</h3>
        </div>
        <div class="overflow-x-auto">
            @if($patient->registrations->isEmpty())
                <div class="px-6 py-8 text-center text-gray-500">
                    You have no previous visits recorded.
                </div>
            @else
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">Date</th>
                            <th class="px-6 py-3 font-medium">Doctor</th>
                            <th class="px-6 py-3 font-medium">Diagnosis</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($patient->registrations as $reg)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-800">{{ $reg->registration_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $reg->schedule && $reg->schedule->doctor ? $reg->schedule->doctor->full_name : 'General Checkup' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $reg->medicalRecord ? $reg->medicalRecord->diagnosis : 'Pending / Not specified' }}
                            </td>
                            <td class="px-6 py-4">
                                @php $statusVal = $reg->status->value ?? $reg->status; @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $statusVal === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $statusVal === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ !in_array($statusVal, ['completed', 'cancelled']) ? 'bg-blue-100 text-blue-700' : '' }}
                                ">
                                    {{ ucfirst($statusVal) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endif
@endsection
