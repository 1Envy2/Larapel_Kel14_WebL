<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">HopeFund</a>
            
            <div class="hidden md:flex space-x-6">
                <a href="{{ route('campaigns.index') }}" class="text-gray-700 hover:text-blue-600 transition">Kampanye</a>
                <a href="{{ route('allocations.index') }}" class="text-gray-700 hover:text-blue-600 transition">Transparansi</a>
                @if (auth()->check() && auth()->user()->isDonor())
                    <a href="{{ route('donations.history') }}" class="text-gray-700 hover:text-blue-600 transition">Donasi Saya</a>
                    <a href="{{ route('notifications.index') }}" class="text-gray-700 hover:text-blue-600 transition relative">
                        Notifikasi
                        @if (auth()->user()->notifications()->where('read', false)->count() > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-2">{{ auth()->user()->notifications()->where('read', false)->count() }}</span>
                        @endif
                    </a>
                @endif
                @if (auth()->check() && auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Admin</a>
                @endif
            </div>

            <div class="flex items-center space-x-4">
                @if (auth()->check())
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-blue-600">{{ auth()->user()->name }}</button>
                        <div class="hidden group-hover:block absolute right-0 w-48 bg-white shadow-lg rounded-lg py-2 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Daftar</a>
                @endif
            </div>
        </div>
    </div>
</nav>
