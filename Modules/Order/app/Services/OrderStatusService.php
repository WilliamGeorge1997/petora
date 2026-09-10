<?php

namespace Modules\Order\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Order\DTOs\OrderStatusDto;
use Modules\Order\Models\OrderStatus;

class OrderStatusService
{
    private string $model = OrderStatus::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): OrderStatus
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|OrderStatus $orderStatusOrId): OrderStatus
    {
        return $orderStatusOrId instanceof OrderStatus ? $orderStatusOrId : $this->findById($orderStatusOrId);
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

    public function save(OrderStatusDto $dto): OrderStatus
    {
        $data = $dto->toArray();

        return $this->model::create($data);
    }

    public function update(int|OrderStatus $orderStatusOrId, OrderStatusDto $dto): OrderStatus
    {
        $orderStatus = $this->resolveModel($orderStatusOrId);
        $data = $dto->toArray();

        $orderStatus->update($data);

        return $orderStatus;
    }

    public function delete(int|OrderStatus $orderStatusOrId): bool
    {
        $orderStatus = $this->resolveModel($orderStatusOrId);

        return $orderStatus->delete();
    }

    public function activate(int|OrderStatus $orderStatusOrId): OrderStatus
    {
        $orderStatus = $this->resolveModel($orderStatusOrId);
        $orderStatus->update(['is_active' => ! $orderStatus->is_active]);

        return $orderStatus;
    }
}
