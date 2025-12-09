<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'address',
        'google_id',
        'google_token',
        'otp_code', 
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the donations for the user
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }

    /**
     * Get the campaigns created by the user
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'organizer_id');
    }

    /**
     * Get the saved campaigns for the user (old relationship)
     */
    public function savedCampaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_user')->withTimestamps();
    }

    /**
     * Get the new saved campaigns relationship
     */
    public function savedCampaignsList(): HasMany
    {
        return $this->hasMany(SavedCampaign::class);
    }

    /**
     * Get total amount donated by this user (successful donations only)
     */
    public function getTotalDonated(): float
    {
        return $this->donations()
            ->where('status', 'successful')
            ->sum('amount');
    }

    /**
     * Get count of distinct campaigns this user has donated to
     */
    public function getCampaignsSupported(): int
    {
        return $this->donations()
            ->where('status', 'successful')
            ->distinct()
            ->count('campaign_id');
    }

    /**
     * Get donor since date formatted as DD Month YYYY in Indonesian
     */
    public function getDonorSince(): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari', 
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $day = $this->created_at->day;
        $month = $months[$this->created_at->month];
        $year = $this->created_at->year;

        return sprintf('%02d %s %d', $day, $month, $year);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is donor
     */
    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    /**
     * Get the social users for this user
     */
    public function socialUsers(): HasMany
    {
        return $this->hasMany(SocialUser::class);
    }
}
