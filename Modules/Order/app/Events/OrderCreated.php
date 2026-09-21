<?php

namespace Modules\Order\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Models\Order;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $orderId;
    public string $orderNo;
    public int $orderStatusId;
    public ?int $clientId;
    public ?int $storeId;
    public ?int $clinicId;
    public float $total;

    public function __construct(Order $order)
    {
        $this->orderId = $order->id;
        $this->orderNo = (string) $order->order_no;
        $this->orderStatusId = (int) $order->order_status_id;
        $this->clientId = $order->client_id;
        $this->storeId = $order->store_id;
        $this->clinicId = $order->clinic_id;
        $this->total = (float) $order->total;
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('orders.live'),
        ];

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'order.created';
    }
}
