<?php

namespace Modules\Store\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Store\DTOs\StoreDto;
use Modules\Store\Models\Store;

class StoreService
{
    use UploaderHelper;

    public function __construct(private Store $model) {}

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->when($data['title'] ?? null, function (Builder $query) use ($data) {
                return $query->whereJsonContainsLocales('title', ['en', 'ar'], "%{$data['title']}%", 'LIKE');
            })
            ->when(isset($data['is_active']) && $data['is_active'] !== '', function (Builder $query) use ($data) {
                return $query->where('is_active', (bool) $data['is_active']);
            })
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Store
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []): Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']): Collection
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

    public function update(Store $store, StoreDto $dto): Store
    {
        $data = $dto->toArray();
        if ($dto->image) {
            if ($store->image) $this->deleteImage($store->image, 'store');

            $data['image'] = $this->uploadImage($dto->image, 'store');
        }

        $store->update($data);
        return $store;
    }

    public function delete(Store $store): bool
    {
        if ($store->image) $this->deleteImage($store->image, 'store');
        return $store->delete();
    }

    public function activate(Store $store): Store
    {
        $store->update(['is_active' => !$store->is_active]);
        return $store;
    }
}
