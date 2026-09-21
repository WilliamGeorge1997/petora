<?php

namespace Modules\Clinic\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Clinic\DTOs\ClinicProductDto;
use Modules\Clinic\Models\Clinic;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductSeller;
use Modules\Product\Services\ProductSellerService;
use Modules\Product\Services\ProductService;

class ClinicProductService
{
    public function __construct(
        private ClinicService $clinicService,
        private ProductSellerService $productSellerService,
    ) {}

    public function products(int|Clinic $clinicOrId, array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $clinic = $this->clinicService->findById($clinicOrId instanceof Clinic ? $clinicOrId->id : $clinicOrId);
        $query = $clinic->products()->with($relations)->filter($data)->latest('products.id');

        return getCaseCollection($query, $data);
    }

    public function importAllProducts(int|Clinic $clinicOrId): void
    {
        $clinic = $this->clinicService->findById($clinicOrId instanceof Clinic ? $clinicOrId->id : $clinicOrId);
        $activeProducts = app(ProductService::class)->active();
        $syncData = [];

        foreach ($activeProducts as $product) {
            $syncData[$product->id] = [
                'title' => ['en' => $product->getTranslation('title', 'en'), 'ar' => $product->getTranslation('title', 'ar')],
                'description' => ['en' => $product->getTranslation('description', 'en'), 'ar' => $product->getTranslation('description', 'ar')],
                'price' => (float) $product->price,
                'is_active' => true,
            ];
        }

        if (! empty($syncData)) {
            $clinic->products()->syncWithoutDetaching($syncData);
        }
    }

    public function findProductSeller(int|Clinic $clinicOrId, int $productId, array $relations = []): ProductSeller
    {
        $clinicId = $clinicOrId instanceof Clinic ? $clinicOrId->id : $clinicOrId;

        return $this->productSellerService->findByClinicAndProduct($clinicId, $productId, $relations);
    }

    public function updateProductSeller(int|Clinic $clinicOrId, int $productId, ClinicProductDto $dto): ProductSeller
    {
        $clinicId = $clinicOrId instanceof Clinic ? $clinicOrId->id : $clinicOrId;
        $sellerProduct = $this->productSellerService->findByClinicAndProduct($clinicId, $productId);

        return $this->productSellerService->update($sellerProduct, $dto->toArray(), $dto->images);
    }

    public function deleteProductSellerImage(int $imageId): bool
    {
        return $this->productSellerService->deleteImage($imageId);
    }

    public function detachProduct(int|Clinic $clinicOrId, int|Product $productOrId): void
    {
        $clinicId = $clinicOrId instanceof Clinic ? $clinicOrId->id : $clinicOrId;
        $productId = $productOrId instanceof Product ? $productOrId->id : $productOrId;

        $this->productSellerService->detachByClinic($clinicId, $productId);
    }

    public function activate(int|Clinic $clinicOrId, int|Product $productOrId): ProductSeller
    {
        $clinicId = $clinicOrId instanceof Clinic ? $clinicOrId->id : $clinicOrId;
        $productId = $productOrId instanceof Product ? $productOrId->id : $productOrId;
        $sellerProduct = $this->productSellerService->findByClinicAndProduct($clinicId, $productId);

        return $this->productSellerService->activate($sellerProduct);
    }
}
