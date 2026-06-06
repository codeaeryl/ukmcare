@extends('layouts.master')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">
        Edit User Role
    </h2>
    <p class="text-sm text-gray-500">Update role for {{ $user->name }}.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Account Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <div class="w-full p-2 bg-gray-50 rounded-md border border-gray-200 text-gray-600">
                    {{ $user->name }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="w-full p-2 bg-gray-50 rounded-md border border-gray-200 text-gray-600">
                    {{ $user->email }}
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                @if(auth()->id() === $user->id)
                    <select disabled class="w-full bg-gray-100 cursor-not-allowed rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="{{ $user->role->value }}" selected>{{ ucfirst($user->role->value) }}</option>
                    </select>
                    <input type="hidden" name="role" value="{{ $user->role->value }}">
                    <p class="text-xs text-gray-500 mt-1">You cannot change your own role.</p>
                @else
                    <select name="role" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->value }}" {{ old('role', $user->role->value) == $role->value ? 'selected' : '' }}>{{ ucfirst($role->value) }}</option>
                        @endforeach
                    </select>
                @endif
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                @if(auth()->id() === $user->id)
                    <select disabled class="w-full bg-gray-100 cursor-not-allowed rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="{{ $user->status->value }}" selected>{{ ucfirst($user->status->value) }}</option>
                    </select>
                    <input type="hidden" name="status" value="{{ $user->status->value }}">
                    <p class="text-xs text-gray-500 mt-1">You cannot change your own status.</p>
                @else
                    <select name="status" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(\App\Enums\UserStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ old('status', $user->status->value) == $status->value ? 'selected' : '' }}>{{ ucfirst($status->value) }}</option>
                        @endforeach
                    </select>
                @endif
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition-colors">Update Role</button>
        </div>
    </form>
</div>
@endsection
