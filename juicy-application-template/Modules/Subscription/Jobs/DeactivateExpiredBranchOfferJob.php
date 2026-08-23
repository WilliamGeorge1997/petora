<?php

namespace Modules\Subscription\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Branch\Entities\BranchSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Subscription\Entities\Subscription;

class DeactivateExpiredBranchOfferJob implements ShouldQueue
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
        $count = BranchSetting::select('id')->where('app_offer_ends_at', '<', now())
            ->where('app_offer_is_active', 1)->update(['app_offer_is_active' => 0]);
            
        if ($count > 0)
            Log::info("Deactivated {$count} expired branches offers");
    }
}
