<?php

namespace Modules\Booking\DTOs;

use Modules\Booking\Http\Requests\BookingStatusRequest;

readonly class BookingStatusDto
{
    public function __construct(
        public array $title,
        public ?bool $isActive = null,
    ) {}

    public static function fromRequest(BookingStatusRequest $request): self
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
