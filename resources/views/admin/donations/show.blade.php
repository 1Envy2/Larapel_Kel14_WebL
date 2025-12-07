@extends('layouts.admin')

@section('page-title', 'Detail Donasi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('admin.donations') }}" class="text-cyan-600 hover:text-cyan-700 font-semibold">
            ← Kembali ke Daftar Donasi
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left column: Donation details -->
        <div class="lg:col-span-2">
            <!-- Donation Info Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Detail Donasi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ID Transaksi</label>
                        <p class="text-slate-600 font-mono bg-gray-50 px-3 py-2 rounded">{{ $donation->transaction_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                        @if($donation->status === 'successful')
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">Berhasil</span>
                        @elseif($donation->status === 'pending')
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">Gagal</span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Donatur</label>
                        <div class="flex items-center gap-2">
                            <p class="text-slate-600">
                                {{ $donation->donor->name ?? $donation->donor_name }}
                            </p>
                            @if($donation->anonymous)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">(Sebagai Anonim)</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email Donatur</label>
                        <p class="text-slate-600">{{ $donation->donor_email ?? ($donation->donor->email ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Metode Pembayaran</label>
                        <p class="text-slate-600">{{ $donation->paymentMethod->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Donasi</label>
                        <p class="text-slate-900 font-bold text-lg">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kampanye</label>
                        <p class="text-slate-600">
                            <a href="{{ route('admin.campaigns.show', $donation->campaign->id ?? '') }}" class="text-cyan-600 hover:text-cyan-700 font-semibold">
                                {{ $donation->campaign->title ?? 'N/A' }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Donasi</label>
                        <p class="text-slate-600">{{ $donation->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @if($donation->message)
                <div class="border-t pt-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pesan Donatur</label>
                    <p class="text-slate-600 italic">{{ $donation->message }}</p>
                </div>
                @endif
            </div>

            <!-- Proof Image Card -->
            @if($donation->proof_image)
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Bukti Pembayaran</h3>
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $donation->proof_image) }}" 
                         alt="Bukti pembayaran" 
                         class="max-w-full h-auto rounded-lg max-h-96 shadow-md">
                </div>
                <p class="text-sm text-slate-500 mt-4 text-center">
                    Klik gambar untuk melihat ukuran penuh
                </p>
            </div>
            @else
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Bukti Pembayaran</h3>
                <p class="text-slate-500 italic text-center py-8">Belum ada bukti pembayaran yang diunggah</p>
            </div>
            @endif
        </div>

        <!-- Right column: Action panel -->
        <div>
            <!-- Status Update Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 sticky top-8">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Perbarui Status</h3>
                
                <!-- Notifikasi hanya di panel ini -->
                @if (session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg flex justify-between items-center shadow-sm" role="alert">
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-700 hover:text-green-900 font-bold">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg flex justify-between items-center shadow-sm" role="alert">
                        <span>{{ session('error') }}</span>
                        <button onclick="this.parentElement.style.display='none'" class="text-red-700 hover:text-red-900 font-bold">&times;</button>
                    </div>
                @endif
                
                <form action="{{ route('admin.donations.updateStatus', $donation) }}" method="POST" class="space-y-4" id="statusForm" onsubmit="handleFormSubmit(event)">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="statusSelect" class="block text-sm font-semibold text-slate-700 mb-2">Status Baru</label>
                        <select name="status" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500" required id="statusSelect">
                            <option value="pending" {{ $donation->status === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="successful" {{ $donation->status === 'successful' ? 'selected' : '' }}>Berhasil</option>
                            <option value="failed" {{ $donation->status === 'failed' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Alasan (opsional)</label>
                        <textarea name="reason" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500" 
                                  placeholder="Contoh: Bukti pembayaran tidak jelas, nominal tidak sesuai, dll" id="reasonText"></textarea>
                    </div>

                    <button type="button" class="w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-white font-semibold py-3 px-4 rounded-lg hover:opacity-95 transition-opacity shadow-md" onclick="toggleConfirmSection()">
                        Simpan Perubahan
                    </button>

                    <!-- Inline Confirmation Section -->
                    <div id="confirmSection" class="hidden mt-6 pt-6 border-t">
                        <h4 class="font-semibold text-slate-900 mb-4">Konfirmasi Perubahan Status</h4>
                        
                        <div class="bg-slate-50 rounded-lg p-4 space-y-3 text-sm mb-4">
                            <div>
                                <p class="text-slate-600">Status Baru:</p>
                                <p class="font-semibold text-slate-900" id="confirmStatus">-</p>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <p class="text-slate-600">Donasi: <span class="font-semibold text-cyan-700">Rp {{ number_format($donation->amount, 0, ',', '.') }}</span></p>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <p class="text-slate-600">Donatur: <span class="font-semibold text-slate-900">{{ $donation->anonymous ? 'Anonim' : ($donation->donor_name ?? ($donation->donor->name ?? 'N/A')) }}</span></p>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <p class="text-slate-600">Kampanye: <span class="font-semibold text-slate-900">{{ Str::limit($donation->campaign->title ?? 'N/A', 25) }}</span></p>
                            </div>
                            <div id="confirmReasonSection" class="hidden border-t border-gray-200 pt-3">
                                <p class="text-slate-600">Alasan:</p>
                                <p class="font-semibold text-slate-900 text-xs" id="confirmReason">-</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" onclick="toggleConfirmSection()" class="flex-1 border border-slate-300 text-slate-700 font-semibold py-2 rounded-lg hover:bg-slate-50 transition">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 bg-gradient-to-r from-cyan-400 to-blue-500 text-white font-semibold py-3 px-4 rounded-lg hover:opacity-95 transition-opacity shadow-md">
                                Lanjutkan
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Status info -->
                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-semibold text-slate-900 mb-3">Keterangan Status:</h4>
                    <div class="space-y-2 text-sm text-slate-600">
                        <div>
                            <span class="font-semibold">Pending:</span> Donasi menunggu verifikasi
                        </div>
                        <div>
                            <span class="font-semibold">Berhasil:</span> Donasi terverifikasi dan dicatat
                        </div>
                        <div>
                            <span class="font-semibold">Gagal:</span> Donasi ditolak
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script for image view and confirmation section -->
@if($donation->proof_image)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const img = document.querySelector('img[alt="Bukti pembayaran"]');
        if (img) {
            img.addEventListener('click', function() {
                window.open(this.src, '_blank');
            });
            img.style.cursor = 'pointer';
        }
    });
</script>
@endif

<script>
    function toggleConfirmSection() {
        const confirmSection = document.getElementById('confirmSection');
        const statusSelect = document.getElementById('statusSelect');
        const reasonText = document.getElementById('reasonText').value;
        
        // If section is currently hidden, show it and populate data
        if (confirmSection.classList.contains('hidden')) {
            const statusText = statusSelect.options[statusSelect.selectedIndex].text;
            document.getElementById('confirmStatus').textContent = statusText;
            
            const confirmReasonSection = document.getElementById('confirmReasonSection');
            if (reasonText.trim()) {
                document.getElementById('confirmReason').textContent = reasonText;
                confirmReasonSection.classList.remove('hidden');
            } else {
                confirmReasonSection.classList.add('hidden');
            }
            
            confirmSection.classList.remove('hidden');
        } else {
            // If section is visible, hide it
            confirmSection.classList.add('hidden');
        }
    }

    function handleFormSubmit(event) {
        // Let form submit normally, then redirect to donations list after brief delay
        // This ensures the backend processes the update before navigation
        setTimeout(() => {
            window.location.href = "{{ route('admin.donations') }}";
        }, 500);
    }
</script>
@endsection
