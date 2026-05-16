@extends('layouts.master')

@section('content')
<div class="mb-6">
    <a href="{{ route('patient.records.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Records
    </a>
    <h2 class="text-2xl font-semibold text-gray-800">Medical Record Detail</h2>
    <p class="text-sm text-gray-500">Recorded on {{ $record->record_date->format('l, d M Y') }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Diagnosis Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i data-lucide="clipboard-list" class="w-5 h-5 text-blue-600"></i>
            Diagnosis & Treatment
        </h3>
        <div class="space-y-4">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Doctor</p>
                <p class="text-gray-800 font-medium">{{ $record->doctor->full_name }}</p>
                <p class="text-xs text-gray-500">{{ $record->doctor->specialist }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Diagnosis</p>
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">
                    {{ $record->diagnosis }}
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Description</p>
                <p class="text-gray-600 text-sm">{{ $record->description }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Action Taken</p>
                <p class="text-gray-600 text-sm">{{ $record->action }}</p>
            </div>
        </div>
    </div>

    {{-- Prescriptions Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i data-lucide="pill" class="w-5 h-5 text-green-600"></i>
            Prescriptions
        </h3>
        @if($record->prescriptions->count() > 0)
            <div class="space-y-3">
                @foreach($record->prescriptions as $prescription)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $prescription->medicine->name }}</p>
                            <p class="text-xs text-gray-500">{{ $prescription->dosage }}</p>
                        </div>
                        <span class="text-sm font-semibold text-blue-600">x{{ $prescription->quantity }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">No prescriptions for this visit.</p>
        @endif
    </div>
</div>
@endsection
