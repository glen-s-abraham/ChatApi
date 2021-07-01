<?php


namespace App\Services;

use App\Models\BroadcastChannel;
use App\Models\User;
use App\Traits\UserStatusTraits;
use App\Events\MessageEvent;
use App\Jobs\SendMessageNotificationMail;
class BroadcastService{

	use UserStatusTraits;

    public function sendMessageNotification(
    $sendChannel,
    $message,
    $toUserMail,
    $fromUserName
    )
    {                 
        if($this->isUserStatusPresent($message->to_user_id)==1)
        {
            error_log($this->isUserOnline($message->to_user_id));
            if($this->isUserOnline($message->to_user_id)==1)
            {
                event(new MessageEvent($message,$sendChannel));
                //MessageEvent::dispatch($message,$sendChannel[0]);
            }
            else
            {
                SendMessageNotificationMail::dispatch($toUserMail,
                    [
                        'fromUser'=>$fromUserName,
                        'time'=>$message->created_at,
                    ])
                    ->delay(now()->addSecondss(10));
            }                
        }      
    }

}