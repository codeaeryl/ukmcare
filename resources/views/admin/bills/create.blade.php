@extends('layouts.master')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bills.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Bills
    </a>
    <h2 class="text-2xl font-semibold text-gray-800">Generate New Bill</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form action="{{ route('admin.bills.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Completed Visit</label>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse($registrations as $reg)
                    <label class="relative flex items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 cursor-pointer">
                        <input type="radio" name="registration_id" value="{{ $reg->id }}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                        <div class="ml-4 flex-1">
                            <div class="font-bold text-gray-800">{{ $reg->patient->full_name }}</div>
                            <div class="text-xs text-gray-500">Visit Date: {{ $reg->registration_date->format('d M Y') }}</div>
                        </div>
                    </label>
                @empty
                    <div class="text-center py-8 text-gray-500 italic">No completed visits pending for billing.</div>
                @endforelse
            </div>
        </div>

        @if($registrations->isNotEmpty())
            <div class="pt-4 flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">Generate Invoice</button>
            </div>
        @endif
    </form>
</div>
@endsection
