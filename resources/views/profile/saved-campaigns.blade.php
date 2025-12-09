@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
                        <p class="text-3xl font-bold text-cyan-600">Rp{{ number_format($totalDonated, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center border-b border-gray-200 pb-5">
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Kampanye Didukung</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $campaignsSupported }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">Donor Sejak</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $donorSince }}</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="border-t border-gray-300 mt-6 pt-6 space-y-3">
                    <a href="{{ route('profile.saved-campaigns') }}" class="flex items-center gap-3 text-cyan-600 font-bold text-sm transition py-2">
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
        <div class="lg:col-span-3">
            <!-- SAVED CAMPAIGNS SECTION -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Kampanye Tersimpan</h2>

                @if($savedCampaigns->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        <p class="text-gray-500 text-lg">Anda belum menyimpan kampanye apapun.</p>
                        <a href="{{ route('campaigns.index') }}" class="inline-block mt-4 bg-cyan-500 hover:bg-cyan-600 text-white font-medium py-2 px-6 rounded-lg transition">
                            Jelajahi Kampanye
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($savedCampaigns as $saved)
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition">
                                <div class="relative pb-2/3 overflow-hidden bg-gray-200 h-40">
                                    @if($saved->campaign->image)
                                        <img src="{{ asset('storage/' . $saved->campaign->image) }}" alt="{{ $saved->campaign->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-r from-cyan-400 to-blue-500 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                                        </div>
                                    @endif
                                    
                                    @if($saved->campaign->is_completed)
                                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                            <span class="bg-green-500 text-white px-4 py-2 rounded-full font-semibold">Selesai</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">{{ $saved->campaign->title }}</h3>
                                    
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $saved->campaign->description }}</p>
                                    
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-2">
                                            <span class="text-gray-600">Progress</span>
                                            <span class="font-semibold text-cyan-600">{{ round(($saved->campaign->collected_amount / $saved->campaign->target_amount) * 100) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-cyan-400 to-blue-500 h-2 rounded-full" style="width: {{ round(($saved->campaign->collected_amount / $saved->campaign->target_amount) * 100) }}%"></div>
                                        </div>
                                    </div>

                                    <div class="text-sm text-gray-600 mb-4">
                                        <p>Target: <span class="font-semibold">Rp{{ number_format($saved->campaign->target_amount, 0, ',', '.') }}</span></p>
                                        <p>Terkumpul: <span class="font-semibold text-cyan-600">Rp{{ number_format($saved->campaign->collected_amount, 0, ',', '.') }}</span></p>
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="{{ route('campaigns.show', $saved->campaign) }}" class="flex-1 bg-cyan-500 hover:bg-cyan-600 text-white font-medium py-2 px-4 rounded-lg text-center transition">
                                            Lihat Detail
                                        </a>
                                        <form method="POST" action="{{ route('campaigns.save', $saved->campaign) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $savedCampaigns->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
