<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdjustmentStatusWorker extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $adjustment;
    public $status;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($adjustment,$status)
    {
        $this->adjustment =$adjustment;
        $this->status = $status;
        $this->subject('Payroll adjustment status');
    

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.worker.adjustment_status');
    }
}
