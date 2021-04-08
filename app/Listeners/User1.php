<?php

namespace App\Listeners;

use App\Events\CronStarted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class User1
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
//        echo $event->user->id."<br/>";


        for($i=0;$i<10;$i++)
        {
            ob_start();
            echo $event->user->id."==$i<br/>";
            flush();
            sleep(1);
        }
    }
}
