<?php


namespace Modules\Subscription\Service;

use Modules\Branch\Service\BranchService;
use Modules\Common\Helper\UploaderHelper;
use Modules\Subscription\Entities\Subscription;

class SubscriptionService
{
    use UploaderHelper;
    function findAll($data = [], $relations = [])
    {
        $subscription = Subscription::query();
        return getCaseCollection($subscription, $data);
    }

    function findById($id, $relations = [])
    {
        return Subscription::with($relations)->findOrFail($id);
    }

    function active()
    {
        return Subscription::active()->get();
    }

    function findByBranchId($id, $relations = [])
    {
        return Subscription::where('branch_id', $id)->with($relations)->latest()->get();
    }

    function activeByBranchId($id, $relations = [])
    {
        return Subscription::where('branch_id', $id)->active()->with($relations)->latest()->first();
    }

    function inactiveByBranchId($id, $relations = [])
    {
        return Subscription::where('branch_id', $id)->inactive()->with($relations)->latest()->get();
    }

    function findBy($key, $value)
    {
        return Subscription::where($key, $value)->get();
    }

    function save($data)
    {
        $this->deacativateActiveSubscriptions($data['branch_id']);
        $branchSubscription =  Subscription::create($data);
        $this->activateBranch($data['branch_id']);
        return $branchSubscription;
    }
    private function activateBranch($branch_id)
    {
        $branch = (new BranchService())->findById($branch_id, ['admin']);
        if ($branch->is_active == 0) {
            $branch->update(['is_active' => 1]);
        }
        if ($branch->admin->is_active == 0) {
            $branch->admin->update(['is_active' => 1]);
        }
    }

    private function deacativateActiveSubscriptions($branch_id)
    {
        Subscription::active()->where('branch_id', $branch_id)->update(['is_active' => 0]);
    }

    function deactivate($id)
    {
        $subscription = $this->findById($id, ['branch.admin']);
        $subscription->update(['is_active' => 0]);
        if ($subscription->branch->is_active == 1) {
            $subscription->branch->update(['is_active' => 0]);
        }
        $admin = $subscription->branch->admin;
        if ($admin->is_active == 1) {
            $admin->update(['is_active' => 0]);
        }
        return $subscription;
    }

    public function getProductLimitForBranch(int $branchId): ?int
    {
        $subscription = $this->activeByBranchId($branchId);

        if (!$subscription) {
            return null;
        }

        if (is_null($subscription->product_count)) {
            return null;
        }

        return (int) $subscription->product_count;
    }
}
