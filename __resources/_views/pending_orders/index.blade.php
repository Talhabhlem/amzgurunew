@extends('layouts.master')
@section('title', 'Analysis- EcommElite')
@section('page_title','Pending Orders')

@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Pending Orders</h1>
    </div>
@endsection
@section('content')
    <!-- Panel -->
    <div class="panel">
        <div class="panel-body">

            <!-- Example Contextual Classes -->
            <div class="example-wrap">
                <div id="view_orders_table">
                    @include('pending_orders.view_pending_orders_table')
                </div>
            </div>

            <!-- End Example Contextual Classes -->
        </div>
    </div>
    <!-- End Panel -->
@endsection
@section('scripts')
    <script>

        function actions_orders_form_success(thisForm,resp) {
            jQuery("#view_orders_table").html(resp['view_table_html']);
        }

        function orders_table_search_form_success(thisForm,resp) {
            jQuery("#view_orders_table").html(resp['view_table_html']);
        }
    </script>
@endsection