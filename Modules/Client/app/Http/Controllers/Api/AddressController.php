<?php

namespace Modules\Client\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Client\DTOs\AddressDto;
use Modules\Client\Http\Requests\AddressRequest;
use Modules\Client\Models\Address;
use Modules\Client\Services\AddressService;
use Modules\Client\Transformers\AddressResource;

#[Middleware('auth:client')]
class AddressController extends Controller
{
    public function __construct(private AddressService $addressService) {}

    public function index(Request $request): JsonResponse
    {
        $data = $request->merge([
            'pagination_type' => 'cursor',
        ])->all();

        $addresses = $this->addressService->findBy('client_id', auth('client')->id(), $data, ['country', 'city', 'zone']);

        return success(true, __('client::message.address.fetched'), paginatedResource($addresses, AddressResource::class));
    }

    public function store(AddressRequest $request): JsonResponse
    {
        $dto = AddressDto::fromRequest($request);
        $address = $this->addressService->save($dto);

        return success(true, __('client::message.address.created'), $address);
    }

    public function update(AddressRequest $request, Address $address): JsonResponse
    {
        $dto = AddressDto::fromRequest($request);
        $address = $this->addressService->update($address, $dto);

        return success(true, __('client::message.address.updated'), $address);
    }

    public function default(Address $address): JsonResponse
    {
        $address = $this->addressService->makeDefault($address);

        return success(true, __('client::message.address.updated'), $address);
    }

    public function destroy(Address $address): JsonResponse
    {
        try {
            $this->addressService->delete($address);

            return success(true, __('client::message.address.deleted'));
        } catch (Exception $e) {
            return success(false, $e->getMessage(), null, 'bad_request');
        }
    }
}
