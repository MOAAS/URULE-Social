<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
        //$schedule->command('demo:cron')->everyMinute();
        //$schedule->call(function () {
        //   DB::select("INSERT INTO announcement (author_id,date_of_creation,duration_secs,content) VALUES (1, '2020-5-10T08:00:00-07:00',3600000, 'Server maintnence in 1 houuur')");
        //})->everyFiveMinutes();

         $schedule->call(function () {
            DB::select('REFRESH MATERIALIZED VIEW post_comments_view');
         })->everyFiveMinutes();
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
