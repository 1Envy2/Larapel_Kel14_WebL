<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengguna extends Authenticatable
{
    use HasFactory;
    
    // Nama tabel di database
    protected $table = 'users';

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'nama',
        'email',
        'sandi',
        'peran',
        'nomor_telepon', // Kolom baru
        'alamat',        // Kolom baru
    ];

    // Kolom yang disembunyikan (untuk keamanan)
    protected $hidden = [
        'sandi',
    ];

    // Relasi: Satu Pengguna memiliki banyak Donasi
    public function donasi(): HasMany
    {
        // 'Donasi' adalah nama model Donasi Anda
        return $this->hasMany(Donasi::class, 'pengguna_id');
    }

    // Fungsi untuk mendapatkan Total Donasi (Fitur di Profile)
    public function totalDonasiBerhasil(): int
    {
        return $this->donasi()
                    ->where('status', 'berhasil')
                    ->sum('jumlah');
    }
}