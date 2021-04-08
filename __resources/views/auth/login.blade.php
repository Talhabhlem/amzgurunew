@extends('layouts.master')

@section('title') Login @endsection

@section('content')
    <div class="block-center mt-xl wd-xl">
        <!-- START panel-->
        <div class="panel panel-dark panel-flat">
            <div class="panel-heading text-center">
                <a href="#">
                    <img src="{!! url('assets/images/logo-blue.png') !!}" alt="Image" class="block-center img-rounded">
                </a>
            </div>
            <div class="panel-body">
                <p class="text-center pv">SIGN IN TO CONTINUE.</p>
                <form  action="{{ url('/auth/login') }}" role="form" data-parsley-validate="" novalidate="" class="mb-lg">
                    {!! csrf_field() !!}

                    <div class="form-group has-feedback">
                        <input id="exampleInputEmail1" type="email" placeholder="Enter email" autocomplete="off" required class="form-control">
                        <span class="fa fa-envelope form-control-feedback text-muted"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input id="exampleInputPassword1" type="password" placeholder="Password" required class="form-control">
                        <span class="fa fa-lock form-control-feedback text-muted"></span>
                    </div>
                    <div class="clearfix">
                        <div class="checkbox c-checkbox pull-left mt0">
                            <label>
                                <input type="checkbox" value="" name="remember">
                                <span class="fa fa-check"></span>Remember Me</label>
                        </div>
                        <div class="pull-right"><a href="recover.html" class="text-muted">Forgot your password?</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-block btn-primary mt-lg">Login</button>
                </form>
                <p class="pt-lg text-center">Need to Signup?</p><a href="register.html" class="btn btn-block btn-default">Register Now</a>
            </div>
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- END panel-->
    </div>
    </div>
@endsection