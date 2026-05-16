@extends('layouts.master')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">My Bills</h2>
    <p class="text-sm text-gray-500">View your billing history and payment status.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Doctor</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium text-right">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($bills as $bill)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-600">
                            {{ $bill->date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $bill->registration->schedule->doctor->full_name ?? '-' }}
                            <div class="text-xs text-gray-500">{{ $bill->registration->schedule->doctor->specialist ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @php $statusVal = $bill->status->value ?? $bill->status; @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $statusVal === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $statusVal === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $statusVal === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                            ">
                                {{ ucfirst($statusVal) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('patient.bills.show', $bill->id) }}" class="px-3 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-xs font-medium transition-colors">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No bills found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
