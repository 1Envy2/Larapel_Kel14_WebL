<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kampanye extends Model
{
    use HasFactory;
    protected $table = 'campaigns';
    
    protected $fillable = [
        'url_foto', 'judul', 'deskripsi', 'target_dana', 
        'terkumpul', 'status', 'kategori_id'
    ];

    // Relasi 1: Kampanye milik satu Kategori (One-to-One Inverse)
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi 2: Satu Kampanye memiliki banyak Donasi
    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class, 'kampanye_id');
    }
    
    // Relasi 3: Satu Kampanye memiliki banyak Alokasi Dana (Transparansi)
    public function alokasiDana(): HasMany
    {
        return $this->hasMany(AlokasiDana::class, 'kampanye_id');
    }
}