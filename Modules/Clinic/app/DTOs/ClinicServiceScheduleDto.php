<?php

namespace Modules\Clinic\DTOs;

use Modules\Clinic\Http\Requests\ClinicServiceScheduleRequest;

readonly class ClinicServiceScheduleDto
{
    public function __construct(
        public int $clinicServiceId,
        public string $day,
        public array $times,
    ) {}

    public static function fromRequest(ClinicServiceScheduleRequest $request): self
    {
        return new self(
            clinicServiceId: (int) $request->route('clinic_service')->id,
            day: $request->validated('day'),
            times: $request->validated('times'),
        );
    }

    public function toArray(): array
    {
        return [
            'clinic_service_id' => $this->clinicServiceId,
            'day'               => $this->day,
        ];
    }
}
