<?php

namespace Modules\Pet\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Pet\Classes\DTOs\PetDto;
use Modules\Pet\Http\Requests\PetRequest;
use Modules\Pet\Models\Pet;
use Modules\Pet\Services\PetService;
use Modules\Pet\Transformers\PetResource;

#[Middleware('auth:client')]
class PetController extends Controller
{
    public function __construct(private PetService $petService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $relations = ['type'];
        $pets = $this->petService->findBy('client_id', auth('client')->id(), $data, $relations);

        return success(true, __('pet::message.fetched'), paginatedResource($pets, PetResource::class));
    }

    public function store(PetRequest $request)
    {
        $dto = PetDto::fromRequest($request);
        $pet = $this->petService->save($dto);

        return success(true, __('pet::message.created'), $pet);
    }

    public function show(int $pet_id)
    {
        $pet = $this->petService->findById($pet_id, ['type']);
        
        Gate::authorize('view', $pet);

        return success(true, __('pet::message.fetched'), new PetResource($pet));
    }

    public function update(PetRequest $request, Pet $pet)
    {
        Gate::authorize('update', $pet);

        $dto = PetDto::fromRequest($request);
        $pet = $this->petService->update($pet, $dto);

        return success(true, __('pet::message.updated'), $pet);
    }

    public function destroy(Pet $pet)
    {
        Gate::authorize('delete', $pet);

        $this->petService->delete($pet);
        return success(true, __('pet::message.deleted'));
    }
}
