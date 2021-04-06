<?php

namespace App;

use App\Helpers\TeHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Askedio\Laravelcp\Models\User;

class Profit extends Model
{

    protected $table = 'profit_setting';
    protected $fillable = ['sku','unit_price','cost','cost','fee','fulfilment','weight_handling','misc_costs','ppc_costs'];
    public function user()
    {
        return $this->hasOne('User','id','user_id');
    }

    public static function get_by_skulist($user_id, $sku_list = array())
    {
        $sku_clause = count($sku_list) == 0 ? '' : " AND sku IN ('" . implode("','", $sku_list) . "')";
        $query = "SELECT * FROM profit_setting WHERE user_id='" . $user_id . "' $sku_clause";
        $data = DB::select($query);
        $profit_setting = array();
        foreach ($data as $dt) {
            $dt->fulfilment = empty($dt->fulfilment) ? 2.04 : $dt->fulfilment;
            $profit_setting[$dt->sku] = $dt;
        }
        return $profit_setting;
    }

    public static function helper_get_profit_settings($data) {

        $per_page = 20;
        $pdt_from_date='';
        $pdt_to_date = '';
        $pageno = (isset($_POST['pageno']) && $_POST['pageno']!='') ? $_POST['pageno']:1;
        $search_keyword = (isset($_POST['search_keyword']) && $_POST['search_keyword']!='') ? $_POST['search_keyword']:'';

        $last_30days_from = date('Y-m-d', strtotime("now -30 days") ).' 00:00:00';
        $last_30days_to = date('Y-m-d').' 23:59:59';

        $from_date = (isset($_POST['from_date']) && $_POST['from_date']!='') ? $_POST['from_date']:$last_30days_from;
        $to_date = (isset($_POST['to_date']) && $_POST['to_date']!='') ? $_POST['to_date']:$last_30days_to;

        $pdt_from_date = $from_date;
        $pdt_to_date = $to_date;
//		echo "$from_date - $to_date <br/>";
        $from_date = TeHelper::te_change_timezone($from_date,'UTC');
        $to_date = TeHelper::te_change_timezone($to_date,'UTC');
//		echo "$from_date - $to_date <br/>";exit;

        $order_by = (isset($_POST['order_by']) && $_POST['order_by']!='') ? $_POST['order_by']:'purchase_date';
        $order = (isset($_POST['order']) && $_POST['order']!='') ? $_POST['order']:'DESC';

        $data['search_keyword'] = $search_keyword;
        $data['from_date'] = $pdt_from_date;
        $data['to_date'] = $pdt_to_date;
        $data['order_by'] = $order_by;
        $data['order'] = $order;

        $total = self::count($data['userdata']->id,$search_keyword,$from_date,$to_date);

        $data['total'] 		= $total;
        $data['perpage'] 	= $per_page;
        $data['pageno'] 	= $pageno;
        $data['sales'] 	= self::get_all_settings($data['userdata']->id,$search_keyword,$from_date,$to_date,$per_page, ($pageno-1)*$per_page,$order_by,$order);

        $data["page_links"] = TeHelper::te_create_pagination_ajax($total,$pageno,$per_page);

        $sku_list = array(0);
        foreach($data['sales'] as $s) {
            $sku_list[] = $s->sku;
        }
        $data['sku_list'] = $sku_list;
        self::update_profit_prices($data['userdata']->id,$data['sku_list']);

        $data['profit_setting'] = self::get_by_skulist($data['userdata']->id,$sku_list);
//	p_rr($data['profit_setting']);exit;
        $data['final_sales'] = array();
        foreach($data['sales'] as $s) {
            $s->unit_price = $data['profit_setting'][$s->sku]->unit_price;
            $s->cost = $data['profit_setting'][$s->sku]->cost;
            $s->fee = $data['profit_setting'][$s->sku]->fee;
            $s->fulfilment = $data['profit_setting'][$s->sku]->fulfilment;
            $s->weight_handling = $data['profit_setting'][$s->sku]->weight_handling;
            $s->ppc_costs    = $data['profit_setting'][$s->sku]->ppc_costs;
            $s->misc_costs = $data['profit_setting'][$s->sku]->misc_costs;
            $s->profit = $s->total_price - ($s->total_qty*($s->cost+$s->fee+$s->fulfilment+$s->weight_handling+$s->ppc_costs+$s->misc_costs));
            $data['final_sales'][] = $s;
        }
        $data['sales'] = $data['final_sales']; unset($data['final_sales']);
        $data['last_7_days_from'] = "";
        return $data;
    }


    public static function count($user_id='',$search_keyword='',$from='',$to='') {

        $search_clause = $search_keyword=='' ? "":" AND (c.sku LIKE '%".$search_keyword."%' OR c.title LIKE '%".$search_keyword."%')";
        $from_clause = $from=='' ? '':" AND c.purchase_date>='".$from."'";
        $to_clause = $to=='' ? '':" AND c.purchase_date<='".$to."'";

        $query = "SELECT c.sku,title FROM sales_".$user_id." c WHERE 1 $search_clause $from_clause $to_clause
              ".Sale::limit_product_query($user_id,$from,$to,"c")." GROUP BY c.sku";
        $data = DB::select($query);

        return count($data);

    }
    public static function update_profit_prices($user_id,$sku_list=array()) {
        foreach($sku_list as $sku) {
            $unit_price = Sale::get_previous_price($sku,$user_id);
            $user = User::findOrFail($user_id);
            $profit_setting = $user->ProfitSettings()->where('sku','=',$sku)->get()->first();
            $_profit = array();
            $_profit['sku'] = $sku;
            $_profit['unit_price'] = $unit_price;
            $_profit['fee'] = $unit_price*0.15;
            if($profit_setting)
            {
                $profit_setting->update($_profit);
            }
            else
            {
                $user->ProfitSettings()->create($_profit);
            }
        }
    }

    public static function get_all_settings($user_id='',$search_keyword='',$from='',$to='',$limit='',$start='',$order_by='c.purchase_date',$order='DESC' ) {

        $limit_clause = ($limit=='') ? "":" LIMIT $start,$limit";

        $from_clause = $from=='' ? '':" AND c.purchase_date>='".$from."'";
        $to_clause = $to=='' ? '':" AND c.purchase_date<='".$to."'";

        $order_by = $order_by=='' ? 'c.purchase_date':$order_by;
        $order = $order=='' ? 'DESC':$order;
        $order_clause = " ORDER BY $order_by $order";

        $search_clause = $search_keyword=='' ? "":" AND (c.sku LIKE '%".$search_keyword."%' OR c.title LIKE '%".$search_keyword."%')";

        $query = "SELECT c.sku,c.asin,title,SUM(qty) AS total_qty, SUM(price) AS total_price FROM sales_".$user_id." c WHERE 1 $search_clause $from_clause $to_clause
              ".Sale::limit_product_query($user_id,$from,$to,"c")." GROUP BY c.sku $order_clause $limit_clause";

//		echo $query;exit;
        $data =  DB::select($query);

        return $data;

    }


}
