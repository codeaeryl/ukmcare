@extends('layouts.master')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Upcoming Patient Queue</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Queue No.</th>
                    <th class="px-6 py-3 font-medium">Patient Name</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($registrations as $reg)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-600 font-medium">{{ $reg->registration_date->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                                {{ $reg->queue_number }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $reg->patient->full_name }}</td>
                        <td class="px-6 py-4 flex justify-end">
                            <a href="{{ route('doctor.records.create', $reg->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition-colors">
                                Examine
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">No patients in queue for today.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
