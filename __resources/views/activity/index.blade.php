@extends('layouts.master')
@section('title', 'Analysis- EcommElite')
@section('page_title','Activity Log')

@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Activity Log</h1>
    </div>
@endsection
@section('content')

                <div id="view_activity_table">
                        @include('activity.view_activity_table')
                </div>
@endsection
@section('scripts')
    <script>
        function actions_activity_form_success(thisForm,resp) {
            jQuery("#view_activity_table").html(resp['view_table_html']);
        }
        function activity_table_search_form_success(thisForm,resp) {
            jQuery("#view_activity_table").html(resp['view_table_html']);
        }
    </script>
@endsection