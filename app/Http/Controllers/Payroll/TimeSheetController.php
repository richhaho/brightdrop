<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Clients;
use App\Workers;
use App\ClientInfoWorkers;
use App\TimeSheets;
use App\TimeCards;
use App\Payments;
use Auth;
use PDF;
use Storage;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use Response;
use App\Reimbursement;
use App\OneTimeAdjustments;
use App\RecurringAdjustments;
use App\CashAdvances;
use App\PaymentLines;
use App\Globals;
use App\InvoiceLines;
use App\Invoices;

use Carbon\Carbon;
use Mail;
use App\Mail\TimecardApprovedWorker;
use App\Mail\CashAdvanceStatusWorker;

class TimeSheetController extends Controller
{
    public function getTimecardsCountDot($timecardCount) {
        $dot = '&#8858;';
        if ($timecardCount > 0 && $timecardCount < 21) {
            $dot = '&#93' . (11 + $timecardCount) .';';
        } elseif ($timecardCount > 20 && $timecardCount < 36) {
            $dot = '&#128' . (60 + $timecardCount) .';';
        } elseif ($timecardCount > 35) {
            $dot = '&#129' . (41 + $timecardCount) .';';
        }
        return $dot;
    }

    public function pendingWorkerApproval()
    {
        $search_clients=[''=>'', 'all' => 'All'];
        $allClients = Clients::where('deleted_at',null)->where('status', 'active')->orderBy('client_name', 'ASC')->get();
        foreach ($allClients as $client) {
            $search_clients[$client->id] = $client->client_name . '&nbsp; - &nbsp; (' . ($client->pendingWorkerApprovalTimecardsCount()). ')';
        }

        $clients_id = session('pendingWorkerApproval.clients_id');
        $workers_id = session('pendingWorkerApproval.workers_id');
        $search_workers = [''=>'', 'all' => 'All'];
        if ($clients_id) {
            $clients = $clients_id == 'all' ? $allClients : [Clients::where('id',$clients_id)->first()];
            foreach ($clients as $client) {
                foreach ($client->assignedWorkers() as $worker) {
                    $search_workers[$worker->id] = $worker->fullnameWithDot($clients_id, 'pending_worker');
                }
            }
        }

        $timecards=TimeCards::where('status','pending_worker');
        if ($clients_id == 'all') {
            $timecards=$timecards->whereIn('clients_id',array_keys($search_clients));
        } else {
            $timecards=$timecards->where('clients_id',$clients_id);
        }
        if ($workers_id == 'all') {
            $timecards=$timecards->whereIn('workers_id',array_keys($search_workers));
        } else {
            $timecards=$timecards->where('workers_id',$workers_id);
        }

        $data=[
            'timecards'=>$timecards->orderBy('end_date', 'DESC')->get(),
            'search_clients'=>$search_clients,
            'search_workers'=>$search_workers,
            'clients_id'=>$clients_id,
            'workers_id'=>$workers_id
        ];
        return view('payroll.timesheet.pendingWorkerApproval',$data);
    }
    
    public function needsApproval()
    {
        $search_clients=[''=>'', 'all' => 'All'];
        $allClients = Clients::where('deleted_at',null)->where('status', 'active')->orderBy('client_name', 'ASC')->get();
        foreach ($allClients as $client) {
            $search_clients[$client->id] = $client->client_name . '&nbsp; - &nbsp; (' . ($client->needsApprovalTimecardsCount()). ')';
        }

        $clients_id = session('needsApproval.clients_id');
        $workers_id = session('needsApproval.workers_id');
        $search_workers = [''=>'', 'all' => 'All'];
        if ($clients_id) {
            $clients = $clients_id == 'all' ? $allClients : [Clients::where('id',$clients_id)->first()];
            foreach ($clients as $client) {
                foreach ($client->assignedWorkers() as $worker) {
                    $search_workers[$worker->id] = $worker->fullnameWithDot($clients_id, 'needs_approval');
                }
            }
        }
        $timecards=TimeCards::where('status','needs_approval');
        if ($clients_id == 'all') {
            $timecards=$timecards->whereIn('clients_id',array_keys($search_clients));
        } else {
            $timecards=$timecards->where('clients_id',$clients_id);
        }
        if ($workers_id == 'all') {
            $timecards=$timecards->whereIn('workers_id',array_keys($search_workers));
        } else {
            $timecards=$timecards->where('workers_id',$workers_id);
        }
        $data=[
            'timecards'=>$timecards->orderBy('end_date', 'DESC')->get(),
            'search_clients'=>$search_clients,
            'search_workers'=>$search_workers,
            'clients_id'=>$clients_id,
            'workers_id'=>$workers_id
        ];
        return view('payroll.timesheet.needsApproval',$data);
    }
    public function submitApprove(Request $request)
    {

        $timecard=TimeCards::where('id',$request->timecard_id)->first();
        $worker=$timecard->worker();
        $client=$timecard->client();
       
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
                return redirect()->route('payroll.timesheet.needsApproval');
            }
        }
        $pto_remain=$pto_days_worker_default*8-$pto_sum;
        if ($pto_remain<$total_pto_time && $total_pto_time>0){
            Session::flash('message', 'You only have '.$pto_remain.' hours PTO remaining, but you submitted '.$total_pto_time.' hours. Please resubmit hours after adjusting the PTO requested on your time card.'); 
                return redirect()->route('payroll.timesheet.needsApproval');
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
                if ($holiday && $client->holiday_shedule_offered!='no_holiday') {
                    $timesheet->holiday_time_hours=$worker_info->target_hours_week/5;
                    $timesheet->save();
                }
                $timesheet->status='approved';
                $timesheet->save();
                
                $total_work_time+=($timesheet->work_time_hours+$timesheet->work_time_minutes/60);
                $total_pto_time+=$timesheet->pto_time_hours;
        }
        $timecard->total_work_time=round($total_work_time*100)/100;
        $timecard->total_pto_time=$total_pto_time;
        $timecard->save();
        $timecard->total_holiday_time=$timecard->timesheets()->sum('holiday_time_hours');
        $timecard->status='approved';
        $timecard->save();

        // if ($timecard->total_work_time==0){
        //     foreach ($timecard->timesheets()->get() as $timesheet) {
        //         $timesheet->status='needs_approval';
        //         $timesheet->save();
        //     }
        //     $timecard->status='needs_approval';
        //     $timecard->save();
        //     Session::flash('message', $worker->fullname()."'s time card was not approved. Please log working time."); 
        //     return redirect()->route('payroll.timesheet.needsApproval');
        // }
        
        $this->sendEmailApprovedTimecard($timecard);    
        $this->movetoQueue_biWeeklyPay($timecard);    
        $this->movetoQueue_invoice($timecard);
        
        Session::flash('message', $worker->fullname()."'s time card was approved for client:".$client->client_name."."); 
        return redirect()->route('payroll.timesheet.needsApproval');
    }
    public function declineTimecard(Request $request)
    {
        $timecard=TimeCards::where('id',$request->id)->first();
        $timecard->status='pending_worker';
        $timecard->save();
        $timesheets=$timecard->timesheets()->get();
        foreach($timesheets as $timesheet){
            $timesheet->status='pending_worker';
            $timesheet->save();
        }
        Session::flash('message', 'The timesheet was declined.');
        return redirect()->route('payroll.timesheet.needsApproval');
    }

    public function current()
    {
        $search_clients=Clients::where('deleted_at',null)->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('','');

        $clients_id = session('currentTimesheets.clients_id');
        $workers_id = session('currentTimesheets.workers_id');
        $search_workers = [];
        if ($clients_id) {
            $client = Clients::where('id',$clients_id)->first();
            $search_workers = $client->activeWorkers()->pluck('fullname','id')->prepend('','');
        }
        $timecards=TimeCards::where('status','logtime')->where('clients_id',$clients_id);
        if ($workers_id) {
            $timecards=$timecards->where('workers_id', $workers_id);
        }
        $timecards=$timecards->where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'))->orderBy('clients_id')->get();
        $data=[
            'timecards'=>$timecards,
            'search_clients'=>$search_clients,
            'search_workers'=>$search_workers,
            'clients_id'=>$clients_id,
            'workers_id'=>$workers_id
        ];
        return view('payroll.timesheet.current',$data);
    }

    public function pastTimesheets()
    {
        $search_clients=Clients::where('deleted_at',null)->where('status', 'active')->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('','');

        $clients_id = session('pastTimesheets.clients_id');
        $workers_id = session('pastTimesheets.workers_id');
        
        $search_workers = [];
        if ($clients_id) {
            $client = Clients::where('id',$clients_id)->first();
            $search_workers = $client->activeWorkers()->pluck('fullname','id')->prepend('','');
        }


        $timecards=TimeCards::whereIn('status',['approved', 'Paid'])->where('clients_id',$clients_id)->where('workers_id', $workers_id)->orderBy('end_date', 'DESC')->get();
        $data=[
            'timecards'=>$timecards,
            'search_clients'=>$search_clients,
            'search_workers'=>$search_workers,
            'clients_id'=>$clients_id,
            'workers_id'=>$workers_id
        ];
        return view('payroll.timesheet.past',$data);
    }
    public function setfilter (Request $request) {
        $page = $request->page;
        session()->forget($page);
        if ($request->has('clients_id')) {
            if($request->clients_id) {
                session([$page.'.clients_id' => $request->clients_id]);
            }
        }
        if ($request->has('workers_id')) {
            if($request->workers_id) {
                session([$page.'.workers_id' => $request->workers_id]);
            }
        }
        return redirect()->route('payroll.timesheet.'.$page);
    }
    public function resetfilter (Request $request) {
        $page = $request->page;
        session()->forget($page);
        return redirect()->route('payroll.timesheet.'.$page);
    }

    public function submitCurrent(Request $request)
    {
        $timecard=TimeCards::where('id',$request->timecard_id)->first();
        $worker=$timecard->worker();
        $client=$timecard->client();
       
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
                return redirect()->route('payroll.timesheet.currentTimesheets');
            }
        }
        $pto_remain=$pto_days_worker_default*8-$pto_sum;
        if ($pto_remain<$total_pto_time && $total_pto_time>0){
            Session::flash('message', 'You only have '.$pto_remain.' hours PTO remaining, but you submitted '.$total_pto_time.' hours. Please resubmit hours after adjusting the PTO requested on your time card.');  
                return redirect()->route('payroll.timesheet.currentTimesheets');
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
                if ($holiday && $client->holiday_shedule_offered!='no_holiday') {
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

        Session::flash('message', $worker->fullname()."'s Pending Timesheet has been saved for client:".$client->client_name."."); 
        return redirect()->route('payroll.timesheet.currentTimesheets');
    }

    public function submitPendingWorkerApproval(Request $request)
    {
        $timecard=TimeCards::where('id',$request->timecard_id)->first();
        $worker=$timecard->worker();
        $client=$timecard->client();
       
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
                return redirect()->route('payroll.timesheet.pendingWorkerApproval');
            }
        }
        $pto_remain=$pto_days_worker_default*8-$pto_sum;
        if ($pto_remain<$total_pto_time && $total_pto_time>0){
            Session::flash('message', 'You only have '.$pto_remain.' hours PTO remaining, but you submitted '.$total_pto_time.' hours. Please resubmit hours after adjusting the PTO requested on your time card.');  
                return redirect()->route('payroll.timesheet.pendingWorkerApproval');
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
                if ($holiday && $client->holiday_shedule_offered!='no_holiday') {
                    $timesheet->holiday_time_hours=$worker_info->target_hours_week/5;
                    $timesheet->save();
                }
                $timesheet->status='approved';
                $timesheet->save();
                
                $total_work_time+=($timesheet->work_time_hours+$timesheet->work_time_minutes/60);
                $total_pto_time+=$timesheet->pto_time_hours;
        }
        $timecard->total_work_time=round($total_work_time*100)/100;
        $timecard->total_pto_time=$total_pto_time;
        $timecard->save();
        $timecard->total_holiday_time=$timecard->timesheets()->sum('holiday_time_hours');
        $timecard->status='approved';
        $timecard->save();

        // if ($timecard->total_work_time==0){
        //     foreach ($timecard->timesheets()->get() as $timesheet) {
        //         $timesheet->status='pending_worker';
        //         $timesheet->save();
        //     }
        //     $timecard->status='pending_worker';
        //     $timecard->save();
        //     Session::flash('message', $worker->fullname()."'s time card was not approved. Please log working time."); 
        //     return redirect()->route('payroll.timesheet.pendingWorkerApproval');
        // }

        $this->sendEmailApprovedTimecard($timecard);
        $this->movetoQueue_biWeeklyPay($timecard);
        $this->movetoQueue_invoice($timecard);

        Session::flash('message', $worker->fullname()."'s Pending Timesheet has been approved for client:".$client->client_name."."); 
        return redirect()->route('payroll.timesheet.pendingWorkerApproval');
    }


    public function sendEmailApprovedTimecard($timecard){
        $mailto=array();
        $mailto[]=$timecard->worker()->email_main;
        // $mailto[]=$timecard->worker()->email_veem;
        Mail::to($mailto)->send(new TimecardApprovedWorker($timecard,'Payroll Manager('.Auth::user()->payrollmanager()->fullname.')'));
    }









    public function movetoQueue_biWeeklyPay($timecard){
        $BD=Globals::first();
        $currency['usd']=1;
        $currency['php']=$BD->php_usd;
        $currency['mxn']=$BD->mxn_usd;

        $payment=Payments::where('id',$timecard->payments_id)->first();
        if (count($payment)==0) $payment=Payments::create();
        $timecard->payments_id=$payment->id;
        $timecard->save();
        $info=ClientInfoWorkers::where('workers_id',$timecard->workers_id)->where('clients_id',$timecard->clients_id)->first();
        // $info->worker_pto_hourly_rate=$info->worker_pto_hourly_rate ? $info->worker_pto_hourly_rate : $info->worker_pay_houly_rate_regular; 
        // $info->worker_holiday_hourly_rate=$info->worker_holiday_hourly_rate ? $info->worker_holiday_hourly_rate : $info->worker_pay_houly_rate_regular; 
        // $info->save();

        $payment->workers_id=$timecard->workers_id;
        $payment->clients_id=$timecard->clients_id;
        $payment->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        
        $payment->currency_type=$info->currency_type;

        $payment->payment_method='Veem';
        $payment->payment_type='bi-weekly';
        $payment->status='Pending';
        $file='payment_summary/bi_weekly/'.$payment->id.'.pdf';
        $payment->payment_summary_report_file=$file;
        $payment->save();
        $paymentlines=$payment->lines();
        foreach ($paymentlines as $paymentline) {
            $paymentline->delete();
        }

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
            $paymentline=PaymentLines::create();
            $paymentline->payments_id=$payment->id;
            $paymentline->service_id='0105';
            $paymentline->description='Regular Pay';
            $paymentline->quantity_hours=$total_time>$regular_time ? $regular_time:$total_time;
            $paymentline->rate=$info->worker_pay_houly_rate_regular;
            $paymentline->save();
            $paymentline->amount=$paymentline->quantity_hours *$paymentline->rate;
            $paymentline->save();
            $total_amount+=$paymentline->amount;

            $line['service_id']=$paymentline->service_id;
            $line['description']=$paymentline->description;
            $line['quantity_hours']=$paymentline->quantity_hours;
            $line['rate']=$paymentline->rate;
            $line['amount']=$paymentline->amount;
            $lines[]=$line;

        //******** 2. Overtime Pay line ************                
            if($total_time>$regular_time && $client->overtime_pay_provided=='yes'){
                $paymentline=PaymentLines::create();
                $paymentline->payments_id=$payment->id;
                $paymentline->service_id='0110';
                $paymentline->description='Overtime Pay';
                $paymentline->quantity_hours=$total_time-$regular_time;
                $paymentline->rate=$info->worker_pay_houly_rate_overtime;
                $paymentline->save();
                $paymentline->amount=$paymentline->rate*$paymentline->quantity_hours;
                $paymentline->save();
                $total_amount+=$paymentline->amount;

                $line['service_id']=$paymentline->service_id;
                $line['description']=$paymentline->description;
                $line['quantity_hours']=$paymentline->quantity_hours;
                $line['rate']=$paymentline->rate;
                $line['amount']=$paymentline->amount;
                $lines[]=$line;
            }

        //******** 3. PTO Pay ************  
         
            if($pto_hours>0 && $client->pto_infomation=='yes'){
                $paymentline=PaymentLines::create();
                $paymentline->payments_id=$payment->id;
                $paymentline->service_id='0150';
                $paymentline->description='PTO Pay';
                $paymentline->quantity_hours=$pto_hours;
                $paymentline->rate=$info->worker_pto_hourly_rate;
                $paymentline->save();
                $paymentline->amount=$paymentline->rate*$paymentline->quantity_hours;
                $paymentline->save();
                $total_amount+=$paymentline->amount;

                $line['service_id']=$paymentline->service_id;
                $line['description']=$paymentline->description;
                $line['quantity_hours']=$paymentline->quantity_hours;
                $line['rate']=$paymentline->rate;
                $line['amount']=$paymentline->amount;
                $lines[]=$line;
            }

        //******** 4. Holiday Pay ************  
         
            if($holiday>0 && $client->holiday_shedule_offered=='yes_paid'){
                $paymentline=PaymentLines::create();
                $paymentline->payments_id=$payment->id;
                $paymentline->service_id='0175';
                $paymentline->description='Holiday Pay';
                $paymentline->quantity_hours=$holiday;
                $paymentline->rate=$info->worker_holiday_hourly_rate;
                $paymentline->save();
                $paymentline->amount=$paymentline->rate*$paymentline->quantity_hours;
                $paymentline->save();
                $total_amount+=$paymentline->amount;

                $line['service_id']=$paymentline->service_id;
                $line['description']=$paymentline->description;
                $line['quantity_hours']=$paymentline->quantity_hours;
                $line['rate']=$paymentline->rate;
                $line['amount']=$paymentline->amount;
                $lines[]=$line;
            }

        //******** 5. Adjustment One time ************  
        $adjustments=OneTimeAdjustments::where('clients_id',$client->id)->where('billto','Worker')->where('workers_id',$worker->id)->where('adjustment_date','<=',$timecard->end_date)->where('payments_id',null)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('type','Time Adjustment')->get();

            foreach($adjustments as $adjustment){

                $paymentline=PaymentLines::create();
                $paymentline->payments_id=$payment->id;
                $paymentline->service_id='0280';
                
                $description=$adjustment->type.' - '.date('m/d/y',strtotime($adjustment->adjustment_date));
                $paymentline->quantity_hours=-$adjustment->adjustment_total_hours;
                
                $rate=$info->worker_pay_houly_rate_regular;
                if ($adjustment->rate=="Overtime") $rate=$info->worker_pay_houly_rate_overtime;
                if ($adjustment->rate=="Percent-Other") $rate=round($info->worker_pay_houly_rate_regular*(1+$adjustment->percent_other/100)*100)/100;

                $paymentline->rate=$rate;
                $paymentline->save();
                $paymentline->amount=$paymentline->rate*$paymentline->quantity_hours;
                
                $paymentline->description='Adjustment: '.$description;
                $paymentline->save();

                $adjustment->payments_id=$payment->id;
                $adjustment->save();
         
                $total_amount+=$paymentline->amount;

                $line['service_id']=$paymentline->service_id;
                $line['description']=$paymentline->description;
                $line['quantity_hours']=$paymentline->quantity_hours;
                $line['rate']=$paymentline->rate;
                $line['amount']=$paymentline->amount;
                $lines[]=$line;
            }
            $adjustments=OneTimeAdjustments::where('clients_id',$client->id)->where('billto','Worker')->where('workers_id',$worker->id)->where('payments_id',null)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('type','!=','Time Adjustment')->get();

            foreach($adjustments as $adjustment){

                $paymentline=PaymentLines::create();
                $paymentline->payments_id=$payment->id;
                $paymentline->service_id='0290';
                
                $description=$adjustment->other_description;
                $paymentline->quantity_hours=-1;
                $paymentline->rate=round($adjustment->other_amount*$currency[$info->currency_type]/$currency[$adjustment->other_currency]*100)/100;
                $paymentline->save();
                $paymentline->amount=-$paymentline->rate;
                
                $paymentline->description='Adjustment: '.$description;
                $paymentline->save();

                $adjustment->payments_id=$payment->id;
                $adjustment->save();
         
                $total_amount+=$paymentline->amount;

                $line['service_id']=$paymentline->service_id;
                $line['description']=$paymentline->description;
                $line['quantity_hours']=$paymentline->quantity_hours;
                $line['rate']=$paymentline->rate;
                $line['amount']=$paymentline->amount;
                $lines[]=$line;
            }


        //******** 6. Adjustment Recurring ************  
        $adjustments=RecurringAdjustments::where('clients_id',$client->id)->where('payto','Worker')->where('paytoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->get();
            foreach($adjustments as $adjustment){

                $paymentline=PaymentLines::create();
                $paymentline->payments_id=$payment->id;
                $paymentline->service_id='0380';
                
                $description=$adjustment->description;
                $paymentline->quantity_hours=1;
                $paymentline->rate=round($adjustment->amount*$currency[$info->currency_type]/$currency[$adjustment->currency_type]*100/100);
                $paymentline->save();
                $paymentline->amount=$paymentline->rate;
                 
                $paymentline->description='Recurring Adjustment: '.$description;
                $paymentline->save();

                $total_amount+=$paymentline->amount;

                $line['service_id']=$paymentline->service_id;
                $line['description']=$paymentline->description;
                $line['quantity_hours']=$paymentline->quantity_hours;
                $line['rate']=$paymentline->rate;
                $line['amount']=$paymentline->amount;
                $lines[]=$line;

                $newRecurring = $adjustment->replicate();
                $newRecurring->description = $adjustment->description.' in '.$start_date.'-'.$end_date;
                $newRecurring->status = 'Approved in billing cycle';
                $newRecurring->payments_id=$payment->id;
                $newRecurring->save();
            }
        $adjustments=RecurringAdjustments::where('clients_id',$client->id)->where('billto','Worker')->where('billtoworker',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->get();
            foreach($adjustments as $adjustment){
                $paymentline=PaymentLines::create();
                $paymentline->payments_id=$payment->id;
                $paymentline->service_id='0380';
                
                $description=$adjustment->description;
                $paymentline->quantity_hours=-1;
                $paymentline->rate=round($adjustment->amount*$currency[$info->currency_type]/$currency[$adjustment->currency_type]*100/100);
                $paymentline->save();
                $paymentline->amount=$paymentline->rate*$paymentline->quantity_hours;
                 
                $paymentline->description='Recurring Adjustment: '.$description;
                $paymentline->save();
         
                $total_amount+=$paymentline->amount;

                $line['service_id']=$paymentline->service_id;
                $line['description']=$paymentline->description;
                $line['quantity_hours']=$paymentline->quantity_hours;
                $line['rate']=$paymentline->rate;
                $line['amount']=$paymentline->amount;
                $lines[]=$line;

                $newRecurring = $adjustment->replicate();
                $newRecurring->description = $adjustment->description.' in '.$start_date.'-'.$end_date;
                $newRecurring->status = 'Approved in billing cycle';
                $newRecurring->payments_id=$payment->id;
                $newRecurring->save();
            }
        //******** 7. Cash Advance Repayment ************  
        $approved_cash_advances=array(); 
        $cashAdvances=CashAdvances::where('workers_id',$worker->id)->where('status','Paid')->where('payroll_managers_id','!=',null)->get();
            foreach($cashAdvances as $cashAdvance){
                $open_cash_advances=array();
                $repaid_approve_update=false;
                foreach (unserialize($cashAdvance->open_cash_advances) as $advance) {
                    if($advance['due_date']>=$timecard->start_date && $advance['due_date']<=$timecard->end_date && $advance['payments_id']==0){

                        $paymentline=PaymentLines::create();
                        $paymentline->payments_id=$payment->id;
                        $paymentline->service_id='0405';
                        
                        $paymentline->description='Cash Advance Repayment: '.$cashAdvance->description.' (Pmt '.$advance['payment_number'].' of '.count(unserialize($cashAdvance->open_cash_advances)).')';
                        $paymentline->quantity_hours=1;
                        $paymentline->rate=round($advance['amount']*$currency[$info->currency_type]/$currency[strtolower($advance['currency'])]*100)/100;
                        $paymentline->save();
                        $paymentline->amount=-$paymentline->rate;
                        $paymentline->save();
                 
                        $total_amount+=$paymentline->amount;

                        $line['service_id']=$paymentline->service_id;
                        $line['description']=$paymentline->description;
                        $line['quantity_hours']=$paymentline->quantity_hours;
                        $line['rate']=$paymentline->rate;
                        $line['amount']=$paymentline->amount;
                        $lines[]=$line;

                        $open_cash_advance['payment_number']=$advance['payment_number'];
                        $open_cash_advance['due_date']=$advance['due_date'];
                        $open_cash_advance['amount']=$advance['amount'];
                        $open_cash_advance['currency']=$advance['currency'];
                        $open_cash_advance['status']='none';
                        $open_cash_advance['payments_id']=$payment->id;
                        $repaid_approve_update=true;
                    }else{
                        $open_cash_advance['payment_number']=$advance['payment_number'];
                        $open_cash_advance['due_date']=$advance['due_date'];
                        $open_cash_advance['amount']=$advance['amount'];
                        $open_cash_advance['currency']=$advance['currency'];
                        $open_cash_advance['status']=$advance['status'];
                        $open_cash_advance['payments_id']=$advance['payments_id'];
                    }
                    $open_cash_advances[]=$open_cash_advance;
                }
                
                $cashAdvance->open_cash_advances=serialize($open_cash_advances);
                $cashAdvance->save();

                if($repaid_approve_update) $approved_cash_advances[]=$cashAdvance;
            }
        



        $payment->amount=$total_amount;
        $payment->save();
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


        foreach ($approved_cash_advances as $cashAdvance) {
            $completed_approve=true;
            foreach (unserialize($cashAdvance->open_cash_advances) as $advance) {
                    if($advance['payments_id']==0) $completed_approve=false;
            }
            if($completed_approve) {
                $this->cashAdvance_status($cashAdvance,'approved completely');
            }else{
                //$this->cashAdvance_status($cashAdvance,'approved');
            }
        }
         
    }


    public function movetoQueue_invoice($timecard){
        $client=$timecard->client();
        $activeWorkerIds = $client->assignedWorkers()->pluck('id')->toArray();
        $timecards=$client->timecards()->whereIn('workers_id', $activeWorkerIds)->where(function ($query){ $query->where('status','needs_approval')->orwhere('status','pending_worker');})->where('end_date',$timecard->end_date)->get();
        if (count($timecards)>0) return;
        $timecards=$client->timecards()->whereIn('status',['approved', 'paid'])->where('invoices_id',null)->where('end_date',$timecard->end_date)->get();
        if (count($timecards)==0) return;

        $BD=Globals::first();
        $currency['usd']=1;
        $currency['php']=$BD->php_usd;
        $currency['mxn']=$BD->mxn_usd;
        

        $invoice=Invoices::create();
         
        $info=ClientInfoWorkers::where('workers_id',$timecard->workers_id)->where('clients_id',$timecard->clients_id)->first();
        
        $workers_ids=array();
        $invoice->clients_id=$timecard->clients_id;
        $invoice->date_queue=date('Y-m-d',strtotime(Carbon::now()));
        $invoice->currency_type='usd'; //$info->currency_type;
        $invoice->payment_method=$client->payment_method;
        $invoice->invoice_method=$client->invoice_method;
        $invoice->account_managers_id=$client->account_managers_id;
        $invoice->billing_cycle_end_date=$timecard->end_date;
        $invoice->status='Needs Sent';
        // $invoice->invoice_number=str_pad($invoice->id,4,'0',STR_PAD_LEFT);;
        $invoice->invoice_number=11201+($invoice->id-1)*3;

        $file='invoice_pdf/invoices/'.$invoice->id.'.pdf';
        $invoice->invoice_report_file=$file;
        $invoice->save();
        $invoicelines=$invoice->lines();
        foreach ($invoicelines as $invoiceline) {
            $invoiceline->delete();
        }

        $start_date=date('D, m/d/y',strtotime($timecard->start_date));
        $end_date=date('D, m/d/y',strtotime($timecard->end_date));
        $invoiced_date=date('D, m/d/y',strtotime(Carbon::now()));
        $invoice_number=$invoice->invoice_number;
        $lines=array();$total_amount=0;

        
        foreach ($timecards as $timecard) {
            $timecard->invoices_id=$invoice->id;
            $timecard->save();
            $worker=$timecard->worker();
            $workers_ids[]=$worker->id;
            $info=ClientInfoWorkers::where('workers_id',$timecard->workers_id)->where('clients_id',$timecard->clients_id)->first();
            // $info->worker_pto_hourly_rate=$info->worker_pto_hourly_rate ? $info->worker_pto_hourly_rate : $info->worker_pay_houly_rate_regular; 
            // $info->worker_holiday_hourly_rate=$info->worker_holiday_hourly_rate ? $info->worker_holiday_hourly_rate : $info->worker_pay_houly_rate_regular; 
            // $info->save();
            $total_work_time=$timecard->total_work_time;
            
            $total_time=$total_work_time;
            //$regular_time=$info->target_hours_week*2;
            $pto_hours=$client->pto_infomation=='yes' ? $timecard->total_pto_time:0;
            $holiday=$client->holiday_shedule_offered=='yes_paid' ? $timecard->total_holiday_time:0;
            
            $ptoHours = $client->who_pays_pto=='client' ? $pto_hours : 0;
            $holidayHours = $client->who_pays_holiday=='client' ? $holiday : 0;
            $regular_time = $total_work_time;
            
            if ($client->overtime_pay_provided=='yes') {
                $regular_time = $client->include_PTO_in_overtime_invoice=='yes' ? 80 - $pto_hours : 80;
                $regular_time = $client->include_PH_in_overtime_invoice=='yes' ? $regular_time - $holiday : $regular_time;
            }

            $regular_rate=$info->client_billable_rate_regular;
            $overtime_rate=$info->client_billable_rate_overtime;
            $pto_rate=$regular_rate;//$info->worker_pto_hourly_rate;
            $holiday_rate=$regular_rate;//$info->worker_holiday_hourly_rate;

        //******** 1. Regular Pay line ************                
            $invoiceline=InvoiceLines::create();
            $invoiceline->invoices_id=$invoice->id;
            $invoiceline->service_id='0105';
            $invoiceline->description='Regular Hours - '.$worker->fullname;
            $invoiceline->quantity_hours=$total_time>$regular_time ? $regular_time:$total_time;
            $invoiceline->rate=$regular_rate;
            $invoiceline->save();
            $invoiceline->amount=$invoiceline->quantity_hours*$invoiceline->rate;
            $invoiceline->save();
            $total_amount+=$invoiceline->amount;

            $line['service_id']=$invoiceline->service_id;
            $line['description']=$invoiceline->description;
            $line['quantity_hours']=$invoiceline->quantity_hours;
            $line['rate']=$invoiceline->rate;
            $line['amount']=$invoiceline->amount;
            $lines[]=$line;

        //******** 2. Overtime Pay line ************                
            if($total_time>$regular_time && $client->overtime_pay_provided=='yes'){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0110';
                $invoiceline->description='Overtime Hours - '.$worker->fullname;
                $invoiceline->quantity_hours=$total_time-$regular_time;
                $invoiceline->rate=$overtime_rate;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate*$invoiceline->quantity_hours;
                $invoiceline->save();
                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;
            }
        //******** 3. PTO Pay ************  
        
            if($pto_hours>0 && $client->pto_infomation=='yes' && $client->who_pays_pto=='client'){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0150';
                $invoiceline->description='Paid Time Off - '.$worker->fullname;;
                $invoiceline->quantity_hours=$pto_hours;
                $invoiceline->rate=$pto_rate;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate*$invoiceline->quantity_hours;
                $invoiceline->save();
                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;
            }

        //******** 4. Holiday Pay ************  
        
            if($holiday>0 && $client->holiday_shedule_offered=='yes_paid' && $client->who_pays_holiday=='client'){
                $holidays=$client->holidays()->where('holiday_date','>=',$timecard->start_date)->where('holiday_date','<=',$timecard->end_date)->get();
                $holiday_dates='(';
                foreach ($holidays as $hd) {
                    if ($holiday_dates=='('){
                        $holiday_dates=$holiday_dates.''. date('m/d/y',strtotime($hd->holiday_date));
                    }else{
                        $holiday_dates=$holiday_dates.', '. date('m/d/y',strtotime($hd->holiday_date));
                    }
                }
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0175';
                $invoiceline->description='Paid Holiday '.$holiday_dates.') - '.$worker->fullname;
                $invoiceline->quantity_hours=$holiday;
                $invoiceline->rate=$holiday_rate;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate*$invoiceline->quantity_hours;
                $invoiceline->save();
                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;
            }
        }
        //******** 5. Worker Reimbursement: ************  
        // $workers=$client->workers();
        // foreach ($workers as $worker) {
        //     $reimbursements=Reimbursement::where('clients_id',$client->id)->where('workers_id',$worker->id)->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->get();
        //     foreach ($reimbursements as $reimbursement) {
        //         $invoiceline=InvoiceLines::create();
        //         $invoiceline->invoices_id=$invoice->id;
        //         $invoiceline->service_id=str_pad($invoiceline->id,6,'0',STR_PAD_LEFT);
        //         $description=($reimbursement->type=='Other' ? $reimbursement->other_type:$reimbursement->type);
        //         $invoiceline->description='Worker Reimbursement: '.$description.' - '.$worker->fullname;
        //         $invoiceline->quantity_hours=1;
        //         $invoiceline->rate=round($reimbursement->amount/$currency[$reimbursement->currency_type]*100)/100;
        //         $invoiceline->amount=$invoiceline->rate;
        //         $invoiceline->save();

        //         $reimbursement->invoices_id=$invoice->id;
        //         $reimbursement->save();

        //         $total_amount+=$invoiceline->amount;

        //         $line['service_id']=$invoiceline->service_id;
        //         $line['description']=$invoiceline->description;
        //         $line['quantity_hours']=$invoiceline->quantity_hours;
        //         $line['rate']=$invoiceline->rate;
        //         $line['amount']=$invoiceline->amount;
        //         $lines[]=$line;
        //     }
        // }
        //******** 6. Adjustment (One - Time): ************
        foreach ($timecards as $timecard) {
            $worker=$timecard->worker();
            $info=ClientInfoWorkers::where('workers_id',$timecard->workers_id)->where('clients_id',$timecard->clients_id)->first();
            
            $regular_rate=$info->client_billable_rate_regular;
            $overtime_rate=$info->client_billable_rate_overtime;
            
            $adjustments=OneTimeAdjustments::where('payto','Client')->where('clients_id',$client->id)->where('workers_id',$worker->id)->where('adjustment_date','<=',$timecard->end_date)->whereIn('status',['Approved','Paid'])->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','Time Adjustment')->get();
            foreach($adjustments as $adjustment){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0280';
                $description=$adjustment->type.' - '.$worker->fullname.'('.date('m/d/y',strtotime($adjustment->adjustment_date)).')';
                $invoiceline->quantity_hours=-$adjustment->adjustment_total_hours;
                $rate=$regular_rate;
                if ($adjustment->rate=="Overtime") $rate=$overtime_rate;
                if ($adjustment->rate=="Percent-Other") $rate=round($regular_rate*(1+$adjustment->percent_other/100)*100)/100;
                $invoiceline->rate=$rate;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate*$invoiceline->quantity_hours;
                $invoiceline->description='Adjustment (One-Time): '.$description;
                $invoiceline->save();
                $adjustment->invoices_id=$invoice->id;
                $adjustment->save();

                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;
            }
            $adjustments=OneTimeAdjustments::where('payto','Client')->where('clients_id',$client->id)->where('workers_id',$worker->id)->whereIn('status',['Approved','Paid'])->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','!=','Time Adjustment')->get();
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

                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;
            }

            $adjustments=OneTimeAdjustments::where('billto','Client')->where('clients_id',$client->id)->where('workers_id',$worker->id)->where('adjustment_date','<=',$timecard->end_date)->whereIn('status',['Approved','Paid'])->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','Time Adjustment')->get();
            foreach($adjustments as $adjustment){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0280';
                $description=$adjustment->type.' - '.$worker->fullname.'('.date('m/d/y',strtotime($adjustment->adjustment_date)).')';
                $invoiceline->quantity_hours=$adjustment->adjustment_total_hours;
                $rate=$regular_rate;
                if ($adjustment->rate=="Overtime") $rate=$overtime_rate;
                if ($adjustment->rate=="Percent-Other") $rate=round($regular_rate*(1+$adjustment->percent_other/100)*100)/100;
                $invoiceline->rate=$rate;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate*$invoiceline->quantity_hours;
                $invoiceline->description='Adjustment (One-Time): '.$description;
                $invoiceline->save();
                $adjustment->invoices_id=$invoice->id;
                $adjustment->save();

                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;
            }
            $adjustments=OneTimeAdjustments::where('billto','Client')->where('clients_id',$client->id)->where('workers_id',$worker->id)->whereIn('status',['Approved','Paid'])->where('payroll_managers_id','!=',null)->where('invoices_id',null)->where('type','!=','Time Adjustment')->get();
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

                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;
            }

        }

        //******** 7. Adjustment (Recurring): ************
            $adjustments=RecurringAdjustments::where('billtoclient',$client->id)->where('payto','BrightDrop')->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->get();
            
            foreach($adjustments as $adjustment){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0380';
                $description=$adjustment->description;
                $invoiceline->quantity_hours=1;
                $invoiceline->rate=round($adjustment->amount/$currency[$adjustment->currency_type]*100)/100;
                $invoiceline->save();
                $invoiceline->amount=$invoiceline->rate;
                 
                $invoiceline->description='Adjustment(Recurring): '.$description.' - BrightDrop';
                $invoiceline->save();

                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;

                $newRecurring = $adjustment->replicate();
                $newRecurring->description = $adjustment->description.' in '.$start_date.'-'.$end_date;
                $newRecurring->status = 'Approved in billing cycle';
                $newRecurring->invoices_id=$invoice->id;
                $newRecurring->save();
            }

            $adjustments=RecurringAdjustments::where('paytoclient',$client->id)->where('billto','BrightDrop')->where('status','Approved')->where('payroll_managers_id','!=',null)->where('invoices_id',null)->get();
            
            foreach($adjustments as $adjustment){
                $invoiceline=InvoiceLines::create();
                $invoiceline->invoices_id=$invoice->id;
                $invoiceline->service_id='0380';
                $description=$adjustment->description;
                $invoiceline->quantity_hours=-1;
                $invoiceline->rate=round($adjustment->amount/$currency[$adjustment->currency_type]*100)/100;
                $invoiceline->save();
                $invoiceline->amount=-$invoiceline->rate;
                 
                $invoiceline->description='Adjustment(Recurring): '.$description.' - BrightDrop';
                $invoiceline->save();

                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;

                $newRecurring = $adjustment->replicate();
                $newRecurring->description = $adjustment->description.' in '.$start_date.'-'.$end_date;
                $newRecurring->status = 'Approved in billing cycle';
                $newRecurring->invoices_id=$invoice->id;
                $newRecurring->save();
            }
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

                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;

                $newRecurring = $adjustment->replicate();
                $newRecurring->description = $adjustment->description.' in '.$start_date.'-'.$end_date;
                $newRecurring->status = 'Approved in billing cycle';
                $newRecurring->invoices_id=$invoice->id;
                $newRecurring->save();
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

                $total_amount+=$invoiceline->amount;

                $line['service_id']=$invoiceline->service_id;
                $line['description']=$invoiceline->description;
                $line['quantity_hours']=$invoiceline->quantity_hours;
                $line['rate']=$invoiceline->rate;
                $line['amount']=$invoiceline->amount;
                $lines[]=$line;

                $newRecurring = $adjustment->replicate();
                $newRecurring->description = $adjustment->description.' in '.$start_date.'-'.$end_date;
                $newRecurring->status = 'Approved in billing cycle';
                $newRecurring->invoices_id=$invoice->id;
                $newRecurring->save();
            }
        }        
                 

        //*******************************************************************
        $invoice->workers_ids=implode(",",$workers_ids);
        
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
        //$data['currency_type']=$invoice->currency_type;
        
        $data['invoiced_date']=$invoiced_date;
        $data['invoice_number']=$invoice_number;

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
        Storage::put($file,$summary_report);

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
}
