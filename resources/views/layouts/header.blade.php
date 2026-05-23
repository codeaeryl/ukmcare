<nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
    <div class="px-4 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-4">
                @if(request()->routeIs('admin.users.*'))
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 hover:bg-gray-100 rounded-md">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>

                    <form class="hidden md:block">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                            <input type="search" placeholder="Search Users..." class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-50 w-64 lg:w-96 text-sm" />
                        </div>
                    </form>
                @else
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 hover:bg-gray-100 rounded-md">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <!-- Notifications -->
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" class="relative p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 rounded-full transition-colors">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        @php
                            $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->where('created_at', '>=', now()->subDay())->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        @endif
                    </button>

                    <div x-show="notifOpen" style="display: none;" class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
                            @if($unreadCount > 0)
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }} New</span>
                            @endif
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @php
                                $notifications = \App\Models\Notification::where('user_id', Auth::id())->latest()->take(10)->get();
                            @endphp
                            @forelse($notifications as $notif)
                                <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 {{ $notif->created_at >= now()->subDay() ? 'bg-blue-50/50' : '' }}">
                                    <p class="text-sm text-gray-800">{{ $notif->message }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-center text-sm text-gray-500">
                                    No notifications yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="relative" x-data="{ accountDropdownOpen: false }">
                    <button @click="accountDropdownOpen = !accountDropdownOpen" @click.away="accountDropdownOpen = false" class="flex items-center gap-2 px-3 py-2 hover:bg-gray-100 rounded-md">
                        @php
                            $nameParts = explode(' ', Auth::user()->name ?? 'User');
                            $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                            $firstName = $nameParts[0];
                        @endphp
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                            {{ $initials }}
                        </div>
                        <span class="hidden sm:block text-sm font-medium">{{ $firstName }} ({{ $initials }})</span>
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
        
        @if(request()->routeIs('admin.users.*'))
        <div class="md:hidden pb-3">
            <form>
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                    <input type="search" placeholder="Search Users..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" />
                </div>
            </form>
        </div>
        @endif
    </div>
</nav>
