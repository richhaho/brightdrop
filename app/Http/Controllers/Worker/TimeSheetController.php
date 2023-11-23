<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Clients;
use App\Workers;
use App\TimeSheets;
use App\TimeCards;
use App\ClientInfoWorkers;
use App\Reimbursement;
use App\OneTimeAdjustments;
use App\RecurringAdjustments;
use App\CashAdvances;
use App\Globals;
use Auth;
use Carbon\Carbon;

use PDF;
use Storage;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;

use Mail;
use App\Mail\TimecardEndedWorker;
use App\Mail\TimecardSubmittedWorker;

class TimeSheetController extends Controller
{
    
    public function logTime()
    {
        $hms=' 12:00:00 ';
        // $worker=Auth::user()->worker();
        // $timecards=$worker->timecards()->where('status','logtime')->get();
        // $now=date('Y-m-d',strtotime(Carbon::now()));
        // foreach ($timecards as $timecard) {
        //     $period_date=date('Y-m-d',strtotime($timecard->end_date.$hms.'+1days'));
        //     if ($period_date<=$now){
        //         $timecard->status='pending_worker';
        //         $timecard->save();
        //         $client=$timecard->client();
        //         $next_enddate=date('Y-m-d',strtotime($timecard->end_date.$hms.'+14days'));
        //         $client->billing_cycle_next_end_date=$next_enddate;
        //         $client->save();
        //         $mailto=$timecard->worker()->email_main;
        //         Mail::to($mailto)->send(new TimecardEndedWorker($timecard));
        //     }
        // }

        $worker=Auth::user()->worker();
        $clients=$worker->clients();
        $inactived_clients = array();
        foreach ($clients as $client) {
            $clientinfo = $client->assigned_worker_info()->where('workers_id', $worker->id)->first();
            if ($clientinfo->status=='inactive') {
                $inactived_clients[]=$client->id;
                continue;
            }
            $timecard=TimeCards::where('workers_id',$worker->id)->where('clients_id',$client->id)->where('end_date',$client->billing_cycle_next_end_date)->first();
            if (count($timecard)==0) {
                $timecard=TimeCards::create();
                $timecard->workers_id=$worker->id;
                $timecard->clients_id=$client->id;
                $timecard->status='logtime';
                $end_date=$client->billing_cycle_next_end_date;
                $start_date=date('Y-m-d',strtotime($end_date.$hms.'-13days'));
                if ($client->billing_cycle_type == 'semi-monthly') {
                    $start_date=date('Y-m-16',strtotime($end_date.$hms));
                    if (date('d',strtotime($end_date.$hms)) == '15') {
                        $start_date=date('Y-m-01',strtotime($end_date.$hms));
                    }
                }
                $timecard->start_date=$start_date;
                $timecard->end_date=$end_date;
                
                $timecard->total_work_time=0;
                $timecard->total_pto_time=0;
                $timecard->total_holiday_time=0;
                $timecard->save();
                 
                for ($d=0;$d<16;$d++){
                    $each_date = date('Y-m-d',strtotime($start_date.$hms.'+'.$d.'days')); 
                    if ($each_date > $end_date) break;
                    $timesheet=TimeSheets::create();
                    $timesheet->clients_id=$client->id;
                    $timesheet->workers_id=$worker->id;
                    $timesheet->date=$each_date;
                    $timesheet->day=date('l',strtotime($start_date.$hms.'+'.$d.'days'));
                    $timesheet->work_time_hours=null;
                    $timesheet->pto_time_hours=null;
                    $timesheet->holiday_time_hours=null;

                    $timesheet->work_time_minutes=null;

                    $timesheet->notes='';
                    $timesheet->status='logtime';
                    $timesheet->time_cards_id=$timecard->id;
                    $timesheet->save();
                    
                    if ($each_date == $end_date) break;

                }
                $timecard->save();
            }
        }
          
        $timecards=$worker->timecards()->whereNotIn('clients_id', $inactived_clients)->where('status','logtime')->get();
        $data=[
            'worker'=>$worker,
            'timecards'=>$timecards,
        ];
        return view('worker.logTime',$data);
    }
    public function storeTime(Request $request)
    {
         
        $worker=Auth::user()->worker();
        $timecard=TimeCards::where('id',$request->timecard_id)->first();
        
        $total_work_time=0;
        $total_pto_time=0;
        $worker_info=\App\ClientInfoWorkers::where('workers_id',$timecard->workers_id)->where('clients_id',$timecard->clients_id)->first();
        
        //*** check if PTO is enough
        $client=$timecard->client();
        $Year=date('Y',strtotime(Carbon::now())).'-%';
        $pto_sum=$worker->ptoHoursUsedForYear($timecard->clients_id, $Year);

        $currentyear=date('Y',strtotime(Carbon::now()));
        $hiredyear=date('Y',strtotime($worker_info->hired_at));
        if ($currentyear==$hiredyear){
            $pto_days_worker_default=$worker_info->ptodays_current_calendar;
        }else{
            $pto_days_worker_default=$worker_info->ptodays_full_calendar;
        }
        //if ($pto_days_worker_default==0 && $worker_info->ptodays_full_calendar==0) $pto_days_worker_default=$client->default_pto_days;

        foreach ($request->day as $key => $value) {
            $total_pto_time+=$request->pto_time_hours[$key];
        }
        if ($client->pto_infomation!='yes' || $pto_days_worker_default==0) {
            if ($total_pto_time>0){
                Session::flash('message', 'Your time card has some PTO hours that client did not set. You can not use PTO time for this client.');  
                return redirect()->route('worker.logtime');
            }
        }
        $pto_remain=$pto_days_worker_default*8-$pto_sum;
        if ($pto_remain<$total_pto_time && $total_pto_time>0){
            Session::flash('message', 'You only have '.$pto_remain.' hours PTO remaining, but you submitted '.$total_pto_time.' hours. Please resubmit hours after adjusting the PTO requested on your time card.'); 
                return redirect()->route('worker.logtime');
        }
        $total_work_time=0;
        $total_pto_time=0;
        //***************************

        foreach ($request->day as $key => $value) {
             $timesheet=TimeSheets::where('id',$key)->first();
                $timesheet->work_time_hours=$request->work_time_hours[$key] =='0' ? null:$request->work_time_hours[$key];
                $timesheet->pto_time_hours=$request->pto_time_hours[$key]=='0' ? null:$request->pto_time_hours[$key];
                 

                $timesheet->work_time_minutes=$request->work_time_minutes[$key]=='0' ? null:$request->work_time_minutes[$key];
                
                $timesheet->notes=$request->notes[$key];
                $holiday=\App\HolidaySchedule::where('holiday_date',$timesheet->date)->where('clients_id',$timecard->clients_id)->first();
                $timesheet->holiday_time_hours=null;
                if ($holiday) {
                    $timesheet->holiday_time_hours=$worker_info->target_hours_week/5;
                    $timesheet->save();
                }
                $timesheet->status='logtime';
                $timesheet->save();
                $total_work_time+=($timesheet->work_time_hours+$timesheet->work_time_minutes/60);
                $total_pto_time+=$timesheet->pto_time_hours;
                 
        }
        $timecard->total_work_time=round($total_work_time*100)/100;
        $timecard->total_pto_time=$total_pto_time;
        $timecard->save();
        $timecard->total_holiday_time=$timecard->timesheets()->sum('holiday_time_hours');
        $timecard->save();

        Session::flash('message', 'Your time card has been updated.');  
        return redirect()->route('worker.logtime');
    }

    public function needsApproval()
    {
        $worker=Auth::user()->worker();
        $timecards=$worker->timecards()->where('status','pending_worker')->get();
        $data=[
            'worker'=>$worker,
            'timecards'=>$timecards,
        ];
        return view('worker.needsApproval',$data);
    }
    public function submitNeedsApproval(Request $request)
    {
        $worker=Auth::user()->worker();
        $timecard=TimeCards::where('id',$request->timecard_id)->first();
       
        $total_work_time=0;
        $total_pto_time=0;
        $worker_info=\App\ClientInfoWorkers::where('workers_id',$timecard->workers_id)->where('clients_id',$timecard->clients_id)->first();
        //*** check if PTO is enough
        $client=$timecard->client();
        $Year=date('Y',strtotime(Carbon::now())).'-%';
        $pto_sum=$worker->ptoHoursUsedForYear($timecard->clients_id, $Year);

        $currentyear=date('Y',strtotime(Carbon::now()));
        $hiredyear=date('Y',strtotime($worker_info->hired_at));
        if ($currentyear==$hiredyear){
            $pto_days_worker_default=$worker_info->ptodays_current_calendar;
        }else{
            $pto_days_worker_default=$worker_info->ptodays_full_calendar;
        }
        //if ($pto_days_worker_default==0 && $worker_info->ptodays_full_calendar==0) $pto_days_worker_default=$client->default_pto_days;

        foreach ($request->day as $key => $value) {
            $total_pto_time+=$request->pto_time_hours[$key];
        }
        if ($client->pto_infomation!='yes' || $pto_days_worker_default==0) {
            if ($total_pto_time>0){
                Session::flash('message', 'Your time card has some PTO hours that client did not set. You can not use PTO time for this client.');  
                return redirect()->route('worker.needsApproval');
            }
        }
        $pto_remain=$pto_days_worker_default*8-$pto_sum;
        if ($pto_remain<$total_pto_time && $total_pto_time>0){
            Session::flash('message', 'You only have '.$pto_remain.' hours PTO remaining, but you submitted '.$total_pto_time.' hours. Please resubmit hours after adjusting the PTO requested on your time card.'); 
                return redirect()->route('worker.needsApproval');
        }
        $total_work_time=0;
        $total_pto_time=0;
        //***************************
        foreach ($request->day as $key => $value) {
             $timesheet=TimeSheets::where('id',$key)->first();
                $timesheet->work_time_hours=$request->work_time_hours[$key] =='0' ? null:$request->work_time_hours[$key];
                $timesheet->pto_time_hours=$request->pto_time_hours[$key]=='0' ? null:$request->pto_time_hours[$key];
                 

                $timesheet->work_time_minutes=$request->work_time_minutes[$key]=='0' ? null:$request->work_time_minutes[$key];
                
                $timesheet->notes=$request->notes[$key];
                $timesheet->status='needs_approval';
                $timesheet->save();
                $holiday=\App\HolidaySchedule::where('holiday_date',$timesheet->date)->where('clients_id',$timecard->clients_id)->first();
                $timesheet->holiday_time_hours=null;
                if ($holiday) {
                    $timesheet->holiday_time_hours=$worker_info->target_hours_week/5;
                    $timesheet->save();
                }
                $total_work_time+=($timesheet->work_time_hours+$timesheet->work_time_minutes/60);
                $total_pto_time+=$timesheet->pto_time_hours ;
                 
        }
        $timecard->total_work_time=round($total_work_time*100)/100;
        $timecard->total_pto_time=$total_pto_time;
        $timecard->status='needs_approval';
        $timecard->handle_date=date('Y-m-d');
        $timecard->save();
        $timecard->total_holiday_time=$timecard->timesheets()->sum('holiday_time_hours');
        $timecard->save();

        
        $this->generatePDF_Timesheet($timecard);

        Session::flash('message', 'Your time card was submitted successfully.');
        return redirect()->route('worker.needsApproval');
    }















   


    //============== Time sheet submite
    public function generatePDF_Timesheet($timecard){
        $BD=Globals::first();
        $currency['usd']=1;
        $currency['php']=$BD->php_usd;
        $currency['mxn']=$BD->mxn_usd;

        $info=ClientInfoWorkers::where('workers_id',$timecard->workers_id)->where('clients_id',$timecard->clients_id)->first();
        
        $start_date=date('D, m/d/y',strtotime($timecard->start_date));
        $end_date=date('D, m/d/y',strtotime($timecard->end_date));
        $worker=$timecard->worker();
        $client=$timecard->client();
        
        $total_work_time=$timecard->total_work_time;
         
        $total_time=$total_work_time;
        //$regular_time=$info->target_hours_week*2;
        $pto_hours=$client->pto_infomation=='yes' ? $timecard->total_pto_time:0;
        $holiday=$client->holiday_shedule_offered=='yes_paid' ? $timecard->total_holiday_time:0;
        $regular_time=$client->overtime_pay_provided=='yes' ? (80-$pto_hours-$holiday) : $total_work_time;

        $lines=array();$total_amount=0;
        //******** 1. Regular Pay line ************                
            $line['service_id']='0105';
            $line['description']='Regular Pay';
            $line['quantity_hours']=$total_time>$regular_time ? $regular_time:$total_time;
            $line['rate']=$info->worker_pay_houly_rate_regular;
            $line['amount']=$line['quantity_hours']*$line['rate'];
            $total_amount+=$line['amount'];
            $lines[]=$line;

        //******** 2. Overtime Pay line ************                
            if($total_time>$regular_time && $client->overtime_pay_provided=='yes'){
                $line['service_id']='0110';
                $line['description']='Overtime Pay';
                $line['quantity_hours']=$total_time-$regular_time;
                $line['rate']=$info->worker_pay_houly_rate_overtime;
                $line['amount']=$line['quantity_hours']*$line['rate'];
                $total_amount+=$line['amount'];
                $lines[]=$line;
            }

        //******** 3. PTO Pay ************  
            if($pto_hours>0 && $client->pto_infomation=='yes'){
                $line['service_id']='0150';
                $line['description']='PTO Pay';
                $line['quantity_hours']=$pto_hours;
                $line['rate']=$info->worker_pto_hourly_rate;
                $line['amount']=$line['quantity_hours']*$line['rate'];
                $total_amount+=$line['amount'];
                $lines[]=$line;
            }

        //******** 4. Holiday Pay ************  
         
            if($holiday>0 && $client->holiday_shedule_offered=='yes_paid'){

                $line['service_id']='0175';
                $line['description']='Holiday Pay';
                $line['quantity_hours']=$holiday;
                $line['rate']=$info->worker_holiday_hourly_rate;
                $line['amount']=$line['quantity_hours']*$line['rate'];
                $total_amount+=$line['amount'];
                $lines[]=$line;
            }

        //******** 5. Adjustment One time ************  
        $adjustments=OneTimeAdjustments::where('clients_id',$client->id)->where('billto','Worker')->where('workers_id',$worker->id)->where('adjustment_date','<=',$timecard->end_date)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('type','Time Adjustment')->get();

            foreach($adjustments as $adjustment){

                $description=$adjustment->type.' - '.date('m/d/y',strtotime($adjustment->adjustment_date));
                
                $rate=$info->worker_pay_houly_rate_regular;
                if ($adjustment->rate=="Overtime") $rate=$info->worker_pay_houly_rate_overtime;
                if ($adjustment->rate=="Percent-Other") $rate=round($info->worker_pay_houly_rate_regular*(1+$adjustment->percent_other/100)*100)/100;

                $line['service_id']='0280';
                $line['description']='Adjustment: '.$description;
                $line['quantity_hours']=-$adjustment->adjustment_total_hours;;
                $line['rate']=$rate;
                $line['amount']=$line['quantity_hours']*$line['rate'];
                $total_amount+=$line['amount'];
                $lines[]=$line;
            }
            $adjustments=OneTimeAdjustments::where('clients_id',$client->id)->where('billto','Worker')->where('workers_id',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('type','!=','Time Adjustment')->get();

            foreach($adjustments as $adjustment){
                $description=$adjustment->other_description;
                $line['service_id']='0290';
                $line['description']='Adjustment: '.$description;
                $line['quantity_hours']=-1;
                $line['rate']=round($adjustment->other_amount*$currency[$info->currency_type]/$currency[$adjustment->other_currency]*100)/100;
                $line['amount']=$line['quantity_hours']*$line['rate'];
                $total_amount+=$line['amount'];
                $lines[]=$line;
            }


        //******** 6. Adjustment Recurring ************  
        $adjustments=RecurringAdjustments::where('clients_id',$client->id)->where('payto','Worker')->where('paytoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->get();
            foreach($adjustments as $adjustment){
                $description=$adjustment->description;
                $line['service_id']='0380';
                $line['description']='Recurring Adjustment: '.$description;;
                $line['quantity_hours']=1;
                $line['rate']=round($adjustment->amount*$currency[$info->currency_type]/$currency[$adjustment->currency_type]*100/100);
                $line['amount']=$line['quantity_hours']*$line['rate'];
                $total_amount+=$line['amount'];
                $lines[]=$line;
            }
        $adjustments=RecurringAdjustments::where('clients_id',$client->id)->where('billto','Worker')->where('billtoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->get();
            foreach($adjustments as $adjustment){    
                $description=$adjustment->description;
                $line['service_id']='0380';
                $line['description']='Recurring Adjustment: '.$description;;
                $line['quantity_hours']=-1;
                $line['rate']=round($adjustment->amount*$currency[$info->currency_type]/$currency[$adjustment->currency_type]*100/100);
                $line['amount']=$line['quantity_hours']*$line['rate'];
                $total_amount+=$line['amount'];
                $lines[]=$line;
            }

        //******** 7. Cash Advance Repayment ************  
        $cashAdvances=CashAdvances::where('workers_id',$worker->id)->where('status','Paid')->where('payroll_managers_id','!=',null)->get();
            foreach($cashAdvances as $cashAdvance){
                foreach (unserialize($cashAdvance->open_cash_advances) as $advance) {
                    if($advance['due_date']>=$timecard->start_date && $advance['due_date']<=$timecard->end_date && $advance['payments_id']==0){

                        $line['service_id']='0405';
                        $line['description']='Cash Advance Repayment: '.$cashAdvance->description.' (Pmt '.$advance['payment_number'].' of '.count(unserialize($cashAdvance->open_cash_advances)).')';
                        $line['quantity_hours']=1;
                        $line['rate']=round($advance['amount']*$currency[$info->currency_type]/$currency[strtolower($advance['currency'])]*100)/100;
                        $line['amount']=$line['quantity_hours']*$line['rate'];
                        $total_amount+=$line['amount'];
                        $lines[]=$line;

                        
                    } 
                    
                }
                 
            }
        $file='timecard/'.$timecard->id.'.pdf'; 
        $data['document']='payment_summary_biweekly';
        $data['BD']=$BD;
        $data['total_amount']=$total_amount;
        $data['start_date']=$start_date;
        $data['end_date']=$end_date;
        $data['worker']=$worker;
        $data['lines']=$lines;
        $data['client']=$client;
        $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
        $report=$pdf->output();

        $data['document']='timecard';
        $data['timecard']=$timecard;
        $pdf=PDF::loadView('pdf.pdf_document',$data)->setPaper('Letter');
        $report_back=$pdf->output();
        $m=new Merger();
        $m->addRaw($report);
        $m->addRaw($report_back);
        $summary_report=$m->merge();
        Storage::put($file,$summary_report);


        $mailto=$timecard->worker()->email_main;
        Mail::to($mailto)->send(new TimecardSubmittedWorker($timecard,'submit'));
         
    }





}
