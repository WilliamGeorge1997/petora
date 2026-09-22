<?php

namespace Modules\Product\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Modules\Clinic\Models\Clinic;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Product\Models\SellerProduct;
use Modules\Product\Models\SellerProductImage;
use Modules\Store\Models\Store;

class SellerProductService
{
    use UploaderHelper;

    private string $model = SellerProduct::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): SellerProduct
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|SellerProduct $modelOrId, array $relations = []): SellerProduct
    {
        return $modelOrId instanceof SellerProduct ? $modelOrId : $this->findById($modelOrId, $relations);
    }

    public function findBy(string $column, mixed $value = null, array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function findByConditions(array $conditions, array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($conditions);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);

        return getCaseCollection($query, $data, $columns);
    }

    public function save(array $data, ?array $images = null): SellerProduct
    {
        return DB::transaction(function () use ($data, $images) {
            /** @var SellerProduct $sellerProduct */
            $sellerProduct = $this->model::create($data);

            if (! empty($images)) {
                foreach ($images as $image) {
                    $sellerProduct->images()->create([
                        'image' => $this->uploadImage($image, 'seller_products'),
                    ]);
                }
            }

            return $sellerProduct;
        });
    }

    public function update(int|SellerProduct $modelOrId, array $data, ?array $images = null): SellerProduct
    {
        $sellerProduct = $this->resolveModel($modelOrId);

        return DB::transaction(function () use ($sellerProduct, $data, $images) {
            $sellerProduct->update($data);

            if (! empty($images)) {
                foreach ($images as $image) {
                    $sellerProduct->images()->create([
                        'image' => $this->uploadImage($image, 'seller_products'),
                    ]);
                }
            }

            return $sellerProduct;
        });
    }

    public function delete(int|SellerProduct $modelOrId): bool
    {
        $sellerProduct = $this->resolveModel($modelOrId);

        return DB::transaction(function () use ($sellerProduct) {
            foreach ($sellerProduct->images as $img) {
                if ($img->getRawOriginal('image')) {
                    $this->deleteImage($img->getRawOriginal('image'), 'seller_products');
                }
                $img->delete();
            }

            return $sellerProduct->delete();
        });
    }

    public function activate(int|SellerProduct $modelOrId): SellerProduct
    {
        $sellerProduct = $this->resolveModel($modelOrId);
        $sellerProduct->update(['is_active' => ! $sellerProduct->is_active]);

        return $sellerProduct;
    }

    public function deleteImage(int $imageId): bool
    {
        $image = SellerProductImage::findOrFail($imageId);
        if ($image->getRawOriginal('image')) {
            $this->deleteImage($image->getRawOriginal('image'), 'seller_products');
        }

        return $image->delete();
    }

    public function detach(array $conditions): void
    {
        $sellerProduct = $this->findByConditions($conditions, relations: ['images'])->first();

        if ($sellerProduct) {
            $this->delete($sellerProduct);
        }
    }

    // Helper queries for generic sellers (Store or Clinic)
    public function findAllBySeller(Store|Clinic $seller, array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $seller->products()->with($relations)->filter($data)->latest('products.id');
        return getCaseCollection($query, $data);
    }

    public function importAllToSeller(Store|Clinic $seller): void
    {
        $existingProductIds = $seller->products()->pluck('products.id')->toArray();
        $activeProducts = app(ProductService::class)->activeExcept($existingProductIds, ['id', 'price']);
        $syncData = [];

        foreach ($activeProducts as $product) {
            $syncData[$product->id] = [
                'price' => (float) $product->price,
                'is_active' => true,
            ];
        }

        if (! empty($syncData)) {
            $seller->products()->attach($syncData);
        }
    }
}
