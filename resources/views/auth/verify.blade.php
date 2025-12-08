@extends('layouts.app')

@section('content')
    <div class="flex flex-col items-center justify-center py-10">

        <!-- Header -->
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800">Verifikasi Email</h2>
            <p class="mt-1 text-sm text-gray-600">
                Kode OTP telah dikirim ke 
                <strong class="text-blue-600">{{ $email ?? 'email@example.com' }}</strong>
            </p>
        </div>

        <!-- Form Card -->
        <div class="w-full max-w-md p-8 bg-white border border-gray-100 shadow-md rounded-2xl">

            <!-- Error -->
            @if ($errors->any())
                <div class="p-3 mb-4 text-sm text-red-700 border border-red-300 rounded-lg bg-red-50">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- OTP Form -->
            <form method="POST" action="{{ route('otp.verify') }}" class="space-y-6">
                @csrf
                
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Label -->
                <label for="otp" class="font-semibold text-gray-800">Kode OTP</label>

                <!-- Input OTP -->
                <input 
                    id="otp" 
                    name="otp" 
                    type="text" 
                    maxlength="6" 
                    inputmode="numeric"
                    placeholder="000000"
                    required
                    class="block mx-auto w-[350px] px-4 py-3 border border-blue-400 rounded-xl text-center 
                           tracking-[10px] text-2xl font-bold text-gray-800 focus:ring-2 focus:ring-blue-300 focus:outline-none"
                />

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button 
                        type="submit"
                        class="w-[300px] p-4 bg-gradient-to-r from-cyan-400 to-blue-500 text-white 
                               font-semibold py-3 rounded-xl shadow hover:scale-[1.03] transition">
                        Verifikasi Sekarang
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="my-5 text-sm text-center text-gray-500">atau</div>

            <!-- Resend Code -->
            <form method="POST" action="{{ route('otp.send') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="flex justify-center">
                    <button 
                        type="submit"
                        class="w-[300px] p-4 bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl shadow 
                               hover:bg-gray-200 transition">
                        Kirim Ulang Kode
                    </button>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">
                    Kembali ke Login
                </a>
            </div>

        </div>
    </div>
@endsection
