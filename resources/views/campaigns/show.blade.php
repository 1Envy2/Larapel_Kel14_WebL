@extends('layouts.app')

@section('content')
<div class="max-w-4xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
    <!-- Back Button -->
    <a href="{{ route('campaigns.index') }}" class="inline-flex items-center mb-6 text-cyan-500 hover:text-cyan-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Kampanye
    </a>

    <!-- Campaign Banner Image -->
    <div class="relative mb-8 overflow-hidden shadow-lg h-96 rounded-2xl">
        @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="object-cover w-full h-full">
        @else
            <div class="flex items-center justify-center w-full h-full bg-gradient-to-r from-slate-100 to-slate-200 text-slate-400">
                Gambar Kampanye
            </div>
        @endif
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

            <!-- Campaign Story -->
            <div class="mt-10">
                <h2 class="mb-4 text-2xl font-bold text-slate-900">Cerita Kampanye</h2>
                <div class="prose-sm prose max-w-none">
                    <p class="leading-relaxed text-slate-700">{{ $campaign->description }}</p>
                    @if($campaign->story)
                        <p class="mt-4 leading-relaxed text-slate-700">{{ $campaign->story }}</p>
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
                        <div class="p-4 rounded-lg bg-slate-50">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $update->title }}</div>
                                <div class="mt-1 text-sm text-slate-500">{{ $update->created_at->format('d M Y H:i') }}</div>
                            </div>
                            <p class="mt-3 text-slate-700">{{ $update->content }}</p>
                        </div>
                    @empty
                        <div class="p-6 text-center rounded-lg bg-slate-50">
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
                    <div class="p-4 mb-4 rounded-lg bg-slate-50">
                        <label class="block mb-2 text-sm font-semibold text-slate-900">Tulis Komentar</label>
                        <textarea name="message" rows="3" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Bagikan pengalaman Anda..."></textarea>
                        @error('message')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="px-4 py-2 mt-3 font-semibold text-white rounded-lg bg-cyan-500 hover:bg-cyan-600">Kirim Komentar</button>
                    </div>
                </form>
                @endif
                @endauth

                <div class="space-y-4">
                    <!-- Display successful donations with amount > 0 -->
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
                                <div class="flex items-center gap-1 text-xs font-semibold text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    Komentar
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

        <!-- Sidebar: Donation Card -->
        <div class="lg:col-span-1">
            <div class="sticky p-6 bg-white shadow-lg rounded-2xl top-20">
                <h3 class="mb-4 text-lg font-bold text-slate-900">Dukung Kampanye Ini</h3>

                @if($progress_percentage < 100)
                <a href="{{ route('donations.create', $campaign->id) }}" class="inline-block w-full py-3 font-bold text-center text-white transition-opacity rounded-lg shadow-md bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95">
                    Donasi Sekarang
                </a>
                @else
                <div class="w-full py-3 font-bold text-center text-green-700 bg-green-100 rounded-lg">
                    Kampanye Selesai
                </div>
                @endif

                <div class="mt-6 space-y-4">
                    <form method="POST" action="{{ route('campaigns.save', $campaign->id) }}">
                        @csrf
                    </form>
                    <button onclick="navigator.share ? navigator.share({title: '{{ $campaign->title }}', url: '{{ url()->current() }}'}) : alert('Copy link: {{ url()->current() }}')" class="flex items-center justify-center w-full gap-2 py-2 font-medium text-center border-2 rounded-lg text-slate-700 border-slate-300 hover:border-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Bagikan
                    </button>
                </div>

                <div class="p-4 mt-6 rounded-lg bg-slate-50">
                    <div class="text-xs font-semibold uppercase text-slate-500">Tentang Kampanye</div>
                    <div class="mt-3 space-y-2 text-sm text-slate-600">
                        <div><strong>Kategori:</strong> {{ $campaign->category->name === 'Bencana Alam' ? 'Bencana' : $campaign->category->name }}</div>
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
            <a href="{{ route('campaigns.index', ['category' => $campaign->category->name === 'Bencana Alam' ? 'Bencana' : $campaign->category->name]) }}" class="font-medium text-cyan-500 hover:text-cyan-700">Lihat Semua →</a>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($campaign->category->campaigns()->where('id', '!=', $campaign->id)->where('status', 'active')->limit(3)->get() as $related)
                <x-campaign-card :campaign="$related" />
            @empty
                <p class="col-span-3 py-8 text-center text-slate-500">Tidak ada kampanye terkait</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
