<?php

namespace Modules\Branch\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Http\Requests\BranchOrderDiscountRequest;
use Modules\Branch\Service\BranchService;
use Modules\Branch\Service\BranchOrderDiscountService;
use Modules\Branch\Validation\BranchValidation;

class BranchOrderDiscountController extends Controller
{
    use BranchValidation, AuthorizesRequests;

    public function __construct(
        private readonly BranchService $branchService,
        private readonly BranchOrderDiscountService $discountService
    ) {
        $this->middleware(['auth:admin']);
        $this->middleware('prevent-back-history');
        $this->middleware('permission:Edit-branch', ['only' => ['edit', 'update']]);
    }

    public function edit(Request $request, $id)
    {
        $branch = $this->branchService->findById($id, ['settings', 'orderDiscounts']);
        $this->authorize('update', $branch);

        return view('branch::discounts.edit', compact('branch'));
    }

    public function update(BranchOrderDiscountRequest $request, $id)
    {
        $branch = $this->branchService->findById($id, ['settings']);
        $this->authorize('update', $branch);

        $data = $request->validated();
        $data['is_order_discount_enabled'] = $request->has('is_order_discount_enabled') ? 1 : 0;
        
        $this->discountService->update($branch, $data);

        return redirect()->back()->with('success', 'تم حفظ إعدادات الخصومات بنجاح');
    }
}
