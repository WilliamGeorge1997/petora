<?php

namespace Modules\Order\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Models\Order;

class OrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $orderId;
    public string $orderNo;
    public int $orderStatusId;
    public ?int $clientId;
    public ?int $driverId;
    public ?int $storeId;
    public ?int $clinicId;
    public float $total;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order)
    {
        $this->orderId = $order->id;
        $this->orderNo = (string) $order->order_no;
        $this->orderStatusId = (int) $order->order_status_id;
        $this->clientId = $order->client_id;
        $this->driverId = $order->driver_id;
        $this->storeId = $order->store_id;
        $this->clinicId = $order->clinic_id;
        $this->total = (float) $order->total;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            // Admin Live Dispatch Console
            new PrivateChannel('orders.live'),
        ];

        // Customer Mobile App (receives all order status updates)
        if ($this->clientId) {
            $channels[] = new PrivateChannel('client.' . $this->clientId);
        }

        // Driver Mobile App (receives event ONLY if driver is assigned)
        if ($this->driverId) {
            $channels[] = new PrivateChannel('driver.' . $this->driverId);
        }

        return $channels;
    }

    /**
     * The event's broadcast name for Flutter / Echo.
     */
    public function broadcastAs(): string
    {
        return 'order.status.changed';
    }
}
