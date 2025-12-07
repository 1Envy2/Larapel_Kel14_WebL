@extends('layouts.admin')

@section('page-title', 'Alokasi Dana')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Alokasi Dana</h1>
        <a href="{{ route('admin.allocations.selectCampaign') }}" class="bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-md">
            + Buat Alokasi Baru
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <p class="text-sm text-slate-600 font-semibold mb-2">Total Alokasi Dana</p>
            <p class="text-3xl font-bold text-cyan-600">Rp {{ number_format($allocations->sum('amount') + ($allocations->currentPage() - 1) * $allocations->perPage() * 50000000, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <p class="text-sm text-slate-600 font-semibold mb-2">Total Alokasi</p>
            <p class="text-3xl font-bold text-slate-900">{{ $allocations->total() }} Alokasi</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <form method="GET" action="{{ route('admin.allocations.index') }}" class="space-y-4">
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

    <!-- Allocations Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-cyan-50 to-blue-50 border-b-2 border-cyan-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Kampanye</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Dana Terkumpul</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Jumlah Pengeluaran</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-900">Dana Tersisa</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Admin</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-900">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($allocations as $allocation)
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-4 text-slate-900 font-semibold">
                            <a href="{{ route('admin.campaigns.show', $allocation->campaign) }}" class="text-cyan-600 hover:text-cyan-700 hover:underline">
                                {{ $allocation->campaign->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-cyan-600">
                            Rp {{ number_format($allocation->campaign->collected_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-900">
                            {{ $allocation->formatted_amount }}
                        </td>
                        <td class="px-6 py-4 text-right font-semibold {{ ($allocation->campaign->collected_amount - $allocation->campaign->allocations()->sum('amount')) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($allocation->campaign->collected_amount - $allocation->campaign->allocations()->sum('amount'), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-sm">
                            {{ $allocation->allocation_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-sm">
                            {{ $allocation->admin->name }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('admin.allocations.show', $allocation) }}" 
                                   class="text-cyan-600 hover:text-cyan-700 font-semibold text-sm hover:underline">
                                    Lihat
                                </a>
                                <form action="{{ route('admin.allocations.destroy', $allocation) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Yakin hapus alokasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-sm hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12">
                            <div class="text-center">
                                <svg class="mx-auto h-10 w-10 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-slate-600 font-semibold">Tidak ada alokasi dana ditemukan</p>
                                <p class="text-slate-500 text-sm mt-1">Mulai buat alokasi dana baru untuk kampanye</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $allocations->links() }}
        </div>
    </div>
</div>
@endsection
