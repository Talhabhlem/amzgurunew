<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
//    public function index()
//    {
//        return view('home');
//    }
    public function index(Request $request)
    {
        $search = array();
//        if($request->input('limit') || !session()->has('l5cp-user-limit'))
//            session(['l5cp-user-limit' => $request->input('limit', 15)]);
//
//        if(null !== $request->input('q') || !session()->has('l5cp-user-search'))
//            session(['l5cp-user-search' => $request->input('q', '')]);
//
//
//        if(null !== $request->input('sort') || !session()->has('l5cp-user-sort'))
//            session(['l5cp-user-sort' => $request->input('sort', 'name')]);
//
//        if(null !== $request->input('order') || !session()->has('l5cp-user-order'))
//            session(['l5cp-user-order' => $request->input('order', 'desc')]);

        $users = User::where('name', 'LIKE', '%'.$request->input('q', '').'%')
            ->orWhere('id', '=', $request->input('q', ''))
            ->orWhere('email', 'LIKE', '%'.$request->input('q', '').'%')
            ->orderBy(session('l5cp-user-sort'), $request->input('order', 'desc'))
            ->paginate($request->input('limit', 15));

        return view('user.index',compact('request',$request))->withRoles(Role::all())->withUsers($users);
    }
    public function create(Request $request)
    {
        return view('user.create_edit')->withRoles(Role::all());
    }

    public function store(Request $request)
    {
        $user = self::createOrUpdate(null, $request);
        $pass = $request->input('password');
//        event(new UserWasCreated($user,$pass));
        return redirect()->action('\App\Http\Controllers\HomeController@index')->withSuccess('Status Changed Successfully!.');
    }

    public static function createOrUpdate($id = null, $request)
    {
        $model = is_null($id) ? new User : User::findOrFail($id);
        $model->name   = $request->input('name');
        $model->email  = $request->input('email');
        $model->status =  null !== $request->input('status') ?$request->input('status'):'active';
        if(!$id || $id && $request->input('password')) $model->password = bcrypt($request->input('password')) ;
//        $model->detachAllRoles();
//        $model->attachRole($request->input('roles'));
        return $model->save() ? $model : false;
    }

    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('user.create_edit')->withRoles(Role::all())->withUser($user);
    }

    public function update(Request $request, $id)
    {
        $user = self::createOrUpdate($id, $request);
        return redirect()->action('\App\Http\Controllers\HomeController@edit', [$user->id])->withSuccess(trans('saved'));
    }

    public function update_status($user_id,$status='active')
    {
        if(!is_array($user_id))
            $user_id = array($user_id);
        \App\User::whereIn('id',$user_id)
            ->update(['status' => $status]);
        return redirect()->action('\App\Http\Controllers\HomeController@index')->withSuccess('Status Changed Successfully!.');
    }

    public function show(Request $request)
    {
//        if($request->input('limit') || !session()->has('l5cp-user-limit'))
//            session(['l5cp-user-limit' => $request->input('limit', 15)]);
//
//        if(null !== $request->input('q') || !session()->has('l5cp-user-search'))
//            session(['l5cp-user-search' => $request->input('q', '')]);
//
//
//        if(null !== $request->input('sort') || !session()->has('l5cp-user-sort'))
//            session(['l5cp-user-sort' => $request->input('sort', 'name')]);
//
//        if(null !== $request->input('order') || !session()->has('l5cp-user-order'))
//            session(['l5cp-user-order' => $request->input('order', 'desc')]);

        $users = User::where('name', 'LIKE', '%'.$request->input('q', '').'%')
            ->orWhere('id', '=', $request->input('q', ''))
            ->orWhere('email', 'LIKE', '%'.$request->input('q', '').'%')
            ->orderBy(session('l5cp-user-sort'), $request->input('order', 'desc'))
            ->paginate($request->input('limit', 15));

        return view('user.index',compact('request',$request))->withRoles(Role::all())->withUsers($users);
    }
}
