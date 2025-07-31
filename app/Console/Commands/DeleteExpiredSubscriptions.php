<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes subscriptions that have reached their end date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get current time with seconds precision
        $now = Carbon::now();

        // Format current time to match database format (removing seconds)
        $currentTime = $now->format('Y-m-d H:i:00');

        // Find subscriptions where the end_date exactly matches current time
        $expiredSubscriptions = Subscription::whereRaw("DATE_FORMAT(end_date, '%Y-%m-%d %H:%i:00') = ?", [$currentTime])->get();

        $count = $expiredSubscriptions->count();

        if ($count > 0) {
            // Delete the expired subscriptions
            $expiredSubscriptions->each(function ($subscription) {
                // Update slot status to available
                if ($subscription->slot) {
                    $subscription->slot->update(['status' => false]);
                }

                // Log the deletion
                Log::info("Subscription {$subscription->id} deleted at exact end time: {$subscription->end_date}");

                // Delete the subscription
                $subscription->delete();
            });

            $this->info("$count subscriptions deleted at exact end time: " . $currentTime);
        } else {
            $this->info("No subscriptions found to delete at: " . $currentTime);
        }
    }
}