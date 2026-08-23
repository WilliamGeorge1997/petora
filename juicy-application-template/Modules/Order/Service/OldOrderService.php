<?php

namespace Modules\Order\Service;

use Modules\Order\Entities\Order;
use Modules\Coupon\Entities\Coupon;
use Modules\Order\Entities\History;
use Modules\Order\Entities\OrderDetails;
use Modules\Product\Entities\AddonValue;
use Modules\Branch\Entities\BranchProduct;
use Modules\Branch\Entities\DeliveryCharge;
use Modules\Order\Entities\OrderDetailsAddon;
use Modules\Product\Entities\ProductAttribute;
use Modules\Order\Entities\OrderDetailsAttribute;
use Modules\Product\Entities\ProductAttributeValue;
use Modules\Branch\Entities\BranchProductAddonValue;
use Modules\Branch\Entities\BranchProductAttributeValue;
use Modules\Branch\Entities\BranchProductType;

class OldOrderService
{

    function findAll($data = [], $relation = [])
    {
        $orders = Order::query()
            ->filter($data)
            ->with($relation)->orderByDesc('id');
        return getCaseCollection($orders, $data);
    }

    function findById($id, $relation = [])
    {
        return Order::with($relation)->findOrFail($id);
    }

    function findBy($key, $value, $data = [], $relation = [])
    {
        $orders = Order::query()
            ->filter($data)
            ->with($relation)->orderByDesc('id')->where($key, $value);
        return getCaseCollection($orders, $data);
    }

    function save($data)
    {
        $order =  Order::create($data);
        $this->storeOrderDetails($order, $data['details']);
        $order = $this->calcOrderDetails($order, @$data['points_discount']);
        return $order;
    }

    function update($id, $data)
    {
        $Order = $this->findById($id);
        $Order->update($data);
        return $Order;
    }

    function delete($id)
    {
        $Order = $this->findById($id);
        $Order->delete();
    }

    function storeOrderDetails($order, $details)
    {
        foreach ($details as $detail) {
            $branchProduct = $this->getProductPrice($detail['product_id'], $order);
            $price = 0;
            $product_price = 0;
            $product_type_id = null;
            $product_type_price = null;

            if (isset($detail['product_type_id']) && $detail['product_type_id']) {
                $productTypePrice = $this->getProductTypePrice($detail['product_type_id'], $order);
                if ($productTypePrice) {
                    $product_type_id = $detail['product_type_id'];
                    $product_type_price = $productTypePrice;
                    $price = $productTypePrice;
                    $product_price = $branchProduct->price;
                }
            } else {
                $price = $branchProduct->price;
                $product_price = $branchProduct->price;
            }

            if (isset($detail['attribute_value_id'])) {
                if ($this->checkOverridePriceAttribute($detail['attribute_value_id'])) {
                    $price = BranchProductAttributeValue::where('branch_id', $order->branch_id)
                        ->whereIn('product_attribute_value_id', $detail['attribute_value_id'])
                        ->where('product_id', $detail['product_id'])
                        ->sum('price');
                    $product_price = $price;
                } else {
                    $price += BranchProductAttributeValue::where('branch_id', $order->branch_id)
                        ->whereIn('product_attribute_value_id', $detail['attribute_value_id'])
                        ->where('product_id', $detail['product_id'])
                        ->sum('price');
                }
            }

            if (isset($detail['addon_value_id'])) {
                $price += BranchProductAddonValue::where('branch_id', $order->branch_id)
                    ->whereIn('addon_value_id', $detail['addon_value_id'])
                    ->where('product_id', $detail['product_id'])
                    ->sum('price');
            }

            $order_details = OrderDetails::create([
                'order_id' => $order->id,
                'product_id' => $detail['product_id'],
                'product_type_id' => @$product_type_id,
                'product_type_price' => @$product_type_price,
                'total' => $price * $detail['quantity'],
                'price' => $price,
                'quantity' => $detail['quantity'],
                'product_price' => $product_price,
                'note' => isset($detail['note']) ? $detail['note'] : null
            ]);

            if (isset($detail['attribute_value_id']))
                $this->orderDetailsAttributes($order_details['id'], $detail, $order);
            if (isset($detail['addon_value_id']))
                $this->orderDetailsAddons($order_details['id'], $detail, $order);
        }
    }

    function getProductPrice($product_id, $order)
    {
        $product = BranchProduct::where('product_id', $product_id)
            ->where('branch_id', $order->branch_id)
            ->active()
            ->first();
        return $product;
    }

    function getProductTypePrice($product_type_id, $order)
    {
        $branchProductType = BranchProductType::where('product_type_id', $product_type_id)
            ->where('branch_id', $order->branch_id)
            ->active()
            ->first();

        return $branchProductType ? $branchProductType->price : null;
    }

    function checkOverridePriceAttribute($value)
    {
        $attribute = ProductAttribute::where('id', ProductAttributeValue::where('id', $value)->first()['product_attribute_id'])->first();
        if ($attribute['override_price']) {
            return true;
        }
        return false;
    }

    function calcOrderDetails($order, $points_discount = false)
    {

        $data = [
            'subtotal' => $this->calcOrderSubTotal($order),
            'total' => $this->calcOrderTotal($order, $points_discount),
            'quantity' => $this->calcOrderQuantity($order)
        ];

        $order->update($data);
        return $order->fresh();
    }

    function calcOrderSubTotal($order)
    {
        return $order->details()->sum('total');
    }
    function calcOrderQuantity($order)
    {
        return $order->details()->sum('quantity');
    }

    function calcOrderTotal($order, $points_discount)
    {
        $base_total =  $order->details()->sum('total');
        $discount = 0;
        $delivery_fee = 0;
        $base_tax = getSetting('tax');
        $tax = ($base_total * $base_tax) / 100;
        $order->tax = $tax;
        // if ($order->address_id) {
        //     // if delivery home
        //     $branch = $order->branch;
        //     $client_address = $order->address;
        //     $distance = $this->calcDistance($branch->lat, $branch->long, $client_address->lat, $client_address->long);
        //     $delivery_fee = $this->getDeliveryBranchFee($order->branch_id, $distance);
        // }
        $order->delivery_fee = $delivery_fee;
        if ($order['coupon_id'] ?? null) {
            $coupon = Coupon::findOrFail($order['coupon_id']);
            $discount = $coupon->discount($base_total + $tax + $delivery_fee);
            $order->discount_type = Order::DISCOUNT_WITH_COUPON;
        } else {
            // Check for branch order discounts
            $branch = $order->branch;
            $branchSettings = $branch->settings;
            if ($branchSettings && $branchSettings->is_order_discount_enabled) {
                $applicableDiscount = \Modules\Branch\Entities\BranchOrderDiscount::where('branch_id', $order->branch_id)
                    ->where('min_total', '<=', $base_total)
                    ->orderBy('min_total', 'desc')
                    ->first();
                    
                if ($applicableDiscount) {
                    $discount = $applicableDiscount->discount($base_total + $tax + $delivery_fee);
                    $order->discount_type = Order::DISCOUNT_WITH_BRANCH;
                }
            }
            
            // if client select points discount
            // if ($points_discount) {
            //     // check if client has balance in his account
            //     $client = (new ClientService())->findById($order->client_id);
            //     if ($client['balance'] > 0) {
            //         $order->discount_type = Order::DISCOUNT_WITH_POINTS;
            //         $discount = min($client['balance'], ($base_total + $tax + $delivery_fee));
            //         $client->update(['balance' => $client['balance'] - $discount]);
            //     }
            // }
        }
        $order->discount = $discount;

        $order->save();
        $total = $base_total + $tax + $delivery_fee - $discount;
        return $total >= 0 ? $total : 0;
    }

    function orderDetailsAttributes($order_details_id, $detail, $order)
    {
        foreach ($detail['attribute_value_id'] as $value) {
            $productAttributeValue = ProductAttributeValue::where('id', $value)->first();
            $branchProductAttributeValue = BranchProductAttributeValue::where('branch_id', $order->branch_id)
                ->where('product_id', $detail['product_id'])
                ->where('product_attribute_value_id', $value)
                ->first();
            OrderDetailsAttribute::create([
                'price' => $branchProductAttributeValue['price'],
                'order_details_id' => $order_details_id,
                'product_id' => $detail['product_id'],
                'product_attribute_id' => $productAttributeValue['product_attribute_id'],
                'product_attribute_value_id' => $productAttributeValue['id'],
            ]);
        }
    }
    function orderDetailsAddons($order_details_id, $detail, $order)
    {
        foreach ($detail['addon_value_id'] as $value) {
            $addonValue = AddonValue::where('id', $value)->first();
            $branchProductAddonValue = BranchProductAddonValue::where('branch_id', $order->branch_id)
                ->where('product_id', $detail['product_id'])
                ->where('addon_value_id', $value)
                ->first();
            OrderDetailsAddon::create([
                'price' => $branchProductAddonValue['price'],
                'order_details_id' => $order_details_id,
                'product_id' => $detail['product_id'],
                'addon_id' => $addonValue['addon_id'],
                'addon_value_id' => $addonValue['id'],
            ]);
        }
    }

    function calcDistance($lat1, $lon1, $lat2, $lon2)
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
        //echo ' '.$km;
        return (int)round($km);
    }

    function getDeliveryBranchFee($branch_id, $distance)
    {
        $branchDeliveryCharges = DeliveryCharge::whereBranchId($branch_id)->where('distance', '>=', $distance)->orderBy('id', 'ASC')->first();
        if ($branchDeliveryCharges) {
            $delivery_fee = $branchDeliveryCharges->price;
            return $delivery_fee;
        }
        return 0;
    }

    function history($order_id)
    {
        return History::with('status')->whereOrderId($order_id)->get();
    }

    function historyStatusIds($order_id)
    {
        return History::whereOrderId($order_id)->pluck('order_status_id');
    }




    // function createOrUpdateOrderDetails($data){
    //     $parent_order = $this->findById($data['order_id']);
    //     if ($data['details'] ?? null){
    //         foreach ($data['details'] as $detail){
    //             $notesData =[];
    //             $price = $this->getProductPrice($detail['product_id'],$parent_order);
    //             if($detail['id'] ?? null){
    //                 // if edits in current order details
    //                 $order_details = $this->updateCurrentOrderDetails($detail,$price,$data);
    //                 continue;
    //             }
    //             $order_details = $this->getExactOrderDetails($data['order_id'],$detail);
    //             if(count($order_details->toArray()) > 0){
    //                 // if order details is exists and identical and changes only in quantity
    //                 $total_quantity = $detail['quantity'] + $order_details['quantity'];
    //                 $order_details->update([
    //                     'quantity' => $total_quantity,'total' => $total_quantity * $price
    //                 ]);

    //             }else{
    //                 // if there is no order details with this conditions
    //                 $order_details = OrderDetails::create([
    //                     'order_id' => $data['order_id'],'product_id'=> $detail['product_id'],
    //                     'quantity' => $detail['quantity'],'total' => $detail['quantity'] * $price,
    //                     'employee_id' =>$data['employee_id'],'completed'=>0,
    //                     'note' =>isset($detail['note'])?$detail['note']:null
    //                     ]);
    //             }

    //         }
    //     }

    //     $order = $this->findById($data['order_id']);
    //     $this->calcOrderDetails($order);
    //     return $order->fresh();
    // }

    // function getProductPrice($product_id,$order)
    // {
    //     $product = Product::find($product_id);
    //             if ($product['new_price'] ==0)$price=$product['price'];
    //             else $price = $product['new_price'];
    //     return $price;
    // }

    // function getExactOrderDetails($order_id,$detail)
    // {
    //     $order_details = OrderDetails::where('order_id',$order_id)->where('product_id',$detail['product_id'])
    //             ->when(isset($detail['note']),function($query) use($detail){
    //                 $query->where('note',$detail['note']);
    //             })
    //             ->get();
    //             if(isset($detail['notes'])){
    //                 // if new data sent has notes
    //                 foreach ($order_details as $key => $order_detail) {
    //                     // loop on each order details to the same order
    //                     // check if order details in database has same notes or not
    //                 if((count(array_diff($order_detail->notes->pluck('id')->toArray(), isset($detail['notes']) ? $detail['notes']:[])) == 0) && (count(array_diff(isset($detail['notes']) ? $detail['notes']:[],$order_detail->notes->pluck('id')->toArray())) == 0)){
    //                     // if has same notes so it will break and get out from for loop
    //                     $order_details = $order_detail;
    //                     break;
    //                 }else{
    //                     // if has different notes , delete order details from array and continue for loop
    //                     unset($order_details[$key]);
    //                     continue;
    //                 }
    //                 }
    //             }else{
    //                 // if new data has not new notes as array
    //             $order_details = OrderDetails::NotCancelled()->where('order_id',$order_id)->where('product_id',$detail['product_id'])
    //             ->when(isset($detail['note']),function($query) use($detail){
    //                 $query->where('note',$detail['note']);
    //             })
    //             ->doesnthave('notes')
    //             ->get();
    //             }
    //             return $order_details;
    // }

    // function updateCurrentOrderDetails($detail,$price,$data)
    // {
    //     $order_details = OrderDetails::NotCancelled()->whereId($detail['id'])->first();
    //                 $order_details->update([
    //                     'quantity' => $detail['quantity'],'total' => $detail['quantity'] * $price,
    //                     'employee_id' =>$data['employee_id'],'completed'=>0,
    //                     'note' =>isset($detail['note'])?$detail['note']:null,
    //                     'completed'=>0
    //                 ]);

    //                 return $order_details;

    // }

    // function deleteOrderDetails($order_details_id){

    //     $order_details = OrderDetails::findOrFail($order_details_id);
    //     $order = $this->findById($order_details['order_id']);
    //     // $order_details->delete();
    //     $order_details->update([
    //         'cancelled' => 1
    //     ]);
    //     if($order->details()->NotCancelled()->exists()){
    //         $this->calcOrderDetails($order);
    //     }else{
    //         $order->delete();
    //     }

    // }




}
