<?php

namespace Modules\Pet\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Pet\DTOs\PetDto;
use Modules\Pet\Http\Requests\PetRequest;
use Modules\Pet\Models\Pet;
use Modules\Pet\Services\PetService;
use Modules\Pet\ViewModels\PetViewModel;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-pet|Create-pet|Edit-pet|Delete-pet', only: ['index', 'store'])]
#[Middleware('permission:Create-pet', only: ['create', 'store'])]
#[Middleware('permission:Edit-pet', only: ['edit', 'update'])]
#[Middleware('permission:Delete-pet', only: ['destroy'])]
class PetController extends Controller
{
    public function __construct(private PetService $petService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $pets = $this->petService->findAll($data, ['client', 'type']);
        if ($request->ajax()) {
            return success(true, __('pet::message.fetched'), $pets->items());
        }

        return view('pet::pets.index', compact('pets'));
    }

    public function create()
    {
        $viewModel = new PetViewModel;

        return view('pet::pets.create', compact('viewModel'));
    }

    public function store(PetRequest $request)
    {
        $dto = PetDto::fromRequest($request);
        $this->petService->save($dto);

        return to_route('admin.pet.index')->with('success', __('pet::message.created'));
    }

    public function edit(Pet $pet)
    {
        $viewModel = new PetViewModel;

        return view('pet::pets.edit', compact('viewModel', 'pet'));
    }

    public function update(PetRequest $request, Pet $pet)
    {
        $dto = PetDto::fromRequest($request);
        $this->petService->update($pet, $dto);

        return to_route('admin.pet.index')->with('success', __('pet::message.updated'));
    }

    public function destroy(Pet $pet)
    {
        $this->petService->delete($pet);

        return success(true, __('pet::message.deleted'));
    }
}
