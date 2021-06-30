<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\MessageRecievedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendMessageNotificationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $toUserId;
    private $message;
    public function __construct($toUserId,$message)
    {
        $this->toUserId=$toUserId;
        $this->message=$message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //need to refactor
        Mail::to(User::findOrFail($this->toUserId))
                   ->send(new MessageRecievedMail(
                    User::findOrFail($this->message->from_user_id)                
        ));
    }
}
