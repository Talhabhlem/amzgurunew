@extends('layouts.master')

@section('title', 'Dashboard')
@section('app-name', 'My App')

@section('content')


@foreach (Hook::$dash->where('hook', 'dashboard')->sortBy('sort') as $chunk => $c)
 @include($c['template'])
@endforeach



@endsection

@section('after')
    @parent
     This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
@endsection


@section('keywords', '')
@section('author', '')
@section('description', '')@endsection

@section('styles')

@endsection

@section('head')

@endsection

@section('header')

@endsection

@section('before')
    @parent

@endsection

@section('side-menu')
    @parent

@endsection

@section('sidebar')

@endsection

@section('page-wrapper')
    @parent

@endsection

@section('after')
    @parent

@endsection

@section('footer')

@endsection

@section('scripts')

@endsection

@section('navbar-right')
@endsection
