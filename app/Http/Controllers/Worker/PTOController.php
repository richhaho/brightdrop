<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\Clients;
use App\Workers;
use App\PTO;
use Auth;
use Carbon\Carbon;

class PTOController extends Controller
{
    public function paidTimeOff()
    {
        $worker=Auth::user()->worker();
        $clients=$worker->clients();
        $clients_list=$worker->clients()->pluck('client_name','id');
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
            'worker'=>$worker,
            'clients'=>$clients,
            'clients_list'=>$clients_list,
            'total_hours'=>$total_hours,
        ];
        return view('worker.paidTimeOff',$data);
    }
    public function summaryPTO()
    {
        // ==== Set testing date ====
        $testDate = env('TEST_SET_CURRENT_DATE');
        //if ($testDate) Carbon::setTestNow($testDate);     
        // ========================== 
        $worker=Auth::user()->worker();
        $clients=$worker->activeClients();
        $clients_list=Clients::where('deleted_at',null)->pluck('client_name','id');
        $Year=date('Y',strtotime(Carbon::now())).'-%';
         
        $pto_summaries=array();
        $y=date('y',strtotime(Carbon::now()));
        
        foreach ($clients as $client) {
            $info=$client->assigned_worker_info()->where('workers_id',$worker->id)->first();
            $currentyear=date('Y',strtotime(Carbon::now()));
            $hiredyear=date('Y',strtotime($info->hired_at));
            if ($currentyear==$hiredyear){
                $pto_days_worker_default=$info->ptodays_current_calendar;
            }else{
                $pto_days_worker_default=$info->ptodays_full_calendar;
            }
            //if ($pto_days_worker_default==0 && $info->ptodays_full_calendar==0) $pto_days_worker_default=$client->default_pto_days;
            if ($client->pto_infomation!='yes') continue;

            $pto_summary['title']='20'.$y.' -  PTO Summary: '.$client->client_name;
            $pto_timesheets=\App\TimeSheets::where(function($q) {
                $q->where('pto_time_hours', '>', 0)->orwhere('pto_time_hours_updated', '>', 0);
            })->where('clients_id', $client->id)->where('workers_id', $worker->id)->where('date', 'like', $Year)->get();
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
            $pto_summaries[]=$pto_summary;
        }

        $data=[
            'worker'=>$worker,
            'clients'=>$clients,
            'clients_list'=>$clients_list,
             
            'pto_summaries'=>$pto_summaries,

        ];
        return view('worker.summaryPTO',$data);
    }
    public function pastPTO()
    {
        $worker=Auth::user()->worker();
        $clients=$worker->clients();
        $clients_list=Clients::where('deleted_at',null)->pluck('client_name','id');
        $Year=date('Y',strtotime(Carbon::now())).'-%';
        $ptos=$worker->ptos()->where('status','!=','Pending Approval - BrightDrop')->where('date_pto','like',$Year)->get();
        $data=[
            'worker'=>$worker,
            'clients'=>$clients,
            'clients_list'=>$clients_list,
            'ptos'=>$ptos,
        ];
        return view('worker.pastPTO',$data);
    }
    public function pendingPTO()
    {
        $worker=Auth::user()->worker();
        $clients=$worker->clients();
        $clients_list=Clients::where('deleted_at',null)->pluck('client_name','id');
        $ptos=$worker->ptos()->where('status','Pending Approval - BrightDrop')->get();
         
        $data=[
            'worker'=>$worker,
            'clients'=>$clients,
            'clients_list'=>$clients_list,
            'ptos'=>$ptos,
        ];
        return view('worker.pendingPTO',$data);
    }

    public function submitPTO(Request $request)
    {
        $client_id=$request->clients_list;
        $date_pto=date('Y-m-d',strtotime($request->date_pto));
        $worker=Auth::user()->worker();
        $client=Clients::where('id',$client_id)->first();

        $info=$client->assigned_worker_info()->where('workers_id',$worker->id)->first();
        if (count($info)==0) {
            Session::flash('message', $client->client_name.' did not assigned the worker '.$worker->fullname);
            return redirect()->route('worker.paidTimeOff');
        }
        
        if ($client->pto_infomation!='yes') {
            Session::flash('message', $client->client_name.' did not approve PTO days on their profile.');
            return redirect()->route('worker.paidTimeOff');
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
            return redirect()->route('worker.paidTimeOff');
        }
        $pto_sum=$worker->ptos()->where('clients_id',$client->id)->where('status','!=','Declined')->sum('total_hours')+$request->total_hours;

        if($pto_sum>$pto_days_worker_default*8){
            Session::flash('message', "You requested too many hours for PTO than client's default pto days.");
            return redirect()->route('worker.paidTimeOff');
        }
        
        $pto=PTO::where('clients_id',$client_id)->where('workers_id',$worker->id)->where('date_pto',$date_pto)->first();
        if(count($pto)==0){
            $pto=PTO::create();
        }
        $pto->clients_id=$client_id;
        $pto->workers_id=$worker->id;
        $pto->date_pto=$date_pto;
        $pto->status='Pending Approval - BrightDrop';
        $pto->reason=$request->reason;
        $pto->total_hours=$request->total_hours;
        $pto->save();

        Session::flash('message', $pto->total_hours.' Hours have been submitted for PTO.');
        return redirect()->route('worker.pendingPTO');
    }

    public function exist_pto(Request $request){
        $client_id=$request->client_id;
        $date_pto=$request->date;
        $worker=Auth::user()->worker();
        $pto=PTO::where('clients_id',$client_id)->where('workers_id',$worker->id)->where('date_pto',$date_pto)->first();
        return response()->json($pto);
    }

}
