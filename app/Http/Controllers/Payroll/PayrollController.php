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
use Mail;
use App\Mail\PaymentSummaryWorker;
use App\Mail\CashAdvanceStatusWorker;

use Auth;
use PDF;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use Response;
use App\Globals;
use App\Custom\Veem;

class PayrollController extends Controller
{
    public function immediateWU()
    {
        $payments=Payments::where('status','Pending')->where('payment_method','Western Union')->where('payment_type','immediate')->get();
        $data=[
            'payments'=>$payments,
        ];

        return view('payroll.payroll.immediateWU',$data);
    }
    public function immediateVeem()
    {
        $payments=Payments::where('status','Pending')->where('payment_method','Veem')->where('payment_type','immediate')->get();
        $data=[
            'payments'=>$payments,
        ];
        return view('payroll.payroll.immediateVeem',$data);
    }
    public function biWeeklyWU()
    {
        $payments=Payments::where('status','Pending')->where('payment_method','Western Union')->where('payment_type','bi-weekly')->get();
        $data=[
            'payments'=>$payments,
        ];
        return view('payroll.payroll.biWeeklyWU',$data);
    }
    public function biWeeklyVeem()
    {
        $payments=Payments::where('status','Pending')->where('payment_method','Veem')->where('payment_type','bi-weekly')->get();
        $data=[
            'payments'=>$payments,
        ];
        return view('payroll.payroll.biWeeklyVeem',$data);
    }
    public function viewClosed()
    {
        $clients = Clients::where('deleted_at', null)->orderBy('client_name', 'ASC')->get();
        $search_clients=$clients->where('status', 'active')->pluck('client_name','id')->prepend('','');

        $clients_id = session('viewClosed_payroll.clients_id');
        $workers_id = session('viewClosed_payroll.workers_id');
        $search_workers = [];
        if ($clients_id) {
            $client = Clients::where('id',$clients_id)->first();
            $search_workers = $client->activeWorkers()->pluck('fullname','id')->prepend('','');
        }

        $workers = Workers::where('deleted_at', null)->get();
        $workersNameList = $workers->pluck('full_name', 'id')->toArray();
        $workersMainEmailList = $workers->pluck('email_main', 'id')->toArray();
        $workersVeemEmailList = $workers->pluck('email_veem', 'id')->toArray();

        $payments=Payments::where('status','Paid')->where('clients_id',$clients_id)->where('workers_id', $workers_id)->orderBy('date_paid','desc')->get();
        $data=[
            'payments'=>$payments,
            'workersNameList'=>$workersNameList,
            'workersMainEmailList'=>$workersMainEmailList,
            'workersVeemEmailList'=>$workersVeemEmailList,
            'clients'=>$clients->pluck('client_name', 'id')->toArray(),
            'search_clients'=>$search_clients,
            'search_workers'=>$search_workers,
            'clients_id'=>$clients_id,
            'workers_id'=>$workers_id
        ];
        return view('payroll.payroll.viewClosed',$data);

    }

    public function setfilter (Request $request) {
        $page = $request->page;
        session()->forget($page.'_payroll');
        if ($request->has('clients_id')) {
            if($request->clients_id) {
                session([$page.'_payroll.clients_id' => $request->clients_id]);
            }
        }
        if ($request->has('workers_id')) {
            if($request->workers_id) {
                session([$page.'_payroll.workers_id' => $request->workers_id]);
            }
        }
        return redirect()->route('payroll.payroll.'.$page);
    }
    public function resetfilter (Request $request) {
        $page = $request->page;
        session()->forget($page.'_payroll');
        return redirect()->route('payroll.payroll.'.$page);
    }

    public function viewReport($id)
    {
        $payment=Payments::where('id',$id)->first();
        if (Storage::disk()->exists($payment->payment_summary_report_file)) {
            $contents = Storage::get($payment->payment_summary_report_file);
            $response = Response::make($contents, '200',[
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="payment_sumary.pdf"',
                ]);
           
            return $response;
        }else{
            Session::flash('message', "This summary document deleted by some reason.");
            return redirect()->back();
        }
    }

    public function changePaymentMethod(Request $request) {
        $payment=Payments::where('id',$request->id)->first();
        $payment->payment_method = $request->from === 'Veem' ? 'Western Union' : 'Veem';
        $payment->save();
        Session::flash('message', "Payment method was changed.");
        return redirect()->back();
    }
    public function pay(Request $request)
    {
        $payment=Payments::where('id',$request->id)->first();
        if ($payment->payment_method == 'Veem' && !$payment->veem_pay_status) {
            $veem = new Veem();
            $veem->getAccessToken();
            $res = $veem->createPayment($payment);
            if ($res != 'success') {
                Session::flash('message', $res);
                return redirect()->route('payroll.payroll.'.$request->from);
            }
            $payment->veem_pay_status = $res;
            $payment->save();
        }
        $mailto=$payment->worker()->email_main;
        Mail::to($mailto)->send(new PaymentSummaryWorker($payment));
        $payment->payment_summary='Sent';
        $payment->date_paid=date('Y-m-d',strtotime(Carbon::now()));
        $payment->status='Paid';
        $payment->save();
        
        $timecard=TimeCards::where('payments_id',$payment->id)->first();

        $reimbursement=Reimbursement::where('payments_id',$payment->id)->first();
        $adjustments=OneTimeAdjustments::where('payments_id',$payment->id)->get();
        $recurrings=RecurringAdjustments::where('payments_id',$payment->id)->get();
        $cashAdvances=CashAdvances::where('payments_id',$payment->id)->get();

        if (count($timecard)>0) 
            {$timecard->status='Paid';$timecard->handle_date=$payment->date_paid;$timecard->save();}
        if (count($reimbursement)>0) 
            {$reimbursement->status='Paid';$reimbursement->handle_date=$payment->date_paid;$reimbursement->save();}
        foreach ($adjustments as $adjustment)
            {$adjustment->status='Paid';$adjustment->handle_date=$payment->date_paid;$adjustment->save();}
        foreach ($recurrings as $recurring) 
            {$recurring->status='Paid';$recurring->handle_date=$payment->date_paid;$recurring->save();}
        if (count($cashAdvance)>0) 
        foreach ($cashAdvances as $cashAdvance) 
            {$cashAdvance->status='Paid';$cashAdvance->handle_date=$payment->date_paid;$cashAdvance->save();}
        $cash_advances=CashAdvances::where('status','Paid')->get();
        foreach ($cash_advances as $cash_advance) {
            $repaid=null;
            foreach (unserialize($cash_advance->open_cash_advances) as $advance) {
                if(isset($advance['payments_id'])){
                    if ($advance['payments_id']==$payment->id){
                        $repaid='done';
                    }
                }
            }
            if(!$repaid) continue;
            $open_cash_advances=array();
            $total_paid=0;
            foreach (unserialize($cash_advance->open_cash_advances) as $advance) {
                if ($advance['payments_id']==$payment->id){
                    $open_cash_advance['payment_number']=$advance['payment_number'];
                    $open_cash_advance['due_date']=$advance['due_date'];
                    $open_cash_advance['amount']=$advance['amount'];
                    $open_cash_advance['currency']=$advance['currency'];
                    $open_cash_advance['status']='repaid';
                    $open_cash_advance['payments_id']=$advance['payments_id'];
                    $total_paid+=$advance['amount'];
                }else{
                    $open_cash_advance['payment_number']=$advance['payment_number'];
                    $open_cash_advance['due_date']=$advance['due_date'];
                    $open_cash_advance['amount']=$advance['amount'];
                    $open_cash_advance['currency']=$advance['currency'];
                    $open_cash_advance['status']=$advance['status'];
                    $open_cash_advance['payments_id']=$advance['payments_id'];
                    if($advance['status']=='repaid') $total_paid+=$advance['amount'];

                }
                $open_cash_advances[]=$open_cash_advance;
            }
            $cash_advance->open_cash_advances=serialize($open_cash_advances);
            if($cash_advance->total_paid!=$total_paid){
                $cash_advance->total_paid=$total_paid;
                $cash_advance->save();
                //$this->cashAdvance_status($cash_advance,'repaid');
            }
            if ($total_paid==$cash_advance->total_due){
                $cash_advance->status='repaid';
                $cash_advance->save();
                $this->cashAdvance_status($cash_advance,'repaid completely');
            }
            $cash_advance->save();
        }
        Session::flash('message', 'Payment submitted to '. $payment->payment_method . '.' );
        return redirect()->route('payroll.payroll.'.$request->from);
    }
    public function sendSummary(Request $request)
    {
        $id=$request->id;
        $payment=Payments::where('id',$id)->first();
        $payment->payment_summary='Sent';
        $payment->save();
        $mailto=$payment->worker()->email_main;
        Mail::to($mailto)->send(new PaymentSummaryWorker($payment));

        return 'success';
    }

    public function cashAdvance_status($cash_advance,$status){
        $file='cash_advance/'.$cash_advance->id.'.pdf';
        $data['document']='cash_advance';
        $data['cash_advance']=$cash_advance;
        $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
        $report=$pdf->output();
        Storage::put($file,$report);

        $mailto=$cash_advance->worker()->email_main;
        Mail::to($mailto)->send(new CashAdvanceStatusWorker($cash_advance,$status));
    }
    
    public function remove(Request $request)
    {
        $id=$request->payment_id;
        $from=$request->from;
        $payment=Payments::where('id',$id)->first();
        foreach ($payment->lines() as $line) {
            $line->delete();
        }
        $payment->delete();

        Session::flash('message', "This payment was deleted successfully.");
        return redirect()->route('payroll.payroll.'.$from);
    }

    public function edit(Request $request, $id)
    {
        $payment=Payments::where('id',$id)->first();
        $from=$request->from;
        if (!$payment) {
            Session::flash('message', "This payment was deleted.");
            return redirect()->route('payroll.payroll.'.$from);
        }
        
        $client=$payment->client();
        $worker=$payment->worker();
        $timecard=$payment->timecard();
        $data=[
            'payment'=>$payment,
            'client'=>$client,
            'worker'=>$worker,
            'timecard'=>$timecard,
            'from'=>$from,
        ];
        return view('payroll.payroll.edit',$data);
    }

    public function update(Request $request)
    {
        $from=$request->from;
        $payment=Payments::where('id',$request->payment_id)->first();
        if (!$payment) {
            Session::flash('message', "This payment was deleted.");
            return redirect()->route('payroll.payroll.'.$from);
        }
        $payment->amount_updated=$request->amount_updated;
        $payment->save();
        $client=$payment->client();
        $worker=$payment->worker();

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
            }
        }
        if (count($lines)==0) {
            Session::flash('message', "You did not add payment lines. Please add them.");
            return redirect()->route('payroll.payment.edit', ['id'=>$payment->id,'from'=>$from]);
        }

        $BD=Globals::first();
        if ($payment->payment_type=='bi-weekly') {
            $timecard=$payment->timecard();
            $start_date=date('D, m/d/y',strtotime($timecard->start_date));
            $end_date=date('D, m/d/y',strtotime($timecard->end_date));

            $total_work_time=0;
            $total_pto_time=0;
            $total_holiday_time=0;
            $timesheets=array();
            if (isset($request->work_time_hours)) {
                foreach ($request->work_time_hours as $key => $value) {
                    $timesheet=$timecard->timesheets()->where('id',$key)->first();
                    $work_time_hours=$request->work_time_hours[$key] =='0' ? null:$request->work_time_hours[$key];
                    $work_time_minutes=$request->work_time_minutes[$key]=='0' ? null:$request->work_time_minutes[$key];
                    $pto_time_hours=$request->pto_time_hours[$key]=='0' ? null:$request->pto_time_hours[$key];
                    $holiday_time_hours=$request->holiday_time_hours[$key]=='0' ? null:$request->holiday_time_hours[$key];
                    $total_work_time+=($work_time_hours+$work_time_minutes/60);
                    $total_pto_time+=$pto_time_hours;
                    $total_holiday_time+=$holiday_time_hours;
                    $timesheet['id']=$timesheet->id;
                    $timesheet['date']=$timesheet->date;
                    $timesheet['work_time_hours']=$work_time_hours;
                    $timesheet['work_time_minutes']=$work_time_minutes;
                    $timesheet['pto_time_hours']=$pto_time_hours;
                    $timesheet['holiday_time_hours']=$holiday_time_hours;
                    $timesheets[]=$timesheet;
                }
            }

            $updated_timecard['timesheets'] = $timesheets;
            $updated_timecard['total_work_time'] = $total_work_time;
            $updated_timecard['total_pto_time'] = $total_pto_time;
            $updated_timecard['total_holiday_time'] = $total_holiday_time;
            $updated_timecard['start_date'] = $start_date;
            $updated_timecard['end_date'] = $end_date;
            $updated_timecard['id'] = $timecard->id;

            // update items_updated
            $items['lines'] = $lines;
            $items['timecard']= $updated_timecard;
            $payment->items_updated = json_encode($items);
            $payment->save();
            ////////////////////////

            $updated_timecard['worker'] = $worker;
            $updated_timecard['client'] = $client;
            



            $file='payment_summary/bi_weekly/'.$payment->id.'.pdf';
            $payment->payment_summary_report_file=$file;

            $data['document']='payment_summary_biweekly';
            $data['BD']=$BD;
            $data['total_amount']=$payment->amount_updated;
            $data['start_date']=$start_date;
            $data['end_date']=$end_date;
            $data['worker']=$worker;
            $data['lines']=$lines;
            $data['client']=$client;
            
            $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
            $report=$pdf->output();

            $data['document']='timecard_edit';
            $data['timecard']=$updated_timecard;
            $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
            $report_back=$pdf->output();
            $m=new Merger();
            $m->addRaw($report);
            $m->addRaw($report_back);
            $summary_report=$m->merge();
            Storage::put($file,$summary_report);
        } else {
            $file='payment_summary/immediate/'.$payment->id.'.pdf';
            $payment->payment_summary_report_file=$file;
            $data['document']='payment_summary_immediate';
            $data['BD']=$BD;
            $data['now_date']=date('D, m/d/y',strtotime($payment->date_queue.' 00:00:00'));
            $data['worker']=$worker;
            $data['lines']=$lines;

            // update items_updated
            $items['lines'] = $lines;
            $items['timecard']= null;
            $payment->items_updated = json_encode($items);
            $payment->save();
            ///////////////////////

            $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
            $summary_report=$pdf->output();
            Storage::put($file,$summary_report);
        }

        Session::flash('message', "This payment summary was updated successfully.");
        return redirect()->route('payroll.payroll.'.$from);
    }
    
}
