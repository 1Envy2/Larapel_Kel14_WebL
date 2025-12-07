<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Notification;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $donations = $user->donations()->with('campaign', 'paymentMethod')->latest()->paginate(10);

        $stats = [
            'total_donated' => $user->donations()->sum('amount'),
            'total_campaigns' => $user->donations()->distinct('campaign_id')->count(),
            'campaigns_supported' => $user->donations()->where('status', 'successful')->distinct('campaign_id')->count(),
            'impact_score' => min(5, (int) ceil($user->donations()->where('status', 'successful')->count() / 5)),
        ];

        return view('donations.index', compact('donations', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Campaign $campaign)
    {
        if (!auth()->user()->isDonor()) {
            abort(403, 'Unauthorized');
        }
        $paymentMethods = PaymentMethod::all();
        return view('donations.create', compact('campaign', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isDonor()) {
            abort(403, 'Unauthorized');
        }
        
        $campaign = Campaign::findOrFail($request->input('campaign_id'));

        $paymentMethod = PaymentMethod::find($request->input('payment_method_id'));

        // Validate proof_image as required for bank transfer
        $proofImageRule = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        if ($paymentMethod && $paymentMethod->name === 'Bank Transfer') {
            $proofImageRule = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        // Conditional validation based on anonymous flag
        $isAnonymous = $request->boolean('anonymous');
        
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:10000',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'donor_name' => $isAnonymous ? 'nullable|string|max:255' : 'required|string|max:255',
            'donor_email' => $isAnonymous ? 'nullable|email' : 'required|email',
            'message' => 'nullable|string|max:500',
            'anonymous' => 'nullable|boolean',
            'proof_image' => $proofImageRule,
        ]);

        // Handle file upload if needed
        if ($request->hasFile('proof_image')) {
            $validated['proof_image'] = $request->file('proof_image')->store('donations', 'public');
        }

        // Handle anonymous donations
        if ($isAnonymous) {
            // For anonymous donations:
            // - Keep donor_id so admin can trace who donated
            // - Set donor_name to 'Anonim' for public visibility
            // - Clear donor_email for privacy
            $validated['donor_id'] = auth()->id();
            $validated['donor_name'] = 'Anonim';
            $validated['donor_email'] = null;
            $validated['anonymous'] = true;
        } else {
            $validated['donor_id'] = auth()->id();
            $validated['donor_email'] = $validated['donor_email'] ?? auth()->user()->email;
            $validated['donor_name'] = $validated['donor_name'] ?? auth()->user()->name;
            $validated['anonymous'] = false;
        }
        
        $validated['campaign_id'] = $campaign->id;

        // Bank transfer needs proof and verification
        $validated['status'] = 'pending';

        $donation = Donation::create($validated);

        return redirect()->route('donations.show', $donation)->with('success', 'Donasi Anda sedang diproses');
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        // Allow access if authenticated user is the donor, or if admin, or if it's an anonymous donation
        if ($donation->donor_id && auth()->id() !== $donation->donor_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        $donation->load('campaign', 'paymentMethod', 'donor');
        return view('donations.show', compact('donation'));
    }

    /**
     * Get donor's donations history
     */
    public function history()
    {
        return $this->index();
    }

    /**
     * Admin: Update donation status
     */
    public function updateStatus(Request $request, Donation $donation)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:pending,successful,failed',
        ]);

        $oldStatus = $donation->status;
        $donation->update($validated);

        // If status changed to successful and was pending
        if ($oldStatus === 'pending' && $validated['status'] === 'successful') {
            $donation->campaign->increment('collected_amount', $donation->amount);
            
            // Auto-update campaign status to finished if completed
            $donation->campaign->updateStatusIfCompleted();
            
            // Create notification
            Notification::create([
                'user_id' => $donation->donor_id,
                'title' => 'Donasi Dikonfirmasi',
                'message' => 'Donasi Anda sebesar Rp ' . number_format($donation->amount) . ' telah dikonfirmasi',
                'type' => 'donation_success',
            ]);
        }

        return redirect()->back()->with('success', 'Status donasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        if ($donation->proof_image) {
            Storage::disk('public')->delete($donation->proof_image);
        }

        $donation->delete();
        return redirect()->back()->with('success', 'Donasi berhasil dihapus');
    }
}
