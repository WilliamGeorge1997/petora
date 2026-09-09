<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Store\DTOs\StoreDeliveryScheduleDto;
use Modules\Store\Http\Requests\StoreDeliveryScheduleRequest;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreDeliverySchedule;
use Modules\Store\Services\StoreDeliveryScheduleService;

#[Middleware('auth:admin')]
#[Middleware('permission:Edit-store')]
class StoreDeliveryScheduleController extends Controller
{
    public function __construct(private StoreDeliveryScheduleService $scheduleService) {}

    public function index(Request $request, Store $store): View|JsonResponse
    {
        $data = $request->merge(['store_id' => $store->id])->all();
        $relations = ['times'];

        $schedules = $this->scheduleService->findAll($data, $relations);

        if ($request->ajax()) {
            return success(true, __('store::message.fetched'), $schedules);
        }

        return view('store::delivery-schedules.index', [
            'store'     => $store,
            'schedules' => $schedules,
        ]);
    }

    public function create(Request $request, Store $store): View
    {
        $schedules = $this->scheduleService->findBy('store_id', $store->id);

        return view('store::delivery-schedules.create', [
            'store'     => $store,
            'schedules' => $schedules,
        ]);
    }

    public function store(StoreDeliveryScheduleRequest $request, Store $store): RedirectResponse
    {
        $dto = StoreDeliveryScheduleDto::fromRequest($request);
        $this->scheduleService->save($store, $dto);

        return to_route('admin.store.delivery-schedules.index', $store->id)
            ->with('success', __('store::message.created'));
    }

    public function edit(Store $store, int $schedule_id): View
    {
        $relations = ['times'];
        $schedule = $this->scheduleService->findById($schedule_id, $relations);

        return view('store::delivery-schedules.edit', [
            'store'    => $store,
            'schedule' => $schedule,
        ]);
    }

    public function update(StoreDeliveryScheduleRequest $request, Store $store, int $schedule_id): RedirectResponse
    {
        $dto = StoreDeliveryScheduleDto::fromRequest($request);
        $this->scheduleService->update($schedule_id, $dto);

        return to_route('admin.store.delivery-schedules.index', $store->id)
            ->with('success', __('store::message.updated'));
    }

    public function destroy(Store $store, StoreDeliverySchedule $schedule): JsonResponse
    {
        $this->scheduleService->delete($schedule);

        return success(true, __('store::message.deleted'));
    }
}
