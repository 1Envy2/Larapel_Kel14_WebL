@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <a href="{{ route('campaigns.index') }}" class="inline-flex items-center text-cyan-500 hover:text-cyan-700 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Kampanye
    </a>

    <!-- Campaign Banner Image -->
    <div class="relative h-96 rounded-2xl overflow-hidden shadow-lg mb-8">
        @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-r from-slate-100 to-slate-200 flex items-center justify-center text-slate-400">
                Gambar Kampanye
            </div>
        @endif
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

            <!-- Campaign Story -->
            <div class="mt-10">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">Cerita Kampanye</h2>
                <div class="prose prose-sm max-w-none">
                    <p class="text-slate-700 leading-relaxed">{{ $campaign->description }}</p>
                    @if($campaign->story)
                        <p class="text-slate-700 leading-relaxed mt-4">{{ $campaign->story }}</p>
                    @endif
                </div>
            </div>

            <!-- Updates & News -->
            <div class="mt-10">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v11a2 2 0 01-2 2zM9 5v12m4-12v12"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-slate-900">Update & Berita</h2>
                </div>
                
                <div class="space-y-4">
                    @forelse($campaign->updates as $update)
                        <div class="bg-slate-50 rounded-lg p-4">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $update->title }}</div>
                                <div class="text-sm text-slate-500 mt-1">{{ $update->created_at->format('d M Y H:i') }}</div>
                            </div>
                            <p class="text-slate-700 mt-3">{{ $update->content }}</p>
                        </div>
                    @empty
                        <div class="bg-slate-50 rounded-lg p-6 text-center">
                            <p class="text-slate-600">Penyelenggara kampanye akan memposting update rutin di sini tentang progres, pencapaian, dan dampak dari donasi Anda.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Donor Comments -->
            <div class="mt-10">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-slate-900">Komentar Donatur</h2>
                </div>
                
                @auth
                @if(auth()->user()->isDonor())
                <form method="POST" action="{{ route('campaigns.comment', $campaign->id) }}" class="mb-8">
                    @csrf
                    <div class="bg-slate-50 rounded-lg p-4 mb-4">
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Tulis Komentar</label>
                        <textarea name="message" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Bagikan pengalaman Anda..."></textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="mt-3 px-4 py-2 bg-cyan-500 text-white font-semibold rounded-lg hover:bg-cyan-600">Kirim Komentar</button>
                    </div>
                </form>
                @endif
                @endauth

                <div class="space-y-4">
                    <!-- Display successful donations with amount > 0 -->
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
                                <div class="text-xs font-semibold text-slate-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    Komentar
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

        <!-- Sidebar: Donation Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-20">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Dukung Kampanye Ini</h3>

                @if($progress_percentage < 100)
                <a href="{{ route('donations.create', $campaign->id) }}" class="w-full inline-block text-center text-white font-bold py-3 rounded-lg shadow-md bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 transition-opacity">
                    Donasi Sekarang
                </a>
                @else
                <div class="w-full text-center bg-green-100 text-green-700 font-bold py-3 rounded-lg">
                    Kampanye Selesai
                </div>
                @endif

                <div class="mt-6 space-y-4">
                    <form method="POST" action="{{ route('campaigns.save', $campaign->id) }}">
                        @csrf
                        <button type="submit" class="w-full text-center text-slate-700 font-medium py-2 border-2 border-slate-300 rounded-lg hover:border-slate-400 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>
                            </svg>
                            Simpan
                        </button>
                    </form>
                    <button onclick="navigator.share ? navigator.share({title: '{{ $campaign->title }}', url: '{{ url()->current() }}'}) : alert('Copy link: {{ url()->current() }}')" class="w-full text-center text-slate-700 font-medium py-2 border-2 border-slate-300 rounded-lg hover:border-slate-400 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Bagikan
                    </button>
                </div>

                <div class="mt-6 bg-slate-50 rounded-lg p-4">
                    <div class="text-xs text-slate-500 uppercase font-semibold">Tentang Kampanye</div>
                    <div class="mt-3 space-y-2 text-sm text-slate-600">
                        <div><strong>Kategori:</strong> {{ $campaign->category->name === 'Bencana Alam' ? 'Bencana' : $campaign->category->name }}</div>
                        <div><strong>Penyelenggara:</strong> {{ $campaign->organizer->name }}</div>
                        <div><strong>Dibuat:</strong> {{ $campaign->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Campaigns -->
    <div class="mt-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Kampanye Terkait</h2>
            <a href="{{ route('campaigns.index', ['category' => $campaign->category->name === 'Bencana Alam' ? 'Bencana' : $campaign->category->name]) }}" class="text-cyan-500 hover:text-cyan-700 font-medium">Lihat Semua →</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($campaign->category->campaigns()->where('id', '!=', $campaign->id)->where('status', 'active')->limit(3)->get() as $related)
                <x-campaign-card :campaign="$related" />
            @empty
                <p class="text-slate-500 text-center col-span-3 py-8">Tidak ada kampanye terkait</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
