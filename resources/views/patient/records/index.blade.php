@extends('layouts.master')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">My Medical Records</h2>
    <p class="text-sm text-gray-500">View your diagnosis history and prescriptions.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Doctor</th>
                    <th class="px-6 py-3 font-medium">Diagnosis</th>
                    <th class="px-6 py-3 font-medium">Treatment</th>
                    <th class="px-6 py-3 font-medium text-right">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($records as $record)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-600">
                            {{ $record->record_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $record->doctor->full_name }}
                            <div class="text-xs text-gray-500">{{ $record->doctor->specialist }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-800">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">
                                {{ $record->diagnosis }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $record->action }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('patient.records.show', $record->id) }}" class="px-3 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-xs font-medium transition-colors">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No medical records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
