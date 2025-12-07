@extends('layouts.admin')

@section('page-title', 'Buat Alokasi Dana')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('admin.allocations.index') }}" class="text-cyan-600 hover:text-cyan-700 font-semibold inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Alokasi
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $campaign->title }}</h1>
            <p class="text-slate-600">Buat alokasi dana untuk kampanye ini dan bagikan transparansi kepada donor</p>
        </div>

        <!-- Fund Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-6 bg-gradient-to-r from-slate-50 to-gray-50 rounded-lg border border-gray-200">
            <div>
                <p class="text-sm text-slate-700 font-semibold mb-2">Dana Terkumpul</p>
                <p class="text-2xl font-bold text-cyan-600">Rp {{ number_format($totalCollected, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-700 font-semibold mb-2">Dana Dialokasikan</p>
                <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($totalAllocated, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-700 font-semibold mb-2">Dana Tersisa</p>
                <p class="text-2xl font-bold {{ $remainingFunds >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    Rp {{ number_format($remainingFunds, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.allocations.store', $campaign) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-3">Deskripsi Kegiatan <span class="text-red-600">*</span></label>
                <p class="text-xs text-slate-500 mb-3">Jelaskan secara detail kegiatan atau penggunaan dana ini. Penjelasan ini akan ditampilkan kepada semua donor untuk transparansi.</p>
                <textarea name="description" rows="5" 
                          class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500 {{ $errors->has('description') ? 'border-red-500' : 'border-slate-300' }}"
                          placeholder="Contoh: Pembelian peralatan medis untuk klinik kesehatan masyarakat, termasuk 10 unit termometer digital, 5 unit tensimeter digital, dan 50 botol desinfektan antiseptik..." required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount and Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Jumlah Dana <span class="text-red-600">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">Rp</span>
                        <input type="number" name="amount" step="1000" min="10000" 
                               class="w-full border rounded-lg px-4 py-3 pl-12 focus:outline-none focus:ring-2 focus:ring-cyan-500 {{ $errors->has('amount') ? 'border-red-500' : 'border-slate-300' }}"
                               placeholder="100000" value="{{ old('amount') }}" required>
                    </div>
                    @error('amount')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-2">Maksimal: Rp {{ number_format($remainingFunds, 0, ',', '.') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Tanggal Pengeluaran <span class="text-red-600">*</span></label>
                    <input type="date" name="allocation_date" 
                           class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500 {{ $errors->has('allocation_date') ? 'border-red-500' : 'border-slate-300' }}"
                           value="{{ old('allocation_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                    @error('allocation_date')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Proof Image -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-3">Bukti Foto/Dokumen</label>
                <p class="text-xs text-slate-500 mb-3">Unggah bukti foto atau dokumen untuk mendukung transparansi alokasi dana (JPG, PNG, GIF - Max 5MB)</p>
                <div class="border-2 border-dashed border-slate-300 rounded-lg p-8 text-center hover:border-cyan-400 hover:bg-cyan-50 transition duration-200 cursor-pointer" 
                     id="dropZone">
                    <input type="file" name="proof_image" accept="image/jpeg,image/png,image/jpg,image/gif" 
                           class="hidden" id="fileInput">
                    <div id="uploadPrompt">
                        <svg class="mx-auto h-14 w-14 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <p class="text-sm font-semibold text-slate-900">Klik untuk pilih file atau drag & drop</p>
                        <p class="text-xs text-slate-500 mt-2">JPG, PNG, GIF (Max 5MB)</p>
                    </div>
                    <div id="fileName" class="hidden mt-3">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm text-green-600 font-semibold" id="fileNameText"></p>
                        </div>
                    </div>
                </div>
                @error('proof_image')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Transparency Notice -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-900">Transparansi Data</p>
                        <p class="text-sm text-blue-700 mt-1">Alokasi dana ini akan ditampilkan di halaman "Transparansi Penggunaan Dana" sehingga semua donor dapat melihat bagaimana dananya digunakan.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-8 border-t border-gray-200">
                <button type="submit" class="flex-1 bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                    Simpan Alokasi Dana
                </button>
                <a href="{{ route('admin.allocations.index') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold py-3 px-4 rounded-lg transition duration-200 text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const fileName = document.getElementById('fileName');
    const fileNameText = document.getElementById('fileNameText');

    // Click to upload
    dropZone.addEventListener('click', () => fileInput.click());

    // File selection
    fileInput.addEventListener('change', function(e) {
        handleFiles(this.files);
    });

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-cyan-400', 'bg-cyan-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-cyan-400', 'bg-cyan-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-cyan-400', 'bg-cyan-50');
        handleFiles(e.dataTransfer.files);
    });

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            fileInput.files = files;
            fileNameText.textContent = file.name;
            uploadPrompt.classList.add('hidden');
            fileName.classList.remove('hidden');
        }
    }
});
</script>
@endsection
