<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create admin user
        User::create([
            'name' => 'Admin HopeFund',
            'email' => 'admin@hopefund.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'phone' => '08123456789',
            'address' => 'Jakarta, Indonesia',
        ]);

        // Create sample donor users
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'donor',
            'email_verified_at' => now(),
            'phone' => '08987654321',
            'address' => 'Jakarta, Indonesia',
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => Hash::make('password123'),
            'role' => 'donor',
            'email_verified_at' => now(),
            'phone' => '08111222333',
            'address' => 'Surabaya, Indonesia',
        ]);

        // Create additional donor users
        User::factory(5)->create([
            'role' => 'donor',
        ]);
    }
}
