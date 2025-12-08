@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <a href="{{ route('campaigns.show', $campaign->id) }}" class="inline-flex items-center text-cyan-500 hover:text-cyan-700 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Kampanye
    </a>

    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Buat Donasi</h1>
        <p class="text-slate-600 mb-8">Dukung {{ $campaign->title }} dengan donasi Anda</p>

        <form action="{{ route('donations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

            <!-- Donation Amount -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-900 mb-4">Jumlah Donasi *</label>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                    @foreach([10000, 25000, 50000, 100000] as $amount)
                        <button type="button" class="amount-btn px-4 py-3 border-2 border-slate-300 rounded-lg hover:border-cyan-500 hover:bg-cyan-50 font-semibold text-slate-900 transition" data-amount="{{ $amount }}">
                            Rp {{ number_format($amount, 0, ',', '.') }}
                        </button>
                    @endforeach
                </div>

                <input type="number" name="amount" id="amount" placeholder="Masukkan jumlah custom..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                @error('amount')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Your Name -->
            <div class="mb-6" id="donor-name-field">
                <label class="block text-sm font-bold text-slate-900 mb-2">Nama Lengkap *</label>
                <input type="text" name="donor_name" value="{{ old('donor_name', auth()->check() ? auth()->user()->name : '') }}" placeholder="Masukkan nama lengkap Anda" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                @error('donor_name')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-6" id="donor-email-field">
                <label class="block text-sm font-bold text-slate-900 mb-2">Email *</label>
                <input type="email" name="donor_email" value="{{ old('donor_email', auth()->check() ? auth()->user()->email : '') }}" placeholder="email@example.com" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                @error('donor_email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message (Optional) -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-900 mb-2">Pesan (Opsional)</label>
                <textarea name="message" rows="4" placeholder="Bagikan mengapa kampanye ini penting bagi Anda..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent resize-none"></textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Anonymous Donation -->
            <div class="mb-8 flex items-center">
                <input type="hidden" name="anonymous" value="0">
                <input type="checkbox" name="anonymous" id="anonymous" value="1" class="w-4 h-4 text-cyan-500 border-slate-300 rounded focus:ring-2 focus:ring-cyan-500" onchange="toggleDonorFields()">
                <label for="anonymous" class="ml-3 text-sm text-slate-700">Donasi secara anonim</label>
            </div>

            <!-- Payment Method -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-900 mb-4">Metode Pembayaran *</label>

                <div class="space-y-3">
                    <label class="flex items-center p-4 border-2 border-slate-300 rounded-lg cursor-pointer hover:border-cyan-500 hover:bg-cyan-50 transition payment-method" data-method="bank">
                        <input type="radio" name="payment_method_id" value="2" class="w-4 h-4 text-cyan-500" required checked>
                        <span class="ml-3">
                            <div class="font-semibold text-slate-900">Transfer Bank</div>
                            <div class="text-sm text-slate-500">Transfer ke rekening HopeFund dengan bukti yang diunggah</div>
                        </span>
                    </label>
                </div>

                @error('payment_method_id')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-900 mb-2">Bukti Transfer *</label>
                <input type="file" name="proof_image" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                <p class="text-sm text-slate-500 mt-2">Upload screenshot bukti transfer (JPG, PNG, max 2MB)</p>
                @error('proof_image')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Donation Summary -->
            <div class="bg-cyan-50 rounded-lg p-6 mb-8">
                <h3 class="font-bold text-slate-900 mb-4">Ringkasan Donasi</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Jumlah Donasi:</span>
                        <span class="font-semibold text-slate-900" id="amount-display">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Biaya Layanan:</span>
                        <span class="font-semibold text-slate-900" id="fee-display">Rp 0</span>
                    </div>
                    <div class="border-t border-slate-200 pt-3 flex justify-between">
                        <span class="font-bold text-slate-900">Total:</span>
                        <span class="font-bold text-lg text-cyan-600" id="total-display">Rp 0</span>
                    </div>
                </div>
            </div>

            <p class="text-xs text-slate-500 mb-6">Dengan mengirimkan donasi, Anda setuju dengan ketentuan layanan kami. Donasi Anda aman dan terenkripsi.</p>

            <!-- Submit Button -->
            <button type="submit" class="w-full text-white font-bold py-3 rounded-lg shadow-md bg-gradient-to-r from-cyan-400 to-blue-500 hover:opacity-95 transition-opacity">
                Lanjutkan ke Pembayaran
            </button>
        </form>
    </div>
</div>

<script>
    const amountInput = document.getElementById('amount');
    const amountDisplay = document.getElementById('amount-display');
    const feeDisplay = document.getElementById('fee-display');
    const totalDisplay = document.getElementById('total-display');
    const proofUpload = document.getElementById('proof-upload');
    const donorNameField = document.getElementById('donor-name-field');
    const donorEmailField = document.getElementById('donor-email-field');
    const anonymousCheckbox = document.getElementById('anonymous');

    // Amount button handlers
    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            amountInput.value = btn.dataset.amount;
            updateSummary();
            document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('border-cyan-500', 'bg-cyan-50'));
            btn.classList.add('border-cyan-500', 'bg-cyan-50');
        });
    });

    // Amount input change
    amountInput.addEventListener('input', updateSummary);

    // Toggle donor fields when anonymous checkbox is clicked
    function toggleDonorFields() {
        const isAnonymous = anonymousCheckbox.checked;
        const donorNameInput = donorNameField.querySelector('input[name="donor_name"]');
        const donorEmailInput = donorEmailField.querySelector('input[name="donor_email"]');

        if (isAnonymous) {
            // Hide donor fields and remove required attribute
            donorNameField.classList.add('hidden');
            donorEmailField.classList.add('hidden');
            donorNameInput.removeAttribute('required');
            donorEmailInput.removeAttribute('required');
            donorNameInput.value = '';
            donorEmailInput.value = '';
        } else {
            // Show donor fields and add required attribute
            donorNameField.classList.remove('hidden');
            donorEmailField.classList.remove('hidden');
            donorNameInput.setAttribute('required', 'required');
            donorEmailInput.setAttribute('required', 'required');
        }
    }

    function updateSummary() {
        const amount = parseInt(amountInput.value) || 0;
        const fee = Math.ceil(amount * 0.025); // 2.5% fee
        const total = amount + fee;

        amountDisplay.textContent = 'Rp ' + amount.toLocaleString('id-ID');
        feeDisplay.textContent = 'Rp ' + fee.toLocaleString('id-ID');
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Initialize on page load
    updateSummary();
    toggleDonorFields();
</script>
@endsection
