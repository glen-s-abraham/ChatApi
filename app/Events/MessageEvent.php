<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;
    private $sendChannel;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,$sendChannel)
    {
        $this->message=$message;
        $this->sendChannel=$sendChannel;
        
    }

    public function broadcastWith()
    {
        return [
            'id'=>$this->message->id,
            'message'=>$this->message->message,
            'from_user_id'=>$this->message->from_user_id,
            'created_at'=>$this->message->created_at
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      
        return [$this->sendChannel];
    }

    public function broadcastAs()
    {
        return 'messageEvent';
    }
}
