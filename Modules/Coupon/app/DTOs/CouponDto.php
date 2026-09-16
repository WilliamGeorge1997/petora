<?php

namespace Modules\Coupon\DTOs;

use Modules\Coupon\Http\Requests\CouponRequest;

readonly class CouponDto
{
    public function __construct(
        public string $code,
        public string $type,
        public float $value,
        public string $discountOn,
        public ?bool $isActive = null,
        public ?int $numOfUses = null,
        public ?float $limit = null,
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public ?string $timeFrom = null,
        public ?string $timeTo = null,
        public ?int $clientUses = null,
    ) {}

    public static function fromRequest(CouponRequest $request): self
    {
        return new self(
            code: $request->validated('code'),
            type: $request->validated('type'),
            value: $request->validated('value'),
            discountOn: $request->validated('discount_on'),
            isActive: $request->boolean('is_active'),
            numOfUses: $request->validated('num_of_uses'),
            limit: $request->validated('limit'),
            dateFrom: $request->validated('date_from'),
            dateTo: $request->validated('date_to'),
            timeFrom: $request->validated('time_from'),
            timeTo: $request->validated('time_to'),
            clientUses: $request->validated('client_uses'),
        );
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'is_active' => $this->isActive,
            'num_of_uses' => $this->numOfUses,
            'type' => $this->type,
            'value' => $this->value,
            'limit' => $this->limit,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'time_from' => $this->timeFrom,
            'time_to' => $this->timeTo,
            'client_uses' => $this->clientUses,
            'discount_on' => $this->discountOn,
        ];
    }
}
