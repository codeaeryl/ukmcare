@extends('layouts.master')

@section('content')
<div class="mb-6">
    <a href="{{ route('doctor.records.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 mb-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to Queue
    </a>
    <h2 class="text-2xl font-semibold text-gray-800">Patient Examination: {{ $registration->patient->full_name }}</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="lg:col-span-2">
        <form action="{{ route('doctor.records.store', $registration->id) }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Medical Notes</h3>
                <div class="space-y-4">
                    <div>
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">Diagnosis</label>
                        <textarea name="diagnosis" id="diagnosis" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required></textarea>
                    </div>
                    <div>
                        <label for="treatment" class="block text-sm font-medium text-gray-700 mb-1">Treatment / Actions</label>
                        <textarea name="treatment" id="treatment" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required></textarea>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="2" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" x-data="{ items: [] }">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-bold text-gray-800">Prescription</h3>
                    <button type="button" @click="items.push({ id: '', quantity: 1, instruction: '3x1 after meal' })" class="text-blue-600 hover:text-blue-700 text-xs font-bold flex items-center gap-1">
                        Add Medicine
                    </button>
                </div>
                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex flex-col md:flex-row gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <select :name="'medicines['+index+'][id]'" class="w-full rounded border-gray-300 text-sm py-1" required>
                                    <option value="">Select Medicine</option>
                                    @foreach($medicines as $med)
                                        <option value="{{ $med->id }}">{{ $med->name }} (Stock: {{ $med->stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-24">
                                <input type="number" :name="'medicines['+index+'][quantity]'" min="1" x-model="item.quantity" class="w-full rounded border-gray-300 text-sm py-1" required>
                            </div>
                            <div class="flex-1">
                                <input type="text" :name="'medicines['+index+'][instruction]'" x-model="item.instruction" class="w-full rounded border-gray-300 text-sm py-1">
                            </div>
                            <button type="button" @click="items.splice(index, 1)" class="text-red-500 hover:text-red-700">Delete</button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold shadow-lg transition-colors">
                    Save Examination Result
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
