<?php

namespace App\Http\Controllers;

use App\Profit;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfitController extends Controller
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
        $data = Profit::helper_get_profit_settings($data);
        $api_settings = $user->AmazonSettings()->get()->first();
        $data['api_setup'] = 'yes';
        if(@$api_settings->merchant_id=='' || @$api_settings->marketplace_id=='' || @$api_settings->access_key=='' || @$api_settings->secret_key=='') {
            $data['api_setup'] = 'no';
        }
        return view('profit_setting.index', $data);
    }


    public function ajax_get_profit_setting()
    {
        $data['userdata'] = Auth::user();

        $response['status'] = 'success';
        $data = Profit::helper_get_profit_settings($data);

        $response['view_table_html'] = (String)view('profit_setting.view_profit_setting_table',$data);

        echo json_encode($response);
    }

    public function ajax_save_profit_summery()
    {
        $user = Auth::user();
        $data['userdata'] = $user;
        //date_default_timezone_set($data['userdata']['timezone']);
        if(isset($_POST['cost']))
        {
            $cost 	= $_POST['cost']; //array
            $weight_handling 	= $_POST['weight_handling']; //array
            $misc_costs 	= $_POST['misc_costs']; //array
            $ppc_costs 	    = $_POST['ppc_costs']; //array
            $fulfilment 	= isset($_POST['fulfilment']) ? $_POST['fulfilment']:array(); //array
            foreach ($cost as $sku => $c)
            {
                $profit_setting = $user->ProfitSettings()->where('sku','=',$sku)->get()->first();
                $profit_row = array();
                $profit_row['weight_handling']  = $weight_handling[$sku];
                $profit_row['ppc_costs']        = $ppc_costs[$sku];
                $profit_row['misc_costs']       = $misc_costs[$sku];
                $profit_row['cost'] = $c;
                $profit_row['fulfilment'] = empty($fulfilment[$sku]) ? 2.04:$fulfilment[$sku];
                $profit_row['sku'] = $sku;
                if($profit_setting)
                {
                    $profit_setting->update($profit_row);
                }
                else
                {
                    $user->ProfitSettings()->create($profit_row);
                }
            }
        }

        $response['status'] = 'success';
        $data = Profit::helper_get_profit_settings($data);
        $response['view_table_html'] = (String)view('profit_setting.view_profit_setting_table',$data);
        echo json_encode($response);

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
