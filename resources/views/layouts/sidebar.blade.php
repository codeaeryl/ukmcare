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

        <a href="{{ url('/patients') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->is('patients*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
            <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Patients</span>
        </a>
        
        <a href="{{ url('/doctors') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->is('doctors*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
            <i data-lucide="stethoscope" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Doctors</span>
        </a>

        <a href="{{ url('/appointments') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->is('appointments*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
            <i data-lucide="calendar-check" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Appointments</span>
        </a>

        <p x-show="sidebarOpen" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Pharmacy & Billing</p>

        <a href="{{ url('/pharmacy') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->is('pharmacy*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
            <i data-lucide="pill" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Pharmacy</span>
        </a>
        
        <a href="{{ url('/billing') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors text-sm hover:bg-gray-100 {{ request()->is('billing*') ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white shadow-md' : 'text-gray-700' }}">
            <i data-lucide="credit-card" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="sidebarOpen" class="whitespace-nowrap font-medium">Billing</span>
        </a>
    </nav>
</aside>
