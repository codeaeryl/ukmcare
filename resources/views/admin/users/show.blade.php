@extends('layouts.master')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">
            User Details
        </h2>
        <p class="text-sm text-gray-500">View complete account details and associated profile for {{ $user->name }}.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
            Back
        </a>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
            <i data-lucide="edit" class="w-4 h-4"></i>
            Edit User
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4 pb-2 border-b">Account Information</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-800">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Role</p>
                    <p class="font-medium text-gray-800">
                        <span class="px-2 py-1 rounded text-xs font-medium 
                            {{ $user->role->value === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $user->role->value === 'doctor' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $user->role->value === 'patient' ? 'bg-green-100 text-green-700' : '' }}
                        ">
                            {{ ucfirst($user->role->value) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Registered At</p>
                    <p class="font-medium text-gray-800">{{ $user->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-1 lg:col-span-2">
        @if($user->role->value === 'admin')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-10 flex flex-col items-center justify-center text-gray-500">
                <i data-lucide="shield-check" class="w-16 h-16 mb-4 text-purple-300"></i>
                <p class="text-lg font-medium">Administrator Account</p>
                <p class="text-sm">No additional profile data is required for admins.</p>
            </div>
        @elseif($user->role->value === 'patient' && $user->patient)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-800">Patient Identity</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><p class="text-sm text-gray-500">MRN</p><p class="font-medium text-gray-800">{{ $user->patient->id }}</p></div>
                    <div><p class="text-sm text-gray-500">NIK</p><p class="font-medium text-gray-800">{{ $user->patient->nik }}</p></div>
                    <div><p class="text-sm text-gray-500">Place & Date of Birth</p><p class="font-medium text-gray-800">{{ $user->patient->pob ? $user->patient->pob . ', ' : '' }}{{ $user->patient->dob->format('d M Y') }}</p></div>
                    <div><p class="text-sm text-gray-500">Gender</p><p class="font-medium text-gray-800">{{ ucfirst($user->patient->gender->value ?? $user->patient->gender) }}</p></div>
                    <div><p class="text-sm text-gray-500">Blood Type</p><p class="font-medium text-gray-800">{{ $user->patient->blood_type ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">BPJS Number</p><p class="font-medium text-gray-800">{{ $user->patient->bpjs_number ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Phone</p><p class="font-medium text-gray-800">{{ $user->patient->phone ?? '-' }}</p></div>
                    <div class="md:col-span-2"><p class="text-sm text-gray-500">Address</p><p class="font-medium text-gray-800">{{ $user->patient->address ?? '-' }}</p></div>
                </div>
            </div>

            <!-- Medical History logic -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-800">Visit History</h3>
                </div>
                <div class="p-6">
                    @if($user->patient->registrations->isEmpty())
                        <p class="text-gray-500 text-center py-4">No visit history recorded for this patient.</p>
                    @else
                        <!-- List logic for patient visit history as before -->
                        <div class="space-y-4">
                            @foreach($user->patient->registrations->sortByDesc('registration_date') as $registration)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-semibold text-gray-800">Visit Date: {{ $registration->registration_date->format('d M Y H:i') }}</span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">{{ ucfirst($registration->status->value ?? $registration->status) }}</span>
                                    </div>
                                    @if($registration->medicalRecord)
                                        <p class="text-gray-600 text-sm">Diagnosis: {{ $registration->medicalRecord->diagnosis ?? 'N/A' }}</p>
                                    @else
                                        <p class="text-gray-500 text-sm italic">No medical record yet.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @elseif($user->role->value === 'doctor' && $user->doctor)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-800">Doctor Information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><p class="text-sm text-gray-500">Doctor ID</p><p class="font-medium text-gray-800">{{ $user->doctor->id }}</p></div>
                    <div><p class="text-sm text-gray-500">NIK</p><p class="font-medium text-gray-800">{{ $user->doctor->nik }}</p></div>
                    <div><p class="text-sm text-gray-500">Specialist</p><p class="font-medium text-gray-800">{{ $user->doctor->specialist }}</p></div>
                    <div><p class="text-sm text-gray-500">Phone</p><p class="font-medium text-gray-800">{{ $user->doctor->phone ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">SIP</p><p class="font-medium text-gray-800">{{ $user->doctor->sip }}</p></div>
                    <div><p class="text-sm text-gray-500">STR</p><p class="font-medium text-gray-800">{{ $user->doctor->str }}</p></div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="font-medium">
                            <span class="px-2 py-1 text-xs rounded {{ $user->doctor->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->doctor->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">BPJS Provider</p>
                        <p class="font-medium">
                            <span class="px-2 py-1 text-xs rounded {{ $user->doctor->is_bpjs ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $user->doctor->is_bpjs ? 'Yes' : 'No' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-800">Recent Medical Records Handled</h3>
                </div>
                <div class="p-6">
                    @if($user->doctor->medicalRecords->isEmpty())
                        <p class="text-gray-500 text-center py-4">No medical records handled by this doctor yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($user->doctor->medicalRecords->sortByDesc('created_at')->take(5) as $record)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-semibold text-gray-800">Date: {{ $record->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                    <p class="text-gray-600 text-sm">Diagnosis: {{ $record->diagnosis }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-10 flex flex-col items-center justify-center text-gray-500">
                <i data-lucide="alert-circle" class="w-16 h-16 mb-4 text-orange-300"></i>
                <p class="text-lg font-medium">Profile Data Missing</p>
                <p class="text-sm">This user's role is set to {{ $user->role->value }} but their profile data is missing.</p>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Complete Profile</a>
            </div>
        @endif
    </div>
</div>
@endsection
