<?php

namespace Modules\Branch\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\DTO\BranchSettingDto;
use Modules\Branch\Service\BranchService;
use Modules\Branch\Service\BranchSettingService;
use Modules\Branch\Validation\BranchValidation;

class BranchSettingController extends Controller
{
    use BranchValidation, AuthorizesRequests;

    public function __construct(
        private readonly BranchService $branchService,
        private readonly BranchSettingService $settingService
    ) {
        $this->middleware(['auth:admin']);
        $this->middleware('prevent-back-history');
        $this->middleware('permission:Edit-branch', ['only' => ['edit', 'update']]);
    }

    public function edit($id)
    {
        $branch = $this->branchService->findById($id, ['settings']);
        $this->authorize('update', $branch);

        return view('branch::settings.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = $this->branchService->findById($id, ['settings']);
        $this->authorize('update', $branch);

        $data = $request->except('_token');
        $validation = $this->validateSetting($data);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $this->settingService->save($branch, (new BranchSettingDto($request))->dataFromRequest());

        return redirect()->back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }

    public function deleteSettingImage(Request $request)
    {
        $success = $this->settingService->deleteSettingImage($request);
        return response()->json(['status' => $success], $success ? 200 : 400);
    }
}
