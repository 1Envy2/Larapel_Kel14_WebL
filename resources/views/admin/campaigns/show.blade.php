@extends('layouts.admin')

@section('page-title', $campaign->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <a href="{{ route('admin.campaigns') }}" class="inline-flex items-center text-cyan-500 hover:text-cyan-700 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Kampanye
    </a>

    <!-- Campaign Banner Image with Edit Button -->
    <div class="relative h-96 rounded-2xl overflow-hidden shadow-lg mb-8 group">
        @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-r from-slate-100 to-slate-200 flex items-center justify-center text-slate-400">
                Gambar Kampanye
            </div>
        @endif

        <!-- Edit Button for Banner -->
        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="absolute top-4 right-4 bg-white rounded-full p-3 shadow-lg hover:bg-cyan-50 transition opacity-0 group-hover:opacity-100">
            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Title and Organizer -->
            <h1 class="text-4xl font-extrabold text-slate-900">{{ $campaign->title }}</h1>
            <div class="mt-3 flex items-center justify-between">
                <div class="text-slate-600">{{ $campaign->organizer->name }}</div>
                <span class="inline-block bg-cyan-100 text-cyan-700 px-4 py-1 rounded-full text-sm font-medium">{{ $campaign->category->name === 'Bencana Alam' ? 'Bencana' : $campaign->category->name }}</span>
            </div>

            <!-- Stats -->
            <div class="mt-8 grid grid-cols-3 gap-4">
                <div class="bg-slate-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-slate-900">{{ $progress_percentage }}%</div>
                    <div class="text-sm text-slate-500 mt-1">Terpenuhi</div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-slate-900">{{ $totalDonations }}</div>
                    <div class="text-sm text-slate-500 mt-1">Donatur</div>
                </div>
                @if($progress_percentage < 100)
                <div class="bg-slate-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-slate-900">{{ $days_remaining ?? '-' }}</div>
                    <div class="text-sm text-slate-500 mt-1">Hari Lagi</div>
                </div>
                @else
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">Selesai</div>
                    <div class="text-sm text-green-600 mt-1">Kampanye Berakhir</div>
                </div>
                @endif
            </div>

            <!-- Progress Bar -->
            <div class="mt-8">
                <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden">
                    <div class="h-3 bg-gradient-to-r from-cyan-400 to-blue-500 transition-all duration-500" style="width: {{ $progress_percentage }}%"></div>
                </div>
                <div class="mt-3 flex justify-between text-sm">
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
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="opacity-0 group-hover:opacity-100 transition text-cyan-600 hover:text-cyan-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                </div>
                <div class="prose prose-sm max-w-none">
                    <p class="text-slate-700 leading-relaxed">{{ $campaign->description }}</p>
                    @if($campaign->story)
                        <p class="text-slate-700 leading-relaxed mt-4">{{ $campaign->story }}</p>
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
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="opacity-0 group-hover:opacity-100 transition text-cyan-600 hover:text-cyan-700 text-sm font-medium">
                        + Tambah Update
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($campaign->updates as $update)
                        <div class="bg-slate-50 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="font-semibold text-slate-900">{{ $update->title }}</div>
                                    <div class="text-sm text-slate-500 mt-1">{{ $update->created_at->format('d M Y H:i') }}</div>
                                </div>
                                <form method="POST" action="{{ route('admin.campaigns.updates.destroy', [$campaign, $update]) }}" style="display:inline;" onsubmit="return confirm('Hapus update ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <p class="text-slate-700 mt-3">{{ $update->content }}</p>
                        </div>
                    @empty
                        <div class="bg-slate-50 rounded-lg p-6 text-center">
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
                    <a href="{{ route('admin.allocations.create', $campaign) }}" class="opacity-0 group-hover:opacity-100 transition bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                        + Catat Alokasi
                    </a>
                </div>

                @if($campaign->allocations->count() > 0)
                    <div class="space-y-4">
                        @foreach($campaign->allocations as $allocation)
                            <div class="bg-slate-50 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="font-semibold text-slate-900">{{ $allocation->description }}</div>
                                        <div class="text-sm text-slate-500 mt-1">
                                            Oleh: {{ $allocation->admin->name }} • {{ $allocation->allocation_date->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-cyan-600">{{ $allocation->formatted_amount }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ number_format($allocation->percentage_of_target, 1) }}% dari target</div>
                                    </div>
                                </div>
                                @if($allocation->proof_image)
                                    <div class="mt-3">
                                        <a href="{{ route('admin.allocations.show', $allocation) }}" class="inline-block text-cyan-600 hover:text-cyan-700 text-sm font-semibold">
                                            📸 Lihat Bukti
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Allocation Summary -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
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
                    <div class="bg-slate-50 rounded-lg p-6 text-center">
                        <p class="text-slate-600 mb-4">Belum ada alokasi dana yang dicatat untuk kampanye ini.</p>
                        <a href="{{ route('admin.allocations.create', $campaign) }}" class="inline-block bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-4 py-2 rounded-lg transition">
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
                        <div class="bg-slate-50 rounded-lg p-4">
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
                                    <div class="text-sm text-slate-500 mt-1">{{ $donation->created_at->format('d M Y H:i') }}</div>
                                </div>
                                <div class="text-sm font-semibold text-cyan-600">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </div>
                            </div>
                            @if($donation->message)
                                <p class="text-slate-700 mt-3">{{ $donation->message }}</p>
                            @else
                                <p class="text-slate-500 italic mt-3">Tidak ada pesan</p>
                            @endif
                        </div>
                    @empty
                        
                    @endforelse

                    <!-- Display comments from campaign_comments table -->
                    @forelse($campaign->comments as $comment)
                        <div class="bg-slate-50 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="font-semibold text-slate-900">
                                        {{ $comment->user->name }}
                                    </div>
                                    <div class="text-sm text-slate-500 mt-1">{{ $comment->created_at->format('d M Y H:i') }}</div>
                                </div>
                                <div class="text-xs font-semibold text-slate-400">
                                    <div class="text-xs font-semibold text-slate-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                        Komentar
                                    </div>
                                </div>
                            </div>
                            <p class="text-slate-700 mt-3">{{ $comment->message }}</p>
                        </div>
                    @empty
                    @endforelse

                    @if($successfulDonations->isEmpty() && $campaign->comments->isEmpty())
                        <p class="text-slate-500 text-center py-8">Belum ada donasi atau komentar dari donatur</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar: Admin Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-20">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Aksi Admin</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="block w-full text-center bg-cyan-500 text-white font-bold py-3 rounded-lg hover:bg-cyan-600 transition">
                        Edit Kampanye
                    </a>
                    <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}" onsubmit="return confirm('Hapus kampanye ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-center bg-red-500 text-white font-bold py-3 rounded-lg hover:bg-red-600 transition">
                            Hapus Kampanye
                        </button>
                    </form>
                </div>

                <div class="mt-6 bg-slate-50 rounded-lg p-4">
                    <div class="text-xs text-slate-500 uppercase font-semibold">Tentang Kampanye</div>
                    <div class="mt-3 space-y-2 text-sm text-slate-600">
                        <div><strong>Kategori:</strong> {{ $campaign->category->name === 'Bencana Alam' ? 'Bencana' : $campaign->category->name }}</div>
                        <div><strong>Penyelenggara:</strong> {{ $campaign->organizer->name }}</div>
                        <div><strong>Status:</strong> {{ ucfirst($campaign->status) }}</div>
                        <div><strong>Dibuat:</strong> {{ $campaign->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
