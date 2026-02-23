<?php

namespace App\Console\Commands;

use App\Models\ProjectProposal;
use Illuminate\Console\Command;

class CleanupAbandonedProposals extends Command
{
    protected $signature = 'proposals:cleanup-abandoned
                            {--days=7 : Delete drafts older than this many days}
                            {--dry-run : Show how many would be deleted without deleting}';

    protected $description = 'Delete abandoned ProjectProposal drafts older than N days';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $dryRun = (bool) $this->option('dry-run');

        $cutoff = now()->subDays($days);

        $query = ProjectProposal::query()
            ->where('status_stage1', 'pending')
            ->where('status_stage2', 'pending')
            ->where('status_stage3', 'pending')
            // created_at cutoff
            ->where('created_at', '<', $cutoff);

        $count = (clone $query)->count();

        if ($dryRun) {
            $this->info("Dry run: would delete {$count} abandoned drafts older than {$days} days.");
            return self::SUCCESS;
        }

        // If you expect tons of rows, delete in chunks to be safe
        $deleted = 0;

        $query->select('id')->chunkById(500, function ($rows) use (&$deleted) {
            $ids = $rows->pluck('id')->all();
            $deleted += ProjectProposal::whereIn('id', $ids)->delete();
        });

        $this->info("Deleted {$deleted} abandoned drafts older than {$days} days.");
        return self::SUCCESS;
    }
}
