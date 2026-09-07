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
            'price' => $this->price,
            'duration' => $this->duration,
            'title' => $this->whenLoaded('service', function () {
                return $this->service->title;
            }),
            'schedules' => $this->whenLoaded('schedules'),
        ];
    }
}
