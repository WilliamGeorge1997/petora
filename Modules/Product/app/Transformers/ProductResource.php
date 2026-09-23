<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $pivot = $this->resolvePivot();

        return [
            'id' => $this->id,
            'title' => $this->whenHas('title', function () use ($pivot) {
                return $pivot?->title ?: $this->title;
            }),
            'description' => $this->whenHas('description', function () use ($pivot) {
                return $pivot?->description ?: $this->description;
            }),
            'price' => $this->whenHas('price', function () use ($pivot) {
                return (float) ($pivot?->price ?? $this->price);
            }),
            'is_active' => $this->whenHas('is_active', function () use ($pivot) {
                return (bool) ($pivot?->is_active ?? $this->is_active);
            }),
            'is_favourited' => $this->whenHas('is_favourited', function () {
                return (bool) $this->is_favourited;
            }),
            'image' => $this->resolveImage($pivot),
            'images' => $this->resolveImages($pivot),
        ];
    }

    private function resolveImage(mixed $pivot): mixed
    {
        if ($pivot?->relationLoaded('firstImage') && $pivot->firstImage) {
            return $pivot->firstImage;
        }

        return $this->whenLoaded('firstImage');
    }

    private function resolvePivot(): mixed
    {
        if ($this->pivot) {
            return $this->pivot;
        }
        if ($this->relationLoaded('stores') && $this->stores->isNotEmpty()) {
            return $this->stores->first()->pivot;
        }
        if ($this->relationLoaded('clinics') && $this->clinics->isNotEmpty()) {
            return $this->clinics->first()->pivot;
        }

        return null;
    }

    private function resolveImages(mixed $pivot): mixed
    {
        if ($pivot?->relationLoaded('images') && $pivot->images->isNotEmpty()) {
            return $pivot->images;
        }

        return $this->whenLoaded('images');
    }
}
