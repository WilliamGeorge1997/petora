<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $product = $this->whenLoaded('product');

        if ($product && $this->relationLoaded('sellerProduct') && $this->sellerProduct) {
            $product->setRelation('pivot', $this->sellerProduct);
        }

        return [
            'id' => $this->whenHas('id'),
            'price' => $this->whenHas('price', function () {
                return (float) $this->price;
            }),
            'quantity' => $this->whenHas('quantity', function () {
                return (int) $this->quantity;
            }),
            'total' => $this->whenHas('total', function () {
                return (float) $this->total;
            }),
            'note' => $this->whenHas('note'),
            'product' => $this->whenLoaded('product', function () {
                return new ProductResource($this->product);
            }),
        ];
    }
}
