<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DonationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewDonation(User $user, Donation $donation): bool
    {
        return $user->id === $donation->donor_id || $user->role_id === 1;
    }

    /**
     * Determine whether the user can create models.
     */
    public function isDonor(User $user): bool
    {
        return $user->role_id === 2;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Donation $donation): bool
    {
        return $user->role_id === 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function isAdmin(User $user): bool
    {
        return $user->role_id === 1;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Donation $donation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Donation $donation): bool
    {
        return false;
    }
}
