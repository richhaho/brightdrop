<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

 
use App\Clients;
use App\Workers;
use App\TimeSheets;
use App\TimeCards;
use App\ClientInfoWorkers;
use App\Globals;
use App\Invoices;
use App\Payments;
use App\PaymentLines;
use App\InvoiceLines;
use Storage;
use Carbon\Carbon;
use PDF;

class GeneratePTOPaymentEndYear implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    
    public function handle()
    {
        $today=date('m-d',strtotime(Carbon::now()));
        if ($today!='12-31') return;
         $BD=Globals::first();
         $clients=Clients::where('deleted_at',null)->get();
         foreach ($clients as $client) {
            if ($client->pto_infomation!='yes') continue;
            $currentyear=date('Y',strtotime(Carbon::now()));
            $workers=$client->workers();
            $pto_remain=0;
            if ($client->who_pays_pto=='client') {
                $invoice=Invoices::create();
                $invoice->clients_id=$client->id;
                $invoice->date_queue=date('Y-m-d',strtotime(Carbon::now()));
                $invoice->currency_type='usd'; //$info->currency_type;
                $invoice->payment_method=$client->payment_method;
                $invoice->invoice_method=$client->invoice_method;
                $invoice->account_managers_id=$client->account_managers_id;
                $invoice->billing_cycle_end_date=$currentyear.'-12-31';
                $invoice->status='Needs Sent';
                $invoice->invoice_number=11201+($invoice->id-1)*3;
                $invoicefile='invoice_pdf/invoices/'.$invoice->id.'.pdf';
                $invoice->invoice_report_file=$invoicefile;
                $invoice->save();
                $iLines=array();
                $workers_ids=array();
            }
            $total_amount = 0;
            foreach ($workers as $worker) {
                $info = $client->assigned_worker_info()->where('workers_id', $worker->id)->first();
                if ($info->status=='inactive') {
                    continue;
                }
                $hiredyear=date('Y',strtotime($info->hired_at));
                $Year=date('Y',strtotime(Carbon::now())).'-%';
                if ($currentyear==$hiredyear){
                    $pto_days=$info->ptodays_current_calendar;
                }else{
                    $pto_days=$info->ptodays_full_calendar;
                }
                $pto_timesheets=$client->timesheets()->where('workers_id', $worker->id)->where('date','like',$Year)->get();
                $pto_sum = 0;
                foreach ($pto_timesheets as $pto_timesheet) {
                    $pto_sum+=($pto_timesheet->pto_time_hours_updated !=null ? $pto_timesheet->pto_time_hours_updated : $pto_timesheet->pto_time_hours);
                }
                $pto_remain=$pto_days*8-$pto_sum;
                if ($pto_remain<=0) continue;
                // ============= Generate payment summary =======================
                $payment=Payments::create();
                $payment->workers_id=$worker->id;
                $payment->clients_id=$client->id;
                $payment->date_queue=date('Y-m-d',strtotime(Carbon::now()));
                $payment->currency_type=$info->currency_type;
                $payment->payment_method='Veem';
                $payment->payment_type='immediate';
                $payment->status='Pending';
                $file='payment_summary/immediate/'.$payment->id.'.pdf';
                $payment->payment_summary_report_file=$file;
                $payment->save();

                $paymentline=PaymentLines::create();
                $paymentline->payments_id=$payment->id;
                $paymentline->service_id='0150';
                $paymentline->description='Rest PTO Pay on the end day of year';
                $paymentline->quantity_hours=$pto_remain;
                $paymentline->rate=$info->worker_pto_hourly_rate;
                $paymentline->save();
                $paymentline->amount=$paymentline->rate*$paymentline->quantity_hours;
                $paymentline->save();
                $payment->amount=$paymentline->amount;
                $payment->save();

                $lines=array();
                $line['service_id']=$paymentline->service_id;
                $line['description']=$paymentline->description;
                $line['quantity_hours']=$paymentline->quantity_hours;
                $line['rate']=$paymentline->rate;
                $line['amount']=$paymentline->amount;
                $lines[]=$line;

                $now_date=date('D, m/d/y',strtotime(Carbon::now()));
                $data = [];
                $data['document']='payment_summary_immediate';
                $data['BD']=$BD;
                $data['now_date']=$now_date;
                $data['worker']=$worker;
                $data['lines']=$lines;

                $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
                $summary_report=$pdf->output();
                Storage::put($file,$summary_report);
                
                // ============= Generate invoice summary =======================
                if ($client->who_pays_pto!='client') continue;
                $workers_ids[]=$worker->id;
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0150';
                $invoiceline->description='Paid Time Off on the end of year - '.$worker->fullname;;
                $invoiceline->quantity_hours=$pto_remain;
                $invoiceline->rate=$info->client_billable_rate_regular;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate*$invoiceline->quantity_hours;
                $invoiceline->save();
                $total_amount+=$invoiceline->amount;

                $iLine['service_id']=$invoiceline->service_id;
                $iLine['description']=$invoiceline->description;
                $iLine['quantity_hours']=$invoiceline->quantity_hours;
                $iLine['rate']=$invoiceline->rate;
                $iLine['amount']=$invoiceline->amount;
                $iLines[]=$iLine;
            }
            if ($client->who_pays_pto!='client') continue;
            $start_date='01/01/'.$currentyear;
            $end_date='12/31/'.$currentyear;
            $invoiced_date=date('D, m/d/y',strtotime(Carbon::now()));
            $invoice_number=$invoice->invoice_number;
            
            $invoice->workers_ids=implode(",",$workers_ids);            
            $ACH_discount=0;
            if ($client->ACH_discount_participation=='yes') $ACH_discount=0.025;
            $data = [];
            $data['document']='invoice_summary';
            $data['BD']=$BD;
            $data['sub_total_amount']=  number_format($total_amount, 2, '.', ',');
            $data['ACH_discount']=number_format($total_amount*$ACH_discount, 2, '.', ',');
            $data['total_amount']=number_format($total_amount*(1-$ACH_discount) , 2, '.', ',');
            $invoice->amount=round($total_amount*(1-$ACH_discount)*100)/100;
            $invoice->save();
            $data['start_date']=$start_date;
            $data['end_date']=$end_date;
            $data['invoiced_date']=$invoiced_date;
            $data['invoice_number']=$invoice_number;
            $data['client']=$client;
            $data['lines']=$iLines;
            $pdfInvoice=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
            $report=$pdfInvoice->output();
            Storage::put($invoicefile,$report);
        }
    }
}
     
