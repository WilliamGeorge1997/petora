<?php

namespace Modules\Clinic\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'address' => $this->address,
            'phone' => $this->phone,
            'image' => $this->image,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'services' => ClinicServiceResource::collection($this->whenLoaded('clinicServices')),
            'doctors' => $this->whenLoaded('doctors'),
            'categories' => $this->whenLoaded('categories'),
            'working_hours' => $this->whenLoaded('workingHours'),
        ];
    }
}
