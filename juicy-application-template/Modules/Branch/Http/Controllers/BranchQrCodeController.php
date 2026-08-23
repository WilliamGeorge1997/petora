<?php

namespace Modules\Branch\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\DTO\BranchQrSettingDto;
use Modules\Branch\Service\BranchQrSettingService;
use Modules\Branch\Service\BranchService;

class BranchQrCodeController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly BranchService $branchService,
        private readonly BranchQrSettingService $qrSettingService
    ) {
        $this->middleware(['auth:admin']);
        $this->middleware('prevent-back-history');
        $this->middleware('permission:Edit-branch', ['only' => ['edit', 'update']]);
    }

    public function edit($id)
    {
        $branch = $this->branchService->findById($id, ['settings', 'qrSetting']);
        $this->authorize('update', $branch);

        return view('branch::qr.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = $this->branchService->findById($id, ['qrSetting']);
        $this->authorize('update', $branch);
        $this->qrSettingService->save($branch, new BranchQrSettingDto($request));

        return redirect()->back()->with('success', 'تم حفظ إعدادات QR بنجاح');
    }
}
