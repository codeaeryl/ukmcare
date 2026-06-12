@extends('layouts.master')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            Patient Registration
        </h2>
        <p class="text-sm text-gray-500 mt-1">Manage patients and schedule walk-ins.</p>
    </div>
    <a href="{{ route('receptionist.patients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm transition-all hover:shadow-md">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        Register New Patient
    </a>
</div>

<!-- Search and Filters -->
<div class="mb-6 bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
    <form action="{{ route('receptionist.patients.index') }}" method="GET" class="w-full md:w-96 flex gap-2">
        <div class="relative flex-1">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, NIK, or MRN..." class="pl-9 w-full border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-700">
        </div>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-1.5 rounded-lg text-sm font-medium transition-colors">
            Search
        </button>
        @if(request('search'))
            <a href="{{ route('receptionist.patients.index') }}" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors flex items-center">
                Clear
            </a>
        @endif
    </form>
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
                    <th class="px-6 py-4 font-medium">Patient Info</th>
                    <th class="px-6 py-4 font-medium">MRN / ID</th>
                    <th class="px-6 py-4 font-medium">NIK</th>
                    <th class="px-6 py-4 font-medium">Phone</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($patients as $patient)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-green-100 to-emerald-50 flex items-center justify-center text-green-600 font-bold shadow-sm border border-green-100">
                                    {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $patient->full_name }}</div>
                                    <div class="text-gray-500 text-xs">{{ $patient->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">
                            {{ $patient->id }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $patient->nik }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $patient->phone ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('receptionist.appointments.create', ['patient_id' => $patient->id]) }}" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-semibold flex items-center gap-1 transition-colors">
                                    <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                                    Schedule Walk-in
                                </a>
                                <a href="{{ route('receptionist.patients.show', $patient->id) }}" class="p-2 bg-gray-50 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="View Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('receptionist.patients.edit', $patient->id) }}" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Edit Patient">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('receptionist.patients.destroy', $patient->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this patient profile and user account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete Patient">
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
                                    <i data-lucide="users" class="w-8 h-8 text-gray-400"></i>
                                </div>
                                <p class="text-base font-medium text-gray-900 mb-1">No patients found</p>
                                <p class="text-sm">We couldn't find any patients matching your search criteria.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($patients->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $patients->links() }}
        </div>
    @endif
</div>
@endsection
