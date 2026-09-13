<?php

namespace Modules\Driver\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Driver\DTOs\DriverDto;
use Modules\Driver\Http\Requests\DriverRequest;
use Modules\Driver\Models\Driver;
use Modules\Driver\Services\DriverService;
use Modules\Driver\ViewModels\DriverViewModel;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-driver|Create-driver|Edit-driver|Delete-driver', only: ['index', 'store'])]
#[Middleware('permission:Create-driver', only: ['create', 'store'])]
#[Middleware('permission:Edit-driver', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-driver', only: ['destroy'])]
class DriverController extends Controller
{
    public function __construct(private DriverService $driverService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $drivers = $this->driverService->findAll($data, ['store', 'clinic']);
        if ($request->ajax()) {
            return success(true, __('driver::message.fetched'), $drivers->items());
        }

        return view('driver::drivers.index', compact('drivers'));
    }

    public function create(): View
    {
        $viewModel = new DriverViewModel;

        return view('driver::drivers.create', compact('viewModel'));
    }

    public function store(DriverRequest $request): RedirectResponse
    {
        $dto = DriverDto::fromRequest($request);
        $this->driverService->save($dto);

        return to_route('admin.driver.index')->with('success', __('driver::message.created'));
    }

    public function edit(Driver $driver): View
    {
        Gate::authorize('update', $driver);
        $viewModel = new DriverViewModel;

        return view('driver::drivers.edit', compact('viewModel', 'driver'));
    }

    public function update(DriverRequest $request, Driver $driver): RedirectResponse
    {
        Gate::authorize('update', $driver);
        $dto = DriverDto::fromRequest($request);
        $this->driverService->update($driver, $dto);

        return to_route('admin.driver.index')->with('success', __('driver::message.updated'));
    }

    public function destroy(Driver $driver): JsonResponse
    {
        $this->driverService->delete($driver);

        return success(true, __('driver::message.deleted'));
    }

    public function activate(Driver $driver): JsonResponse
    {
        $driver = $this->driverService->activate($driver);

        return success(
            true,
            $driver->is_active ? __('driver::message.activated') : __('driver::message.deactivated'),
            $driver
        );
    }
}
