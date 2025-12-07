<x-guest-layout>
    <div class="glassmorphism p-8 space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white">Masuk ke Akun</h2>
            <p class="text-white text-sm mt-2">Selamat kembali ke HopeFund</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-500 bg-opacity-20 border border-green-400 border-opacity-30 rounded-xl p-4 backdrop-blur-md">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-green-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-green-200">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-white bg-opacity-20 border border-blue-400 border-opacity-30 rounded-xl p-4 space-y-2 backdrop-blur-md">
                @foreach ($errors->all() as $error)
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-white">
                            @php
                                $errorMessage = $error;
                                if (strpos($error, 'auth.failed') !== false || $error === 'auth.failed') {
                                    $errorMessage = 'Email atau kata sandi salah. Silakan coba lagi.';
                                } elseif (strpos($error, 'auth.throttle') !== false || strpos($error, 'throttle') !== false) {
                                    $errorMessage = 'Terlalu banyak percobaan login. Silakan coba lagi nanti.';
                                }
                            @endphp
                            {{ $errorMessage }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-white mb-2">Email</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    class="w-full px-4 py-3 bg-white bg-opacity-80 border {{ $errors->has('email') ? 'border-red-400 border-opacity-50' : 'border-white border-opacity-20' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:border-transparent transition text-black placeholder-gray-400"
                    placeholder="nama@email.com"
                />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-white mb-2">Kata Sandi</label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    class="w-full px-4 py-3 bg-white bg-opacity-80 border {{ $errors->has('password') ? 'border-red-400 border-opacity-50' : 'border-white border-opacity-20' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:border-transparent transition text-black placeholder-gray-400"
                    placeholder="Masukkan kata sandi Anda"
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-300">Kata sandi salah. Silakan coba lagi.</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white font-semibold py-3 rounded-xl hover:from-cyan-500 hover:to-blue-600 transition-all duration-200 shadow-lg hover:shadow-xl mt-6 backdrop-blur-md"
            >
                Masuk
            </button>

            <!-- Divider -->
            <div class="flex items-center my-4">
                <div class="flex-1 border-t border-white border-opacity-30"></div>
                <div class="px-3 text-white text-sm font-medium">atau</div>
                <div class="flex-1 border-t border-white border-opacity-30"></div>
            </div>

            <!-- Google Login Button -->
            <a 
                href="{{ route('auth.google') }}"
                class="w-full flex items-center justify-center bg-white text-gray-800 font-semibold py-3 rounded-xl hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-xl"
            >
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Masuk dengan Google
            </a>
        </form>

        <!-- Navigation -->
        <div class="text-center mt-4">
            <p class="text-white text-sm">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-semibold text-white hover:text-cyan-200 transition">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
