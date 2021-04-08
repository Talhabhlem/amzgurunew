<?php

namespace App\Listeners;

use App\Events\UserWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUserSalesTable
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
        Schema::create('sales_'.$event->user->id, function($table)
        {
            $table->increments('id');
            $table->string('item_id')->nullable();
            $table->string('order_id')->nullable();
            $table->decimal('order_total', 10, 2)->nullable();
            $table->string('title')->nullable();
            $table->string('sku')->nullable();
            $table->string('asin')->nullable();
            $table->integer('qty')->unsigned()->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('shipping_price', 10, 2)->nullable();
            $table->decimal('gift_wrap_price', 10, 2)->nullable();
            $table->decimal('shipping_discount', 10, 2)->nullable();
            $table->string('currency_code')->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->timestamp('loaded_on')->nullable();
            $table->string('status')->nullable();
            $table->string('sale_type')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable();
        });
    }
}
