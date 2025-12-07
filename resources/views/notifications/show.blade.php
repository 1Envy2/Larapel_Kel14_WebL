@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Back button -->
    <div class="mb-6">
        <a href="{{ route('notifications.index') }}" class="inline-flex items-center text-cyan-600 hover:text-cyan-700 font-semibold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Notifikasi
        </a>
    </div>

    <!-- Notification detail -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-cyan-50 to-cyan-100 px-6 py-6 sm:px-8 border-b-2 border-cyan-200">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        @if($notification->type === 'donation_success')
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h1 class="text-2xl font-extrabold text-slate-900">Donasi Berhasil Dikonfirmasi</h1>
                        @elseif($notification->type === 'donation_failed')
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <h1 class="text-2xl font-extrabold text-slate-900">Donasi Ditolak</h1>
                        @elseif($notification->type === 'campaign_update')
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v4m6 0a2 2 0 01-2-2V6a2 2 0 012 2v4m0 0a2 2 0 002 2h.01a2 2 0 002-2V6a2 2 0 00-2-2h-.01a2 2 0 00-2 2v4"></path>
                                </svg>
                            </div>
                            <h1 class="text-2xl font-extrabold text-slate-900">Update Kampanye</h1>
                        @else
                            <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <h1 class="text-2xl font-extrabold text-slate-900">Pemberitahuan</h1>
                        @endif
                    </div>
                    <p class="text-sm text-slate-600">
                        {{ $notification->created_at->format('d M Y \p\u\k\u\l H:i') }}
                    </p>
                </div>
                <div class="text-right">
                    @if($notification->read_at)
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Sudah Dibaca</span>
                    @else
                        <span class="inline-block px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-xs font-semibold">Baru</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-8 sm:px-8">
            <!-- Main message -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">{{ $notification->title }}</h2>
                <p class="text-slate-700 leading-relaxed text-base">
                    {{ $notification->message }}
                </p>
            </div>

            <!-- Campaign info if available -->
            @if(isset($notification->data['campaign_title']))
                <div class="bg-slate-50 rounded-lg p-6 mb-8 border border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide mb-2">Kampanye Terkait</h3>
                    <p class="text-slate-900 font-semibold text-lg">{{ $notification->data['campaign_title'] }}</p>
                    @if(isset($notification->data['campaign_id']))
                        <a href="{{ route('campaigns.show', $notification->data['campaign_id']) }}" class="inline-block w-full text-center text-white font-medium py-2 rounded-lg shadow-md bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 transition-opacity">
                            Lihat Kampanye
                        </a>
                    @endif
                </div>
            @endif

            <!-- Donation info if available -->
            @if(isset($notification->data['donation_id']))
                <div class="bg-slate-50 rounded-lg p-6 mb-8 border border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide mb-2">Detail Donasi</h3>
                    @if(isset($notification->data['amount']))
                        <div class="mb-3">
                            <p class="text-slate-600 text-sm">Jumlah Donasi</p>
                            <p class="text-slate-900 font-semibold text-lg">Rp {{ number_format($notification->data['amount'], 0, ',', '.') }}</p>
                        </div>
                    @endif
                    @if(isset($notification->data['campaign_title']))
                        <div class="mb-4">
                            <p class="text-slate-600 text-sm">Untuk Kampanye</p>
                            <p class="text-slate-900 font-semibold">{{ $notification->data['campaign_title'] }}</p>
                        </div>
                    @endif
                    <a href="{{ route('donations.show', $notification->data['donation_id']) }}" class="inline-block w-full text-center text-white font-medium py-2 rounded-lg shadow-md bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 transition-opacity">
                        Lihat Detail Donasi
                    </a>
                </div>
            @endif

            <!-- Rejection reason if available -->
            @if($notification->type === 'donation_failed' && isset($notification->data['reason']) && $notification->data['reason'])
                <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                    <h3 class="text-sm font-semibold text-red-600 uppercase tracking-wide mb-2">Alasan Penolakan</h3>
                    <p class="text-slate-700 leading-relaxed">
                        {{ $notification->data['reason'] }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Footer actions -->
        <div class="bg-slate-50 px-6 py-4 sm:px-8 border-t border-slate-200 flex justify-between items-center">
            <div>
                <p class="text-xs text-slate-500">
                    Diterima: {{ $notification->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('notifications.index') }}" class="inline-block text-center text-white font-medium py-2 px-6 rounded-lg shadow-md bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 transition-opacity">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
