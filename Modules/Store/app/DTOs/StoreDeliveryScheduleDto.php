<?php

namespace Modules\Store\DTOs;

use Modules\Store\Http\Requests\StoreDeliveryScheduleRequest;

readonly class StoreDeliveryScheduleDto
{
    public function __construct(
        public string $day,
        public array $times,
    ) {}

    public static function fromRequest(StoreDeliveryScheduleRequest $request): self
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
