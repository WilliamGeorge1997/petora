<?php

namespace Modules\Pet\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Pet\Classes\DTOs\PetDto;
use Modules\Pet\Http\Requests\PetRequest;
use Modules\Pet\Models\Pet;
use Modules\Pet\Services\PetService;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware('auth:client')]
class PetController extends Controller
{
    public function __construct(private PetService $petService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $pets = $this->petService->findAll($data, ['type']);

        return success(true, __('pet::message.fetched'), $pets);
    }

    public function store(PetRequest $request)
    {
        $request->merge(['client_id' => auth('client')->id()]);

        $dto = PetDto::fromRequest($request);
        $pet = $this->petService->save($dto);

        return success(true, __('pet::message.created'), $pet);
    }

    public function show($id)
    {
        $pet = $this->petService->findById($id, ['type']);

        if ($pet->client_id !== auth('client')->id()) {
            return fail(false, __('pet::message.unauthorized'), 403);
        }

        return success(true, __('pet::message.fetched'), $pet);
    }

    public function update(PetRequest $request, $id)
    {
        $pet = $this->petService->findById($id);

        if ($pet->client_id !== auth('client')->id()) {
            return fail(false, __('pet::message.unauthorized'), 403);
        }

        $request->merge(['client_id' => auth('client')->id()]);
        $dto = PetDto::fromRequest($request);

        $pet = $this->petService->update($pet, $dto);

        return success(true, __('pet::message.updated'), $pet);
    }

    public function destroy($id)
    {
        $pet = $this->petService->findById($id);

        if ($pet->client_id !== auth('client')->id()) {
            return fail(false, __('pet::message.unauthorized'), 403);
        }

        $this->petService->delete($pet);
        return success(true, __('pet::message.deleted'));
    }
}
