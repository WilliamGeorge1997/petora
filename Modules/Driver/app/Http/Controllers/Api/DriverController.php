<?php

namespace Modules\Driver\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Driver\Models\Driver;
use Modules\Driver\Services\DriverService;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Services\OrderService;

#[Middleware('auth:driver')]
class DriverController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function orders(Request $request)
    {
        $data = [
            'paginated' => $request->input('paginated') ?: 50,
            'pagination_type' => 'cursor',
        ];
        $relations = ['orderStatus', 'orderMethod'];
        $orders = $this->orderService->driverOrders(auth('driver')->id(), null,  $data, $relations);
        return success(true, __('driver::message.all_orders'), $orders);
    }

    public function newOrders(Request $request)
    {
        $data = [
            'paginated' => $request->input('paginated') ?: 50,
            'pagination_type' => 'cursor',
        ];
        $relations = [
            'paymentMethod:id,title',
            'client:id,name,phone',
            'address',
            'orderMethod',
            'store',
            'clinic'
        ];
        $orders = $this->orderService->driverOrders(auth('driver')->id(), OrderStatus::DeliverToDriver->value, $data, $relations);
        return success(true, __('driver::message.new_orders'), $orders);
    }

    public function openOrders(Request $request)
    {
        $data = [
            'paginated' => $request->input('paginated') ?: 50,
            'pagination_type' => 'cursor',
        ];
        $relations = [
            'paymentMethod:id,title',
            'client:id,name,phone',
            'address',
            'orderMethod',
            'store',
            'clinic'
        ];
        $orders = $this->orderService->driverOrders(auth('driver')->id(), OrderStatus::OnTheWay->value, $data, $relations);
        return success(true, __('driver::message.open_orders'), $orders);
    }

    public function closedOrders(Request $request)
    {
        $data = [
            'paginated' => $request->input('paginated') ?: 50,
            'pagination_type' => 'cursor',
        ];
        $relations = [
            'paymentMethod:id,title',
            'client:id,name,phone',
            'address',
            'orderStatus',
            'orderMethod',
            'store',
            'clinic'
        ];
        $orders = $this->orderService->driverOrders(auth('driver')->id(), orderStatusId: OrderStatus::Done->value, data: $data, relations: $relations);
        return success(true, __('driver::message.closed_orders'), $orders);
    }

    public function orderDetails(int $id)
    {
        $relations = [
            'orderStatus',
            'address.city',
            'address.zone',
            'paymentMethod',
            'details.product.images',
            'orderMethod',
            'store.city',
            'store.zone',
            'clinic.city',
            'clinic.zone',
        ];
        $orders = $this->orderService->findById($id, $relations);
        return success(true, __('driver::message.order_details'), $orders);
    }

    public function acceptOrder(Request $request)
    {
        /** @var Driver $driver */
        $driver = auth('driver')->user();

        $order = $this->orderService->changeStatusTo(
            orderOrId: $request->input('order_id'),
            newStatus: OrderStatus::OnTheWay,
            actor: $driver,
            notes: $request->input('notes')
        );
        return success(true, __('driver::message.order_accepted'), $order);
    }

    public function deliverOrder(Request $request)
    {
        /** @var Driver $driver */
        $driver = auth('driver')->user();

        $order = $this->orderService->changeStatusTo(
            orderOrId: $request->input('order_id'),
            newStatus: OrderStatus::Done,
            actor: $driver,
            notes: $request->input('notes')
        );
        return success(true, __('driver::message.order_delivered'), $order);
    }

    public function refuseOrder(Request $request)
    {
        /** @var Driver $driver */
        $driver = auth('driver')->user();

        $order = $this->orderService->changeStatusTo(
            orderOrId: $request->input('order_id'),
            newStatus: OrderStatus::RefusedByDriver,
            actor: $driver,
            notes: $request->input('notes')
        );
        return success(true, __('driver::message.order_refused'), $order);
    }

    public function failOrder(Request $request)
    {
        /** @var Driver $driver */
        $driver = auth('driver')->user();

        $order = $this->orderService->changeStatusTo(
            orderOrId: $request->input('order_id'),
            newStatus: OrderStatus::Fail,
            actor: $driver,
            notes: $request->input('notes')
        );

        return success(true, __('driver::message.order_failed'), $order);
    }

    public function saveLocation(Request $request)
    {
        $latitude = (float) $request->input('latitude');
        $longitude = (float) $request->input('longitude');

        /** @var Driver $driver */
        $driver = auth('driver')->user();
        $driver->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        return success(true, __('driver::message.location_updated'));
    }

    public function changeLocale(DriverService $driverService)
    {
        /** @var Driver $driver */
        $driver = auth('driver')->user();
        $driverService->changeLocale($driver);

        return success(true, __('driver::message.locale_changed'));
    }

    public function statics()
    {
        $data = $this->orderService->driverStatistics(
            driverId: auth('driver')->id(),
            fromDate: today()->toDateString(),
            toDate: today()->toDateString()
        );

        return success(true, __('driver::message.driver_statistics'), $data);
    }
}
