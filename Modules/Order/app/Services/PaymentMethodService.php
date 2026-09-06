<?php

namespace Modules\Order\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Order\DTOs\PaymentMethodDto;
use Modules\Order\Models\PaymentMethod;

class PaymentMethodService
{
    private string $model = PaymentMethod::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): PaymentMethod
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|PaymentMethod $paymentMethodOrId): PaymentMethod
    {
        return $paymentMethodOrId instanceof PaymentMethod ? $paymentMethodOrId : $this->findById($paymentMethodOrId);
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

    public function save(PaymentMethodDto $dto): PaymentMethod
    {
        $data = $dto->toArray();
        return $this->model::create($data);
    }

    public function update(int|PaymentMethod $paymentMethodOrId, PaymentMethodDto $dto): PaymentMethod
    {
        $paymentMethod = $this->resolveModel($paymentMethodOrId);
        $data = $dto->toArray();

        $paymentMethod->update($data);
        return $paymentMethod;
    }

    public function delete(int|PaymentMethod $paymentMethodOrId): bool
    {
        $paymentMethod = $this->resolveModel($paymentMethodOrId);
        return $paymentMethod->delete();
    }

    public function activate(int|PaymentMethod $paymentMethodOrId): PaymentMethod
    {
        $paymentMethod = $this->resolveModel($paymentMethodOrId);
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);
        return $paymentMethod;
    }
}