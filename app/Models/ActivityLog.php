<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'actionable_type',
        'actionable_id',
        'description',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the actionable model (Donation, Allocation, etc)
     */
    public function actionable()
    {
        return $this->morphTo();
    }

    /**
     * Static method to log activity
     */
    public static function log(
        int $userId,
        string $action,
        ?string $actionableType = null,
        ?int $actionableId = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'actionable_type' => $actionableType,
            'actionable_id' => $actionableId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
