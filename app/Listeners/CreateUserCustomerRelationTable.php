<?php

namespace App\Listeners;

use App\Events\UserWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCustomerRelationTable
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
        $user = $event->user;
        Schema::create('customer_order_'.$user->id, function (Blueprint $table) use ($user) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->string('order_id');
            $table->foreign('customer_id')->references('id')->on('customers_'.$user->id)->onDelete('cascade');
            $table->foreign('order_id')->references('order_id')->on('orders_'.$user->id)->onDelete('cascade');
            $table->timestamps();
        });
    }
}
