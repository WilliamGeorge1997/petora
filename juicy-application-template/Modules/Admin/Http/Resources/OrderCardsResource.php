<?php

namespace Modules\Admin\Http\Resources;

use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Entities\OrderMethod;
use Modules\Order\Entities\PaymentMethod;

class OrderCardsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected $delivery_time_order;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $date = new DateTime($this->created_at);
        $this->delivery_time_order = $date;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'order_no' => $this->order_no,
            'payment_method_id' => @PaymentMethod::whereId($this->payment_method_id)->first()->id,
            'payment_method_title' => @PaymentMethod::whereId($this->payment_method_id)->first()->title,
            'order_method_id' => @OrderMethod::whereId($this->order_method_id)->first()->id,
            'order_method_title' => @OrderMethod::whereId($this->order_method_id)->first()->title,
            'delivery_time_order' => date_format($this->delivery_time_order, "h:i A"),
            'subtotal' => '+' . $this->subtotal,
            'tax' => '+' . $this->tax,
            'phone' => @$this->phone,
            // 'delivery_fee' => '+' . $this->delivery_fee,
            'discount' => '-' . $this->discount,
            'total' => $this->total,
            'notes' => $this->notes,
            // 'getdistance'   => $this->getdistance(),
            // 'clientName' => @$this->client->name,
            'client_phone' => @$this->phone,
            'OrderStatusVal' => $this->OrderStatusVal,
            'ButtonColor' => $this->ButtonColor,
            'order_status_id' => $this->order_status_id,
            'firstSubmitButton' => $this->firstSubmitButton,
            'secondSubmitButton' => $this->secondSubmitButton,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
            'OrderDetails' => $this->OrderDetails($this->details),
            'coupon_id' => $this->coupon_id,
            // Additional fields for contextual display
            'table_no' => $this->table_no,
            'car_no' => $this->car_no,
            'car_color' => $this->car_color,
            'parking_no' => $this->parking_no,
            'address_ar' => $this->getTranslation('address', 'ar') ?? null,
            'address_en' => $this->getTranslation('address', 'en') ?? null,

        ];
    }

    function OrderDetails($orderDetails)
    {
        $data = [];
        foreach ($orderDetails as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['title'] = $value['product']['title'];
            $data[$key]['quantity'] = $value['quantity'];
            $data[$key]['total'] = $value['total'];
            $data[$key]['note'] = $value['note'];
            $data[$key]['attributes'] = @$this->orderDetailAttributes($value['attributes']);
            $data[$key]['addons'] = @$this->orderDetailAddons($value['addons']);
            $data[$key]['sides'] = @$this->orderDetailSides($value['sides']);
        }
        return $data;
    }

    function orderDetailAttributes($attributes)
    {
        $groupped = [];
        foreach ($attributes as $attribute) {
            $attribute_id = $attribute['product_attribute_id'];
            if (!isset($groupped[$attribute_id])) {
                $groupped[$attribute_id] = [
                    'id' => $attribute_id,
                    'title' => $attribute['attributeValue']['attribute']['title'],
                    'override_price' => $attribute['attributeValue']['attribute']['override_price'],
                ];
            }
            $groupped[$attribute_id]['values'][] = [
                'id' => $attribute['product_attribute_value_id'],
                'title' => $attribute['attributeValue']['attribute_value'],
                'price' => $attribute['price'],
            ];
        }
        return array_values($groupped);
    }


    function orderDetailAddons($addons)
    {
        $groupped = [];
        foreach ($addons as $addon) {
            $addon_id = $addon['addon_id'];
            if (!isset($groupped[$addon_id])) {
                $groupped[$addon_id] = [
                    'id' => $addon_id,
                    'title' => $addon['addonValue']['addon']['title'],
                    'values' => [],
                ];
            }
            $groupped[$addon_id]['values'][] = [
                'id' => $addon['addon_value_id'],
                'title' => $addon['addonValue']['title'],
                'price' => $addon['price'],
            ];
        }
        return array_values($groupped);
    }

    function orderDetailSides($sides)
    {
        $groupped = [];
        foreach ($sides as $side) {
            $side_id = $side['side_id'];
            if (!isset($groupped[$side_id])) {
                $groupped[$side_id] = [
                    'id' => $side_id,
                    'title' => $side['sideValue']['side']['title'],
                    'values' => [],
                ];
            }
            $groupped[$side_id]['values'][] = [
                'id' => $side['side_value_id'],
                'title' => $side['sideValue']['title'],
                'price' => $side['price'],
            ];
        }
        return array_values($groupped);
    }
}
