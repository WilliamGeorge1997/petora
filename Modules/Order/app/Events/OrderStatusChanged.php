<?php

namespace Modules\Order\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Models\Order;

class OrderStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct($order, $previousStatus, $newStatus, $actor) {}
}
