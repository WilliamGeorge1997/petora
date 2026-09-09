<?php

namespace Modules\Community\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at?->format('Y-m-d h:i A'),
            'likes_count' => $this->whenCounted('likes'),
            'is_liked' => (bool) ($this->is_liked ?? false),
            'comments_count' => $this->whenCounted('comments'),
            'client' => $this->whenLoaded('client', function () {
                return $this->client ? [
                    'id' => $this->client->id,
                    'name' => $this->client->name,
                    'phone' => $this->client->phone,
                    'email' => $this->client->email,
                    'image' => $this->client->image,
                ] : null;
            }),
            'media' => PostMediaResource::collection($this->whenLoaded('media')),
            'hashtags' => $this->whenLoaded('hashtags', function () {
                return $this->hashtags->pluck('text');
            }),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
