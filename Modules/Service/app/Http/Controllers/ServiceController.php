<?php

namespace Modules\Service\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Admin\Enums\AdminRole;
use Modules\Service\DTOs\ServiceDto;
use Modules\Service\Http\Requests\ServiceRequest;
use Modules\Service\Models\Service;
use Modules\Service\Services\ServiceService;
use Illuminate\Support\Facades\Gate;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-service|Create-service|Edit-service|Delete-service', only: ['index', 'export', 'store'])]
#[Middleware('permission:Create-service', only: ['create', 'store'])]
#[Middleware('permission:Edit-service', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-service', only: ['destroy'])]
class ServiceController extends Controller
{
    public function __construct(private ServiceService $serviceService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $services = $this->serviceService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('service::message.fetched'), $services->items());
        }
        return view('service::services.index', compact('services'));
    }

    public function create()
    {
        return view('service::services.create');
    }

    public function store(ServiceRequest $request)
    {
        $dto = ServiceDto::fromRequest($request);
        $this->serviceService->save($dto);
        return to_route('admin.service.index')->with('success', __('service::message.created'));
    }

    public function edit(Service $service)
    {
        Gate::authorize('update', $service);
        return view('service::services.edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        Gate::authorize('update', $service);
        $dto = ServiceDto::fromRequest($request);
        $this->serviceService->update($service, $dto);
        return to_route('admin.service.index')->with('success', __('service::message.updated'));
    }

    public function destroy(Service $service)
    {
        $this->serviceService->delete($service);
        return success(true, __('service::message.deleted'));
    }

    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ServiceExport(), 'services.xlsx');
    }

    public function activate(Service $service)
    {
        $service = $this->serviceService->activate($service);
        return success(
            true,
            $service->is_active ? __('service::message.activated') : __('service::message.deactivated'),
            $service
        );
    }
}
