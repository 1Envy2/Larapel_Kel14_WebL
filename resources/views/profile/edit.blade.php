@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Success Message -->
    @if(session('status') == 'profile-updated')
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-green-800 font-medium">Profil Anda berhasil diperbarui.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- LEFT SIDEBAR -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-8">
                <!-- Avatar -->
                <div class="w-28 h-28 rounded-full bg-gradient-to-r from-cyan-400 to-blue-500 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>

                <!-- Name & Email -->
                <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">{{ auth()->user()->name }}</h2>
                <p class="text-sm text-gray-600 text-center mb-6 break-all">{{ auth()->user()->email }}</p>

                <!-- Stats - More Prominent -->
                <div class="border-t border-gray-300 pt-6 pb-6 space-y-5">
                    <div class="text-center border-b border-gray-200 pb-5">
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Total Donasi</p>
                        <p class="text-3xl font-bold text-cyan-600">Rp{{ number_format(auth()->user()->getTotalDonated(), 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center border-b border-gray-200 pb-5">
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Kampanye Didukung</p>
                        <p class="text-3xl font-bold text-gray-900">{{ auth()->user()->getCampaignsSupported() }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Donor Sejak</p>
                        <p class="text-lg font-semibold text-gray-900">{{ auth()->user()->getDonorSince() }}</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="border-t border-gray-300 mt-6 pt-6 space-y-3">
                    <a href="{{ route('profile.saved-campaigns') }}" class="flex items-center gap-3 text-gray-700 hover:text-cyan-600 font-medium text-sm transition py-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        Kampanye Tersimpan
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 text-red-600 hover:text-red-700 font-medium text-sm transition py-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT MAIN CONTENT -->
        <div class="lg:col-span-3 space-y-8">
            <!-- UPDATE PROFILE INFORMATION SECTION -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Informasi Pribadi</h2>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                                required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                                placeholder="Contoh: 081234567890">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea name="address" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                                placeholder="Masukkan alamat lengkap Anda">{{ old('address', auth()->user()->address ?? '') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Save Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white font-bold py-3 px-4 rounded-lg hover:shadow-lg hover:opacity-95 transition">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- CHANGE PASSWORD SECTION -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Ubah Kata Sandi</h2>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi Saat Ini</label>
                        <div class="relative">
                            <input type="password" name="current_password" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition" 
                                placeholder="••••••••"
                                required>
                            <button type="button" class="absolute right-3 top-3 text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi Baru</label>
                        <input type="password" name="password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition" 
                            placeholder="••••••••"
                            required>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition" 
                            placeholder="••••••••"
                            required>
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Update Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white font-bold py-3 px-4 rounded-lg hover:shadow-lg hover:opacity-95 transition">
                        Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
