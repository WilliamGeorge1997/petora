<?php

namespace Modules\Order\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderHistory;

class OrderHistoryService
{
    private string $model = OrderHistory::class;

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

    /**
     * Get order history logs with status relation.
     */
    public function history(int $order_id, array $relations = []): Collection
    {
        return $this->model::with($relations)
            ->whereOrderId($order_id)
            ->get();
    }
}

