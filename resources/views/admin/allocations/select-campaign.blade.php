@extends('layouts.admin')

@section('page-title', 'Pilih Kampanye - Buat Alokasi Dana')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('admin.allocations.index') }}" class="text-cyan-600 hover:text-cyan-700 font-semibold inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Alokasi
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Pilih Kampanye</h1>
        <p class="text-slate-600">Pilih kampanye untuk membuat alokasi dana baru</p>
    </div>

    <!-- Search/Filter -->
    <div class="mb-8">
        <input type="text" id="campaignSearch" placeholder="Cari kampanye..." 
               class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
    </div>

    <!-- Campaigns Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($campaigns as $campaign)
        <a href="{{ route('admin.allocations.create', $campaign) }}" 
           class="campaign-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden cursor-pointer"
           data-campaign="{{ strtolower($campaign->title) }}">
            
            <!-- Campaign Image -->
            <div class="h-48 bg-gray-200 relative overflow-hidden flex items-center justify-center">
                @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                         alt="{{ $campaign->title }}" 
                         class="w-full h-full object-cover hover:scale-105 transition duration-300">
                @else
                    <div class="text-center text-slate-400">
                        <svg class="mx-auto h-16 w-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm">Tidak ada gambar</p>
                    </div>
                @endif
            </div>

            <!-- Campaign Info -->
            <div class="p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-2 line-clamp-2">{{ $campaign->title }}</h3>
                
                <!-- Fund Info -->
                <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-slate-600">Dana Terkumpul</p>
                        <p class="font-bold text-cyan-600">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-slate-600">Target Dana</p>
                        <p class="font-bold text-slate-900">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-slate-600">Dana Tersisa</p>
                        <p class="font-bold {{ ($campaign->collected_amount - $campaign->allocations()->sum('amount')) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($campaign->collected_amount - $campaign->allocations()->sum('amount'), 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold text-slate-700">Progress</span>
                        <span class="text-xs font-bold text-cyan-600">{{ min($campaign->progress_percentage, 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-cyan-400 to-blue-500 h-2 rounded-full" 
                             style="width: {{ min($campaign->progress_percentage, 100) }}%"></div>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="flex items-center justify-between">
                    @if($campaign->status === 'active')
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">{{ ucfirst($campaign->status) }}</span>
                    @elseif($campaign->status === 'completed')
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">{{ ucfirst($campaign->status) }}</span>
                    @else
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">{{ ucfirst($campaign->status) }}</span>
                    @endif
                    
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-cyan-50 text-cyan-700">
                        {{ $campaign->allocations()->count() }} alokasi
                    </span>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full bg-white rounded-2xl shadow-lg p-16 text-center">
            <svg class="mx-auto h-16 w-16 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">Tidak Ada Kampanye</h3>
            <p class="text-slate-600">Belum ada kampanye yang tersedia untuk alokasi dana</p>
        </div>
        @endforelse
    </div>
</div>

<script>
document.getElementById('campaignSearch').addEventListener('keyup', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.campaign-card');
    
    cards.forEach(card => {
        const campaignName = card.getAttribute('data-campaign');
        if (campaignName.includes(searchTerm)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>
@endsection
