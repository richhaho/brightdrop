<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Jobs\TimeCardEnded;
use App\Jobs\ResetBillingCycleEndDate;
use App\Jobs\NewTimesheet;
use App\Jobs\GeneratePTOPaymentEndYear;
use App\Jobs\SetClientAndWorkerName;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $testDate = date('Y-m-d H:i:s', strtotime(\Carbon\Carbon::now())+30*60*60*24);
        // \Carbon\Carbon::setTestNow($testDate);
        
        $schedule->job(new TimeCardEnded())->dailyAt('04:00'); 
        $schedule->job(new TimeCardEnded())->dailyAt('04:05');
        $schedule->job(new TimeCardEnded())->dailyAt('04:10');
        
        $schedule->job(new ResetBillingCycleEndDate())->dailyAt('04:30'); 
        $schedule->job(new ResetBillingCycleEndDate())->dailyAt('04:40'); 
        $schedule->job(new ResetBillingCycleEndDate())->dailyAt('04:50'); 
        
        $schedule->job(new NewTimesheet())->dailyAt('22:00');
        $schedule->job(new NewTimesheet())->dailyAt('22:10');
        $schedule->job(new NewTimesheet())->dailyAt('22:20');

        $schedule->job(new SetClientAndWorkerName())->everyFiveMinutes();

        // $schedule->job(new GeneratePTOPaymentEndYear())->dailyAt('22:30');
    }
    












    protected function load()
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
