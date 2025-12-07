@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Donasi Saya</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-slate-600 text-sm font-semibold mb-2">Total Donasi</div>
            <div class="text-3xl font-bold text-slate-900">
                Rp {{ number_format($stats['total_donated'], 0, ',', '.') }}
            </div>
            <div class="text-sm text-slate-500 mt-1">Dari {{ $stats['total_campaigns'] }} kampanye</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-slate-600 text-sm font-semibold mb-2">Kampanye Didukung</div>
            <div class="text-3xl font-bold text-slate-900">{{ $stats['campaigns_supported'] }}</div>
            <div class="text-sm text-slate-500 mt-1">Aktif dan selesai</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-slate-600 text-sm font-semibold mb-2">Skor Dampak</div>
            <div class="flex items-center">
                @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 {{ $i < $stats['impact_score'] ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                @endfor
            </div>
            <div class="text-sm text-slate-500 mt-1">Donatur luar biasa</div>
        </div>
    </div>

    <!-- Donation History Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-900">Riwayat Donasi</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Kampanye</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($donations as $donation)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('campaigns.show', $donation->campaign->id) }}" class="text-slate-900 font-semibold hover:text-cyan-600">
                                    {{ $donation->campaign->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $donation->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-900">
                                Rp {{ number_format($donation->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($donation->status === 'successful')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                        ✓ Berhasil
                                    </span>
                                @elseif($donation->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                                        ⏳ Menunggu
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                        ✗ Gagal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('donations.show', $donation->id) }}" class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    </a>
                                    <a href="{{ route('campaigns.show', $donation->campaign->id) }}" class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <p class="text-slate-500 text-lg mb-4">Anda belum melakukan donasi</p>
                                <a href="{{ route('campaigns.index') }}" class="inline-block text-white font-semibold py-2 px-6 rounded-lg bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95">
                                    Jelajahi Kampanye
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($donations->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $donations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
