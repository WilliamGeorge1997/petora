<?php

namespace Modules\Branch\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\DTO\BranchManagerDto;
use Modules\Branch\DTO\BranchDto;
use Modules\Branch\DTO\BranchSettingDto;
use Modules\Branch\Entities\ShortUrl;
use Modules\Branch\Service\BranchService;
use Modules\Branch\Validation\BranchValidation;
use Modules\Branch\ViewModel\BranchViewModel;
use Modules\Common\Helper\EnctyptionService;
use Modules\Common\Helper\UploaderHelper;

class BranchController extends Controller
{
    use UploaderHelper, BranchValidation, AuthorizesRequests;

    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->middleware(['auth:admin']);
        $this->middleware('prevent-back-history')->except('generateQrCode');
        $this->branchService = $branchService;
        $this->middleware('permission:Index-branch|Create-branch|Edit-branch|Delete-branch', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-branch', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit-branch', ['only' => ['edit', 'update', 'activate', 'offer', 'offerUpdate', 'destroyOffer']]);
        $this->middleware('permission:Delete-branch', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['paginated'] = 50;
        $relations = ['settings', 'activeSubscription', 'shortUrl'];
        $branches = $this->branchService->findAll($data, $relations);
        $themeCounts = $this->branchService->getThemeCounts();
        if ($request->ajax()) {
            return response()->json(['data' => $branches->items()]);
        }
        return view('branch::branches.index', ['branches' => $branches, 'themeCounts' => $themeCounts]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $viewModel = new BranchViewModel();
        return view('branch::branches.create', ['viewModel' => $viewModel]);
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $validation = $this->validateStore($data);
        if ($validation->fails())
            return redirect()->back()->withInput()->withErrors($validation);
        $data = (new BranchDto($request))->dataFromRequest();
        $managerData = (new BranchManagerDto($request))->dataFromRequest();
        // $settingData = (new BranchSettingDto($request))->dataFromRequest();
        $branch = $this->branchService->save($data, $managerData, null);
        return redirect('/admin/branches')->with('created', 'created');
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    // public function show($id)
    // {
    //     $branch = Branch::where('id',$id)->with('admins:id,name,email,created_at,image','orderMethods:id,title_ar','coupon','printers','employees')->first();
    //     $table_sections = TableSection::whereHas('tables',function($query) use ($id){
    //         $query->where('branch_id',$id);
    //     })->withCount(['tables' => function ($query) use ($id){
    //         $query->where('branch_id',$id);
    //     }])->get();

    //     return view('branch::branches.details',compact('branch','table_sections'));
    // }

    public function edit($id)
    {
        $relations = ['settings'];
        $branch = $this->branchService->findById($id, $relations);
        $this->authorize('update', $branch);
        $branch_delivery_charges = $this->branchService->deliveryCharges($id);
        $viewModel = new BranchViewModel();
        $branch_order_methods = $branch->orderMethods()->pluck('id')->toArray();
        $branch_payment_methods = $branch->paymentMethods()->pluck('id')->toArray();
        $branch_working_hours = $branch->workingHours()->get();
        return view('branch::branches.edit', compact('branch', 'branch_delivery_charges', 'viewModel', 'branch_order_methods', 'branch_payment_methods', 'branch_working_hours'));
    }

    public function update(Request $request, $id)
    {
        $branch = $this->branchService->findById($id);
        $this->authorize('update', $branch);
        $data = $request->except('_token');
        $validation = $this->validateUpdate($data);
        if ($validation->fails())
            return redirect()->back()->withInput()->withErrors($validation);
        $data = (new BranchDto($request, true))->dataFromRequest();
        // $settingData = (new BranchSettingDto($request))->dataFromRequest();
        $this->branchService->update($branch, $data, null);
        return redirect()->back()->with('updated', 'updated');
    }

    public function destroy($id, Request $request)
    {
        $this->branchService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->branchService->activate($id);
        return redirect('admin/branches')->with('updated', 'updated');
    }


    // public function generateQrCode($id)
    // {
    //     $branch = $this->branchService->findById($id, ['settings', 'qrSetting']);
    //     $encryptedQrCode = EnctyptionService::generateEncryptedToken($branch->qr_code);
    //     $theme_no = $branch->settings?->theme;
    //     // if ($theme_no == 1) {
    //     //     $qrUrl = url('/public/web?token=' . $encryptedQrCode);
    //     // } else {
    //     //     $qrUrl = url("/public/web{$theme_no}?token=" . $encryptedQrCode);
    //     // }
    //     if ($theme_no == 1) {
    //         $qrUrl = config('app.frontend_url') . '/public/web?token=' . $encryptedQrCode;
    //     } else {
    //         $qrUrl = config('app.frontend_url') . "/public/web{$theme_no}?token=" . $encryptedQrCode;
    //     }
    //     $this->branchService->generateShortUrl($qrUrl, $branch);
    //     $qr = $branch->qrSetting;
    //     $html = $this->branchService->generateQrCodeHtml(
    //         $qrUrl,
    //         $qr?->is_qr_image_enabled ?? false,
    //         $qr?->getRawOriginal('qr_image')
    //     );

    //     return response($html)
    //         ->header('Content-Type', 'text/html')
    //         ->header('Content-Disposition', 'attachment; filename="branch-' . $branch->title . '-qrcode.html"');
    // }


    public function updateTheme(Request $request, $id)
    {
        $this->branchService->updateTheme($id, $request['theme']);
        return redirect('admin/branches')->with('updated', 'updated');
    }

    // public function deleteSettingImage(Request $request)
    // {
    //     $success = $this->branchService->deleteSettingImage($request);
    //     return response()->json(['status' => $success], $success ? 200 : 400);
    // }

    //Offer
    public function indexOffer(Request $request)
    {
        $viewModel = new BranchViewModel();
        $branch_id = getBranchId($request);
        $offerData = $this->branchService->getOfferByBranch($branch_id);
        return view('branch::branches.offer', compact('viewModel', 'offerData', 'branch_id'));
    }

    public function updateOffer(Request $request)
    {
        $data = $request->except('_token');
        $validation = $this->validateUpdateOffer($data);
        if ($validation->fails())
            return redirect()->back()->withInput()->withErrors($validation);
        $updated = $this->branchService->upsertOffer($request->branch_id, $data);
        return $updated
            ? back()->with('success', 'تم الحفظ بنجاح')
            : back()->withInput()->with('error', 'حدث خطأ، يرجى المحاولة مرة أخرى');
    }

    public function destroyOffer(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);
        $this->branchService->deleteOffer($request->branch_id);
        return back()->with('success', 'تم الحذف بنجاح');
    }
}
