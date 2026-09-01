<?php

namespace Modules\Client\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Client\DTOs\ClientDto;
use Modules\Client\Models\Client;
use Modules\Common\Helpers\UploaderHelper;

class ClientService
{
    use UploaderHelper;
    public function __construct(private Client $model) {}

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->when(isset($data['is_active']) && $data['is_active'] !== '', function (Builder $query) use ($data) {
                return $query->where('is_active', (bool) $data['is_active']);
            })
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Client
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Client $clientOrId): Client
    {
        return $clientOrId instanceof Client ? $clientOrId : $this->findById($clientOrId);
    }

    public function findBy(string $column, mixed $value, array $data = [], array $relations = []):  LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']):  LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);
        return getCaseCollection($query, $data, $columns);
    }


    public function save(ClientDto $dto): Client
    {
        $data = $dto->toArray();
        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image, 'client');
        }

        return $this->model::create($data);
    }

    public function update(int|Client $clientOrId, ClientDto $dto): Client
    {
        $client = $this->resolveModel($clientOrId);
        $data = $dto->toArray();
        if ($dto->image) {
            if ($client->image) $this->deleteImage($client->image, 'client');

            $data['image'] = $this->uploadImage($dto->image, 'client');
        }

        $client->update($data);
        return $client;
    }

    public function delete(int|Client $clientOrId): bool
    {
        $client = $this->resolveModel($clientOrId);
        if ($client->image) $this->deleteImage($client->image, 'client');
        return $client->delete();
    }

    public function activate(int|Client $clientOrId): Client
    {
        $client = $this->resolveModel($clientOrId);
        $client->update(['is_active' => !$client->is_active]);
        return $client;
    }
}
