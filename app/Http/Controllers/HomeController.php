<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Redirect admin to dashboard
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $featuredCampaigns = Campaign::where('status', 'active')
            ->with('organizer', 'category')
            ->withCount(['donations' => function ($q) {
                $q->where('status', 'successful')->where('amount', '>', 0);
            }])
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'total_donations' => Donation::where('status', 'successful')->where('amount', '>', 0)->count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_raised' => Donation::where('status', 'successful')->sum('amount'),
        ];

    $totalDonors = \App\Models\User::where('role_id', 2)->count();
    return view('home', compact('featuredCampaigns', 'stats', 'totalDonors'));
    }
}
