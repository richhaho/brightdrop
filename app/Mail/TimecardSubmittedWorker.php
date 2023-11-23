<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TimecardSubmittedWorker extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $timecard;
    public $status;
     
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($timecard,$status)
    {
        
        $this->timecard = $timecard;
        $this->status = $status;
        $this->subject('Your hours have been submitted.');
    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.worker.timecard_submit')->attach(storage_path('app/timecard/'.$this->timecard->id.'.pdf'),[
                'as' => 'timesheet.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
