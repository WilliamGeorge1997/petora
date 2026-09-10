<?php

namespace Modules\Service\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Service\Http\Requests\ServiceRequest;

readonly class ServiceDto
{
    public function __construct(
        public array $title,
        public ?bool $isActive = null,
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
            description: ($request->validated('description_en') || $request->validated('description_ar')) ? [
                'en' => $request->validated('description_en'),
                'ar' => $request->validated('description_ar'),
            ] : null,
            price: (float) $request->validated('price'),
            duration: $request->validated('duration'),
            image: $request->file('image'),
            isActive: $request->boolean('is_active'),
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
