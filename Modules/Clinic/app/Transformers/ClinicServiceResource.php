<?php

namespace Modules\Clinic\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            $this->mergeWhen($this->relationLoaded('service'), function () {
                return [
                    'title' => $this->service->title,
                    'description' => $this->service->description,
                    'image' => $this->service->image,
                ];
            }),
            'price' => $this->price,
            'duration' => $this->duration,
            'schedules' => $this->whenLoaded('schedules'),
            'created_at' => $this->created_at->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at->format('Y-m-d h:i A'),
        ];
    }
}
