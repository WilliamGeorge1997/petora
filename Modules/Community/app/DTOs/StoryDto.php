<?php

namespace Modules\Community\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Community\Http\Requests\StoryRequest;

readonly class StoryDto
{
    public function __construct(
        public int $clientId,
        public UploadedFile|string|null $media,
        public bool $isVideo,
        public ?string $expiresAt,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoryRequest $request): self
    {
        return new self(
            clientId: $request->validated('client_id'),
            media: $request->file('media') ?? $request->validated('media'),
            isVideo: $request->boolean('is_video', false),
            expiresAt: $request->validated('expires_at'),
            isActive: $request->boolean('is_active', true),
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->clientId,
            'is_video' => $this->isVideo,
            'expires_at' => $this->expiresAt,
            'is_active' => $this->isActive,
        ];
    }
}
