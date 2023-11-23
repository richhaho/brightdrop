<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WorkerVideoProfileClient extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $link;
    public $worker;
     
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($worker,$link)
    {
        
        $this->worker = $worker;
        $this->link = $link;
        $this->subject('Video Profile: '.$worker->fullname);
    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.client.worker_videoprofile');
    }
}
