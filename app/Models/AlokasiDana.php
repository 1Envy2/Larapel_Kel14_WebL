<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlokasiDana extends Model
{
    use HasFactory;
    protected $table = 'fund_allocations';
    
    protected $fillable = [
        'kampanye_id', 'deskripsi', 'jumlah_digunakan', 
        'url_foto_bukti', 'tanggal_pengeluaran'
    ];

    // Relasi: Alokasi Dana milik satu Kampanye
    public function kampanye(): BelongsTo
    {
        return $this->belongsTo(Kampanye::class, 'kampanye_id');
    }
}