<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('vite.svg') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'Hospital Dashboard') }}</title>
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <!-- Alpine.js wrapper for layout state -->
    <div x-data="{ sidebarOpen: true, isMobile: window.innerWidth < 1024 }" 
         @resize.window="isMobile = window.innerWidth < 1024; if(isMobile) sidebarOpen = false"
         class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Overlay for mobile -->
        <div x-show="sidebarOpen && isMobile" 
             @click="sidebarOpen = false" 
             style="display: none;"
             class="fixed inset-0 bg-black/50 z-40 lg:hidden">
        </div>

        @if (!request()->routeIs('login') && !request()->routeIs('register') && !request()->routeIs('profile.edit'))
            @include('layouts.sidebar')
        @endif

        <div class="flex-1 flex flex-col overflow-hidden">
            @if (!request()->routeIs('login') && !request()->routeIs('register'))
                @include('layouts.header')
            @endif

            <main class="flex-1 overflow-auto bg-gray-50">
                <div class="p-4 md:p-8 h-full">
                    @yield('content')
                </div>
            </main>

            @if (!request()->routeIs('login') && !request()->routeIs('register'))
                @include('layouts.footer')
            @endif
        </div>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
      lucide.createIcons();
    </script>
</body>
</html>
