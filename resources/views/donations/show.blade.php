@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Detail Donasi</h1>
                <p class="text-slate-600 mt-2">ID Transaksi: <span class="font-mono text-cyan-600">{{ $donation->transaction_id }}</span></p>
            </div>
            <div class="text-right">
                @if($donation->status === 'successful')
                    <div class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-700">Berhasil</div>
                @elseif($donation->status === 'pending')
                    <div class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">Menunggu Konfirmasi</div>
                @else
                    <div class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-700">Gagal</div>
                @endif
            </div>
        </div>

        <!-- Campaign Info -->
        <div class="bg-slate-50 rounded-lg p-6 mb-8 border-l-4 border-cyan-500">
            <h2 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-2">Kampanye Tujuan</h2>
            <h3 class="text-xl font-bold text-slate-900">{{ $donation->campaign->title }}</h3>
            <p class="text-slate-600 mt-2">{{ Str::limit($donation->campaign->description, 200) }}</p>
            <a href="{{ route('campaigns.show', $donation->campaign) }}" class="text-cyan-600 hover:text-cyan-700 font-semibold mt-4 inline-block">
                Lihat Kampanye →
            </a>
        </div>

        <!-- Donation Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Amount -->
            <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-lg p-6">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Jumlah Donasi</p>
                <p class="text-3xl font-bold text-cyan-600 mt-2">Rp {{ number_format($donation->amount) }}</p>
            </div>

            <!-- Payment Method -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-6">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Metode Pembayaran</p>
                <p class="text-2xl font-bold text-purple-600 mt-2">{{ $donation->paymentMethod->name }}</p>
            </div>

            <!-- Date -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Tanggal Donasi</p>
                <p class="text-lg font-semibold text-green-600 mt-2">{{ $donation->created_at->format('d F Y H:i') }}</p>
            </div>

            <!-- Donor Info -->
            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-lg p-6">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide">Donatur</p>
                @if($donation->anonymous || !$donation->donor)
                    <p class="text-lg font-semibold text-orange-600 mt-2">Anonim</p>
                @else
                    <p class="text-lg font-semibold text-orange-600 mt-2">{{ $donation->donor->name }}</p>
                @endif
            </div>
        </div>

        <!-- Proof Image (if bank transfer) -->
        @if($donation->paymentMethod->name === 'Bank Transfer' && $donation->proof_image)
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Bukti Pembayaran</h3>
                <div class="bg-slate-100 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $donation->proof_image) }}" alt="Bukti Pembayaran" class="w-full h-auto max-h-96 object-cover">
                </div>
            </div>
        @endif

        <!-- Message -->
        @if($donation->message)
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 rounded">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide mb-2">Pesan Donatur</p>
                <p class="text-slate-700 italic">{{ $donation->message }}</p>
            </div>
        @endif

        <!-- Status Timeline -->
        <div class="bg-slate-50 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Status Donasi</h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-white rounded-lg border-l-4 border-green-500">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-700">Donasi Diterima</p>
                </div>

                <div class="flex items-center gap-3 p-3 bg-white rounded-lg {{ $donation->status === 'pending' ? 'border-l-4 border-yellow-500' : ($donation->status === 'successful' ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500') }}">
                    @if($donation->status === 'pending')
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    @elseif($donation->status === 'successful')
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    @endif
                    <p class="text-sm font-semibold text-slate-700">
                        @if($donation->status === 'pending') Menunggu Konfirmasi Admin
                        @elseif($donation->status === 'successful') Donasi Dikonfirmasi
                        @else Donasi Ditolak
                        @endif
                    </p>
                </div>
            </div>

            @if($donation->status === 'pending')
                <p class="text-yellow-700 bg-yellow-50 border border-yellow-200 rounded p-4 mt-6 text-sm">
                    <strong>⏳ Menunggu Konfirmasi:</strong> Donasi Anda sedang menunggu verifikasi admin. Biasanya diproses dalam 1-24 jam.
                </p>
            @elseif($donation->status === 'failed')
                <p class="text-red-700 bg-red-50 border border-red-200 rounded p-4 mt-6 text-sm">
                    <strong>Donasi Ditolak:</strong> Mohon maaf, donasi Anda tidak dapat diproses. Silakan hubungi admin untuk informasi lebih lanjut.
                </p>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <a href="{{ route('donations.index') }}" class="flex-1 bg-slate-600 hover:bg-slate-700 text-white font-semibold py-3 rounded-lg text-center transition">
                Kembali ke Riwayat Donasi
            </a>
            <a href="{{ route('campaigns.show', $donation->campaign) }}" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 rounded-lg text-center transition">
                Lihat Kampanye
            </a>
        </div>
    </div>
</div>
@endsection
