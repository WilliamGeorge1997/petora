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
            $clinicServices = ClinicService::with(['service', 'schedules'])
                ->where('clinic_id', $clinic->id)
                ->latest('id')
                ->get();

            return response()->json(['data' => $clinicServices]);
        }

        $services = ClinicService::with(['service', 'schedules'])
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
                'duration'  => $service->duration !== null ? (int) $service->duration : null,
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
            'price'            => ['sometimes', 'required', 'numeric', 'min:0'],
            'duration'         => ['nullable', 'integer', 'min:1'],
            'is_active'        => ['nullable', 'boolean'],
            'schedules'        => ['nullable', 'array'],
            'schedules.*.day'  => ['required', 'string'],
            'schedules.*.from' => ['required'],
            'schedules.*.to'   => ['required'],
        ]);

        $attributes = collect($data)->only(['price', 'duration', 'is_active'])->filter(fn($v) => !is_null($v))->all();
        if (!empty($attributes)) {
            $clinicService->update($attributes);
        }

        if ($request->has('schedules')) {
            $clinicService->schedules()->delete();
            if (!empty($data['schedules'])) {
                $clinicService->schedules()->createMany($data['schedules']);
            }
        }

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
