@extends('layouts.master')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">BPJS Verifications</h2>
        <p class="text-sm text-gray-500">Manage and verify patient BPJS submissions.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-4 font-medium">Patient Info</th>
                    <th class="px-6 py-4 font-medium">BPJS Number</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($patients as $patient)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $patient->full_name }}</div>
                        <div class="text-gray-500 text-xs">NIK: {{ $patient->nik }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">
                            {{ $patient->bpjs_number }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($patient->bpjs_status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Pending
                            </span>
                        @elseif($patient->bpjs_status === 'verified')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Verified
                            </span>
                        @elseif($patient->bpjs_status === 'rejected')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rejected
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($patient->bpjs_status === 'pending')
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('admin.bpjs.update', $patient->id) }}" method="POST">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="status" value="verified">
                                <button type="submit" class="p-1.5 text-green-600 hover:bg-green-50 rounded-md transition-colors tooltip" title="Approve">
                                    <i data-lucide="check" class="w-5 h-5"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.bpjs.update', $patient->id) }}" method="POST">
                                @csrf
                                @method('patch')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors tooltip" title="Reject">
                                    <i data-lucide="x" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-gray-400 text-xs italic">Reviewed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="inbox" class="w-8 h-8 mb-2 text-gray-400"></i>
                            <p>No BPJS verification requests found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($patients->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $patients->links() }}
    </div>
    @endif
</div>
@endsection
