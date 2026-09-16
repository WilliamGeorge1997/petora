<?php

namespace Modules\Order\DTOs;

use Modules\Order\Http\Requests\OrderUpdateRequest;

readonly class OrderUpdateDto
{
    public function __construct(
        public int $orderStatusId,
        public ?int $driverId = null,
        public ?string $notes = null,
    ) {}

    public static function fromRequest(OrderUpdateRequest $request): self
    {
        return new self(
            orderStatusId: $request->validated('order_status_id'),
            driverId: $request->validated('driver_id'),
            notes: $request->validated('notes'),
        );
    }
}
