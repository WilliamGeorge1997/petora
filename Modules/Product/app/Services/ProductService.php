<?php

namespace Modules\Product\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Validation\ValidationException;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Product\DTOs\ProductDto;
use Modules\Product\Models\Product;

class ProductService
{
    use UploaderHelper;

    private string $model = Product::class;

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
        $product = $this->model::create($dto->toArray());

        if ($dto->images) {
            foreach ($dto->images as $image) {
                $product->images()->create([
                    'image' => $this->uploadImage($image, 'product'),
                ]);
            }
        }

        return $product;
    }

    public function update(int|Product $productOrId, ProductDto $dto): Product
    {
        $product = $this->resolveModel($productOrId);
        $product->update($dto->toArray());

        if ($dto->images) {
            foreach ($product->images as $oldImage) {
                $this->deleteImage($oldImage->getRawOriginal('image'), 'product');
            }
            $product->images()->delete();

            foreach ($dto->images as $image) {
                $product->images()->create([
                    'image' => $this->uploadImage($image, 'product'),
                ]);
            }
        }

        return $product;
    }

    public function delete(int|Product $productOrId): bool
    {
        $product = $this->resolveModel($productOrId);

        foreach ($product->images as $image) {
            $this->deleteImage($image->getRawOriginal('image'), 'product');
        }
        $product->images()->delete();

        return $product->delete();
    }

    public function activate(int|Product $productOrId): Product
    {
        $product = $this->resolveModel($productOrId);
        $product->update(['is_active' => ! $product->is_active]);

        return $product;
    }

    // For API
    public function byCategoryAndSeller(int $categoryId, string $sellerType, int $sellerId, array $data = [], array $relations = [], array $columns = ['*']): LengthAwarePaginator|CursorPaginator|Collection
    {
        $type = $sellerType == 'store' ? 'stores' : 'clinics';
        $query = $this->model::query()->with($relations)->active()->latest('id')
            ->where('category_id', $categoryId)
            ->withWhereHas($type, function ($q) use ($type, $sellerId) {
                $q->where("$type.id", $sellerId)
                    ->where('product_sellers.is_active', true);
            });

        return getCaseCollection($query, $data, $columns);
    }

    public function checkProducts(array $items, ?array $seller): Collection
    {
        $products = $this->model::select('id', 'title', 'is_active')
            ->whereIn('id', collect($items)->pluck('product_id')->toArray())
            ->when($seller, function ($q) use ($seller) {
                $relation = $seller['relation']; // 'stores' or 'clinics'

                return $q->with([$relation => function ($sq) use ($seller, $relation) {
                    $sq->where($relation . '.id', $seller['id']);
                }]);
            })
            ->get();

        $this->validateForOrdering($products, $items, $seller);

        return $products;
    }

    private function validateForOrdering(Collection $products, array $items, ?array $seller): void
    {
        $errors = [];

        foreach ($items as $item) {
            $product = $products->firstWhere('id', $item['product_id']);

            if (!$product) {
                $errors[] = __('product::message.not_found', ['id' => $item['product_id']]);
                continue;
            }

            if (!$product->is_active) {
                $errors[] = __('product::message.inactive', ['title' => $product->title]);
                continue;
            }

            if ($seller) {
                $sellerData = $product->{$seller['relation']}->first();
                $sellerType = $seller['relation'] === 'stores' ? 'store' : 'clinic';

                if (!$sellerData) {
                    $errors[] = __('product::message.not_available_' . $sellerType, ['title' => $product->title]);
                    continue;
                }

                if (!$sellerData->pivot->is_active) {
                    $errors[] = __('product::message.seller_inactive_' . $sellerType, ['title' => $product->title]);
                    continue;
                }
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages(['items' => $errors]);
        }
    }
}
