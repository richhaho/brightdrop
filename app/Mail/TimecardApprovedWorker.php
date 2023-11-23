<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TimecardApprovedWorker extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $timecard;
    public $approver;
     
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($timecard,$approver)
    {
        
        $this->timecard = $timecard;
        $this->approver = $approver;
        $this->subject('Timesheet Approved');
    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.worker.timecard_approve');
    }
}
