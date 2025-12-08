<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = Category::all();

        $campaigns = [
            [
                'title' => 'Biaya Operasi Jantung Ayah Saya',
                'description' => 'Ayah saya membutuhkan operasi jantung darurat. Kami membutuhkan bantuan untuk biaya operasi dan perawatan di rumah sakit.',
                'target_amount' => 50000000,
                'collected_amount' => 35000000,
                'category_id' => $categories->where('name', 'Kesehatan')->first()?->id ?? 1,
                'status' => 'active',
                'story' => 'Ayah saya berusia 60 tahun dan baru saja didiagnosis dengan penyakit jantung. Dokter merekomendasikan operasi segera. Kami adalah keluarga sederhana dan tidak memiliki cukup biaya untuk operasi ini.',
                'end_date' => now()->addMonths(2),
            ],
            [
                'title' => 'Bantu Pendidikan Anak-anak Kurang Mampu',
                'description' => 'Program beasiswa untuk 50 anak-anak kurang mampu yang ingin melanjutkan pendidikan ke jenjang yang lebih tinggi.',
                'target_amount' => 100000000,
                'collected_amount' => 45000000,
                'category_id' => $categories->where('name', 'Pendidikan')->first()?->id ?? 2,
                'status' => 'active',
                'story' => 'Banyak anak berbakat yang tidak bisa melanjutkan pendidikan karena keterbatasan ekonomi. Mari bersama-sama membantu mereka mencapai impian.',
                'end_date' => now()->addMonths(3),
            ],
            [
                'title' => 'Bantuan untuk Korban Gempa Bumi Cianjur',
                'description' => 'Gempa bumi yang terjadi telah merusak ribuan rumah dan kebutuhan makanan & tempat tinggal darurat sangat dibutuhkan.',
                'target_amount' => 200000000,
                'collected_amount' => 150000000,
                'category_id' => $categories->where('name', 'Bencana')->first()?->id ?? 3,
                'status' => 'active',
                'story' => 'Gempa bumi berkekuatan 5.6 telah mengguncang Cianjur. Ribuan keluarga kehilangan rumah mereka. Bantuan segera sangat dibutuhkan untuk membangun kembali kehidupan mereka.',
                'end_date' => now()->addMonths(1),
            ],
            [
                'title' => 'Program Penanaman Pohon di Jakarta',
                'description' => 'Mari bersama-sama menanam 10.000 pohon untuk mengurangi polusi udara di Jakarta dan sekitarnya.',
                'target_amount' => 75000000,
                'collected_amount' => 60000000,
                'category_id' => $categories->where('name', 'Lingkungan')->first()?->id ?? 5,
                'status' => 'completed',
                'story' => 'Jakarta membutuhkan lebih banyak ruang hijau. Kami ingin menanam 10.000 pohon untuk meningkatkan kualitas udara dan memberikan tempat bermain yang aman untuk anak-anak.',
                'end_date' => now()->subMonths(1),
            ],
            [
                'title' => 'Pondok Pesantren Membutuhkan Renovasi',
                'description' => 'Pondok pesantren kami membutuhkan renovasi infrastruktur untuk menunjang pendidikan 200 santri.',
                'target_amount' => 150000000,
                'collected_amount' => 80000000,
                'category_id' => $categories->where('name', 'Pendidikan')->first()?->id ?? 2,
                'status' => 'active',
                'story' => 'Pondok pesantren kami sudah berdiri selama 50 tahun. Fasilitas sudah mengalami kerusakan dan membutuhkan renovasi untuk memberikan lingkungan belajar yang lebih baik.',
                'end_date' => now()->addMonths(4),
            ],
        ];

        foreach ($campaigns as $campaign) {
            Campaign::create($campaign);
        }
    }
}
