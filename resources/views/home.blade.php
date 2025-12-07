@extends('layouts.app')

@section('title', 'Beranda - HopeFund')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-cyan-50 to-white py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight">
                    Buat Perbedaan<br>Hari Ini
                </h1>
                <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                    Terhubung dengan kampanye yang penting. Dukung alasan yang Anda pedulikan dan ubah kehidupan. Setiap donasi menciptakan dampak nyata.
                </p>
                <div class="mt-8 flex flex-col md:flex-row gap-4">
                    <a href="{{ route('campaigns.index') }}" class="inline-block text-center text-white font-bold py-3 px-8 rounded-lg shadow-md bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 transition-opacity">
                        Mulai Mendonasi →
                    </a>
                    <a href="#" class="inline-block text-center text-slate-700 font-semibold py-3 px-8 rounded-lg border-2 border-slate-300 hover:border-slate-400 transition">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>

            <div class="hidden md:block">
                <div class="bg-gradient-to-br from-cyan-200 to-blue-300 rounded-2xl h-96 flex items-center justify-center">
                    <svg class="w-32 h-32 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="bg-white py-12 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="text-4xl md:text-5xl font-extrabold text-slate-900">{{ $stats['total_donations'] ?? '0' }}</div>
                <p class="text-slate-600 mt-2 font-semibold">Total Donasi</p>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-extrabold text-slate-900">{{ $stats['active_campaigns'] ?? '0' }}</div>
                <p class="text-slate-600 mt-2 font-semibold">Kampanye Aktif</p>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-extrabold text-slate-900">{{ number_format($totalDonors) }}+</div>
                <p class="text-slate-600 mt-2 font-semibold">Donatur Aktif</p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Campaigns -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900">Kampanye Unggulan</h2>
            <p class="text-slate-600 mt-2">Jelajahi kampanye yang membuat dampak nyata sekarang</p>
        </div>
        <a href="{{ route('campaigns.index') }}" class="hidden md:inline-block text-cyan-500 hover:text-cyan-700 font-semibold">Lihat Semua →</a>
    </div>

    <!-- Category Chips -->
    <div class="flex flex-wrap gap-3 mb-8">
    <a href="{{ route('campaigns.index') }}" class="px-4 py-2 {{ !request('category') ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-600' }} rounded-full font-medium">Semua</a>
    <a href="{{ route('campaigns.index', ['category' => 'Pendidikan']) }}" class="px-4 py-2 {{ request('category') === 'Pendidikan' ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-600' }} rounded-full font-medium">Pendidikan</a>
    <a href="{{ route('campaigns.index', ['category' => 'Kesehatan']) }}" class="px-4 py-2 {{ request('category') === 'Kesehatan' ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-600' }} rounded-full font-medium">Kesehatan</a>
    <a href="{{ route('campaigns.index', ['category' => 'Lingkungan']) }}" class="px-4 py-2 {{ request('category') === 'Lingkungan' ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-600' }} rounded-full font-medium">Lingkungan</a>
    <a href="{{ route('campaigns.index', ['category' => 'Bencana']) }}" class="px-4 py-2 {{ request('category') === 'Bencana' ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-600' }} rounded-full font-medium">Bencana Alam</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($featuredCampaigns as $campaign)
            <x-campaign-card :campaign="$campaign" />
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-slate-500 text-lg">Tidak ada kampanye yang tersedia</p>
                <a href="{{ route('campaigns.index') }}" class="mt-4 inline-block text-white font-semibold py-2 px-6 rounded-lg bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95">
                    Jelajahi Kampanye
                </a>
            </div>
        @endforelse
    </div>

    <div class="text-center md:hidden">
        <a href="{{ route('campaigns.index') }}" class="inline-block text-white font-semibold py-3 px-8 rounded-lg bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95">
            Lihat Semua Kampanye
        </a>
    </div>
</div>

<!-- Why Choose HopeFund -->
<div class="bg-gradient-to-r from-slate-50 to-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-slate-900 text-center mb-12">Mengapa Pilih HopeFund?</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <div class="w-16 h-16 mx-auto bg-gradient-to-br from-cyan-200 to-blue-300 rounded-full flex items-center justify-center text-2xl mb-4">
                    🔍
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Transparansi Komunitas</h3>
                <p class="text-slate-600">Dapatkan update rutin dari penyelenggara kampanye. Lihat bagaimana donasi Anda digunakan.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <div class="w-16 h-16 mx-auto bg-gradient-to-br from-cyan-200 to-blue-300 rounded-full flex items-center justify-center text-2xl mb-4">
                    <svg class="w-12 h-12 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Pelacakan Dampak Nyata</h3>
                <p class="text-slate-600">Rayakan pencapaian dan lihat hasil dari kontribusi Anda untuk kampanye yang Anda dukung.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <div class="w-16 h-16 mx-auto bg-gradient-to-br from-cyan-200 to-blue-300 rounded-full flex items-center justify-center text-2xl mb-4">
                    🔒
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Cepat & Aman</h3>
                <p class="text-slate-600">Transaksi donasi Anda aman dan terenkripsi. Selalu dilindungi dengan teknologi terkini.</p>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials / Stories -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-extrabold text-slate-900 text-center mb-12">Cerita dari Komunitas Kami</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center mb-4">
                @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                @endfor
            </div>
            <p class="text-slate-700 mb-4 italic">"HopeFund membuat sangat mudah untuk mendukung alasan yang saya pedulikan. Antarmuka intuitif dan transparansi nyata."</p>
            <div class="font-semibold text-slate-900">Sarah Johnson</div>
            <div class="text-sm text-slate-500">Donatur Reguler</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center mb-4">
                @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                @endfor
            </div>
            <p class="text-slate-700 mb-4 italic">"Platform ini benar-benar membuat perbedaan. Kami mengorganisir kampanye dan menerima dukungan luar biasa dari komunitas."</p>
            <div class="font-semibold text-slate-900">Michael Chen</div>
            <div class="text-sm text-slate-500">Penyelenggara Kampanye</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center mb-4">
                @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                @endfor
            </div>
            <p class="text-slate-700 mb-4 italic">"Berkat donasi dari HopeFund, kami dapat melayani lebih banyak penerima manfaat. Terima kasih kepada semua pendonor!"</p>
            <div class="font-semibold text-slate-900">Amelia Torres</div>
            <div class="text-sm text-slate-500">Penerima Manfaat</div>
        </div>
    </div>
</div>

<!-- Final CTA -->
<div class="bg-gradient-to-r from-cyan-400 to-blue-500 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Siap Membuat Dampak?</h2>
        <p class="text-lg mb-8 opacity-95">Bergabunglah dengan ribuan donatur yang mendukung alasan yang penting. Donasi Anda dimulai hari ini.</p>
        <a href="{{ route('campaigns.index') }}" class="inline-block text-white font-bold py-3 px-8 rounded-lg shadow-lg bg-white bg-opacity-20 hover:bg-opacity-30 transition border-2 border-white">
            Jelajahi Kampanye Sekarang →
        </a>
    </div>
</div>
@endsection
