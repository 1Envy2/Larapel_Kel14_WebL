@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="mb-12">
        <h1 class="text-4xl font-bold text-slate-900 mb-4">Transparansi Penggunaan Dana</h1>
        <p class="text-lg text-slate-600">Lihat bagaimana dana yang Anda sumbangkan digunakan untuk membantu masyarakat</p>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <form method="GET" action="{{ route('allocations.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kampanye</label>
                    <select name="campaign_id" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        <option value="">Semua Kampanye</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                {{ $campaign->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" 
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" 
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:opacity-95 transition">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Allocations Grid -->
    <div class="space-y-6">
        @forelse($allocations as $allocation)
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                <!-- Proof Image -->
                <div class="h-64 lg:h-auto bg-gray-200 flex items-center justify-center overflow-hidden">
                    @if($allocation->proof_image)
                        <img src="{{ asset('storage/' . $allocation->proof_image) }}" 
                             alt="Bukti alokasi" 
                             class="w-full h-full object-cover hover:scale-105 transition duration-300 cursor-pointer"
                             onclick="window.open(this.src, '_blank')">
                    @else
                        <div class="text-center text-slate-400">
                            <svg class="mx-auto h-16 w-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm">Tidak ada bukti foto</p>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="lg:col-span-2 p-8">
                    <div class="mb-6">
                        <a href="{{ route('campaigns.show', $allocation->campaign) }}" class="text-cyan-600 hover:text-cyan-700 font-semibold text-lg">
                            {{ $allocation->campaign->title }}
                        </a>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ $allocation->description }}</h3>

                    <!-- Transparency Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-200">
                        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-slate-600 font-semibold mb-1">Dana Terkumpul</p>
                            <p class="text-2xl font-bold text-cyan-600">Rp {{ number_format($allocation->campaign->collected_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-emerald-50 to-green-50 p-4 rounded-lg">
                            <p class="text-sm text-slate-600 font-semibold mb-1">Jumlah Pengeluaran</p>
                            <p class="text-2xl font-bold text-emerald-600">{{ $allocation->formatted_amount }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-amber-50 to-yellow-50 p-4 rounded-lg">
                            <p class="text-sm text-slate-600 font-semibold mb-1">Dana Tersisa</p>
                            <p class="text-2xl font-bold {{ ($allocation->campaign->collected_amount - $allocation->campaign->allocations()->sum('amount')) >= 0 ? 'text-amber-600' : 'text-red-600' }}">
                                Rp {{ number_format($allocation->campaign->collected_amount - $allocation->campaign->allocations()->sum('amount'), 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-lg">
                            <p class="text-sm text-slate-600 font-semibold mb-1">Tanggal Pengeluaran</p>
                            <p class="text-slate-900">{{ $allocation->allocation_date->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Campaign Progress -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-semibold text-slate-700">Progress Kampanye</p>
                            <p class="text-sm font-bold text-cyan-600">{{ min($allocation->campaign->progress_percentage, 100) }}%</p>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-cyan-400 to-blue-500 h-3 rounded-full" 
                                 style="width: {{ min($allocation->campaign->progress_percentage, 100) }}%">
                            </div>
                        </div>
                        <p class="text-xs text-slate-500">
                            Rp {{ number_format($allocation->campaign->collected_amount, 0, ',', '.') }} dari Rp {{ number_format($allocation->campaign->target_amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-lg p-16 text-center">
            <svg class="mx-auto h-16 w-16 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">Tidak Ada Alokasi Dana</h3>
            <p class="text-slate-600">Belum ada alokasi dana yang dicatat untuk kampanye ini</p>
        </div>
        @endforelse
    </div>    <!-- Pagination -->
    @if($allocations->total() > $allocations->perPage())
    <div class="mt-12">
        {{ $allocations->links() }}
    </div>
    @endif
</div>
@endsection
