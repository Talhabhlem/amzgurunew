<?php

use App\Events\UserWasCreated;
use App\Helpers\TeHelper;
use App\Sale;
use App\Order;
use App\Customer;
use Askedio\Laravelcp\Models\User;
use Askedio\Laravelcp\Models\Role;
use Askedio\Laravelcp\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AmazonHelper
{
    public static function invokeListOrders(MarketplaceWebServiceOrders_Interface $service, $request, $user_id = '', $merchant_id, $sale_type = 'new', $is_missing = false)
    {
        $user = User::findOrFail($user_id);
        try {
            $response = $service->ListOrders($request);
            $orders = $response->getListOrdersResult()->getOrders();
            ?>
            <table cellspacing="0" cellpadding="10" border="1">
                <?php
                foreach ($orders as $order) {

                    if ($is_missing) {
                        if (Sale::order_already_exists($order->getAmazonOrderId(), $user_id)) {
                            $this_user_latest_purchase_date = $order->getPurchaseDate();
                            Sale::update_missing_date($user_id, ['purchase_date' => $this_user_latest_purchase_date]);
                            continue;
                        }
                    }
                    $order_id = self::save_order($user_id, $order);
                    $customer_id = self::save_customer($user_id, $order);
                    self::save_order_customer_rel($order_id, $customer_id, $user_id);

                    if ($order->getOrderStatus() == 'Canceled') {
                        echo '<strong style="color:red;">deleted</strong>';
                        Sale::delete_sales_by_order_id($order->getAmazonOrderId(), $user_id);
                        continue;
                    } else if ($order->getOrderStatus() == 'Pending') {
                    }


                    $request = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
                    $request->setSellerId($merchant_id);
                    $request->setAmazonOrderId($order->getAmazonOrderId());
                    ?>
                    <tr>
                        <td>
                            <strong>User ID: </strong><?php echo $user_id; ?> <br/>
                            <strong>Sale Type: </strong><span style="color:red"> <?php echo $sale_type; ?></span> <br/>
                            <strong>Order ID: </strong><?php echo $order->getAmazonOrderId(); ?> <br/>
                            <strong>Buyer Name: </strong><?php echo $order->getBuyerName(); ?> <br/>
                            <strong>Buyer Email: </strong><?php echo $order->getBuyerEmail(); ?> <br/>
                            <strong>Purchase Date: </strong><?php echo $order->getPurchaseDate(); ?> <br/>
                            <strong>Order Status: </strong><?php echo $order->getOrderStatus(); ?> <br/>
                            <strong>Order Total: </strong><?php $orderTotal = $order->getOrderTotal();
                            echo $orderTotal ? $orderTotal->getAmount() : 0; ?> <br/>
                            <strong>NumberOfItemsShipped: </strong><?php echo $order->getNumberOfItemsShipped(); ?>
                            <br/>
                            <strong>NumberOfItemsUnshipped: </strong><?php echo $order->getNumberOfItemsUnshipped(); ?>
                            <br/>
                            <strong>OrderType: </strong><?php echo $order->getOrderType(); ?> <br/>
                            <table border="1" cellspacing="0" cellpadding="5">
                                <tr>
                                    <th>SKU</th>
                                    <th>ASIN</th>
                                    <th>Item ID</th>
                                    <th>Quantity Ordered</th>
                                    <th>Quantity Shipped</th>
                                    <th>Item Price</th>
                                    <th>Status</th>
                                </tr>
                                <?php
                                try {
                                    $orderItemsResponse = $service->ListOrderItems($request);

                                    $orderItems = $orderItemsResponse->getListOrderItemsResult()->getOrderItems();
                                    //p_rr($orderItems);
                                } catch (MarketplaceWebServiceOrders_Exception $exp) {

                                    echo("Caught Exception: " . $exp->getMessage() . "<br/>");
                                    echo("Response Status Code: " . $exp->getStatusCode() . "<br/>");
                                    echo("Error Code: " . $exp->getErrorCode() . "<br/>");
                                    echo("Error Type: " . $exp->getErrorType() . "<br/>");
                                    echo "AMAZON_EXCEPTION";
                                    if ('RequestThrottled' == $exp->getErrorCode()) {
                                        break;
                                    } else {
                                        continue;
                                    }
                                } catch (Exception $eee) {
                                    echo "ERROR";
                                    continue;
                                }

                                foreach ($orderItems as $item) {

//                                    ob_start();

                                    $itemTotal2 = $item->getShippingPrice();
                                    $itemTotal3 = $item->getGiftWrapPrice();
                                    $itemTotal4 = $item->getShippingDiscount();
                                    ?>
                                    <tr>
                                        <td><?php
                                            // p_rr($item);
                                            echo $item->getSellerSKU(); ?></td>
                                        <td><?php echo $item->getASIN(); ?></td>
                                        <td><?php echo $item->getOrderItemId(); ?></td>
                                        <td><?php echo $item->getQuantityOrdered(); ?></td>
                                        <td><?php echo $item->getQuantityShipped(); ?></td>
                                        <td><?php $itemTotal = $item->getItemPrice();
                                            echo $itemTotal ? $itemTotal->getAmount() : '0'; ?></td>
                                        <!--                                    <td>-->
                                        <?php //echo $itemTotal2 ? $itemTotal2->getAmount():'0';?><!--</td>-->
                                        <!--                                    <td>-->
                                        <?php //echo $itemTotal3 ? $itemTotal3->getAmount():'0';?><!--</td>-->
                                        <!--                                    <td>-->
                                        <?php //echo $itemTotal4 ? $itemTotal4->getAmount():'0';?><!--</td>-->


                                        <?php
                                        //                                        $sale['user_id']= $user_id;
                                        $sale['item_id'] = $item->getOrderItemId();
                                        $sale['order_id'] = $order->getAmazonOrderId();
                                        $sale['order_total'] = $orderTotal ? $orderTotal->getAmount() : 0;
                                        $sale['sku'] = $item->getSellerSKU();
                                        $sale['asin'] = $item->getASIN();
                                        $sale['title'] = $item->getTitle();
                                        $sale['qty'] = $item->getQuantityOrdered();
                                        $price = $item->getItemPrice();
                                        $sale['price'] = $price ? $price->getAmount() : 0;
                                        $sale['shipping_price'] = $itemTotal2 ? $itemTotal2->getAmount() : 0;
                                        $sale['gift_wrap_price'] = $itemTotal3 ? $itemTotal3->getAmount() : 0;
                                        $sale['shipping_discount'] = $itemTotal4 ? $itemTotal4->getAmount() : 0;
                                        $sale['currency_code'] = $price ? $price->getCurrencyCode() : '';
                                        $sale['purchase_date'] = $order->getPurchaseDate();
                                        $sale['sale_type'] = $sale_type;
                                        $sale['buyer_name'] = $order->getBuyerName() ? $order->getBuyerName() : '';
                                        $sale['buyer_email'] = $order->getBuyerEmail() ? $order->getBuyerEmail() : '';
                                        $statuss = false;
                                        ?>
                                        <td>

                                            <?php
                                            if ($order->getOrderStatus() == 'Pending') {
                                                if (!Sale::already_exists($sale['item_id'], $user_id)) {
//                                                    $p_sale['user_id']= $user_id;
                                                    $p_sale['item_id'] = $item->getOrderItemId();
                                                    $p_sale['order_id'] = $order->getAmazonOrderId();
                                                    $p_sale['sku'] = $item->getSellerSKU();
                                                    $p_sale['asin'] = $item->getASIN();
                                                    $p_sale['title'] = $item->getTitle();
                                                    $p_sale['qty'] = $item->getQuantityOrdered();
                                                    $p_sale['status'] = 'pending';
                                                    $p_sale['purchase_date'] = $order->getPurchaseDate();
                                                    $cup = Sale::get_previous_price($p_sale['sku'], $user_id);
                                                    $p_sale['price'] = $p_sale['qty'] * $cup;
                                                    $p_sale['sale_type'] = $sale_type;
                                                    $p_sale['buyer_name'] = $order->getBuyerName() ? $order->getBuyerName() : '';
                                                    $p_sale['buyer_email'] = $order->getBuyerEmail() ? $order->getBuyerEmail() : '';
                                                    echo "Inserted Pending";
                                                    $icup = (int)$cup;
                                                    echo " @ <strong style='" . ($cup == 0 ? 'color:red;' : '') . "' >$cup</strong>";
                                                } else {
                                                    $existed_sale = Sale::get_by($user_id, 'item_id', $sale['item_id']);
                                                    echo "Already Exists as <strong>" . $existed_sale->sale_type . "</strong>";
                                                    if ($existed_sale->sale_type == 'new' && $sale_type == 'old') {
                                                        $user->old_data_status = 'complete';
                                                        $user->update();
                                                    }
                                                }
                                            } else {
                                                if (!Sale::already_exists($sale['item_id'], $user_id)) {
                                                    Sale::insert($user_id, $sale);
                                                    $profit_setting = $user->ProfitSettings()->where('sku', '=', $sale['sku'])->get()->first();
                                                    $current_unit_price = round($sale['price'] / $sale['qty'], 2);
                                                    $_profit_row = array();
                                                    $_profit_row['user_id'] = $user_id;
                                                    $_profit_row['unit_price'] = $current_unit_price;
                                                    $_profit_row['fee'] = $current_unit_price * 0.15;
//                                                    $_profit_row['fulfilment'] =  $current_unit_price*0.15;
                                                    $_profit_row['sku'] = $sale['sku'];
                                                    $_profit_row['user_id'] = $user_id;
                                                    // $sku_profit_summary[$sale['sku']] = $_profit_row;
                                                    if ($profit_setting) {
                                                        $profit_setting->update($_profit_row);
                                                    } else {
                                                        $user->ProfitSettings()->create($_profit_row);
                                                    }
                                                    $statuss = true;
                                                    echo "inserted";
                                                    echo " @ <strong style='" . ($current_unit_price == 0 ? 'color:red;' : '') . "' >$current_unit_price</strong>";
                                                } else {
                                                    $existed_sale = Sale::get_by($user_id, 'item_id', $sale['item_id']);
                                                    echo "Already Exists as <strong>" . $existed_sale->sale_type . "</strong>";
                                                    if ($existed_sale->sale_type == 'new' && $sale_type == 'old') {
                                                        $user->old_data_status = 'complete';
                                                        $user->update();
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $this_user_latest_purchase_date = $order->getPurchaseDate();
                                    if ($is_missing) {
                                        Sale::update_missing_date($user_id, ['purchase_date' => $this_user_latest_purchase_date]);
                                    }
//                                    echo ob_get_clean();
//                                    flush();
                                }

                                ?>
                            </table>
                        </td>
                    </tr>
                    <?php

                    //print_r($orderItems);exit;
                    //}
                }
                ?>
            </table>
            <?php
        } catch
        (Exception $ex) {
            echo("<strong> Invoke Order Methods Caught Exception</strong>: " . $ex->getMessage() . "\n");
        } catch (MarketplaceWebServiceOrders_Exception $ex) {
            echo("<strong> Invoke Order Methods Caught Exception</strong>: " . $ex->getMessage() . "\n");
            echo("Response Status Code: " . $ex->getStatusCode() . "\n");
        }

    }

    public static function compare_order_id()
    {
        $orders_str = file_get_contents("http://stats.ecommelite.com/orders.txt");
        $orders_objs = json_decode($orders_str);
        $orders = array();
        foreach ($orders_objs->data as $obj) {
            $orders[] = $obj->order_id;
        }

        $user_id = 3;
        set_time_limit(0);
        echo "Function called for Pending Orders Check <br/>";
        $path = app_path('third_party/MarketplaceWebServiceOrders/Samples/') . '.config.inc.php';
        require_once($path);
        $users = User::GetAllUsersExceptAdmin();
        $user = User::findOrFail($user_id);
        $users = array($user);
        foreach ($users as $user) {
            echo "<br/>checking pending orders for user: " . $user->id . ' - ' . $user->email . "<br>";
            $user_id = $user->id;
            //if($user_id!=63) continue;

            $args = array();
            $args['user_id'] = $user_id;
            $args['bug'] = 'yes'; // getting orders without bug
            $args['order'] = 'ASC';
            $args['order_by'] = 'purchase_date';
            $args['offset'] = 0;
            $args['limit'] = 5;
            $user_pending_orders = Order::get_all($args);
            $settings = $user->AmazonSettings()->get()->first();
//            p_rr($settings);exit;
            if ($settings) {
                if (isset($settings['merchant_id']) && trim($settings['merchant_id']) != '' &&
                    isset($settings['marketplace_id']) && trim($settings['marketplace_id']) != '' &&
                    isset($settings['access_key']) && trim($settings['access_key']) != '' &&
                    isset($settings['secret_key']) && trim($settings['secret_key']) != ''
                ) {

                    $_MERCHANT_ID = trim($settings['merchant_id']);
                    $_MARKETPLACE_ID = trim($settings['marketplace_id']);
                    $_AWS_ACCESS_KEY_ID = trim($settings['access_key']);
                    $_AWS_SECRET_ACCESS_KEY = trim($settings['secret_key']);

                    $serviceUrl = $settings->region . "Orders/2013-09-01";

                    // Europe
                    //$serviceUrl = "https://mws-eu.amazonservices.com/Orders/2013-09-01";
                    // Japan
                    //$serviceUrl = "https://mws.amazonservices.jp/Orders/2013-09-01";
                    // China
                    //$serviceUrl = "https://mws.amazonservices.com.cn/Orders/2013-09-01";

                    $config = array(
                        'ServiceURL' => $serviceUrl,
                        'ProxyHost' => null,
                        'ProxyPort' => -1,
                        'ProxyUsername' => null,
                        'ProxyPassword' => null,
                        'MaxErrorRetry' => 3,
                    );

                    $service = new MarketplaceWebServiceOrders_Client(
                        $_AWS_ACCESS_KEY_ID,
                        $_AWS_SECRET_ACCESS_KEY,
                        APPLICATION_NAME,
                        APPLICATION_VERSION,
                        $config);
                    echo "total pending order : " . count($user_pending_orders) . '<br/>';
//                    p_rr($user_pending_orders);exit;

                    foreach ($orders as $order_id) {
//                        p_rr($u_pending_order);exit;
                        echo "<br/>Checking Order ID <strong>" . $order_id . '</strong> ';
                        //if($u_pending_order->purchase_date != '0000-00-00 00:00:00') continue;
                        // get order detail

                        $request = new MarketplaceWebServiceOrders_Model_GetOrderRequest();
                        $request->setSellerId($_MERCHANT_ID);
                        $request->setAmazonOrderId($order_id);


                        try {

                            $response = $service->GetOrder($request);
                            $pending_orders = $response->getGetOrderResult()->getOrders();


                            foreach ($pending_orders as $porder) {

                                if ($porder->getOrderStatus() == 'Canceled') {
                                } else if ($porder->getOrderStatus() == 'Pending') {
                                    echo '<strong style="color:#666;">pending</strong>';
                                } else {

                                    echo "ORder Status " . $porder->getOrderStatus() . "<br/>";

//                                    continue;

                                    try {

                                        $prequest = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
                                        $prequest->setSellerId($_MERCHANT_ID);
                                        $prequest->setAmazonOrderId($porder->getAmazonOrderId());

                                        $orderItemsResponse = $service->ListOrderItems($prequest);
                                        $orderItems = $orderItemsResponse->getListOrderItemsResult()->getOrderItems();
                                    } catch (MarketplaceWebServiceOrders_Exception $exp) {

                                        echo("Caught Exception: " . $exp->getMessage() . "<br/>");
                                        echo("Response Status Code: " . $exp->getStatusCode() . "<br/>");
                                        echo("Error Code: " . $exp->getErrorCode() . "<br/>");
                                        echo("Error Type: " . $exp->getErrorType() . "<br/>");
                                        echo "AMAZON_EXCEPTION";
                                        continue;
                                    }
                                    $this_item_type = Sale::get_type_by_order_id($porder->getAmazonOrderId(), $user_id);
                                    Sale::delete_table_data($porder->getAmazonOrderId(), $user_id);
                                    foreach ($orderItems as $item) {

                                        $porderTotal = $porder->getOrderTotal();
                                        $itemTotal2 = $item->getShippingPrice(); //echo $itemTotal2 ? $itemTotal2->getAmount():'0';
                                        $itemTotal3 = $item->getGiftWrapPrice(); //echo $itemTotal3 ? $itemTotal3->getAmount():'0';
                                        $itemTotal4 = $item->getShippingDiscount(); //echo $itemTotal4 ? $itemTotal4->getAmount():'0';

                                        $sale = array();
                                        $sale['item_id'] = $item->getOrderItemId();
                                        $sale['order_id'] = $porder->getAmazonOrderId();
                                        $sale['order_total'] = $porderTotal ? $porderTotal->getAmount() : 0;
                                        $sale['sku'] = $item->getSellerSKU();
                                        $sale['asin'] = $item->getASIN();
                                        $sale['title'] = $item->getTitle();
                                        $sale['qty'] = $item->getQuantityShipped();
                                        $price = $item->getItemPrice();
                                        $sale['price'] = $price ? $price->getAmount() : 0;
                                        $sale['shipping_price'] = $itemTotal2 ? $itemTotal2->getAmount() : 0;
                                        $sale['gift_wrap_price'] = $itemTotal3 ? $itemTotal3->getAmount() : 0;
                                        $sale['shipping_discount'] = $itemTotal4 ? $itemTotal4->getAmount() : 0;
                                        $sale['currency_code'] = $price ? $price->getCurrencyCode() : '';
                                        $sale['purchase_date'] = $porder->getPurchaseDate();
                                        $sale['sale_type'] = $this_item_type;
                                        $sale['buyer_name'] = $porder->getBuyerName() ? $porder->getBuyerName() : '';
                                        $sale['buyer_email'] = $porder->getBuyerEmail() ? $porder->getBuyerEmail() : '';
                                        if (!Sale::already_exists($sale['item_id'], $user_id)) {
                                            $inserted_id = Sale::insert($user_id, $sale);
                                            if ($inserted_id) {
                                                echo '<strong style="color:blue;">Processed . inserted_id =' . $inserted_id . '</strong>';
                                                $user->orders()->delete('order_id', '=', $order_id);
                                                echo ' <strong style="color:red;">Deleted</strong>';
                                            } else {
                                                echo ' <strong style="color:yellow;">Error in insertion</strong>';
                                            }
                                        } else {
                                            echo '<strong style="color:#14EBE3;">Already exists</strong>';
                                            $user->orders()->delete('order_id', '=', $order_id);
                                            echo ' <strong style="color:red;">Deleted</strong>';
                                        }
                                    }
                                }
                            }
                        } catch (Exception $eee) {

                            $errmsg = $eee->getMessage();

                            $pos = strpos(strtolower($errmsg), 'throttled');

                            // Note our use of ===.  Simply == would not work as expected
                            // because the position of 'a' was the 0th (first) character.
                            // echo "<br/>".$eee->getTraceAsString()."<br/>";
                            echo('  <strong style="color:green;">' . $errmsg . '</strong>');
                            if ($pos === false) {
                                echo('  <strong style="color:RED;">Marked as Bug.</strong>');
//                                echo $u_pending_order->id;exit;
                                // $pending_orders = $user->orders()->where('id','=',$u_pending_order->id)->get();
                                // foreach($pending_orders  as $pending_order)
                                // {
                                //     $pending_order->bug = 'yes';
                                //     $pending_order->save();
                                // }
//                                    ->update(array('order_id' => , 'bug' => 'yes'));
//                          p_rr($u_pending_order);
//                                exit;
//                              
                                echo jTraceEx($eee);
                                exit;
                            } else {
                                echo('<strong style="color:orange;">' . $errmsg . "</strong>");
                            }
                            continue;
                        }
                    }
                }
            }
        }
    }

    public static function get_api_old_orders($args = array())
    {
        set_time_limit(0);
        $path = app_path('third_party/MarketplaceWebServiceOrders/Samples/.config.inc.php');
        require_once($path);
        $users = User::GetAllUsersExceptAdmin();
        echo "<h1>Getting Old Sales</h1>";
        foreach ($users as $user) {
            $user_id = $user->id;
            //if($user_id!=63) continue;
            $c_date = $user->created_at;
            $data = TeHelper::get_sale_dates($user, array(), false);
//            p_rr($data);
//            exit;
            $new_latest_date = strtotime($data['new_latest']);
            $new_start_date = strtotime($data['new_first']);
            $latest_date = strtotime($data['old_latest']);
            $old_date = date('Y-m-d H:i:s', $latest_date);
            $new_date = date('Y-m-d H:i:s', $new_start_date);
            $above_from_new_row = $data['above_from_new_row']['purchase_date'];
            echo "<br> User ID:<strong> $user_id </strong><br/>";
            $today_time = time();


            if ($today_time - $new_latest_date > 3 * 24 * 60 * 60) {
                echo "<strong style='color:#00F'>NEW SALES ARE STILL BEING FETCHED</strong>. <br/>";
                continue;
            }

            if ($latest_date >= $new_start_date || $above_from_new_row == $old_date || $user->old_data_status == 'complete') {
                echo "<strong style='color:#500'>ALL OLD SALE ARE FETCHED</strong>. <br/>";
                continue;
            }

            $settings = $user->AmazonSettings()->get()->first();

//            p_rr($settings);
//            exit;


            if ($settings) {
                if (isset($settings->merchant_id) && trim($settings->merchant_id) != '' &&
                    isset($settings->marketplace_id) && trim($settings->marketplace_id) != '' &&
                    isset($settings->access_key) && trim($settings->access_key) != '' &&
                    isset($settings->secret_key) && trim($settings->secret_key) != ''
                ) {

                    $_MERCHANT_ID = trim($settings->merchant_id);
                    $_MARKETPLACE_ID = trim($settings->marketplace_id);
                    $_AWS_ACCESS_KEY_ID = trim($settings->access_key);
                    $_AWS_SECRET_ACCESS_KEY = trim($settings->secret_key);


                    $serviceUrl = $settings->region . "Orders/2013-09-01";

                    // Europe
                    //$serviceUrl = "https://mws-eu.amazonservices.com/Orders/2013-09-01";
                    // Japan
                    //$serviceUrl = "https://mws.amazonservices.jp/Orders/2013-09-01";
                    // China
                    //$serviceUrl = "https://mws.amazonservices.com.cn/Orders/2013-09-01";

                    $config = array(
                        'ServiceURL' => $serviceUrl,
                        'ProxyHost' => null,
                        'ProxyPort' => -1,
                        'ProxyUsername' => null,
                        'ProxyPassword' => null,
                        'MaxErrorRetry' => 3,
                    );


                    $service = new MarketplaceWebServiceOrders_Client(
                        $_AWS_ACCESS_KEY_ID,
                        $_AWS_SECRET_ACCESS_KEY,
                        APPLICATION_NAME,
                        APPLICATION_VERSION,
                        $config);

//                    echo "<pre>";
//                    print_r($service);
//                    echo "<pre>";

                    $request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
                    $request->setSellerId($_MERCHANT_ID);
                    $request->setMaxResultsPerPage(60);
                    // object or array of parameters
                    $request->setMarketplaceId($_MARKETPLACE_ID);
                    $last_date = $latest_date;
                    $time_offset = 1;
                    $seconds = date('Y-m-d', $last_date - $time_offset) . 'T' . date('H:i:s', $last_date - $time_offset) . 'Z';
                    echo "Latest Old Date: " . $seconds . '<br/>';
                    $request->setCreatedAfter($seconds);
                    self::invokeListOrders($service, $request, $user_id, $_MERCHANT_ID, 'old');
                }
            } else {
                echo "<strong style='color:red'>Missing Settings.</strong>";
            }
        }
    }

    public static function send_email_alerts()
    {

        $currentTimeHourInt = (int)date('H');
        // $currentTimeHourInt = 8;
        if (!($currentTimeHourInt == 8 || $currentTimeHourInt == 9)) return;

        set_time_limit(0);
        $users = User::GetAllUsersExceptAdmin();
        $interval = array();
        $interval['daily'] = 1 * 24 * 60 * 60;
        $interval['weekly'] = 7 * 24 * 60 * 60;
        $interval['monthly'] = 30 * 24 * 60 * 60;
        $diff = array();
        $diff['daily'] = '-1 day';
        $diff['weekly'] = '-7 days';
        $diff['monthly'] = '-1 month';

        $count = 0;
        $limit = 10;
        //p_rr( $users );
        // echo count($users);
        // exit;
        foreach ($users as $user) {


            // if($user->email!='rashid.ali000@gmail.com') continue;
            // $count++;
            // if($limit==$count) break;

            if ($user->status == 'inactive') {
                echo $user->first_name . " continue";
                continue;
            }
            echo "<br/> <hr/>User <strong>" . $user->email . '</strong><br>';
            $_setting = $user->EmailSettings()->get()->first();
            if (empty($_setting)) {
                echo "<strong style='color:red'>Email Settings not found.</strong><br/>";
                continue;
            }
            $_email = $_setting->email;
            $packages = explode(',', $_setting->package);
            $last_email_time = $user->email_time == '0000-00-00 00:00:00' ? '2015-01-01 00:00:00' : $user->email_time;
            $last_weekly_email_time = $user->weekly_email_time == '0000-00-00 00:00:00' ? '2015-01-01 00:00:00' : $user->weekly_email_time;
            $last_monthly_email_time = $user->monthly_email_time == '0000-00-00 00:00:00' ? '2015-01-01 00:00:00' : $user->monthly_email_time;
            $last_email_time = strtotime($last_email_time);
            $last_weekly_email_time = strtotime($last_weekly_email_time);
            $last_monthly_email_time = strtotime($last_monthly_email_time);
            echo "Last time: " . date('d-M-Y H:i A', $last_email_time) . '<br/>';
            echo "Last weekly time: " . date('d-M-Y H:i A', $last_email_time) . '<br/>';
            echo "Last monthly time: " . date('d-M-Y H:i A', $last_email_time) . '<br/>';
            echo "Current time: " . date('d-M-Y H:i A') . '<br/>';
            //echo $interval[$package]." <= ".(time()-$last_email_time).'<br/>';

            $last_times['daily'] = $last_email_time;
            $last_times['weekly'] = $last_weekly_email_time;
            $last_times['monthly'] = $last_monthly_email_time;
            // p_rr($packages);
            foreach ($packages as $package) {


                if (!empty($package) && $interval[$package] <= time() - $last_times[$package]) {
//                    if (1) {
                    echo "<strong>sending $package email....</strong> ";
                    $till_today = date('Y-m-d', strtotime('-1 day', time())) . ' 23:59:59';
                    $from_date = date('Y-m-d', (strtotime($diff[$package], strtotime($till_today))));
                    $from_date = "$from_date 00:00:00";
                    $from_date = date('Y-m-d', strtotime('+1 day', strtotime($from_date))) . ' 00:00:00';

                    $data = array();
                    $data['userdata'] = $user;
                    $data['from_date'] = $from_date;
                    $data['to_date'] = $till_today;

                    // p_rr($data);
                    $data = Sale::helper_get_sales($data, 1);
                    // echo "here.<br/>";

                    $newline = "<br/>";
                    $email = array();
                    $email['to'] = trim($_email) != '' ? trim($_email) : $user->email;
                    $email['subject'] = ucwords($package) . ' Alert | EcommElite Sales';

                    $data2 = array();
                    $data2['userdata'] = $user;

                    if ($package == 'daily') {
                        $data2['from_date'] = date('Y-m-d 00:00:00', strtotime("-1 day", strtotime($from_date)));
                        $data2['to_date'] = date('Y-m-d', strtotime("-1 day", strtotime($till_today))) . ' 23:59:59';
                    } else if ($package == 'weekly') {
                        $data2['from_date'] = date('Y-m-d 00:00:00', strtotime("-1 week", strtotime($from_date)));
                        $data2['to_date'] = date('Y-m-d', strtotime("-1 week", strtotime($till_today))) . ' 23:59:59';
                    } else if ($package == 'monthly') {
                        $data2['from_date'] = date('Y-m-d 00:00:00', strtotime("-1 month", strtotime($from_date)));
                        $data2['to_date'] = date('Y-m-d', strtotime("-1 month", strtotime($till_today))) . ' 23:59:59';
                    }
                    //p_rr( $data2 );
                    $data['package'] = ucwords($package);
                    $data['previous'] = Sale::helper_get_sales($data2, 1);

                    Mail::send('layouts.email.alert', $data, function ($m) use ($user, $package) {
                        $subject = ucwords($package) . ' Alert - EcommElite Sales';

                        $package_type = $package == 'daily' ? '' : $package . "_";
                        if ($package == 'daily')
                            $user->email_time = date('Y-m-d H:i:s');
                        if ($package == 'weekly')
                            $user->weekly_email_time = date('Y-m-d H:i:s');
                        if ($package == 'monthly')
                            $user->monthly_email_time = date('Y-m-d H:i:s');
                        $user->save();
                        $m->to($user->email, $user->first_name)->subject($subject);
                        echo "<strong style='color:green'>Email Sent</strong><br/>";
                    });
                }
//                }
            }
        }
    }

    public static function get_api_new_orders()
    {
        set_time_limit(0);
        $path = app_path('third_party/MarketplaceWebServiceOrders/Samples/') . '.config.inc.php';
        require_once($path);

        $users = User::GetAllUsersExceptAdmin();

        echo "<h1>Getting New Sales</h1>";
        foreach ($users as $user) {

            $user_id = $user->id;

            echo "<br> User ID:<strong> $user_id </strong><br/>";
            $settings = $user->AmazonSettings()->get()->first();
            if ($settings) {
                if (isset($settings['merchant_id']) && trim($settings['merchant_id']) != '' &&
                    isset($settings['marketplace_id']) && trim($settings['marketplace_id']) != '' &&
                    isset($settings['access_key']) && trim($settings['access_key']) != '' &&
                    isset($settings['secret_key']) && trim($settings['secret_key']) != ''
                ) {


                    $_MERCHANT_ID = trim($settings['merchant_id']);
                    $_MARKETPLACE_ID = trim($settings['marketplace_id']);
                    $_AWS_ACCESS_KEY_ID = trim($settings['access_key']);
                    $_AWS_SECRET_ACCESS_KEY = trim($settings['secret_key']);

//                        echo "here";
//                    p_rr($settings['merchant_id']);


                    $serviceUrl = $settings->region . "Orders/2013-09-01";

                    // Europe
                    //$serviceUrl = "https://mws-eu.amazonservices.com/Orders/2013-09-01";
                    // Japan
                    //$serviceUrl = "https://mws.amazonservices.jp/Orders/2013-09-01";
                    // China
                    //$serviceUrl = "https://mws.amazonservices.com.cn/Orders/2013-09-01";

//                    echo $serviceUrl;
//                    exit;

                    $config = array(
                        'ServiceURL' => $serviceUrl,
                        'ProxyHost' => null,
                        'ProxyPort' => -1,
                        'ProxyUsername' => null,
                        'ProxyPassword' => null,
                        'MaxErrorRetry' => 3,
                    );

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
                    $request->setMarketplaceId($_MARKETPLACE_ID);
                    $dates = TeHelper::get_sale_dates($user, array(), false);
                    $last_date = $dates['new_latest'];
                    echo "LATEST NEW DATE: " . $last_date . "<br/>";
                    //                  echo $last_date;exit;
                    $time_offset = 1;
                    $seconds = date('Y-m-d', strtotime($last_date) - $time_offset) . 'T' . date('H:i:s', strtotime($last_date) - $time_offset) . 'Z';
                    //echo $seconds;exit;
                    $request->setCreatedAfter($seconds);
                    self::invokeListOrders($service, $request, $user_id, $_MERCHANT_ID, 'new');
                }
            } else {
                Debugbar::info($user_id . '-  Missing Settings ');
                Debugbar::error($user_id . '-  Missing Settings ');
                Debugbar::warning($user_id . '-  Missing Settings ');
                Debugbar::addMessage($user_id . '-  Missing Settings ', 'mylabel');
                echo "<strong style='color:red'>Missing Settings.</strong>";
            }
        }
    }

    public static function check_pending_orders($args = array())
    {


        set_time_limit(0);
        echo "Function called for Pending Orders Check <br/>";


        $path = app_path('third_party/MarketplaceWebServiceOrders/Samples/') . '.config.inc.php';
        require_once($path);

//        $serviceUrl = "https://mws.amazonservices.com/Orders/2013-09-01";
//
//        $config = array (
//            'ServiceURL' => $serviceUrl,
//            'ProxyHost' => null,
//            'ProxyPort' => -1,
//            'ProxyUsername' => null,
//            'ProxyPassword' => null,
//            'MaxErrorRetry' => 3,
//        );

        $users = User::GetAllUsersExceptAdmin();
//        p_rr($users);exit;

//        ob_start();
        foreach ($users as $user) {
//            ob_start();
            echo "<br/>checking pending orders for user: " . $user->id . ' - ' . $user->email . "<br>";
//            echo ob_get_clean();
//            flush();
//            ob_flush();
            $user_id = $user->id;
            if ($user_id != 9) continue;

            $args = array();
            $args['user_id'] = $user_id;
            // $args['bug'] = 'yes'; // getting orders without bug
            $args['order'] = 'ASC';
            $args['order_by'] = 'purchase_date';
            $args['offset'] = 0;
            $args['status'] = 'Pending';
            $args['limit'] = 50;
            $user_pending_orders = Order::get_all($args);
            $settings = $user->AmazonSettings()->get()->first();
//            p_rr($settings);exit;
            if ($settings) {
                if (isset($settings['merchant_id']) && trim($settings['merchant_id']) != '' &&
                    isset($settings['marketplace_id']) && trim($settings['marketplace_id']) != '' &&
                    isset($settings['access_key']) && trim($settings['access_key']) != '' &&
                    isset($settings['secret_key']) && trim($settings['secret_key']) != ''
                ) {

                    $_MERCHANT_ID = trim($settings['merchant_id']);
                    $_MARKETPLACE_ID = trim($settings['marketplace_id']);
                    $_AWS_ACCESS_KEY_ID = trim($settings['access_key']);
                    $_AWS_SECRET_ACCESS_KEY = trim($settings['secret_key']);

                    $serviceUrl = $settings->region . "Orders/2013-09-01";

                    // Europe
                    //$serviceUrl = "https://mws-eu.amazonservices.com/Orders/2013-09-01";
                    // Japan
                    //$serviceUrl = "https://mws.amazonservices.jp/Orders/2013-09-01";
                    // China
                    //$serviceUrl = "https://mws.amazonservices.com.cn/Orders/2013-09-01";

                    $config = array(
                        'ServiceURL' => $serviceUrl,
                        'ProxyHost' => null,
                        'ProxyPort' => -1,
                        'ProxyUsername' => null,
                        'ProxyPassword' => null,
                        'MaxErrorRetry' => 3,
                    );

                    $service = new MarketplaceWebServiceOrders_Client(
                        $_AWS_ACCESS_KEY_ID,
                        $_AWS_SECRET_ACCESS_KEY,
                        APPLICATION_NAME,
                        APPLICATION_VERSION,
                        $config);
                    echo "total pending order : " . count($user_pending_orders) . '<br/>';
//                    p_rr($user_pending_orders);exit;
                    $pending_orders_ids_arr = [];
                    foreach ($user_pending_orders as $u_pending_order) {
//                        p_rr($u_pending_order);exit;
//                        echo "<br/>Checking Order ID <strong>" . $u_pending_order->order_id . '</strong> ';
                        $pending_orders_ids_arr[] = $u_pending_order->order_id;
                    }

//                    foreach ($user_pending_orders as $u_pending_order) {
//                        p_rr($u_pending_order);exit;
//                        echo "<br/>Checking Order ID <strong>" . $u_pending_order->order_id . '</strong> ';
                    //if($u_pending_order->purchase_date != '0000-00-00 00:00:00') continue;
                    // get order detail
                    $request = new MarketplaceWebServiceOrders_Model_GetOrderRequest();
                    $request->setSellerId($_MERCHANT_ID);
//                        $request->setAmazonOrderId($u_pending_order->order_id);
                    $request->setAmazonOrderId($pending_orders_ids_arr);


//                    try {
                    $response = $service->GetOrder($request);
                    $pending_orders = $response->getGetOrderResult()->getOrders();
//                        p_rr($pending_orders->toArray());
                    foreach ($pending_orders as $porder) {

//                            p_rr($porder);continue;
                        Order::update_order($porder->getAmazonOrderId(), $user->id, ['status' => $porder->getOrderStatus()]);
//                            echo "ORder Status " . $porder->getOrderStatus() . "<br/>";
//                            continue;
                        if ($porder->getOrderStatus() == 'Canceled') {
                            echo '<strong style="color:red;">deleted</strong>';
                            Sale::delete_sales_by_order_id($porder->getAmazonOrderId(), $user_id);

                        } else if ($porder->getOrderStatus() == 'Pending') {
                            //do nothing
                            echo '<strong style="color:#666;">pending</strong>';
                        } else {

                            echo "ORder Status " . $porder->getOrderStatus() . "<br/>";

//                                    continue;


                            try {
                                $prequest = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
                                $prequest->setSellerId($_MERCHANT_ID);
                                $prequest->setAmazonOrderId($porder->getAmazonOrderId());
                                $orderItemsResponse = $service->ListOrderItems($prequest);
                                $orderItems = $orderItemsResponse->getListOrderItemsResult()->getOrderItems();
                            } catch (MarketplaceWebServiceOrders_Exception $exp) {

                                echo("Caught Exception: " . $exp->getMessage() . "<br/>");
                                echo("Response Status Code: " . $exp->getStatusCode() . "<br/>");
                                echo("Error Code: " . $exp->getErrorCode() . "<br/>");
                                echo("Error Type: " . $exp->getErrorType() . "<br/>");
                                echo "AMAZON_EXCEPTION";
                                continue;
                            } catch (Exception $exp) {

                                echo("Caught Exception: " . $exp->getMessage() . "<br/>");
                                // echo("Response Status Code: " . $exp->getStatusCode() . "<br/>");
                                // echo("Error Code: " . $exp->getErrorCode() . "<br/>");
                                // echo("Error Type: " . $exp->getErrorType() . "<br/>");
                                // echo "AMAZON_EXCEPTION";
                                continue;

                            }
                            $this_item_type = Sale::get_type_by_order_id($porder->getAmazonOrderId(), $user_id);
                            foreach ($orderItems as $item) {
                                $porderTotal = $porder->getOrderTotal();
                                $itemTotal2 = $item->getShippingPrice(); //echo $itemTotal2 ? $itemTotal2->getAmount():'0';
                                $itemTotal3 = $item->getGiftWrapPrice(); //echo $itemTotal3 ? $itemTotal3->getAmount():'0';
                                $itemTotal4 = $item->getShippingDiscount(); //echo $itemTotal4 ? $itemTotal4->getAmount():'0';
                                $sale = array();
                                $sale['item_id'] = $item->getOrderItemId();
                                $sale['order_id'] = $porder->getAmazonOrderId();
                                $sale['order_total'] = $porderTotal ? $porderTotal->getAmount() : 0;
                                $sale['sku'] = $item->getSellerSKU();
                                $sale['asin'] = $item->getASIN();
                                $sale['title'] = $item->getTitle();
                                $sale['qty'] = $item->getQuantityShipped();
                                $price = $item->getItemPrice();
                                $sale['price'] = $price ? $price->getAmount() : 0;
                                $sale['shipping_price'] = $itemTotal2 ? $itemTotal2->getAmount() : 0;
                                $sale['gift_wrap_price'] = $itemTotal3 ? $itemTotal3->getAmount() : 0;
                                $sale['shipping_discount'] = $itemTotal4 ? $itemTotal4->getAmount() : 0;
                                $sale['currency_code'] = $price ? $price->getCurrencyCode() : '';
                                $sale['purchase_date'] = $porder->getPurchaseDate();
                                $sale['sale_type'] = $this_item_type;
                                $sale['buyer_name'] = $porder->getBuyerName() ? $porder->getBuyerName() : '';
                                $sale['buyer_email'] = $porder->getBuyerEmail() ? $porder->getBuyerEmail() : '';
                                if (!Sale::already_exists($sale['item_id'], $user_id)) {
                                    $inserted = Sale::insert($user_id, $sale);
                                    if ($inserted) {
                                        echo '<strong style="color:blue;">Processed</strong>';
//                                            $user->orders()->delete('order_id', '=', $u_pending_order->id);
//                                            echo ' <strong style="color:red;">Deleted</strong>';
                                    }
                                } else {
                                    echo '<strong style="color:#14EBE3;">Already exists</strong>';
                                }
                            }
                        }
                    }
//                    } catch (Exception $eee) {
//                        $errmsg = $eee->getMessage();
//                        $pos = strpos(strtolower($errmsg), 'throttled');
//                        // Note our use of ===.  Simply == would not work as expected
//                        // because the position of 'a' was the 0th (first) character.
//                        if ($pos === false) {
//                            echo('  <strong style="color:RED;">Marked as Bug.</strong>');
//                            Order::update_order($u_pending_order->order_id, $user->id, ['bug' => 'yes']);
//                        } else {
//                            echo('<strong style="color:orange;">' . $errmsg . "</strong>");
//                        }
//                        continue;
//                    }
                }
//                }
            }
        }
//        echo ob_get_clean();
//        flush();
//        ob_flush();
        //exit;
    }

    public static function create_customer_order_table()
    {
        $users = User::GetAllUsersExceptAdmin();
        foreach ($users as $user) {
            event(new UserWasCreated($user));
        }
        echo "Table Created Succssfully.";

    }

    public static function populate_order_table()
    {
        $users = User::GetAllUsersExceptAdmin();
        foreach ($users as $user) {
            $query = "SELECT * from orders WHERE user_id={$user->id}";
            $data = DB::select($query);
            foreach ($data as $row) {
                $order = array();
                $order['order_id'] = $row->order_id;
                $user_id = $row->user_id;
                $order['purchase_date'] = $row->purchase_date;
                $order['created_on'] = $row->created_on;
                $order['bug'] = $row->bug;
                $order['status'] = $row->status;
                $order['created_at'] = $row->created_at;
                $order['updated_at'] = $row->updated_at;
                $order_already = Order::already_exists($row->order_id, $user->id);
                if (!$order_already) {
                    Order::insert_order($user->id, $order);
                } else {
                    Order::update_order($row->order_id, $user->id, $order);
                }
            }
        }
    }

    public static function populate_order_Table_from_sale_table()
    {
        set_time_limit(0);
        $users = User::GetAllUsersExceptAdmin();
        $limit = 10000;

        foreach ($users as $user) {
//            if ($user->id == 3) continue;

            $user_sale_count = 0;
            $offset = Sale::get_sale_offset($user->id);

            /*
             * @TODO get_sale_offset returns an empty array when it can't find anything.
             * that then throws an error in the query string because of an attempted
             * array->string conversion. This seems to be holding up any other
             * requests, so we will be skipping any user that doesn't
             * have a sale_offset for the time being.
             */

            if (is_array($offset)) {
                continue;
            }

//            if($offset>0)
            $query = "SELECT * from sales_" . $user->id . " limit $offset , $limit";
            $data = DB::select($query);
            echo "User ID: " . $user->id . "<br/>";
            echo "data selected. <br/>";
            try {
                foreach ($data as $sale) {
                    $user_sale_count++;
                    $order = array();
                    $order['order_id'] = $sale->order_id;
                    $order['OrderTotal'] = $sale->order_total;
                    $order['CurrencyCode'] = $sale->currency_code;
                    $order['status'] = $sale->status;
                    $order['purchase_date'] = $sale->purchase_date;
                    $data = Order::get_order($sale->order_id, $user->id);
                    if (isset($data) && isset($data->order_id)) {
                        $order_id = $data->order_id;
                        Order::update_order($sale->order_id, $user->id, $order);
                        echo "<br/>Update Order==>$order_id <br/>";
                    } else {
                        $order_id = Order::insert_order($user->id, $order);
                        echo "<br/>Insert Order==>$order_id <br/>";
                    }
                    $customer = array();
                    $customer['name'] = $sale->buyer_name;
                    $customer['email'] = $sale->buyer_email;
                    $data = Customer::get_customer_by_email($customer['email'], $user->id);
                    if (isset($data) && isset($data->id)) {
                        $customer_id = $data->id;
                        Customer::update_customer($customer_id, $user->id, $customer);
                        echo "<br/>Update Customer==>$customer_id <br/>";
                    } else {
                        $customer_id = Customer::insert_customer($user->id, $customer);
                        echo "<br/>Insert Customer==>$customer_id <br/>";
                    }
                    self::save_order_customer_rel($order_id, $customer_id, $user->id);
                }
                Sale::update_sale_offset($user->id, ['offset' => $offset + $user_sale_count]);
            } catch (Exception $ex) {
                echo("<strong style='color:red ;'> Exception</strong>: " . $ex->getMessage() . "\n");
            }
        }
    }

    public static function is_credentials_valid($credentials)
    {

    }

    public static function altar_sales_table()
    {
        $users = User::GetAllUsersExceptAdmin();
        foreach ($users as $user) {
            $query = "ALTER TABLE `sales_{
                $user->id}` CHANGE `order_total` `order_total` DECIMAL(10,2) NULL DEFAULT NULL, CHANGE `asin` `asin` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `shipping_price` `shipping_price` DECIMAL(10,2) NULL, CHANGE `gift_wrap_price` `gift_wrap_price` DECIMAL(10,2) NULL, CHANGE `shipping_discount` `shipping_discount` DECIMAL(10,2) NULL, CHANGE `status` `status` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `sale_type` `sale_type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL";
            DB::raw($query);
        }
    }

    public static function get_missing_sales()
    {
        set_time_limit(0);
        $path = app_path('third_party/MarketplaceWebServiceOrders/Samples/') . '.config.inc.php';
        require_once($path);

        $users = User::GetAllUsersExceptAdmin();

        // $user_id = 3;
        // $user    = User::findOrFail($user_id);
        // $users   = array($user);    
        echo "<h1>Getting New Sales</h1>";
        foreach ($users as $user) {

            $user_id = $user->id;

            echo "<br> User ID:<strong> $user_id </strong><br/>";
            $settings = $user->AmazonSettings()->get()->first();
            if ($settings) {
                if (isset($settings['merchant_id']) && trim($settings['merchant_id']) != '' &&
                    isset($settings['marketplace_id']) && trim($settings['marketplace_id']) != '' &&
                    isset($settings['access_key']) && trim($settings['access_key']) != '' &&
                    isset($settings['secret_key']) && trim($settings['secret_key']) != ''
                ) {


                    $_MERCHANT_ID = trim($settings['merchant_id']);
                    $_MARKETPLACE_ID = trim($settings['marketplace_id']);
                    $_AWS_ACCESS_KEY_ID = trim($settings['access_key']);
                    $_AWS_SECRET_ACCESS_KEY = trim($settings['secret_key']);

//                        echo "here";
//                    p_rr($settings['merchant_id']);


                    $serviceUrl = $settings->region . "Orders/2013-09-01";

                    // Europe
                    //$serviceUrl = "https://mws-eu.amazonservices.com/Orders/2013-09-01";
                    // Japan
                    //$serviceUrl = "https://mws.amazonservices.jp/Orders/2013-09-01";
                    // China
                    //$serviceUrl = "https://mws.amazonservices.com.cn/Orders/2013-09-01";

//                    echo $serviceUrl;
//                    exit;

                    $config = array(
                        'ServiceURL' => $serviceUrl,
                        'ProxyHost' => null,
                        'ProxyPort' => -1,
                        'ProxyUsername' => null,
                        'ProxyPassword' => null,
                        'MaxErrorRetry' => 3,
                    );

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
                    $request->setMarketplaceId($_MARKETPLACE_ID);
                    $last_date = Sale::get_missing_date($user_id);
                    echo "LATEST NEW DATE: " . $last_date . "<br/>";
                    //                  echo $last_date;exit;
                    $time_offset = 1;
                    $seconds = date('Y-m-d', strtotime($last_date) - $time_offset) . 'T' . date('H:i:s', strtotime($last_date) - $time_offset) . 'Z';
                    //echo $seconds;exit;
                    $request->setCreatedAfter($seconds);
                    self::invokeListOrders($service, $request, $user_id, $_MERCHANT_ID, 'new', true);
                }
            } else {
                Debugbar::info($user_id . '-  Missing Settings ');
                Debugbar::error($user_id . '-  Missing Settings ');
                Debugbar::warning($user_id . '-  Missing Settings ');
                Debugbar::addMessage($user_id . '-  Missing Settings ', 'mylabel');
                echo "<strong style='color:red'>Missing Settings.</strong>";
            }
        }
    }

    public static function save_order($user_id, $order)
    {
        $order_id = $order->getAmazonOrderId();
        $data = array();
        $data['order_id'] = $order_id;
        $data['purchase_date'] = $order->getPurchaseDate();
        $data['LastUpdateDate'] = $order->getLastUpdateDate();
        $data['status'] = $order->getOrderStatus();
        $data['SalesChannel'] = $order->getSalesChannel();
        $data['ShipServiceLevel'] = $order->getShipServiceLevel();
        $data['OrderChannel'] = $order->getOrderChannel();
        $data['TFMShipmentStatus'] = $order->getTFMShipmentStatus();
        $data['CbaDisplayableShippingLabel'] = $order->getCbaDisplayableShippingLabel();
        $data['OrderType'] = $order->getOrderType();
        $data['EarliestShipDate'] = $order->getEarliestShipDate();
        $data['LatestDeliveryDate'] = $order->getLatestDeliveryDate();
        $model_money_obj = $order->getOrderTotal();
        $data['OrderTotal'] = ($model_money_obj) ? $model_money_obj->getAmount() : 0;
        $data['CurrencyCode'] = ($model_money_obj) ? $model_money_obj->getCurrencyCode() : 'USD';
        $order_already_exists = Order::already_exists($order_id, $user_id);
        if (!$order_already_exists) {
            echo "insert new order.<br/>";
            Order::insert_order($user_id, $data);
        } else {
            echo "update  order.<br/>";
            Order::update_order($order_id, $user_id, $data);
        }
        return $order_id;
    }

    public static function save_customer($user_id, $order)
    {
        $customer = array();
        $customer['name'] = $order->getBuyerName() ? $order->getBuyerName() : '';
        $customer['email'] = $order->getBuyerEmail() ? $order->getBuyerEmail() : '';
        $shippAddress_obj = $order->getShippingAddress();
        if ($shippAddress_obj) {
            $customer['AddressLine1'] = $shippAddress_obj->getAddressLine1();
            $customer['AddressLine2'] = $shippAddress_obj->getAddressLine2();
            $customer['AddressLine3'] = $shippAddress_obj->getAddressLine3();
            $customer['City'] = $shippAddress_obj->getCity();
            $customer['County'] = $shippAddress_obj->getCounty();
            $customer['District'] = $shippAddress_obj->getDistrict();
            $customer['StateOrRegion'] = $shippAddress_obj->getStateOrRegion();
            $customer['PostalCode'] = $shippAddress_obj->getPostalCode();
            $customer['CountryCode'] = $shippAddress_obj->getCountryCode();
            $customer['Phone'] = $shippAddress_obj->getPhone();
        }
        $data = Customer::get_customer_by_email($customer['email'], $user_id);
        if (isset($data) && isset($data->id)) {
            $customer_id = $data->id;
            Customer::update_customer($customer_id, $user_id, $customer);
        } else {
            $customer_id = Customer::insert_customer($user_id, $customer);
        }
        return $customer_id;
    }

    /**
     * @param $order_id
     * @param $customer_id
     * @param $user_id
     */

    public static function save_order_customer_rel($order_id, $customer_id, $user_id)
    {
        if (!(Customer::customer_order_relation_already_exists($order_id, $customer_id, $user_id))) {
            Customer::insert_customer_order_relation($order_id, $customer_id, $user_id);
            echo "relation inserted<br/>";
        }
    }

    public static function populate_missing_sale_table()
    {
        $users = User::GetAllUsersExceptAdmin();
        foreach ($users as $user) {
            Sale::insert_missing_date($user->id);
        }
    }

    public static function populate_sale_offset_table()
    {
        $users = User::GetAllUsersExceptAdmin();
        foreach ($users as $user) {
            Sale::insert_sale_offset($user->id);
        }
    }

    public static function delete_duplicate_relations()
    {
        $users = User::GetAllUsersExceptAdmin();
//        p_rr($users );exit;
        foreach ($users as $user) {
            echo "USer ID {$user->id}<br/>";
            $data = Sale::get_duplicate_ids($user->id);
            foreach ($data as $row) {
                echo $row->ids . "<br/>";
                $ids = explode(',', $row->ids);
                for ($i = 1; $i < count($ids); $i++) {
                    echo $ids[$i] . "<br/>";
                    Sale::delete_customer_order_relation($ids[$i], $user->id);
                }
            }
        }
        echo "processed all users";
    }

    public static function test_customer_order_relation()
    {
        self::save_order_customer_rel('107-1425897-2305029-nothing', 35540, 672);
    }

}

if (!function_exists('p_rr')) {
    function p_rr($obj)
    {
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }
}

function jTraceEx($e, $seen = null)
{
    $starter = $seen ? 'Caused by: ' : '';
    $result = array();
    if (!$seen) $seen = array();
    $trace = $e->getTrace();
    $prev = $e->getPrevious();
    $result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
    $file = $e->getFile();
    $line = $e->getLine();
    while (true) {
        $current = "$file:$line";
        if (is_array($seen) && in_array($current, $seen)) {
            $result[] = sprintf(' ... %d more', count($trace) + 1);
            break;
        }
        $result[] = sprintf(' at %s%s%s(%s%s%s)',
            count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
            count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
            count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
            $line === null ? $file : basename($file),
            $line === null ? '' : ':',
            $line === null ? '' : $line);
        if (is_array($seen))
            $seen[] = "$file:$line";
        if (!count($trace))
            break;
        $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
        $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
        array_shift($trace);
    }
    $result = join("\n", $result);
    if ($prev)
        $result .= "\n" . jTraceEx($prev, $seen);

    return $result;
}
