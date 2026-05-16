@extends('layouts.master')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bills.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Bills
    </a>
    <h2 class="text-2xl font-semibold text-gray-800">Bill Detail #BILL-{{ $bill->id }}</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 overflow-hidden relative">
            @if(($bill->status->value ?? $bill->status) === 'complete')
                <div class="absolute top-10 right-10 border-4 border-green-500 text-green-500 px-4 py-1 font-black text-3xl rotate-12 opacity-50 uppercase">PAID</div>
            @endif

            <table class="w-full text-sm mb-8">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="py-2 text-left font-bold text-gray-700">Description</th>
                        <th class="py-2 text-center font-bold text-gray-700">Qty</th>
                        <th class="py-2 text-right font-bold text-gray-700">Price</th>
                        <th class="py-2 text-right font-bold text-gray-700">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($bill->billServices as $bs)
                        <tr>
                            <td class="py-3">{{ $bs->service->name }}</td>
                            <td class="py-3 text-center">{{ $bs->quantity }}</td>
                            <td class="py-3 text-right">{{ number_format($bs->price, 0, ',', '.') }}</td>
                            <td class="py-3 text-right font-medium">{{ number_format($bs->quantity * $bs->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    @foreach($bill->billMedicines as $bm)
                        <tr>
                            <td class="py-3">{{ $bm->medicine->name }}</td>
                            <td class="py-3 text-center">{{ $bm->quantity }}</td>
                            <td class="py-3 text-right">{{ number_format($bm->price, 0, ',', '.') }}</td>
                            <td class="py-3 text-right font-medium">{{ number_format($bm->quantity * $bm->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-800">
                        <td colspan="3" class="py-4 text-right font-bold text-gray-800 text-lg">Grand Total</td>
                        <td class="py-4 text-right font-bold text-blue-600 text-xl">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        @if(($bill->status->value ?? $bill->status) === 'pending')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Process Payment</h3>
                <form action="{{ route('admin.bills.pay', $bill->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <select name="payment_method" class="w-full rounded-lg border-gray-300 text-sm" required>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div>
                        <input type="number" name="amount_paid" value="{{ $grandTotal }}" class="w-full rounded-lg border-gray-300 text-sm" required>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold shadow-md">Mark as Paid</button>
                </form>
            </div>
        @else
            <div class="bg-green-50 rounded-xl border border-green-200 p-6">
                <h3 class="font-bold text-green-800 mb-2">Paid Successfully</h3>
            </div>
        @endif
    </div>
</div>
@endsection
