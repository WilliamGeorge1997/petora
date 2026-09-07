<?php

namespace Modules\Order\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Order\DTOs\OrderMethodDto;
use Modules\Order\Models\OrderMethod;

class OrderMethodService
{
    private string $model = OrderMethod::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): OrderMethod
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|OrderMethod $orderMethodOrId): OrderMethod
    {
        return $orderMethodOrId instanceof OrderMethod ? $orderMethodOrId : $this->findById($orderMethodOrId);
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

    public function save(OrderMethodDto $dto): OrderMethod
    {
        $data = $dto->toArray();
        return $this->model::create($data);
    }

    public function update(int|OrderMethod $orderMethodOrId, OrderMethodDto $dto): OrderMethod
    {
        $orderMethod = $this->resolveModel($orderMethodOrId);
        $data = $dto->toArray();

        $orderMethod->update($data);
        return $orderMethod;
    }

    public function delete(int|OrderMethod $orderMethodOrId): bool
    {
        $orderMethod = $this->resolveModel($orderMethodOrId);
        return $orderMethod->delete();
    }

    public function activate(int|OrderMethod $orderMethodOrId): OrderMethod
    {
        $orderMethod = $this->resolveModel($orderMethodOrId);
        $orderMethod->update(['is_active' => !$orderMethod->is_active]);
        return $orderMethod;
    }
}
