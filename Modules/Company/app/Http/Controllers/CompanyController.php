<?php

namespace Modules\Company\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Admin\Enums\AdminRole;
use Modules\Company\DTOs\CompanyDto;
use Modules\Company\Http\Requests\CompanyRequest;
use Modules\Company\Models\Company;
use Modules\Company\Services\CompanyService;
use Illuminate\Support\Facades\Gate;

class CompanyController implements HasMiddleware
{
    public function __construct(private CompanyService $companyService) {}

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('role:' . AdminRole::SuperAdmin->value, except: ['edit', 'update']),
            new Middleware('permission:Index-company|Create-company|Edit-company|Delete-company', only: ['index', 'store']),
            new Middleware('permission:Create-company', only: ['create', 'store']),
            new Middleware('permission:Edit-company', only: ['edit', 'update', 'activate']),
            new Middleware('permission:Delete-company', only: ['destroy']),
        ];
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = $request->merge(['paginated' => 50])->all();
            $companies = $this->companyService->findAll($data);
            return success(true, __('company::message.fetched'), $companies->items());
        }
        return view('company::companies.index');
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
            $company->is_active ?  __('company::message.activated') :  __('company::message.deactivated'),
            $company
        );
    }
}
