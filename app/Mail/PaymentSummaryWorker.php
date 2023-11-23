<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentSummaryWorker extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    
    public $payment;
     
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        
        $this->payment = $payment;
        $this->subject('Payment Summary');
    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.worker.payment_summary')->attach(storage_path('app/'.$this->payment->payment_summary_report_file),[
                'as' => 'payment-summary-'.$this->payment->date_queue.'.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
