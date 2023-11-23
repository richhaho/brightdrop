<?php

namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Workers;
use App\Clients;
use App\ClientInfoWorkers;
use App\Contacts;
use App\AccountManagers;
use App\TimeCards;
use App\TimeSheets;

use App\HolidaySchedule;
use App\HolidayDefault;
use Storage;
use Carbon\Carbon;
use App\Payments;

use App\Reimbursement;
use App\OneTimeAdjustments;
use App\CashAdvances;
use App\RecurringAdjustments;
use Auth;
use PDF;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use Response;
use App\InvoiceLines;
use App\Invoices;
use App\Globals;
use Mail;
use App\Mail\InvoiceSummaryClient;

class InvoiceController extends Controller
{
    public function manual()
    {
        $invoices=Invoices::where('deleted_at',null)->where('invoice_method','manual')->where('status','Needs Sent')->get();
        $data=[
            'invoices'=>$invoices,
        ];
        return view('payroll.invoices.manual',$data);
    }
    public function automatic()
    {
        $invoices=Invoices::where('deleted_at',null)->where('invoice_method','automatically')->where('status','Needs Sent')->get();
        $data=[
            'invoices'=>$invoices,
        ];
        return view('payroll.invoices.automatic',$data);
    }
    public function needsSent()
    {
        $invoices=Invoices::where('deleted_at',null)->where('status','Needs Sent From Finalized')->get();
        $data=[
            'invoices'=>$invoices,
        ];
        return view('payroll.invoices.needsSent',$data);
    }
    public function pendingClientApproval()
    {
        $invoices=Invoices::where('deleted_at',null)->where('status','Pending Client Approval')->get();
        $data=[
            'invoices'=>$invoices,
        ];
        return view('payroll.invoices.pendingClientApproval',$data);
    }
    public function needsProcessed()
    {
        $invoices=Invoices::where('deleted_at',null)->where('status','Needs Processed')->get();
        $payment_method=[
            'client_process_ach'=>'Client Process - ACH',
            'internal_process_ach'=>'Internal Process - ACH on file',
            'internal_process_cc'=>'Internal Process - Credit Card on file',
        ];
        $data=[
            'invoices'=>$invoices,
            'payment_method'=>$payment_method,
        ];
        return view('payroll.invoices.needsProcessed',$data);
    }
    public function bankVerifications()
    {
        $invoices=Invoices::where('deleted_at',null)->where('status','Needs Bank Verification')->get();
        $data=[
            'invoices'=>$invoices,
        ];
        return view('payroll.invoices.bankVerifications',$data);
    }
    public function viewClosed()
    {
        $invoices=Invoices::where('deleted_at',null)->where('status','Bank Verified')->get();
        $data=[
            'invoices'=>$invoices,
        ];
        return view('payroll.invoices.viewClosed',$data);
    }
    public function finalized(Request $request)
    {
        $id=$request->id;
        $invoice=Invoices::where('id',$id)->first();
        $invoice->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        if ($invoice->client()->review_time=='auto') {
            $invoice->status="Needs Processed";
            $invoice->save();
            // Session::flash('message', "Just finalized invoice #".$invoice->invoice_number."."); 
            // return redirect()->route('payroll.invoices.needsProcessed');
        } else {
            $invoice->status="Needs Sent From Finalized";
            $invoice->save();
            // Session::flash('message', "Just finalized invoice #".$invoice->invoice_number."."); 
            // return redirect()->route('payroll.invoices.needsSent');
        }
        Session::flash('message', "Just finalized invoice #".$invoice->invoice_number."."); 
        return redirect()->route('payroll.invoices.'.$request->from);
    }
    public function send_client(Request $request)
    {
        $id=$request->id;
        $invoice=Invoices::where('id',$id)->first();
        $invoice->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        // $mailto=array();
        // foreach ($invoice->client()->contacts()->get() as $contact) {
        //     $mailto[]=$contact->email;
        // }
        // if (count($mailto)>0) Mail::to($mailto)->send(new InvoiceSummaryClient($invoice));
        
        $invoice->status="Pending Client Approval";
        $invoice->save();
        
        Session::flash('message', "Just sent an invoice #".$invoice->invoice_number." to client."); 
        return redirect()->route('payroll.invoices.needsSent');
    }
    public function mark_sent(Request $request)
    {
        $id=$request->id;
        $invoice=Invoices::where('id',$id)->first();
        $invoice->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        $invoice->status="Pending Client Approval";
        $invoice->save();
        Session::flash('message', "Just marked an invoice #".$invoice->invoice_number." as sent to client."); 
        return redirect()->route('payroll.invoices.pendingClientApproval');
    }

    public function recalculate(Request $request)
    {
        $id=$request->id;
        $invoice=Invoices::where('id',$id)->first();
        $this->recalculateInvoice($invoice);
        Session::flash('message', "Invoice #".$invoice->invoice_number." has been recalculated.");
        if ($invoice->invoice_method=='automatically'){
            return redirect()->route('payroll.invoices.automatic');
        }else{
            return redirect()->route('payroll.invoices.manual');
        }
    }
    public function download($id)
    {
        $invoice=Invoices::where('id',$id)->first();
        $client=$invoice->client();
        $filename=$client->client_name.'_'.$invoice->invoice_number.'_'.$invoice->billing_cycle_end_date;
        if (Storage::disk()->exists($invoice->invoice_report_file)) {
            $contents = Storage::get($invoice->invoice_report_file);
            $response = Response::make($contents, '200',[
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.pdf"',
                ]);
           
            return $response;
        }else{
            Session::flash('message', "Invoice #".$invoice->invoice_number."'s document deleted by some reason.");
            return redirect()->back();
        }
    }
    public function delete(Request $request)
    {
        $id=$request->id;$url=$request->url;
        $invoice=Invoices::where('id',$id)->first();
        $invoice->delete();
        Session::flash('message', "Just deleted an invoice #".$invoice->invoice_number."."); 
        return redirect()->route($url);
    }

    public function approvebyclient(Request $request)
    {
        $id=$request->id;
        $invoice=Invoices::where('id',$id)->first();
        $invoice->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        $client=$invoice->client();
        if($client->payment_method=="client_process_ach"){
            $invoice->status="Needs Bank Verification";
            $invoice->save();
            // Session::flash('message', "Invoice #".$invoice->invoice_number." approved by client."); 
            // return redirect()->route('payroll.invoices.bankVerifications');
        }else{
            $invoice->status="Needs Processed";
            $invoice->save();
            // Session::flash('message', "Invoice #".$invoice->invoice_number." approved by client."); 
            // return redirect()->route('payroll.invoices.needsProcessed');
        }
        Session::flash('message', "Invoice #".$invoice->invoice_number." approved by client."); 
        return redirect()->route('payroll.invoices.pendingClientApproval');
    }
    public function declinebyclient(Request $request)
    {
        $id=$request->id;
        $invoice=Invoices::where('id',$id)->first();
        $invoice->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        $invoice->status="Needs Sent";
        $invoice->save();
        Session::flash('message', "Invoice #".$invoice->invoice_number." declined by client."); 
        return redirect()->route('payroll.invoices.pendingClientApproval');
    }

    public function process(Request $request)
    {
        $id=$request->id;
        $invoice=Invoices::where('id',$id)->first();
        $invoice->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        $invoice->status="Needs Bank Verification";
        $invoice->save();
        Session::flash('message', "Invoice #".$invoice->invoice_number." processed."); 
        return redirect()->route('payroll.invoices.needsProcessed');
    }

    public function bank_verify(Request $request)
    {
        $id=$request->id; 
        $invoice=Invoices::where('id',$id)->first();
        $invoice->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        $invoice->status="Bank Verified";
        $invoice->date_verified=date('Y-m-d',strtotime(Carbon::now()));
        $invoice->save();

        $recurrings=RecurringAdjustments::where('invoices_id',$invoice->id)->get();
        foreach ($recurrings as $recurring) {$recurring->status='Invoice Verified';$recurring->handle_date=$invoice->date_verified;$recurring->save();}

        Session::flash('message', "Invoice #".$invoice->invoice_number." verified Bank."); 
        return redirect()->route('payroll.invoices.bankVerifications');
    }



    public function recalculateInvoice($invoice){
        $client=$invoice->client();
        $BD=Globals::first();
        $currency['usd']=1;
        $currency['php']=$BD->php_usd;
        $currency['mxn']=$BD->mxn_usd;
        
        $timecards=$invoice->timecards();
        if (count($timecards)==0) return;
        $cnt=0;
        foreach ($timecards as $timecard) {
            $worker=$timecard->worker();
            $adjustments1=OneTimeAdjustments::where('paytoclient',$client->id)->where('billtoworker',$worker->id)->where('adjustment_date','>=',$timecard->start_date)->where('adjustment_date','<=',$timecard->end_date)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','Time Adjustment')->get();
            $adjustments2=OneTimeAdjustments::where('paytoclient',$client->id)->where('billtoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','!=','Time Adjustment')->get();
            $adjustments3=OneTimeAdjustments::where('billtoclient',$client->id)->where('paytoworker',$worker->id)->where('adjustment_date','>=',$timecard->start_date)->where('adjustment_date','<=',$timecard->end_date)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','Time Adjustment')->get();
            $adjustments4=OneTimeAdjustments::where('billtoclient',$client->id)->where('paytoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','!=','Time Adjustment')->get();
            $adjustments5=RecurringAdjustments::where('billtoclient',$client->id)->where('paytoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->get();
            $adjustments6=RecurringAdjustments::where('paytoclient',$client->id)->where('billtoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->get();
            $cnt=$cnt+count($adjustments1)+count($adjustments2)+count($adjustments3)+count($adjustments4)+count($adjustments5)+count($adjustments6);
            $start_date=date('D, m/d/y',strtotime($timecard->start_date));
            $end_date=date('D, m/d/y',strtotime($timecard->end_date));
        }
        if ($cnt==0) {
            $invoiced_date=date('D, m/d/y',strtotime(Carbon::now()));

            $lines=array();
            $total_amount=0;
            foreach ($invoice->lines() as $key => $invoiceline) {
                $total_amount+=$invoiceline->amount;
                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;
            }

            $ACH_discount=0;
            if ($client->ACH_discount_participation=='yes') $ACH_discount=0.025;

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
            $data['invoice_number']=$invoice->invoice_number;

            $data['client']=$client;
            $data['lines']=$lines;
            $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
            $report=$pdf->output();
            $m=new Merger();
            $m->addRaw($report);

            foreach ($timecards as $timecard) {
                $data['document']='timecard';
                $data['timecard']=$timecard;
                $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
                $report_timesheets=$pdf->output();
                $m->addRaw($report_timesheets);
            }
            $summary_report=$m->merge();
            $file=$invoice->invoice_report_file;
            Storage::put($file,$summary_report);
            return;
        }


        //******** 6. Adjustment (One - Time): ************
        foreach ($timecards as $timecard) {
            $worker=$timecard->worker();
            $info=ClientInfoWorkers::where('workers_id',$timecard->workers_id)->where('clients_id',$timecard->clients_id)->first();
            $regular_rate=round($info->worker_pay_houly_rate_regular/$currency[$info->currency_type]*100)/100;
            
            $adjustments=OneTimeAdjustments::where('paytoclient',$client->id)->where('billtoworker',$worker->id)->where('adjustment_date','>=',$timecard->start_date)->where('adjustment_date','<=',$timecard->end_date)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','Time Adjustment')->get();
            foreach($adjustments as $adjustment){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0280';
                $description=$adjustment->type.' - '.$worker->fullname.'('.date('m/d/y',strtotime($adjustment->adjustment_date)).')';
                $invoiceline->quantity_hours=-$adjustment->adjustment_total_hours;
                $invoiceline->rate=$regular_rate;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate*$invoiceline->quantity_hours;
                $invoiceline->description='Adjustment (One-Time): '.$description;
                $invoiceline->save();
                $adjustment->invoices_id=$invoice->id;
                $adjustment->save();

            }
            $adjustments=OneTimeAdjustments::where('paytoclient',$client->id)->where('billtoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','!=','Time Adjustment')->get();
            foreach($adjustments as $adjustment){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0290';
                $description=$adjustment->other_description.' - '.$worker->fullname;
                $invoiceline->quantity_hours=-1;
                $invoiceline->rate=round($adjustment->other_amount/$currency[$adjustment->other_currency]*100)/100;
                $invoiceline->save();
                $invoiceline->amount=-$invoiceline->rate;
                
                $invoiceline->description='Adjustment (One-Time): '.$description;
                $invoiceline->save();
                $adjustment->invoices_id=$invoice->id;
                $adjustment->save();
 
            }

            $adjustments=OneTimeAdjustments::where('billtoclient',$client->id)->where('paytoworker',$worker->id)->where('adjustment_date','>=',$timecard->start_date)->where('adjustment_date','<=',$timecard->end_date)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','Time Adjustment')->get();
            foreach($adjustments as $adjustment){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0280';
                $description=$adjustment->type.' - '.$worker->fullname.'('.date('m/d/y',strtotime($adjustment->adjustment_date)).')';
                $invoiceline->quantity_hours=$adjustment->adjustment_total_hours;
                $invoiceline->rate=$regular_rate;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate*$invoiceline->quantity_hours;
                $invoiceline->description='Adjustment (One-Time): '.$description;
                $invoiceline->save();
                $adjustment->invoices_id=$invoice->id;
                $adjustment->save();
 
            }
            $adjustments=OneTimeAdjustments::where('billtoclient',$client->id)->where('paytoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','!=','Time Adjustment')->get();
            foreach($adjustments as $adjustment){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0290';
                $description=$adjustment->other_description.' - '.$worker->fullname;
                $invoiceline->quantity_hours=1;
                $invoiceline->rate=round($adjustment->other_amount/$currency[$adjustment->other_currency]*100)/100;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate;
                
                $invoiceline->description='Adjustment (One-Time): '.$description;
                $invoiceline->save();
                $adjustment->invoices_id=$invoice->id;
                $adjustment->save();
 
            }

        }

        //******** 7. Adjustment (Recurring): ************
         
        foreach ($timecards as $timecard) {
            $worker=$timecard->worker();
            $adjustments=RecurringAdjustments::where('billtoclient',$client->id)->where('paytoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->get();
            
            foreach($adjustments as $adjustment){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0380';
                $description=$adjustment->description;
                $invoiceline->quantity_hours=1;
                $invoiceline->rate=round($adjustment->amount/$currency[$adjustment->currency_type]*100)/100;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate;
                 
                $invoiceline->description='Adjustment(Recurring): '.$description.' - '.$worker->fullname;
                $invoiceline->save();

                $adjustment->invoices_id=$invoice->id;
                $adjustment->save();

    
            }

            $adjustments=RecurringAdjustments::where('paytoclient',$client->id)->where('billtoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->get();
            
            foreach($adjustments as $adjustment){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0380';
                $description=$adjustment->description;
                $invoiceline->quantity_hours=-1;
                $invoiceline->rate=round($adjustment->amount/$currency[$adjustment->currency_type]*100)/100;
                $invoiceline->save();
                $invoiceline->amount=-$invoiceline->rate;
                 
                $invoiceline->description='Adjustment(Recurring): '.$description.' - '.$worker->fullname;
                $invoiceline->save();

                $adjustment->invoices_id=$invoice->id;
                $adjustment->save();
 
            }
        
        $start_date=date('D, m/d/y',strtotime($timecard->start_date));
        $end_date=date('D, m/d/y',strtotime($timecard->end_date));
        }
        $invoiced_date=date('D, m/d/y',strtotime(Carbon::now()));

        $lines=array();
        $total_amount=0;
        foreach ($invoice->lines() as $invoiceline) {
            $total_amount+=$invoiceline->amount;
            $line['service_id']=$invoiceline->service_id;
            $line['description']=$invoiceline->description;
            $line['quantity_hours']=$invoiceline->quantity_hours;
            $line['rate']=$invoiceline->rate;
            $line['amount']=$invoiceline->amount;
            $lines[]=$line;
        }

        $ACH_discount=0;
        if ($client->ACH_discount_participation=='yes') $ACH_discount=0.025;

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
        $data['invoice_number']=$invoice->invoice_number;

        $data['client']=$client;
        $data['lines']=$lines;
        $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
        $report=$pdf->output();
        $m=new Merger();
        $m->addRaw($report);

        foreach ($timecards as $timecard) {
            $data['document']='timecard';
            $data['timecard']=$timecard;
            $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
            $report_timesheets=$pdf->output();
            $m->addRaw($report_timesheets);
        }
        $summary_report=$m->merge();
        $file=$invoice->invoice_report_file;
        Storage::put($file,$summary_report);
    }

    public function remove(Request $request)
    {
        $id=$request->invoice_id;
        $from=$request->from;
        $invoice=Invoices::where('id',$id)->first();
        foreach ($invoice->lines() as $line) {
            $line->delete();
        }
        $invoice->delete();

        Session::flash('message', "This invoice was deleted successfully.");
        return redirect()->route('payroll.invoices.'.$from);
    }

    public function edit(Request $request, $id)
    {
        $invoice=Invoices::where('id',$id)->first();
        $from=$request->from;
        if (!$invoice) {
            Session::flash('message', "This invoice was deleted.");
            return redirect()->route('payroll.invoices.'.$from);
        }
        
        $client=$invoice->client();
        $timecards=$invoice->timecards();
        $data=[
            'invoice'=>$invoice,
            'client'=>$client,
            'timecards'=>$timecards,
            'from'=>$from,
        ];
        return view('payroll.invoices.edit',$data);
    }

    public function update(Request $request)
    {
        $from=$request->from;
        $invoice=Invoices::where('id',$request->invoice_id)->first();
        if (!$invoice) {
            Session::flash('message', "This invoice was deleted.");
            return redirect()->route('payroll.invoices.'.$from);
        }
        
        $client=$invoice->client();
        $ACH_discount=0;
        if ($client->ACH_discount_participation=='yes') $ACH_discount=0.025;
        $total_amount=0;
        $lines=array();
        if (isset($request->service_id)) {
            foreach ($request->service_id as $key => $value) {
                $line['id'] = $key;
                $line['service_id'] = $request->service_id[$key];
                $line['description'] = $request->description[$key];
                $line['quantity_hours'] = $request->quantity_hours[$key];
                $line['rate'] = $request->rate[$key];
                $line['amount'] = $request->amount[$key];
                $lines[] = $line;
                $total_amount+=$line['amount'];
            }
        }
        $invoice->amount_updated=round($total_amount*(1-$ACH_discount)*100)/100;
        $invoice->save();
        if (count($lines)==0) {
            Session::flash('message', "You did not add invoice lines. Please add them.");
            return redirect()->route('payroll.invoices.edit', ['id'=>$invoice->id,'from'=>$from]);
        }

        $timecard=$invoice->timecards()->first();
        $start_date=date('D, m/d/y',strtotime($timecard->start_date));
        $end_date=date('D, m/d/y',strtotime($timecard->end_date));

        $updated_timecards=array();
        foreach ($invoice->timecards() as $timecard) {
            $total_work_time=0;
            $total_pto_time=0;
            $total_holiday_time=0;
            $timesheets=array();
            foreach ($timecard->timesheets()->get() as $timesheet) {
                $work_time_hours=$request->work_time_hours[$timesheet->id] =='0' ? null:$request->work_time_hours[$timesheet->id];
                $work_time_minutes=$request->work_time_minutes[$timesheet->id]=='0' ? null:$request->work_time_minutes[$timesheet->id];
                $pto_time_hours=$request->pto_time_hours[$timesheet->id]=='0' ? null:$request->pto_time_hours[$timesheet->id];
                $holiday_time_hours=$request->holiday_time_hours[$timesheet->id]=='0' ? null:$request->holiday_time_hours[$timesheet->id];
                $total_work_time+=($work_time_hours+$work_time_minutes/60);
                $total_pto_time+=$pto_time_hours;
                $total_holiday_time+=$holiday_time_hours;
                $timesheet['date']=$timesheet->date;
                $timesheet['work_time_hours']=$work_time_hours;
                $timesheet['work_time_minutes']=$work_time_minutes;
                $timesheet['pto_time_hours']=$pto_time_hours;
                $timesheet['holiday_time_hours']=$holiday_time_hours;
                $timesheet['day']=$timesheet->day;
                $timesheet['id']=$timesheet->id;
                $timesheets[]=$timesheet;
            }

            $updated_timecard['timesheets'] = $timesheets;
            $updated_timecard['total_work_time'] = round($total_work_time*100)/100;
            $updated_timecard['total_pto_time'] = $total_pto_time;
            $updated_timecard['total_holiday_time'] = $total_holiday_time;
            $worker=$timecard->worker();
            $updated_timecard['worker'] = $worker;
            $updated_timecard['client'] = $client;
            $updated_timecard['workers_id'] = $worker->id;
            $updated_timecard['clients_id'] = $client->id;
            $updated_timecard['start_date'] = $start_date;
            $updated_timecard['end_date'] = $end_date;
            $updated_timecard['id'] = $timecard->id;
            $updated_timecards[]=$updated_timecard;
        }

        // update items_updated
        $items['lines'] = $lines;
        $items['timecards']= $updated_timecards;
        $invoice->items_updated = json_encode($items);
        $invoice->save();
        ////////////////////////

        $invoiced_date=date('D, m/d/y',strtotime(Carbon::now()));
        $invoice_number=$invoice->invoice_number;
        $BD=Globals::first();
        $file='invoice_pdf/invoices/'.$invoice->id.'.pdf';
        $invoice->invoice_report_file=$file;
        $invoice->save();

        $data['document']='invoice_summary';
        $data['BD']=$BD;
        $data['sub_total_amount']=  number_format($total_amount, 2, '.', ',');
        $data['ACH_discount']=number_format($total_amount*$ACH_discount, 2, '.', ',');
        $data['total_amount']=number_format($total_amount*(1-$ACH_discount) , 2, '.', ',');
        $data['start_date']=$start_date;
        $data['end_date']=$end_date;

        $data['invoiced_date']=$invoiced_date;
        $data['invoice_number']=$invoice_number;

        $data['client']=$client;
        $data['lines']=$lines;
        $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
        $report=$pdf->output();
        $m=new Merger();
        $m->addRaw($report);

        foreach ($updated_timecards as $timecard) {
            $data['document']='timecard_edit';
            $data['timecard']=$timecard;
            $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
            $report_timesheets=$pdf->output();
            $m->addRaw($report_timesheets);
        }
        $summary_report=$m->merge();
        Storage::put($file,$summary_report);
         

        Session::flash('message', "This invoice summary was updated successfully.");
        return redirect()->route('payroll.invoices.'.$from);
    }
    
}
