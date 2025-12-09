<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'HopeFund Admin')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="flex min-h-screen">
            <!-- Sidebar - Fixed, tidak bisa diganggu -->
            <aside class="z-50 w-64 h-screen overflow-y-auto bg-white border-r border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-cyan-50 to-white">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-slate-900">HopeFund</a>
                    <p class="mt-1 text-xs text-slate-500">Admin Panel</p>
                </div>

                <nav class="mt-8">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="block px-6 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-50 border-l-4 border-cyan-500 text-cyan-700' : 'text-slate-600 hover:text-slate-900 hover:bg-gray-50' }} transition duration-200 flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-4m0 0l7-4 7 4M5 8v10a1 1 0 001 1h12a1 1 0 001-1V8m-9 4v4m0 0H9m3 0h3"></path></svg>
                        <span class="font-semibold">Dashboard</span>
                    </a>

                    <!-- Donasi -->
                    <div class="mt-6">
                        <p class="px-6 mb-3 text-xs font-semibold uppercase text-slate-400">Donasi</p>
                        <a href="{{ route('admin.donations') }}" class="block px-6 py-3 {{ request()->routeIs('admin.donations*') ? 'bg-cyan-50 border-l-4 border-cyan-500 text-cyan-700' : 'text-slate-600 hover:text-slate-900 hover:bg-gray-50' }} transition duration-200 flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-semibold">Donasi Masuk</span>
                        </a>
                    </div>

                    <!-- Alokasi Dana -->
                    <div class="mt-6">
                        <p class="px-6 mb-3 text-xs font-semibold uppercase text-slate-400">Alokasi</p>
                        <a href="{{ route('admin.allocations.index') }}" class="block px-6 py-3 {{ request()->routeIs('admin.allocations*') ? 'bg-cyan-50 border-l-4 border-cyan-500 text-cyan-700' : 'text-slate-600 hover:text-slate-900 hover:bg-gray-50' }} transition duration-200 flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="font-semibold">Alokasi Dana</span>
                        </a>
                    </div>

                    <!-- Kampanye -->
                    <div class="mt-6">
                        <p class="px-6 mb-3 text-xs font-semibold uppercase text-slate-400">Kampanye</p>
                        <a href="{{ route('admin.campaigns') }}" class="block px-6 py-3 {{ request()->routeIs('admin.campaigns') ? 'bg-cyan-50 border-l-4 border-cyan-500 text-cyan-700' : 'text-slate-600 hover:text-slate-900 hover:bg-gray-50' }} transition duration-200 flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span class="font-semibold">Kelola Kampanye</span>
                        </a>
                        <a href="{{ route('admin.campaigns.create') }}" class="block px-6 py-3 {{ request()->routeIs('admin.campaigns.create') ? 'bg-cyan-50 border-l-4 border-cyan-500 text-cyan-700' : 'text-slate-600 hover:text-slate-900 hover:bg-gray-50' }} transition duration-200 flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                            <span class="font-semibold">Buat Kampanye</span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="mt-8 border-t border-gray-200"></div>

                    <!-- Logout -->
                    <div class="mt-6">
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="flex items-center w-full gap-3 px-6 py-3 text-left transition duration-200 text-slate-600 hover:text-slate-900 hover:bg-gray-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span class="font-semibold">Keluar</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            <!-- Main Content - Mulai setelah sidebar width -->
            <div class="w-full pl-64">
                <!-- Page Content -->
                <main class="flex-grow p-8">
                    @yield('content')
                    {{ $slot ?? '' }}
                </main>
            </div>
        </div>
    </body>
</html>
