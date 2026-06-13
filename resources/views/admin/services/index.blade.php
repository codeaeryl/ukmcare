@extends('layouts.master')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            Service Management
        </h2>
        <p class="text-sm text-gray-500 mt-1">Manage hospital services and their prices.</p>
    </div>
    
    <div class="flex items-center gap-3 w-full sm:w-auto">
        <a href="{{ route('admin.services.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium flex items-center justify-center gap-2 shadow-sm transition-all hover:shadow-md whitespace-nowrap">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Service
        </a>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl shadow-sm flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm flex items-center gap-3">
        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
        <p class="font-medium">{{ session('error') }}</p>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500">
                <tr>
                    <th class="px-6 py-4 font-medium">Service Name</th>
                    <th class="px-6 py-4 font-medium">Doctor</th>
                    <th class="px-6 py-4 font-medium">Description</th>
                    <th class="px-6 py-4 font-medium">Price</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($services as $service)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $service->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($service->doctor)
                                <div class="text-sm text-gray-800">{{ $service->doctor->full_name }}</div>
                                <div class="text-xs text-gray-500">{{ $service->doctor->specialist }}</div>
                            @else
                                <span class="text-gray-500 text-xs font-medium px-2 py-1 bg-gray-100 rounded-full">General Service</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-600 max-w-xs truncate">{{ $service->description ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Rp {{ number_format($service->price, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors tooltip" title="Edit service">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors tooltip" title="Delete service">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="briefcase-medical" class="w-8 h-8 text-gray-400"></i>
                                </div>
                                <p class="text-base font-medium text-gray-900 mb-1">No services found</p>
                                <p class="text-sm">There are currently no services added to the system.</p>
                                <a href="{{ route('admin.services.create') }}" class="mt-4 text-blue-600 hover:text-blue-700 font-medium text-sm">Add a new service</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($services->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
