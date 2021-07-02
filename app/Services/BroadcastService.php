<?php


namespace App\Services;

use App\Models\BroadcastChannel;
use App\Models\User;
use App\Traits\UserStatusTraits;
use App\Events\MessageEvent;
use App\Jobs\SendMessageNotificationMail;
class BroadcastService{

	use UserStatusTraits;

    public function pushMessage($sendChannel,$message)
    {                 
        
        event(new MessageEvent($message,$sendChannel));
    }

    public function sendEmailNotification($toUserMail,$fromUserName,$message)
    {
        SendMessageNotificationMail::dispatch($toUserMail,
                    [
                        'fromUser'=>$fromUserName,
                        'time'=>$message->created_at,
                    ])
                    ->delay(now()->addSeconds(10));
    }

}