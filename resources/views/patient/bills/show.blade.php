@extends('layouts.master')

@section('content')
<div class="mb-6">
    <a href="{{ route('patient.bills.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Bills
    </a>
    <h2 class="text-2xl font-semibold text-gray-800">Bill Detail</h2>
    <p class="text-sm text-gray-500">Billed on {{ $bill->date->format('l, d M Y') }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Bill Info --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
            Bill Information
        </h3>
        <div class="space-y-4">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Doctor</p>
                <p class="text-gray-800 font-medium">{{ $bill->registration->schedule->doctor->full_name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Visit Date</p>
                <p class="text-gray-600">{{ $bill->registration->registration_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                @php $statusVal = $bill->status->value ?? $bill->status; @endphp
                <span class="px-2 py-1 text-xs font-medium rounded-full
                    {{ $statusVal === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $statusVal === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $statusVal === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                ">
                    {{ ucfirst($statusVal) }}
                </span>
            </div>
            @if($bill->payment)
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Payment Method</p>
                <p class="text-gray-600">{{ ucfirst($bill->payment->payment_method) }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Amount Paid</p>
                <p class="text-gray-800 font-semibold text-lg">Rp {{ number_format($bill->payment->paid_amount, 0, ',', '.') }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Services --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i data-lucide="stethoscope" class="w-5 h-5 text-purple-600"></i>
            Services
        </h3>
        @if($bill->services->count() > 0)
            <div class="space-y-3">
                @foreach($bill->services as $service)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $service->name }}</p>
                            <p class="text-xs text-gray-500">x{{ $service->pivot->quantity }}</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Rp {{ number_format($service->pivot->price, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">No services billed.</p>
        @endif
    </div>

    {{-- Medicines --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i data-lucide="pill" class="w-5 h-5 text-green-600"></i>
            Medicines
        </h3>
        @if($bill->medicines->count() > 0)
            <div class="space-y-3">
                @foreach($bill->medicines as $medicine)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $medicine->name }}</p>
                            <p class="text-xs text-gray-500">x{{ $medicine->pivot->quantity }}</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Rp {{ number_format($medicine->pivot->price, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">No medicines billed.</p>
        @endif
    </div>
</div>
@endsection
