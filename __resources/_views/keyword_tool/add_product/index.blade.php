@extends('layouts.master')
@section('title', 'Analysis- EcommElite')
@section('page_title','Pending Orders')

@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Keyword Tracker - Add Product</h1>
    </div>
    @endsection
    @section('content')

        @if (Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif

            <!-- Panel -->
    <div class="panel">
        <div class="panel-body">
            <span><a href="addProductBulk">Power user? Try the new bulk keyword adder!</a></span><br><br>
            <!-- Example Contextual Classes -->
            <div class="example-wrap">
                <div id="view_orders_table">
                    @include('keyword_tool.add_product.addProductForm')
                </div>
            </div>

            <!-- End Example Contextual Classes -->
        </div>
    </div>
    <!-- End Panel -->
@endsection
@section('scripts')
@endsection