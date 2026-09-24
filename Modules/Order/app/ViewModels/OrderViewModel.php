<?php

namespace Modules\Order\ViewModels;

use Modules\Driver\Services\DriverService;
use Modules\Order\Enums\OrderMethod;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderStatusService;

class OrderViewModel
{
    public function orderStatuses(): iterable
    {
        return (new OrderStatusService)->active(columns: ['id', 'title']);
    }

    public function drivers(): iterable
    {
        return (new DriverService)->active(columns: ['id', 'name']);
    }

    public function nextAction(Order $order): ?array
    {
        $statusId = (int) $order->order_status_id;
        $isReceiveFromBranch = (int) $order->order_method_id === OrderMethod::ReceiveFromBranch->value;

        return match ($statusId) {
            OrderStatus::Sent->value => [
                'id' => OrderStatus::AcceptedAndPreparing->value,
                'title' => OrderStatus::AcceptedAndPreparing->label(),
                'color' => 'primary',
                'icon' => 'check-circle',
            ],
            OrderStatus::AcceptedAndPreparing->value => $isReceiveFromBranch
                ? [
                    'id' => OrderStatus::Done->value,
                    'title' => OrderStatus::Done->label(),
                    'color' => 'success',
                    'icon' => 'check',
                ]
                : [
                    'id' => OrderStatus::DeliverToDriver->value,
                    'title' => OrderStatus::DeliverToDriver->label(),
                    'color' => 'info',
                    'icon' => 'arrow-right-circle',
                ],
            OrderStatus::DeliverToDriver->value => [
                'id' => OrderStatus::OnTheWay->value,
                'title' => OrderStatus::OnTheWay->label(),
                'color' => 'success',
                'icon' => 'truck',
            ],
            OrderStatus::OnTheWay->value => [
                'id' => OrderStatus::Done->value,
                'title' => OrderStatus::Done->label(),
                'color' => 'success',
                'icon' => 'check',
            ],
            OrderStatus::RefusedByDriver->value => [
                'id' => OrderStatus::DeliverToDriver->value,
                'title' => OrderStatus::DeliverToDriver->label(),
                'color' => 'warning',
                'icon' => 'refresh-cw',
            ],
            default => null,
        };
    }
}
