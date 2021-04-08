@extends('layouts.master')
@section('title', 'Analysis- EcommElite')
@section('page_title','Sales Analysiss')
@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Customer Orders</h1>
    </div>
@endsection
@section('site-menu-footer')
    <a href="{{url('settings/amazon')}}" class="fold-show">
        <span class="icon fa fa-key" aria-hidden="true"></span>
    </a>
    <a href="{{url('settings/changepassword')}}" class="fold-show">
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
                <!-- Panel -->
        <div class="panel">
            <div class="panel-body" style="padding-left: 0; padding-right: 0;">
                <!-- Example Contextual Classes -->
                <div class="example-wrap">
                    <div class="col-md-6">
                        <p><strong>Name: </strong>{{$customer->name}}</p>
                        <p><strong>Email: </strong>{{$customer->email}}</p>
                    </div>
                    <div id="view_customers_table">
                        @include('customers._order_table')
                    </div>

                </div>

                <!-- End Example Contextual Classes -->
            </div>
        </div>
        <!-- End Panel -->



@endsection

@section('scripts')
    <script>
        function actions_event_form_success(thisForm,resp) {
            jQuery("#view_customers_table").html(resp['view_table_html']);
        }
        function event_table_search_form_success(thisForm,resp) {
            jQuery("#view_customers_table").html(resp['view_table_html']);
        }
    </script>
@endsection