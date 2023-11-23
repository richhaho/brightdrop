<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\HolidayDefault;
use App\Admins;
use App\User;
use App\Role;
use App\Globals;
use Auth;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendLinkResetPassword; 
use App\Workers;
use App\Clients;
use App\TimeCards;
use Illuminate\Support\Facades\Mail;
use App\Mail\TimecardEndedWorker;
use App\ClientInfoWorkers;
use App\TimeSheets;
use App\Mail\BillingcycleEndedAccountmanager;

class AdminController extends Controller
{
    

    public function create()
    {
        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
 
        $data=[
            'status'=>$status,
        ];

        return view('admin.admin.create',$data);
    }
    public function store(Request $request)
    { 
        $data=$request->all();
        if (count(User::where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('admin.admin.create');
        }
        if (count(Admins::where('email',$data['email'])->get())>0){
            Session::flash('message',"Admin email already exists on other Admin. Please input unique email for new Admin.");
            return redirect()->route('admin.admin.create');
        }

        $admin=Admins::create();
        $admin->status=$data['status'];
        $admin->first_name=$data['first_name'];
        $admin->last_name=$data['last_name'];
        $admin->fullname=$data['first_name'].' '.$data['last_name'];
        $admin->email=$data['email'];
        $admin->phone=$data['phone'];
        $admin->address1=$data['address1'];
        $admin->address2=$data['address2'];
        $admin->city=$data['city'];
        $admin->state=$data['state'];
        $admin->zip=$data['zip'];
        
        $admin->updated_at=date('Y-m-d H:i:s');
        $admin->save();

        $user= User::create([
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['user_email'],
            'password' => bcrypt($data['password']),
        ]);
        $default_role= Role::where('name', 'Admin')->first();
        $user->attachRole($default_role);
        $admin->user_id=$user->id;
        $admin->save();

        Session::flash('message',"New Admin: ".$admin->fullname." created successfully.");
        return redirect()->route('admin.admin.profile',$admin->id);

    }
    public function profile($id)
    {
        $admin=Admins::where('id',$id)->first();
        $admin->updated_at=date('Y-m-d H:i:s');
        $admin->save();
        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];

        $data=[
            'status'=>$status,
            'admin'=>$admin,
            
        ];
        return view('admin.admin.profile',$data);
    }

    public function update(Request $request)
    { 
        $data=$request->all();
        $admin=Admins::where('id',$data['admin_id'])->first();

        if (count(User::where('id','!=',$admin->user_id)->where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('admin.admin.profile',$admin->id);
        }
        if (count(Admins::where('id','!=',$admin->id)->where('email',$data['email'])->get())>0){
            Session::flash('message',"Admin email already exists on other Admin. Please input unique email for this Admin.");
            return redirect()->route('admin.admin.profile',$admin->id);
        }

        $admin->status=$data['status'];
        $admin->first_name=$data['first_name'];
        $admin->last_name=$data['last_name'];
        $admin->fullname=$data['first_name'].' '.$data['last_name'];
        $admin->email=$data['email'];
        $admin->phone=$data['phone'];
        $admin->address1=$data['address1'];
        $admin->address2=$data['address2'];
        $admin->city=$data['city'];
        $admin->state=$data['state'];
        $admin->zip=$data['zip'];
        $admin->updated_at=date('Y-m-d H:i:s');
        $admin->save();
         
        $user=$admin->user();
        $user->name=$data['first_name'].' '.$data['last_name'];
        $user->email=$data['user_email'];
        $user->password=bcrypt($data['password']);
        $user->save();

        Session::flash('message',"Administrator: ".$admin->fullname."'s profile updated.");
        return redirect()->route('admin.admin.profile',$admin->id);

    }

    public function search()
    {
        $status=[
            'all'=>'All',
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
        
        $admins=Admins::where('deleted_at',null);
        if (session()->has('admin_search.status')){
            if (session('admin_search.status')!='all'){
                $admins=$admins->where('status',session('admin_search.status'));
            }
        }
        
        if (session()->has('admin_search.first_name')){
            if (session('admin_search.first_name')!=''){
                $admins=$admins->where('first_name','like','%'.session('admin_search.first_name').'%');
            }
        }
        if (session()->has('admin_search.last_name')){
            if (session('admin_search.last_name')!=''){
                $admins=$admins->where('last_name','like','%'.session('admin_search.last_name').'%');
            }
        }
        if (session()->has('admin_search.email')){
            if (session('admin_search.email')!=''){
                $admins=$admins->where('email','like','%'.session('admin_search.email').'%');
            }
        }

        if (session()->has('admin_search.phone')){
            if (session('admin_search.phone')!=''){
                $admins=$admins->where('phone','like','%'.session('admin_search.phone').'%');
            }
        }
        
        if (session()->has('admin_search.city')){
            if (session('admin_search.city')!=''){
                $admins=$admins->where('city','like','%'.session('admin_search.city').'%');
            }
        }
        if (session()->has('admin_search.state')){
            if (session('admin_search.state')!=''){
                $admins=$admins->where('state','like','%'.session('admin_search.state').'%');
            }
        }
        if (session()->has('admin_search.zip')){
            if (session('admin_search.zip')!=''){
                $admins=$admins->where('zip','like','%'.session('admin_search.zip').'%');
            }
        }

        if (session()->has('admin_search.address1')){
            if (session('admin_search.address1')!=''){
                $admins=$admins->where('address1','like','%'.session('admin_search.address1').'%');
            }
        }
        if (session()->has('admin_search.address2')){
            if (session('admin_search.address2')!=''){
                $admins=$admins->where('address2','like','%'.session('admin_search.address2').'%');
            }
        }
  
        $admins=$admins->orderBy('last_name')->get();
 
        $data=[
            'status'=>$status,
            'admins'=>$admins,
             
        ];

        return view('admin.admin.search',$data);
    }
    public function setfilter(Request $request)
    {
        session(['admin_search.status'=>$request->status]);
        session(['admin_search.first_name'=>$request->first_name]);
        session(['admin_search.last_name'=>$request->last_name]);
        session(['admin_search.email'=>$request->email]);
        session(['admin_search.phone'=>$request->phone]);
        session(['admin_search.city'=>$request->city]);
        session(['admin_search.state'=>$request->state]);
        session(['admin_search.zip'=>$request->zip]);
        session(['admin_search.address1'=>$request->address1]);
        session(['admin_search.address2'=>$request->address2]);
        return redirect()->route('admin.admin.search');
    }
    public function resetfilter(Request $request)
    {
        session()->forget('admin_search');
        return redirect()->route('admin.admin.search');
    }

    public function remove(Request $request)
    {
        $admin=Admins::where('id',$request->admin_id)->first();
        $fullname=$admin->fullname;
        $admin->user()->delete();
        $admin->delete();
        Session::flash('message',"Administrator: ".$fullname." removed.");
        return redirect()->route('admin.admin.search');
    }
 

    


    public function createPayrollManager()
    {
        return view('admin.Admin_createPayrollManager');
    }
    public function searchPayrollManager()
    {
        return view('admin.Admin_searchPayrollManager');
    }   
    public function profilePayrollManager()
    {
        return view('admin.Admin_profilePayrollManager');
    } 

    


    public function holidaySchedule()
    {
        $admins_id=Auth::user()->admin()->id;
        $current_year=date('Y',strtotime(Carbon::now()));
        $holiday_defaults=array();
        for ($year=$current_year;$year<2030;$year++){
            // $holiday_default=HolidayDefault::where('admins_id',$admins_id)->where('year',$year)->orderBy('holiday_date')->get();
            $holiday_default=HolidayDefault::where('year',$year)->orderBy('holiday_date')->get();
            if (count($holiday_default)==0) continue;
            $holiday['holidays']=$holiday_default;
            $holiday['year']=$year;
            $holiday_defaults[]=$holiday;
        }

        $data=['holiday_defaults'=>$holiday_defaults];
        return view('admin.holiday.holidaySchedule',$data);
    } 
    public function addholiday(Request $request)
    {
        $admins_id=Auth::user()->admin()->id;
        $holiday_name=$request->holiday_name;
        $holiday_date=date('Y-m-d',strtotime($request->holiday_date));
        $year=date('Y',strtotime($request->holiday_date));
        // $holiday_default=HolidayDefault::where('admins_id',$admins_id)->where('holiday_date',$holiday_date)->first();
        $holiday_default=HolidayDefault::where('holiday_date',$holiday_date)->first();
        if (count($holiday_default)==0) $holiday_default=HolidayDefault::create();

        $holiday_default->admins_id=$admins_id;
        $holiday_default->holiday_name=$holiday_name;
        $holiday_default->holiday_date=$holiday_date;
        $holiday_default->year=$year;
        $holiday_default->save();
        return redirect()->route('admin.holidaySchedule');
    }  
    public function updateholiday(Request $request)
    {
        $holiday_id=$request->holiday_id;
        $holiday_name=$request->holiday_name;
        $holiday_date=date('Y-m-d',strtotime($request->holiday_date));
        $year=date('Y',strtotime($request->holiday_date));
        $holiday_default=HolidayDefault::where('id',$holiday_id)->first();
        $holiday_default->holiday_name=$holiday_name;
        $holiday_default->holiday_date=$holiday_date;
        $holiday_default->year=$year;
        $holiday_default->save();
        return redirect()->route('admin.holidaySchedule');
    }  
    public function deleteholiday(Request $request)
    {
        $holiday_id=$request->holiday_id;
        $holiday_default=HolidayDefault::where('id',$holiday_id)->first();
        $holiday_default->delete();
        return redirect()->route('admin.holidaySchedule');
    }  

    






    public function globalFileds()
    {
        $global=Globals::first();
        $data=['global'=>$global];
        return view('admin.globalFileds',$data);
    } 
    public function updateGlobalFileds(Request $request)
    {
        $data=$request->all();
        $global=Globals::first();
        $global->update($data);

        if(isset($data['otherfield'])){
            $fields=array();
            $field_id=0;
            foreach ($data['otherfield'] as $key => $value) {
                $field_id++;
                $field_row['id']=$field_id;
                $field_row['fieldname']=$value;
                $field_row['value']=$request->setvalue[$key];
                $fields[]=$field_row;
            }
            $values=serialize($fields);
            $global->values=$values;
        }
        $global->save();


        Session::flash('message',"Global Fields updated.");
        return redirect()->route('admin.globalFileds');
    } 
    public function sendResetPasswordLink(Request $request)
    {
        Auth::logout();
        return redirect('/password/reset');

        $id=$request->id;
        $admin=Admins::where('id',$id)->first();
        $admin->user()->notify(new SendLinkResetPassword());

        Session::flash('message',"Just sent password reset link to ".$admin->fullname." over email.");
        return redirect()->route('admin.admin.profile',$id);
    }


    public function cronRun()
    {
        $this->timeCardEnded();
        $this->resetBillingCycleEndDate();
        $this->newTimesheet();
        Session::flash('message',"Manual Cron was run successfully.");
        return redirect()->back();
    }

    public function timeCardEnded()
    {
        $hms=' 12:00:00 ';
        $timecards=TimeCards::where('status','logtime')->get();
        $now=date('Y-m-d',strtotime(Carbon::now()));

        foreach ($timecards as $timecard) {
            $period_date=date('Y-m-d',strtotime($timecard->end_date.$hms.'+1days'));
            if ($period_date<=$now){
                $timecard->status='pending_worker';
                $timecard->save();
                $mailto=$timecard->worker()->email_main;
                if (!email_validate($mailto)) $mailto=$timecard->worker()->email_veem;
                if (email_validate($mailto)){
                    try{
                        Mail::to($mailto)->send(new TimecardEndedWorker($timecard));
                    }catch(\Exception $e){
                        $myfile = fopen("job_failed.txt", "w");
                        fwrite($myfile, 'Failed to send email on TimecardEndedWorker. '.date('Y-m-d H:i:s',strtotime(Carbon::now())));
                        fclose($myfile);
                    }
                }
                
            }
        } 
    }

    public function resetBillingCycleEndDate()
    {
         $hms=' 12:00:00 ';
         $BD=Globals::first();
         $next_enddate=date('Y-m-d',strtotime('+13days'));
         $now=date('Y-m-d',strtotime(Carbon::now()));
         $period_date=date('Y-m-d',strtotime($BD->billing_cycle_end_date.$hms.'+1days'));
         if ($period_date==$now){
            $BD->billing_cycle_end_date=$next_enddate;
            $BD->save();
         }

         $clients=Clients::where('deleted_at',null)->get();
         foreach ($clients as $client) {
             $period_date=date('Y-m-d',strtotime($client->billing_cycle_next_end_date.$hms.'+1days'));
             if ($period_date==$now){
                // Check if asigned worker does not have timesheet
                    $workers=$client->workers();
                    foreach ($workers as $worker) {
                        $clientinfo = $client->assigned_worker_info()->where('workers_id', $worker->id)->first();
                        if ($clientinfo->status=='inactive') {
                            continue;
                        }
                        $timecard=$client->timecards()->where('workers_id', $worker->id)->where('end_date', $client->billing_cycle_next_end_date)->first();
                        if ($timecard) continue;
                        $timecard=TimeCards::create();
                        $timecard->workers_id=$worker->id;
                        $timecard->clients_id=$client->id;
                        $timecard->status='pending_worker';
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
                            $timesheet->status='pending_worker';
                            $timesheet->time_cards_id=$timecard->id;
                            $timesheet->save();
                            $each_date = date('Y-m-d',strtotime($start_date.$hms.'+'.$d.'days'));
                            if ($each_date == $end_date) break;
                        }
                        $timecard->save();
                    }
                //////////////////////////////////////////////////
                if ($client->billing_cycle_type == 'semi-monthly') {
                    $next_enddate = date('Y-m-t');
                    $today = (int) date('d');
                    if ($today<16) {
                        $next_enddate = date('Y-m-15');
                    }
                 }
                $client->billing_cycle_next_end_date=$next_enddate;

                $client->save();
                if ($client->accountmanager()) {
                    $mailto=$client->accountmanager()->email;
                    if (email_validate($mailto)){
                        try{
                            Mail::to($mailto)->send(new BillingcycleEndedAccountmanager($client));
                        }catch(\Exception $e){
                            $myfile = fopen("job_failed.txt", "w");
                            fwrite($myfile, 'Failed to send email on BillingcycleEndedAccountmanager. '.date('Y-m-d H:i:s',strtotime(Carbon::now())));
                            fclose($myfile);
                        }
                    }
                }
            }
        }
    }

    public function newTimesheet()
    {
         $hms=' 12:00:00 ';
         $clients=Clients::where('deleted_at',null)->get();
         foreach ($clients as $client) {
            $workers=$client->workers();
            foreach ($workers as $worker) {
                $clientinfo = $client->assigned_worker_info()->where('workers_id', $worker->id)->first();
                if ($clientinfo->status=='inactive') {
                    continue;
                }
                $timecard=$client->timecards()->where('workers_id', $worker->id)->where('end_date', $client->billing_cycle_next_end_date)->where('status', 'logtime')->first();
                if ($timecard) continue;
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
                    $each_date = date('Y-m-d',strtotime($start_date.$hms.'+'.$d.'days'));
                    if ($each_date == $end_date) break;
                }
                $timecard->save();
            }
        }
    }
}


function email_validate($email){
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    return true;
}