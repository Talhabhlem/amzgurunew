<?php

namespace App\Http\Controllers;

use App\AmazonSettings;
use App\EmailSettings;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
class SettingController extends Controller
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
        //
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
    public function amazon()
    {
        $cuser   = \Auth::user();
        $amazon = $cuser->AmazonSettings()->get()->first();
        if(!isset($amazon) )
        {
            $amazon = new AmazonSettings();
        }
        return view('settings.amazon',compact('amazon','cuser'));
    }
    public function email()
    {
        $cuser = \Auth::user();
        $email = $cuser->EmailSettings()->get()->first();
        if(!isset($email) )
        {
            $email = new EmailSettings();
        }
        $email->package = explode(',',$email->package);
        return view('settings.email',compact('email','cuser'));
    }
    public function ajax_save_amazon_settings()
    {
        $user       = Auth::user();
        $settings   = $user->AmazonSettings()->get()->first();
        if($settings)
        {
            $settings->update(Input::all());
        }
        else
        {
            $amazon_settings = $user->AmazonSettings()->create(Input::all());
        }
        $response = array();
        $errors = array();
        $response['status'] = 'success';
        echo json_encode($response);
        exit;
    }
    public function ajax_save_email_settings()
    {
        $data = Input::all();
        $data['package'] = implode(',',$data['package']);
        $user = Auth::user();
        $user->EmailSettings()->delete();
        $user->EmailSettings()->create($data);
        $response = array();
        $errors = array();
        $response['status'] = 'success';
        echo json_encode($response);
        exit;
    }
    public function changepassword()
    {
        return view('settings.changepassword');
    }
    public function ajax_change_password()
    {
        $cuser = \Auth::user();
        $data       = $_POST;
        $errors = array();
//        echo md5($data['current_password']);exit;

//      833cf08080b70702b42763a60ead0264
//      bcryp  $2y$10$9DR.t/lUy7sDFd6cV817Bu35PpCQwmqJCrNyM3rIgpD4rCRfdbZXe
//    db    $2y$10$MqSbUpO.ENukNmktbCCqfOEzSynKPYI5loONxv/mLDfazcuQGLY5q


//        if( (bcrypt($data['current_password'])!=$cuser->password ) && (md5($data['current_password'])!=$cuser->password) ) {

//        echo bcrypt($data['current_password']);
//        exit;
        if (!Hash::check($data['current_password'], $cuser->password))  {
            $error['field'] = 'current_password';
            $error['error'] = 'Your current password is not correct.';
            $errors[] = $error;
        }
        if(count($errors)==0) {
            if ( empty( $data['new_password'] )) {
                $error['field'] = 'new_password';
                $error['error'] = 'Password can not be empty';
                $errors[] = $error;
            }
        }
        if(count($errors)==0) {
            if($data['new_password'] != $data['new_password2']) {
                $error['field'] = 'new_password';
                $error['error'] = 'Password did not match.';
                $errors[] = $error;
            }
        }
        if(count($errors)==0) {
            if($data['new_password'] == $data['current_password']) {
                $error['field'] = 'new_password';
                $error['error'] = 'New Password cannot be same as old password';
                $errors[] = $error;
            }
        }
        if(count($errors)==0) {

            $cuser->password  = bcrypt($data['new_password']);
            $cuser->update();
            $inserted_client_id = $cuser->id;
            $msg = "Your password has been successfully.";
            if($inserted_client_id) {
                $response['status'] = 'success';
                $response['inserted_id'] = $inserted_client_id;
                $response['msg'] = $msg;
            } else {
                $response['status'] = 'fail';
                $error['field'] = 'general';
                $error['error'] = 'Database Operation Failed.';
                $errors[] = $error;
                $response['errors'] = $errors;
            }
        } else {
            $response['status'] = 'fail';
            $response['errors'] = $errors;
        }
        echo json_encode($response);
        exit;
    }
}
