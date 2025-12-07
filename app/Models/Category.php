<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the campaigns in this category
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }
}
