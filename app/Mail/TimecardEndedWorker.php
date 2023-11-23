<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TimecardEndedWorker extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $timecard;
     
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($timecard)
    {
        
        $this->timecard = $timecard;
        $this->subject('End of payroll cycle: Please review your timesheet');
    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.worker.timecard_end');
    }
}
