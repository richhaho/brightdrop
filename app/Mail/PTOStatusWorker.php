<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PTOStatusWorker extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $pto;
    public $status;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pto,$status)
    {
        $this->pto =$pto;
        $this->status = $status;
        $this->subject('PTO Approval Status');
    

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.worker.pto_status');
    }
}
