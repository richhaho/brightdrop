<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Clients;
use App\Workers;
use App\PTO;
use Auth;
use Mail;
use App\Mail\PTOStatusWorker;
use Carbon\Carbon;
class PTOController extends Controller
{
    public function PTOneedsApproval()
    {
        $workers=Workers::where('deleted_at',null)->get();
        $data=[
            'workers'=>$workers,

        ]; 
        return view('admin.pto.PTONeedsApproval',$data);
        
    }

    public function approvePTO(Request $request)
    {
        $id= $request->pto_id;

        $pto=PTO::where('id',$id)->first();
        $mailto=$pto->worker()->email_main;
        //Mail::to($mailto)->send(new PTOStatusWorker($pto,'approved'));
        $pto->status='Approved';
        
        
        $pto->save();
        Session::flash('message', $pto->total_hours.' Hours approved for PTO.'); 
        return redirect()->route('admin.PTONeedsApproval');
    }
    public function declinePTO(Request $request)
    {
        $id= $request->pto_id;

        $pto=PTO::where('id',$id)->first();
        $mailto=$pto->worker()->email_main;
        //Mail::to($mailto)->send(new PTOStatusWorker($pto,'declined'));
        $pto->status='Declined';
        
        $pto->save();
        
        Session::flash('message', $pto->total_hours.' Hours declined for PTO.'); 
        return redirect()->route('admin.PTONeedsApproval');
    }


    public function PTOOverride()
    {
        $workers=Workers::where('deleted_at',null)->get();
        $clients=Clients::where('deleted_at',null)->get();
        $clients_list=$clients->pluck('client_name','id');
        $workers_list=$workers->pluck('fullname','id');

        $total_hours=[
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',            
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            
        ];
        

        $data=[
            'workers'=>$workers,
            'workers_list'=>$workers_list,
            'clients'=>$clients,
            'clients_list'=>$clients_list,
            'total_hours'=>$total_hours,
             

        ];
        return view('admin.pto.PTOOverride',$data);
    }
    public function PTOpast()
    {
        $workers=Workers::where('deleted_at',null)->get();
        $clients=Clients::where('deleted_at',null)->get();
        $clients_list=$clients->pluck('client_name','id');
        $workers_list=$workers->pluck('fullname','id');
        $ptos=array();
        $Year=date('Y',strtotime(Carbon::now())).'-%';
        foreach ($workers as $worker) {
            $ptos[]=$worker->ptos()->where('status','!=','Pending Approval - BrightDrop')->where('date_pto','like',$Year)->get();            
        }
        
         

        $data=[
            'workers'=>$workers,
            'workers_list'=>$workers_list,
            'clients'=>$clients,
            'clients_list'=>$clients_list,
            'ptos'=>$ptos,
            
        ];
        return view('admin.pto.PTOpast',$data);
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
        if ($request->has('status')) {
            if($request->status) {
                session([$page.'.status' => $request->status]);
            }
        }
        if ($request->has('year')) {
            if($request->year) {
                session([$page.'.year' => $request->year]);
            }
        }
        return redirect()->route('admin.'.$page);
    }
    public function resetfilter (Request $request) {
        $page = $request->page;
        session()->forget($page);
        return redirect()->route('admin.'.$page);
    }

    public function PTOsummary()
    {
        // ==== Set testing date ====
        $testDate = env('TEST_SET_CURRENT_DATE');
        if ($testDate) Carbon::setTestNow($testDate);     
        // ========================== 
        
        $search_clients=Clients::where('deleted_at',null)->orderBy('client_name', 'ASC')->get()->pluck('client_name','id')->prepend('','');

        $clients_id = session('PTOsummary.clients_id');
        $workers_id = session('PTOsummary.workers_id');
        // $status = session('PTOsummary.status');
        $year = session('PTOsummary.year');

        $search_workers = [];
        $client = null;
        if ($clients_id) {
            $client = Clients::where('id',$clients_id)->first();
            $search_workers = $client->assignedWorkers()->pluck('fullname','id')->prepend('','');
        }

        $pto_summaries=array();
        $years = ['' => ''];
        for ($y=2020; $y<=date('Y'); $y++) {
            $years["$y"] = "$y";
        }
        $data=[
            'years'=>$years,
            'pto_summaries'=>$pto_summaries,
            'search_clients'=>$search_clients,
            'search_workers'=>$search_workers,
            'clients_id'=>$clients_id,
            'workers_id'=>$workers_id,
            // 'status'=>$status,
            'year'=>$year,
        ];

        if (!$clients_id || !$workers_id || !$year) {
            return view('admin.pto.PTOsummary',$data);
        }

        $worker = Workers::where('id', $workers_id)->first();
        if (!$client || !$worker) {
            return view('admin.pto.PTOsummary',$data);
        }
        
        $info=$client->assigned_worker_info()->where('workers_id',$worker->id)->first();

        // if ($info->status != $status) {
        //     return view('admin.pto.PTOsummary',$data);
        // }

        $Year = $year;
        $currentyear=$Year;
        $hiredyear=date('Y',strtotime($info->hired_at));
        if ($currentyear==$hiredyear){
            $pto_days_worker_default=$info->ptodays_current_calendar;
        }else{
            $pto_days_worker_default=$info->ptodays_full_calendar;
        }

        if ($client->pto_infomation!='yes') {
            return view('admin.pto.PTOsummary',$data);
        }

        $pto_summary['title']=$Year.' -  PTO Summary: [Client: '.$client->client_name.'], [Worker:'.$worker->fullname().']';
        $pto_summary['client']=$client->id;
        $pto_summary['worker']=$worker->id;
        $pto_summary['status']=$info->status;
        $pto_summary['year']=$Year;

        $pto_timesheets=\App\TimeSheets::where(function($q) {
            $q->where('pto_time_hours', '>', 0)->orwhere('pto_time_hours_updated', '>', 0);
        })->where('clients_id', $clients_id)->where('workers_id', $workers_id)->where('date', '>=', $Year.'-01-01')->where('date', '<=', $Year.'-12-31')->get();
        $pto_used = array();
        $pto_sum = 0;
        foreach ($pto_timesheets as $pto_timesheet) {
            if ($pto_timesheet->status=='approved' && $pto_timesheet->pto_time_hours>0 && $pto_timesheet->pto_time_hours_updated===null) {
                $pto_used[]=[
                    'date' => $pto_timesheet->date,
                    'hours' => $pto_timesheet->pto_time_hours
                ];
                $pto_sum+=$pto_timesheet->pto_time_hours;
            }
            if ($pto_timesheet->pto_time_hours_updated>0) {
                $pto_used[]=[
                    'date' => $pto_timesheet->date,
                    'hours' => $pto_timesheet->pto_time_hours_updated
                ];
                $pto_sum+=$pto_timesheet->pto_time_hours_updated;
            }
        }
        $pto_remain=$pto_days_worker_default*8-$pto_sum;
        $pto_remaining=intval($pto_remain/8).' Days, '.($pto_remain-intval($pto_remain/8)*8).' Hours';
        $pto_summary['pto_used']=$pto_used;
        $pto_summary['pto_remaining']=$pto_remaining;
        $pto_summary['pto_all_hours']=$pto_days_worker_default*8;
        $pto_summaries[]=$pto_summary;

        $data['pto_summaries'] = $pto_summaries;
        return view('admin.pto.PTOsummary',$data);
    }
    public function updatePTOsummary(Request $request)
    {
        $client_id=$request->client_id;
        $worker_id=$request->worker_id;
        $worker=Workers::where('id',$worker_id)->first();
        $client=Clients::where('id',$client_id)->first();
        $info=$client->assigned_worker_info()->where('workers_id',$worker->id)->first();
        $currentyear=date('Y',strtotime(Carbon::now()));
        $hiredyear=date('Y',strtotime($info->hired_at));
        if ($currentyear==$hiredyear){
            $pto_days_worker_default=$info->ptodays_current_calendar;
        }else{
            $pto_days_worker_default=$info->ptodays_full_calendar;
        }

        $removed = false;
        if (isset($request->origin_pto_date)) {
            if (count($request->origin_pto_date)>count($request->pto_date)) {
                $removed = true;
            }
        }
        $total = 0;
        if (isset($request->pto_date)) {
            foreach ($request->pto_date as $key => $value) {
                $total += $request->pto_hours[$key];
            }
        }
        if ($total>$pto_days_worker_default*8 && !$removed) {
            Session::flash('message', 'You added more time than remaining hours. Please remove some rows to update PTO.');
            return redirect()->route('admin.PTOsummary');
        }

        $unexist_date = array();
        if (isset($request->origin_pto_date)) {
            foreach ($request->origin_pto_date as $key => $value) {
                $timesheets = $worker->timesheets()->where('clients_id',$client_id)->where('date',$request->origin_pto_date[$key])->get();
                if (!count($timesheets)) {$unexist_date[]=$request->origin_pto_date[$key] ;continue;}
                foreach($timesheets as $timesheet) {
                    $timesheet->pto_time_hours_updated = 0;
                    $timesheet->save();
                }
            }
        }
        if (isset($request->pto_date)) {
            foreach ($request->pto_date as $key => $value) {
                $timesheet = $worker->timesheets()->where('clients_id',$client_id)->where('date',$request->pto_date[$key])->first();
                if (!$timesheet) {$unexist_date[]=$request->pto_date[$key] ;continue;}
                $timesheet->pto_time_hours_updated = $request->pto_hours[$key];
                $timesheet->save();
            }
        }
        if (count($unexist_date)>0) {
            Session::flash('message', 'The worker has no timesheet on the given date on '.join(', ',$unexist_date).', the PTO cannot be saved.');
        } else {
            Session::flash('message', 'PTO Summary was updated.');
        }
        return redirect()->route('admin.PTOsummary');
    }

    public function submitPTO(Request $request)
    {
        $client_id=$request->clients_list;
        $worker_id=$request->workers_list;
 
        $date_pto=date('Y-m-d',strtotime($request->date_pto));

        $worker=Workers::where('id',$worker_id)->first();
        $clients_ids=explode(",",$worker->clients_ids);
        $assigned = array_search($client_id,$clients_ids);
        if ($assigned === false) {
            Session::flash('message', 'This worker is not assigned to the selected client.');
            return redirect()->route('admin.PTOOverride');
        }

        $client=Clients::where('id',$client_id)->first();
        $info=$client->assigned_worker_info()->where('workers_id',$worker->id)->first();
        if (count($info)==0) {
            Session::flash('message', $client->client_name.' did not assigned the worker '.$worker->fullname);
            return redirect()->route('admin.PTOOverride');
        }
        if ($client->pto_infomation!='yes') {
            Session::flash('message', $client->client_name.' did not approve PTO days on their profile.');
            return redirect()->route('admin.PTOOverride');
        }
        $currentyear=date('Y',strtotime(Carbon::now()));
        $hiredyear=date('Y',strtotime($info->hired_at));
        if ($currentyear==$hiredyear){
            $pto_days_worker_default=$info->ptodays_current_calendar;
        }else{
            $pto_days_worker_default=$info->ptodays_full_calendar;
        }
        //if ($pto_days_worker_default==0 && $info->ptodays_full_calendar==0) $pto_days_worker_default=$client->default_pto_days;

        if ($pto_days_worker_default==0) {
            Session::flash('message', 'There is not sufficient PTO hours to fulfill your request with '.$client->client_name);
            return redirect()->route('admin.PTOOverride');
        }
        $pto_sum=$worker->ptos()->where('clients_id',$client->id)->where('status','!=','Declined')->sum('total_hours')+$request->total_hours;

        if($pto_sum>$pto_days_worker_default*8){
            Session::flash('message', "You requested too many hours for PTO than client's default pto days.");
            return redirect()->route('admin.PTOOverride');
        }


        $pto=PTO::where('clients_id',$client_id)->where('workers_id',$worker->id)->where('date_pto',$date_pto)->first();
        if(count($pto)==0){
            $pto=PTO::create();
        }
        $pto->clients_id=$client_id;
        $pto->workers_id=$worker->id;
        $pto->date_pto=$date_pto;
        $pto->status='Pending Approval - BrightDrop';
        $pto->status='Approved';
        $pto->reason=$request->reason;
        $pto->total_hours=$request->total_hours;
        $pto->save();

        Session::flash('message', $pto->total_hours.' Hours have been submitted for PTO.');
        return redirect()->route('admin.PTOOverride');
    }
    public function exist_pto(Request $request){
        $client_id=$request->client_id;
        $worker_id=$request->worker_id;
        $date_pto=$request->date;
        
        $pto=PTO::where('clients_id',$client_id)->where('workers_id',$worker_id)->where('date_pto',$date_pto)->first();
        return response()->json($pto);
    }

}
