<?php

namespace Modules\Store\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductSeller;
use Modules\Product\Services\ProductSellerService;
use Modules\Product\Services\ProductService;
use Modules\Store\DTOs\StoreProductDto;
use Modules\Store\Models\Store;

class StoreProductService
{
    public function __construct(
        private StoreService $storeService,
        private ProductSellerService $productSellerService,
    ) {}

    public function products(int|Store $storeOrId, array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $store = $this->storeService->findById($storeOrId instanceof Store ? $storeOrId->id : $storeOrId);
        $query = $store->products()->with($relations)->filter($data)->latest('products.id');

        return getCaseCollection($query, $data);
    }

    public function importAllProducts(int|Store $storeOrId): void
    {
        $store = $this->storeService->findById($storeOrId instanceof Store ? $storeOrId->id : $storeOrId);
        $activeProducts = app(ProductService::class)->active();
        $syncData = [];

        foreach ($activeProducts as $product) {
            $syncData[$product->id] = [
                'title' => ["en" => $product->getTranslation('title', 'en'), "ar" => $product->getTranslation('title', 'ar')],
                'description' => ["en" => $product->getTranslation('description', 'en'), "ar" => $product->getTranslation('description', 'ar')],
                'price' => (float) $product->price,
                'is_active' => true,
            ];
        }

        if (! empty($syncData)) {
            $store->products()->syncWithoutDetaching($syncData);
        }
    }

    public function findProductSeller(int|Store $storeOrId, int $productId, array $relations = []): ProductSeller
    {
        $storeId = $storeOrId instanceof Store ? $storeOrId->id : $storeOrId;

        return $this->productSellerService->findByStoreAndProduct($storeId, $productId, $relations);
    }

    public function updateProductSeller(int|Store $storeOrId, int $productId, StoreProductDto $dto): ProductSeller
    {
        $storeId = $storeOrId instanceof Store ? $storeOrId->id : $storeOrId;
        $sellerProduct = $this->productSellerService->findByStoreAndProduct($storeId, $productId);

        return $this->productSellerService->update($sellerProduct, $dto->toArray(), $dto->images);
    }

    public function deleteProductSellerImage(int $imageId): bool
    {
        return $this->productSellerService->deleteImage($imageId);
    }

    public function detachProduct(int|Store $storeOrId, int|Product $productOrId): void
    {
        $storeId = $storeOrId instanceof Store ? $storeOrId->id : $storeOrId;
        $productId = $productOrId instanceof Product ? $productOrId->id : $productOrId;

        $this->productSellerService->detachByStore($storeId, $productId);
    }

    public function activate(int|Store $storeOrId, int|Product $productOrId): ProductSeller
    {
        $storeId = $storeOrId instanceof Store ? $storeOrId->id : $storeOrId;
        $productId = $productOrId instanceof Product ? $productOrId->id : $productOrId;
        $sellerProduct = $this->productSellerService->findByStoreAndProduct($storeId, $productId);

        return $this->productSellerService->activate($sellerProduct);
    }
}
