<?php

namespace Modules\Community\DTOs;

use Modules\Community\Http\Requests\CommentRequest;

readonly class CommentDto
{
    public function __construct(
        public readonly  int $clientId,
        public readonly ?int $postId = null,
        public readonly ?int $parentId = null,
        public readonly string $content = '',
        public readonly bool $isActive = true,
    ) {}

    public static function fromRequest(CommentRequest $request): self
    {
        return new self(
            clientId: $request->validated('client_id'),
            postId: $request->validated('post_id'),
            parentId: $request->validated('parent_id'),
            content: $request->validated('content'),
            isActive: $request->boolean('is_active'),
        );
    }

    public function toArray(): array
    {
        return [
            'client_id' => $this->clientId,
            'post_id' => $this->postId,
            'parent_id' => $this->parentId,
            'content' => $this->content,
            'is_active' => $this->isActive,
        ];
    }
}
