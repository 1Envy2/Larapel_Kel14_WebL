<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignUpdate;
use Illuminate\Http\Request;

class CampaignUpdateController extends Controller
{
    /**
     * Store a new campaign update
     */
    public function store(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $campaign->updates()->create($validated);

        return redirect()->route('admin.campaigns.edit', $campaign)->with('success', 'Update berhasil ditambahkan');
    }

    /**
     * Delete a campaign update
     */
    public function destroy(Campaign $campaign, CampaignUpdate $update)
    {
        // Make sure the update belongs to this campaign
        if ($update->campaign_id !== $campaign->id) {
            abort(404);
        }

        $update->delete();

        return redirect()->route('admin.campaigns.edit', $campaign)->with('success', 'Update berhasil dihapus');
    }
}
