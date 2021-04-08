<?php

namespace App\Commands;

use App\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;

class get_api_new_orders extends Command implements SelfHandling
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $path = set_realpath('application/third_party/MarketplaceWebServiceOrders/Samples/').'.config.inc.php';
        require_once($path);

        $serviceUrl = "https://mws.amazonservices.com/Orders/2013-09-01";
        // Europe
        //$serviceUrl = "https://mws-eu.amazonservices.com/Orders/2013-09-01";
        // Japan
        //$serviceUrl = "https://mws.amazonservices.jp/Orders/2013-09-01";
        // China
        //$serviceUrl = "https://mws.amazonservices.com.cn/Orders/2013-09-01";

        $config = array (
            'ServiceURL' => $serviceUrl,
            'ProxyHost' => null,
            'ProxyPort' => -1,
            'ProxyUsername' => null,
            'ProxyPassword' => null,
            'MaxErrorRetry' => 3,
        );

        $users = User::where('id','<>','2')->get();

        echo "<h1>Getting New Sales</h1>";
        foreach($users as $user) {

            $user_id = $user['ID'];


//            $settings = $CI->Model_setting->get_setting($user_id);
            $settings = $user->AmazonSettings()->get()->first();


            if($settings) {
                if(isset($settings['merchant_id']) && trim($settings['merchant_id'])!='' &&
                    isset($settings['marketplace_id']) && trim($settings['marketplace_id'])!='' &&
                    isset($settings['access_key']) && trim($settings['access_key'])!='' &&
                    isset($settings['secret_key']) && trim($settings['secret_key'])!='') {

                    //define ('MERCHANT_ID', trim($settings['merchant_id']));
                    //define ('MARKETPLACE_ID', trim($settings['marketplace_id']));
                    //define('AWS_ACCESS_KEY_ID', trim($settings['access_key']));
                    //define('AWS_SECRET_ACCESS_KEY', trim($settings['secret_key']));

                    $_MERCHANT_ID = trim($settings['merchant_id']);
                    $_MARKETPLACE_ID = trim($settings['marketplace_id']);
                    $_AWS_ACCESS_KEY_ID = trim($settings['access_key']);
                    $_AWS_SECRET_ACCESS_KEY = trim($settings['secret_key']);

                    $service = new MarketplaceWebServiceOrders_Client(
                        $_AWS_ACCESS_KEY_ID,
                        $_AWS_SECRET_ACCESS_KEY,
                        APPLICATION_NAME,
                        APPLICATION_VERSION,
                        $config);

                    $request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
                    $request->setSellerId($_MERCHANT_ID);
                    $request->setMaxResultsPerPage(60);
                    // object or array of parameters
                    $request->setMarketplaceId( $_MARKETPLACE_ID );
                    $last_date = $CI->Model_sales->get_latest_new_sale_date($user_id);
                    echo "LATEST NEW DATE: ".$last_date."<br/>";
//                echo $last_date;exit;
                    $time_offset = 1;
                    $seconds = date('Y-m-d',strtotime($last_date)-$time_offset).'T'.date('H:i:s',strtotime($last_date)-$time_offset).'Z';
                    //echo $seconds;exit;
                    $request->setCreatedAfter( $seconds );
                    invokeListOrders($service, $request, $user_id, $_MERCHANT_ID , 'new');
                }
            }
        }
    }
}
