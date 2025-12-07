<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'HopeFund - Platform Donasi Online')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="flex-grow">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded m-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded m-4" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
                {{ $slot ?? '' }}
            </main>

            <!-- Footer -->
            <footer class="bg-gray-800 text-white mt-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-lg font-bold mb-4">HopeFund</h3>
                            <p class="text-gray-400">Platform donasi online untuk membantu orang-orang yang membutuhkan.</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold mb-4">Menu</h3>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                                <li><a href="{{ route('campaigns.index') }}" class="hover:text-white">Kampanye</a></li>
                                <li><a href="{{ route('allocations.index') }}" class="hover:text-white">Transparansi</a></li>
                                @if (auth()->check())
                                    <li><a href="{{ route('profile.edit') }}" class="hover:text-white">Profil</a></li>
                                @endif
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold mb-4">Hubungi Kami</h3>
                            <p class="text-gray-400">
                                Email: info@hopefund.com<br>
                                Telepon: +62 821 3456 7890<br>
                                Alamat: Jakarta, Indonesia
                            </p>
                        </div>
                    </div>
                    <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                        <p>&copy; 2025 HopeFund. Semua hak dilindungi.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
