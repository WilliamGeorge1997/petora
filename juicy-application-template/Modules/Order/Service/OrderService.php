<?php

namespace Modules\Order\Service;

use Modules\Order\Entities\Order;
use Modules\Coupon\Entities\Coupon;
use Modules\Order\Entities\History;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\SideValue;
use Modules\Order\Entities\OrderDetails;
use Modules\Product\Entities\AddonValue;
use Modules\Product\Entities\ProductType;
use Modules\Branch\Entities\BranchProduct;
use Modules\Branch\Entities\DeliveryArea;
use Modules\Branch\Entities\DeliveryCharge;
use Modules\Order\Entities\OrderDetailsSide;
use Modules\Order\Entities\OrderDetailsAddon;
use Modules\Branch\Entities\BranchProductType;
use Modules\Product\Entities\ProductAttribute;
use Modules\Order\Entities\OrderDetailsAttribute;
use Modules\Product\Entities\ProductAttributeValue;
use Modules\Branch\Entities\BranchProductAddonValue;
use Modules\Branch\Entities\BranchProductAttributeValue;

class OrderService
{

    function findAll($data = [], $relation = [])
    {
        $orders = Order::query()
            ->available()
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
            ->available()
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

            if (!$branchProduct)
                throw new \Exception('Product not found in the branch');


            if (isset($detail['product_type_id']) && $detail['product_type_id']) {
                $productTypePrice = $this->getProductTypePrice($detail['product_type_id']);
                if ($productTypePrice) {
                    $product_type_id = $detail['product_type_id'];
                    $product_type_price = $productTypePrice;
                    $price = $productTypePrice;
                    $product_price = $this->getEffectivePrice($branchProduct);
                }

            } else {
                $price = $this->getEffectivePrice($branchProduct);
                $product_price = $price;
            }

            if (isset($detail['attribute_value_id'])) {
                $hasOverride = false;
                foreach ($detail['attribute_value_id'] as $attribute_value_id) {
                    if ($this->checkOverridePriceAttribute($attribute_value_id)) {
                        $hasOverride = true;
                        break;
                    }
                }

                if ($hasOverride) {
                    $price = ProductAttributeValue::whereIn('id', $detail['attribute_value_id'])
                        ->whereHas('attribute', function ($query) use ($detail) {
                            $query->where('product_id', $detail['product_id']);
                        })
                        ->sum('price');
                    $product_price = $price;
                } else {
                    $price += ProductAttributeValue::whereIn('id', $detail['attribute_value_id'])
                        ->whereHas('attribute', function ($query) use ($detail) {
                            $query->where('product_id', $detail['product_id']);
                        })
                        ->sum('price');
                }
            }

            if (isset($detail['addon_value_id'])) {
                $price += AddonValue::whereIn('id', $detail['addon_value_id'])
                    ->whereHas('addon', function ($query) use ($order) {
                        $query->where('branch_id', $order->branch_id);
                    })
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
            if (isset($detail['side_value_id']))
                $this->orderDetailsSides($order_details['id'], $detail, $order);
        }
    }

    function getProductPrice($product_id, $order)
    {
        $product = Product::where('id', $product_id)
            ->where('branch_id', $order->branch_id)
            ->active()
            ->first();
        return $product;
    }

    function getEffectivePrice($product)
    {
        if (!is_null($product->discounted_price) && $product->discounted_price != 0) {
            return $product->discounted_price;
        }
        return $product->price;
    }

    function getProductTypePrice($product_type_id)
    {
        $branchProductType = ProductType::where('product_type_id', $product_type_id)
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
        $branch = $order->branch;
        $branchSettings = $branch->settings;
        $base_total =  $order->details()->sum('total');
        $discount = 0;
        $delivery_fee = 0;
        $base_tax = $branchSettings->tax ?? 0;
        $tax = ($base_total * $base_tax) / 100;
        $order->tax = $tax;
        
        $service = 0;
        if ($order->order_method_id == \Modules\Order\Entities\OrderMethod::RECEIPT_FROM_BRANCH) {
            $base_service = $branchSettings->service ?? 0;
            $service = ($base_total * $base_service) / 100;
        }
        $order->service = $service;
        if ($order->order_method_id == \Modules\Order\Entities\OrderMethod::RECEIPT_IN_HOME) {
            $delivery_fee_method = $branch->delivery_fee_method;
            if ($delivery_fee_method != null) {
                if ($branch->lat && $branch->long && $order->lat && $order->long) {
                    $distance = $this->calcDistance($order->branch->lat, $order->branch->long, $order->lat, $order->long);
                    if ($delivery_fee_method == 'per_km') {
                        //cannot deliver more than 50 km as static value
                        if ($distance == 50) {
                            throw new \Exception('المسافة غير مسموح بها');
                        }
                        $delivery_fee = $branch->delivery_fee_per_km * $distance;
                    } else if ($delivery_fee_method == 'per_charge') {
                        $delivery_fee = $this->getDeliveryBranchFee($order->branch_id, $distance);
                    } else if ($delivery_fee_method == 'per_area') {
                        $delivery_area_id = request()->get('delivery_area_id');
                        $delivery_fee = $this->getDeliveryAreaBranchFee($order->branch_id, $delivery_area_id);
                    } else {
                        $delivery_fee = $branch->delivery_fee_fixed;
                    }
                }
            }
        }
        // }
        $order->delivery_fee = $delivery_fee;
        if ($order['coupon_id'] ?? null) {
            $coupon = Coupon::findOrFail($order['coupon_id']);
            $discount = $coupon->discount($base_total + $tax + $service + $delivery_fee);
            $order->discount_type = Order::DISCOUNT_WITH_COUPON;
        } else {
            // Check for branch order discounts
            if ($branchSettings && $branchSettings->is_order_discount_enabled) {
                $applicableDiscount = \Modules\Branch\Entities\BranchOrderDiscount::where('branch_id', $order->branch_id)
                    ->where('min_total', '<=', $base_total)
                    ->orderBy('min_total', 'desc')
                    ->first();
                    
                if ($applicableDiscount) {
                    $discount = $applicableDiscount->discount($base_total + $tax + $service + $delivery_fee);
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
        $total = $base_total + $tax + $service + $delivery_fee - $discount;
        return $total >= 0 ? $total : 0;
    }

    function orderDetailsAttributes($order_details_id, $detail, $order)
    {
        foreach ($detail['attribute_value_id'] as $value) {
            $productAttributeValue = ProductAttributeValue::where('id', $value)->first();
            OrderDetailsAttribute::create([
                'price' => $productAttributeValue['price'],
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
            OrderDetailsAddon::create([
                'price' => $addonValue['price'],
                'order_details_id' => $order_details_id,
                'product_id' => $detail['product_id'],
                'addon_id' => $addonValue['addon_id'],
                'addon_value_id' => $addonValue['id'],
            ]);
        }
    }

    function orderDetailsSides($order_details_id, $detail)
    {
        foreach ($detail['side_value_id'] as $value) {
            $sideValue = SideValue::where('id', $value)->first();
            OrderDetailsSide::create([
                'order_details_id' => $order_details_id,
                'product_id' => $detail['product_id'],
                'side_id' => $sideValue['side_id'],
                'side_value_id' => $sideValue['id'],
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

    function getDeliveryAreaBranchFee($branch_id, $delivery_area_id = null)
    {
        $branchDeliveryArea = DeliveryArea::whereBranchId($branch_id)
            ->where('id', $delivery_area_id)
            ->first();

        if ($branchDeliveryArea) {
            return $branchDeliveryArea->price;
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

    function generateZatcaQRCode($order, $settings)
    {
        if (!empty($settings['tax_number'])) {
            return zatca()
                ->sellerName($settings['name'])
                ->vatRegistrationNumber($settings['tax_number'])
                ->timestamp($order->created_at->toIso8601String())
                ->totalWithVat($order->total)
                ->vatTotal($order->tax)
                ->toQrCode(
                    qrCodeOptions()
                        ->format("svg")
                        // ->color(255, 0, 0, 1)
                        ->size(100)
                );
        } else {
            return null;
        }
    }
}
