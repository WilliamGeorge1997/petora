<?php

namespace Modules\Clinic\DTOs;

use Modules\Clinic\Http\Requests\ClinicProductRequest;

readonly class ClinicProductDto
{
    public function __construct(
        public float $price,
        public ?array $title = null,
        public ?array $description = null,
        public ?bool $isActive = null,
        public ?array $images = null,
    ) {}

    public static function fromRequest(ClinicProductRequest $request): self
    {
        return new self(
            price: (float) $request->validated('price'),
            title: ($request->validated('title_en') || $request->validated('title_ar')) ? [
                'en' => $request->validated('title_en'),
                'ar' => $request->validated('title_ar'),
            ] : null,
            description: ($request->validated('description_en') || $request->validated('description_ar')) ? [
                'en' => $request->validated('description_en'),
                'ar' => $request->validated('description_ar'),
            ] : null,
            isActive: $request->boolean('is_active'),
            images: $request->file('images'),
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'is_active' => $this->isActive,
        ];
    }
}
