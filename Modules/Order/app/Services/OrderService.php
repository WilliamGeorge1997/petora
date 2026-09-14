<?php

namespace Modules\Order\Services;

use Carbon\Carbon;
use Modules\Order\Models\Order;
use Illuminate\Support\Facades\DB;
use Modules\Country\Models\Zone;
// use Modules\Store\Models\Store;
use Modules\Client\Models\Client;
use Modules\Coupon\Models\Coupon;
use Modules\Order\Models\OrderHistory;
use Illuminate\Support\Facades\Auth;
use Modules\Order\DTOs\OrderDto;
use Modules\Country\Models\SubZone;
use Modules\Product\Models\Product;
// use Modules\Client\Services\PointService;
use Modules\Order\Models\OrderDetail;
// use Modules\Client\Services\ClientService;
use Modules\Common\Helper\UploaderHelper;
// use Modules\Coupon\Services\CouponService;
// use Modules\Product\Services\ProductService;
// use Modules\Notification\Services\NotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;

class OrderService
{
    // use UploaderHelper;

    public function findAll(array $relations = [], array $data = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $orders = Order::query()
            ->when($data['store_id'] ?? null, function ($q) use ($data) {
                $q->where('store_id', $data['store_id']);
            })
            ->when($data['clinic_id'] ?? null, function ($q) use ($data) {
                $q->where('clinic_id', $data['clinic_id']);
            })
            ->when($data['order_status_id'] ?? null, function ($q) use ($data) {
                $q->where('order_status_id', $data['order_status_id']);
            })
            ->when($data['client_id'] ?? null, function ($q) use ($data) {
                $q->where('client_id', $data['client_id']);
            })
            ->when($data['order_no'] ?? null, function ($q) use ($data) {
                $q->where('order_no', $data['order_no']);
            })
            ->with($relations)
            ->orderByDesc('id');
            
        return getCaseCollection($orders, $data);
    }

    public function findById($id, array $relations = [])
    {
        return Order::with($relations)->findOrFail($id);
    }

    public function DriverOrders($driver_id, $order_Status_id = null, $paginate = 15, array $relations = [])
    {
        return Order::with($relations)->where('driver_id', $driver_id)
            ->when($order_Status_id ?? null, function ($q) use ($order_Status_id) {
                return $q->where('order_status_id', $order_Status_id);
            })
            ->orderByDesc('created_at')
            ->paginate($paginate);
    }

    public function findBy($key, $value, array $relations = [], $paginate = null)
    {
        if ($paginate ?? null) {
            return Order::withCount('rate')->latest()->with($relations)->where($key, $value)->when(request('status') ?? null, function ($q) {
                return $q->whereIn('order_status_id', request('status'));
            })->paginate($paginate);
        }
        return Order::withCount('rate')->latest()->with($relations)->where($key, $value)->when(request('status') ?? null, function ($q) {
            return $q->whereIn('order_status_id', request('status'));
        })->get();
    }

    public function save(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $response = [];
            $items = collect($data['items']);
            
            // Group by store_id and clinic_id instead of branch_id
            $groupedBySeller = $items->groupBy(function($item) {
                $storeId = $item['store_id'] ?? 'null';
                $clinicId = $item['clinic_id'] ?? 'null';
                return $storeId . '-' . $clinicId;
            })->toArray();
            
            foreach ($groupedBySeller as $key => $group) {
                $data['order_no'] = $this->generateSerial();
                
                // Extract store and clinic from group
                $firstItem = $group[0];
                $data['store_id'] = $firstItem['store_id'] ?? null;
                $data['clinic_id'] = $firstItem['clinic_id'] ?? null;
                
                $order = Order::create($data);
                $response[] = $order->order_no;
                
                $this->storeOrderDetails($order, $group);
                $this->calcOrderDetails($order, @$data['points_discount'], count($groupedBySeller));
                
                $data['order_id'] = $order->id;
                
                $order->histories()->create([
                    'order_status_id' => 1,
                    'notes' => 'Order created',
                    'historible_id' => $data['client_id'],
                    'historible_type' => 'Modules\Client\Models\Client',
                ]);
                event(new \Modules\Order\Events\OrderCreated($order));
            }

            return $response;
        });
    }

    public function prepareOrderDetails($data)
    {
        // $items = (new ProductService())->checkProduct($data)['data']['data'];
        $included_items = [];
        $collection = collect($data['items']);
        
        // This simulates retrieving valid products. You might need to adjust ProductService logic
        // based on how your new ProductService validates products.
        // For now, assuming you can query products directly or use ProductService.
        $productIds = $collection->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->with(['stores' => function($q) use ($data) {
            // Include store relationship to get price/stock if needed
            // $q->where('store_id', $data['items'][0]['store_id'] ?? null);
        }])->get();

        foreach ($products as $product) {
            $index = $collection->search(function ($one_item) use ($product) {
                return $one_item['product_id'] == $product->id;
            });
            
            if ($index !== false) {
                // Determine price (you can adjust this if price comes from store pivot)
                $data['items'][$index]['price'] = $product->price ?? 0;
                $included_items[] = $data['items'][$index];
            }
        }

        return $included_items;
    }

    private function generateSerial()
    {
        $serial = 'PH-';
        $today_orders_count = Order::whereDate('created_at', Carbon::today())->count() + 1;
        $serial .= 100 - date("y");
        $serial .= 100 - date("m");
        $serial .= 100 - date("d");
        $serial .= str_pad((string)$today_orders_count, 4, '0', STR_PAD_LEFT);
        return $serial;
    }

    public function update($id, $data)
    {
        $Order = $this->findById($id);
        if (isset($data['order_status_id']) && $data['order_status_id'] == 5 && $Order['client_id'] ?? null) {
            // if order delivered successfully so now add points
            // $checkSavedPoints = (new PointService())->findBy('order_id', $id);
            // if ($checkSavedPoints->count() < 1) { ... }
        }
        $Order->update($data);
        return $Order;
    }

    public function delete($id)
    {
        $Order = $this->findById($id);
        $Order->delete();
    }

    public function updateOrderItemPrice(int $orderId, int $orderDetailId, float $price): OrderDetail
    {
        $detail = OrderDetail::query()
            ->where('order_id', $orderId)
            ->findOrFail($orderDetailId);

        $detail->update([
            'price' => $price,
            'total' => $price * $detail->quantity,
        ]);

        $order = $this->findById($orderId);
        $this->calcOrderDetails($order, 0);

        return $detail->fresh();
    }

    public function returnDiscount($order)
    {
        if ($order['discount_type'] == 1 && $order['coupon_id'] ?? null) {
            // $coupon = (new CouponService())->findById($order['coupon_id']);
            // $coupon->counter--;
            // $coupon->save();
        } else if ($order['discount_type'] == 2) {
            // $client = (new ClientService())->findById($order['client_id']);
            // $client->balance += $order['discount'];
            // $client->save();
        }
    }

    public function storeOrderDetails($order, $details)
    {
        foreach ($details as $detail) {
            $order_details = OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $detail['product_id'],
                'total' => $detail['price'] * $detail['quantity'],
                'price' => $detail['price'],
                'quantity' => $detail['quantity'],
                'note' => isset($detail['note']) ? $detail['note'] : null
            ]);
        }
    }

    public function calcOrderDetails($order, $points_discount, $orders_count = 1)
    {
        $data = [
            'subtotal' => $this->calcOrderSubTotal($order),
            'total' => $this->calcOrderTotal($order, $points_discount, $orders_count),
            'quantity' => $this->calcOrderQuantity($order)
        ];

        $order->update($data);
        return $order->fresh();
    }

    public function calcOrderSubTotal($order): mixed
    {
        return $order->details()->sum('total');
    }
    
    public function calcOrderQuantity($order)
    {
        return $order->details()->sum('quantity');
    }

    public function calcOrderTotal($order, $points_discount, $orders_count = 1)
    {
        $base_total = $order->details()->sum('total');
        $discount = 0;

        $delivery_fee = $this->calcDeliveryFee($order, $base_total);
        $order->delivery_fee = $delivery_fee;

        if ($order['coupon_id'] ?? null) {
            $coupon = Coupon::findOrFail($order['coupon_id']);
            switch ($coupon->discount_on) {
                case ('subtotal'):
                    $discount = $coupon->discount($base_total);
                    if ($coupon->type == 1) // 1 for FIXED
                        $discount = round($discount / $orders_count, 2);
                    break;

                case ('delivery'):
                    $discount = $coupon->discount($delivery_fee);
                    if ($coupon->type == 1)
                        $discount = round($discount / $orders_count, 2);
                    break;

                case ('both'):
                default:
                    $discount = $coupon->discount($base_total + $delivery_fee);
                    if ($coupon->type == 1)
                        $discount = round($discount / $orders_count, 2);
            }
            $order->discount_type = 1; // DISCOUNT_WITH_COUPON
        } else {
            if ($points_discount) {
                // $client = (new ClientService())->findById($order->client_id);
                // if ($client['balance'] > 0) {
                //     $order->discount_type = 2; // DISCOUNT_WITH_POINTS
                //     $discount = min($client['balance'], ($base_total + $delivery_fee));
                //     $client->update(['balance' => $client['balance'] - $discount]);
                // }
            }
        }
        $order->discount = $discount;

        $base_tax = (float) getSetting('tax', 0);
        $tax = (($base_total) * $base_tax) / 100;
        $order->tax = $tax;

        $order->save();
        $total = $base_total + $tax + $delivery_fee - $discount;
        return $total >= 0 ? $total : 0;
    }

    public function calcDeliveryFee($order, $base_total)
    {
        $delivery_fee = $this->getSellerDeliveryFee($order);
        if (Carbon::parse($order->delivery_date)->isSameDay(Carbon::today())) {
            $delivery_fee += (float) getSetting('today_fee', 0);
        } else {
            $delivery_fee += (float) getSetting('next_day_fee', 0);
        }

        if ($base_total >= (float) getSetting('free_delivery_limit', PHP_INT_MAX))
            $delivery_fee = 0;

        return $delivery_fee;
    }

    public function getSellerDeliveryFee($order)
    {
        // Old logic was checking branch->sub_zone_id.
        // Currently returning 0 to be implemented with new zone/distance logic.
        return 0;
    }

    public function history($order_id)
    {
        return OrderHistory::with('status')->where('order_id', $order_id)->get();
    }

    public function historyStatusIds($order_id)
    {
        return OrderHistory::where('order_id', $order_id)->pluck('order_status_id');
    }
    
    public function historyStatus($order_id)
    {
        return OrderHistory::with('status:id,title')->select('id', 'order_status_id', 'created_at')->where('order_id', $order_id)->get();
    }

    public function ordersChart($from, $to)
    {
        $counts = [];
        $dates = [];
        $period = Carbon::parse($from)->daysUntil(Carbon::parse($to));

        foreach ($period as $date) {
            $format = $date->format('d/m');
            $dates[] = $format;
            $counts[] = Order::query()->whereDate('created_at', $date)->count();
        }
        return ['dates' => $dates, 'counts' => $counts];
    }

    public function returnQuantityToSeller($order)
    {
        // $order->details->each(function ($detail) use ($order) { ... }
    }
}
