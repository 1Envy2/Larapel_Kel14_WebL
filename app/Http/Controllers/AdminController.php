<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_donations' => Donation::where('status', 'successful')->count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_raised' => Donation::where('status', 'successful')->sum('amount'),
            'total_donors' => User::where('role_id', 2)->count(),
            'pending_donations' => Donation::where('status', 'pending')->count(),
        ];

        $recentDonations = Donation::with('donor', 'campaign')
            ->where('status', 'successful')
            ->latest()
            ->take(10)
            ->get();

        // Get top 10 donors by total donation amount (all statuses)
        $topDonors = Donation::selectRaw('donor_id, SUM(amount) as total_donated')
            ->with('donor:id,name')
            ->groupBy('donor_id')
            ->orderByRaw('SUM(amount) DESC')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentDonations', 'topDonors'));
    }

    /**
     * View all donations with filters
     */
    public function donations(Request $request)
    {
        $query = Donation::with('donor', 'campaign', 'paymentMethod');

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method_id')) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        $donations = $query->latest()->paginate(20);
        $campaigns = Campaign::all();

        return view('admin.donations', compact('donations', 'campaigns'));
    }

    /**
     * Show donation detail with proof image
     */
    public function donationDetail(Donation $donation)
    {
        $donation->load('donor', 'campaign', 'paymentMethod');
        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Update donation status
     */
    public function updateDonationStatus(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,successful,failed',
            'reason' => 'nullable|string|max:500',
        ]);

        $oldStatus = $donation->status;
        $newStatus = $validated['status'];

        // Update donation status
        $donation->update(['status' => $newStatus]);

        // If successful, update campaign collected amount
        if ($newStatus === 'successful' && $oldStatus !== 'successful') {
            $campaign = $donation->campaign;
            $campaign->increment('collected_amount', $donation->amount);

            // Auto-update campaign status if needed
            $campaign->updateStatusIfCompleted();

            // Create notification for donor (including anonymous)
            if ($donation->donor_id) {
                Notification::create([
                    'user_id' => $donation->donor_id,
                    'title' => 'Donasi Dikonfirmasi',
                    'message' => ($donation->anonymous ? 'Donasi Anda yang anonim ' : 'Donasi Anda ') . 'sebesar Rp ' . number_format($donation->amount, 0, ',', '.') . ' telah dikonfirmasi untuk kampanye "' . $campaign->title . '"',
                    'type' => 'donation_success',
                    'data' => [
                        'donation_id' => $donation->id,
                        'campaign_id' => $campaign->id,
                        'campaign_title' => $campaign->title,
                        'amount' => $donation->amount,
                    ],
                ]);
            }
        } elseif ($newStatus === 'failed' && $oldStatus !== 'failed') {
            // Create notification for failed donation (including anonymous)
            if ($donation->donor_id) {
                $campaign = $donation->campaign;
                Notification::create([
                    'user_id' => $donation->donor_id,
                    'title' => 'Donasi Ditolak',
                    'message' => ($donation->anonymous ? 'Donasi Anda yang anonim ' : 'Donasi Anda ') . 'sebesar Rp ' . number_format($donation->amount, 0, ',', '.') . ' tidak dapat diproses. ' . ($validated['reason'] ? 'Alasan: ' . $validated['reason'] : ''),
                    'type' => 'donation_failed',
                    'data' => [
                        'donation_id' => $donation->id,
                        'campaign_id' => $campaign->id,
                        'campaign_title' => $campaign->title,
                        'amount' => $donation->amount,
                        'reason' => $validated['reason'] ?? null,
                    ],
                ]);
            }
        } elseif ($oldStatus === 'successful' && $newStatus !== 'successful') {
            // Revert if changed from successful to something else
            $donation->campaign->decrement('collected_amount', $donation->amount);
        }

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'donation_status_updated',
            Donation::class,
            $donation->id,
            "Status donasi dari {$oldStatus} menjadi {$newStatus} (Rp " . number_format($donation->amount, 0, ',', '.') . ")" .
            ($validated['reason'] ? " - Alasan: {$validated['reason']}" : ""),
            ['status' => $oldStatus],
            ['status' => $newStatus, 'reason' => $validated['reason'] ?? null]
        );

        return redirect()->route('admin.donations.show', $donation)
            ->with('success', 'Status donasi berhasil diperbarui');
    }

    /**
     * Show all campaigns for admin
     */
    public function campaigns(Request $request)
    {
        $query = Campaign::with('organizer', 'category')
            ->withCount(['donations' => function ($q) {
                $q->where('status', 'successful')->where('amount', '>', 0);
            }]);

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%$search%")
                  ->orWhere('description', 'ILIKE', "%$search%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Sort
        if ($request->filled('sort') && $request->sort === 'terlama') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $campaigns = $query->paginate(9);
        
        return view('admin.campaigns.index', compact('campaigns'));
    }

    /**
     * Show create campaign form
     */
    public function createCampaign()
    {
        $categories = Category::all();
        return view('admin.campaigns.create', compact('categories'));
    }

    /**
     * Store campaign
     */
    public function storeCampaign(Request $request)
    {
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

        $validated['organizer_id'] = auth()->id();
        $validated['status'] = 'active';
        $validated['collected_amount'] = 0;

        Campaign::create($validated);

        return redirect()->route('admin.campaigns')->with('success', 'Kampanye berhasil dibuat');
    }

    /**
     * Show edit campaign form
     */
    public function editCampaign(Campaign $campaign)
    {
        $categories = Category::all();
        return view('admin.campaigns.edit', compact('campaign', 'categories'));
    }

    /**
     * Update campaign
     */
    public function updateCampaign(Request $request, Campaign $campaign)
    {
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

        return redirect()->route('admin.campaigns')->with('success', 'Kampanye berhasil diperbarui');
    }

    /**
     * Delete campaign
     */
    public function deleteCampaign(Campaign $campaign)
    {
        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns')->with('success', 'Kampanye berhasil dihapus');
    }

    /**
     * Show campaign detail for admin
     */
    public function showCampaign(Campaign $campaign)
    {
        $campaign->load('organizer', 'category', 'comments.user');
        
        // Use database queries instead of loading all donations in memory
        $totalDonations = Donation::where('campaign_id', $campaign->id)
            ->where('status', 'successful')
            ->where('amount', '>', 0)
            ->count();
        
        $successfulDonations = Donation::where('campaign_id', $campaign->id)
            ->where('status', 'successful')
            ->where('amount', '>', 0)
            ->with('donor')
            ->latest()
            ->get();
        
        $progress_percentage = $campaign->target_amount > 0
            ? min(100, round(($campaign->collected_amount / $campaign->target_amount) * 100))
            : 0;
        
        // Calculate days remaining using the same logic as the donor view
        if ($campaign->end_date) {
            $daysLeft = now()->diffInDays($campaign->end_date, false);
            $days_remaining = $daysLeft < 0 ? null : ($daysLeft === 0 && now()->lessThan($campaign->end_date) ? 1 : (int)$daysLeft);
        } else {
            $days_remaining = null;
        }

        return view('admin.campaigns.show', compact('campaign', 'totalDonations', 'successfulDonations', 'progress_percentage', 'days_remaining'));
    }
}
