<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // If not authenticated, redirect to campaigns page
        if (!Auth::check()) {
            return redirect()->route('campaigns.index');
        }

        // Redirect admin to their dashboard
        if (Auth::user() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Fetch featured campaigns for authenticated donors
        $featuredCampaigns = Campaign::where('status', 'active')
            ->with('category')
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

        $totalDonors = \App\Models\User::where('role', 'donor')->count();
        
        return view('home', compact('featuredCampaigns', 'stats', 'totalDonors'));
    }
}
