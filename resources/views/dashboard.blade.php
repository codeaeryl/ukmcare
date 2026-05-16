@extends('layouts.master')

@section('content')
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">
                Hospital Dashboard
            </h2>
            <p class="text-sm text-gray-500">Overview of today's hospital activities.</p>
        </div>
        @if(auth()->user()->role->value === 'admin')
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            New User
        </a>
        @else
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            New Patient
        </button>
        @endif
    </div>

    @if(auth()->user()->role->value === 'admin')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Patients</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalPatients) }}</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                <i data-lucide="calendar-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Appointments</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalAppointments) }}</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 flex-shrink-0">
                <i data-lucide="wallet" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalRevenue / 1000, 0) }}k</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 flex-shrink-0">
                <i data-lucide="stethoscope" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Doctors</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalDoctors }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Visits -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Upcoming Appointments</h3>
                <a href="{{ route('admin.schedules.index') }}" class="text-sm text-blue-600 hover:underline">Manage</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">Patient</th>
                            <th class="px-6 py-3 font-medium">Doctor</th>
                            <th class="px-6 py-3 font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($upcomingVisits as $visit)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $visit->patient->full_name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $visit->schedule->doctor->full_name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $visit->registration_date->format('d M') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-4 text-center text-gray-400">No upcoming visits.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Recent Activity Logs</h3>
            </div>
            <div class="p-6">
                <div class="flow-root">
                    <ul class="-mb-8">
                        @forelse($recentActivities as $log)
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                            <i data-lucide="user" class="w-4 h-4 text-white"></i>
                                        </span>
                                    </div>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">{{ $log->activity }} <span class="font-medium text-gray-900">by {{ $log->user->name }}</span></p>
                                        </div>
                                        <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                            <time>{{ $log->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="text-sm text-gray-400 italic">No activity recorded yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @elseif(auth()->user()->role->value === 'doctor')
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-8 text-center">
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="stethoscope" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Welcome, {{ auth()->user()->name }}!</h3>
        <p class="text-gray-600 mb-6">Have a great day at work. Check your patient queue to see your appointments for today.</p>
        <a href="{{ route('doctor.records.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
            View Patient Queue
        </a>
    </div>
    @endif
@endsection
