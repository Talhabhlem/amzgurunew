<?php

namespace App\Listeners;

use App\Events\CronStarted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class User3
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CronStarted  $event
     * @return void
     */
    public function handle(CronStarted $event)
    {
        //
    }
}
