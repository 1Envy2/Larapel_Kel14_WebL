<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Allocation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AllocationController extends Controller
{
    /**
     * Display a public listing of allocations (for donors/transparency)
     */
    public function publicIndex(Request $request)
    {
        $query = Allocation::with('campaign', 'admin');

        // Filter by campaign
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('allocation_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('allocation_date', '<=', $request->to_date);
        }

        $allocations = $query->latest('allocation_date')->paginate(12);
        $campaigns = Campaign::where('status', 'active')->orWhere('status', 'completed')->get();

        return view('allocations.index', compact('allocations', 'campaigns'));
    }

    /**
     * Show campaign selection page for creating allocation
     */
    public function selectCampaign()
    {
        $campaigns = Campaign::orderBy('created_at', 'desc')
                            ->with('allocations')
                            ->get();
        
        return view('admin.allocations.select-campaign', compact('campaigns'));
    }

    /**
     * Display a listing of allocations (admin)
     */
    public function index(Request $request)
    {
        $query = Allocation::with('campaign', 'admin');

        // Filter by campaign
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('allocation_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('allocation_date', '<=', $request->to_date);
        }

        $allocations = $query->latest('allocation_date')->paginate(20);
        $campaigns = Campaign::all();

        return view('admin.allocations.index', compact('allocations', 'campaigns'));
    }

    /**
     * Show the form for creating a new allocation
     */
    public function create(Campaign $campaign)
    {
        $totalCollected = $campaign->collected_amount;
        $totalAllocated = $campaign->allocations()->sum('amount');
        $remainingFunds = $totalCollected - $totalAllocated;

        return view('admin.allocations.create', compact('campaign', 'totalCollected', 'totalAllocated', 'remainingFunds'));
    }

    /**
     * Store a newly created allocation in storage
     */
    public function store(Request $request, Campaign $campaign)
    {
        // Validate input
        $validated = $request->validate([
            'description' => 'required|string|min:5',
            'amount' => 'required|numeric|min:10000',
            'allocation_date' => 'required|date|before_or_equal:today',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Check if amount doesn't exceed available funds
        $totalCollected = $campaign->collected_amount;
        $totalAllocated = $campaign->allocations()->sum('amount');
        $remainingFunds = $totalCollected - $totalAllocated;

        if ($validated['amount'] > $remainingFunds) {
            return back()->withErrors(['amount' => "Jumlah alokasi tidak boleh melebihi dana tersedia (Rp " . number_format($remainingFunds, 0, ',', '.') . ")"]);
        }

        // Handle file upload
        if ($request->hasFile('proof_image')) {
            $validated['proof_image'] = $request->file('proof_image')->store('allocations', 'public');
        }

        // Add campaign_id and admin_id
        $validated['campaign_id'] = $campaign->id;
        $validated['admin_id'] = auth()->id();

        // Create allocation
        $allocation = Allocation::create($validated);

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'allocation_created',
            Allocation::class,
            $allocation->id,
            "Alokasi dana untuk kampanye '{$campaign->title}' sejumlah Rp " . number_format($allocation->amount, 0, ',', '.'),
            null,
            $validated
        );

        return redirect()->route('admin.allocations.index')
            ->with('success', 'Alokasi dana berhasil dicatat');
    }

    /**
     * Show a specific allocation
     */
    public function show(Allocation $allocation)
    {
        $allocation->load('campaign', 'admin');
        return view('admin.allocations.show', compact('allocation'));
    }

    /**
     * Delete an allocation
     */
    public function destroy(Allocation $allocation)
    {
        $campaign = $allocation->campaign;

        // Delete proof image if exists
        if ($allocation->proof_image && Storage::disk('public')->exists($allocation->proof_image)) {
            Storage::disk('public')->delete($allocation->proof_image);
        }

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'allocation_deleted',
            Allocation::class,
            $allocation->id,
            "Alokasi dana untuk kampanye '{$campaign->title}' sejumlah Rp " . number_format($allocation->amount, 0, ',', '.') . " dihapus",
            ['description' => $allocation->description, 'amount' => $allocation->amount],
            null
        );

        $allocation->delete();

        return redirect()->route('admin.allocations.index')
            ->with('success', 'Alokasi dana berhasil dihapus');
    }
}
