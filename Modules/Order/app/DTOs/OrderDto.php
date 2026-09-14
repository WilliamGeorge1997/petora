<?php

namespace Modules\Order\DTOs;

use Illuminate\Http\UploadedFile;
use Modules\Order\Http\Requests\OrderRequest;

readonly class OrderDto
{
    public function __construct(
        public int $clientId,
        public ?int $paymentMethodId = null,
        public ?int $addressId = null,
        public ?string $coupon = null,
        public ?int $orderMethodId = null,
        public ?array $items = null,
        public ?string $notes = null,
        public ?bool $pointsDiscount = null,
    ) {}

    public static function fromRequest(OrderRequest $request): self
    {
        return new self(
            clientId: auth('client')->id(),
            paymentMethodId: $request->validated('payment_method_id'),
            addressId: $request->validated('address_id'),
            coupon: $request->validated('coupon'),
            orderMethodId: $request->validated('order_method_id'),
            items: $request->validated('items'),
            notes: $request->validated('notes'),
            pointsDiscount: $request->boolean('points_discount'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'client_id' => $this->clientId,
            'payment_method_id' => $this->paymentMethodId,
            'address_id' => $this->addressId,
            'coupon' => $this->coupon,
            'order_method_id' => $this->orderMethodId,
            'items' => $this->items,
            'notes' => $this->notes,
            'points_discount' => $this->pointsDiscount,
        ];

        return array_filter($data, function($value) {
            return !is_null($value) && $value !== '';
        });
    }

    public function dataFromRequest(): array
    {
        $data = $this->toArray();
        $data['link_code'] = $this->generateLinkCode();
        $data['order_status_id'] = 1;
        // Delivery Date is no longer handled by 'today' or 'tomorrow' string.
        // It will be handled natively or added later.

        return $data;
    }

    private function generateLinkCode(): string
    {
        $serial = 'PH-';
        $today = date("Ymd");
        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
        return $serial . $today . $rand;
    }
}
