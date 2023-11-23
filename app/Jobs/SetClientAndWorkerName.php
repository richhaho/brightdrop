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

class SetClientAndWorkerName implements ShouldQueue
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
        $timecards=TimeCards::where('client_name',null)->get();
        foreach ($timecards as $timecard) {
            $client = Clients::where('id', $timecard->clients_id)->first();
            $worker = Workers::where('id', $timecard->workers_id)->first();
            if (!$client || !$worker) continue;
            $timecard->client_name = $client->client_name;
            $timecard->worker_name = $worker->fullname;
            $timecard->save();
        }
    }
}
     
