<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pendidikan',
                'description' => 'Kampanye untuk membantu biaya pendidikan',
            ],
            [
                'name' => 'Kesehatan',
                'description' => 'Kampanye untuk membantu biaya kesehatan dan pengobatan',
            ],
            [
                'name' => 'Lingkungan',
                'description' => 'Kampanye untuk pelestarian lingkungan',
            ],
            [
                'name' => 'Bencana',
                'description' => 'Kampanye untuk membantu korban bencana',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
