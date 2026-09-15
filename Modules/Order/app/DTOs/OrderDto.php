<?php

namespace Modules\Order\DTOs;

use Modules\Common\Helpers\SerialGenerator;
use Modules\Order\Enums\OrderStatus as OrderStatusEnum;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Models\Order;

readonly class OrderDto
{
    public function __construct(
        public int $clientId,
        public int $addressId,
        public int $paymentMethodId,
        public int $orderMethodId,
        public array $items,
        public string $deliveryDate,
        public ?string $coupon = null,
        public ?string $notes = null,
        public ?int $storeId = null,
        public ?int $clinicId = null,
        public ?int $storeDeliveryScheduleTimeId = null,
        public ?int $clinicDeliveryScheduleTimeId = null,
    ) {}

    public static function fromRequest(OrderRequest $request): self
    {
        return new self(
            clientId: auth('client')->id(),
            addressId: $request->validated('address_id'),
            paymentMethodId: $request->validated('payment_method_id'),
            orderMethodId: $request->validated('order_method_id'),
            deliveryDate: $request->validated('delivery_date'),
            items: $request->validated('items'),
            coupon: $request->validated('coupon'),
            notes: $request->validated('notes'),
            storeId: $request->validated('store_id'),
            clinicId: $request->validated('clinic_id'),
            storeDeliveryScheduleTimeId: $request->validated('store_delivery_schedule_time_id'),
            clinicDeliveryScheduleTimeId: $request->validated('clinic_delivery_schedule_time_id'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'client_id' => $this->clientId,
            'payment_method_id' => $this->paymentMethodId,
            'address_id' => $this->addressId,
            'order_method_id' => $this->orderMethodId,
            'items' => $this->items,
            'notes' => $this->notes,
            'store_id' => $this->storeId,
            'clinic_id' => $this->clinicId,
            'delivery_date' => $this->deliveryDate,
            'store_delivery_schedule_time_id' => $this->storeDeliveryScheduleTimeId,
            'clinic_delivery_schedule_time_id' => $this->clinicDeliveryScheduleTimeId,
        ];

        $data['order_no'] = SerialGenerator::generate(Order::class, 'ORD');
        $data['order_status_id'] = OrderStatusEnum::Sent->value;

        //Return only non falsy values
        return array_filter($data);
    }

    public function getSellerContext(): ?array
    {
        if ($this->storeId) {
            return ['relation' => 'stores', 'fk' => 'store_id', 'id' => $this->storeId];
        }
        if ($this->clinicId) {
            return ['relation' => 'clinics', 'fk' => 'clinic_id', 'id' => $this->clinicId];
        }
        return null;
    }
}
