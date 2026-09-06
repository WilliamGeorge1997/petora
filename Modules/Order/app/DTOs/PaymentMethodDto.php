<?php

namespace Modules\Order\DTOs;

use Modules\Order\Http\Requests\PaymentMethodRequest;

readonly class PaymentMethodDto
{
    public function __construct(
        public array $title,
        public bool $isActive,
    ) {}

    public static function fromRequest(PaymentMethodRequest $request): self
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
        return [
            'title' => $this->title,
            'is_active' => $this->isActive,
        ];
    }
}