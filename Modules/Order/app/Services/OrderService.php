<?php

namespace Modules\Order\Services;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Client\Models\Client;
use Modules\Coupon\Models\Coupon;
use Modules\Coupon\Services\CouponService;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderDetail;
use Modules\Order\Models\OrderHistory;
use Modules\Order\Services\OrderHistoryService;
use Modules\Product\Models\Product;
use Modules\Product\Services\ProductService;

class OrderService
{
    public function __construct(private CouponService $couponService) {}

    public function findAll(array $relations = [], array $data = []): LengthAwarePaginator|CursorPaginator|EloquentCollection
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

    public function save(OrderDto $dto): array
    {
        $data = $dto->toArray();

        $coupon = $this->couponService->checkCoupon($dto->coupon, $dto->clientId);
        if (!empty($coupon)) $data['coupon_id'] = $coupon->id;

        $items = $this->prepareOrderDetails($dto);
        $data = $this->calcOrderDetails($data, $items, $coupon);

        return DB::transaction(function () use ($data, $items) {
            $order = Order::create($data);
            $this->storeOrderDetails($order, $items);

            (new OrderHistoryService())->log($order, $data['order_status_id'], $data['client_id'], Client::class, $data['notes']);

            event(new \Modules\Order\Events\OrderCreated($order));

            return [$order->order_no];
        });
    }


    //Helpers=======================================
    private function prepareOrderDetails(OrderDto $dto): Collection
    {
        $sellerContext = $dto->getSellerContext();
        $products = (new ProductService())->checkProducts($dto->items, $sellerContext);

        $requestedItems = collect($dto->items)->keyBy('product_id');

        return $products->map(function ($product) use ($requestedItems, $sellerContext) {
            $requestedItem = $requestedItems[$product->id];
            return [
                'product_id' => $product->id,
                'quantity'   => $requestedItem['quantity'],
                'price'      => $product->{$sellerContext['relation']}->first()->pivot->price,
                'note'       => $requestedItem['note'] ?? null,
            ];
        });
    }


    private function calcOrderDetails(array $data, Collection $items, ?Coupon $coupon = null): array
    {
        $data['subtotal'] = $this->calcOrderSubTotal($items);
        $data['quantity'] = $this->calcOrderQuantity($items);
        $totals = $this->calcOrderTotal($data, $data['subtotal'], $coupon);

        return array_merge($data, $totals);
    }


    private function calcOrderSubTotal(Collection $items): float
    {
        return $items->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }


    private function calcOrderQuantity(Collection $items): int
    {
        return $items->sum('quantity');
    }

    private function calcOrderTotal(array $data, float $base_total, ?Coupon $coupon = null): array
    {
        $tax = $this->calcTax($base_total);
        $delivery_fee = $this->calcDeliveryFee($data, $base_total);
        $discountData = $this->calcDiscount($base_total, $delivery_fee, $coupon);

        $total = $base_total + $tax + $delivery_fee - $discountData['discount'];

        return [
            'delivery_fee'  => $delivery_fee,
            'tax'           => $tax,
            'discount'      => $discountData['discount'],
            'discount_type' => $discountData['discount_type'],
            'total'         => $total,
        ];
    }

    private function calcTax(float $base_total): float
    {
        $base_tax = (float) getSetting('tax'); //Percent
        return ($base_total * $base_tax) / 100;
    }

    private function calcDeliveryFee(array $data, $base_total)
    {
        $delivery_fee = $this->getSellerDeliveryFee($data);
        if (isset($data['delivery_date']) && Carbon::parse($data['delivery_date'])->isSameDay(Carbon::today())) {
            $delivery_fee += (float) getSetting('today_fee', 0);
        } else {
            $delivery_fee += (float) getSetting('next_day_fee', 0);
        }

        if ($base_total >= (float) getSetting('free_delivery_limit', PHP_INT_MAX))
            $delivery_fee = 0;

        return $delivery_fee;
    }

    private function calcDiscount(float $base_total, float $delivery_fee, ?Coupon $coupon = null): array
    {
        $discount = 0;
        $discount_type = null;

        if ($coupon) {
            $discount_type = 1; // DISCOUNT_WITH_COUPON
            switch ($coupon->discount_on) {
                case ('subtotal'):
                    $discount = $coupon->discount($base_total);
                    break;
                case ('delivery'):
                    $discount = $coupon->discount($delivery_fee);
                    break;
                case ('both'):
                default:
                    $discount = $coupon->discount($base_total + $delivery_fee);
            }
        } else {
            // Placeholder for points logic
        }

        return [
            'discount'      => $discount,
            'discount_type' => $discount_type,
        ];
    }



    public function storeOrderDetails(Order $order, Collection $details)
    {
        $now = now();
        $insertData = $details->map(function ($detail) use ($order, $now) {
            return [
                'order_id'   => $order->id,
                'product_id' => $detail['product_id'],
                'total'      => $detail['price'] * $detail['quantity'],
                'price'      => $detail['price'],
                'quantity'   => $detail['quantity'],
                'note'       => @$detail['note'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();

        OrderDetail::insert($insertData);
    }


    public function update($id, $data)
    {
        $Order = $this->findById($id);
        if (isset($data['order_status_id']) && $data['order_status_id'] == 5 && $Order['client_id'] ?? null) {
        }
        $Order->update($data);
        return $Order;
    }

    public function delete($id)
    {
        $Order = $this->findById($id);
        $Order->delete();
    }

    // public function updateOrderItemPrice(int $orderId, int $orderDetailId, float $price): OrderDetail
    // {
    //     $detail = OrderDetail::query()
    //         ->where('order_id', $orderId)
    //         ->findOrFail($orderDetailId);

    //     $detail->update([
    //         'price' => $price,
    //         'total' => $price * $detail->quantity,
    //     ]);

    //     $order = $this->findById($orderId);

    //     $items = $order->details->map(function ($item) {
    //         return [
    //             'product_id' => $item->product_id,
    //             'price'      => $item->price,
    //             'quantity'   => $item->quantity
    //         ];
    //     });

    //     $data = $order->toArray();
    //     $coupon = !empty($data['coupon_id']) ? Coupon::find($data['coupon_id']) : null;

    //     $data = $this->calcOrderDetails($data, $items, $coupon);

    //     $order->update($data);

    //     return $detail->fresh();
    // }

    // public function returnDiscount(Order $order)
    // {
    //     if ($order['discount_type'] == 1 && $order['coupon_id'] ?? null) {
    //     } else if ($order['discount_type'] == 2) {
    //     }
    // }
}
