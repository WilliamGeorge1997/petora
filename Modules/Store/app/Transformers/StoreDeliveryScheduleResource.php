<?php

namespace Modules\Store\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreDeliveryScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'day' => $this->day,
            'title' => __('common::general.days.'.$this->day),
            'times' => $this->times ? $this->times->map(fn ($time) => [
                'id' => $time->id,
                'from' => $time->from,
                'to' => $time->to,
            ]) : [],
        ];
    }
}
