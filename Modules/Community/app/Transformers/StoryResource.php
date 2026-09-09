<?php

namespace Modules\Community\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'media' => $this->media,
            'is_video' => (bool) $this->is_video,
            'expires_at' => $this->expires_at?->format('Y-m-d h:i A'),
            'is_active' => (bool) $this->is_active,
            'likes_count' => $this->whenCounted('likes', $this->likes_count, $this->likes_count ?? 0),
            'is_liked' => (bool) ($this->is_liked ?? false),
            'client' => $this->whenLoaded('client', function () {
                return $this->client ? [
                    'id' => $this->client->id,
                    'name' => $this->client->name,
                    'phone' => $this->client->phone,
                    'email' => $this->client->email,
                    'image' => $this->client->image,
                ] : null;
            }),
            'created_at' => $this->created_at?->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at?->format('Y-m-d h:i A'),
        ];
    }
}
