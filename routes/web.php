<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('test_listners',function(){
//    return \AmazonHelper::run_threads();
    \AmazonHelper::test_listners();
});
Route::get('run_threads',function(){
//    return \AmazonHelper::run_threads();
    phpinfo();
});

Route::get('delete_duplicate_relations',function(){
    return \AmazonHelper::delete_duplicate_relations();
});

Route::get('test_customer_order_relation',function(){
    return \AmazonHelper::test_customer_order_relation();
});

Route::get('get_missing_sales',function(){
    return \AmazonHelper::get_missing_sales();
});

Route::get('populate_missing_sale_table',function(){
    return \AmazonHelper::populate_missing_sale_table();
});
Route::get('compare-order-id',function(){
    return \AmazonHelper::compare_order_id();
});

Route::get('run-cron',function(){
    \AmazonHelper::send_email_alerts();
});
Route::get('send_email_alerts',function(){
    \AmazonHelper::send_email_alerts();
});
Route::get('get_api_old_orders',function(){
    \AmazonHelper::get_api_old_orders();
});
Route::get('check_pending_orders',function(){
    \AmazonHelper::check_pending_orders();
});
Route::get('get_api_new_orders',function(){
    \AmazonHelper::get_api_new_orders();
});
Route::get('get_api_orders',function(){
    \AmazonHelper::get_api_new_orders();
    \AmazonHelper::get_api_old_orders();
});
Route::get('get_api_data',function(){
//     \AmazonHelper::get_missing_sales();
//	\AmazonHelper::check_pending_orders();
    \AmazonHelper::get_api_new_orders();
    \AmazonHelper::get_api_old_orders();
    // \AmazonHelper::send_email_alerts();
});


Route::get('create_user_tables',function(){
    \AmazonHelper::create_customer_order_table();
});

Route::get('populate_order_table',function(){
    set_time_limit(0);
    \AmazonHelper::populate_order_table();
    echo "<br/> Data from order table imported successfully .<br/>";
});

Route::get('populate_order_table_from_sales',function(){
    set_time_limit(0);
    \AmazonHelper::populate_order_Table_from_sale_table();
    echo "<br/>Data from sales table is imported successfully.<br/>";
});


Route::get('populate_sale_offset_table',function(){
    set_time_limit(0);
    \AmazonHelper::populate_sale_offset_table();
    echo "<br/>Sale offset table populated successfully.<br/>";
});

Route::get('/','WelcomeController@index');
Route::put('admin/update_status/{users}','\Askedio\Laravelcp\User\Http\Controllers\HomeController@update_status');
Route::post('admin/ajax_upload_csv_users','AdminController@ajax_upload_csv_users');
Route::post('admin/ajax_edit_user','AdminController@ajax_edit_user');
Route::get('settings/amazon','SettingController@amazon');
Route::get('settings/email','SettingController@email');
Route::get('settings/changepassword','SettingController@changepassword');
Route::post('settings/ajax_change_password','SettingController@ajax_change_password');
Route::post('settings/ajax_save_amazon_settings','SettingController@ajax_save_amazon_settings');
Route::post('settings/ajax_save_email_settings','SettingController@ajax_save_email_settings');
Route::get('analysis','AnalysisController@index');
Route::post('analysis/alternate_get_sales_method','AnalysisController@alternate_get_sales_method');
Route::post('analysis/chart_of_week','AnalysisController@chart_of_week');
Route::post('analysis/daily_graph','AnalysisController@daily_graph');
Route::resource('events','EventController');
Route::post('events/ajax_product_name_search','EventController@ajax_product_name_search');
Route::post('events/ajax_save_event','EventController@ajax_save_event');
Route::get('events/delete/{events}','EventController@delete');
Route::post('events/ajax_get_events','EventController@ajax_get_events');
Route::post('activity/ajax_get_activity','ActivityController@ajax_get_activity');
Route::post('profit/ajax_get_profit_setting','ProfitController@ajax_get_profit_setting');
Route::post('profit/ajax_save_profit_summery','ProfitController@ajax_save_profit_summery');
Route::post('pending_orders/ajax_get_orders','OrdersController@ajax_get_orders');
Route::get('analysis','AnalysisController@index');
Route::get('activity','ActivityController@index');

Route::get('orders/pending','OrdersController@index');
Route::get('profit','ProfitController@index');
Route::get('upcTool', 'UpcToolController@index');
Route::post('upcToolPost', 'UpcToolController@post');
Route::get('calculator','CalculatorController@index');
Route::post('calculator/calculatedValues','CalculatorController@index');
Route::post('calculator','CalculatorController@index');
Route::get('keywordTracker', 'KeywordToolController@index');
Route::post('keywordTracker/addApiKeys', 'KeywordToolController@postApiKeys');
Route::post('keywordTracker/remove/{index}', 'KeywordToolController@removeProduct');
Route::get('addProduct', 'KeywordToolController@addProduct');
Route::post('addProduct', 'KeywordToolController@postProduct');
Route::get('addProductBulk', 'KeywordToolController@addProductBulk');
Route::post('addProductBulk', 'KeywordToolController@addProductBulkPost');
Route::post('removeProduct', 'KeywordToolController@removeProduct');
Route::post('customers/ajax_get','CustomerController@ajax_get');
Route::post('customers/ajax_get_orders/{customer}','CustomerController@ajax_get_orders');
Route::get('customers','CustomerController@index');
Route::get('customers/orders/{customer}','CustomerController@orders');


Route::get('ajax/tableDetail', ['middleware' => 'auth', function() {
    $user = Auth::user();
    $location = $user->getLocation();
    $detailData = $user->getDashboardDetailData( Input::get('asin') . '|||' . Input::get('keyword') . '|||' . $location );
    $bsrSum = 0;
    $rankSum = 0;
    $count = 0;
    foreach($detailData['table'] as $point) {
        $bsrSum += $point->bsr;
        $rankSum += $point->rank;
        $count++;
    }
    if ($count != 0) {
        $detailData['tableMeta']['avgBsr'] = round($bsrSum / $count);
        $detailData['tableMeta']['avgRank'] = round($rankSum / $count);
    } else {
        $detailData['tableMeta']['avgBsr'] = 0;
        $detailData['tableMeta']['avgRank'] = 0;
    }
    echo(json_encode($detailData));
}]);


Route::get('create_user_tables',function(){
    \AmazonHelper::create_customer_order_table();
});

Route::get('populate_order_table',function(){
    set_time_limit(0);
    \AmazonHelper::populate_order_table();
    echo "<br/> Data from order table imported successfully .<br/>";
});

Route::get('populate_order_table_from_sales',function(){
    set_time_limit(0);
    \AmazonHelper::populate_order_Table_from_sale_table();
    echo "<br/>Data from sales table is imported successfully.<br/>";
});

Route::get('populate_sale_offset_table',function(){
    set_time_limit(0);
    \AmazonHelper::populate_sale_offset_table();
    echo "<br/>Sale offset table populated successfully.<br/>";
});




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
