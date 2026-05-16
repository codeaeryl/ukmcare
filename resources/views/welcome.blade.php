<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UKMCare Hospital</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800">
    
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md fixed w-full z-50 border-b border-gray-100 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                        <i data-lucide="activity" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">UKMCare</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#services" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Services</a>
                    <a href="#doctors" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Doctors</a>
                    <a href="#contact" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Contact</a>
                </div>

                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-full font-medium transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-medium shadow-md shadow-blue-600/20 transition-all hover:-translate-y-0.5">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute inset-0 bg-gradient-to-b from-blue-50/50 to-white -z-10"></div>
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-[800px] h-[800px] bg-blue-100/50 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[600px] h-[600px] bg-green-50/50 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-600 font-medium text-sm mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                        Caring for Life
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        Your Health Is Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Top Priority</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Experience world-class healthcare with UKMCare. We combine advanced medical technology with compassionate care to ensure your well-being.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5 text-center flex items-center justify-center gap-2">
                                Go to Dashboard
                                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5 text-center flex items-center justify-center gap-2">
                                Book an Appointment
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </a>
                        @endauth
                        <a href="#services" class="px-8 py-4 bg-white border border-gray-200 hover:border-blue-200 hover:bg-blue-50 text-gray-700 rounded-full font-semibold transition-all text-center">
                            Explore Services
                        </a>
                    </div>
                    
                    <div class="mt-12 flex items-center gap-8 border-t border-gray-200 pt-8">
                        <div>
                            <p class="text-3xl font-bold text-gray-900">24/7</p>
                            <p class="text-sm text-gray-500">Emergency</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-gray-900">50+</p>
                            <p class="text-sm text-gray-500">Specialist Doctors</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-gray-900">10k+</p>
                            <p class="text-sm text-gray-500">Happy Patients</p>
                        </div>
                    </div>
                </div>
                <div class="relative hidden lg:block">
                    <!-- Image Placeholder container -->
                    <div class="relative w-full aspect-square rounded-[3rem] overflow-hidden bg-gradient-to-tr from-blue-100 to-white border border-white shadow-2xl p-2 flex items-center justify-center">
                        <div class="w-full h-full rounded-[2.5rem] bg-gray-100 overflow-hidden relative">
                           <img src="https://images.unsplash.com/photo-1638202993928-7267aad84c31?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Hospital Team" class="w-full h-full object-cover">
                           <div class="absolute inset-0 bg-blue-600/10 mix-blend-multiply"></div>
                        </div>
                        
                        <!-- Floating Card -->
                        <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Certified</p>
                                <p class="text-lg font-bold text-gray-800">Top Hospital 2026</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Medical Services</h2>
                <p class="text-gray-600">We provide a wide range of healthcare services to ensure you get the best treatment possible. From general checkups to specialized surgeries.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:shadow-xl hover:shadow-blue-900/5 hover:-translate-y-1 transition-all group">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i data-lucide="stethoscope" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">General Checkup</h3>
                    <p class="text-gray-600 leading-relaxed">Comprehensive health evaluations to keep track of your overall well-being and prevent illnesses.</p>
                </div>
                
                <!-- Service 2 -->
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:shadow-xl hover:shadow-blue-900/5 hover:-translate-y-1 transition-all group">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center text-red-600 mb-6 group-hover:bg-red-600 group-hover:text-white transition-colors">
                        <i data-lucide="activity" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Emergency Care</h3>
                    <p class="text-gray-600 leading-relaxed">24/7 rapid response emergency department equipped to handle all critical medical situations.</p>
                </div>

                <!-- Service 3 -->
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:shadow-xl hover:shadow-blue-900/5 hover:-translate-y-1 transition-all group">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <i data-lucide="pill" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pharmacy</h3>
                    <p class="text-gray-600 leading-relaxed">Fully stocked pharmacy providing authentic medications with professional pharmacist consultation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-4 gap-8">
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center text-white">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xl font-bold">UKMCare</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">Providing exceptional healthcare services with compassion, expertise, and advanced technology for a healthier tomorrow.</p>
            </div>
            
            <div>
                <h4 class="font-bold text-lg mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-blue-400 transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Services</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Doctors</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Contact</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-bold text-lg mb-4">Support</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-blue-400 transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-bold text-lg mb-4">Contact Us</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-5 h-5 text-blue-500 shrink-0"></i>
                        <span>123 Health Avenue, Medical District, Jakarta 12345</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i data-lucide="phone" class="w-5 h-5 text-blue-500 shrink-0"></i>
                        <span>+62 123 4567 890</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i data-lucide="mail" class="w-5 h-5 text-blue-500 shrink-0"></i>
                        <span>contact@ukmcare.com</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} UKMCare Hospital. All rights reserved.
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
