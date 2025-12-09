<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Notification;
use App\Policies\CampaignPolicy;
use App\Policies\DonationPolicy;
use App\Policies\NotificationPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authorize;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Campaign::class => CampaignPolicy::class,
        Donation::class => DonationPolicy::class,
        Notification::class => NotificationPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }

    /**
     * Register the application's policies.
     */
    public function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            \Illuminate\Support\Facades\Gate::policy($model, $policy);
        }
    }
}
