@extends('layouts.admin')

@section('page-title', 'Donasi Masuk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Donasi Masuk</h1>
    
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <!-- Filter Section -->
        <form method="GET" action="{{ route('admin.donations') }}" class="mb-8">
            <div class="flex gap-4 flex-wrap items-end">
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kampanye</label>
                    <select name="campaign_id" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        <option value="">Semua Kampanye</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Berhasil</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>
                
                <button type="submit" class="bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 text-white font-semibold px-6 py-2 rounded-lg transition-opacity shadow-md">
                    Filter
                </button>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-cyan-50 to-white border-b border-gray-200">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">ID Transaksi</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Donatur</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Kampanye</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-900">Jumlah</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Metode</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-900">Tanggal</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-900">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($donations as $donation)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-sm font-mono text-slate-600">{{ Str::limit($donation->transaction_id, 12) }}</td>
                        <td class="px-6 py-3 text-sm">
                            @if($donation->anonymous)
                                <span class="italic text-slate-500">Anonim</span>
                            @else
                                <span class="text-slate-900 font-medium">{{ $donation->donor_name ?? ($donation->donor->name ?? '-') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-900">{{ $donation->campaign->title ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-right font-semibold text-cyan-700">Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm text-slate-600">{{ $donation->paymentMethod->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($donation->status === 'successful')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Berhasil</span>
                            @elseif($donation->status === 'pending')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Gagal</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-600">{{ $donation->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('admin.donations.show', $donation) }}" class="text-cyan-600 hover:text-cyan-700 font-semibold text-sm hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500">Tidak ada donasi ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $donations->links() }}
        </div>
    </div>
</div>
@endsection
