<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Branch\Entities\Branch;
use Modules\Subscription\DTO\SubscriptionDto;
use Modules\Subscription\Service\SubscriptionService;
use Modules\Subscription\ViewModel\SubscriptionViewModel;
use Modules\Subscription\Http\Requests\SubscriptionRequest;


class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->middleware('permission:Index-subscription|Create-subscription|Edit-subscription|Delete-subscription', ['only' => ['index', 'store',]]);
        $this->middleware('permission:Create-subscription', ['only' => ['create', 'store', 'show']]);
        $this->middleware('permission:Edit-subscription', ['only' => ['edit', 'update', 'activate', 'deactivate']]);
        $this->middleware('permission:Delete-subscription', ['only' => ['destroy']]);
    }

    public function show(Branch $branch)
    {
        $relations = ['package'];
        $viewModel = new SubscriptionViewModel();
        $subscriptions = $this->subscriptionService->findByBranchId($branch->id, $relations);
        $activeSubscription = $subscriptions->where('is_active', 1)->first();
        $inactiveSubscriptions = $subscriptions->where('is_active', 0)->values();
        return view('subscription::subscriptions.show', compact('branch', 'viewModel', 'activeSubscription', 'inactiveSubscriptions'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SubscriptionRequest $request, Branch $branch)
    {
        $data = (new SubscriptionDto($request, $branch))->dataFromRequest();
        $this->subscriptionService->save($data);
        return redirect()->route('admin.subscriptions.show', $branch->id)->with('created', 'created');
    }

    public function deactivate($branch_id, $id)
    {
        $this->subscriptionService->deactivate($id);
        return redirect()->route('admin.subscriptions.show', $branch_id)->with('deactivated', 'deactivated');
    }
}
