<?php

namespace Modules\Order\Services;

use Modules\Order\Models\Order;
use Modules\Order\Models\OrderHistory;

class OrderHistoryService
{
    /**
     * Log a new history entry for an order.
     */
    public function save(Order $order, int $statusId, ?int $historibleId = null, ?string $historibleType = null, ?string $notes = null): OrderHistory
    {
        return $order->histories()->create([
            'order_status_id' => $statusId,
            'notes'           => $notes,
            'historible_id'   => $historibleId,
            'historible_type' => $historibleType,
        ]);
    }
}
