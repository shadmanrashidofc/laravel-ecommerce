<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Admin Panel">
        <title>{{ config('app.name', 'E-Commerce') }} - Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-gray-800 text-white flex flex-col">
                <!-- Logo -->
                <div class="h-16 flex items-center px-6 border-b border-gray-700">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">
                        Admin Panel
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Categories
                    </a>

                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.products.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Products
                    </a>

                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Orders
                    </a>
                </nav>

                <!-- Back to Store -->
                <div class="px-4 py-4 border-t border-gray-700">
                    <a href="{{ route('home') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Back to Store
                    </a>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col">
                <!-- Topbar -->
                <header class="h-16 bg-white border-b flex items-center justify-between px-6">
                    <div class="flex items-center">
                        <h1 class="text-lg font-semibold text-gray-800">
                            @yield('title', 'Dashboard')
                        </h1>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- User Dropdown -->
                        <div class="relative">
                            <button type="button" class="flex items-center gap-2 text-gray-700 hover:text-gray-900" onclick="document.getElementById('userDropdown').classList.toggle('hidden')">
                                <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 bg-gray-50 p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            document.getElementById('userDropdown')?.classList.add('hidden');
        </script>
    </body>
</html>
