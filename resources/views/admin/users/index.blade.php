@extends('layouts.master')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            User Management
        </h2>
        <p class="text-sm text-gray-500">Manage admins, doctors, and patients.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Add User
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
                    <th class="px-6 py-3 font-medium">Name</th>
                    <th class="px-6 py-3 font-medium">Email</th>
                    <th class="px-6 py-3 font-medium">Role</th>
                    <th class="px-6 py-3 font-medium">Associated Profile</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-medium 
                                {{ $user->role->value === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $user->role->value === 'doctor' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $user->role->value === 'patient' ? 'bg-green-100 text-green-700' : '' }}
                            ">
                                {{ ucfirst($user->role->value) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            @if($user->role->value === 'doctor' && $user->doctor)
                                {{ $user->doctor->id }} ({{ $user->doctor->specialist }})
                            @elseif($user->role->value === 'patient' && $user->patient)
                                {{ $user->patient->id }} (NIK: {{ $user->patient->nik }})
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 flex justify-end gap-2">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="px-2 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-xs font-medium transition-colors">View</a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="px-2 py-1 bg-orange-50 text-orange-600 hover:bg-orange-100 rounded text-xs font-medium transition-colors">Edit</a>
                            @if(auth()->user()->id !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded text-xs font-medium transition-colors">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
