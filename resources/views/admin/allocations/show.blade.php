@extends('layouts.admin')

@section('page-title', 'Detail Alokasi Dana')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('admin.allocations.index') }}" class="text-cyan-600 hover:text-cyan-700 font-semibold inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Alokasi
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Allocation Details Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-8">Detail Alokasi Dana</h2>
                
                <div class="space-y-8">
                    <!-- Campaign -->
                    <div class="pb-8 border-b border-gray-200">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kampanye</label>
                        <a href="{{ route('admin.campaigns.show', $allocation->campaign) }}" class="text-lg text-cyan-600 hover:text-cyan-700 font-semibold hover:underline">
                            {{ $allocation->campaign->title }}
                        </a>
                    </div>

                    <!-- Description -->
                    <div class="pb-8 border-b border-gray-200">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Deskripsi Kegiatan</label>
                        <p class="text-slate-600 leading-relaxed">{{ $allocation->description }}</p>
                    </div>

                    <!-- Amount -->
                    <div class="pb-8 border-b border-gray-200">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Dana</label>
                        <p class="text-3xl font-bold text-cyan-600">{{ $allocation->formatted_amount }}</p>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-8 pb-8 border-b border-gray-200">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Pengeluaran</label>
                            <p class="text-slate-600">{{ $allocation->allocation_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Pencatatan</label>
                            <p class="text-slate-600">{{ $allocation->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Admin -->
                    <div class="pb-8">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Dicatat Oleh</label>
                        <p class="text-slate-600">{{ $allocation->admin->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Proof Image Card -->
            @if($allocation->proof_image)
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Bukti Foto/Dokumen</h3>
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $allocation->proof_image) }}" 
                         alt="Bukti alokasi" 
                         class="max-w-full h-auto rounded-lg max-h-96 shadow-md cursor-pointer hover:shadow-lg transition duration-200"
                         onclick="window.open(this.src, '_blank')">
                </div>
                <p class="text-sm text-slate-500 mt-4 text-center">
                    Klik gambar untuk melihat ukuran penuh
                </p>
            </div>
            @endif

            <!-- Transparency Info -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-200 p-8">
                <div class="flex items-start gap-4 mb-4">
                    <svg class="w-8 h-8 text-green-600 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold text-green-900 mb-2">Alokasi Transparan</h3>
                        <p class="text-green-700">Alokasi dana ini telah dicatat dan akan ditampilkan kepada semua donor melalui halaman Transparansi Penggunaan Dana</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Campaign Summary -->
            <div class="bg-white rounded-2xl shadow-lg p-8 sticky top-8 space-y-6">
                <h3 class="text-xl font-bold text-slate-900">Ringkasan Kampanye</h3>
                
                <div class="space-y-4">
                    <!-- Funds Collected -->
                    <div class="p-4 bg-gradient-to-br from-cyan-50 to-blue-50 rounded-lg border border-cyan-200">
                        <p class="text-sm text-slate-700 font-semibold mb-1">Dana Terkumpul</p>
                        <p class="text-2xl font-bold text-cyan-600">Rp {{ number_format($allocation->campaign->collected_amount, 0, ',', '.') }}</p>
                    </div>

                    <!-- Target -->
                    <div class="p-4 bg-slate-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-slate-700 font-semibold mb-1">Target Dana</p>
                        <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($allocation->campaign->target_amount, 0, ',', '.') }}</p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm text-slate-700 font-semibold">Progress Kampanye</p>
                            <span class="text-sm font-bold text-cyan-600">{{ min($allocation->campaign->progress_percentage, 100) }}%</span>
                        </div>
                        <div class="w-full bg-gray-300 rounded-full h-3">
                            <div class="bg-gradient-to-r from-cyan-400 to-blue-500 h-3 rounded-full" style="width: {{ min($allocation->campaign->progress_percentage, 100) }}%"></div>
                        </div>
                    </div>

                    <!-- Total Allocations -->
                    <div class="p-4 bg-amber-50 rounded-lg border border-amber-200">
                        <p class="text-sm text-slate-700 font-semibold mb-1">Total Alokasi</p>
                        <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($allocation->campaign->allocations()->sum('amount'), 0, ',', '.') }}</p>
                        <p class="text-xs text-amber-700 mt-2">{{ $allocation->campaign->allocations()->count() }} alokasi dana</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.campaigns.show', $allocation->campaign) }}" class="block w-full bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 text-center">
                        Lihat Kampanye
                    </a>
                    <form action="{{ route('admin.allocations.destroy', $allocation) }}" method="POST" class="block"
                          onsubmit="return confirm('Yakin hapus alokasi ini? Data transparansi donor akan berubah.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                            Hapus Alokasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
