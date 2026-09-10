<?php

namespace Modules\Community\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $client = $this->relationLoaded('follower') ? $this->follower : $this->following;

        if (! $client) {
            return [];
        }

        return [
            'id' => $client->id,
            'name' => $client->name,
            'image' => $client->image,
            'followed_at' => $this->created_at?->format('Y-m-d h:i A'),
        ];
    }
}
