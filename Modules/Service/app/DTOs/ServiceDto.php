<?php

namespace Modules\Service\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Service\Http\Requests\ServiceRequest;

readonly class ServiceDto
{
    public function __construct(
        public array $title,
        public bool $isActive,
        public float $price = 0,
        public ?int $duration = null,
        public ?array $description = null,
        public ?UploadedFile $image = null,
    ) {}

    public static function fromRequest(ServiceRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->validated('title_en'),
                'ar' => $request->validated('title_ar'),
            ],
            isActive: $request->boolean('is_active'),
            price: (float) $request->validated('price', 0),
            duration: $request->validated('duration'),
            description: ($request->validated('description_en') || $request->validated('description_ar')) ? [
                'en' => $request->validated('description_en'),
                'ar' => $request->validated('description_ar'),
            ] : null,
            image: $request->hasFile('image') ? $request->file('image') : null,
        );
    }

    public function toArray(): array
    {
        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'duration' => $this->duration,
            'is_active' => $this->isActive,
            'image' => $this->image,
        ];

        if (is_null($this->image)) {
            unset($data['image']);
        }

        return $data;
    }
}
