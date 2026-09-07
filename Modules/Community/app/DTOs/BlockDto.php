<?php

namespace Modules\Community\DTOs;

use Modules\Community\Http\Requests\BlockRequest;

readonly class BlockDto
{
    public function __construct(
        public int $blockerId,
        public int $blockedId,
    ) {}

    public static function fromRequest(BlockRequest $request): self
    {
        return new self(
            blockerId: $request->validated('blocker_id'),
            blockedId: $request->validated('blocked_id'),
        );
    }

    public function toArray(): array
    {
        return [
            'blocker_id' => $this->blockerId,
            'blocked_id' => $this->blockedId,
        ];
    }
}
