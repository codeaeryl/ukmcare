@extends('layouts.master')

@section('content')
<div class="mb-6">
    <a href="{{ route('doctor.records.history') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to History
    </a>
    <h2 class="text-2xl font-semibold text-gray-800">Medical Record Details</h2>
    <p class="text-sm text-gray-500">Date: {{ $record->created_at->format('d M Y') }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Diagnosis & Action</h3>
            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Diagnosis</span>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded-lg">{{ $record->diagnosis }}</p>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Action / Treatment</span>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded-lg">{{ $record->action }}</p>
                </div>
                @if($record->description)
                <div>
                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Doctor's Notes</span>
                    <p class="text-gray-800 bg-gray-50 p-3 rounded-lg">{{ $record->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Prescribed Medicines</h3>
            @if($record->prescriptions->count() > 0)
                <div class="space-y-3">
                    @foreach($record->prescriptions as $prescription)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div>
                            <p class="font-medium text-gray-800">{{ $prescription->medicine->name }}</p>
                            <p class="text-sm text-gray-500">Dosage: {{ $prescription->dosage }}</p>
                        </div>
                        <span class="text-sm font-semibold bg-white border px-3 py-1 rounded-full text-blue-600">Qty: {{ $prescription->quantity }}</span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 italic">No medicines prescribed.</p>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Patient Profile</h3>
            <div class="space-y-3">
                <div>
                    <span class="block text-xs text-gray-500">Full Name</span>
                    <p class="font-medium text-gray-800">{{ $record->registration->patient->full_name }}</p>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Gender / Age</span>
                    <p class="font-medium text-gray-800">{{ ucfirst($record->registration->patient->gender->value ?? $record->registration->patient->gender) }}, {{ \Carbon\Carbon::parse($record->registration->patient->dob)->age }} years</p>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Blood Type</span>
                    <p class="font-medium text-gray-800">{{ $record->registration->patient->blood_type ?? '-' }}</p>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Allergies</span>
                    <p class="font-medium text-red-600">{{ $record->registration->patient->allergies ?? 'None' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
