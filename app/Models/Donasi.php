<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donasi extends Model
{
    use HasFactory;
    protected $table = 'donations';
    
    protected $fillable = [
        'jumlah', 'metode_pembayaran', 'status', 'pengguna_id', 
        'kampanye_id', 'apakah_anonim', 'id_transaksi', 'rincian_pembayaran'
    ];

    // Relasi 1: Donasi milik satu Pengguna (Bisa null jika anonim)
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    // Relasi 2: Donasi milik satu Kampanye
    public function kampanye(): BelongsTo
    {
        return $this->belongsTo(Kampanye::class, 'kampanye_id');
    }
}