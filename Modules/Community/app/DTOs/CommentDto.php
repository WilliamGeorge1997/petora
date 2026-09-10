<?php

namespace Modules\Community\DTOs;

use Modules\Community\Http\Requests\CommentRequest;

readonly class CommentDto
{
    public function __construct(
        public readonly int $clientId,
        public readonly string $content = '',
        public readonly ?bool $isActive = null,
    ) {}

    public static function fromRequest(CommentRequest $request): self
    {
        return new self(
            clientId: auth('admin')->check() ? $request->validated('client_id') : auth('client')->id(),
            content: $request->validated('content'),
            isActive: auth('admin')->check() ? $request->boolean('is_active') : null,
        );
    }

    public function toArray(): array
    {
        $data = [
            'client_id' => $this->clientId,
            'content' => $this->content,
            'is_active' => $this->isActive,
        ];

        if (is_null($this->isActive)) {
            unset($data['is_active']);
        }

        return $data;
    }
}
