<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
class MessageRecievedMail extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user=$user;
       

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->view('email.messageRecieved')
                     ->with(['user'=>$this->user->name]);
    }
}