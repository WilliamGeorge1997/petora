<?php

namespace Modules\Order\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Client\Models\Client;
use Modules\Client\Services\AddressService;
use Modules\Country\Services\ZoneService;
use Modules\Coupon\Enums\CouponDiscountOn;
use Modules\Coupon\Models\Coupon;
use Modules\Coupon\Services\CouponService;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\Enums\DiscountType;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Events\OrderStatusChanged;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderDetail;
use Modules\Order\Services\OrderHistoryService;
use Modules\Product\Services\ProductService;

class OrderService
{
    private $model = Order::class;
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

    public function findById(int $id, array $relations = [])
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Order $orderOrId): Order
    {
        return $orderOrId instanceof Order ? $orderOrId : $this->findById($orderOrId);
    }

    function driverOrders(int $driverId, ?int $orderStatusId = null, array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|EloquentCollection
    {
        $query = $this->model::with($relations)->whereDriverId($driverId)
            ->when($orderStatusId ?? null, function ($q) use ($orderStatusId) {
                return $q->whereOrderStatusId($orderStatusId);
            })
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function driverStatistics(int $driverId, string $fromDate, string $toDate): array
    {
        $startDate = Carbon::parse($fromDate)->startOfDay();
        $endDate = Carbon::parse($toDate)->endOfDay();

        $stats = $this->model::query()
            ->where('driver_id', $driverId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("
                COUNT(CASE WHEN order_status_id = ? THEN 1 END) as delivered_orders,
                COUNT(CASE WHEN order_status_id = ? THEN 1 END) as refused_orders,
                COUNT(CASE WHEN order_status_id = ? THEN 1 END) as failed_orders
            ", [
                OrderStatus::Done->value,
                OrderStatus::RefusedByDriver->value,
                OrderStatus::Fail->value,
            ])
            ->first();

        return [
            'DeliveredOrders' => (int) ($stats->delivered_orders ?? 0),
            'RefusedOrders'   => (int) ($stats->refused_orders ?? 0),
            'FailedOrders'    => (int) ($stats->failed_orders ?? 0),
        ];
    }

    public function track(Order $order): array
    {
        $statusIds = $order->histories->pluck('order_status_id')->values()->all();
        $lastStatus = $order->orderStatus ?? $order->histories->last()?->status;

        return [
            'order_id'          => $order->id,
            'duration_time'     => $order->duration_time ?? ($order->delivery_time_from && $order->delivery_time_to ? "{$order->delivery_time_from} - {$order->delivery_time_to}" : null),
            'status_ids'        => $statusIds,
            'statuses'          => $order->histories,
            'last_Status_title' => $lastStatus?->getTranslations('title') ?? [],
        ];
    }

    public function historyStatusIds(int|Order $orderOrId): array
    {
        $order = $this->resolveModel($orderOrId);
        return $order->histories()->pluck('order_status_id')->values()->all();
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
        $seller = $dto->resolveSeller();

        $coupon = $this->couponService->checkCoupon($dto->coupon, $dto->clientId);
        if (!empty($coupon)) $data['coupon_id'] = $coupon->id;

        $items = $this->prepareOrderDetails($dto, $seller);

        $orderDetails = $this->calcOrderDetails($items, $coupon, $seller, $dto->addressId);
        $data = array_merge($data, $orderDetails);

        return DB::transaction(function () use ($data, $items) {

            $order = Order::create($data);
            $this->storeOrderDetails($order, $items);

            app(OrderHistoryService::class)->save(
                $order,
                $data['order_status_id'],
                $data['client_id'],
                Client::class,
                $data['notes']
            );

            event(new OrderCreated($order));

            return ["order_no" =>  $order->order_no];
        });
    }

    public function update(int|Order $orderOrId, OrderDto $dto)
    {
        $data = $dto->toArray();
        $order = $this->resolveModel($orderOrId);
        $order->update($data);
        return $order;
    }

    public function delete(int|Order $orderOrId)
    {
        $order = $this->resolveModel($orderOrId);
        $order->delete();
    }

    public function changeStatusTo(int|Order $orderOrId, OrderStatus $newStatus, Model $actor, ?string $notes = null, array $extraAttributes = []): Order
    {
        $order = $this->resolveModel($orderOrId);

        return DB::transaction(function () use ($order, $newStatus, $actor, $notes) {
            $previousStatus = $order->order_status_id;

            // 1. Update order
            $order->update(['order_status_id' => $newStatus->value]);

            // 2. Synchronous Audit Trail
            app(OrderHistoryService::class)->save(
                order: $order,
                statusId: $newStatus->value,
                historibleId: $actor->getKey(),
                historibleType: $actor->getMorphClass(),
                notes: $notes
            );

            // 3. Dispatch event for notifications & external services
            broadcast(OrderStatusChanged::class)->toOthers();

            return $order;
        });
    }

    //Helpers=======================================
    private function prepareOrderDetails(OrderDto $dto, array $seller): Collection
    {
        $products = app(ProductService::class)->checkProducts($dto->items, $seller);

        $requestedItems = collect($dto->items)->keyBy('product_id');

        return $products->map(function ($product) use ($requestedItems, $seller) {
            $requestedItem = $requestedItems[$product->id];

            return [
                'product_id' => $product->id,
                'quantity'   => $requestedItem['quantity'],
                'price'      => $product->{$seller['relation']}->first()->pivot->price,
                'note'       => $requestedItem['note'] ?? null,
            ];
        });
    }


    private function calcOrderDetails(Collection $items, ?Coupon $coupon, ?array $seller, int $addressId): array
    {
        $subtotal = $this->calcOrderSubTotal($items);
        $quantity = $this->calcOrderQuantity($items);
        $totals = $this->calcOrderTotal($subtotal, $coupon, $seller, $addressId);

        return array_merge([
            'subtotal' => $subtotal,
            'quantity' => $quantity,
        ], $totals);
    }

    private function calcOrderTotal(float $base_total, ?Coupon $coupon, ?array $seller, int $addressId): array
    {
        $tax = $this->calcTax($base_total);
        $delivery_fee = $this->calcDeliveryFee($seller, $addressId);
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

    private function calcTax(float $base_total): float
    {
        $base_tax = (float) getSetting('tax'); //Percent
        return ($base_total * $base_tax) / 100;
    }

    private function calcDeliveryFee(?array $seller, int $addressId): float
    {
        $delivery_fee = 0;

        $address = app(AddressService::class)->findById($addressId);

        if ($address && $seller) {
            $sellerModel = app($seller['service'])->findById($seller['id']);

            if ($sellerModel) {
                //1. Case seller & client in the same zone
                if ($sellerModel->zone_id && $address->zone_id && $sellerModel->zone_id == $address->zone_id) {
                    $zone = app(ZoneService::class)->findById($sellerModel->zone_id);
                    $delivery_fee = $zone ? (float) $zone->delivery_fee : 0;
                } else {
                    //2. Case client outside the seller zone
                    $distance = calculateDistance(
                        (float) $sellerModel->latitude,
                        (float) $sellerModel->longitude,
                        (float) $address->latitude,
                        (float) $address->longitude
                    );
                    $fee_per_km = (float) (getSetting('delivery_fee_for_each_km') ?? 0);
                    $delivery_fee = $distance * $fee_per_km;
                }
            }
        }

        return $delivery_fee;
    }

    private function calcDiscount(float $base_total, float $delivery_fee, ?Coupon $coupon = null): array
    {
        $discount = 0;
        $discount_type = null;

        if ($coupon) {
            $discount_type = DiscountType::Coupon->value;
            switch ($coupon->discount_on) {
                case CouponDiscountOn::Subtotal:
                    $discount = $coupon->discount($base_total);
                    break;
                case CouponDiscountOn::Delivery:
                    $discount = $coupon->discount($delivery_fee);
                    break;
                case CouponDiscountOn::Both:
                default:
                    $discount = $coupon->discount($base_total + $delivery_fee);
            }
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
