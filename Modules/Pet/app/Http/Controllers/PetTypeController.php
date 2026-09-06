<?php

namespace Modules\Pet\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Pet\Classes\DTOs\PetTypeDto;
use Modules\Pet\Http\Requests\PetTypeRequest;
use Modules\Pet\Models\PetType;
use Modules\Pet\Services\PetTypeService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-pettype|Create-pettype|Edit-pettype|Delete-pettype', only: ['index', 'store'])]
#[Middleware('permission:Create-pettype', only: ['create', 'store'])]
#[Middleware('permission:Edit-pettype', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-pettype', only: ['destroy'])]
class PetTypeController extends Controller
{
    public function __construct(private PetTypeService $petTypeService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $petTypes = $this->petTypeService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('pet::message.type_fetched'), $petTypes->items());
        }
        return view('pet::pet_types.index', compact('petTypes'));
    }

    public function create()
    {
        return view('pet::pet_types.create');
    }

    public function store(PetTypeRequest $request)
    {
        $dto = PetTypeDto::fromRequest($request);
        $this->petTypeService->save($dto);
        return to_route('admin.pet_type.index')->with('success', __('pet::message.created'));
    }

    public function edit(PetType $petType)
    {
        return view('pet::pet_types.edit', compact('petType'));
    }

    public function update(PetTypeRequest $request, PetType $petType)
    {
        $dto = PetTypeDto::fromRequest($request);
        $this->petTypeService->update($petType, $dto);
        return to_route('admin.pet_type.index')->with('success', __('pet::message.updated'));
    }

    public function destroy(PetType $petType)
    {
        $this->petTypeService->delete($petType);
        return success(true, __('pet::message.deleted'));
    }

    public function activate(PetType $petType)
    {
        $petType = $this->petTypeService->activate($petType);
        return success(
            true,
            $petType->is_active ? __('pet::message.activated') : __('pet::message.deactivated'),
            $petType
        );
    }
}
