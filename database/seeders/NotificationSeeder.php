<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Notification for Budi's successful donation
        \App\Models\Notification::create([
            'user_id' => 2,
            'title' => 'Donasi Dikonfirmasi',
            'message' => 'Donasi Anda sebesar Rp 100.000 telah dikonfirmasi untuk kampanye "Biaya Operasi Jantung Ayah Saya"',
            'type' => 'donation_success',
            'data' => [
                'donation_id' => 1,
                'campaign_id' => 1,
                'campaign_title' => 'Biaya Operasi Jantung Ayah Saya',
                'amount' => 100000,
            ],
            'read_at' => null,
        ]);

        // Notification for Siti's pending donation
        \App\Models\Notification::create([
            'user_id' => 3,
            'title' => 'Donasi Diterima',
            'message' => 'Donasi Anda sebesar Rp 250.000 telah diterima dan sedang menunggu verifikasi dari admin',
            'type' => 'donation_success',
            'data' => [
                'donation_id' => 2,
                'campaign_id' => 2,
                'campaign_title' => 'Biaya Pendidikan Anak Kurang Mampu',
                'amount' => 250000,
            ],
            'read_at' => null,
        ]);
    }
}
