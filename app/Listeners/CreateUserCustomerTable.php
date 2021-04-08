<?php

namespace App\Listeners;

use App\Events\UserWasCreated;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Schema;

class CreateUserCustomerTable
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
        Schema::create('customers_'.$event->user->id, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('AddressLine1')->nullable();
            $table->string('AddressLine2')->nullable();
            $table->string('AddressLine3')->nullable();
            $table->string('City')->nullable();
            $table->string('County')->nullable();
            $table->string('District')->nullable();
            $table->string('StateOrRegion')->nullable();
            $table->string('PostalCode')->nullable();
            $table->string('CountryCode')->nullable();
            $table->string('Phone')->nullable();
            $table->timestamps();
        });
    }
}
