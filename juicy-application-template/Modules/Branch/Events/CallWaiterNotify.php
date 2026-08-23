<?php

namespace Modules\Branch\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class CallWaiterNotify implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id, $table, $branch_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($callWaiter)
    {
        $this->id = $callWaiter->id;
        $this->table = $callWaiter->table;
        $this->branch_id = $callWaiter->branch_id;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new Channel('call-waiter-notify-channel-' . $this->branch_id);
    }
}
