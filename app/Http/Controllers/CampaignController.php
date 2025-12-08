<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\CampaignComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    // Save campaign for donor
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Campaign::with('category');

        // If sort is 'selesai', show only completed campaigns
        // Otherwise show only active campaigns
        if (request('sort') === 'selesai') {
            $query->where('status', 'completed');
        } else {
            $query->where('status', 'active');
        }

        // Count only successful donations for accurate donor count
        $query->withCount(['donations' => function ($q) {
            $q->where('status', 'successful')->where('amount', '>', 0);
        }]);

        // Fetch categories from database
        $categories = Category::pluck('name')->toArray();

        if (request()->has('category') && request('category') !== 'Semua') {
            $query->whereHas('category', function ($q) {
                $q->where('name', request('category'));
            });
        }

        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%$search%")
                  ->orWhere('description', 'ILIKE', "%$search%");
            });
        }

        // Sort by created_at (terbaru by default)
        if (request('sort') === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $campaigns = $query->paginate(9);
        return view('campaigns.index', compact('campaigns', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        $categories = Category::all();
        return view('campaigns.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'story' => 'nullable|string',
            'target_amount' => 'required|numeric|min:1000',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'end_date' => 'nullable|date|after:today',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $validated['status'] = 'active';
        $validated['collected_amount'] = 0;

        Campaign::create($validated);

        return redirect()->route('campaigns.index')->with('success', 'Kampanye berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        $campaign->load('category', 'donations.donor', 'comments.user');
        $totalDonations = $campaign->donations->where('status', 'successful')->where('amount', '>', 0)->count();
        // Get only successful donations with amount > 0 (exclude comment entries)
        $successfulDonations = $campaign->donations->where('status', 'successful')->where('amount', '>', 0)->values();
        $progress_percentage = $campaign->target_amount > 0
            ? min(100, round(($campaign->collected_amount / $campaign->target_amount) * 100))
            : 0;
        
        // Calculate days remaining - use ceiling to show at least 1 day if still valid
        if ($campaign->end_date) {
            $daysLeft = now()->diffInDays($campaign->end_date, false);
            $days_remaining = $daysLeft < 0 ? null : ($daysLeft === 0 && now()->lessThan($campaign->end_date) ? 1 : (int)$daysLeft);
        } else {
            $days_remaining = null;
        }

        return view('campaigns.show', compact('campaign', 'totalDonations', 'successfulDonations', 'progress_percentage', 'days_remaining'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        $categories = Category::all();
        return view('campaigns.edit', compact('campaign', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'story' => 'nullable|string',
            'target_amount' => 'required|numeric|min:1000',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,completed,cancelled',
            'end_date' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update($validated);

        return redirect()->route('campaigns.show', $campaign)->with('success', 'Kampanye berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'Kampanye berhasil dihapus');
    }

    // Comment on campaign for donor
    public function comment(Request $request, Campaign $campaign)
    {
        $user = auth()->user();
        if (!$user->isDonor()) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);
        // Save comment as a campaign comment (not a donation)
        $campaign->comments()->create([
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);
        return back()->with('success', 'Komentar berhasil dikirim');
    }
}
