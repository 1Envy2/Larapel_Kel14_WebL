@extends('layouts.admin')

@section('page-title', 'Buat Kampanye')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('admin.campaigns') }}" class="text-cyan-600 hover:text-cyan-700 font-semibold">
            ← Kembali ke Kampanye
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Buat Kampanye Baru</h1>
        <p class="text-slate-600 mb-8">Isi detail kampanye untuk memulai penggalangan dana</p>

        <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label class="block text-sm font-bold text-slate-900 mb-2">Judul Kampanye *</label>
                <input type="text" name="title" placeholder="Masukkan judul kampanye" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required value="{{ old('title') }}">
                @error('title')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-bold text-slate-900 mb-2">Deskripsi *</label>
                <textarea name="description" rows="4" placeholder="Jelaskan tujuan kampanye..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Story -->
            <div>
                <label class="block text-sm font-bold text-slate-900 mb-2">Cerita Lengkap</label>
                <textarea name="story" rows="4" placeholder="Cerita detail tentang kampanye..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">{{ old('story') }}</textarea>
                @error('story')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid 2 kolom untuk fields yang lebih kecil -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Kategori *</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Amount -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Target Dana (Rp) *</label>
                    <input type="number" name="target_amount" placeholder="Masukkan target dana" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required value="{{ old('target_amount') }}" step="0.01" min="0">
                    @error('target_amount')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid 2 kolom untuk End Date dan Image -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- End Date -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Tanggal Berakhir</label>
                    <input type="date" name="end_date" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" value="{{ old('end_date') }}">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Gambar Kampanye</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    @error('image')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-6">
                <button type="submit" class="w-full text-white font-semibold py-3 px-4 rounded-lg shadow-md bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 transition-opacity">
                    Buat Kampanye
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
