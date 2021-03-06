@extends('layouts.master')

@section('title', trans_choice('l5cp-user::default.role', 2))
@section('page-header')
  <div class="clearfix">
    <h1 class="page-title pull-left">Roles</h1>
  </div>
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
  <div class="row">
    <div class="col-sm-2">
      <form method="get">
        <div class="form-group">  
          <div class="input-group">  
            <input type="number" class="form-control" name="limit" value="{{ session('l5cp-role-limit') }}">
            <div class="input-group-btn"><button type="submit" class="btn btn-default">Limit</button></div>
          </div>
        </div>
      </form>
    </div>

    <div class="col-sm-6 pull-right">
      <form method="get">
        <div class="form-group">  
          <div class="input-group">  
            <div class="input-group-addon"><i class="fa fa-search"></i></div>
            <input name="q" type="search" class="form-control" placeholder="Search"  value="{{ session('l5cp-role-search') }}">
        </div>
      </form>
    </div>

    </div>
  </div>

  <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th class="col-md-2 col-sm-3 col-xs-5">{{ trans('lcp::default.control') }}</th>
          <th>
            {{ trans('lcp::default.name') }} 
            <a href="?sort=name&order={{ session('l5cp-role-order') == 'desc' ? 'asc' : 'desc' }}" class="disabled">
              <i class="fa fa-sort{{ session('l5cp-role-sort') == 'name' ? (Input::get('order', 'desc') == 'desc' ? '-alpha-asc' : '-alpha-desc') : '' }} pull-right"></i>
            </a>
          </th>
          <th class="hidden-xs">
            {{ trans('lcp::default.slug') }}
            <a href="?sort=slug&order={{ session('l5cp-role-order') == 'desc' ? 'asc' : 'desc' }}" class="disabled">
              <i class="fa fa-sort{{ session('l5cp-role-sort') == 'slug' ? (Input::get('order', 'desc') == 'desc' ? '-alpha-asc' : '-alpha-desc') : '' }} pull-right"></i>
            </a>
          </th>
        </tr>
      </thead>
      <tbody>
      @foreach($roles as $user)
        <tr>
          <td>
            <a href="{{ action('\Askedio\Laravelcp\User\Http\Controllers\RolesController@edit', ['id' => $user->id]) }}" class="btn btn-default"><i class="fa fa-fw fa-pencil"></i></a>
            <a href="{{ action('\Askedio\Laravelcp\User\Http\Controllers\RolesController@destroy', ['id' => $user->id]) }}" class="btn btn-danger delete" data-type="DELETE"><i class="fa fa-fw fa-trash"></i></a>
          </td>
          <td>{{ $user->name }}</td>
          <td class="hidden-xs">{{ $user->slug }}</td>
        </tr>
     @endforeach
  </table>
  {!! $roles->render() !!}

  </div>
@endsection

@section('page-header-right')
    <a href="{{ action('\Askedio\Laravelcp\User\Http\Controllers\RolesController@create') }}" class=" pull-right btn btn-primary btn-xl">Create</a>
@endsection