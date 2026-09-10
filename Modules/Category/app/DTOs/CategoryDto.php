<?php

namespace Modules\Category\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Category\Http\Requests\CategoryRequest;

readonly class CategoryDto
{
    public function __construct(
        public array $title,
        public ?bool $isActive = null,
        public ?UploadedFile $image = null,
    ) {}

    public static function fromRequest(CategoryRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->validated('title_en'),
                'ar' => $request->validated('title_ar'),
            ],
            isActive: $request->boolean('is_active'),
            image: $request->file('image'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'title' => $this->title,
            'is_active' => $this->isActive,
            'image' => $this->image,
        ];

        if (is_null($this->image)) {
            unset($data['image']);
        }

        return $data;
    }
}
