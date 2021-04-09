@extends('layouts.public')

@section('body_classes')
    page-forgot-password layout-full
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="brand">
                <img class="brand-img" src="{!! url('assets/images/logo-blue.png') !!}" alt="...">
            </div>
            <h2 class="brand-text font-size-18">Reset Password</h2>
            <p>Input your registered email to reset your password</p>
            <form method="post" action="{{ url('/password/email') }}">
                {!! csrf_field() !!}

                <div class="form-group">
                    <label for="exampleInputEmail1" class="sr-only">Email Aaddress</label>
                    <input type="email" class="form-control" id="exampleInputEmail1"
                           placeholder="Email Address" name="email" value="{{ old('email') }}"
                           required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        Send Password Reset Link
                    </button>
                </div>
            </form>
            <p><a href="{!!url('/auth/login')!!}">Login to your Account</a></p>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    Error<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection
