<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create([
            'name' => 'QR Code',
            'description' => 'Pembayaran melalui QR Code (simulasi instant)',
            'requires_proof' => false,
        ]);

        PaymentMethod::create([
            'name' => 'Transfer Bank',
            'description' => 'Pembayaran melalui transfer bank dengan bukti',
            'requires_proof' => true,
        ]);
    }
}
