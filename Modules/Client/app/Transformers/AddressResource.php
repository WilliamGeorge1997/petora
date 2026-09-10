<?php

namespace Modules\Client\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'block' => $this->block,
            'street' => $this->street,
            'house_number' => $this->house_number,
            'notes' => $this->notes,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_default' => $this->is_default,
            'created_at' => $this->created_at->format('Y-m-d H:i A'),
            'country' => $this->whenLoaded('country', fn() => [
                'id' => $this->country->id,
                'title' => $this->country->title,
            ]),
            'city' => $this->whenLoaded('city', fn() => [
                'id' => $this->city->id,
                'title' => $this->city->title,
            ]),
            'zone' => $this->whenLoaded('zone', fn() => [
                'id' => $this->zone->id,
                'title' => $this->zone->title,
            ]),
        ];
    }
}
