<?php

namespace App\Listeners;

use App\Events\UserWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
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
     * @param  UserWasCreated  $event
     * @return void
     */
    public function handle(UserWasCreated $event)
    {
//       $user = $event->user;
//       $pass = $event->pass;
//       Mail::send('layouts.email.welcome', ['user' => $user,'pass'=>$pass], function ($m) use ($user) {
//           $m->to($user->email, $user->first_name)->subject('EcommElite- Registration Completed!');
//       });
    }
}
