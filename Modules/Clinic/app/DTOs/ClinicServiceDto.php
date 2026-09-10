<?php

namespace Modules\Clinic\DTOs;

use Modules\Clinic\Http\Requests\ClinicServiceUpdateRequest;

readonly class ClinicServiceDto
{
    public function __construct(
        public float $price,
        public ?int $duration = null,
        public ?bool $isActive = null,
    ) {}

    public static function fromRequest(ClinicServiceUpdateRequest $request): self
    {
        return new self(
            price: (float) $request->validated('price'),
            duration: $request->validated('duration'),
            isActive: $request->boolean('is_active'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'price' => $this->price,
            'duration' => $this->duration,
            'is_active' => $this->isActive,
        ];

        if (is_null($this->duration)) {
            unset($data['duration']);
        }

        return $data;
    }
}
