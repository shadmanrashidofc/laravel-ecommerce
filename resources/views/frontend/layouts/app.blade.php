<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="E-Commerce Store">
        <title>{{ config('app.name', 'E-Commerce') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col">
            <!-- Navbar -->
            <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800 hover:text-blue-600">
                                {{ config('app.name', 'E-Commerce') }}
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden sm:flex sm:items-center sm:space-x-8">
                            <a href="{{ route('home') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('home') ? 'border-blue-600 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                                Home
                            </a>
                            <a href="{{ route('products') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('products') || request()->routeIs('product.show') ? 'border-blue-600 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                                Products
                            </a>
                            <a href="{{ route('cart') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('cart*') ? 'border-blue-600 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Cart
                                @php
                                    $cart = session()->get('cart', []);
                                    $cartCount = array_sum(array_column($cart, 'quantity'));
                                @endphp
                                @if($cartCount > 0)
                                    <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </div>

                        <!-- Auth Links -->
                        <div class="hidden sm:flex sm:items-center sm:space-x-4">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-700 hover:text-blue-600">Admin Panel</a>
                                @else
                                    <a href="{{ route('profile.page') }}" class="text-sm text-gray-700 hover:text-blue-600">Profile</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-700 hover:text-red-600">Log Out</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-blue-600">Log in</a>
                                <a href="{{ route('register') }}" class="text-sm bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Register</a>
                            @endauth
                        </div>

                        <!-- Mobile menu button -->
                        <div class="flex items-center sm:hidden">
                            <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div class="hidden sm:hidden" id="mobile-menu">
                    <div class="pt-2 pb-3 space-y-1">
                        <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('home') ? 'bg-blue-50 border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300' }}">
                            Home
                        </a>
                        <a href="{{ route('products') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('products') ? 'bg-blue-50 border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300' }}">
                            Products
                        </a>
                        <a href="{{ route('cart') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('cart*') ? 'bg-blue-50 border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300' }}">
                            Cart ({{ $cartCount ?? 0 }})
                        </a>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-500 hover:bg-gray-50 hover:border-gray-300">
                                    Admin Panel
                                </a>
                            @else
                                <a href="{{ route('profile.page') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-500 hover:bg-gray-50 hover:border-gray-300">
                                    Profile
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-500 hover:bg-gray-50 hover:border-gray-300">
                                    Log Out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-500 hover:bg-gray-50 hover:border-gray-300">
                                Log in
                            </a>
                            <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-500 hover:bg-gray-50 hover:border-gray-300">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="flex-grow">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-gray-800 text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <!-- Company Info -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">{{ config('app.name', 'E-Commerce') }}</h3>
                            <p class="text-gray-400 text-sm">Your one-stop shop for quality products at amazing prices.</p>
                        </div>

                        <!-- Quick Links -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                            <ul class="space-y-2">
                                <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white text-sm">Home</a></li>
                                <li><a href="{{ route('products') }}" class="text-gray-400 hover:text-white text-sm">Products</a></li>
                                <li><a href="{{ route('cart') }}" class="text-gray-400 hover:text-white text-sm">Cart</a></li>
                            </ul>
                        </div>

                        <!-- Customer Service -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-white text-sm">Contact Us</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white text-sm">Shipping Info</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white text-sm">Returns</a></li>
                            </ul>
                        </div>

                        <!-- Contact -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Contact</h3>
                            <ul class="space-y-2 text-sm text-gray-400">
                                <li>Email: info@example.com</li>
                                <li>Phone: +1 234 567 890</li>
                                <li>Address: 123 Store St, City</li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                        <p>&copy; {{ date('Y') }} {{ config('app.name', 'E-Commerce') }}. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
