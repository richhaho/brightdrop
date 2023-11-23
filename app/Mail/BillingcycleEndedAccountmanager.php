<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BillingcycleEndedAccountmanager extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $client;
     
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client)
    {
        $this->client = $client;
        $this->subject('End of payroll cycle: '.$client->client_name);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.accountmanager.billlingcycle_end');
    }
}
