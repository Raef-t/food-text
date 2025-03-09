<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RowStored implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
 public function __construct($order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        Log::info('RowStored event broadcasting on channel: getOrder');
        return ['getOrder' => $this->order];
    }

    public function broadcastWith()
    {
        return ['getOrder' => $this->order];
    }
    public function broadcastAs() {

 return 'getOrder';

 }

}

