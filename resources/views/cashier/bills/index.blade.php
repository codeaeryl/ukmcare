@extends('layouts.master')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">Billing Management</h2>
    </div>
    <a href="{{ route('cashier.bills.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
        Generate New Bill
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Bill No.</th>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Patient</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($bills as $bill)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">#BILL-{{ $bill->id }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $bill->date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-800">{{ $bill->registration->patient->full_name }}</td>
                        <td class="px-6 py-4">
                            @php $statusVal = $bill->status->value ?? $bill->status; @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusVal === 'complete' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($statusVal) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex justify-end gap-2">
                            <a href="{{ route('cashier.bills.show', $bill->id) }}" class="px-2 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-xs font-medium transition-colors">View Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No bills found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
