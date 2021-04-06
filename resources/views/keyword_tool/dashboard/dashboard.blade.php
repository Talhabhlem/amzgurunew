@extends('layouts.master')
@section('title', 'Keyword Tracker - EcommElite')
@section('page_title','Keyword Tracker')

@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Keyword Tracker - Dashboard</h1>
    </div>
@endsection
@section('content')

    @if (Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        @endif

                <!-- Panel -->
        <div class="panel">
            <div class="panel-body">

                <!-- Example Contextual Classes -->
                <div class="example-wrap">
                    <div id="view_orders_table">
                        @include('keyword_tool.dashboard.dashboardData')
                    </div>
                </div>

                <div class="example-wrap">
                    <div id="view_orders_table">
                        @include('keyword_tool.dashboard.trackedKeywords')
                    </div>
                </div>

                <!-- End Example Contextual Classes -->
            </div>
        </div>
        <!-- End Panel -->
@endsection
@section('scripts')
@endsection