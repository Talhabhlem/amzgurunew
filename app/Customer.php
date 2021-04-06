<?php

namespace App;

use App\Helpers\TeHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'AddressLine1', 'AddressLine2', 'AddressLine3','City','County','District','StateOrRegion','PostalCode','CountryCode','Phone'];
    public static function helper_get_customers($data) {
        $per_page = 100;
        $pageno = (isset($_POST['pageno']) && $_POST['pageno']!='') ? $_POST['pageno']:1;
        $search_keyword = (isset($_POST['search_keyword']) && $_POST['search_keyword']!='') ? $_POST['search_keyword']:'';
        $order_by = (isset($_POST['order_by']) && $_POST['order_by']!='') ? $_POST['order_by']:'created_at';
        $order = (isset($_POST['order']) && $_POST['order']!='') ? $_POST['order']:'DESC';
        $data['search_keyword'] = $search_keyword;
        $data['order_by'] = $order_by;
        $data['order']    = $order;
        $search['name'] = $search_keyword;
        $search['email'] = $search_keyword;
        $args['search'] = $search;
        $args['order_by'] = (isset($_POST['order_by']) && $_POST['order_by']!='') ? $_POST['order_by']:'created_at';
        $args['order']   = (isset($_POST['order']) && $_POST['order']!='') ? $_POST['order']:'DESC';
        $args['user_id'] = $data['userdata']->id;
        $args['limit']   = $per_page;
        $args['offset']  = ($pageno-1)*$per_page;
        $total = self::count($args);
        $data['total'] 		= $total;
        $data['perpage'] 	= $per_page;
        $data['pageno'] 	= $pageno;
        $data['customers'] 	= self::get_all($args);
        $data["page_links"] = TeHelper::te_create_pagination_ajax($total,$pageno,$per_page);
        return $data;
    }

    public static function count($args=array(),$get_pending=true) {
        $default = array( 	'user_id' => '',
            'search' => array('name'=>'','email'=>'')
        );
        $args = array_merge($default, $args);
        extract($args);
        $user_id_clause = $user_id=='' ? "":" user_id='".$user_id."'";
        $name_clause = $search['name']=='' ? "":" AND (name LIKE '%".$search['name']."%' Or email like '%".$search['name']."%')";
        $query = "SELECT COUNT(id) AS total_rows FROM customers_{$user_id} WHERE 1=1  $name_clause";
        $data = DB::select($query);
        return $data[0]->total_rows;
    }
    public static function get_all($args=array(),$get_pending=true) {

        $default = array( 	'user_id' => '',
            'search' => array('name'=>''),
            'offset' => '',
            'limit'  => '',
            'bug'    => '',
            'order'  => 'DESC',
            'order_by' => 'created_at'
        );
        $args = array_merge($default, $args);
        extract($args);
        $limit_clause = ($limit=='') ? "":" LIMIT $offset,$limit";
        $order_clause = " ORDER BY `customers_{$user_id}`.$order_by $order";
        $search_clause = $search['name']=='' ? "":" AND (name LIKE '%".$search['name']."%' Or email like '%".$search['name']."%')";
        $query = "SELECT * , count( orders_{$user_id}.order_id ) as total_orders,`customers_{$user_id}`.id as customer_id FROM `customers_{$user_id}` 
                JOIN customer_order_{$user_id} ON customer_id = `customers_{$user_id}`.id
                JOIN orders_{$user_id} ON orders_{$user_id}.order_id = customer_order_{$user_id}.order_id
                WHERE 1=1 $search_clause  
                GROUP BY `customers_{$user_id}`.id
                $order_clause
                $limit_clause
                ";
                // echo $query;
        $data = DB::select($query);
        return $data;
    }
    public static function delete_customer($id,$user_id)
    {
        $query = "DELETE FROM customers_{$user_id} WHERE id={$id}";
        DB::delete($query);
    }
    public static function get_customer_by_email($email,$user_id)
    {
        $query = "SELECT * FROM customers_{$user_id} WHERE email='{$email}'";
        $data = DB::select($query);
        if(count($data) > 0)  return $data[0];
        else return $data;
    }
    public static function update_customer($customer_id,$user_id,$data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $table = "customers_{$user_id}";
        DB::table($table)
            ->where('id', $customer_id)
            ->update($data);
    }
    public static function insert_customer($user_id,$data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return DB::table("customers_".$user_id)->insertGetId($data);
    }
    public static function already_exists($email,$user_id)
    {
        $data = self::get_customer_by_email($email,$user_id);
        if(count($data) > 0)  true;
        else return false;
    }
    public static function insert_customer_order_relation($order_id,$customer_id,$user_id)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $table = "customer_order_{$user_id}";
        return DB::table($table)->insertGetId(['customer_id'=>$customer_id,'order_id'=>$order_id]);
    }
    public static function customer_order_relation_already_exists($order_id,$customer_id,$user_id)
    {
        $query = "SELECT * FROM customer_order_{$user_id} WHERE customer_id='{$customer_id}' AND order_id='{$order_id}'";
        $data = DB::select($query);
        if(count($data) > 0)  return true;
        else return false;
    }

    public static function helper_get_customer_orders($data) {
        $user_id = $data['userdata']->id;
        $per_page = 30;
        $pageno = (isset($_POST['pageno']) && $_POST['pageno']!='') ? $_POST['pageno']:1;
        $search_keyword = (isset($_POST['search_keyword']) && $_POST['search_keyword']!='') ? $_POST['search_keyword']:'';
        $order_by = (isset($_POST['order_by']) && $_POST['order_by']!='') ? $_POST['order_by']:'created_at';
        $order = (isset($_POST['order']) && $_POST['order']!='') ? $_POST['order']:'DESC';
        $data['search_keyword'] = $search_keyword;
        $data['order_by'] = $order_by;
        $data['order']    = $order;
//        $search[] = $search_keyword;
        $args['search_keyword'] = $search_keyword;
        $args['order_by'] = (isset($_POST['order_by']) && $_POST['order_by']!='') ? $_POST['order_by']:'created_at';
        $args['order']   = (isset($_POST['order']) && $_POST['order']!='') ? $_POST['order']:'DESC';
        $args['user_id'] = $data['userdata']->id;
        $args['limit']   = $per_page;
        $args['offset']  = ($pageno-1)*$per_page;
        $args['customer_id']  = $data['customer_id'];
        $total = self::count_customer_orders($args);
        $data['total']      = $total;
        $data['perpage']    = $per_page;
        $data['pageno']     = $pageno;
        $data['orders']  = self::get_customer_orders($args);
        $data["page_links"] = TeHelper::te_create_pagination_ajax($total,$pageno,$per_page);
        return $data;
    }
    public static function count_customer_orders($args)
    {
        $default = array(   'user_id' => '',
            'search' => array('name'=>'','email'=>'')
        );
        $args = array_merge($default, $args);
        extract($args);
        $user_id_clause = $user_id=='' ? "":" user_id='".$user_id."'";
        // $name_clause = $search['name']=='' ? "":" AND (name LIKE '%".$search['name']."%')";
        $query = "SELECT count(*) AS total_rows FROM customer_order_{$user_id} r 
                    join customers_{$user_id} c  on c.id=r.customer_id
                    join orders_{$user_id} o on o.order_id=r.order_id
                    Where c.id={$customer_id}
                    ";
        $data = DB::select($query);
        return $data[0]->total_rows;
    }

    public static function get_customer_orders($args)
    {
        $default = array(   'user_id' => '',
                            'customer_id'=>'',
            'search' => array('name'=>''),
            'offset' => '',
            'limit'  => '',
            'bug'    => '',
            'order'  => 'DESC',
            'order_by' => 'created_at',
            'search_keyword' => ''
        );
        $args = array_merge($default, $args);
        extract($args);
        $order_id_clause = '';
        if($search_keyword)
             $order_id_clause = " AND r.order_id like '%{$search_keyword}%'";
        $query = "SELECT * FROM customer_order_{$user_id} r 
                    join customers_{$user_id} c  on c.id=r.customer_id
                    join orders_{$user_id} o on o.order_id=r.order_id
                    Where c.id={$customer_id} $order_id_clause
                    ";
//        echo $query;
        $data = DB::select($query);
        return $data;
    }
    public static function get_customer_by_id($customer_id,$user_id)
    {
        $query = "SELECT * FROM customers_{$user_id} WHERE id='{$customer_id}'";
        $data = DB::select($query);
        if(count($data) > 0)  return $data[0];
        else return $data;
    }
}
