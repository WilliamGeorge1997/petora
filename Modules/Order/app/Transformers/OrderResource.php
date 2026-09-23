<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_no' => $this->order_no,
            'subtotal' => $this->whenHas('subtotal', function () {
                return (float) $this->subtotal;
            }),
            'discount' => $this->whenHas('discount', function () {
                return (float) $this->discount;
            }),
            'discount_type' => $this->whenHas('discount_type', function () {
                return (int) $this->discount_type;
            }),
            'tax' => $this->whenHas('tax', function () {
                return (float) $this->tax;
            }),
            'delivery_fee' => $this->whenHas('delivery_fee', function () {
                return (float) $this->delivery_fee;
            }),
            'total' => $this->whenHas('total', function () {
                return (float) $this->total;
            }),
            'quantity' => $this->whenHas('quantity', function () {
                return (int) $this->quantity;
            }),
            'notes' => $this->whenHas('notes'),
            'delivery_date' => $this->whenHas('delivery_date', function () {
                return $this->delivery_date?->format('Y-m-d h:i A');
            }),
            'created_at' => $this->created_at?->format('Y-m-d h:i A'),
            'delivery_time_from' => $this->whenHas('delivery_time_from'),
            'delivery_time_to' => $this->whenHas('delivery_time_to'),
            'order_status' => $this->whenLoaded('orderStatus'),
            'payment_method' => $this->whenLoaded('paymentMethod'),
            'address' => $this->whenLoaded('address'),
            'coupon' => $this->whenLoaded('coupon'),
            'store' => $this->when($this->store_id, function () {
                return $this->whenLoaded('store');
            }),
            'clinic' => $this->when($this->clinic_id, function () {
                return $this->whenLoaded('clinic');
            }),
            'details' => OrderDetailResource::collection($this->whenLoaded('details')),
            'last_history' => new HistoryResource($this->whenLoaded('lastHistory')),
            'histories' => HistoryResource::collection($this->whenLoaded('histories')),
        ];
    }
}
