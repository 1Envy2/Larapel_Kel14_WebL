@extends('layouts.admin')

@section('page-title', 'Kelola Kampanye')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-gradient-to-r from-cyan-50 to-white rounded-2xl p-10 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900">Kelola Kampanye</h1>
                <p class="mt-2 text-slate-600">Kelola dan pantau semua kampanye Anda</p>
            </div>
            <a href="{{ route('admin.campaigns.create') }}" class="bg-gradient-to-r from-cyan-400 to-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:opacity-95 transition-opacity shadow-md">+ Kampanye Baru</a>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
            <div class="md:col-span-2">
                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.campaigns') }}" class="flex items-center bg-white rounded-full px-4 py-2 shadow-sm mb-4">
                    <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"></path></svg>
                    <input type="text" name="search" placeholder="Cari kampanye berdasarkan nama atau deskripsi..." class="w-full outline-none text-sm text-slate-600" value="{{ request('search') }}">
                </form>

                <!-- Filter Status -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.campaigns') }}" class="px-4 py-2 {{ !request('status') || request('status') === 'all' ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-600' }} rounded-full text-sm font-medium hover:opacity-90 transition">Semua</a>
                    <a href="{{ route('admin.campaigns', ['status' => 'active']) }}" class="px-4 py-2 {{ request('status') === 'active' ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-600' }} rounded-full text-sm font-medium hover:opacity-90 transition">Aktif</a>
                    <a href="{{ route('admin.campaigns', ['status' => 'completed']) }}" class="px-4 py-2 {{ request('status') === 'completed' ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-600' }} rounded-full text-sm font-medium hover:opacity-90 transition">Selesai</a>
                </div>
            </div>

            <!-- Sort Dropdown -->
            <div class="flex justify-end">
                <form method="GET" action="{{ route('admin.campaigns') }}" class="flex items-center gap-2">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <select name="sort" class="rounded-full border border-slate-200 px-4 py-2 text-sm appearance-none" onchange="this.form.submit()" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23475569%22><path d=%22M6 9l6 6 6-6%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 20px; padding-right: 40px;">
                        <option value="terbaru" {{ request('sort') !== 'terlama' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-8">
        <div class="text-slate-500 mb-4">Menampilkan {{ $campaigns->count() }} kampanye</div>

        @if($campaigns->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($campaigns as $campaign)
                    <x-campaign-card-admin :campaign="$campaign" />
                @empty
                    <div class="text-center py-12 text-slate-500 text-lg col-span-3">Tidak ada kampanye ditemukan</div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $campaigns->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <p class="text-slate-500 text-lg">Tidak ada kampanye yang ditemukan</p>
            </div>
        @endif
    </div>
</div>
@endsection
