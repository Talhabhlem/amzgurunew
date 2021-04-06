<?php

namespace App\Http\Controllers;

use App\Sale;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\TeHelper;
use App\AmazonSettings;
class AnalysisController extends Controller
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
        $data = Sale::helper_get_sales($data);
//dd(Sale::helper_get_sales($data));
        $data = TeHelper::get_sale_dates($user, $data);
        $amazon = $user->AmazonSettings()->get()->first();

        if(!isset($amazon) )
        {
            $amazon = new AmazonSettings();
        }

        $data['api_setup'] = 'yes';
        // if(@$amazon->merchant_id=='' || @$amazon->marketplace_id=='' || @$amazon->access_key=='' || @$amazon->secret_key=='' || @$amazon->region=='' ||  $amazon->is_valid=='no') {
        if(@$amazon->merchant_id=='' || @$amazon->marketplace_id=='' || @$amazon->access_key=='' || @$amazon->secret_key=='' || @$amazon->region=='') {
            $data['api_setup'] = 'no';
        }

        $data['cuser'] = $user;
//        dd($data);
        return view('analysis', $data);
    }

    public function alternate_get_sales_method()
    {
        $user = Auth::user();
        $data = array();
        $response= array();
        $response['status'] = 'success';
        $data['userdata'] = $user;
        $data = Sale::helper_get_sales($data);
        $response['view_table_html'] = (String) view('analytics.sales_table' , $data);
        $response['view_stats_html'] =  (String) view('analytics.total' , $data);
        echo json_encode($response);
    }
    
    public function chart_of_week()
    {
        $user = Auth::user();
        $data['userdata'] = $user;
        $data = $_POST;
        $data['user_id'] = $user->id;
        $salesdata = Sale::chart_of_week_info($data);
        $sales = "";
        $price = "";
        $mydata = array();
        foreach ($salesdata['sales'] as $key => $value) {
            $lastcol = round($value->price, 2);
            $pcount = 0;
            foreach($salesdata['psales'] as $pkey => $pvalue) {
                if($pvalue->pdate == $value->pdate) {
                    $pcount = $pvalue->qtysum;
                    break;
                }
            }
            $mydata['sales'][] = array($value->pdate, $value->qtysum,$pcount);
            $mydata['price'][] = array($value->pdate, round($value->average, 2), $lastcol);
        }
        $mydata['events'] = array();
        foreach ($salesdata['events'] as $key => $value) {
            $val = 0.25;
            $mydata['events'][] = array($value->pdate,$val,$value->event_name);
        }
        $weekTime = 7*24*60*60;
        $mydata['line_pattern'] = 'solid';
        if(time() - strtotime($data['to_date']) > $weekTime) {
            $mydata['line_pattern'] = 'solid';
        }
        echo json_encode($mydata);
        exit;
    }

    public function daily_graph()
    {
        $user            = Auth::user();
        $data            = $_POST;
        $data['user_id'] = $user->id;
        $salesdata       = Sale::daily_graph_info( $data );
        $sales           = "";
        $price           = "";
        $mydata          = array();
        $mydata['sales'] = array();
        $mydata['price'] = array();

        foreach ($salesdata['sales'] as $key => $value) {
            $lastcol = round( $value->price, 2 );
            $pcount  = 0;

            foreach($salesdata['psales'] as $pkey => $pvalue) {
                if($pvalue->pdate == $value->pdate) {
                    $pcount = $pvalue->qtysum;
                    break;
                }
            }

            $mydata['sales'][] = array($value->pdate, $value->qtysum, $pcount);
            $mydata['price'][] = array($value->pdate, round($value->average, 2), $lastcol);
        }

        $mydata['events'] = array();
        foreach ($salesdata['events'] as $key => $value) {
            $val = 0.25;
            $mydata['events'][] = array($value->pdate,$val,$value->event_name);
        }

        $weekTime               = 7 * 24 * 60 * 60;
        $mydata['line_pattern'] = 'solid';

        if(time() - strtotime($data['to_date']) > $weekTime) {
            $mydata['line_pattern'] = 'solid';
        }

        echo json_encode($mydata);

        exit;

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
}
