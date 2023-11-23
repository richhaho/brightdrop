<?php

namespace App\Http\Controllers\Account;

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
use App\PayrollManagers;
use App\Admins;
use App\HolidaySchedule;
use App\HolidayDefault;
use App\TimeCards;
use App\TimeSheets;
use App\Globals;
use Carbon\Carbon;
use Auth;


class ClientController extends Controller
{
    
    public function create()
    {
        $status=[
            'potential'=>'Potential Client',
            'active'=>'Active Client',
            'past'=>'Past Client',
        ];
        $country=[
            'US'=>'United States',
            'Other'=>'Other',
        ];
        $accountmanagers=AccountManagers::get()->pluck('fullname','id')->prepend('','');
        $industry=[
            "Accounting/Bookkeeping"=>"Accounting/Bookkeeping",
            "Auto Dealerships"=>"Auto Dealerships",
            "Banking"=>"Banking",
            "Business Broker"=>"Business Broker",
            "Catering"=>"Catering",
            "Directory Assistance"=>"Directory Assistance",
            "Insurance - Other"=>"Insurance - Other",
            "Insurance Sales"=>"Insurance Sales",
            "Law Firm"=>"Law Firm",
            "Medical - Dental"=>"Medical - Dental",
            "Medical - Doctor's Office"=>"Medical - Doctor's Office",
            "Medical - General"=>"Medical - General",
            "Medical - Records Collections"=>"Medical - Records Collections",
            "Medical - Records Review"=>"Medical - Records Review",
            "Mortgage Broker"=>"Mortgage Broker",
            "Plumbing/HVAC"=>"Plumbing/HVAC",
            "Professional Services - Other"=>"Professional Services - Other",
            "Real Estate - Commercial Sales"=>"Real Estate - Commercial Sales",
            "Real Estate - Residential Sales"=>"Real Estate - Residential Sales",
            "Real Estate - Staging"=>"Real Estate - Staging",
            "Software Development"=>"Software Development",
            "Technology - Other"=>"Technology - Other",
            "Trucking"=>"Trucking",
            "Other"=>"Other"
        ];
        $job_function=[
            'Answering Service'=>'Answering Service',
            'Call Center - Inbound General'=>'Call Center - Inbound General',
            'Call Center - Outbound General'=>'Call Center - Outbound General',
            'Cold Calling'=>'Cold Calling',
            'Data Entry'=>'Data Entry',
            'Directory Assistance'=>'Directory Assistance',
            'Dispatcher - Automotive'=>'Dispatcher - Automotive',
            'Executive Assistant'=>'Executive Assistant',
            'Graphic Design'=>'Graphic Design',
            'Inside Sales Associate'=>'Inside Sales Associate',
            'Insurance Verification'=>'Insurance Verification',
            'Legal - Legal Secretary'=>'Legal - Legal Secretary',
            'Legal - Paralegal'=>'Legal - Paralegal',
            'Medical Records Collections'=>'Medical Records Collections',
            'Sales Assistant'=>'Sales Assistant',
            'Website Development'=>'Website Development',
            'Other'=>'Other'
        ];

        $global_values=Globals::first()->values;
        if (unserialize($global_values)){
            foreach (unserialize($global_values) as $other){
                if (strnatcasecmp($other['fieldname'],'Client Industry')==0){
                    $industry=array();
                    $industry['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $industry[$value]=$value;
                    }
                }

                if (strnatcasecmp($other['fieldname'],'Job Function')==0){
                    $job_function=array();
                    //$job_function['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $job_function[$value]=$value;
                    }
                }
            }    
        }

        $lead_generated_by=[
            ''=>'',
            'Direct Contact - Business Development'=>'Direct Contact - Business Development',
            'Direct Contact - Other Internal'=>'Direct Contact - Other Internal',
            'Marketing Program - Craigslist'=>'Marketing Program - Craigslist',
            'Marketing Program - Indeed'=>'Marketing Program - Indeed',
            'Marketing Program - Other'=>'Marketing Program - Other',
            'Networking Event'=>'Networking Event',
            'Client Referral'=>'Client Referral',
            'Other'=>'Other'
        ];

        $payrolls=PayrollManagers::where('deleted_at',null)->get();
        $admins=Admins::where('deleted_at',null)->get();
        $direct_contact_internal_payroll_admin=array();
        $direct_contact_internal_payroll_admin['']='';
        foreach ($payrolls as $payroll) {
            $direct_contact_internal_payroll_admin['payroll_'.$payroll->id]=$payroll->fullname;
        }
        foreach ($admins as $admin) {
            $direct_contact_internal_payroll_admin['admin_'.$admin->id]=$admin->fullname;
        }
 
        $contacts_list=Contacts::where('deleted_at',null)->orderBy('last_name')->pluck('fullname','id');
         
        $contacts=Contacts::where('deleted_at',null)->get();
        $client_referral=array();
        foreach ($contacts as $contact) {
            $client_referral[$contact->id]=isset($contact->client()->client_name) ? $contact->last_name.', '.$contact->first_name.' ('.$contact->client()->client_name.')': $contact->last_name.', '.$contact->first_name;
        }
        //$contacts=$client->contacts()->get();
        $billing_cycle_next_end_date_adder=[
            '0'=>'0',
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',            
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
            '10'=>'10',
        ];
        $billing_cycle_next_end_date=Globals::first()->billing_cycle_end_date;
        $overtime_pay_provided=[
            'yes'=>'Yes',
            'no'=>'No',
        ];
 
        $lunchtime_billable=$overtime_pay_provided;
        $breaktime_billable=$overtime_pay_provided;
        $invoice_method=[
            'automatically'=>'System Generated',
            'manual'=>'Create Manually',
        ];
        $ACH_discount_participation=[
            'no'=>'No',
            'yes'=>'Yes',
        ];
        $payment_method=[
            'client_process_ach'=>'Client Process - ACH',
            'internal_process_ach'=>'Internal Process - ACH on file',
            'internal_process_cc'=>'Internal Process - Credit Card on file',
        ];
        $review_time=[
            'auto'=>'Auto Approve',
            '1'=>'1 business day',
            '2'=>'2 business days',
            '3'=>'3 business days',
            '4'=>'4 business days',            
            '5'=>'5 business days'
        ];
        $internal_processor=[
            'Quickbooks Online'=>'Quickbooks Online',
            'Square'=>'Square',
        ];
        $pto_infomation=$overtime_pay_provided;
        $who_pays_pto=[
            'brightdrop'=>'BrightDrop',
            'client'=>'Client',
        ];
        $default_pto_days=[
            '0'=>'0',
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',            
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
            '10'=>'10',
            '11'=>'11',
            '12'=>'12',
            '13'=>'13',
            '14'=>'14',            
            '15'=>'15',
            '16'=>'16',
            '17'=>'17',
            '18'=>'18',
            '19'=>'19',
            '20'=>'20',
        ];

        $holiday_shedule_offered= [
            'yes_paid'=>'Yes - Paid',
            'yes_unpaid'=>'Yes - Unpaid',
            'no_holiday'=>'No Holiday Schedule',
        ];
        $who_pays_holiday=[
            
            'brightdrop'=>'BrightDrop',
            'client'=>'Client',
        ];
 
         
        //$workers_list=Workers::where('deleted_at',null)->where('status','!=','disqualfied')->where('status','!=','not_available_hired')->orderBy('fullname')->get()->pluck('fullname','id');
        $workers_list=Workers::where('deleted_at',null)->orderBy('last_name')->get()->pluck('fullname','id');
         



        $admins_id=Auth::user()->accountmanager()->admin()->id;
        
        $current_year=date('Y',strtotime(Carbon::now()));
        $holiday_defaults=array();
        for ($year=$current_year;$year<$current_year+3;$year++){
            // $holiday_default=HolidayDefault::where('admins_id',$admins_id)->where('year',$year)->orderBy('holiday_date')->get();
            $holiday_default=HolidayDefault::where('year',$year)->orderBy('holiday_date')->get();
             
            $holiday['holidays']=$holiday_default;
            $holiday['year']=$year;
            $holiday_defaults[]=$holiday;
        }
        $data=[
            'country'=>$country,
            'status'=>$status,
            'business_development'=>$accountmanagers,
            'account_manager'=>$accountmanagers,
            'industry'=>$industry,
            'job_function'=>$job_function,
            'lead_generated_by'=>$lead_generated_by,
            'direct_contact_business_accountmanager'=>$accountmanagers,
            'direct_contact_internal_payroll_admin'=>$direct_contact_internal_payroll_admin,
            'client_referral'=>$client_referral,
            'billing_cycle_next_end_date_adder'=>$billing_cycle_next_end_date_adder,
            'billing_cycle_next_end_date'=>$billing_cycle_next_end_date,
            'overtime_pay_provided'=>$overtime_pay_provided,
            'lunchtime_billable'=>$lunchtime_billable,
            'breaktime_billable'=>$breaktime_billable,
            'invoice_method'=>$invoice_method,
            'ACH_discount_participation'=>$ACH_discount_participation,
            'payment_method'=>$payment_method,
            'review_time'=>$review_time,
            'internal_processor'=>$internal_processor,
            'pto_infomation'=>$pto_infomation,
            'who_pays_pto'=>$who_pays_pto,
            'default_pto_days'=>$default_pto_days,
            'holiday_shedule_offered'=>$holiday_shedule_offered,
            'who_pays_holiday'=>$who_pays_holiday,

            'contacts_list'=>$contacts_list,
            'workers_list'=>$workers_list,

            'holiday_defaults'=>$holiday_defaults,

        ];

        return view('account.client.create',$data);
    }
    public function store(Request $request)
    { 
        $data=$request->all();
        if ($data['billing_cycle_type'] == 'semi-monthly') {
            $billing_cycle_next_end_date = date('Y-m-t');
            $today = (int) date('d');
            if ($today<16) {
                $billing_cycle_next_end_date = date('Y-m-15');
            }
            $data['billing_cycle_next_end_date'] = $billing_cycle_next_end_date;
        }
        $client=Clients::create($data);
        // $client->account_managers_id=Auth::user()->accountmanager()->id;
        // $client->save();
 
        if (isset($data['job_functions'])){            
            $job_functions=array();
            foreach ($data['job_functions'] as $key => $value) {
                $job_functions[]=$value;
            }
            $client->job_function=implode(",",$job_functions);
            $client->save();
        }

        $workers_ids=array();
        if(isset($data['worker_id'])){
        foreach ($data['worker_id'] as $key => $value) {
            $worker=Workers::where('id',$key)->first();
            $client_ids=explode(",",$worker->clients_ids);
            $client_ids[]=$client->id;
            $worker->clients_ids=implode(",",$client_ids);
            $worker->save();
            $workers_ids[]=$key;
            
            $clientinfo=ClientInfoWorkers::where('clients_id',$client->id)->where('workers_id',$key)->first();
            if (count($clientinfo)==0) {
                $clientinfo=ClientInfoWorkers::create();
                $clientinfo->hired_at=date('Y-m-d H:i:s');
            }
            $clientinfo->clients_id=$client->id;
            $clientinfo->workers_id=$key;
            $clientinfo->account_managers_id=Auth::user()->accountmanager()->id;
            $clientinfo->target_hours_week=$request->target_hours_week[$key];
            $clientinfo->client_billable_rate_regular=$request->client_billable_rate_regular[$key];
            $clientinfo->client_billable_rate_overtime=$request->client_billable_rate_overtime[$key];
            $clientinfo->worker_pay_houly_rate_regular=$request->worker_pay_houly_rate_regular[$key];
            $clientinfo->worker_pay_houly_rate_overtime=$request->worker_pay_houly_rate_overtime[$key];
            $clientinfo->currency_type=strtolower($request->currency_type[$key]);
            $clientinfo->ptodays_full_calendar=$request->ptodays_full_calendar[$key];
            $clientinfo->ptodays_current_calendar=$request->ptodays_current_calendar[$key];

            $clientinfo->worker_pto_hourly_rate=$request->worker_pto_hourly_rate[$key];
            $clientinfo->worker_holiday_hourly_rate=$request->worker_holiday_hourly_rate[$key];

            $clientinfo->save();
             
            $percent=$client->overtime_pay_provided=='yes' ? 1+$client->overtime_percent/100:1;
            $clientinfo->client_billable_rate_overtime=$clientinfo->client_billable_rate_regular*$percent;
            $clientinfo->worker_pay_houly_rate_overtime=$clientinfo->worker_pay_houly_rate_regular*$percent;            
            $clientinfo->save();
        }
        }

        $client->workers_ids=implode(",",$workers_ids);
        $client->updated_at=date('Y-m-d H:i:s');
        $client->save();
        if (isset($data['contact_id'])){ 
        foreach ($data['contact_id'] as $key => $value) {
            $contact=Contacts::where('id',$key)->first();
            $contact->clients_id=$client->id; 
            $contact->receives_copy_invoice=$request->receives_copy_invoice[$key];
            $contact->save();
        }
        }

        if (isset($data['holiday_name'])){
            foreach ($data['holiday_name'] as $key => $value) {
                $holiday_schedule=HolidaySchedule::where('clients_id',$client->id)->where('holiday_date',$key)->first();

                if(count($holiday_schedule)==0) $holiday_schedule=HolidaySchedule::create();
                $holiday_schedule->clients_id=$client->id;
                $holiday_schedule->holiday_name=$request->holiday_name[$key];
                $holiday_schedule->holiday_date=$key;
                $holiday_schedule->year=date('Y',strtotime($key));
                $holiday_schedule->save();
            }
        }
        Session::flash('message',"New client: ".$client->client_name."'s profile created successfully.");
        return redirect()->route('account.client.profile',$client->id);

    }
    public function update(Request $request)
    { 
        $data=$request->all();
        if ($data['billing_cycle_type'] == 'semi-monthly') {
            $billing_cycle_next_end_date = date('Y-m-t');
            $today = (int) date('d');
            if ($today<16) {
                $billing_cycle_next_end_date = date('Y-m-15');
            }
            $data['billing_cycle_next_end_date'] = $billing_cycle_next_end_date;
        }
         
        $client=Clients::where('id',$request->client_id)->first();
        
////////  Time card update when change billing cycle end date
        $hms=' 12:00:00'; 
        $target_end_date=$data['billing_cycle_next_end_date'];
        $current_end_date=$client->billing_cycle_next_end_date;
        $now=date('Y-m-d',strtotime(Carbon::now()));
        if ($now>$target_end_date){
            Session::flash('message',"The billing cycle end date entered is invalid. Please enter a future date.");
            return redirect()->route('account.client.profile',$request->client_id);
        }

        if ($target_end_date == $current_end_date && $data['billing_cycle_type'] == 'semi-monthly'){
            $timecards=$client->timecards()->where('status','logtime')->get();
            foreach ($timecards as $timecard) {
                $end_date=$target_end_date;
                $start_date=date('Y-m-16',strtotime($end_date.$hms));
                if (date('d',strtotime($end_date.$hms)) == '15') {
                    $start_date=date('Y-m-01',strtotime($end_date.$hms));
                }
                $timesheets=$timecard->timesheets()->where('date','>',$end_date)->get();
                foreach ($timesheets as $timesheet) {
                    $timesheet->delete();
                }
                for ($d=0;$d<16;$d++){
                    $each_date=date('Y-m-d',strtotime($start_date.$hms)+$d*3600*24);
                    if ($each_date > $end_date) break;
                    $timesheet=$timecard->timesheets()->where('date',$each_date)->first();
                    if (count($timesheet)>0) {
                        continue;
                    }
                    $timesheet=TimeSheets::create();
                    $timesheet->clients_id=$client->id;
                    $timesheet->workers_id=$timecard->worker()->id;
                    $timesheet->date=date('Y-m-d',strtotime($each_date));
                    $timesheet->day=date('l',strtotime($each_date));
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
                $timecard->end_date=$end_date;
                $timecard->start_date=$start_date;
                $timecard->save();
            }
        } else if ($target_end_date<$current_end_date){
            $timecards=$client->timecards()->where('status','logtime')->get();
            foreach ($timecards as $timecard) {
                $end_date=$target_end_date;
                $start_date=date('Y-m-d',strtotime($end_date)-13*3600*24);
                if ($data['billing_cycle_type'] == 'semi-monthly') {
                    $start_date=date('Y-m-16',strtotime($end_date.$hms));
                    if (date('d',strtotime($end_date.$hms)) == '15') {
                        $start_date=date('Y-m-01',strtotime($end_date.$hms));
                    }
                }
                $timesheets=$timecard->timesheets()->where('date','>',$end_date)->get();
                foreach ($timesheets as $timesheet) {
                    $timesheet->delete();
                }
                for ($d=0;$d<16;$d++){
                    $each_date=date('Y-m-d',strtotime($start_date)+$d*3600*24);
                    if ($each_date > $end_date) break;
                    $timesheet=$timecard->timesheets()->where('date',$each_date)->first();
                    if (count($timesheet)>0) continue;
                    $timesheet=TimeSheets::create();
                    $timesheet->clients_id=$client->id;
                    $timesheet->workers_id=$timecard->worker()->id;
                    $timesheet->date=date('Y-m-d',strtotime($each_date));
                    $timesheet->day=date('l',strtotime($each_date));
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
                $timecard->end_date=$end_date;
                $timecard->start_date=$start_date;
                $timecard->save();
            }
        } else if ($target_end_date>$current_end_date){
            $timecards=$client->timecards()->where('status','logtime')->get();
            $end_date=$target_end_date;
            $start_date=date('Y-m-d',strtotime($end_date)-13*3600*24);
            if ($data['billing_cycle_type'] == 'semi-monthly') {
                $start_date=date('Y-m-16',strtotime($end_date.$hms));
                if (date('d',strtotime($end_date.$hms)) == '15') {
                    $start_date=date('Y-m-01',strtotime($end_date.$hms));
                }
            }

            foreach ($timecards as $timecard) {
                $timesheets=$timecard->timesheets()->where('date','<',$start_date)->get();
                $new_timecard=TimeCards::create();
                $total_work_time=0;
                $total_pto_time=0;
                $total_holiday_time=0;
                foreach ($timesheets as $timesheet) {
                    $timesheet->time_cards_id=$new_timecard->id;
                    $timesheet->status='pending_worker';
                    $timesheet->save();
                    $total_work_time+=($timesheet->work_time_hours+$timesheet->work_time_minutes/60);
                    $total_pto_time+=$timesheet->pto_time_hours;
                    $total_holiday_time+=$timesheet->holiday_time_hours;
                }
                $new_timecard->workers_id=$timecard->worker()->id;
                $new_timecard->clients_id=$client->id;
                $new_timecard->start_date=$timecard->start_date;
                $new_timecard->end_date=date('Y-m-d',strtotime($start_date)-1*3600*24);
                $new_timecard->total_work_time=$total_work_time;
                $new_timecard->total_pto_time=$total_pto_time;
                $new_timecard->total_holiday_time=$total_holiday_time;
                $new_timecard->status='pending_worker';
                $new_timecard->save();

                
                $total_work_time=0;
                $total_pto_time=0;
                $total_holiday_time=0;
                for ($d=0;$d<16;$d++){
                    $each_date=date('Y-m-d',strtotime($end_date)-$d*3600*24);
                    if ($each_date < $start_date) break;
                    $timesheet=$timecard->timesheets()->where('date',$each_date)->first();
                    if (count($timesheet)>0) {
                        $total_work_time+=($timesheet->work_time_hours+$timesheet->work_time_minutes/60);
                        $total_pto_time+=$timesheet->pto_time_hours;
                        $total_holiday_time+=$timesheet->holiday_time_hours;
                        continue;
                    }
                    $timesheet=TimeSheets::create();
                    $timesheet->clients_id=$client->id;
                    $timesheet->workers_id=$timecard->worker()->id;
                    $timesheet->date=date('Y-m-d',strtotime($each_date));
                    $timesheet->day=date('l',strtotime($each_date));
                    $timesheet->work_time_hours=null;
                    $timesheet->pto_time_hours=null;
                    $timesheet->holiday_time_hours=null;
                    $timesheet->work_time_minutes=null;
                     
                    $timesheet->notes='';
                    $timesheet->status='logtime';
                    $timesheet->time_cards_id=$timecard->id;
                    $timesheet->save();
                    if ($each_date == $start_date) break;
                }
                $timecard->total_work_time=$total_work_time;
                $timecard->total_pto_time=$total_pto_time;
                $timecard->total_holiday_time=$total_holiday_time;
                $timecard->end_date=$end_date;
                $timecard->start_date=$start_date;
                $timecard->save();
            }
        }
////////////////////////////////////////////////////////////////////////
        $default_pto_days=$client->default_pto_days;
        $client->update($data);
        if (isset($data['job_functions'])){            
            $job_functions=array();
            foreach ($data['job_functions'] as $key => $value) {
                $job_functions[]=$value;
            }
            $client->job_function=implode(",",$job_functions);
            $client->save();
        }else{
            $client->job_function='';
            $client->save();
        }
        $workers_ids=array();
        if(isset($data['worker_id'])){
            foreach ($data['worker_id'] as $key => $value) {
                $workers_ids[]=$key;
            }
        }
        $workers=$client->workers()->whereNotIn('id', $workers_ids);
        foreach ($workers as $worker){
            $client_ids=explode(",",$worker->clients_ids);
            
            $client_ids=array_unique(array_diff($client_ids, array($client->id)));
            $client_ids=implode(",",$client_ids);
            $worker->clients_ids=$client_ids;
            $worker->save();

            $clientinfo=ClientInfoWorkers::where('clients_id',$client->id)->where('workers_id',$worker->id)->first();
            if (count($clientinfo)>0) $clientinfo->delete();

        }
        $workers_ids=array();
        if(isset($data['worker_id'])){
        foreach ($data['worker_id'] as $key => $value) {
            $worker=Workers::where('id',$key)->first();
            $client_ids=explode(",",$worker->clients_ids);
            if (!in_array($client->id, $client_ids)) {
                $client_ids[]=$client->id;
            }
            $worker->clients_ids=implode(",",$client_ids);
            $worker->save();
            $workers_ids[]=$key;
            
            $clientinfo=ClientInfoWorkers::where('clients_id',$client->id)->where('workers_id',$key)->first();
            if (count($clientinfo)==0) {
                $clientinfo=ClientInfoWorkers::create();
                $clientinfo->hired_at=date('Y-m-d H:i:s');
            }
            $clientinfo->clients_id=$client->id;
            $clientinfo->workers_id=$key;
            $clientinfo->account_managers_id=Auth::user()->accountmanager()->id;
            $clientinfo->target_hours_week=$request->target_hours_week[$key];
            $clientinfo->client_billable_rate_regular=$request->client_billable_rate_regular[$key];
            $clientinfo->client_billable_rate_overtime=$request->client_billable_rate_overtime[$key];
            $clientinfo->worker_pay_houly_rate_regular=$request->worker_pay_houly_rate_regular[$key];
            $clientinfo->worker_pay_houly_rate_overtime=$request->worker_pay_houly_rate_overtime[$key];
            $clientinfo->currency_type=strtolower($request->currency_type[$key]);
            $clientinfo->ptodays_full_calendar=$request->ptodays_full_calendar[$key];
            $clientinfo->ptodays_current_calendar=$request->ptodays_current_calendar[$key];

            $clientinfo->worker_pto_hourly_rate=$request->worker_pto_hourly_rate[$key];
            $clientinfo->worker_holiday_hourly_rate=$request->worker_holiday_hourly_rate[$key];
            if (isset($request->action_status[$key])) {$clientinfo->status=$request->action_status[$key];}
            $clientinfo->save();
            // if ($default_pto_days==$clientinfo->ptodays_full_calendar){
            //     $clientinfo->ptodays_full_calendar=$client->default_pto_days;
            //     $clientinfo->save();
            // }
            $percent=$client->overtime_pay_provided=='yes' ? 1+$client->overtime_percent/100:1;
            $clientinfo->client_billable_rate_overtime=$clientinfo->client_billable_rate_regular*$percent;
            $clientinfo->worker_pay_houly_rate_overtime=$clientinfo->worker_pay_houly_rate_regular*$percent;
            // $clientinfo->save();
            // $firstHiredTimecard = TimeCards::where('workers_id',$worker->id)->where('clients_id',$client->id)->orderBy('start_date', 'ASC')->first();
            // if ($firstHiredTimecard) {
            //     if (date('Y',strtotime($clientinfo->hired_at)) != date('Y',strtotime($firstHiredTimecard->start_date))) {
            //         $clientinfo->hired_at = $firstHiredTimecard->start_date . ' 00:00:00';
            //     }
            // }
            $clientinfo->updated_at=date('Y-m-d H:i:s');         
            $clientinfo->save();
        }
        }

        $client->workers_ids=implode(",",$workers_ids);
        $client->updated_at=date('Y-m-d H:i:s');
        $client->save();

        
        $contacts=$client->contacts()->get();
        foreach ($contacts as $contact) {
            $contact->clients_id=0; 
            $contact->save(); 
        }

        if (isset($data['contact_id'])){ 
        foreach ($data['contact_id'] as $key => $value) {
            $contact=Contacts::where('id',$key)->first();
            $contact->clients_id=$client->id; 
            // $contact->timesheet_able_to_approve=$request->timesheet_able_to_approve[$key];
            // $contact->timesheet_view_only=$request->timesheet_view_only[$key];
            $contact->receives_copy_invoice=$request->receives_copy_invoice[$key];
            $contact->save();
        }
        }
         
        
        $current_year=date('Y',strtotime(Carbon::now()));
        $holiday_schedules=HolidaySchedule::where('clients_id',$client->id)->whereIn('year',[$current_year, $current_year + 1, $current_year + 2])->get();
        foreach ($holiday_schedules as $holiday_schedule) {
            $holiday_schedule->delete();
        }
        if (isset($data['holiday_name'])){ 
        foreach ($data['holiday_name'] as $key => $value) {
            $holiday_schedule=HolidaySchedule::create();
            $holiday_schedule->clients_id=$client->id;
            $holiday_schedule->holiday_name=$request->holiday_name[$key];
            $holiday_schedule->holiday_date=$key;
            $holiday_schedule->year=date('Y',strtotime($key));
            $holiday_schedule->save();
        }
        }
         
        Session::flash('message',"Client: ".$client->client_name."'s profile updated successfully.");
        return redirect()->route('account.client.profile',$client->id);

    }
    public function profile($id)
    {
        $client=Clients::where('id',$id)->first();
        if (count($client)==0){
            Session::flash('message',"This client does not exist.");
            return redirect()->route('account.client.search');
        }
        $client->updated_at=date('Y-m-d H:i:s');
        $client->save();
        
        $status=[
            'potential'=>'Potential Client',
            'active'=>'Active Client',
            'past'=>'Past Client',
        ];
        $country=[
            'US'=>'United States',
            'Other'=>'Other',
        ];
        $accountmanagers=AccountManagers::get()->pluck('fullname','id')->prepend('','');
        $industry=[
            "Accounting/Bookkeeping"=>"Accounting/Bookkeeping",
            "Auto Dealerships"=>"Auto Dealerships",
            "Banking"=>"Banking",
            "Business Broker"=>"Business Broker",
            "Catering"=>"Catering",
            "Directory Assistance"=>"Directory Assistance",
            "Insurance - Other"=>"Insurance - Other",
            "Insurance Sales"=>"Insurance Sales",
            "Law Firm"=>"Law Firm",
            "Medical - Dental"=>"Medical - Dental",
            "Medical - Doctor's Office"=>"Medical - Doctor's Office",
            "Medical - General"=>"Medical - General",
            "Medical - Records Collections"=>"Medical - Records Collections",
            "Medical - Records Review"=>"Medical - Records Review",
            "Mortgage Broker"=>"Mortgage Broker",
            "Plumbing/HVAC"=>"Plumbing/HVAC",
            "Professional Services - Other"=>"Professional Services - Other",
            "Real Estate - Commercial Sales"=>"Real Estate - Commercial Sales",
            "Real Estate - Residential Sales"=>"Real Estate - Residential Sales",
            "Real Estate - Staging"=>"Real Estate - Staging",
            "Software Development"=>"Software Development",
            "Technology - Other"=>"Technology - Other",
            "Trucking"=>"Trucking",
            "Other"=>"Other"
        ];
        $job_function=[
            'Answering Service'=>'Answering Service',
            'Call Center - Inbound General'=>'Call Center - Inbound General',
            'Call Center - Outbound General'=>'Call Center - Outbound General',
            'Cold Calling'=>'Cold Calling',
            'Data Entry'=>'Data Entry',
            'Directory Assistance'=>'Directory Assistance',
            'Dispatcher - Automotive'=>'Dispatcher - Automotive',
            'Executive Assistant'=>'Executive Assistant',
            'Graphic Design'=>'Graphic Design',
            'Inside Sales Associate'=>'Inside Sales Associate',
            'Insurance Verification'=>'Insurance Verification',
            'Legal - Legal Secretary'=>'Legal - Legal Secretary',
            'Legal - Paralegal'=>'Legal - Paralegal',
            'Medical Records Collections'=>'Medical Records Collections',
            'Sales Assistant'=>'Sales Assistant',
            'Website Development'=>'Website Development',
            'Other'=>'Other'
        ];
        $global_values=Globals::first()->values;
        if (unserialize($global_values)){
            foreach (unserialize($global_values) as $other){
                if (strnatcasecmp($other['fieldname'],'Client Industry')==0){
                    $industry=array();
                    $industry['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $industry[$value]=$value;
                    }
                }

                if (strnatcasecmp($other['fieldname'],'Job Function')==0){
                    $job_function=array();
                    //$job_function['']='';
                    foreach (explode(',', $other['value']) as $value) {
                         $job_function[$value]=$value;
                    }
                }
            }    
        }

        $lead_generated_by=[
            ''=>'',
            'Direct Contact - Business Development'=>'Direct Contact - Business Development',
            'Direct Contact - Other Internal'=>'Direct Contact - Other Internal',
            'Marketing Program - Craigslist'=>'Marketing Program - Craigslist',
            'Marketing Program - Indeed'=>'Marketing Program - Indeed',
            'Marketing Program - Other'=>'Marketing Program - Other',
            'Networking Event'=>'Networking Event',
            'Client Referral'=>'Client Referral',
            'Other'=>'Other'
        ];
        $payrolls=PayrollManagers::where('deleted_at',null)->get();
        $admins=Admins::where('deleted_at',null)->get();
        $direct_contact_internal_payroll_admin=array();
        $direct_contact_internal_payroll_admin['']='';
        foreach ($payrolls as $payroll) {
            $direct_contact_internal_payroll_admin['payroll_'.$payroll->id]=$payroll->fullname;
        }
        foreach ($admins as $admin) {
            $direct_contact_internal_payroll_admin['admin_'.$admin->id]=$admin->fullname;
        }
        $contacts_list=Contacts::where('deleted_at',null)->orderBy('last_name')->pluck('fullname','id');
         

        $billing_cycle_next_end_date_adder=[
            '0'=>'0',
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',            
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
            '10'=>'10',
        ];
        $overtime_pay_provided=[
            'yes'=>'Yes',
            'no'=>'No',
        ];

        $lunchtime_billable=$overtime_pay_provided;
        $breaktime_billable=$overtime_pay_provided;
        $invoice_method=[
            'automatically'=>'System Generated',
            'manual'=>'Create Manually',
        ];
        $ACH_discount_participation=$overtime_pay_provided;
        $payment_method=[
            'client_process_ach'=>'Client Process - ACH',
            'internal_process_ach'=>'Internal Process - ACH on file',
            'internal_process_cc'=>'Internal Process - Credit Card on file',
        ];
        $review_time=[
            'auto'=>'Auto Approve',
            '1'=>'1 business day',
            '2'=>'2 business days',
            '3'=>'3 business days',
            '4'=>'4 business days',            
            '5'=>'5 business days'
        ];
        $internal_processor=[
            'Quickbooks Online'=>'Quickbooks Online',
            'Square'=>'Square',
        ];
        $pto_infomation=$overtime_pay_provided;
        $who_pays_pto=[
            'brightdrop'=>'BrightDrop',
            'client'=>'Client',
        ];
        $default_pto_days=[
            '0'=>'0',
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',            
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
            '10'=>'10',
            '11'=>'11',
            '12'=>'12',
            '13'=>'13',
            '14'=>'14',            
            '15'=>'15',
            '16'=>'16',
            '17'=>'17',
            '18'=>'18',
            '19'=>'19',
            '20'=>'20',
        ];

        $holiday_shedule_offered= [
            'yes_paid'=>'Yes - Paid',
            'yes_unpaid'=>'Yes - Unpaid',
            'no_holiday'=>'No Holiday Schedule',
        ];
        $who_pays_holiday=[
            
            'brightdrop'=>'BrightDrop',
            'client'=>'Client',
        ];
 
        
        $admins_id=Auth::user()->accountmanager()->admin()->id;
        
        $current_year=date('Y',strtotime(Carbon::now()));
        $client=Clients::where('id',$id)->first();
        $holiday_defaults=array();
        for ($year=$current_year;$year<$current_year+3;$year++){
            $holiday_default=$client->holidays()->where('year',$year)->orderBy('holiday_date')->get();
             
            $holiday['holidays']=$holiday_default;
            $holiday['year']=$year;
            $holiday_defaults[]=$holiday;
        }
        $yesno=[
            'Yes'=>'Yes',
            'No'=>'No',
        ];
        

        $target_hours=[
            '10'=>'10',
            '20'=>'20',
            '30'=>'30',
            '40'=>'40',            
        ];
        $currency_type=[
            'mxn'=>'MXN',
            'php'=>'PHP',
            'usd'=>'USD'
        ];
        $pto_days=[
            '0'=>'0',
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',            
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
            '10'=>'10',
            '11'=>'11',
            '12'=>'12',
            '13'=>'13',
            '14'=>'14',            
            '15'=>'15',
            '16'=>'16',
            '17'=>'17',
            '18'=>'18',
            '19'=>'19',
            '20'=>'20',
            '21'=>'21',
            '22'=>'22',
            '23'=>'23',
            '24'=>'24',            
            '25'=>'25',
            '26'=>'26',
            '27'=>'27',
            '28'=>'28',
            '29'=>'29',
            '30'=>'30',
        ];

        $contacts=Contacts::where('deleted_at',null)->get();
        $client_referral=array();
        foreach ($contacts as $contact) {
            $client_referral[$contact->id]=isset($contact->client()->client_name) ? $contact->last_name.', '.$contact->first_name.' ('.$contact->client()->client_name.')': $contact->last_name.', '.$contact->first_name;
        }
        $contacts=$client->contacts()->get();
        $workers=$client->workers();

        //$workers_list=Auth::user()->accountmanager()->workers()->get()->pluck('fullname','id');
        $workers_list=array();
        //$workerss=Workers::where('deleted_at',null)->where('status','!=','disqualfied')->where('status','!=','not_available_hired')->orderBy('fullname')->get();
        $workerss=Workers::where('deleted_at',null)->orderBy('last_name')->get();
        foreach ($workerss as $worker) {
            if (in_array($client->id, explode(',', $worker->target_client))){
                $workers_list[$worker->id]=$worker->fullname.' ( Targeted )';
            }
        }
        foreach ($workerss as $worker) {
            if (!in_array($client->id, explode(',', $worker->target_client))){
                $workers_list[$worker->id]=$worker->fullname;    
            }
        }
         
        $data=[
            'country'=>$country,
            'status'=>$status,
            'business_development'=>$accountmanagers,
            'account_manager'=>$accountmanagers,
            'industry'=>$industry,
            'job_function'=>$job_function,
            'lead_generated_by'=>$lead_generated_by,
            'direct_contact_business_accountmanager'=>$accountmanagers,
            'direct_contact_internal_payroll_admin'=>$direct_contact_internal_payroll_admin,
            'client_referral'=>$client_referral,
            'billing_cycle_next_end_date_adder'=>$billing_cycle_next_end_date_adder,
            'overtime_pay_provided'=>$overtime_pay_provided,
            'lunchtime_billable'=>$lunchtime_billable,
            'breaktime_billable'=>$breaktime_billable,
            'invoice_method'=>$invoice_method,
            'ACH_discount_participation'=>$ACH_discount_participation,
            'payment_method'=>$payment_method,
            'review_time'=>$review_time,
            'internal_processor'=>$internal_processor,
            'pto_infomation'=>$pto_infomation,
            'who_pays_pto'=>$who_pays_pto,
            'default_pto_days'=>$default_pto_days,
            'holiday_shedule_offered'=>$holiday_shedule_offered,
            'who_pays_holiday'=>$who_pays_holiday,

            'contacts_list'=>$contacts_list,
            'workers_list'=>$workers_list,

            'holiday_defaults'=>$holiday_defaults,
            'client'=>$client,
            'contacts'=>$contacts,
            'workers'=>$workers,

            'yesno'=>$yesno,

            'target_hours'=>$target_hours,
            'currency_type'=>$currency_type,
            'pto_days'=>$pto_days,

        ];
        return view('account.client.profile',$data);
    }

    














    public function search()
    {
        $status=[
            ''=>'',
            'active'=>'Active Client',
            'past'=>'Past Client',
            'potential'=>'Potential Client',
        ];
        $country=[
            ''=>'',
            'US'=>'United States',
            'Other'=>'Other',
        ];
        $industry=[
            ''=>'',
            "Accounting/Bookkeeping"=>"Accounting/Bookkeeping",
            "Auto Dealerships"=>"Auto Dealerships",
            "Banking"=>"Banking",
            "Business Broker"=>"Business Broker",
            "Catering"=>"Catering",
            "Directory Assistance"=>"Directory Assistance",
            "Insurance - Other"=>"Insurance - Other",
            "Insurance Sales"=>"Insurance Sales",
            "Law Firm"=>"Law Firm",
            "Medical - Dental"=>"Medical - Dental",
            "Medical - Doctor's Office"=>"Medical - Doctor's Office",
            "Medical - General"=>"Medical - General",
            "Medical - Records Collections"=>"Medical - Records Collections",
            "Medical - Records Review"=>"Medical - Records Review",
            "Mortgage Broker"=>"Mortgage Broker",
            "Plumbing/HVAC"=>"Plumbing/HVAC",
            "Professional Services - Other"=>"Professional Services - Other",
            "Real Estate - Commercial Sales"=>"Real Estate - Commercial Sales",
            "Real Estate - Residential Sales"=>"Real Estate - Residential Sales",
            "Real Estate - Staging"=>"Real Estate - Staging",
            "Software Development"=>"Software Development",
            "Technology - Other"=>"Technology - Other",
            "Trucking"=>"Trucking",
            "Other"=>"Other"
        ];
        $job_function=[
            ''=>'',
            'Answering Service'=>'Answering Service',
            'Call Center - Inbound General'=>'Call Center - Inbound General',
            'Call Center - Outbound General'=>'Call Center - Outbound General',
            'Cold Calling'=>'Cold Calling',
            'Data Entry'=>'Data Entry',
            'Directory Assistance'=>'Directory Assistance',
            'Dispatcher - Automotive'=>'Dispatcher - Automotive',
            'Executive Assistant'=>'Executive Assistant',
            'Graphic Design'=>'Graphic Design',
            'Inside Sales Associate'=>'Inside Sales Associate',
            'Insurance Verification'=>'Insurance Verification',
            'Legal - Legal Secretary'=>'Legal - Legal Secretary',
            'Legal - Paralegal'=>'Legal - Paralegal',
            'Medical Records Collections'=>'Medical Records Collections',
            'Sales Assistant'=>'Sales Assistant',
            'Website Development'=>'Website Development',
            'Other'=>'Other'
        ];

        $lead_generated_by=[
            ''=>'',
            'Direct Contact - Business Development'=>'Direct Contact - Business Development',
            'Direct Contact - Other Internal'=>'Direct Contact - Other Internal',
            'Marketing Program - Craigslist'=>'Marketing Program - Craigslist',
            'Marketing Program - Indeed'=>'Marketing Program - Indeed',
            'Marketing Program - Other'=>'Marketing Program - Other',
            'Networking Event'=>'Networking Event',
            'Client Referral'=>'Client Referral',
            'Other'=>'Other'
        ];
        $invoice_method=[
            ''=>'',
            'automatically'=>'System Generated',
            'manual'=>'Create Manually',
        ];
        $payment_method=[
            ''=>'',
            'client_process_ach'=>'Client Process - ACH',
            'internal_process_ach'=>'Internal Process - ACH on file',
            'internal_process_cc'=>'Internal Process - Credit Card on file',
        ];

        $clients=Auth::user()->accountmanager()->clients();
        if (session()->has('client_search.status')){
            if (session('client_search.status')!=''){
                $clients=$clients->where('status',session('client_search.status'));
            }
        }
        if (session()->has('client_search.country')){
            if (session('client_search.country')!=''){
                $clients=$clients->where('country',session('client_search.country'));
            }
        }
        if (session()->has('client_search.industry')){
            if (session('client_search.industry')!=''){
                $clients=$clients->where('industry',session('client_search.industry'));
            }
        }
        if (session()->has('client_search.job_function')){
            if (session('client_search.job_function')!=''){
                $clients=$clients->where('job_function', 'like','%'.session('client_search.job_function').'%');
            }
        }
        if (session()->has('client_search.lead_generated_by')){
            if (session('client_search.lead_generated_by')!=''){
                $clients=$clients->where('lead_generated_by',session('client_search.lead_generated_by'));
            }
        }
        if (session()->has('client_search.invoice_method')){
            if (session('client_search.invoice_method')!=''){
                $clients=$clients->where('invoice_method',session('client_search.invoice_method'));
            }
        }
        if (session()->has('client_search.payment_method')){
            if (session('client_search.payment_method')!=''){
                $clients=$clients->where('payment_method',session('client_search.payment_method'));
            }
        }


        if (session()->has('client_search.client_name')){
            if (session('client_search.client_name')!=''){
                $clients=$clients->where('client_name','like','%'.session('client_search.client_name').'%');
            }
        }
        if (session()->has('client_search.website')){
            if (session('client_search.website')!=''){
                $clients=$clients->where('website','like','%'.session('client_search.website').'%');
            }
        }
        if (session()->has('client_search.phone')){
            if (session('client_search.phone')!=''){
                $clients=$clients->where('phone','like','%'.session('client_search.phone').'%');
            }
        }
        if (session()->has('client_search.address')){
            if (session('client_search.address')!=''){
                $clients=$clients->where('address1','like','%'.session('client_search.address').'%')->orwhere('address_foreign','like','%'.session('client_search.address').'%');
            }
        }
        if (session()->has('client_search.city')){
            if (session('client_search.city')!=''){
                $clients=$clients->where('city','like','%'.session('client_search.city').'%');
            }
        }
        if (session()->has('client_search.state')){
            if (session('client_search.state')!=''){
                $clients=$clients->where('state','like','%'.session('client_search.state').'%');
            }
        }
        if (session()->has('client_search.zip')){
            if (session('client_search.zip')!=''){
                $clients=$clients->where('zip','like','%'.session('client_search.zip').'%');
            }
        }
        if (session()->has('client_search.billing_cycle_next_end_date')){
            if (session('client_search.billing_cycle_next_end_date')!=''){
                $clients=$clients->where('billing_cycle_next_end_date','like','%'.session('client_search.billing_cycle_next_end_date').'%');
            }
        }

 




        $clients=$clients->get();

        $data=[
            'country'=>$country,
            'status'=>$status,
            'industry'=>$industry,
            'job_function'=>$job_function,
            'lead_generated_by'=>$lead_generated_by,
            'invoice_method'=>$invoice_method,
            'payment_method'=>$payment_method,
            'clients'=>$clients,
         
        ];

        return view('account.client.search',$data);
    }

    public function removeClient(Request $request)
    {
        $client=Clients::where('id',$request->client_id)->first();
        if(count($client->timecards()->where('status','!=','Paid')->get())>0){
            Session::flash('message',"You can not remove. This Client has timesheet(s) to pay workers.");
            return redirect()->route('account.client.search');
        }
        if(count($client->invoices()->where('status','!=','Bank Verified')->get())>0){
            Session::flash('message',"You can not remove. This Client has invoice(s) that did not verified yet.");
            return redirect()->route('account.client.search');
        }
        $client->delete();
        Session::flash('message',"Client removed.");
        return redirect()->route('account.client.search');
    }


    public function setfilter(Request $request)
    {
        session(['client_search.status'=>$request->status]);
        session(['client_search.client_name'=>$request->client_name]);
        session(['client_search.website'=>$request->website]);
        session(['client_search.phone'=>$request->phone]);
        session(['client_search.country'=>$request->country]);
        session(['client_search.address'=>$request->address]);
        session(['client_search.industry'=>$request->industry]);
        session(['client_search.city'=>$request->city]);
        session(['client_search.state'=>$request->state]);
        session(['client_search.zip'=>$request->zip]);
        session(['client_search.job_function'=>$request->job_function]);
        session(['client_search.lead_generated_by'=>$request->lead_generated_by]);
        session(['client_search.invoice_method'=>$request->invoice_method]);
        session(['client_search.payment_method'=>$request->payment_method]);
        session(['client_search.billing_cycle_next_end_date'=>$request->billing_cycle_next_end_date]);

        return redirect()->route('account.client.search');
    }
    public function resetfilter(Request $request)
    {
        session()->forget('client_search');
        return redirect()->route('account.client.search');
    }
    public function activeWorkers(Request $request)
    {
        $client=Clients::where('id',$request->clients_id)->first();
        if (!$client){
            return response()->json([]);
        }
        return response()->json($client->activeWorkers());
    }
    public function activeWorkersForPendingWorker(Request $request)
    {
        $client=Clients::where('id',$request->clients_id)->first();
        if (!$client){
            return response()->json([]);
        }
        $workers = [];
        foreach ($client->activeWorkers() as $worker) {
            $workers[] = [
                'id' => $worker->id,
                'fullname' => $worker->fullnameForPendingWorkerApprovalTimecards($client->id)
            ];
        }
        return response()->json($workers);
    }

    public function allWorkers(Request $request)
    {
        $from = isset($request->from) ? $request->from : null;
        $accountmanager=Auth::user()->accountmanager();
        $clients = $request->clients_id == 'all' ? $accountmanager->clients()->where('status', 'active')->orderBy('client_name', 'ASC')->get() : Clients::where('id',$request->clients_id)->get();

        $workers[] = [
            'id' => 'all',
            'fullname' => 'All'
        ];
        foreach ($clients as $client) {
            foreach ($client->assignedWorkers() as $worker) {
                if ($from) {
                    $fullname = $worker->fullnameWithDot($client->id, $from);
                    if ($fullname == $worker->fullname) continue;
                }
                $workers[] = [
                    'id' => $worker->id,
                    'fullname' => $worker->fullname
                ];
            }
        }
        return response()->json($workers);
    }
}
