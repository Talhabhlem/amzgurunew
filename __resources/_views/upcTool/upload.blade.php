@extends('layouts.master')
@section('title', 'Analysis- EcommElite')
@section('page_title','Pending Orders')

@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Upc Tool</h1>
    </div>
    @endsection
    @section('content')
            <!-- Panel -->
    <div class="panel">
        <div class="panel-body">

            <!-- Example Contextual Classes -->
            <div class="example-wrap">
                <div id="view_orders_table">
                    @include('upcTool.uploadForm')
                </div>
            </div>

            <!-- End Example Contextual Classes -->
        </div>
    </div>
    <!-- End Panel -->
@endsection
@section('scripts')
@endsection