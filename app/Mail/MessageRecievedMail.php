<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageRecievedMail extends Mailable
{
    use Queueable, SerializesModels;
    private $message;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message=$message;
       

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
         return $this->view('email.messageRecieved')
                     ->with([
                                'fromuser'=>$this->message['fromUser'],
                                'time'=>$this->message['time']
                            ]);
    }
}
