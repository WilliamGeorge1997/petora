<?php

namespace Modules\Product\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Product\Http\Requests\ProductRequest;

readonly class ProductDto
{
    public function __construct(
        public int $categoryId,
        public array $title,
        public float $price,
        public bool $isActive,
        public ?array $description = null,
        public ?array $images = null,
    ) {}

    public static function fromRequest(ProductRequest $request): self
    {
        return new self(
            categoryId: $request->validated('category_id'),
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
            images: $request->hasFile('images') ? $request->file('images') : null,
        );
    }

    public function toArray(): array
    {
        return [
            'category_id' => $this->categoryId,
            'title'       => $this->title,
            'description' => $this->description,
            'price'       => $this->price,
            'is_active'   => $this->isActive,
        ];
    }
}

