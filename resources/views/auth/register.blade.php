<x-guest-layout>
    <div class="glassmorphism p-8 space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white">Daftar Akun</h2>
            <p class="text-white text-sm mt-2">Bergabunglah dan mulai berdonasi</p>
        </div>

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
                                
                                // Mapping untuk semua error keys
                                $errorMap = [
                                    // Name
                                    'validation.required' => 'Field ini harus diisi.',
                                    'validation.string' => 'Field ini harus berupa teks.',
                                    'validation.max.string' => 'Field ini tidak boleh lebih dari maks karakter.',
                                    'validation.min.string' => 'Kata sandi minimal 8 karakter.',
                                    
                                    // Email
                                    'validation.email' => 'Format email tidak valid.',
                                    'validation.unique' => 'Email ini sudah terdaftar.',
                                    
                                    // Password
                                    'validation.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
                                    'validation.min' => 'Field ini minimal :min karakter.',
                                    'validation.password' => 'Kata sandi tidak memenuhi kriteria keamanan.',
                                ];
                                
                                // Check for exact matches first
                                foreach ($errorMap as $key => $message) {
                                    if ($error === $key) {
                                        $errorMessage = $message;
                                        break;
                                    }
                                }
                                
                                // If no exact match, do string matching
                                if ($errorMessage === $error) {
                                    if (strpos($error, 'validation.confirmed') !== false || strpos($error, 'confirmed') !== false) {
                                        $errorMessage = 'Konfirmasi kata sandi tidak cocok.';
                                    } elseif (strpos($error, 'validation.min.string') !== false) {
                                        $errorMessage = 'Field ini minimal 8 karakter.';
                                    } elseif (strpos($error, 'validation.min') !== false || strpos($error, 'min') !== false) {
                                        $errorMessage = 'Field ini minimal 8 karakter.';
                                    } elseif (strpos($error, 'validation.required') !== false || strpos($error, 'required') !== false) {
                                        if (strpos($error, 'name') !== false) {
                                            $errorMessage = 'Nama lengkap harus diisi.';
                                        } elseif (strpos($error, 'email') !== false) {
                                            $errorMessage = 'Email harus diisi.';
                                        } elseif (strpos($error, 'password') !== false) {
                                            $errorMessage = 'Kata sandi harus diisi.';
                                        } else {
                                            $errorMessage = 'Field ini harus diisi.';
                                        }
                                    } elseif (strpos($error, 'validation.email') !== false || strpos($error, 'email') !== false) {
                                        $errorMessage = 'Format email tidak valid.';
                                    } elseif (strpos($error, 'already taken') !== false || strpos($error, 'unique') !== false) {
                                        $errorMessage = 'Email ini sudah terdaftar. Gunakan email lain atau login.';
                                    } elseif (strpos($error, 'validation.string') !== false || strpos($error, 'string') !== false) {
                                        $errorMessage = 'Field ini harus berupa teks.';
                                    } elseif (strpos($error, 'validation.max') !== false || strpos($error, 'max') !== false) {
                                        $errorMessage = 'Field ini terlalu panjang.';
                                    } elseif (strpos($error, 'password') !== false && strpos($error, 'uppercase') !== false) {
                                        $errorMessage = 'Kata sandi harus mengandung huruf besar (A-Z).';
                                    } elseif (strpos($error, 'password') !== false && strpos($error, 'lowercase') !== false) {
                                        $errorMessage = 'Kata sandi harus mengandung huruf kecil (a-z).';
                                    } elseif (strpos($error, 'password') !== false && strpos($error, 'number') !== false) {
                                        $errorMessage = 'Kata sandi harus mengandung angka (0-9).';
                                    } elseif (strpos($error, 'password') !== false && strpos($error, 'symbol') !== false) {
                                        $errorMessage = 'Kata sandi harus mengandung simbol.';
                                    } elseif (strpos($error, 'password') !== false) {
                                        $errorMessage = 'Kata sandi tidak memenuhi kriteria keamanan (minimal 8 karakter, uppercase, lowercase, angka).';
                                    }
                                }
                            @endphp
                            {{ $errorMessage }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-white mb-2">Nama Lengkap</label>
                <input 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    autocomplete="name"
                    class="w-full px-4 py-3 bg-white bg-opacity-80 border {{ $errors->has('name') ? 'border-red-400 border-opacity-50' : 'border-white border-opacity-20' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:border-transparent transition text-black placeholder-gray-400"
                    placeholder="Masukkan nama lengkap"
                />
                @error('name')
                    <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-white mb-2">Email</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
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
                    autocomplete="new-password"
                    class="w-full px-4 py-3 bg-white bg-opacity-80 border {{ $errors->has('password') ? 'border-red-400 border-opacity-50' : 'border-white border-opacity-20' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:border-transparent transition text-black placeholder-gray-400"
                    placeholder="Minimal 8 karakter"
                />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-white mb-2">Konfirmasi Kata Sandi</label>
                <input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    class="w-full px-4 py-3 bg-white bg-opacity-80 border {{ $errors->has('password_confirmation') ? 'border-red-400 border-opacity-50' : 'border-white border-opacity-20' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:border-transparent transition text-black placeholder-gray-400"
                    placeholder="Ulangi kata sandi Anda"
                />
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white font-semibold py-3 rounded-xl hover:from-cyan-500 hover:to-blue-600 transition-all duration-200 shadow-lg hover:shadow-xl mt-6 backdrop-blur-md"
            >
                Daftar Sekarang
            </button>

            <!-- Divider -->
            <div class="flex items-center my-4">
                <div class="flex-1 border-t border-white border-opacity-30"></div>
                <div class="px-3 text-white text-sm font-medium">atau</div>
                <div class="flex-1 border-t border-white border-opacity-30"></div>
            </div>

            <!-- Google Register Button -->
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
                Daftar dengan Google
            </a>
        </form>

        <!-- Navigation -->
        <div class="text-center mt-4">
            <p class="text-white text-sm">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-semibold text-white hover:text-cyan-200 transition">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
