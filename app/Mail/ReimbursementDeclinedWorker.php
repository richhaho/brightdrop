<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReimbursementDeclinedWorker extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $reimbursement;
    public $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reimbursement,$status)
    {
        $this->reimbursement =$reimbursement;
        $this->status =$status;
        
        $this->subject('Reimbursement request status.');
    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.worker.declined_reimbursement');
    }
}
