<?php

namespace Modules\Product\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Product\DTOs\ProductDto;
use Modules\Product\Models\Product;

class ProductService
{
    public function __construct(private Product $model) {}

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Product
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Product $productOrId): Product
    {
        return $productOrId instanceof Product ? $productOrId : $this->findById($productOrId);
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

    public function save(ProductDto $dto): Product
    {
        return $this->model::create($dto->toArray());
    }

    public function update(int|Product $productOrId, ProductDto $dto): Product
    {
        $product = $this->resolveModel($productOrId);
        $product->update($dto->toArray());
        return $product;
    }

    public function delete(int|Product $productOrId): bool
    {
        $product = $this->resolveModel($productOrId);
        return $product->delete();
    }

    public function activate(int|Product $productOrId): Product
    {
        $product = $this->resolveModel($productOrId);
        $product->update(['is_active' => !$product->is_active]);
        return $product;
    }
}
