<?php

namespace Modules\Product\DTOs;

use Modules\Product\Http\Requests\ProductRequest;

readonly class ProductDto
{
    public function __construct(
        public int $categoryId,
        public array $title,
        public float $price,
        public ?bool $isActive = null,
        public ?array $description = null,
        public ?array $images = null,
    ) {}

    public static function fromRequest(ProductRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->validated('title_en'),
                'ar' => $request->validated('title_ar'),
            ],
            description: ($request->validated('description_en') || $request->validated('description_ar')) ? [
                'en' => $request->validated('description_en'),
                'ar' => $request->validated('description_ar'),
            ] : null,
            categoryId: $request->validated('category_id'),
            price: $request->validated('price'),
            images: $request->file('images'),
            isActive: $request->boolean('is_active'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->categoryId,
            'price' => $this->price,
            'is_active' => $this->isActive,
        ];

        return $data;
    }
}
