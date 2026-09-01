<?php

namespace Modules\Store\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Store\DTOs\StoreDto;
use Modules\Store\Models\Store;
use Illuminate\Pagination\CursorPaginator;
class StoreService
{
    use UploaderHelper;

    public function __construct(private Store $model) {}

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Store
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Store $storeOrId): Store
    {
        return $storeOrId instanceof Store ? $storeOrId : $this->findById($storeOrId);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []):  LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']):  LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);
        return getCaseCollection($query, $data, $columns);
    }
    
    public function save(StoreDto $dto): Store
    {
        $data = $dto->toArray();
        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image, 'store');
        }

        return $this->model::create($data);
    }

    public function update(int|Store $storeOrId, StoreDto $dto): Store
    {
        $store = $this->resolveModel($storeOrId);
        $data = $dto->toArray();
        if ($dto->image) {
            if ($store->image) $this->deleteImage($store->image, 'store');

            $data['image'] = $this->uploadImage($dto->image, 'store');
        }

        $store->update($data);
        return $store;
    }

    public function delete(int|Store $storeOrId): bool
    {
        $store = $this->resolveModel($storeOrId);
        if ($store->image) $this->deleteImage($store->image, 'store');
        return $store->delete();
    }

    public function activate(int|Store $storeOrId): Store
    {
        $store = $this->resolveModel($storeOrId);
        $store->update(['is_active' => !$store->is_active]);
        return $store;
    }
}
