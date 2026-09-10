<?php

namespace Modules\Client\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Client\DTOs\ClientDto;
use Modules\Client\Http\Requests\ClientRequest;
use Modules\Client\Models\Client;
use Modules\Client\Services\ClientService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-client|Create-client|Edit-client|Delete-client', only: ['index', 'store'])]
#[Middleware('permission:Create-client', only: ['create', 'store'])]
#[Middleware('permission:Edit-client', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-client', only: ['destroy'])]
class ClientController extends Controller
{
    public function __construct(private ClientService $clientService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $clients = $this->clientService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('client::message.fetched'), $clients->items());
        }

        return view('client::clients.index', compact('clients'));
    }

    public function create()
    {
        return view('client::clients.create');
    }

    public function store(ClientRequest $request)
    {
        $dto = ClientDto::fromAdminRequest($request);
        $this->clientService->save($dto);

        return to_route('admin.client.index')->with('success', __('client::message.created'));
    }

    public function edit(Client $client)
    {
        // Add gate policy for clients if implemented, e.g. Gate::authorize('update', $client);
        return view('client::clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client)
    {
        // Gate::authorize('update', $client);
        $dto = ClientDto::fromAdminRequest($request);
        $this->clientService->update($client, $dto);

        return to_route('admin.client.index')->with('success', __('client::message.updated'));
    }

    public function destroy(Client $client)
    {
        $this->clientService->delete($client);

        return success(true, __('client::message.deleted'));
    }

    public function activate(Client $client)
    {
        $client = $this->clientService->activate($client);

        return success(
            true,
            $client->is_active ? __('client::message.activated') : __('client::message.deactivated'),
            $client
        );
    }
}
