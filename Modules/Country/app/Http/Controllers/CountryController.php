<?php

namespace Modules\Country\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Admin\Enums\AdminRole;
use Modules\Country\DTOs\CountryDto;
use Modules\Country\Http\Requests\CountryRequest;
use Modules\Country\Models\Country;
use Modules\Country\Services\CountryService;
use Illuminate\Support\Facades\Gate;

class CountryController implements HasMiddleware
{
    public function __construct(private CountryService $countryService) {}

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
        $countries = $this->countryService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('country::message.fetched'), $countries->items());
        }
        return view('country::country.index', compact('countries'));
    }

    public function create()
    {
        return view('country::country.create');
    }

    public function store(CountryRequest $request)
    {
        $dto = CountryDto::fromRequest($request);
        $this->countryService->save($dto);
        return to_route('admin.countries.index')->with('success', __('country::message.created'));
    }

    public function edit(Country $country)
    {
        Gate::authorize('update', $country);
        return view('country::country.edit', compact('country'));
    }

    public function update(CountryRequest $request, Country $country)
    {
        Gate::authorize('update', $country);
        $dto = CountryDto::fromRequest($request);
        $this->countryService->update($country, $dto);
        return to_route('admin.countries.index')->with('success', __('country::message.updated'));
    }

    public function destroy(Country $country)
    {
        $this->countryService->delete($country);
        return success(true, __('country::message.deleted'));
    }

    public function activate(Country $country)
    {
        $country = $this->countryService->activate($country);
        return success(
            true,
            $country->is_active ?  __('country::message.activated') :  __('country::message.deactivated'),
            $country
        );
    }
}
