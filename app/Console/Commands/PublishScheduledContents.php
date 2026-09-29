<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Content;

class PublishScheduledContents extends Command
{
    protected $signature = 'contents:publish-scheduled';

    protected $description = 'Publish scheduled contents whose publication date has passed';

    public function handle()
    {
        $now = now();

        $updatedCount = Content::where('status', Content::STATUS_SCHEDULED)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->update([
                'status'       => Content::STATUS_ACTIVE,
                'published_at' => $now,
            ]);

        if ($updatedCount > 0) {
            $this->info("Successfully published {$updatedCount} scheduled content(s).");
        } else {
            $this->info("No scheduled contents to publish at this time.");
        }
    }
}
