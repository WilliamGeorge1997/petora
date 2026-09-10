<?php

namespace Modules\Clinic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Clinic\DTOs\ClinicDeliveryScheduleDto;
use Modules\Clinic\Http\Requests\ClinicDeliveryScheduleRequest;
use Modules\Clinic\Models\Clinic;
use Modules\Clinic\Models\ClinicDeliverySchedule;
use Modules\Clinic\Services\ClinicDeliveryScheduleService;

#[Middleware('auth:admin')]
#[Middleware('permission:Edit-clinic')]
class ClinicDeliveryScheduleController extends Controller
{
    public function __construct(private ClinicDeliveryScheduleService $scheduleService) {}

    public function index(Request $request, Clinic $clinic): View|JsonResponse
    {
        $data = $request->merge(['clinic_id' => $clinic->id])->all();
        $relations = ['times'];

        $schedules = $this->scheduleService->findAll($data, $relations);

        if ($request->ajax()) {
            return success(true, __('clinic::message.fetched'), $schedules);
        }

        return view('clinic::delivery-schedules.index', [
            'clinic' => $clinic,
            'schedules' => $schedules,
        ]);
    }

    public function create(Request $request, Clinic $clinic): View
    {
        $schedules = $this->scheduleService->findBy('clinic_id', $clinic->id);

        return view('clinic::delivery-schedules.create', [
            'clinic' => $clinic,
            'schedules' => $schedules,
        ]);
    }

    public function store(ClinicDeliveryScheduleRequest $request, Clinic $clinic): RedirectResponse
    {
        $dto = ClinicDeliveryScheduleDto::fromRequest($request);
        $this->scheduleService->save($clinic, $dto);

        return to_route('admin.clinic.delivery-schedules.index', $clinic->id)
            ->with('success', __('clinic::message.created'));
    }

    public function edit(Clinic $clinic, int $schedule_id): View
    {
        $relations = ['times'];
        $schedule = $this->scheduleService->findById($schedule_id, $relations);

        return view('clinic::delivery-schedules.edit', [
            'clinic' => $clinic,
            'schedule' => $schedule,
        ]);
    }

    public function update(ClinicDeliveryScheduleRequest $request, Clinic $clinic, int $schedule_id): RedirectResponse
    {
        $dto = ClinicDeliveryScheduleDto::fromRequest($request);
        $this->scheduleService->update($schedule_id, $dto);

        return to_route('admin.clinic.delivery-schedules.index', $clinic->id)
            ->with('success', __('clinic::message.updated'));
    }

    public function destroy(Clinic $clinic, ClinicDeliverySchedule $schedule): JsonResponse
    {
        $this->scheduleService->delete($schedule);

        return success(true, __('clinic::message.deleted'));
    }
}
