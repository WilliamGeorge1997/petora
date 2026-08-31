<?php

namespace Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Admin\Enums\AdminRole;
use Modules\Clinic\DTOs\ClinicDto;
use Modules\Clinic\Http\Requests\ClinicRequest;
use Modules\Clinic\Models\Clinic;
use Modules\Clinic\Services\ClinicService;
use Modules\Clinic\ViewModels\ClinicViewModel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware('auth:admin')]
#[Middleware('role:' . AdminRole::SuperAdmin->value, except: ['edit', 'update'])]
#[Middleware('permission:Index-clinic|Create-clinic|Edit-clinic|Delete-clinic', only: ['index', 'store'])]
#[Middleware('permission:Create-clinic', only: ['create', 'store'])]
#[Middleware('permission:Edit-clinic', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-clinic', only: ['destroy'])]
class ClinicController extends Controller
{
    public function __construct(private ClinicService $clinicService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $clinics = $this->clinicService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('clinic::message.fetched'), $clinics->items());
        }
        return view('clinic::clinics.index', compact('clinics'));
    }

    public function create()
    {
        $viewModel = new ClinicViewModel();
        return view('clinic::clinics.create', compact('viewModel'));
    }

    public function store(ClinicRequest $request)
    {
        $dto = ClinicDto::fromRequest($request);
        $this->clinicService->save($dto);
        return to_route('admin.clinic.index')->with('success', __('clinic::message.created'));
    }

    public function edit(Clinic $clinic)
    {
        Gate::authorize('update', $clinic);
        $viewModel = new ClinicViewModel();
        return view('clinic::clinics.edit', compact('clinic', 'viewModel'));
    }

    public function update(ClinicRequest $request, Clinic $clinic)
    {
        Gate::authorize('update', $clinic);
        $dto = ClinicDto::fromRequest($request);
        $this->clinicService->update($clinic, $dto);
        return to_route('admin.clinic.index')->with('success', __('clinic::message.updated'));
    }

    public function destroy(Clinic $clinic)
    {
        $this->clinicService->delete($clinic);
        return success(true, __('clinic::message.deleted'));
    }

    public function activate(Clinic $clinic)
    {
        $clinic = $this->clinicService->activate($clinic);
        return success(
            true,
            $clinic->is_active ?  __('clinic::message.activated') :  __('clinic::message.deactivated'),
            $clinic
        );
    }
}
