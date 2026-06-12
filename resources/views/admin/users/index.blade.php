@extends('layouts.master')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            User Management
        </h2>
        <p class="text-sm text-gray-500 mt-1">Manage admins, doctors, and patients.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm transition-all hover:shadow-md">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Add User
    </a>
</div>

<!-- Filters -->
<div class="mb-6 bg-white p-3 rounded-xl border border-gray-100 shadow-sm inline-flex items-center gap-3">
    <label for="role-filter" class="text-sm font-medium text-gray-700">Filter by Role:</label>
    <form action="{{ route('admin.users.index') }}" method="GET" class="m-0">
        <select id="role-filter" name="role" onchange="this.form.submit()" class="border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 pl-3 pr-8 text-gray-700">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
            <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>Patient</option>
            <option value="pharmacist" {{ request('role') == 'pharmacist' ? 'selected' : '' }}>Pharmacist</option>
            <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
        </select>
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
                    <th class="px-6 py-4 font-medium">User Profile</th>
                    <th class="px-6 py-4 font-medium">Role</th>
                    <th class="px-6 py-4 font-medium">Associated ID</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-100 to-indigo-50 flex items-center justify-center text-blue-600 font-bold shadow-sm border border-blue-100">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium 
                                {{ $user->role->value === 'admin' ? 'bg-purple-50 text-purple-700 border border-purple-100' : '' }}
                                {{ $user->role->value === 'doctor' ? 'bg-blue-50 text-blue-700 border border-blue-100' : '' }}
                                {{ $user->role->value === 'patient' ? 'bg-green-50 text-green-700 border border-green-100' : '' }}
                                {{ $user->role->value === 'pharmacist' ? 'bg-orange-50 text-orange-700 border border-orange-100' : '' }}
                                {{ $user->role->value === 'cashier' ? 'bg-teal-50 text-teal-700 border border-teal-100' : '' }}
                            ">
                                <span class="w-1.5 h-1.5 rounded-full 
                                    {{ $user->role->value === 'admin' ? 'bg-purple-500' : '' }}
                                    {{ $user->role->value === 'doctor' ? 'bg-blue-500' : '' }}
                                    {{ $user->role->value === 'patient' ? 'bg-green-500' : '' }}
                                    {{ $user->role->value === 'pharmacist' ? 'bg-orange-500' : '' }}
                                    {{ $user->role->value === 'cashier' ? 'bg-teal-500' : '' }}
                                "></span>
                                {{ ucfirst($user->role->value) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            @if($user->role->value === 'doctor' && $user->doctor)
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-800">{{ $user->doctor->id }}</span>
                                    <span class="text-xs text-gray-500">{{ $user->doctor->specialist }}</span>
                                </div>
                            @elseif($user->role->value === 'patient' && $user->patient)
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-800">{{ $user->patient->id }}</span>
                                    <span class="text-xs text-gray-500">NIK: {{ $user->patient->nik }}</span>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors tooltip" title="View details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors tooltip" title="Edit user">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>
                                @if(auth()->user() && auth()->user()->id !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors tooltip" title="Delete user">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="users" class="w-8 h-8 text-gray-400"></i>
                                </div>
                                <p class="text-base font-medium text-gray-900 mb-1">No users found</p>
                                <p class="text-sm">We couldn't find any users matching your criteria.</p>
                                @if(request('role'))
                                    <a href="{{ route('admin.users.index') }}" class="mt-4 text-blue-600 hover:text-blue-700 font-medium text-sm">Clear Filters</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
