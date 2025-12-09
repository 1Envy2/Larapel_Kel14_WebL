@extends('layouts.admin')

@section('page-title', 'Edit Kampanye')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('admin.campaigns') }}" class="inline-flex items-center text-cyan-500 hover:text-cyan-700 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Kampanye
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Edit Form -->
        <div>
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Edit Kampanye</h1>
                <p class="text-slate-600 mb-8">Perbarui detail kampanye</p>

                <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Title -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Judul Kampanye *</label>
                        <input type="text" name="title" placeholder="Masukkan judul kampanye" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required value="{{ old('title', $campaign->title) }}">
                        @error('title')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Deskripsi *</label>
                        <textarea name="description" rows="4" placeholder="Jelaskan tujuan kampanye..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>{{ old('description', $campaign->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Story -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Cerita Lengkap</label>
                        <textarea name="story" rows="4" placeholder="Cerita detail tentang kampanye..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">{{ old('story', $campaign->story) }}</textarea>
                        @error('story')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Kategori *</label>
                        <select name="category_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $campaign->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target Amount -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Target Dana (Rp) *</label>
                        <input type="number" name="target_amount" placeholder="Masukkan target dana" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required value="{{ old('target_amount', $campaign->target_amount) }}" step="0.01" min="0">
                        @error('target_amount')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Status *</label>
                        <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                            <option value="active" {{ old('status', $campaign->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="completed" {{ old('status', $campaign->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Berakhir</label>
                        <input type="date" name="end_date" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" value="{{ old('end_date', $campaign->end_date?->format('Y-m-d')) }}">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Gambar Kampanye</label>
                        @if($campaign->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="h-32 rounded-lg object-cover">
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        @error('image')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full text-white font-bold py-3 rounded-lg shadow-md bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 transition-opacity">
                        Perbarui Kampanye
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Updates & Berita Section -->
        <div>
            <div class="bg-white rounded-2xl shadow-lg p-8 h-fit sticky top-20">
                <div class="flex items-center gap-3 mb-6">
                    <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.386c.623 0 1.236.195 1.762.576l5.244 3.912c.2.15.49.178.708.063.217-.114.363-.332.363-.566V3.75a.75.75 0 00-.75-.75H3.75A.75.75 0 003 3.75v16.5a.75.75 0 00.75.75h6m12-1.5H9m4.06-12.75l-5.25 3.955A2.25 2.25 0 004.5 10.5H3.75a.75.75 0 00-.75.75v9a.75.75 0 00.75.75h9.75a.75.75 0 00.75-.75v-.906a2.25 2.25 0 00-1.312-2.022l-5.25-3.955"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-slate-900">Kelola Update & Berita</h2>
                </div>

                <!-- Add New Update Form -->
                <form action="{{ route('admin.campaigns.updates.store', $campaign) }}" method="POST" class="mb-8 p-6 bg-slate-50 rounded-lg border border-slate-200">
                    @csrf
                    <h3 class="font-bold text-slate-900 mb-4">Tambah Update Baru</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Judul Update *</label>
                        <input type="text" name="title" placeholder="Masukkan judul update..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Konten Update *</label>
                        <textarea name="content" rows="4" placeholder="Tulis konten update..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required></textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-cyan-500 text-white font-bold py-2 rounded-lg hover:bg-cyan-600 transition">
                        + Tambah Update
                    </button>
                </form>

                <!-- Display Existing Updates -->
                <h3 class="font-bold text-slate-900 mb-4">Daftar Update</h3>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($campaign->updates as $update)
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h4 class="font-semibold text-slate-900 text-sm">{{ $update->title }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">{{ $update->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.campaigns.updates.destroy', [$campaign, $update]) }}" style="display:inline;" onsubmit="return confirm('Hapus update ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <p class="text-slate-700 mt-2 text-sm">{{ $update->content }}</p>
                        </div>
                    @empty
                        <div class="p-4 text-center text-slate-500 bg-slate-50 rounded-lg text-sm">
                            Belum ada update untuk kampanye ini
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
