<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Admin',
            'description' => 'Administrator dengan akses penuh',
        ]);

        Role::create([
            'name' => 'Donor',
            'description' => 'Pengguna donor yang dapat membuat donasi',
        ]);
    }
}
