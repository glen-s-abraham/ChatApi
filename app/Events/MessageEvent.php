<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $fromUser;
    public $toUser;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,$fromUser,$toUser)
    {
        $this->message=$message;
        $this->fromUser=$fromUser;
        $this->toUser=$toUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //$channelName=$this->$fromUser;
        //error_log("channelfrom".$this->fromUser."to".$this->toUser);
        return ["messageChannel"];
    }

    public function broadcastAs()
    {
        return '2';
    }
}
