<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Company\DTOs\CompanyDto;
use Modules\Company\Http\Requests\CompanyRequest;
use Modules\Company\Models\Company;
use Modules\Company\Services\CompanyService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-company|Create-company|Edit-company|Delete-company', only: ['index', 'store'])]
#[Middleware('permission:Create-company', only: ['create', 'store'])]
#[Middleware('permission:Edit-company', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-company', only: ['destroy'])]
class CompanyController extends Controller
{
    public function __construct(private CompanyService $companyService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $companies = $this->companyService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('company::message.fetched'), $companies->items());
        }

        return view('company::companies.index', compact('companies'));
    }

    public function create()
    {
        return view('company::companies.create');
    }

    public function store(CompanyRequest $request)
    {
        $dto = CompanyDto::fromRequest($request);
        $this->companyService->save($dto);

        return to_route('admin.company.index')->with('success', __('company::message.created'));
    }

    public function edit(Company $company)
    {
        Gate::authorize('update', $company);

        return view('company::companies.edit', compact('company'));
    }

    public function update(CompanyRequest $request, Company $company)
    {
        Gate::authorize('update', $company);
        $dto = CompanyDto::fromRequest($request);
        $this->companyService->update($company, $dto);

        return to_route('admin.company.index')->with('success', __('company::message.updated'));
    }

    public function destroy(Company $company)
    {
        $this->companyService->delete($company);

        return success(true, __('company::message.deleted'));
    }

    public function activate(Company $company)
    {
        $company = $this->companyService->activate($company);

        return success(
            true,
            $company->is_active ? __('company::message.activated') : __('company::message.deactivated'),
            $company
        );
    }
}
