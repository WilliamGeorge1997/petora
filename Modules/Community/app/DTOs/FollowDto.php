<?php

namespace Modules\Community\DTOs;

use Modules\Community\Http\Requests\FollowRequest;

readonly class FollowDto
{
    public function __construct(
        public int $followerId,
        public int $followingId,
    ) {}

    public static function fromRequest(FollowRequest $request): self
    {
        return new self(
            followerId: $request->validated('follower_id'),
            followingId: $request->validated('following_id'),
        );
    }

    public function toArray(): array
    {
        return [
            'follower_id' => $this->followerId,
            'following_id' => $this->followingId,
        ];
    }
}
