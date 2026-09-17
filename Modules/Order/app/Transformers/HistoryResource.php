<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'status'     => $this->status?->title,
            'notes'      => $this->notes,
            'created_at' => $this->created_at?->format('Y-m-d h:i A'),
        ];
    }
}
