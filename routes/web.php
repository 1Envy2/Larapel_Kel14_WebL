<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\CampaignUpdateController;
use Illuminate\Support\Facades\Route;

// Guest routes (Hanya rute yang bisa diakses tanpa login dan tanpa verifikasi)
// Route home dihapus dari sini
Route::get('/', [CampaignController::class, 'index'])->name('campaigns.landing');
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::get('/transparansi', [AllocationController::class, 'publicIndex'])->name('allocations.index');

// =========================================================================
// RUTE YANG MEMBUTUHKAN LOGIN DAN VERIFIKASI EMAIL (OTP)
// =========================================================================
Route::middleware(['auth', 'verified', \App\Http\Middleware\NoCache::class])->group(function () {
    
    // 1. Rute Utama (HOME/DASHBOARD)
    // Pengguna yang tidak terverifikasi akan dialihkan ke 'verification.notice' (halaman OTP)
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // 2. Rute Khusus DONOR (membutuhkan role 'donor' selain 'auth' dan 'verified')
    Route::middleware('donor')->group(function () {
        
        // Save & Comment Campaign
        Route::post('/campaigns/{campaign}/save', [CampaignController::class, 'save'])->name('campaigns.save');
        Route::post('/campaigns/{campaign}/comment', [CampaignController::class, 'comment'])->name('campaigns.comment');

        // Protected donor routes
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/saved-campaigns', [ProfileController::class, 'savedCampaigns'])->name('profile.saved-campaigns');
        
        // Donations
        Route::get('/donations/create/{campaign}', [DonationController::class, 'create'])->name('donations.create');
        Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
        Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
        Route::get('/donations/history', [DonationController::class, 'history'])->name('donations.history');
        Route::get('/donations/{donation}', [DonationController::class, 'show'])->name('donations.show');
        
        // Profile edit routes for donors
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    
    // 3. Rute Khusus ADMIN (membutuhkan role 'admin' selain 'auth' dan 'verified')
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Donations management
        Route::get('/donations', [AdminController::class, 'donations'])->name('admin.donations');
        Route::get('/donations/{donation}', [AdminController::class, 'donationDetail'])->name('admin.donations.show');
        Route::patch('/donations/{donation}/status', [AdminController::class, 'updateDonationStatus'])->name('admin.donations.updateStatus');
        Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])->name('donations.destroy');
        
        // Allocations management
        Route::get('/allocations', [AllocationController::class, 'index'])->name('admin.allocations.index');
        Route::get('/allocations/select-campaign', [AllocationController::class, 'selectCampaign'])->name('admin.allocations.selectCampaign');
        Route::get('/allocations/create/{campaign}', [AllocationController::class, 'create'])->name('admin.allocations.create');
        Route::post('/allocations/{campaign}', [AllocationController::class, 'store'])->name('admin.allocations.store');
        Route::get('/allocations/{allocation}', [AllocationController::class, 'show'])->name('admin.allocations.show');
        Route::delete('/allocations/{allocation}', [AllocationController::class, 'destroy'])->name('admin.allocations.destroy');
        
        // Campaign CRUD
        Route::get('/campaigns', [AdminController::class, 'campaigns'])->name('admin.campaigns');
        Route::get('/campaigns/create', [AdminController::class, 'createCampaign'])->name('admin.campaigns.create');
        Route::post('/campaigns', [AdminController::class, 'storeCampaign'])->name('admin.campaigns.store');
        Route::get('/campaigns/{campaign}', [AdminController::class, 'showCampaign'])->name('admin.campaigns.show');
        Route::get('/campaigns/{campaign}/edit', [AdminController::class, 'editCampaign'])->name('admin.campaigns.edit');
        Route::patch('/campaigns/{campaign}', [AdminController::class, 'updateCampaign'])->name('admin.campaigns.update');
        Route::delete('/campaigns/{campaign}', [AdminController::class, 'deleteCampaign'])->name('admin.campaigns.destroy');
        
        // Campaign Updates
        Route::post('/campaigns/{campaign}/updates', [CampaignUpdateController::class, 'store'])->name('admin.campaigns.updates.store');
        Route::delete('/campaigns/{campaign}/updates/{update}', [CampaignUpdateController::class, 'destroy'])->name('admin.campaigns.updates.destroy');
    });

});
// =========================================================================

require __DIR__.'/auth.php';