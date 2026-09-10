<?php

namespace Modules\Store\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Store\DTOs\StoreDto;
use Modules\Store\Models\Store;

class StoreService
{
    use UploaderHelper;

    private string $model = Store::class;

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

    public function findBy(string $column, mixed $value, array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);

        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);

        return getCaseCollection($query, $data, $columns);
    }

    public function save(StoreDto $dto): Store
    {
        return DB::transaction(function () use ($dto) {
            $data = $dto->toArray();
            if ($dto->image) {
                $data['image'] = $this->uploadImage($dto->image, 'store');
            }

            /** @var Store $store */
            $store = $this->model::create($data);

            if (! empty($dto->workingHours)) {
                $this->syncWorkingHours($store, $dto->workingHours);
            }

            return $store;
        });
    }

    public function update(int|Store $storeOrId, StoreDto $dto): Store
    {
        $store = $this->resolveModel($storeOrId);

        return DB::transaction(function () use ($store, $dto) {
            $data = $dto->toArray();
            if ($dto->image) {
                if ($store->image) {
                    $this->deleteImage($store->image, 'store');
                }

                $data['image'] = $this->uploadImage($dto->image, 'store');
            }

            $store->update($data);

            if ($dto->workingHours !== null) {
                $this->syncWorkingHours($store, $dto->workingHours);
            }

            return $store;
        });
    }

    public function syncWorkingHours(Store $store, array $workingHours): void
    {
        $days = [];

        foreach ($workingHours as $item) {
            if (empty($item['day'])) {
                continue;
            }

            $isOpen24 = (bool) ($item['is_open_24_hours'] ?? false);

            $store->workingHours()->updateOrCreate(
                ['day' => $item['day']],
                [
                    'is_open_24_hours' => $isOpen24,
                    'from' => $isOpen24 ? null : ($item['from'] ?? null),
                    'to' => $isOpen24 ? null : ($item['to'] ?? null),
                ]
            );

            $days[] = $item['day'];
        }

        $store->workingHours()->whereNotIn('day', $days)->delete();
    }

    public function delete(int|Store $storeOrId): bool
    {
        $store = $this->resolveModel($storeOrId);
        if ($store->image) {
            $this->deleteImage($store->image, 'store');
        }

        return $store->delete();
    }

    public function activate(int|Store $storeOrId): Store
    {
        $store = $this->resolveModel($storeOrId);
        $store->update(['is_active' => ! $store->is_active]);

        return $store;
    }
}
