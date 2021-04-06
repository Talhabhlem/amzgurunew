<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $data = array();
        $data['userdata'] = $user;
        $data = Customer::helper_get_customers($data);
        return view('customers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function ajax_get()
    {
        $data['userdata'] = Auth::user();
        $response['status'] = 'success';
        $data = Customer::helper_get_customers($data);
        ob_start();
        $response['view_table_html'] = (String) view('customers._table',$data);
        echo json_encode($response);
        exit;
    }
    public function ajax_get_orders($customer_id)
    {
        $data = array();
        $data['userdata']       = Auth::user();
        $data['customer_id']    = $customer_id;
        $data['customer']    = Customer::get_customer_by_id($customer_id,$data['userdata']->id);
        $response['status'] = 'success';
        $data = Customer::helper_get_customer_orders($data);
        ob_start();
        $response['view_table_html'] = (String) view('customers._order_table',$data);
        echo json_encode($response);
        exit;
    }

    public function orders($customer_id)
    {
        $data = array();
        $data['userdata']       = Auth::user();
        $data['customer_id']    = $customer_id;
        $data['customer']    = Customer::get_customer_by_id($customer_id,$data['userdata']->id);
        $data = Customer::helper_get_customer_orders($data);
        return view('customers.orders', $data);
    }
}
