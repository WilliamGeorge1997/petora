<?php

namespace Modules\Subscription\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Subscription\Entities\Subscription;

class DeactivateExpiredBranchSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscriptions = Subscription::where('is_active', 1)->with('branch.admin')
            ->whereDate('end_date', '<', now()->toDateString())
            ->get();
        foreach ($subscriptions as $subscription) {
            $subscription->update(['is_active' => 0]);
            $subscription->branch->update(['is_active' => 0]);
            $subscription->branch->admin->update(['is_active' => 0]);
        }
        $count = $subscriptions->count();
        Log::info("Deactivated {$count} expired branch subscriptions and companies");
    }
}
