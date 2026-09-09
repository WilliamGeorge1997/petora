<?php

namespace Modules\Community\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'likes_count' => $this->whenCounted('likes', $this->likes_count, $this->likes_count ?? 0),
            'is_liked' => (bool) ($this->is_liked ?? false),
            'replies_count' => $this->whenCounted('replies'),
            'created_at' => $this->created_at?->format('Y-m-d h:i A'),
            'client' => $this->whenLoaded('client', function () {
                return $this->client ? [
                    'id' => $this->client->id,
                    'name' => $this->client->name,
                    'phone' => $this->client->phone,
                    'email' => $this->client->email,
                    'image' => $this->client->image,
                ] : null;
            }),
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
