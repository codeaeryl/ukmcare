@extends('layouts.master')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">
                Profile Settings
            </h2>
            <p class="text-sm text-gray-500">Manage your account settings, password, and security.</p>
        </div>
        <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Dashboard
        </a>
    </div>

    <div class="space-y-6">
        
        <!-- Profile Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800">Profile Information</h3>
                    <p class="text-sm text-gray-500">Update your account's profile information and email address.</p>
                </div>
            </div>
            <div class="p-6">
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-6 max-w-xl">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded p-3 text-sm">
                                <p>
                                    Your email address is unverified.
                                    <button form="send-verification" class="underline font-medium hover:text-yellow-900 focus:outline-none">
                                        Click here to re-send the verification email.
                                    </button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-green-600">
                                        A new verification link has been sent to your email address.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition-colors cursor-pointer relative z-10">
                            Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-4 h-4"></i> Saved.
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if (auth()->user()->role->value === 'patient')
        <!-- Patient Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative z-0">
            <div class="p-6 border-b border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800">Medical & Verification Info</h3>
                    <p class="text-sm text-gray-500">Update your blood type and BPJS verification status.</p>
                </div>
            </div>
            <div class="p-6 relative z-10">
                <form method="post" action="{{ route('profile.patient.update') }}" class="space-y-6 max-w-xl relative z-10">
                    @csrf
                    @method('patch')
                    @php $patient = auth()->user()->patient; @endphp

                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                        <select id="blood_type" name="blood_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Blood Type</option>
                            <option value="A" {{ old('blood_type', $patient->blood_type ?? '') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('blood_type', $patient->blood_type ?? '') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ old('blood_type', $patient->blood_type ?? '') == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ old('blood_type', $patient->blood_type ?? '') == 'O' ? 'selected' : '' }}>O</option>
                        </select>
                        @error('blood_type') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="bpjs_number" class="block text-sm font-medium text-gray-700 mb-1">BPJS Number</label>
                        <div class="flex gap-2">
                            <input id="bpjs_number" name="bpjs_number" type="text" value="{{ old('bpjs_number', $patient->bpjs_number ?? '') }}" {{ ($patient->bpjs_status ?? '') === 'verified' ? 'readonly' : '' }} class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ ($patient->bpjs_status ?? '') === 'verified' ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                        </div>
                        @error('bpjs_number') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        
                        <div class="mt-2">
                            @if(($patient->bpjs_status ?? 'unverified') === 'unverified')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                    <i data-lucide="circle-dashed" class="w-3.5 h-3.5"></i> Unverified
                                </span>
                            @elseif($patient->bpjs_status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-700">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i> Pending Admin Verification
                                </span>
                                <p class="text-xs text-gray-500 mt-1">Please wait while our admin verifies your BPJS number.</p>
                            @elseif($patient->bpjs_status === 'verified')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-green-100 text-green-700 border border-green-200">
                                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Verified Active
                                </span>
                                <p class="text-xs text-gray-500 mt-1">Your BPJS is verified. Contact admin if you need to change it.</p>
                            @elseif($patient->bpjs_status === 'rejected')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-red-100 text-red-700">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Rejected / Invalid
                                </span>
                                <p class="text-xs text-red-500 mt-1">Your BPJS number is invalid or inactive. Please update it.</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        @if(($patient->bpjs_status ?? '') !== 'verified')
                        <button type="submit" class="pointer-events-auto relative z-50 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition-colors cursor-pointer">
                            Save Information
                        </button>
                        @endif

                        @if (session('status') === 'patient-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-4 h-4"></i> Saved.
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Update Password -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative z-0">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-800">Update Password</h3>
                <p class="text-sm text-gray-500 mt-1">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="p-6 relative z-10">
                <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-xl relative z-10">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('current_password', 'updatePassword') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('password', 'updatePassword') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('password_confirmation', 'updatePassword') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <!-- Make sure button has pointer-events-auto and high z-index to be clickable -->
                        <button type="submit" class="pointer-events-auto relative z-50 px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-900 font-medium shadow-sm transition-colors cursor-pointer">
                            Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-4 h-4"></i> Password updated.
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Account -->
        <div x-data="{ showConfirmModal: false }" class="bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">
            <div class="p-6 border-b border-red-100 bg-red-50/30">
                <h3 class="text-lg font-medium text-red-600">Delete Account</h3>
                <p class="text-sm text-gray-500 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-6 max-w-xl">
                    Before deleting your account, please download any data or information that you wish to retain.
                </p>

                <button type="button" @click="showConfirmModal = true" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-sm transition-colors cursor-pointer">
                    Delete Account
                </button>

                <!-- Custom Modal for verification -->
                <div x-show="showConfirmModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <!-- Background overlay -->
                        <div x-show="showConfirmModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showConfirmModal = false"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <!-- Modal panel -->
                        <div x-show="showConfirmModal" 
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                            
                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                @csrf
                                @method('delete')
                                
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Delete Account Verification
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500 mb-4">
                                                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                                            </p>
                                            
                                            <div class="mt-4">
                                                <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-1">Verify Password</label>
                                                <input type="password" id="delete_password" name="password" required placeholder="Enter your password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                                @error('password', 'userDeletion') 
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 sm:mt-8 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Confirm Delete
                                    </button>
                                    <button type="button" @click="showConfirmModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- If there are deletion errors, open the modal automatically -->
@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('alpine:init', () => {
        // Alpine is ready
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent('open-modal'));
        }, 100);
    });
</script>
@endif
@endsection
