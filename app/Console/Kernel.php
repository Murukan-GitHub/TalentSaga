<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        Commands\AutoFinishBooking::class,
        Commands\SendNewsletter::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // AutoFinish Booking
        $schedule->command('booking:finish')->hourly();
        // Newsletter Execution
        $schedule->command('newsletter:send')->dailyAt(settings('newsletter_send_hour', '07').':'.settings('newsletter_send_minute', '00'));
    }
}
