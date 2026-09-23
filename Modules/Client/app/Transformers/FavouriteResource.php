<?php

namespace Modules\Client\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;

class FavouriteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $sellerProduct = $this->relationLoaded('sellerProduct') ? $this->sellerProduct : null;
        
        if ($sellerProduct && $sellerProduct->relationLoaded('product') && $sellerProduct->product) {
            $sellerProduct->product->setRelation('pivot', $sellerProduct);
        }

        return array_merge(
            [
                'id' => $this->id,
                'product' => ($sellerProduct && $sellerProduct->relationLoaded('product') && $sellerProduct->product) 
                    ? new ProductResource($sellerProduct->product) 
                    : null,
            ],
            $this->resolveSeller($sellerProduct),
            [
                'created_at' => $this->created_at?->format('Y-m-d H:i A'),
            ]
        );
    }

    private function resolveSeller(mixed $sellerProduct): array
    {
        if ($sellerProduct && $sellerProduct->relationLoaded('store') && $sellerProduct->store) {
            return ['store' =>
            [
                'id' => $sellerProduct->store->id,
                'title' => $sellerProduct->store->title,
                'image' => $sellerProduct->store->image
            ]];
        }

        if ($sellerProduct && $sellerProduct->relationLoaded('clinic') && $sellerProduct->clinic) {
            return ['clinic' => [
                'id' => $sellerProduct->clinic->id,
                'title' => $sellerProduct->clinic->title,
                'image' => $sellerProduct->clinic->image
            ]];
        }

        return [];
    }
}
