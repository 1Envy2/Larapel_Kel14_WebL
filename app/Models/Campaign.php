<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'description',
        'target_amount',
        'collected_amount',
        'image',
        'organizer_id',
        'category_id',
        'status',
        'story',
        'end_date',
        'is_completed',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'end_date' => 'date',
    ];

    /**
     * Get the organizer of the campaign
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get the category of the campaign
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the donations for the campaign
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the comments for the campaign
     */
    public function comments(): HasMany
    {
        return $this->hasMany(CampaignComment::class);
    }

    /**
     * Get the updates for the campaign
     */
    public function updates(): HasMany
    {
        return $this->hasMany(CampaignUpdate::class)->latest();
    }

    /**
     * Get the allocations for the campaign
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class)->latest();
    }

    /**
     * Get the users who saved this campaign
     */
    public function savedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'campaign_user')->withTimestamps();
    }

    /**
     * Get the progress percentage
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount == 0) {
            return 0;
        }
        return min(($this->collected_amount / $this->target_amount) * 100, 100);
    }

    /**
     * Check if campaign is completed (collected_amount >= target_amount)
     */
    public function isCompleted(): bool
    {
        return $this->target_amount > 0 && 
               $this->collected_amount >= $this->target_amount;
    }

    /**
     * Auto-update status to completed if campaign is completed
     * This ensures database status stays in sync with collected amount
     */
    public function updateStatusIfCompleted(): void
    {
        if ($this->isCompleted() && $this->status !== 'completed') {
            $this->update(['status' => 'completed']);
        }
    }
}
