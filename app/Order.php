<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\TeHelper;

class Order extends Model
{
    protected $fillable = ['order_id', 'purchase_date', 'bug', 'status', 'created_on'];
    public static function helper_get_orders($data) {
        $per_page = 15;
        $pageno = (isset($_POST['pageno']) && $_POST['pageno']!='') ? $_POST['pageno']:1;
        $search_keyword = (isset($_POST['search_keyword']) && $_POST['search_keyword']!='') ? $_POST['search_keyword']:'';
        $order_by = (isset($_POST['order_by']) && $_POST['order_by']!='') ? $_POST['order_by']:'created_on';
        $order = (isset($_POST['order']) && $_POST['order']!='') ? $_POST['order']:'DESC';
        $data['search_keyword'] = $search_keyword;
        $data['order_by'] = $order_by;
        $data['order']    = $order;
        $search['order_id'] = $search_keyword;
        $args['search'] = $search;
        $args['status'] = $data['status']?$data['status']:'';
        $args['order_by'] = (isset($_POST['order_by']) && $_POST['order_by']!='') ? $_POST['order_by']:'created_on';
        $args['order']   = (isset($_POST['order']) && $_POST['order']!='') ? $_POST['order']:'DESC';
        $args['user_id'] = $data['userdata']->id;
        $args['limit']   = $per_page;
        $args['offset']  = ($pageno-1)*$per_page;
        $total = self::count($args);
        $data['total']      = $total;
        $data['perpage']    = $per_page;
        $data['pageno']     = $pageno;
        $data['orders']     = self::get_all($args);
        $data["page_links"] = TeHelper::te_create_pagination_ajax($total,$pageno,$per_page);
        return $data;
    }

    public static function count($args=array(),$get_pending=true) {
        $default = array(   'user_id' => '',
            'search' => array('order_id'=>''),
            'status' => '',
        );
        $args = array_merge($default, $args);
        extract($args);
        $user_id_clause = $user_id=='' ? "":" user_id='".$user_id."'";
        $search_clause = $search['order_id']=='' ? "":" AND (order_id LIKE '%".$search['order_id']."%')";
        $pending_clause = $status? " AND status='{$status}'":"";
        $query = "SELECT COUNT(order_id) AS total_rows FROM orders_{$user_id} WHERE 1=1  $pending_clause $search_clause";
        $data = DB::select($query);
        return $data[0]->total_rows;
    }

    public static function get_all($args=array(),$get_pending=true) {
        $default = array(   'user_id' => '',
            'search' => array('order_id'=>''),
            'status' => '',
            'offset' => '',
            'limit'  => '',
            'bug'    => '',
            'order'  => 'DESC',
            'order_by' => 'created_on'
        );
        $args = array_merge($default, $args);
        extract($args);
        $pending_clause = $status? " AND status='{$status}'":"";
        $bug_clause = $bug=='' ? "":" AND bug<>'yes'";
        $user_id_clause = $user_id=='' ? "":" user_id='".$user_id."'";
        $limit_clause = ($limit=='') ? "":" LIMIT $offset,$limit";
        $order_clause = " ORDER BY $order_by $order";
        $search_clause = $search['order_id']=='' ? "":" AND (order_id LIKE '%".$search['order_id']."%')";
        $query = "SELECT * FROM orders_{$user_id} WHERE 1=1 $pending_clause $bug_clause $search_clause $order_clause $limit_clause";
        // echo $query;exit;
        $data = DB::select($query);
        return $data;
    }
    public static function delete_order($order_id,$user_id)
    {
        $query = "DELETE FROM orders_{$user_id} WHERE order_id={$order_id}";
        DB::delete($query);
    }
    public static function get_order($order_id,$user_id)
    {
        $query = "SELECT * FROM orders_{$user_id} WHERE order_id='{$order_id}'";
        // echo $query."<br/>";
        $data = DB::select($query);
        if(count($data) > 0)  return $data[0];
        else return false;
    }
    public static function update_order($order_id,$user_id,$data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $table = "orders_{$user_id}";
        DB::table($table)
            ->where('order_id', $order_id)
            ->update($data);
    }
    public static function insert_order($user_id,$data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table("orders_".$user_id)->insertGetId($data);
        return $data['order_id'];
    }
    public static function already_exists($order_id,$user_id)
    {   
        $data = self::get_order($order_id,$user_id);
        return ($data)?true:false;
    }
}
