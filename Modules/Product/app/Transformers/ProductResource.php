<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {

        if ($this->relationLoaded('stores') && $this->stores->isNotEmpty()) {
            $overridePrice = $this->stores->first()->pivot->price;
            $overrideIsActive = $this->stores->first()->pivot->is_active;
        } elseif ($this->relationLoaded('clinics') && $this->clinics->isNotEmpty()) {
            $overridePrice = $this->clinics->first()->pivot->price;
            $overrideIsActive = $this->clinics->first()->pivot->is_active;
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $overridePrice,
            'is_active' => $overrideIsActive,
            'images' => $this->images,
        ];
    }
}
