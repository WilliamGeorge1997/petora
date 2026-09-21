<?php

namespace Modules\Order\DTOs;

use Carbon\Carbon;
use Modules\Order\Enums\OrderStatus as OrderStatusEnum;
use Modules\Order\Enums\SellerType;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Models\Order;

readonly class OrderDto
{
    public function __construct(
        public int     $clientId,
        public int     $addressId,
        public int     $paymentMethodId,
        public int     $orderMethodId,
        public array   $items,
        public string  $date,
        public ?string $coupon = null,
        public ?string $notes = null,
        public ?int    $storeId = null,
        public ?int    $clinicId = null,
        public ?int    $storeSlotId = null,
        public ?int    $clinicSlotId = null,
    ) {
    }

    public static function fromRequest(OrderRequest $request): self
    {
        return new self(
            clientId:           auth('client')->id(),
            addressId:          $request->validated('address_id'),
            paymentMethodId:    $request->validated('payment_method_id'),
            orderMethodId:      $request->validated('order_method_id'),
            items:              $request->validated('items'),
            date:               Carbon::parse($request->validated('delivery_date'))->format('Y-m-d'),
            coupon:             $request->validated('coupon'),
            notes:              $request->validated('notes'),
            storeId:            $request->validated('store_id'),
            clinicId:           $request->validated('clinic_id'),
            storeSlotId:        $request->validated('store_delivery_schedule_time_id'),
            clinicSlotId:       $request->validated('clinic_delivery_schedule_time_id'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'client_id'                        => $this->clientId,
            'payment_method_id'                => $this->paymentMethodId,
            'address_id'                       => $this->addressId,
            'order_method_id'                  => $this->orderMethodId,
            'items'                            => $this->items,
            'notes'                            => $this->notes,
            'store_id'                         => $this->storeId,
            'clinic_id'                        => $this->clinicId,
            'delivery_date'                    => $this->date,
            'store_delivery_schedule_time_id'  => $this->storeSlotId,
            'clinic_delivery_schedule_time_id' => $this->clinicSlotId,
        ];

        $data['order_no'] = generateSerial(Order::class, 'ORD');
        $data['order_status_id'] = OrderStatusEnum::Sent->value;

        //Return only not null values
        return array_filter($data, fn($value) => !is_null($value));
    }

    public function resolveSeller(): ?array
    {
        if ($this->storeId) {
            return [
                'type'   => SellerType::Store,
                'id'     => $this->storeId,
                'slotId' => $this->storeSlotId,
            ];
        }
        if ($this->clinicId) {
            return [
                'type'   => SellerType::Clinic,
                'id'     => $this->clinicId,
                'slotId' => $this->clinicSlotId,
            ];
        }
        return null;
    }
}
