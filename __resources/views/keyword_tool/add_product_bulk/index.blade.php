@extends('layouts.master')
@section('title', 'Keyword Tracker - EcommElite')
@section('page_title','Keyword Tracker')

@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Keyword Tracker - Add Bulk</h1>
    </div>
@endsection
@section('content')

    @if (Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        @endif

                <!-- Panel -->
        <div class="panel">
            <div class="panel-body">
                {{--<span>Instructions: Enter your ASIN and a list of keywords to track. One keyword per line.</span><br><br>--}}
                @if (isset($results))
                    <div>
                        Results<br>
                        <ul>
                            @foreach($results as $keyword=>$result)
                                <li>{{ $keyword }}: {{ $result }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Example Contextual Classes -->
                <div class="example-wrap">
                    <div id="view_orders_table">
                        @include('keyword_tool.add_product_bulk.addProductBulkForm')
                    </div>
                </div>

                <!-- End Example Contextual Classes -->
            </div>
        </div>
        <!-- End Panel -->
@endsection
@section('scripts')
@endsection