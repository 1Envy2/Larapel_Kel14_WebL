<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SocialUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    /**
     * Redirect to Google OAuth provider
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal menghubungkan ke Google.');
        }

        // Check if social user already exists
        $socialUser = SocialUser::where('provider', 'google')
            ->where('provider_id', $googleUser->getId())
            ->first();

        if ($socialUser) {
            // User already registered via Google
            Auth::login($socialUser->user, true);
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/');
        }

        // Check if user exists by email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // User exists but not linked to Google yet
            // Link this Google account to existing user
            SocialUser::create([
                'user_id' => $user->id,
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'provider_email' => $googleUser->getEmail(),
                'provider_data' => [
                    'name' => $googleUser->getName(),
                    'avatar' => $googleUser->getAvatar(),
                    'email' => $googleUser->getEmail(),
                ],
            ]);

            Auth::login($user, true);
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/');
        }

        // Create new user from Google data
        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'password' => Hash::make(Str::random(24)),
            'avatar' => $googleUser->getAvatar(),
        ]);

        // Assign Donor role to new user (automatically set to 'donor' by default in users table)

        // Create social user record
        SocialUser::create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => $googleUser->getId(),
            'provider_email' => $googleUser->getEmail(),
            'provider_data' => [
                'name' => $googleUser->getName(),
                'avatar' => $googleUser->getAvatar(),
                'email' => $googleUser->getEmail(),
            ],
        ]);

        Auth::login($user, true);
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect('/');
    }
}
