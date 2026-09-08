<?php

namespace Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Clinic\DTOs\ClinicServiceScheduleDto;
use Modules\Clinic\Http\Requests\ClinicServiceScheduleRequest;
use Modules\Clinic\Models\Clinic;
use Modules\Clinic\Services\ClinicServiceScheduleService;
use Modules\Service\Models\ClinicService;
use Modules\Service\Models\ClinicServiceSchedule;

#[Middleware('auth:admin')]
#[Middleware('permission:Edit-clinic')]
class ClinicServiceScheduleController extends Controller
{
    public function __construct(private ClinicServiceScheduleService $scheduleService) {}

    public function index(Request $request, Clinic $clinic, ClinicService $clinic_service)
    {
        $data = $request->merge(['clinic_service_id' => $clinic_service->id])->all();
        $relations = ['times'];
        
        $schedules = $this->scheduleService->findAll($data, $relations);

        if ($request->ajax()) {
            return success(true, __('clinic::message.fetched'), $schedules);
        }

        return view('clinic::schedules.index', [
            'clinic' => $clinic,
            'clinicService' => $clinic_service,
            'schedules' => $schedules,
        ]);
    }

    public function create(Request $request, Clinic $clinic, ClinicService $clinic_service)
    {
        $schedules = $this->scheduleService->findBy('clinic_service_id', $clinic_service->id);

        return view('clinic::schedules.create', [
            'clinic' => $clinic,
            'clinicService' => $clinic_service,
            'schedules' => $schedules,
        ]);
    }

    public function store(ClinicServiceScheduleRequest $request, Clinic $clinic, ClinicService $clinic_service): RedirectResponse
    {
        $dto = ClinicServiceScheduleDto::fromRequest($request);
        $this->scheduleService->save($dto);

        return to_route('admin.clinic.services.schedules.index', [$clinic->id, $clinic_service->id])
            ->with('success', __('clinic::message.created'));
    }

    public function edit(Clinic $clinic, ClinicService $clinic_service, int $schedule_id)
    {
        $relations = ['times'];
        $schedule = $this->scheduleService->findById($schedule_id, $relations);

        return view('clinic::schedules.edit', [
            'clinic' => $clinic,
            'clinicService' => $clinic_service,
            'schedule' => $schedule,
        ]);
    }

    public function update(ClinicServiceScheduleRequest $request, Clinic $clinic, ClinicService $clinic_service, int $schedule_id): RedirectResponse
    {
        $dto = ClinicServiceScheduleDto::fromRequest($request);
        $this->scheduleService->update($schedule_id, $dto);

        return to_route('admin.clinic.services.schedules.index', [$clinic->id, $clinic_service->id])
            ->with('success', __('clinic::message.updated'));
    }

    public function destroy(Clinic $clinic, ClinicService $clinic_service, ClinicServiceSchedule $schedule): JsonResponse
    {
        $this->scheduleService->delete($schedule);

        return success(true, __('clinic::message.deleted'));
    }
}
