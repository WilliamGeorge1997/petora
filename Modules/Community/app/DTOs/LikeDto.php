<?php

namespace Modules\Community\DTOs;

use Modules\Community\Http\Requests\LikeRequest;

readonly class LikeDto
{
    public function __construct(
        public int $clientId,
        public int $likeableId,
        public string $likeableType,
    ) {}

    public static function fromRequest(LikeRequest $request): self
    {
        return new self(
            clientId: $request->validated('client_id'),
            likeableId: $request->validated('likeable_id'),
            likeableType: $request->validated('likeable_type'),
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->clientId,
            'likeable_id' => $this->likeableId,
            'likeable_type' => $this->likeableType,
        ];
    }
}
