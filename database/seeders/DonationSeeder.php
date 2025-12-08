<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Donation::create([
            'transaction_id' => \Illuminate\Support\Str::uuid(),
            'donor_id' => 2,
            'campaign_id' => 1,
            'amount' => 100000,
            'payment_method_id' => 1,
            'proof_image' => null,
            'message' => 'Semoga lancar',
            'status' => 'successful',
            'anonymous' => false,
            'donor_name' => 'Budi Santoso',
            'donor_email' => 'budi@example.com',
        ]);
        
        \App\Models\Donation::create([
            'transaction_id' => \Illuminate\Support\Str::uuid(),
            'donor_id' => 3,
            'campaign_id' => 2,
            'amount' => 250000,
            'payment_method_id' => 1,
            'proof_image' => null,
            'message' => 'Semoga cepat pulih',
            'status' => 'pending',
            'anonymous' => false,
            'donor_name' => 'Siti Rahayu',
            'donor_email' => 'siti@example.com',
        ]);
        
        \App\Models\Donation::create([
            'transaction_id' => \Illuminate\Support\Str::uuid(),
            'donor_id' => 4,  // Bukan null - tetap terisi agar admin bisa trace siapa
            'campaign_id' => 1,
            'amount' => 50000,
            'payment_method_id' => 1,
            'proof_image' => null,
            'message' => 'Donasi anonim untuk kampanye ini',
            'status' => 'failed',
            'anonymous' => true,
            'donor_name' => 'Anonim',  // Untuk publik lihat "Anonim"
            'donor_email' => null,
        ]);
    }
}
