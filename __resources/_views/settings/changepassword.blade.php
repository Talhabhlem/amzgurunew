@extends('layouts.master')
@section('title', trans_choice('l5cp-user::default.user', 2))
@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Email Settings</h1>
    </div>
@endsection


@section('site-menu-footer')
    <a href="{{url('settings/amazon')}}" class="fold-show">
        <span class="icon fa fa-key" aria-hidden="true"></span>
    </a>
    <a href="{{url('settings/changepassword')}}" class="fold-show active">
        <span class="icon fa fa-unlock-alt" aria-hidden="true"></span>
    </a>
    <a href="{{url('auth/logout')}}" class="fold-show">
        <span class="icon wb-power" aria-hidden="true"></span>
    </a>
@endsection
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            {!! trans('lcp::auth.error') !!}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="panel-body">
        <form role="form" class="te-ajax-form" action="settings/ajax_change_password" id="change_password_form">
            <div class="form-group form-material">
                <label for="exampleInputEmail1">Current Password</label>
                <input class="form-control" autocomplete="off" value="" type="password"  placeholder="Current Password" id="current_password" name="current_password" />
            </div>
            <div class="form-group form-material">
                <label for="exampleInputPassword1">Password</label>
                <input class="form-control" value=""  autocomplete="off" type="password"  placeholder="Password" id="new_password" name="new_password" />
            </div>

            <div class="form-group form-material">
                <label for="exampleInputPassword1">Re-Password</label>
                <input class="form-control"  autocomplete="off" type="password" placeholder="Re Password" id="new_password2" name="new_password2" value="" />
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Change Password</button>
        </form>

    </div>

@endsection

@section('scripts')
    <script type="text/javascript">

        function change_password_form_success(thisForm, resp) {
            //thisForm.reset_form();
            if(resp['status']=='fail')
            {
                var msg = '';
                $.each(resp['errors'], function( index, value ) {
                    msg+= value.error;
                });
                thisForm.prepend(bs_alert('danger',msg));
            }
            else
                thisForm.prepend(bs_alert(resp['status'],'Password changed successfully.'));
        }
        function change_password_form_fail(thisForm, resp) {
            console.log( resp );
            thisForm.prepend(bs_alert('error','Err.'));
        }



        // select2
        if (jQuery.fn.select2) {
            jQuery("#select2-option").select2();
        }
    </script>
@endsection