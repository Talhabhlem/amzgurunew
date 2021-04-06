<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\TeHelper;
use App\Profit;

class Sale extends Model
{
    protected $table = 'sales_';

    public static function get_sale_date($user_id = -1, $sale_type = 'new')
    {
        $table_name = 'sales_' . $user_id;
        $data = DB::select("SELECT sale_type,  MIN(purchase_date) as first_date,MAX(purchase_date) as latest_date FROM $table_name group by  sale_type ");
//        echo "SELECT sale_type,  MIN(purchase_date) as first_date,MAX(purchase_date) as latest_date FROM $table_name group by  sale_type ";exit;

        return $data;

    }

    public static function get_above_row_from_first_new($user_id = '', $first_new_date)
    {
        $query = "SELECT * FROM sales_" . $user_id . " WHERE purchase_date<'" . $first_new_date . "' LIMIT 0,1";
        $data = DB::select($query);
        return count($data) > 0 ? $data[0]->purchase_date : '0000-00-00 00:00:00';
    }

    public static function helper_get_sales($data, $cron = 0)
    {

        $pdt_from_date = '';
        $pdt_to_date = '';

        if ($cron == 0) {
            $per_page = 20;
            $pageno = (isset($_POST['pageno']) && $_POST['pageno'] != '') ? $_POST['pageno'] : 1;
            $search_keyword = (isset($_POST['search_keyword']) && $_POST['search_keyword'] != '') ? $_POST['search_keyword'] : '';
            $last_30days_from = date('Y-m-d', strtotime("now -30 days")) . ' 00:00:00';
            $last_30days_to = date('Y-m-d') . ' 23:59:59';
            $from_date = (isset($_POST['from_date']) && $_POST['from_date'] != '') ? $_POST['from_date'] : $last_30days_from;
            $to_date = (isset($_POST['to_date']) && $_POST['to_date'] != '') ? $_POST['to_date'] : $last_30days_to;
            $pdt_from_date = $from_date;
            $pdt_to_date = $to_date;
            $from_date = TeHelper::te_change_timezone($from_date, 'UTC');
            $to_date = TeHelper::te_change_timezone($to_date, 'UTC');
            $order_by = (isset($_POST['order_by']) && $_POST['order_by'] != '') ? $_POST['order_by'] : 'total_qty';
            $order = (isset($_POST['order']) && $_POST['order'] != '') ? $_POST['order'] : 'DESC';
        } else {

            $per_page = '';
            $pageno = 1;
            $search_keyword = '';
            $from_date = $data['from_date'];
            $to_date = $data['to_date'];
            //echo "$from_date - $to_date <br/>";
            $pdt_from_date = $from_date;
            $pdt_to_date = $to_date;
            $from_date = TeHelper::te_change_timezone($from_date, 'UTC');
            $to_date = TeHelper::te_change_timezone($to_date, 'UTC');
            //echo "$from_date - $to_date <br/>";exit;
            $order_by = 'total_qty';
            $order = 'DESC';

        }

        $data['search_keyword'] = $search_keyword;
//	echo "$from_date - $to_date <br/>";
        $data['from_date'] = $pdt_from_date;
        $data['to_date'] = $pdt_to_date;
//	echo $data['from_date'].' - '.$data['to_date']." <br/>";exit;
        $data['sales_order_by'] = $order_by;
        $data['sales_order'] = $order;
        $total = self::count($data['userdata']->id, $search_keyword, $from_date, $to_date);
        $data['total'] = $total;
        $data['perpage'] = $per_page;
        $data['pageno'] = $pageno;
        $data['sales'] = self::get_all($data['userdata']->id, $search_keyword, $from_date, $to_date, $per_page, ($pageno - 1) * $per_page, $order_by, $order);

        $data['sales_sum'] = self::get_sum($data['userdata']->id, $search_keyword, $from_date, $to_date);

        if ($cron == 0)
            $data["sales_page_links"] = TeHelper::te_create_pagination_ajax($total, $pageno, $per_page);

//	$data['sku_profit'] = $CI->Model_sales->get_all_sku($data['userdata']->id,$from_date,$to_date);


        $data['overall_stats']['total_units_sold'] = $data['sales_sum']->qty_sum;
        $data['overall_stats']['total_gross_sale'] = $data['sales_sum']->price_sum;
        $data['overall_stats']['total_profit'] = 0.0;

        $sku_list = array(0);
        foreach ($data['sales'] as $s) {
            $sku_list[] = $s->sku;
        }
        $data['sku_list'] = $sku_list;
        self::update_profit_prices($data['userdata']->id, $data['sku_list']);

        $data['profit_setting'] = Profit::get_by_skulist($data['userdata']->id, $sku_list);



        $data['final_sales'] = array();
        foreach ($data['sales'] as $s) {
            if (!isset($data['profit_setting'][$s->sku])) continue;
            $s->profit_cost             = $data['profit_setting'][$s->sku]->cost;
            $s->profit_fee              = $data['profit_setting'][$s->sku]->fee;
            $s->profit_fulfilment       = $data['profit_setting'][$s->sku]->fulfilment;
            $s->profit_weight_handling  = $data['profit_setting'][$s->sku]->weight_handling;
            $s->item_profit = $s->total_price - ($s->total_qty * ($s->profit_cost + $s->profit_fee + $s->profit_fulfilment + $s->profit_weight_handling));
            $data['overall_stats']['total_profit'] += $s->item_profit;
            $data['final_sales'][] = $s;
        }
        $data['sales'] = $data['final_sales'];
        unset($data['final_sales']);
        $data['last_7_days_from'] = "";
//        p_rr($data)
        return $data;
    }


    public static function count($user_id = '', $search_keyword = '', $from = '', $to = '')
    {
        $user_id_clause = $user_id == '' ? "" : " c.user_id='" . $user_id . "'";
        $search_clause = $search_keyword == '' ? "" : " AND (c.sku LIKE '%" . $search_keyword . "%')";
        $from_clause = $from == '' ? '' : " AND c.purchase_date>='" . $from . "'";
        $to_clause = $to == '' ? '' : " AND c.purchase_date<='" . $to . "'";
        $query = "SELECT sku FROM sales_" . $user_id . " c WHERE 1 $search_clause $from_clause $to_clause " . self::limit_product_query($user_id) . " GROUP BY c.sku";
        // echo $query."<br/>";
        $data = DB::select($query);
        return count($data);
    }

    public static function get_previous_price($sku, $user_id)
    {

        $query = "SELECT qty,price FROM sales_" . $user_id . " WHERE sku='" . $sku . "' AND status<>'pending' ORDER BY purchase_date DESC LIMIT 0,1 ";
        $data = DB::select($query);
        $unit_price = 0;
        if (count($data) > 0) {
            $price = $data[0]->price;
            $qty = $data[0]->qty;
            $unit_price = $qty == 0 ? 0 : $price / $qty;
        }
        return $unit_price;
    }

    public static function chart_of_week_info($data)
    {

        extract($data);
        $user_id_clause = $user_id == '' ? "" : " AND user_id='" . $user_id . "'";
//        $from_clause = $from_date == '' ? '' : " AND CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles')>='" . $from_date . "'";
//        $from_clause = $from_date == '' ? '' : " AND CONVERT_TZ(purchase_date,'+00:00','-08:00'  )>='" . $from_date . "'";
        $from_clause = $from_date == '' ? '' : " AND purchase_date>='" . $from_date . "'";
//        $to_clause = $to_date == '' ? '' : " AND CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles')<='" . $to_date . "'";
//        $to_clause = $to_date == '' ? '' : " AND CONVERT_TZ(purchase_date,'+00:00','-08:00')<='" . $to_date . "'";
        $to_clause = $to_date == '' ? '' : " AND purchase_date<='" . $to_date . "'";
        $sku_clause = $sku == 'ALL_SKU' ? '1' : "sku='" . $sku . "'";
//        $query = "SELECT SUBSTR(DATE_ADD(CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles'), INTERVAL(1-DAYOFWEEK(CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles'))) +1 DAY),1,10) pdate, SUM(qty) AS qtysum, SUM(price)/SUM(qty) AS average, price/qty AS price,MAX(price) AS maximum,MIN(price) AS minimum FROM sales_" . $user_id . " WHERE $sku_clause $from_clause $to_clause  GROUP BY pdate";
//        $query = "SELECT SUBSTR(DATE_ADD(CONVERT_TZ(purchase_date,'+00:00','-08:00'), INTERVAL(1-DAYOFWEEK(CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles'))) +1 DAY),1,10) pdate, SUM(qty) AS qtysum, SUM(price)/SUM(qty) AS average, price/qty AS price,MAX(price) AS maximum,MIN(price) AS minimum FROM sales_" . $user_id . " WHERE $sku_clause $from_clause $to_clause  GROUP BY pdate";
        $query = "SELECT SUBSTR(DATE_ADD(purchase_date, INTERVAL(1-DAYOFWEEK(purchase_date)) +1 DAY),1,10) pdate, SUM(qty) AS qtysum, SUM(price)/SUM(qty) AS average, price/qty AS price,MAX(price) AS maximum,MIN(price) AS minimum FROM sales_" . $user_id . " WHERE $sku_clause $from_clause $to_clause  GROUP BY pdate";
        $data['sales'] = DB::select($query);
//        $query = "SELECT SUBSTR(DATE_ADD(CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles'), INTERVAL(1-DAYOFWEEK(CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles'))) +1 DAY),1,10) pdate, SUM(qty) AS qtysum FROM sales_" . $user_id . " WHERE status='pending' AND $sku_clause $from_clause $to_clause  GROUP BY pdate";
//        $query = "SELECT SUBSTR(DATE_ADD(CONVERT_TZ(purchase_date,'+00:00','-08:00'), INTERVAL(1-DAYOFWEEK(CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles'))) +1 DAY),1,10) pdate, SUM(qty) AS qtysum FROM sales_" . $user_id . " WHERE status='pending' AND $sku_clause $from_clause $to_clause  GROUP BY pdate";
        $query = "SELECT SUBSTR(DATE_ADD(purchase_date, INTERVAL(1-DAYOFWEEK(purchase_date)) +1 DAY),1,10) pdate, SUM(qty) AS qtysum FROM sales_" . $user_id . " WHERE status='pending' AND $sku_clause $from_clause $to_clause  GROUP BY pdate";
        $data['psales'] = DB::select($query);
        $user_id_clause = $user_id == '' ? "" : "  AND created_by='" . $user_id . "'";
        $from_clause = $from_date == '' ? '' : " AND event_date>='" . $from_date . "'";
        $to_clause = $to_date == '' ? '' : " AND event_date<='" . $to_date . "'";
        $query = "SELECT SUBSTR(event_date,1,10) as pdate, GROUP_CONCAT(`description` SEPARATOR ' , ') event_name FROM events WHERE  $sku_clause $user_id_clause $from_clause $to_clause GROUP BY pdate";
        $data['events'] = DB::select($query);
        return $data;
    }

    public static function daily_graph_info($data)
    {
        extract($data);
        $user_id_clause = $user_id == '' ? "" : " AND user_id='" . $user_id . "'";
        $from_clause    = $from_date == '' ? '' : " AND CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles')>='" . $from_date . "'";
//        $from_clause    = $from_date == '' ? '' : " AND CONVERT_TZ(purchase_date,'+00:00','-08:00')>='" . $from_date . "'";
        $from_clause    = $from_date == '' ? '' : " AND purchase_date>='" . $from_date . "'";
//        $to_clause      = $to_date == '' ? '' : " AND CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles')<='" . $to_date . "'";
        $to_clause      = $to_date == '' ? '' : " AND CONVERT_TZ(purchase_date,'+00:00','-08:00')<='" . $to_date . "'";
//        $to_clause      = $to_date == '' ? '' : " AND purchase_date<='" . $to_date . "'";
        $sku_clause     = $sku == 'ALL_SKU' ? '1' : "sku='" . $sku . "'";
//        $query          = "SELECT SUM(qty) AS qtysum,( SUM(price)/SUM(qty) ) AS average, price/qty AS price, MAX(price) AS maximum,min(price) AS minimum, SUBSTR(CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles'),1,10) AS pdate FROM sales_" . $user_id . " WHERE $sku_clause $from_clause $to_clause GROUP BY pdate";
        $query          = "SELECT SUM(qty) AS qtysum,( SUM(price)/SUM(qty) ) AS average, price/qty AS price, MAX(price) AS maximum,min(price) AS minimum, SUBSTR(CONVERT_TZ(purchase_date,'+00:00','-08:00'),1,10) AS pdate FROM sales_" . $user_id . " WHERE $sku_clause $from_clause $to_clause GROUP BY pdate";
//        $query          = "SELECT SUM(qty) AS qtysum,( SUM(price)/SUM(qty) ) AS average, price/qty AS price, MAX(price) AS maximum,min(price) AS minimum, purchase_date AS pdate FROM sales_" . $user_id . " WHERE $sku_clause $from_clause $to_clause GROUP BY pdate";

        $data['sales']  = DB::select( $query );

//        $query          = "SELECT SUM(qty) AS qtysum,SUBSTR(CONVERT_TZ(purchase_date,'UTC','America/Los_Angeles'),1,10) AS pdate FROM sales_" . $user_id . " WHERE status='pending' AND  $sku_clause $from_clause $to_clause GROUP BY pdate";
        $query          = "SELECT SUM(qty) AS qtysum,SUBSTR(CONVERT_TZ(purchase_date,'+00:00','-08:00'),1,10) AS pdate FROM sales_" . $user_id . " WHERE status='pending' AND  $sku_clause $from_clause $to_clause GROUP BY pdate";
//        $query          = "SELECT SUM(qty) AS qtysum, purchase_date AS pdate FROM sales_" . $user_id . " WHERE status='pending' AND  $sku_clause $from_clause $to_clause GROUP BY pdate";
        $data['psales'] = DB::select( $query );
        $user_id_clause = $user_id == '' ? "" : "  AND created_by='" . $user_id . "'";
        $from_clause    = $from_date == '' ? '' : " AND event_date>='" . $from_date . "'";
        $to_clause      = $to_date == '' ? '' : " AND event_date<='" . $to_date . "'";
//        $query          = "SELECT event_date as pdate, GROUP_CONCAT(`description` SEPARATOR ' , ') event_name FROM events WHERE  $sku_clause $user_id_clause $from_clause $to_clause GROUP BY pdate";
        $query          = "SELECT event_date as pdate, GROUP_CONCAT(`description` SEPARATOR ' , ') event_name FROM events WHERE  $sku_clause $user_id_clause $from_clause $to_clause GROUP BY pdate";
        $data['events'] = DB::select( $query );
        return $data;
    }


    public static function update_profit_prices($user_id, $sku_list = array())
    {
        foreach ($sku_list as $sku) {
            $unit_price = self::get_previous_price($sku, $user_id);
            $_profit = array();
            $_profit['user_id'] = $user_id;
            $_profit['sku'] = $sku;
            $_profit['unit_price'] = $unit_price;
            $_profit['fee'] = $unit_price * 0.15;

            /*
             * Todo
             * For Rashid
             * Update model_profit save_Cron method
             *
             *
             * */

//            $CI->Model_profit->save_cron($_profit);


        }
    }


    public static function    get_all($user_id = '', $search_keyword = '', $from = '', $to = '', $limit = '', $start = '', $order_by = 'c.purchase_date', $order = 'DESC')
    {

        $limit_clause = ($limit == '') ? "" : " LIMIT $start,$limit";

        $user_id_clause = $user_id == '' ? "" : " c.user_id='" . $user_id . "'";
        $from_clause = $from == '' ? '' : " AND c.purchase_date>='" . $from . "'";
        $to_clause = $to == '' ? '' : " AND c.purchase_date<='" . $to . "'";

        $order_by = $order_by == '' ? 'c.purchase_date' : $order_by;
        $order = $order == '' ? 'DESC' : $order;
        $order_clause = " ORDER BY $order_by $order";

        $search_clause = $search_keyword == '' ? "" : " AND (c.sku LIKE '%" . $search_keyword . "%')";

        $s_user_id_clause = $user_id == '' ? "" : " s.user_id='" . $user_id . "'";
        $s_from_clause = $from == '' ? '' : " AND s.purchase_date>='" . $from . "'";
        $s_to_clause = $to == '' ? '' : " AND s.purchase_date<='" . $to . "'";

        $s_search_clause = $search_keyword == '' ? "" : " AND (s.sku LIKE '%" . $search_keyword . "%')";

        $qty_sum_subquery = "SELECT SUM(s.qty) as qty_sum FROM sales_" . $user_id . " s WHERE 1 $s_search_clause $s_from_clause $s_to_clause ";//.$this->limit_product_query($user_id,5);
        $price_sum_subquery = "SELECT SUM(s.price) as price_sum FROM sales_" . $user_id . " s WHERE 1 $s_search_clause $s_from_clause $s_to_clause ";//.$this->limit_product_query($user_id,5);

        $query = "SELECT SUM(c.qty) AS total_qty, SUM(c.qty)/($qty_sum_subquery) AS qty_percentage,
              SUM(price) AS total_price, SUM(c.price)/($price_sum_subquery) AS price_percentage, c.currency_code, c.sku, c.asin, title,
              MAX(purchase_date) AS latest_date FROM sales_" . $user_id . " c WHERE 1 $search_clause $from_clause $to_clause
              " . self::limit_product_query($user_id, $from, $to) . " GROUP BY c.sku $order_clause $limit_clause";
        // echo $query;
        // exit;
        $data = DB::select($query);
        return $data;
    }

    public static function get_sum($user_id = '', $search_keyword = '', $from = '', $to = '')
    {
        $search_clause = $search_keyword == '' ? "" : " AND (sku LIKE '%" . $search_keyword . "%')";
        $from_clause = $from == '' ? '' : " AND purchase_date>='" . $from . "'";
        $to_clause = $to == '' ? '' : " AND purchase_date<='" . $to . "'";
        $query = "SELECT SUM(qty) as qty_sum, SUM(price) as price_sum FROM sales_" . $user_id . " WHERE 1 $search_clause $from_clause $to_clause";
        // echo $query;
        $data = DB::select($query);
        return $data[0];
    }

    public static function limit_product_query($user_id, $from = '', $to = '', $table_prefix = '')
    {

        return '';
        $limit      = helper_level_products(helper_get_level($user_id));
        $selected   = $CI->Model_sales->get_selected_products($user_id);
        $sku_list   = $CI->Model_sales->get_all_sku($user_id, $from, $to);
        $new_selected = array();
        if ($limit != 99999) {
            $new_counter = 0;
            foreach ($selected as $sku) {
                if ($new_counter < $limit) {
                    $new_selected[] = $sku;
                } else {
                    break;
                }
                $new_counter++;
            }
            $selected = count($selected) == 0 ? $new_selected : $selected;
        } else {
            foreach ($sku_list as $skuu) {
                $new_selected[] = $skuu['sku'];
            }
            $selected = count($selected) == 0 ? $new_selected : $selected;
        }
        $selected[] = 0;
        $query = "AND " . ($table_prefix == '' ? '' : $table_prefix . ".") . "sku IN ( '" . implode("' , '", $selected) . "' )";
        return $limit == 99999 ? '' : $query;
    }


    public static function    get_sku_for_events($user_id, $sku_list)
    {

        $sku_clause = implode("','", $sku_list);
        $data = DB::select("SELECT sku,asin,title FROM sales_" . $user_id . " WHERE sku IN ('" . $sku_clause . "') GROUP BY sku ORDER BY purchase_date DESC");

        $finaldata = array();
        foreach ($data as $dt) {
            $temp = array();
            $temp['asin'] = $dt->asin;
            $temp['title'] = $dt->title;
            $finaldata[$dt->sku] = $temp;
        }
        return $finaldata;
    }


    public static function already_exists($item_id,$user_id) {
        $q = "SELECT id FROM sales_".$user_id." WHERE item_id='".$item_id."'";
        $data= DB::select($q);
        return count($data)>0 ? true:false;
    }

    public static function order_already_exists($order_id,$user_id) {
        $query = "SELECT id FROM sales_".$user_id." WHERE order_id='".$order_id."'";
        $data= DB::select($query);
        return count($data)>0 ? true:false;
    }
    public static function  get_by($user_id,$by,$value) {
        $query = "SELECT * FROM sales_$user_id WHERE $by='{$value}'";
        $data = DB::select($query);
        return count($data)>0 ? $data[0]:NULL;
    }

    public static  function insert($user_id,$data)
    {
        $data['loaded_on'] = date('Y-m-d H:i:s');
        return DB::table('sales_'.$user_id)->insertGetId($data);
    }

    public static function delete_table_data($order_id,$user_id){

        DB::table('sales_'.$user_id)->where('order_id','=', $order_id)->delete();

    }

    public static function get_type_by_order_id($order_id, $user_id) {

//        echo "here it is ->";
//        exit;
        $type = 'new';
        $data = DB::table('sales_'.$user_id)->where('order_id','=', $order_id)->get();
        foreach($data as $row) {
            $type = $row->sale_type;
        }
        return $type;
    }
    public static function insert_missing_date($user_id)
    {
        $data = array();
        $data['purchase_date'] = '2015-10-15 00:00:00';
        $data['user_id'] = $user_id;
        return DB::table('user_missing_sales')->insertGetId($data);
    }
    public static function insert_sale_offset($user_id)
    {
        $data = array();
        $data['offset']  = '0';
        $data['user_id'] = $user_id;
        return DB::table('user_last_sale_limit')->insertGetId($data);
    }
    public static function get_sale_offset($user_id)
    {
        $query = "SELECT  offset from  user_last_sale_limit where user_id='{$user_id}'";
        $data  = DB::select($query);
        if(count($data)>0) return $data[0]->offset;
        else return array();
    }
    public static function update_sale_offset($user_id,$data)
    {
        DB::table('user_last_sale_limit')
            ->where('user_id', $user_id)
            ->update($data);
    }

    public static function get_missing_date($user_id)
    {
        $query = "SELECT  purchase_date from  user_missing_sales where user_id='{$user_id}'";
        $data  = DB::select($query);
        if(count($data)>0) return $data[0]->purchase_date;
        else return array();
    }
    public static function update_missing_date($user_id,$data)
    {
        DB::table('user_missing_sales')
            ->where('user_id', $user_id)
            ->update($data);
    }

    public static function get_product_by_sku($sku,$user_id)
    {
        $query = "SELECT * FROM sales_{$user_id} where sku='{$sku}' group by sku order by purchase_date";
        $data  = DB::select($query);
        if(count($data)>0) return $data[0];
        else return array();
    }
    public static function get_duplicate_ids($user_id)
    {
        $query  ="SELECT customer_id, order_id, COUNT( * ) , GROUP_CONCAT( id ) as ids
                    FROM customer_order_{$user_id}
                    GROUP BY customer_id, order_id
                    HAVING COUNT( * ) >1
                    LIMIT 0,30000
                    ";
        $data  = DB::select($query);
        return $data;
    }
    public static function delete_customer_order_relation($relation_id,$user_id)
    {
        $table = "customer_order_{$user_id}";
        DB::table($table)->where('id', '=', $relation_id)->delete();
    }
    public static function delete_sales_by_order_id($order_id, $user_id)
    {
        $table = "sales_{$user_id}";
        DB::table($table)->where('order_id', '=', $order_id)->delete();
    }
}
