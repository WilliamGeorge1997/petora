<?php

namespace Modules\Community\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryFeedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'is_me' => $this->is_me,
            'stories_count' => (int) $this->stories_count,
        ];
    }
}
