<?php

namespace Modules\Client\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Admin\Enums\AdminRole;
use Modules\Client\DTOs\ClientDto;
use Modules\Client\Http\Requests\ClientRequest;
use Modules\Client\Models\Client;
use Modules\Client\Services\ClientService;
use Illuminate\Support\Facades\Gate;

class ClientController extends Controller implements HasMiddleware
{
    public function __construct(private ClientService $clientService) {}

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('role:' . AdminRole::SuperAdmin->value, except: ['edit', 'update']),
            new Middleware('permission:Index-client|Create-client|Edit-client|Delete-client', only: ['index', 'store']),
            new Middleware('permission:Create-client', only: ['create', 'store']),
            new Middleware('permission:Edit-client', only: ['edit', 'update', 'activate']),
            new Middleware('permission:Delete-client', only: ['destroy']),
        ];
    }

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
