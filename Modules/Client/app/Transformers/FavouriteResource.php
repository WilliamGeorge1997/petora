<?php

namespace Modules\Client\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;

class FavouriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'store' => $this->whenLoaded('store', fn () => [
                'id' => $this->store->id,
                'title' => $this->store->title,
                'image' => $this->store->image,
            ]),
            'clinic' => $this->whenLoaded('clinic', fn () => [
                'id' => $this->clinic->id,
                'title' => $this->clinic->title,
                'image' => $this->clinic->image,
            ]),
            'created_at' => $this->created_at?->format('Y-m-d H:i A'),
        ];
    }
}
