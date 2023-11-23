<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CashAdvanceStatusWorker extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $cash_advance;
    public $status;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cash_advance,$status)
    {
        $this->cash_advance =$cash_advance;
        $this->status = $status;
        $this->subject('Cash advance status');
    

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.worker.cash_advance_status')->attach(storage_path('app/cash_advance/'.$this->cash_advance->id.'.pdf'),[
                'as' => 'cash_advance.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
