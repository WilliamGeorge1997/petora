<?php

namespace Modules\Community\DTOs;

use Modules\Community\Http\Requests\PostRequest;

readonly class PostDto
{
    public function __construct(
        public int $clientId,
        public bool $isActive,
        public ?int $petId = null,
        public ?string $content = null,
        public ?array $images = null,
    ) {}

    public static function fromRequest(PostRequest $request): self
    {
        return new self(
            clientId: $request->validated('client_id'),
            isActive: $request->boolean('is_active'),
            petId: $request->validated('pet_id'),
            content: $request->validated('content'),
            images: $request->file('images'),
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->clientId,
            'pet_id' => $this->petId,
            'content' => $this->content,
            'is_active' => $this->isActive,
        ];
    }
}
