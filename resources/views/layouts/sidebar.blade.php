<aside :class="[
      'bg-white border-r border-gray-200 transition-all duration-300 flex flex-col fixed lg:relative h-full z-50 overflow-hidden',
      isMobile ? (sidebarOpen ? 'w-64' : 'w-0') : (sidebarOpen ? 'w-64' : 'w-16'),
      isMobile && !sidebarOpen ? '-translate-x-full' : 'translate-x-0'
    ]">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 x-show="sidebarOpen" class="text-sm font-semibold flex items-center gap-2 whitespace-nowrap text-blue-600">
            <i data-lucide="activity" class="w-6 h-6" x-show="sidebarOpen"></i>
            UKMCare Hospital
        </h3>
        <!-- Tanda icon kecil saat sidebar tertutup -->
        <i data-lucide="activity" class="w-6 h-6 mx-auto text-blue-600" x-show="!sidebarOpen && !isMobile"></i>
        
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 hover:bg-gray-100 rounded-md hidden lg:block flex-shrink-0" :class="{ 'mx-auto': !sidebarOpen }">
            <i data-lucide="chevron-left" x-show="sidebarOpen" class="w-5 h-5 text-gray-500"></i>
            <i data-lucide="chevron-right" x-show="!sidebarOpen" class="w-5 h-5 text-gray-500"></i>
        </button>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <p x-show="sidebarOpen" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Main Menu</p>
        
        <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->is('dashboard') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Dashboard</span>
        </a>

        @if(auth()->user()->role->value === 'admin')
            <!-- ADMIN MENU -->
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">User Management</span>
            </a>
            
            <a href="{{ url('/doctors') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->is('doctors*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="stethoscope" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Doctors</span>
            </a>

            <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->routeIs('admin.schedules.*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="calendar-check" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Doctor Schedules</span>
            </a>

        <p x-show="sidebarOpen" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Pharmacy & Billing</p>


            <a href="{{ route('admin.medicines.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->routeIs('admin.medicines.*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="pill" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Pharmacy</span>
            </a>
            
            <a href="{{ route('admin.bills.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->routeIs('admin.bills.*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="credit-card" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Billing</span>
            </a>
            
            <a href="{{ route('admin.logs.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->routeIs('admin.logs.*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="activity" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Logs</span>
            </a>
        @elseif(auth()->user()->role->value === 'doctor')
            <!-- DOCTOR MENU -->
            <a href="{{ route('doctor.records.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->routeIs('doctor.records.*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="clipboard-list" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Patient Queue</span>
            </a>
            <a href="{{ route('doctor.records.history') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->routeIs('doctor.records.history') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="history" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Medical History</span>
            </a>
        @elseif(auth()->user()->role->value === 'patient')
            <!-- PATIENT MENU -->
            <a href="{{ route('patient.appointments.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->routeIs('patient.appointments.*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="calendar" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">My Appointments</span>
            </a>
            <a href="{{ url('/records') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->is('records*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="file-text" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Medical Records</span>
            </a>
            <a href="{{ url('/billing') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->is('billing*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
                <i data-lucide="receipt" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">My Bills</span>
            </a>
        @endif

    </nav>
</aside>
