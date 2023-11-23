<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Workers;
use App\Clients;
use App\TimeCards;
use Illuminate\Support\Facades\Mail;
use App\Mail\TimecardEndedWorker;
use Carbon\Carbon;
use App\ClientInfoWorkers;

class TimeCardEnded implements ShouldQueue
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
        $timecards=TimeCards::where('status','logtime')->get();
        $now=date('Y-m-d',strtotime(Carbon::now()));

        foreach ($timecards as $timecard) {
            $period_date=date('Y-m-d',strtotime($timecard->end_date.$hms.'+1days'));
            if ($period_date<=$now){
                // $clientinfo = ClientInfoWorkers::where('clients_id', $timecard->clients_id)->where('workers_id', $timecard->workers_id)->first();
                // if (!$clientinfo) continue;
                // if ($clientinfo->status=='inactive') continue;

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

}
    function email_validate($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
        return true;
    }
