<?php

namespace Modules\Country\DTOs;

use Modules\Country\Http\Requests\CountryRequest;

readonly class CountryDto
{
    public function __construct(
        public array $title,
        public bool $isActive,
    ) {}

    public static function fromRequest(CountryRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->input('title_en'),
                'ar' => $request->input('title_ar'),
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
