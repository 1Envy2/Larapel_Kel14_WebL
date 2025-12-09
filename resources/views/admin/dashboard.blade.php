@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Dashboard Admin</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="text-2xl font-bold text-cyan-700 mb-2">{{ $stats['total_donations'] }}</div>
            <div class="text-slate-600">Total Donasi</div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="text-2xl font-bold text-cyan-700 mb-2">{{ $stats['active_campaigns'] }}</div>
            <div class="text-slate-600">Kampanye Aktif</div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="text-2xl font-bold text-cyan-700 mb-2">Rp {{ number_format($stats['total_raised'], 0, ',', '.') }}</div>
            <div class="text-slate-600">Total Dana Terkumpul</div>
        </div>
    </div>
    <div class="mb-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Donasi Terbaru (2 kolom) -->
            <div class="lg:col-span-2">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Donasi Terbaru</h2>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-cyan-50 to-white border-b border-gray-200">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Donatur</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Kampanye</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Jumlah</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentDonations as $donation)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-sm text-slate-700">{{ $donation->donor->name ?? '-' }}</td>
                                <td class="px-6 py-3 text-sm text-slate-700">{{ $donation->campaign->title ?? '-' }}</td>
                                <td class="px-6 py-3 text-sm font-semibold text-cyan-700">Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-3 text-sm text-slate-600">{{ $donation->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada donasi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Donatur (1 kolom) -->
            <div>
                <h2 class="text-xl font-bold text-slate-900 mb-4">Top Donatur</h2>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        @forelse($topDonors as $index => $item)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-semibold text-slate-900">{{ $item->donor->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-cyan-700 font-bold ml-11">Rp {{ number_format($item->total_donated ?? 0, 0, ',', '.') }}</p>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-slate-500">Belum ada donatur</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
