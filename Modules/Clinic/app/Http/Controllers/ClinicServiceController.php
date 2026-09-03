<?php

namespace Modules\Clinic\Http\Controllers;

use App\Exports\ServiceExport;
use App\Http\Controllers\Controller;
use App\Imports\ClinicServiceImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Enums\AdminRole;
use Modules\Clinic\Models\Clinic;
use Modules\Service\Models\ClinicService;
use Modules\Service\Models\Service;

#[Middleware('auth:admin')]
#[Middleware('role:' . AdminRole::SuperAdmin->value)]
#[Middleware('permission:Edit-clinic')]
class ClinicServiceController extends Controller
{
    public function index(Request $request, Clinic $clinic)
    {
        if ($request->ajax()) {
            $clinicServices = ClinicService::with('service')
                ->where('clinic_id', $clinic->id)
                ->latest('id')
                ->get();

            return response()->json(['data' => $clinicServices]);
        }

        $services = ClinicService::with('service')
            ->where('clinic_id', $clinic->id)
            ->paginate(50);

        return view('clinic::services.index', compact('clinic', 'services'));
    }

    public function export(Clinic $clinic)
    {
        return Excel::download(new ServiceExport(), 'services_template.xlsx');
    }

    public function import(Request $request, Clinic $clinic): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new ClinicServiceImport($clinic), $request->file('file'));

        return back()->with('success', __('common::message.imported_successfully') ?? 'Services imported successfully');
    }

    public function importAll(Clinic $clinic): RedirectResponse
    {
        $activeServices = Service::active()->get();
        $syncData = [];

        foreach ($activeServices as $service) {
            $syncData[$service->id] = [
                'price'     => (float) $service->price,
                'duration'  => (int) $service->duration,
                'is_active' => true,
            ];
        }

        if (!empty($syncData)) {
            $clinic->services()->syncWithoutDetaching($syncData);
        }

        return back()->with('success', __('clinic::general.imported_all_services') ?? 'All services imported successfully');
    }

    public function update(Request $request, Clinic $clinic, ClinicService $clinicService): JsonResponse
    {
        $data = $request->validate([
            'price'     => ['required', 'numeric', 'min:0'],
            'duration'  => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $clinicService->update($data);

        return response()->json([
            'success' => true,
            'message' => __('clinic::message.updated_successfully') ?? 'Service updated successfully',
        ]);
    }

    public function activate(Clinic $clinic, ClinicService $clinicService): JsonResponse
    {
        $clinicService->update(['is_active' => !$clinicService->is_active]);

        return response()->json([
            'success' => true,
            'message' => __('clinic::message.status_updated') ?? 'Status updated successfully',
        ]);
    }

    public function destroy(Clinic $clinic, ClinicService $clinicService): JsonResponse
    {
        $clinicService->delete();

        return response()->json([
            'success' => true,
            'message' => __('clinic::message.deleted_successfully') ?? 'Service deleted successfully',
        ]);
    }
}
