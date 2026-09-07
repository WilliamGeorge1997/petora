<?php

namespace Modules\Community\DTOs;

use Modules\Community\Http\Requests\HashtagRequest;

readonly class HashtagDto
{
    public function __construct(
        public string $text,
    ) {}

    public static function fromRequest(HashtagRequest $request): self
    {
        return new self(
            text: $request->validated('text'),
        );
    }

    public function toArray(): array
    {
        return [
            'text' => $this->text,
        ];
    }
}
