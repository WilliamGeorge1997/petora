<?php

namespace Modules\Clinic\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Admin\Enums\AdminRole;
use Modules\Clinic\DTOs\ClinicDto;
use Modules\Clinic\Http\Requests\ClinicRequest;
use Modules\Clinic\Models\Clinic;
use Modules\Clinic\Services\ClinicService;
use Illuminate\Support\Facades\Gate;

class ClinicController implements HasMiddleware
{
    public function __construct(private ClinicService $clinicService) {}

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('role:' . AdminRole::SuperAdmin->value, except: ['edit', 'update']),
            new Middleware('permission:Index-clinic|Create-clinic|Edit-clinic|Delete-clinic', only: ['index', 'store']),
            new Middleware('permission:Create-clinic', only: ['create', 'store']),
            new Middleware('permission:Edit-clinic', only: ['edit', 'update', 'activate']),
            new Middleware('permission:Delete-clinic', only: ['destroy']),
        ];
    }

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
        return view('clinic::clinics.create');
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
        return view('clinic::clinics.edit', compact('clinic'));
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
