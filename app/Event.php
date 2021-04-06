<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\TeHelper;
use Illuminate\Support\Facades\DB;
use App\Sale;
class Event extends Model
{
    protected $fillable = ['sku','description','event_date','created_by','created_on'];
    public static function helper_get_events($data)
    {
        $per_page = 15;
        $pageno = (isset($_POST['pageno']) && $_POST['pageno']!='') ? $_POST['pageno']:1;
        $search_keyword = (isset($_POST['search_keyword']) && $_POST['search_keyword']!='') ? $_POST['search_keyword']:'';
        $order_by = (isset($_POST['order_by']) && $_POST['order_by']!='') ? $_POST['order_by']:'event_date';
        $order = (isset($_POST['order']) && $_POST['order']!='') ? $_POST['order']:'DESC';
        $data['search_keyword'] = $search_keyword;
        $data['event_order_by'] = $order_by;
        $data['event_order'] = $order;
        $total = self::count($data['userdata']->id,$search_keyword);
        $data['total'] 		= $total;
        $data['perpage'] 	= $per_page;
        $data['pageno'] 	= $pageno;
        $data['events'] 	= self::get_all($data['userdata']->id,$search_keyword,'','',$per_page, ($pageno-1)*$per_page,$order_by,$order);
        $all_sku = array();
        foreach($data['events'] as $event) {
            $all_sku[] = $event->sku;
        }
        $data['sku_titles'] = Sale::get_sku_for_events($data['userdata']->id,$all_sku);
        $data["events_page_links"] = TeHelper::te_create_pagination_ajax($total,$pageno,$per_page);
        return $data;
    }
    public static function get_all($user_id = '', $search_keyword = '', $from = '', $to = '', $limit = '', $start = '', $order_by = 'event_date', $order = 'DESC') {

        $user_id_clause = $user_id == '' ? "" : " created_by='" . $user_id . "'";

        $limit_clause = ($limit == '') ? "" : " LIMIT $start,$limit";

        $order_by = $order_by == '' ? 'created_on' : $order_by;
        $order = $order == '' ? 'DESC' : $order;
        $order_clause = " ORDER BY $order_by $order";
        $search_clause = $search_keyword == '' ? "" : " AND (description LIKE '%" . $search_keyword . "%' || sku LIKE '%" . $search_keyword . "%')";
        $query = "SELECT * FROM events WHERE $user_id_clause $search_clause ".Sale::limit_product_query($user_id)." $order_clause $limit_clause";
        $data = DB::select($query);
        return $data;
    }
    public static function count($user_id = '', $search_keyword = '', $from = '', $to = '') {
        $user_id_clause = $user_id == '' ? "" : " created_by='" . $user_id . "'";
        $search_clause = $search_keyword == '' ? "" : " AND (description LIKE '%" . $search_keyword . "%')";
        $query = "SELECT COUNT(id) AS total_events FROM events WHERE $user_id_clause $search_clause";
        $data = DB::select($query);
        return $data[0]->total_events;
    }


    public static function search_product($user_id,$keyword='')
    {
        $search_clause = $keyword == '' ? "" : " AND (sku LIKE '%" . $keyword . "%' || title LIKE '%" . $keyword . "%')";
        $query = "SELECT sku AS id, CONCAT(title,' ___ [',sku,']') AS text  FROM sales_".$user_id." WHERE 1 $search_clause  ".Sale::limit_product_query($user_id)." GROUP BY sku ORDER BY title ASC,purchase_date DESC LIMIT 0,50";
        return DB::select($query);
    }

}
