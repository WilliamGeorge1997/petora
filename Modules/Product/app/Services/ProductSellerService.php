<?php

namespace Modules\Product\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Product\Models\ProductSeller;
use Modules\Product\Models\ProductSellerImage;

class ProductSellerService
{
    use UploaderHelper;

    private string $model = ProductSeller::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): ProductSeller
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|ProductSeller $modelOrId, array $relations = []): ProductSeller
    {
        return $modelOrId instanceof ProductSeller ? $modelOrId : $this->findById($modelOrId, $relations);
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

    public function save(array $data, ?array $images = null): ProductSeller
    {
        return DB::transaction(function () use ($data, $images) {
            /** @var ProductSeller $sellerProduct */
            $sellerProduct = $this->model::create($data);

            if (! empty($images)) {
                foreach ($images as $image) {
                    $sellerProduct->images()->create([
                        'image' => $this->uploadImage($image, 'product_sellers'),
                    ]);
                }
            }

            return $sellerProduct;
        });
    }

    public function update(int|ProductSeller $modelOrId, array $data, ?array $images = null): ProductSeller
    {
        $sellerProduct = $this->resolveModel($modelOrId);

        return DB::transaction(function () use ($sellerProduct, $data, $images) {
            $sellerProduct->update($data);

            if (! empty($images)) {
                foreach ($images as $image) {
                    $sellerProduct->images()->create([
                        'image' => $this->uploadImage($image, 'product_sellers'),
                    ]);
                }
            }

            return $sellerProduct;
        });
    }

    public function delete(int|ProductSeller $modelOrId): bool
    {
        $sellerProduct = $this->resolveModel($modelOrId);

        return DB::transaction(function () use ($sellerProduct) {
            foreach ($sellerProduct->images as $img) {
                if ($img->getRawOriginal('image')) {
                    $this->deleteImage($img->getRawOriginal('image'), 'product_sellers');
                }
                $img->delete();
            }

            return $sellerProduct->delete();
        });
    }

    public function activate(int|ProductSeller $modelOrId): ProductSeller
    {
        $sellerProduct = $this->resolveModel($modelOrId);
        $sellerProduct->update(['is_active' => ! $sellerProduct->is_active]);

        return $sellerProduct;
    }

    public function findByStoreAndProduct(int $storeId, int $productId, array $relations = []): ProductSeller
    {
        return $this->model::query()
            ->where('store_id', $storeId)
            ->where('product_id', $productId)
            ->with($relations)
            ->firstOrFail();
    }

    public function findByClinicAndProduct(int $clinicId, int $productId, array $relations = []): ProductSeller
    {
        return $this->model::query()
            ->where('clinic_id', $clinicId)
            ->where('product_id', $productId)
            ->with($relations)
            ->firstOrFail();
    }

    public function deleteImage(int $imageId): bool
    {
        $image = ProductSellerImage::findOrFail($imageId);
        if ($image->getRawOriginal('image')) {
            $this->deleteImage($image->getRawOriginal('image'), 'product_sellers');
        }

        return $image->delete();
    }

    public function detachByStore(int $storeId, int $productId): void
    {
        $sellerProduct = $this->model::query()
            ->where('store_id', $storeId)
            ->where('product_id', $productId)
            ->with('images')
            ->first();

        if ($sellerProduct) {
            $this->delete($sellerProduct);
        }
    }

    public function detachByClinic(int $clinicId, int $productId): void
    {
        $sellerProduct = $this->model::query()
            ->where('clinic_id', $clinicId)
            ->where('product_id', $productId)
            ->with('images')
            ->first();

        if ($sellerProduct) {
            $this->delete($sellerProduct);
        }
    }
}
