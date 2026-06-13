@extends('layouts.master')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.services.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-500">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Add New Service</h2>
            <p class="text-sm text-gray-500 mt-1">Create a new service with its pricing details.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.services.store') }}" method="POST" class="p-6 md:p-8">
            @csrf

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Service Name</label>
                    <input type="text" name="name" id="name" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('name') border-red-500 @enderror" value="{{ old('name') }}" required autofocus placeholder="e.g. Consultation Fee">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Doctor Select -->
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">Associated Doctor (Optional)</label>
                    <select name="doctor_id" id="doctor_id" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('doctor_id') border-red-500 @enderror">
                        <option value="">-- General Service (No Specific Doctor) --</option>
                        @foreach($doctors as $doc)
                            <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                                {{ $doc->full_name }} ({{ $doc->specialist }})
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">If this service is a consultation fee for a specific doctor, select them here.</p>
                    @error('doctor_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (Rp)</label>
                    <div class="flex rounded-xl shadow-sm">
                        <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 font-medium sm:text-sm">
                            Rp
                        </span>
                        <input type="number" name="price" id="price" min="0" step="1" class="flex-1 min-w-0 block w-full rounded-none rounded-r-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition-colors @error('price') z-10 border-red-500 @enderror" value="{{ old('price') }}" required placeholder="50000">
                    </div>
                    @error('price')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('description') border-red-500 @enderror" placeholder="Describe the service...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.services.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-sm transition-colors">
                    Save Service
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
