<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetApiNewOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_api_new_orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get_api_new_orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \AmazonHelper::get_api_new_orders();
    }
}
