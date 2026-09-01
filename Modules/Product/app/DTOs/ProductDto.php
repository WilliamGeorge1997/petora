<?php

namespace Modules\Product\DTOs;

use Modules\Product\Http\Requests\ProductRequest;

readonly class ProductDto
{
    public function __construct(
        public array $title,
        public float $price,
        public bool $isActive,
        public ?array $description = null,
    ) {}

    public static function fromRequest(ProductRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->validated('title_en'),
                'ar' => $request->validated('title_ar'),
            ],
            price: $request->validated('price'),
            isActive: $request->boolean('is_active'),
            description: ($request->validated('description_en') || $request->validated('description_ar')) ? [
                'en' => $request->validated('description_en'),
                'ar' => $request->validated('description_ar'),
            ] : null,
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
