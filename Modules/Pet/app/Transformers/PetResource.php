<?php

namespace Modules\Pet\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'breed' => $this->breed,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth->format('Y-m-d'),
            'weight' => $this->weight,
            'image' => $this->image,
            'type' => [
                'title' => [
                    'en' => $this->type->getTranslation('title', 'en'),
                    'ar' => $this->type->getTranslation('title', 'ar'),
                ],
            ],
            'created_at' => $this->created_at->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at->format('Y-m-d h:i A'),
        ];
    }
}
