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
            'day_name' => __('store::general.days.'.$this->day),
            'times' => $this->times ? $this->times->map(fn ($time) => [
                'id' => $time->id,
                'from' => substr($time->from, 0, 5),
                'to' => substr($time->to, 0, 5),
            ]) : [],
        ];
    }
}
