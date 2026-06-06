@extends('layouts.master')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-2xl border border-gray-100">
        <div class="flex flex-col items-center justify-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 shadow-sm">
                <i data-lucide="user-plus" class="w-8 h-8"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Create an Account</h2>
            <p class="text-sm text-gray-500 mt-1">Register to UKMCare Hospital</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block font-medium text-sm text-gray-700">{{ __('Full Name') }}</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <input id="name" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-sm" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </div>
                    <input id="email" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="patient@hospital.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <input id="password" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('Confirm Password') }}</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </div>
                    <input id="password_confirmation" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
            </div>

            <!-- NIK -->
            <div class="mt-4">
                <label for="nik" class="block font-medium text-sm text-gray-700">{{ __('NIK (16 Digits)') }} <span class="text-red-500">*</span></label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                    </div>
                    <input id="nik" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2" type="text" name="nik" value="{{ old('nik') }}" required maxlength="16" placeholder="3201010000000000" />
                </div>
                <x-input-error :messages="$errors->get('nik')" class="mt-2 text-red-600 text-sm" />
            </div>

            <!-- POB & DOB Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="pob" class="block font-medium text-sm text-gray-700">{{ __('Place of Birth') }}</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <input id="pob" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2" type="text" name="pob" value="{{ old('pob') }}" placeholder="Jakarta" />
                    </div>
                    <x-input-error :messages="$errors->get('pob')" class="mt-2 text-red-600 text-sm" />
                </div>
                <div>
                    <label for="dob" class="block font-medium text-sm text-gray-700">{{ __('Date of Birth') }} <span class="text-red-500">*</span></label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                        </div>
                        <input id="dob" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2" type="date" name="dob" value="{{ old('dob') }}" required />
                    </div>
                    <x-input-error :messages="$errors->get('dob')" class="mt-2 text-red-600 text-sm" />
                </div>
            </div>

            <!-- Gender -->
            <div class="mt-4">
                <label for="gender" class="block font-medium text-sm text-gray-700">{{ __('Gender') }} <span class="text-red-500">*</span></label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <select id="gender" name="gender" required class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('gender')" class="mt-2 text-red-600 text-sm" />
            </div>

            <!-- Phone -->
            <div class="mt-4">
                <label for="phone" class="block font-medium text-sm text-gray-700">{{ __('Phone Number') }}</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                    </div>
                    <input id="phone" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2" type="text" name="phone" value="{{ old('phone') }}" placeholder="081234567890" />
                </div>
                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-red-600 text-sm" />
            </div>

            <!-- Address -->
            <div class="mt-4">
                <label for="address" class="block font-medium text-sm text-gray-700">{{ __('Address') }}</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 pt-2">
                        <i data-lucide="home" class="w-5 h-5"></i>
                    </div>
                    <textarea id="address" name="address" rows="2" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2" placeholder="Jl. Kesehatan No. 123">{{ old('address') }}</textarea>
                </div>
                <x-input-error :messages="$errors->get('address')" class="mt-2 text-red-600 text-sm" />
            </div>

            <!-- BPJS Number -->
            <div class="mt-4">
                <label for="bpjs_number" class="block font-medium text-sm text-gray-700">{{ __('BPJS Number (Optional)') }}</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i data-lucide="shield" class="w-5 h-5"></i>
                    </div>
                    <input id="bpjs_number" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm py-2" type="text" name="bpjs_number" value="{{ old('bpjs_number') }}" placeholder="0001234567890" />
                </div>
                <x-input-error :messages="$errors->get('bpjs_number')" class="mt-2 text-red-600 text-sm" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="text-sm text-blue-600 hover:text-blue-800 hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-md" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <button type="submit" class="inline-flex justify-center items-center px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
    
    <div class="mt-8 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} UKMCare Hospital System. All rights reserved.
    </div>
</div>
@endsection
