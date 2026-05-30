@extends('layouts.master')

@section('content')
<div class="mb-6">
    <a href="{{ route('pharmacist.medicines.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Inventory
    </a>
    <h2 class="text-2xl font-semibold text-gray-800">Add New Medicine</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form action="{{ route('pharmacist.medicines.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Medicine Name</label>
            <input type="text" name="name" id="name" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Amount</label>
                <input type="number" name="stock" id="stock" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                <input type="text" name="unit" id="unit" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
            </div>
        </div>
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price per Unit (Rp)</label>
            <input type="number" name="price" id="price" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        <div class="pt-4 flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                Save Medicine
            </button>
        </div>
    </form>
</div>
@endsection
