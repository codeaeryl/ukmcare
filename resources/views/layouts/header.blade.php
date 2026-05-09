<nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
    <div class="px-4 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 hover:bg-gray-100 rounded-md">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <form class="hidden md:block">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                        <input type="search" placeholder="Search Patients or Doctors..." class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-64 lg:w-96 text-sm" />
                    </div>
                </form>
            </div>

            <div class="flex items-center gap-2">
                <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:block p-2 hover:bg-gray-100 rounded-md">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <div class="relative" x-data="{ accountDropdownOpen: false }">
                    <button @click="accountDropdownOpen = !accountDropdownOpen" @click.away="accountDropdownOpen = false" class="flex items-center gap-2 px-3 py-2 hover:bg-gray-100 rounded-md">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <span class="hidden sm:block text-sm font-medium">Account</span>
                        <i data-lucide="chevron-down" class="hidden sm:block w-4 h-4"></i>
                    </button>

                    <div x-show="accountDropdownOpen" style="display: none;" class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-50">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm font-medium">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@hospital.com' }}</p>
                        </div>
                        
                        <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100">
                            <i data-lucide="user-cog" class="w-4 h-4"></i>
                            <span>Profile & Settings</span>
                        </a>

                        <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 text-red-600">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="md:hidden pb-3">
            <form>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                    <input type="search" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" />
                </div>
            </form>
        </div>
    </div>
</nav>
