<?php

namespace Modules\Country\DTOs;

use Modules\Country\Http\Requests\ZoneRequest;

readonly class ZoneDto
{
    public function __construct(
        public int $cityId,
        public array $title,
        public ?bool $isActive = null,
    ) {}

    public static function fromRequest(ZoneRequest $request): self
    {
        return new self(
            cityId: $request->input('city_id'),
            title: [
                'en' => $request->input('title_en'),
                'ar' => $request->input('title_ar'),
            ],
            isActive: $request->has('is_active') ? $request->boolean('is_active') : null,
        );
    }

    public function toArray(): array
    {
        $data = [
            'city_id' => $this->cityId,
            'title' => $this->title,
            'is_active' => $this->isActive,
        ];

        return $data;
    }
}
