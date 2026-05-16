@extends('layouts.master')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">
        Add New User
    </h2>
    <p class="text-sm text-gray-500">Create a new user account (Admin, Doctor, or Patient).</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
    <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
        @csrf

        <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Account Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                <select name="role" id="role-select" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->value }}" {{ old('role') == $role->value ? 'selected' : '' }}>{{ ucfirst($role->value) }}</option>
                    @endforeach
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div id="patient-fields" style="display: none;">
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Patient Identity</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK (16 Digits) <span class="text-red-500">*</span></label>
                    <input type="text" name="nik_patient" value="{{ old('nik_patient') }}" maxlength="16" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('nik_patient') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Place of Birth</label>
                    <input type="text" name="pob" value="{{ old('pob') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('pob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                    <input type="date" name="dob" value="{{ old('dob') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('dob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Gender</option>
                        @foreach($genders as $gender)
                            <option value="{{ $gender->value }}" {{ old('gender') == $gender->value ? 'selected' : '' }}>{{ ucfirst($gender->value) }}</option>
                        @endforeach
                    </select>
                    @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_patient" value="{{ old('phone_patient') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('phone_patient') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                    <select name="blood_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Blood Type</option>
                        @foreach(['A', 'B', 'AB', 'O'] as $type)
                            <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('blood_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">BPJS Number</label>
                    <input type="text" name="bpjs_number" value="{{ old('bpjs_number') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('bpjs_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address') }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div id="doctor-fields" style="display: none;">
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Doctor Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK (16 Digits) <span class="text-red-500">*</span></label>
                    <input type="text" name="nik_doctor" value="{{ old('nik_doctor') }}" maxlength="16" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('nik_doctor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Specialist <span class="text-red-500">*</span></label>
                    <input type="text" name="specialist" value="{{ old('specialist') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('specialist') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SIP <span class="text-red-500">*</span></label>
                    <input type="text" name="sip" value="{{ old('sip') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('sip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">STR <span class="text-red-500">*</span></label>
                    <input type="text" name="str" value="{{ old('str') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('str') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_doctor" value="{{ old('phone_doctor') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('phone_doctor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center gap-4 pt-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_bpjs" value="1" {{ old('is_bpjs') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">BPJS Provider</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Save User</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role-select');
        const patientFields = document.getElementById('patient-fields');
        const doctorFields = document.getElementById('doctor-fields');

        function updateFields() {
            const role = roleSelect.value;
            if (role === 'patient') {
                patientFields.style.display = 'block';
                doctorFields.style.display = 'none';
            } else if (role === 'doctor') {
                patientFields.style.display = 'none';
                doctorFields.style.display = 'block';
            } else {
                patientFields.style.display = 'none';
                doctorFields.style.display = 'none';
            }
        }

        roleSelect.addEventListener('change', updateFields);
        updateFields(); // initialize on load
    });
</script>
@endsection
