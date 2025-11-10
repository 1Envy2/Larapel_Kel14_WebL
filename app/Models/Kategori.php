<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['nama'];
    
    // Relasi: Satu Kategori memiliki banyak Kampanye
    public function kampanye(): HasMany
    {
        return $this->hasMany(Kampanye::class, 'kategori_id');
    }
}