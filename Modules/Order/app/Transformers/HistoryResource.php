<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Admin\Models\Admin;
use Modules\Client\Models\Client;
use Modules\Driver\Models\Driver;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'notes' => $this->notes,
            'created_by' => $this->whenHas('historible_type', function () {
                return match ($this->historible_type) {
                    Client::class => 'client',
                    Driver::class => 'driver',
                    Admin::class => 'admin',
                    default => null,
                };
            }),
            'created_at' => $this->created_at?->format('Y-m-d h:i A'),
            'status' => $this->whenLoaded('status', function () {
                return $this->status;
            }),
        ];
    }
}
