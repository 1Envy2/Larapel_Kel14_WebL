@extends('layouts.admin')

@section('page-title', $campaign->title)

@section('content')
<div class="max-w-4xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
    <!-- Back Button -->
    <a href="{{ route('admin.campaigns') }}" class="inline-flex items-center mb-6 text-cyan-500 hover:text-cyan-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Kampanye
    </a>

    <!-- Campaign Banner Image with Edit Button -->
    <div class="relative mb-8 overflow-hidden shadow-lg h-96 rounded-2xl group">
        @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="object-cover w-full h-full">
        @else
            <div class="flex items-center justify-center w-full h-full bg-gradient-to-r from-slate-100 to-slate-200 text-slate-400">
                Gambar Kampanye
            </div>
        @endif

        <!-- Edit Button for Banner -->
        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="absolute p-3 transition bg-white rounded-full shadow-lg opacity-0 top-4 right-4 hover:bg-cyan-50 group-hover:opacity-100">
            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Title and Organizer -->
            <h1 class="text-4xl font-extrabold text-slate-900">{{ $campaign->title }}</h1>
            <div class="flex items-center justify-between mt-3">
                <span class="inline-block px-4 py-1 text-sm font-medium rounded-full bg-cyan-100 text-cyan-700">{{ $campaign->category->name === 'Bencana Alam' ? 'Bencana' : $campaign->category->name }}</span>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mt-8">
                <div class="p-4 text-center rounded-lg bg-slate-50">
                    <div class="text-2xl font-bold text-slate-900">{{ $progress_percentage }}%</div>
                    <div class="mt-1 text-sm text-slate-500">Terpenuhi</div>
                </div>
                <div class="p-4 text-center rounded-lg bg-slate-50">
                    <div class="text-2xl font-bold text-slate-900">{{ $totalDonations }}</div>
                    <div class="mt-1 text-sm text-slate-500">Donatur</div>
                </div>
                @if($progress_percentage < 100)
                <div class="p-4 text-center rounded-lg bg-slate-50">
                    <div class="text-2xl font-bold text-slate-900">{{ $days_remaining ?? '-' }}</div>
                    <div class="mt-1 text-sm text-slate-500">Hari Lagi</div>
                </div>
                @else
                <div class="p-4 text-center rounded-lg bg-green-50">
                    <div class="text-2xl font-bold text-green-600">Selesai</div>
                    <div class="mt-1 text-sm text-green-600">Kampanye Berakhir</div>
                </div>
                @endif
            </div>

            <!-- Progress Bar -->
            <div class="mt-8">
                <div class="w-full h-3 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-3 transition-all duration-500 bg-gradient-to-r from-cyan-400 to-blue-500" style="width: {{ $progress_percentage }}%"></div>
                </div>
                <div class="flex justify-between mt-3 text-sm">
                    <div class="font-semibold text-slate-900">
                        Rp {{ number_format((float)$campaign->collected_amount, 0, ',', '.') }}
                    </div>
                    <div class="text-slate-500">
                        dari Rp {{ number_format((float)$campaign->target_amount, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Campaign Story with Edit Button -->
            <div class="mt-10 group">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-slate-900">Cerita Kampanye</h2>
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="transition opacity-0 group-hover:opacity-100 text-cyan-600 hover:text-cyan-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                </div>
                <div class="prose-sm prose max-w-none">
                    <p class="leading-relaxed text-slate-700">{{ $campaign->description }}</p>
                    @if($campaign->story)
                        <p class="mt-4 leading-relaxed text-slate-700">{{ $campaign->story }}</p>
                    @endif
                </div>
            </div>

            <!-- Updates & News with Edit Button -->
            <div class="mt-10 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.386c.623 0 1.236.195 1.762.576l5.244 3.912c.2.15.49.178.708.063.217-.114.363-.332.363-.566V3.75a.75.75 0 00-.75-.75H3.75A.75.75 0 003 3.75v16.5a.75.75 0 00.75.75h6m12-1.5H9m4.06-12.75l-5.25 3.955A2.25 2.25 0 004.5 10.5H3.75a.75.75 0 00-.75.75v9a.75.75 0 00.75.75h9.75a.75.75 0 00.75-.75v-.906a2.25 2.25 0 00-1.312-2.022l-5.25-3.955"></path>
                        </svg>
                        <h2 class="text-2xl font-bold text-slate-900">Update & Berita</h2>
                    </div>
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="text-sm font-medium transition opacity-0 group-hover:opacity-100 text-cyan-600 hover:text-cyan-700">
                        + Tambah Update
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($campaign->updates as $update)
                        <div class="p-4 rounded-lg bg-slate-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="font-semibold text-slate-900">{{ $update->title }}</div>
                                    <div class="mt-1 text-sm text-slate-500">{{ $update->created_at->format('d M Y H:i') }}</div>
                                </div>
                                <form method="POST" action="{{ route('admin.campaigns.updates.destroy', [$campaign, $update]) }}" style="display:inline;" onsubmit="return confirm('Hapus update ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <p class="mt-3 text-slate-700">{{ $update->content }}</p>
                        </div>
                    @empty
                        <div class="p-6 text-center rounded-lg bg-slate-50">
                            <p class="text-slate-600">Belum ada update untuk kampanye ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Allocations (Fund Usage Transparency) -->
            <div class="mt-10 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3 mb-6">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h2 class="text-2xl font-bold text-slate-900">Alokasi Dana & Transparansi</h2>
                    </div>
                    <a href="{{ route('admin.allocations.create', $campaign) }}" class="px-4 py-2 text-sm font-medium text-white transition rounded-lg opacity-0 group-hover:opacity-100 bg-cyan-600 hover:bg-cyan-700">
                        + Catat Alokasi
                    </a>
                </div>

                @if($campaign->allocations->count() > 0)
                    <div class="space-y-4">
                        @foreach($campaign->allocations as $allocation)
                            <div class="p-4 rounded-lg bg-slate-50">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="font-semibold text-slate-900">{{ $allocation->description }}</div>
                                        <div class="mt-1 text-sm text-slate-500">
                                            Oleh: {{ $allocation->admin->name }} • {{ $allocation->allocation_date->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-cyan-600">{{ $allocation->formatted_amount }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ number_format($allocation->percentage_of_target, 1) }}% dari target</div>
                                    </div>
                                </div>
                                @if($allocation->proof_image)
                                    <div class="mt-3">
                                        <a href="{{ route('admin.allocations.show', $allocation) }}" class="inline-block text-sm font-semibold text-cyan-600 hover:text-cyan-700">
                                            📸 Lihat Bukti
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Allocation Summary -->
                    <div class="p-4 mt-6 border border-blue-200 rounded-lg bg-blue-50">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-slate-600">Total Dialokasikan</p>
                                <p class="text-lg font-bold text-slate-900">Rp {{ number_format($campaign->allocations()->sum('amount'), 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-600">Sisa Dana</p>
                                <p class="text-lg font-bold text-slate-900">Rp {{ number_format($campaign->collected_amount - $campaign->allocations()->sum('amount'), 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-600">Alokasi %</p>
                                <p class="text-lg font-bold text-slate-900">
                                    @if($campaign->collected_amount > 0)
                                        {{ number_format(($campaign->allocations()->sum('amount') / $campaign->collected_amount) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center rounded-lg bg-slate-50">
                        <p class="mb-4 text-slate-600">Belum ada alokasi dana yang dicatat untuk kampanye ini.</p>
                        <a href="{{ route('admin.allocations.create', $campaign) }}" class="inline-block px-4 py-2 font-semibold text-white transition rounded-lg bg-cyan-600 hover:bg-cyan-700">
                            Catat Alokasi Dana Pertama
                        </a>
                    </div>
                @endif
            </div>

            <!-- Donor Comments -->
            <div class="mt-10">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-slate-900">Komentar Donatur</h2>
                </div>
                <div class="space-y-4">
                    @forelse($successfulDonations as $donation)
                        <div class="p-4 rounded-lg bg-slate-50">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="font-semibold text-slate-900">
                                        @if($donation->anonymous)
                                            Donatur Anonim
                                        @elseif($donation->donor && $donation->donor->name)
                                            {{ $donation->donor->name }}
                                        @elseif($donation->donor_name)
                                            {{ $donation->donor_name }}
                                        @else
                                            Donatur Anonim
                                        @endif
                                    </div>
                                    <div class="mt-1 text-sm text-slate-500">{{ $donation->created_at->format('d M Y H:i') }}</div>
                                </div>
                                <div class="text-sm font-semibold text-cyan-600">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </div>
                            </div>
                            @if($donation->message)
                                <p class="mt-3 text-slate-700">{{ $donation->message }}</p>
                            @else
                                <p class="mt-3 italic text-slate-500">Tidak ada pesan</p>
                            @endif
                        </div>
                    @empty
                        
                    @endforelse

                    <!-- Display comments from campaign_comments table -->
                    @forelse($campaign->comments as $comment)
                        <div class="p-4 rounded-lg bg-slate-50">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="font-semibold text-slate-900">
                                        {{ $comment->user->name }}
                                    </div>
                                    <div class="mt-1 text-sm text-slate-500">{{ $comment->created_at->format('d M Y H:i') }}</div>
                                </div>
                                <div class="text-xs font-semibold text-slate-400">
                                    <div class="flex items-center gap-1 text-xs font-semibold text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                        Komentar
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 text-slate-700">{{ $comment->message }}</p>
                        </div>
                    @empty
                    @endforelse

                    @if($successfulDonations->isEmpty() && $campaign->comments->isEmpty())
                        <p class="py-8 text-center text-slate-500">Belum ada donasi atau komentar dari donatur</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar: Admin Actions -->
        <div class="lg:col-span-1">
            <div class="sticky p-6 bg-white shadow-lg rounded-2xl top-20">
                <h3 class="mb-4 text-lg font-bold text-slate-900">Aksi Admin</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="block w-full py-3 font-bold text-center text-white transition rounded-lg bg-cyan-500 hover:bg-cyan-600">
                        Edit Kampanye
                    </a>
                    <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}" onsubmit="return confirm('Hapus kampanye ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 font-bold text-center text-white transition bg-red-500 rounded-lg hover:bg-red-600">
                            Hapus Kampanye
                        </button>
                    </form>
                </div>

                <div class="p-4 mt-6 rounded-lg bg-slate-50">
                    <div class="text-xs font-semibold uppercase text-slate-500">Tentang Kampanye</div>
                    <div class="mt-3 space-y-2 text-sm text-slate-600">
                        <div><strong>Kategori:</strong> {{ $campaign->category->name === 'Bencana Alam' ? 'Bencana' : $campaign->category->name }}</div>
                        <div><strong>Status:</strong> {{ ucfirst($campaign->status) }}</div>
                        <div><strong>Dibuat:</strong> {{ $campaign->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
