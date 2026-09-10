<?php

namespace Modules\Country\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Country\DTOs\CityDto;
use Modules\Country\Http\Requests\CityRequest;
use Modules\Country\Models\City;
use Modules\Country\Services\CityService;
use Modules\Country\ViewModels\CityViewModel;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-country|Create-country|Edit-country|Delete-country', only: ['index', 'store'])]
#[Middleware('permission:Create-country', only: ['create', 'store'])]
#[Middleware('permission:Edit-country', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-country', only: ['destroy'])]
class CityController extends Controller
{
    public function __construct(private CityService $cityService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $cities = $this->cityService->findAll($data, ['country']);
        if ($request->ajax()) {
            return success(true, __('country::message.fetched'), $cities->items());
        }

        return view('country::city.index', compact('cities'));
    }

    public function create()
    {
        $viewModel = new CityViewModel;

        return view('country::city.create', compact('viewModel'));
    }

    public function store(CityRequest $request)
    {
        $dto = CityDto::fromRequest($request);
        $this->cityService->save($dto);

        return to_route('admin.cities.index')->with('success', __('country::message.created'));
    }

    public function edit(City $city)
    {
        Gate::authorize('update', $city);
        $viewModel = new CityViewModel;

        return view('country::city.edit', compact('city', 'viewModel'));
    }

    public function update(CityRequest $request, City $city)
    {
        Gate::authorize('update', $city);
        $dto = CityDto::fromRequest($request);
        $this->cityService->update($city, $dto);

        return to_route('admin.cities.index')->with('success', __('country::message.updated'));
    }

    public function destroy(City $city)
    {
        $this->cityService->delete($city);

        return success(true, __('country::message.deleted'));
    }

    public function activate(City $city)
    {
        $city = $this->cityService->activate($city);

        return success(
            true,
            $city->is_active ? __('country::message.activated') : __('country::message.deactivated'),
            $city
        );
    }

    public function ajax(Request $request)
    {
        $cities = $this->cityService->findBy($request->column, $request->value, $request->all());

        return success(true, __('country::message.fetched'), $cities);
    }
}
