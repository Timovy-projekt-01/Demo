<?php

namespace App\Console;

use App\Console\Commands\UpdateMalwareCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\StartBlazeGraph::class,
        // Other commands...
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        //todo 2 separate schedules for parse and upload imho idk
        $schedule->command(UpdateMalwareCommand::class)
            ->pingBefore('localhost:9999')
            ->sendOutputTo('malware_update.log')
            ->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
