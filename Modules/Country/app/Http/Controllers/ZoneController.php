<?php

namespace Modules\Country\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Country\DTOs\ZoneDto;
use Modules\Country\Http\Requests\ZoneRequest;
use Modules\Country\Models\Zone;
use Modules\Country\Services\ZoneService;
use Modules\Country\ViewModels\ZoneViewModel;

class ZoneController implements HasMiddleware
{
    public function __construct(private ZoneService $zoneService) {}

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:Index-country|Create-country|Edit-country|Delete-country', only: ['index', 'store']),
            new Middleware('permission:Create-country', only: ['create', 'store']),
            new Middleware('permission:Edit-country', only: ['edit', 'update', 'activate']),
            new Middleware('permission:Delete-country', only: ['destroy']),
        ];
    }

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $zones = $this->zoneService->findAll($data, ['city']);
        if ($request->ajax()) {
            return success(true, __('country::message.fetched'), $zones->items());
        }

        return view('country::zone.index', compact('zones'));
    }

    public function create()
    {
        $viewModel = new ZoneViewModel;

        return view('country::zone.create', compact('viewModel'));
    }

    public function store(ZoneRequest $request)
    {
        $dto = ZoneDto::fromRequest($request);
        $this->zoneService->save($dto);

        return to_route('admin.zones.index')->with('success', __('country::message.created'));
    }

    public function edit(Zone $zone)
    {
        Gate::authorize('update', $zone);
        $viewModel = new ZoneViewModel;

        return view('country::zone.edit', compact('zone', 'viewModel'));
    }

    public function update(ZoneRequest $request, Zone $zone)
    {
        Gate::authorize('update', $zone);
        $dto = ZoneDto::fromRequest($request);
        $this->zoneService->update($zone, $dto);

        return to_route('admin.zones.index')->with('success', __('country::message.updated'));
    }

    public function destroy(Zone $zone)
    {
        $this->zoneService->delete($zone);

        return success(true, __('country::message.deleted'));
    }

    public function activate(Zone $zone)
    {
        $zone = $this->zoneService->activate($zone);

        return success(
            true,
            $zone->is_active ? __('country::message.activated') : __('country::message.deactivated'),
            $zone
        );
    }

    public function ajax(Request $request)
    {
        $zones = $this->zoneService->findBy($request->column, $request->value, $request->all());

        return success(true, __('country::message.fetched'), $zones);
    }
}
