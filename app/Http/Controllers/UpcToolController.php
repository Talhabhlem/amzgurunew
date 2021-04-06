<?php

namespace App\Http\Controllers;

use Input;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UpcToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('upcTool.upload');

    }


    /**
     * Currently not used. Doing a hacky post right to run.php instead
     *
     * @param Request $request
     * @return string
     */
    public function post(Request $request)
    {
//        echo("<pre>");
//        print_r($request);

        echo("<pre>");
        print_r(Input::all());
        $options = Input::all();

        $ch = curl_init();

        $fields = array(
            'submit' => 1,
            'email' => $options['email'],
            'ext' => $options['marketplace'],
            'fixed' => $options['refFee'],
            'upcfile' => '@'.$request->file('upcfile').';filename='.$options['upcfile']->fileName,
        );

//url-ify the data for the POST
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

//set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, 'http://localhost/ecommelite_final/amazonupc/run.php');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
        $result = curl_exec($ch);

        echo("<pre>");
        print_r($result);

        return 'test';

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
