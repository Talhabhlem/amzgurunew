<?php

namespace App\Events;

use App\Events\Event;
use Askedio\Laravelcp\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserWasCreated extends Event
{
    use SerializesModels;

    public $user;
    public $pass;
    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(User $user,$pass='')
    {
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
