<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'description',
        'requires_proof',
    ];

    protected $casts = [
        'requires_proof' => 'boolean',
    ];

    /**
     * Get the donations using this payment method
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
