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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\SendEmailAlert::class,
        \App\Console\Commands\CheckPendingOrders::class,
        \App\Console\Commands\GetApiNewOrders::class,
        \App\Console\Commands\GetApiOldOrders::class,
        \App\Console\Commands\FetchUpdates::class,
        \App\Console\Commands\AddCategoriesToProductKeywordList::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
            // $schedule->command('email_alerts')->withoutOverlapping()->sendOutputTo('public/cron-output/email_alerts.html');
            // $schedule->command('email_alerts')->daily()->withoutOverlapping()->sendOutputTo('public/cron-output/email_alerts.html')->emailOutputTo('ihassanusmani@gmail.com');
            // $schedule->command('command:check_pending_orders')->everyTenMinutes()->withoutOverlapping()->sendOutputTo('public/cron-output/check_pending_orders.html')->emailOutputTo('ihassanusmani@gmail.com');
            // $schedule->command('command:get_api_new_orders')->everyTenMinutes()->withoutOverlapping()->sendOutputTo('public/cron-output/get_api_new_orders.html')->emailOutputTo('ihassanusmani@gmail.com');
        $schedule->command('command:get_api_old_orders')->everyTenMinutes()->withoutOverlapping()->sendOutputTo('public/cron-output/get_api_old_orders.html')->emailOutputTo('ihassanusmani@gmail.com');
        $schedule->command('fetch:updates')->name('fetch:updates')->withoutOverlapping()->everyMinute();
        $schedule->command('backend:addCategoriesToProductKeywordList')->name('backendaddCategoriesToProductKeywordList')->withoutOverlapping()->everyMinute();
    }
}
