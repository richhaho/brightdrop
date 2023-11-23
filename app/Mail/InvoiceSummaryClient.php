<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceSummaryClient extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $invoice;
     
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        
        $this->invoice = $invoice;
        $this->subject('Invoice Summary');
    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.client.invoice_summary')->attach(storage_path('app/'.$this->invoice->invoice_report_file),[
                'as' => 'invoice-summary-'.$this->invoice->date_queue.'.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
