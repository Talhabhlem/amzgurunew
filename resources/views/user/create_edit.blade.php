@extends('layouts.master')

@section('title')
	@if (isset($user))
		{{ trans('edit') }}  {{ trans_choice('l5cp-user::default.user', 1) }} {{ $user->name }}
	@else
		{{ trans('create') }} {{ trans_choice('l5cp-user::default.user', 1) }}
	@endif
@stop
@section('page-header')
  <div class="clearfix">
    @if (isset($user))
      <h1 class="page-title pull-left">Edit User</h1>
    @else
      <h1 class="page-title pull-left">Create User</h1>
    @endif
  </div>
@endsection


@section('content')
  <div class="panel-body">
  @if (isset($user))
    <form class="" method="post" action="{{ action('\App\Http\Controllers\HomeController@update', ['id' => $user['id']]) }}">
      <input type="hidden" name="_method" value="PUT">
	@else
    <form class="form-ajax" method="post" action="{{ action('\App\Http\Controllers\HomeController@store') }}">
	@endif
      {!! csrf_field() !!}

      <div class="form-group @if ($errors->has('name')) has-error @endif">
        <label for="name">{{ trans('name') }}</label>
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-fw fa-user"></i></div>
          <input type="text" class="form-control" id="name" placeholder="{{ trans('name') }}" name="name" value="{{ isset($user) ? $user->name : Request::old('name') }}" required maxlength="70">
        </div>
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
      </div>

      <div class="form-group @if ($errors->has('email')) has-error @endif">
        <label for="email">{{ trans('email') }}</label>
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></div>
          <input type="email" class="form-control" id="email" placeholder="{{ trans('email') }}" name="email" value="{{ isset($user) ? $user->email : Request::old('email') }}" maxlength="254" required>
        </div>
        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
      </div>

      <div class="form-group @if ($errors->has('password')) has-error @endif">
        <label for="password">{{ trans('password') }}</label>
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-fw fa-lock"></i></div>
          <input type="password" class="form-control" id="password" placeholder="{{ trans('password') }}" name="password"{{ !isset($user) ? ' required' : null }}>
        </div>
        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
      </div>

      <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
        <label for="password_confirmation">{{ trans('confirm') }} {{ trans('password') }}</label>
        <div class="input-group">
          <div class="input-group-addon"><i class="fa fa-fw fa-lock"></i></div>
          <input type="password" class="form-control" id="password_confirmation" placeholder="{{ trans('confirm') }} {{ trans('password') }}" name="password_confirmation"{{ !isset($user) ? ' required' : null }}>
        </div>
        @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
      </div>

      <div class="form-group @if ($errors->has('roles')) has-error @endif">
        <label for="roles">{{ trans('roles') }}</label>
        <select class="form-control" id="roles" name="roles[]" multiple>
          @foreach($roles as $role)
          <option value="{{ $role->id }}"{{ isset($user) && $user->is($role->slug) ? ' selected' : null }}>{{ $role->name }}</option>
          @endforeach
        </select>
        @if ($errors->has('roles')) <p class="help-block">{{ $errors->first('roles') }}</p> @endif
      </div>

      <div class="clearfix"></div>

      <div class="pull-left">
        <button type="submit" class="btn-responsive btn btn-primary">{{ trans('save') }}</button>
        <a href="{{ action('\App\Http\Controllers\HomeController@index') }}" class="btn-responsive btn btn-danger">{{ trans('cancel') }}</a>
      </div>

      @if (isset($user))
      <div class="pull-right">
        <button type="reset" class="btn-responsive btn btn-default">{{ trans('reset') }}</button>
        <a data-row="[{ $user->id }}"  href="{{ action('\App\Http\Controllers\HomeController@destroy', ['id' => $user->id]) }}" class="delete btn-responsive btn btn-warning">{{ trans('delete') }}</a>
      </div>
      @endif

	</form>
  </div>
@stop