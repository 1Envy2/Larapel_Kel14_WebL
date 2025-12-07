<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignComment extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'message',
    ];

    /**
     * Get the campaign this comment belongs to
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the user who posted the comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
