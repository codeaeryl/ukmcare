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

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Patients</p>
                <h3 class="text-2xl font-bold text-gray-800">1,245</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                <i data-lucide="calendar-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Appointments Today</p>
                <h3 class="text-2xl font-bold text-gray-800">42</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 flex-shrink-0">
                <i data-lucide="activity" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Available Beds</p>
                <h3 class="text-2xl font-bold text-gray-800">18 / 150</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 flex-shrink-0">
                <i data-lucide="stethoscope" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Doctors on Duty</p>
                <h3 class="text-2xl font-bold text-gray-800">24</h3>
            </div>
        </div>
    </div>

    <!-- Recent Appointments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Recent Appointments</h3>
            <a href="#" class="text-sm text-blue-600 hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">Patient Name</th>
                        <th class="px-6 py-3 font-medium">Doctor</th>
                        <th class="px-6 py-3 font-medium">Date & Time</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">Michael Scott</td>
                        <td class="px-6 py-4 text-gray-600">Dr. Sarah Johnson</td>
                        <td class="px-6 py-4 text-gray-600">Today, 10:00 AM</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-700 rounded-md text-xs font-medium">Completed</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">Dwight Schrute</td>
                        <td class="px-6 py-4 text-gray-600">Dr. Emily Chen</td>
                        <td class="px-6 py-4 text-gray-600">Today, 11:30 AM</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-medium">In Progress</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">Jim Halpert</td>
                        <td class="px-6 py-4 text-gray-600">Dr. Mark Davis</td>
                        <td class="px-6 py-4 text-gray-600">Today, 02:15 PM</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-md text-xs font-medium">Waiting</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
