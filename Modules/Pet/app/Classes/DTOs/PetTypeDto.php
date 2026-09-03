<?php

namespace Modules\Pet\Classes\DTOs;

use Modules\Pet\Http\Requests\PetTypeRequest;

class PetTypeDto
{
    public function __construct(
        public array $title,
        public bool $isActive,
    ) {}

    public static function fromRequest(PetTypeRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->validated('title_en'),
                'ar' => $request->validated('title_ar'),
            ],
            isActive: $request->boolean('is_active'),
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'is_active' => $this->isActive,
        ];
    }
}
