<?php

namespace Modules\Country\DTOs;

use Modules\Country\Http\Requests\ZoneRequest;

readonly class ZoneDto
{
    public function __construct(
        public int $cityId,
        public array $title,
        public bool $isActive,
    ) {}

    public static function fromRequest(ZoneRequest $request): self
    {
        return new self(
            cityId: $request->input('city_id'),
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
            'city_id' => $this->cityId,
            'title' => $this->title,
            'is_active' => $this->isActive,
        ];
    }
}
