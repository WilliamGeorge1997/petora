<?php

namespace Modules\Clinic\Http\Controllers;

use App\Exports\ServiceExport;
use App\Http\Controllers\Controller;
use App\Imports\ClinicServiceImport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Clinic\DTOs\ClinicServiceDto;
use Modules\Clinic\Http\Requests\ClinicServiceImportRequest;
use Modules\Clinic\Http\Requests\ClinicServiceUpdateRequest;
use Modules\Clinic\Models\Clinic;
use Modules\Clinic\Services\ClinicServiceService;
use Modules\Service\Models\ClinicService;

#[Middleware('auth:admin')]
#[Middleware('permission:Edit-clinic')]
class ClinicServiceController extends Controller
{
    public function __construct(private ClinicServiceService $clinicServiceService) {}

    public function index(Request $request, Clinic $clinic): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50, 'clinic_id' => $clinic->id])->all();
        $relations = ['service', 'schedules'];
        $services = $this->clinicServiceService->findAll($data, $relations);

        if ($request->ajax()) {
            return success(true, __('clinic::message.service.fetched'), $services->items());
        }

        return view('clinic::services.index', compact('clinic', 'services'));
    }

    public function export(Clinic $clinic)
    {
        return Excel::download(new ServiceExport, 'services_template.xlsx');
    }

    public function edit(Clinic $clinic, int $clinic_service): View
    {
        $clinic_service->load('service');

        return view('clinic::services.edit', compact('clinic', 'clinic_service'));
    }

    public function import(ClinicServiceImportRequest $request, Clinic $clinic): RedirectResponse
    {
        Excel::import(new ClinicServiceImport($clinic), $request->file('file'));

        return back()->with('success', __('clinic::message.service.imported'));
    }

    public function importAll(Clinic $clinic): RedirectResponse
    {
        $this->clinicServiceService->importAll($clinic);

        return back()->with('success', __('clinic::message.service.imported'));
    }

    public function update(ClinicServiceUpdateRequest $request, Clinic $clinic, ClinicService $clinic_service): RedirectResponse
    {
        $dto = ClinicServiceDto::fromRequest($request);
        $this->clinicServiceService->update($clinic_service, $dto);

        return redirect()->route('admin.clinic.services.index', $clinic->id)
            ->with('success', __('clinic::message.service.updated'));
    }

    public function activate(Clinic $clinic, ClinicService $clinic_service): JsonResponse
    {
        $clinic_service = $this->clinicServiceService->activate($clinic_service);

        return success(
            true,
            $clinic_service->is_active ? __('clinic::message.service.activated') : __('clinic::message.service.deactivated'),
            $clinic_service
        );
    }

    public function destroy(Clinic $clinic, ClinicService $clinic_service): JsonResponse
    {
        $this->clinicServiceService->delete($clinic_service);

        return success(true, __('clinic::message.service.deleted'));
    }
}
