<nav class="sticky top-0 z-50 bg-white shadow-lg">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">HopeFund</a>
            
            <div class="hidden space-x-6 md:flex">
                <a href="{{ route('campaigns.index') }}" class="text-gray-700 transition hover:text-blue-600">Kampanye</a>
                <a href="{{ route('allocations.index') }}" class="text-gray-700 transition hover:text-blue-600">Transparansi</a>
                @if (auth()->check() && auth()->user()->isDonor())
                    <a href="{{ route('donations.history') }}" class="text-gray-700 transition hover:text-blue-600">Donasi Saya</a>
                @endif
                @if (auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 transition hover:text-blue-600">Admin</a>
                @endif
            </div>

            <div class="flex items-center space-x-4">
                @if (auth()->check())
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-blue-600">{{ auth()->user()->name }}</button>
                        <div class="absolute right-0 z-50 hidden w-48 py-2 bg-white rounded-lg shadow-lg group-hover:block">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-left text-gray-700 hover:bg-gray-100">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">Daftar</a>
                @endif
            </div>
        </div>
    </div>
</nav>
