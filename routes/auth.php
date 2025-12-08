    <?php

    use App\Http\Controllers\Auth\AuthenticatedSessionController;
    use App\Http\Controllers\Auth\ConfirmablePasswordController;
    use App\Http\Controllers\Auth\EmailVerificationNotificationController;
    use App\Http\Controllers\Auth\EmailVerificationPromptController;
    use App\Http\Controllers\Auth\NewPasswordController;
    use App\Http\Controllers\Auth\PasswordController;
    use App\Http\Controllers\Auth\PasswordResetLinkController;
    use App\Http\Controllers\Auth\RegisteredUserController;
    use App\Http\Controllers\Auth\SocialiteController;
    use App\Http\Controllers\Auth\VerifyEmailController;
    use App\Http\Controllers\OtpController;
    use Illuminate\Support\Facades\Route;

    Route::middleware(['guest', \App\Http\Middleware\NoCache::class])->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])
            ->name('register');

        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');

        // Google OAuth routes
        Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle'])
            ->name('auth.google');
        Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])
            ->name('auth.google.callback');
    });

    Route::middleware(['auth', \App\Http\Middleware\NoCache::class])->group(function () {
        Route::get('verify-email', [OtpController::class, 'showVerificationForm'])
            ->name('verification.notice');

            Route::post('otp/send', [OtpController::class, 'sendOtp'])
            ->name('otp.send');

        Route::post('otp/verify', [OtpController::class, 'verifyOtp'])
            ->name('otp.verify');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });
