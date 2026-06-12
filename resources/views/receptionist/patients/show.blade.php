@extends('layouts.master')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            Patient Information
        </h2>
        <p class="text-sm text-gray-500">View complete patient details and appointment history.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('receptionist.patients.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Back
        </a>
        <a href="{{ route('receptionist.patients.edit', $patient->id) }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
            <i data-lucide="edit" class="w-4 h-4"></i>
            Edit Patient
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Account details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium text-gray-800">{{ $patient->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-800">{{ $patient->user->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Registered At</p>
                    <p class="font-medium text-gray-800">{{ $patient->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-1 lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-800">Identity Details</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><p class="text-sm text-gray-500">MRN</p><p class="font-medium text-gray-800">{{ $patient->id }}</p></div>
                <div><p class="text-sm text-gray-500">NIK</p><p class="font-medium text-gray-800">{{ $patient->nik }}</p></div>
                <div><p class="text-sm text-gray-500">Place & Date of Birth</p><p class="font-medium text-gray-800">{{ $patient->pob ? $patient->pob . ', ' : '' }}{{ $patient->dob->format('d M Y') }}</p></div>
                <div><p class="text-sm text-gray-500">Gender</p><p class="font-medium text-gray-800">{{ ucfirst($patient->gender->value ?? $patient->gender) }}</p></div>
                <div><p class="text-sm text-gray-500">Blood Type</p><p class="font-medium text-gray-800">{{ $patient->blood_type ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">BPJS Number</p><p class="font-medium text-gray-800">{{ $patient->bpjs_number ?? '-' }}</p></div>
                <div><p class="text-sm text-gray-500">Phone</p><p class="font-medium text-gray-800">{{ $patient->phone ?? '-' }}</p></div>
                <div class="md:col-span-2"><p class="text-sm text-gray-500">Address</p><p class="font-medium text-gray-800">{{ $patient->address ?? '-' }}</p></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-800">Visit & Appointment History</h3>
            </div>
            <div class="p-6">
                @if($patient->registrations->isEmpty())
                    <p class="text-gray-500 text-center py-4">No visit history recorded for this patient.</p>
                @else
                    <div class="space-y-4">
                        @foreach($patient->registrations->sortByDesc('registration_date') as $registration)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-semibold text-gray-800">Visit Date: {{ $registration->registration_date->format('d M Y') }} ({{ $registration->time_slot }})</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">{{ ucfirst($registration->status->value ?? $registration->status) }}</span>
                                </div>
                                <p class="text-gray-600 text-sm">Doctor: {{ $registration->schedule->doctor->full_name ?? 'N/A' }}</p>
                                @if($registration->medicalRecord)
                                    <p class="text-gray-600 text-sm font-medium mt-1">Diagnosis: {{ $registration->medicalRecord->diagnosis ?? 'N/A' }}</p>
                                @else
                                    <p class="text-gray-500 text-sm italic mt-1">No medical record yet.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
