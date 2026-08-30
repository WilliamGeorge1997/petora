<?php

namespace Modules\Country\DTOs;

use Modules\Country\Http\Requests\CityRequest;

readonly class CityDto
{
    public function __construct(
        public int $countryId,
        public array $title,
        public bool $isActive,
    ) {}

    public static function fromRequest(CityRequest $request): self
    {
        return new self(
            countryId: $request->input('country_id'),
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
            'country_id' => $this->countryId,
            'title' => $this->title,
            'is_active' => $this->isActive,
        ];
    }
}
