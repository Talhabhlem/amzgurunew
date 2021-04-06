<?php

namespace App;

use App\Helpers\TeHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Activity extends Model
{
    public static function helper_get_activity($data) {
        $per_page = 200;
        $pageno = (isset($_POST['pageno']) && $_POST['pageno']!='') ? $_POST['pageno']:1;
        $search_keyword = (isset($_POST['search_keyword']) && $_POST['search_keyword']!='') ? $_POST['search_keyword']:'';
        $order_by = (isset($_POST['order_by']) && $_POST['order_by']!='') ? $_POST['order_by']:'loaded_on';
        $order = (isset($_POST['order']) && $_POST['order']!='') ? $_POST['order']:'DESC';
        $data['search_keyword'] = $search_keyword;
        $data['activity_order_by'] = $order_by;
        $data['activity_order'] = $order;
        $total = self::count($data['userdata']->id,$search_keyword);
        $data['total'] 		= $total;
        $data['perpage'] 	= $per_page;
        $data['pageno'] 	= $pageno;
        $data['activity'] 	= self::get_all($data['userdata']->id,$search_keyword,'','',$per_page, ($pageno-1)*$per_page,$order_by,$order);
        $data["activity_page_links"] = TeHelper::te_create_pagination_ajax($total,$pageno,$per_page);
        return $data;
    }

    public static function count($user_id='',$search_keyword='',$from='',$to='') {
        $user_id_clause = $user_id=='' ? "":" c.user_id='".$user_id."'";
        $search_clause = $search_keyword=='' ? "":" AND (c.order_id LIKE '%".$search_keyword."%')";
        $query = "SELECT COUNT(sku) AS total_activity FROM sales_".$user_id." c WHERE 1 $search_clause";
        $data  = DB::select($query);
        return $data[0]->total_activity;
    }

    public static function get_all($user_id='',$search_keyword='',$from='',$to='',$limit='',$start='',$order_by='c.loaded_on',$order='DESC' ) {
        $user_id_clause = $user_id=='' ? "":" c.user_id='".$user_id."'";
        $limit_clause = ($limit=='') ? "":" LIMIT $start,$limit";
        $order_by = $order_by=='' ? 'c.loaded_on':$order_by;
        $order = $order=='' ? 'DESC':$order;
        $order_clause  = " ORDER BY $order_by $order";
        $search_clause = $search_keyword=='' ? "":" AND (c.order_id LIKE '%".$search_keyword."%')";
        $query = "SELECT * FROM sales_".$user_id." c WHERE 1 $search_clause $order_clause $limit_clause";
        $data =  DB::select($query);
        return $data;
    }
}
