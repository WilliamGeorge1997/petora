<?php

namespace Modules\Clinic\DTOs;

use Modules\Clinic\Http\Requests\ClinicServiceScheduleRequest;

readonly class ClinicServiceScheduleDto
{
    public function __construct(
        public string $day,
        public array $times,
    ) {}

    public static function fromRequest(ClinicServiceScheduleRequest $request): self
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
