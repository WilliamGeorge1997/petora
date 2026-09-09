<?php

namespace Modules\Client\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Client\DTOs\ClientDto;
use Modules\Client\Http\Requests\ClientEditProfileRequest;
use Modules\Client\Models\Client;
use Modules\Client\Services\ClientService;

#[Middleware('auth:client')]
class ClientController extends Controller
{
    public function __construct(private ClientService $clientService) {}

    public function editProfile(ClientEditProfileRequest $request)
    {
        /** @var Client $client */
        $client = $request->user('client');
        $dto = ClientDto::fromClientRequest($request);
        $client = $this->clientService->update($client, $dto);

        return success(true, __('client::message.profile_updated'), $client);
    }
}
