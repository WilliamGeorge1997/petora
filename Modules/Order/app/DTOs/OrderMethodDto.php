<?php

namespace Modules\Order\DTOs;

use Modules\Order\Http\Requests\OrderMethodRequest;

readonly class OrderMethodDto
{
    public function __construct(
        public array $title,
        public ?bool $isActive = null,
    ) {}

    public static function fromRequest(OrderMethodRequest $request): self
    {
        return new self(
            title: [
                'en' => $request->validated('title_en'),
                'ar' => $request->validated('title_ar'),
            ],
            isActive: $request->boolean('is_active'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'title' => $this->title,
            'is_active' => $this->isActive,
        ];

        return $data;
    }
}
