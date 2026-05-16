@extends('layouts.master')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            Doctor Schedules
        </h2>
        <p class="text-sm text-gray-500">Manage when doctors are available for appointments.</p>
    </div>
    <a href="{{ route('admin.schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Add Schedule
    </a>
</div>

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
        <p>{{ session('success') }}</p>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Doctor</th>
                    <th class="px-6 py-3 font-medium">Day</th>
                    <th class="px-6 py-3 font-medium">Time</th>
                    <th class="px-6 py-3 font-medium">Quota</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($schedules as $schedule)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $schedule->doctor->full_name }}
                            <div class="text-xs text-gray-500">{{ $schedule->doctor->specialist }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $schedule->schedule_day }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $schedule->start_hour }} - {{ $schedule->end_hour }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $schedule->quota }} patients</td>
                        <td class="px-6 py-4 flex justify-end gap-2">
                            <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded text-xs font-medium transition-colors">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No schedules found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($schedules->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $schedules->links() }}
        </div>
    @endif
</div>
@endsection
