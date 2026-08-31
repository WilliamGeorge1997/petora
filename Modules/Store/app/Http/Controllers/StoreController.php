<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Admin\Enums\AdminRole;
use Modules\Store\DTOs\StoreDto;
use Modules\Store\Http\Requests\StoreRequest;
use Modules\Store\Models\Store;
use Modules\Store\Services\StoreService;
use Illuminate\Support\Facades\Gate;
use Modules\Store\ViewModels\StoreViewModel;

#[Middleware('auth:admin')]
#[Middleware('role:' . AdminRole::SuperAdmin->value, except: ['edit', 'update'])]
#[Middleware('permission:Index-store|Create-store|Edit-store|Delete-store', only: ['index', 'store'])]
#[Middleware('permission:Create-store', only: ['create', 'store'])]
#[Middleware('permission:Edit-store', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-store', only: ['destroy'])]
class StoreController extends Controller
{
    public function __construct(private StoreService $storeService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $stores = $this->storeService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('store::message.fetched'), $stores->items());
        }
        return view('store::stores.index', compact('stores'));
    }

    public function create()
    {
        $viewModel = new StoreViewModel();
        return view('store::stores.create', compact('viewModel'));
    }

    public function store(StoreRequest $request)
    {
        $dto = StoreDto::fromRequest($request);
        $this->storeService->save($dto);
        return to_route('admin.store.index')->with('success', __('store::message.created'));
    }

    public function edit(Store $store)
    {
        Gate::authorize('update', $store);
        $viewModel = new StoreViewModel();
        return view('store::stores.edit', compact('viewModel', 'store'));
    }

    public function update(StoreRequest $request, Store $store)
    {
        Gate::authorize('update', $store);
        $dto = StoreDto::fromRequest($request);
        $this->storeService->update($store, $dto);
        return to_route('admin.store.index')->with('success', __('store::message.updated'));
    }

    public function destroy(Store $store)
    {
        $this->storeService->delete($store);
        return success(true, __('store::message.deleted'));
    }

    public function activate(Store $store)
    {
        $store = $this->storeService->activate($store);
        return success(
            true,
            $store->is_active ?  __('store::message.activated') :  __('store::message.deactivated'),
            $store
        );
    }
}
