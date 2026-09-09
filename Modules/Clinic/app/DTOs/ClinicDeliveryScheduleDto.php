<?php

namespace Modules\Clinic\DTOs;

use Modules\Clinic\Http\Requests\ClinicDeliveryScheduleRequest;

readonly class ClinicDeliveryScheduleDto
{
    public function __construct(
        public string $day,
        public array $times,
    ) {}

    public static function fromRequest(ClinicDeliveryScheduleRequest $request): self
    {
        return new self(
            day: $request->validated('day'),
            times: $request->validated('times'),
        );
    }

    public function toArray(): array
    {
        return [
            'day' => $this->day,
        ];
    }
}
