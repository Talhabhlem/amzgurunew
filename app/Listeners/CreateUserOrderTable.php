<?php

namespace App\Listeners;

use App\Events\UserWasCreated;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Schema;

class CreateUserOrderTable
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
        Schema::create('orders_'.$event->user->id, function (Blueprint $table) {
            $table->string('order_id');
            $table->dateTime('purchase_date')->nullable();
            $table->dateTime('LastUpdateDate')->nullable();
            $table->string('FulfillmentChannel')->nullable();
            $table->string('SalesChannel')->nullable();
            $table->string('ShipServiceLevel')->nullable();
            $table->string('OrderChannel')->nullable();
            $table->string('TFMShipmentStatus')->nullable();
            $table->string('CbaDisplayableShippingLabel')->nullable();
            $table->string('OrderType')->nullable();
            $table->dateTime('EarliestShipDate')->nullable();
            $table->dateTime('EarliestDeliveryDate')->nullable();
            $table->decimal('OrderTotal',10,2);
            $table->string('CurrencyCode')->nullable();
            $table->dateTime('LatestDeliveryDate')->nullable();
            $table->dateTime('created_on')->nullable();
            $table->string('bug')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->primary('order_id');
        });
    }
}
