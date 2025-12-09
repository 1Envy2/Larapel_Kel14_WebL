<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use Illuminate\Console\Command;

class SyncCompletedCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:sync-completed-status';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Sync campaign status to finished if collected_amount >= target_amount. Fallback for any missed auto-updates.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Starting campaign status sync...');

        $updated = 0;
        $skipped = 0;

        Campaign::all()->each(function ($campaign) use (&$updated, &$skipped) {
            if ($campaign->isCompleted() && $campaign->status !== 'completed') {
                $campaign->update(['status' => 'completed']);
                $this->line("✅ Updated: {$campaign->title}");
                $updated++;
            } elseif ($campaign->isCompleted()) {
                $this->line("⏭️  Already completed: {$campaign->title}");
                $skipped++;
            }
        });

        $this->info("✨ Sync complete!");
        $this->line("📊 Updated: {$updated} campaigns");
        $this->line("⏭️  Already finished: {$skipped} campaigns");

        return Command::SUCCESS;
    }
}
