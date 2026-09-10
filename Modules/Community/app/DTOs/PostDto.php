<?php

namespace Modules\Community\DTOs;

use Modules\Community\Http\Requests\PostRequest;

readonly class PostDto
{
    public function __construct(
        public int $clientId,
        public ?bool $isActive = null,
        public ?int $petId = null,
        public ?string $content = null,
        public ?array $media = null,
        public ?array $hashtags = null,
    ) {}

    public static function fromRequest(PostRequest $request): self
    {
        return new self(
            clientId: $request->validated('client_id'),
            petId: $request->validated('pet_id'),
            content: $request->validated('content'),
            media: $request->validated('media'),
            hashtags: $request->validated('hashtags'),
            isActive: auth('admin')->check() ? $request->boolean('is_active') : null,
        );
    }

    public function toArray(): array
    {
        $data = [
            'client_id' => $this->clientId,
            'pet_id' => $this->petId,
            'content' => $this->content,
            'is_active' => $this->isActive,
        ];

        if (is_null($this->isActive)) {
            unset($data['is_active']);
        }

        return $data;
    }
}
