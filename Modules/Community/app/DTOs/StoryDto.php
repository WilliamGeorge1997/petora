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
        public ?bool $isActive = null,
    ) {}

    public static function fromRequest(StoryRequest $request): self
    {
        $media = $request->file('media') ?? $request->validated('media');
        $isVideo = isVideo($media);

        return new self(
            clientId: $request->validated('client_id'),
            media: $media,
            isVideo: $isVideo,
            expiresAt: now()->addHours(24)->toDateTimeString(),
            isActive: auth('admin')->check() ? $request->boolean('is_active') : null,
        );
    }

    public function toArray(): array
    {
        $data = [
            'client_id' => $this->clientId,
            'is_video' => $this->isVideo,
            'expires_at' => $this->expiresAt,
            'is_active' => $this->isActive,
        ];

        if (is_null($this->isActive)) {
            unset($data['is_active']);
        }

        return $data;
    }
}
