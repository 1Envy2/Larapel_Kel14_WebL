<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Donation extends Model
{
    protected $fillable = [
        'transaction_id',
        'donor_id',
        'campaign_id',
        'amount',
        'payment_method_id',
        'status',
        'proof_image',
        'message',
        'donor_name',
        'donor_email',
        'anonymous',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'anonymous' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->transaction_id) {
                $model->transaction_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the donor
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    /**
     * Get the campaign
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the payment method
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
