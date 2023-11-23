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
use Illuminate\Support\Facades\Mail;
use App\Mail\BillingcycleEndedAccountmanager;
use Carbon\Carbon;

class ResetBillingCycleEndDate implements ShouldQueue
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
             $next_enddate=date('Y-m-d',strtotime('+13days'));
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
}
     
