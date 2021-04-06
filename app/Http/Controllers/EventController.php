<?php

namespace App\Http\Controllers;

use App\Sale;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
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
        $data['userdata'] = $user;
        $data = Event::helper_get_events($data);
//        p_rr($data);
//        exit;
//        echo "<pre>";
        return view('events.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $data['userdata'] = $user;
///        if (intval($id) > 0)
            $data['event'] = new \App\Event();
        $data['selected_product'] = array();//  $this->Model_event->get_all_product($user['ID']);
        return view('events.create', $data);
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
        $user = Auth::user();
        $data['userdata'] = $user;
        $data['event']    = $user->events()->findOrFail($id);
        $selected_product  = Sale::get_product_by_sku($data['event']->sku,$user->id);
        $data['selected_product'] = $selected_product;
        return view('events.create', $data);
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
    public function ajax_product_name_search()
    {
        $data['userdata'] = Auth::user();
        $q = $_REQUEST['q'];
        $products = Event::search_product($data['userdata']->id,$q);
        $response['items'] = $products;
        echo json_encode($response);exit;
    }
    public function ajax_save_event(){
        $user = Auth::user();
        $data['userdata'] = $user;
        $response    = array();
        $errors      = array();
        $response['status'] = 'success';
        if (intval($_POST['event_id']) > 0) {
            $event = $user->events()->findOrFail($_POST['event_id']);
            $event->update($_POST);
        } else {
            $event = $user->events()->create($_POST);
        }
        echo json_encode($response);
        exit;
    }
    public function delete($id)
    {
        $user = Auth::user();
        $event = $user->events()->findOrFail($id);
        $event->delete();
        return redirect("events");
    }
    public function ajax_get_events()
    {
        $data['userdata'] = Auth::user();
        $response['status'] = 'success';
        $data = Event::helper_get_events($data);
        ob_start();
        $response['view_table_html'] = (String) view('events._table',$data);
        echo json_encode($response);
        exit;
    }
}
