<?php

namespace App\Http\Controllers;

use Askedio\Laravelcp\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Events\UserWasCreated;
use App\Helpers\TeHelper;
use App\Helpers\parseCSV;

class AdminController extends Controller
{

    public function getFileContents($user_id)
    {

        if (!isset($_FILES['files']) || !$_FILES['files']['tmp_name'][0]) {
            return ['Error' => "You didn't choose any file!"];
        }

        if (!in_array($_FILES['files']['type'][0], array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'))) {
            return ['Error' => '"' . $_FILES['files']['name'][0] . '" is not a CSV file, please try again.'];
        }

        $path_url = null;
        $upload_dir_path    = 'uploads'; // upload path

        $fileName=explode('.',$_FILES['files']['name'][0]);

        $loadFile= $upload_dir_path . '/csv_' . $user_id . '_' . time() . '.' . $fileName[count($fileName)-1];

        if(!move_uploaded_file($_FILES['files']['tmp_name'][0],$loadFile)) {
            return ['Error' => 'Can not load file "' . $_FILES['files']['name'][0] . '"!'];
        }

        $contents = file_get_contents($loadFile);
        $str = $contents;
        /*
          Remove Line break issue
         */
        if( strpos( $str , '""') !== false  || strpos( $str , '�?“') !== false || strpos( $str , '�?�?') !== false || strpos( $str , '““') !== false ){
            echo("doing the strreplace stuff");
            $str = str_replace( '""', "\"\n\"" , $str);
            $str = str_replace( '�?“', "�?\n“" , $str);
            $str = str_replace( '�?�?', "�?\n�?" , $str);
            $str = str_replace( '““', "“\n“" , $str);
            file_put_contents($loadFile, $str);
        }
        //end of break issue resolution


        if ( !empty( $loadFile )) {
            unlink($loadFile);
        }

        return [
            'contents' => $str,
            'loadFile' => $loadFile
        ];
    }

    public function ajax_upload_csv_users()
    {
        $cuser = \Auth::user();
        $user_id = $cuser->id;
        $error = '';
        $status = array();

        $contents = $this->getFileContents($user_id);

        if (isset($contents['Error'])) {
            echo json_encode([
                'error' => $contents['Error'],
                'status' => 'fail'
            ]);
            exit;
        }

        $str = $contents['contents'];
        $loadFile = $contents['loadFile'];

        $csv = new parseCSV();
        // Parse using automatic delimiter detection...
        $csv->auto( $str ,$parse = true, $search_depth = null, $preferred = null, $enclosure = '"');

        foreach ($csv->data as $key => $row){

            foreach ($row as $key1 => $value) {
                //store key in temporaray
                $tempkey = $key1 ;

                $key1 = strtolower( $key1 );
                $key1 = trim( $key1 );
                $key1 = str_replace( array('�?','“','"'), '', $key1 );

                $value = trim( $value );
                $value = str_replace( array('�?','“','"'), '', $value );
                $row[ $key1 ] = $value;

            }
            $users[ $key ] = $row;
        }

        if( isset($users)){

            $emails=array();
            $passwords=array();
            $firstnames=array();
            $lastnames=array();
            $names=array();

            foreach($users as $key=>$user){
                $_email = trim($user['email']);
                $_name = trim(ucwords($user['name']));
                if(empty($_email)) {
                    continue;
                }
                $email_parts = explode('@',$_email);
                if(empty($_name)) {
                    $first_name = $email_parts[0];
                    $last_name = '';
                } else {
                    $namesTmp = explode(" ", $_name);
                    $last_name  = array();
                    $first_name = '';

                    // Loops through, pulls out the first and last
                    // name parts, separated by spaces.
                    for ($i = 0; $i < count($namesTmp); $i++) {
                        if (!empty($namesTmp[$i]) && $first_name == '') {
                            $first_name = trim($namesTmp[$i]);
                        } else {
                            $last_name[] = trim($namesTmp[$i]);
                        }
                    }

                    $last_name = implode(' ',$last_name);
                }
                $emails[]       = $_email;
                $passwords[]    = $email_parts[0];
                $firstnames[]   = $first_name;
                $lastnames[]    = $last_name;
                $names[]    = $_name;
            }

            foreach($emails as $i => $email) {
                $eml = array();
                $eml['name'] 	= $names[$i];
                $eml['first_name'] 	= $firstnames[$i];
                $eml['last_name'] 	= $lastnames[$i];
                $eml['email'] 		= $email;
//                            $user_pass = str_random(6);
                $user_pass = $passwords[$i];
//                            $eml['password'] 	= $user_pass;
                $eml['password'] 	= bcrypt($passwords[$i]);
                $eml['status'] 		= 'active';
                if(sizeof(User::where('email','=',$email)->get()) > 0)
                {
                    $status[$email] = "ALREADY_EXISTS";
                }
                else {

                    $user = new User;
                    $user->name    = ($eml['name'])?$eml['name']:$eml['first_name'] . ' '. $eml['last_name'];
                    $user->email   = $eml['email'];
                    $user->status  =  'active';
                    $user->password  =  bcrypt($user_pass);
                    $user->detachAllRoles();
                    $user->save();
                    event(new UserWasCreated($user,$user_pass));
                    $inserted_id = $user->id;
                    if($inserted_id) {
                        $user = $eml;
                        $pass = $passwords[$i];
                        $alert = array();
                        $alert['to']        = $email;
                        $alert['subject']   = "EcommElite Account Creation Complete";
                        $alert['message']   = view('partials.email._view_import_alert', compact('user','pass'));
//                                    $sent_email         = te_smtp_email($alert);
                        $status[$email] = "SUCCESS";
                    } else {
                        $status[$email] = "ERROR";
                    }
                }
            }
        } else $error='There are no user(s) in file "'.$_FILES['files']['name'][0].'"!';

        $response['error'] = $error;
        $response['status'] = 'fail';
        if ( empty( $error )) {
            $response['status'] = 'success';
            $response['import_status'] = $status;
            $users = User::
            where('name', 'LIKE', '%'.session('l5cp-user-search').'%')
                ->orWhere('email', 'LIKE', '%'.session('l5cp-user-search').'%')
                ->orderBy(session('l5cp-user-sort'), session('l5cp-user-order'))
                ->paginate(session('l5cp-user-limit'));
            $response['view_table_html']=  (String) view('partials.users._list' , compact('users'));
        }
        echo json_encode( $response );
        exit;
    }

    public function ajax_edit_user()
    {
        $action     = $_POST['user_action'];
        $userids    = $_POST['selected_users'];
        $users = array();
        foreach($userids as $id) {
            $user = User::where('id','=',$id)->get();
            if(sizeof($user) > 0)
            {
                $users[$id] = $user[0];
            }
        }

        if($action == 'disable') {
            foreach($users as $uid => $usr) {
                $usr->status = 'inactive';
                $usr->save();
            }
            $response['status'] = 'success';
            $response['msg'] = "User(s) disabled successfully.";

        } else if($action == 'enable') {
            foreach($users as $uid => $usr) {
                $usr->status = 'active';
                $usr->save();
            }
            $response['status'] = 'success';
            $response['msg'] = "User(s) enabled successfully.";

        } else if($action == 'delete') {
            foreach($users as $uid => $usr) {
                $usr->delete();
            }
            $response['status'] = 'success';
            $response['msg'] = "User(s) deleted successfully.";
        }

        $users = User::where('name', 'LIKE', '%'.session('l5cp-user-search').'%')
            ->orWhere('email', 'LIKE', '%'.session('l5cp-user-search').'%')
            ->orderBy(session('l5cp-user-sort'), session('l5cp-user-order'))
            ->paginate(session('l5cp-user-limit'));

        $response['view_table_html']=  (String) view('partials.users._list' , compact('users'));

        echo json_encode($response);

        exit;
    }
}
