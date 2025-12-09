@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <!-- Success Card -->
    <div class="bg-white rounded-lg shadow-2xl p-12 text-center">
        <!-- Success Icon -->
        <div class="flex justify-center mb-8">
            <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center animate-bounce">
                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Heading -->
        <h1 class="text-4xl font-bold text-slate-900 mb-4">Donasi Berhasil! 🎉</h1>
        <p class="text-xl text-slate-600 mb-8">Terima kasih telah berkontribusi untuk membuat perbedaan</p>

        <!-- Details -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-8 mb-8 border-2 border-green-200">
            <div class="mb-6">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide mb-2">Kampanye</p>
                <h2 class="text-2xl font-bold text-slate-900">{{ $donation->campaign->title }}</h2>
            </div>
            
            <div class="grid grid-cols-2 gap-6 mb-6 border-t border-green-200 pt-6">
                <div>
                    <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide mb-2">Jumlah Donasi</p>
                    <p class="text-3xl font-bold text-green-600">Rp {{ number_format($donation->amount) }}</p>
                </div>
                <div>
                    <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide mb-2">ID Transaksi</p>
                    <p class="text-lg font-mono text-cyan-600 break-all">{{ $donation->transaction_id }}</p>
                </div>
            </div>

            <div class="text-left bg-white rounded p-4">
                <p class="text-slate-600 text-sm font-semibold uppercase tracking-wide mb-2">Metode Pembayaran</p>
                <p class="text-lg font-semibold text-slate-900">{{ $donation->paymentMethod->name }}</p>
            </div>
        </div>

        <!-- Message -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 text-left rounded">
            <h3 class="text-lg font-bold text-slate-900 mb-3">Apa Selanjutnya?</h3>
            <ul class="space-y-3 text-slate-700">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Anda akan menerima email konfirmasi dalam beberapa saat</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Pantau kemajuan kampanye di halaman detail</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Bantu lebih banyak kampanye dengan berbagi kepada teman dan keluarga</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('donations.index') }}" class="flex-1 bg-slate-600 hover:bg-slate-700 text-white font-semibold py-3 rounded-lg transition">
                Lihat Riwayat Donasi
            </a>
            <a href="{{ route('campaigns.show', $donation->campaign) }}?updated=true" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 rounded-lg transition">
                Lihat Kampanye
            </a>
            <a href="{{ route('campaigns.index') }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition">
                Donasi Lagi
            </a>
        </div>

        <!-- Share Section -->
        <div class="mt-12 pt-8 border-t border-slate-200">
            <p class="text-slate-600 font-semibold mb-4">Bagikan Kampanye Ini</p>
            <div class="flex justify-center gap-4">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('campaigns.show', $donation->campaign)) }}" target="_blank" class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('campaigns.show', $donation->campaign)) }}&text=Saya baru saja berdonasi ke kampanye ini di HopeFund!" target="_blank" class="inline-flex items-center justify-center w-12 h-12 bg-blue-400 hover:bg-blue-500 text-white rounded-full transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                </a>
                <a href="https://www.whatsapp.com/send?text=Saya baru saja berdonasi ke kampanye ini di HopeFund! {{ urlencode(route('campaigns.show', $donation->campaign)) }}" target="_blank" class="inline-flex items-center justify-center w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-9.746 9.798c0 2.719.738 5.36 2.138 7.649L2.971 23.848l8.305-2.702c2.226 1.215 4.72 1.858 7.331 1.858h.004c5.404 0 9.859-4.454 9.859-9.853 0-2.621-.898-5.086-2.642-7.088-1.744-2.003-4.171-3.10-6.752-3.10 0 0 0 0 0 0"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
