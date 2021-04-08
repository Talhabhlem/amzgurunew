<?php

namespace App\Console\Commands;

use App\Helpers\TeHelper;
use Illuminate\Console\Command;

class SendEmailAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email_alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email alerts to users.';

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
        \AmazonHelper::send_email_alerts();
    }
}
