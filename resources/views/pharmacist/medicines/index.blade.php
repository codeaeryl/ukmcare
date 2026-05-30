@extends('layouts.master')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">Medicine Inventory</h2>
    </div>
    <a href="{{ route('pharmacist.medicines.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
        Add Medicine
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Medicine Name</th>
                    <th class="px-6 py-3 font-medium">Stock</th>
                    <th class="px-6 py-3 font-medium">Price</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($medicines as $med)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $med->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $med->stock < 10 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $med->stock }} {{ $med->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">Rp {{ number_format($med->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 flex justify-end gap-2">
                            <a href="{{ route('pharmacist.medicines.edit', $med->id) }}" class="px-2 py-1 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded text-xs font-medium transition-colors">Edit</a>
                            <form action="{{ route('pharmacist.medicines.destroy', $med->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this medicine?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded text-xs font-medium transition-colors">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No medicines found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
