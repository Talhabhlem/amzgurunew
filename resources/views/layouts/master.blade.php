<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="author" content="@yield('author')">
    <meta name="description" content="@yield('description')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="_token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="{{url('vendor/fontawesome/css/font-awesome.min.css')}}">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="{{url('vendor/simple-line-icons/css/simple-line-icons.css')}}">

    @section('vendor_styles') @show

    <!-- =============== PAGE VENDOR STYLES ===============-->
    @section('page_vendor_styles') @show

    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="{{url('assets/css/bootstrap.css')}}" id="bscss">
    @section('bootstrap') @show

    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="{{url('assets/css/app.css')}}" id="maincss">
    @section('app_styles') @show

    @yield('styles')

    <script>
        // console.log(url());
{{--        base_url = '{!! url() !!}/';--}}
        // var thisTimeZone = 'America/Los_Angeles';
    </script>

    @yield('head')
</head>

<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

@section('header')
@show

@section('wrapper')
    <div class="wrapper">
        @section('before')
            {{--@include('layouts.partials.before')--}}
        @show

        @section('navbar-static-top')
            <!-- top navbar-->
            <header class="topnavbar-wrapper">
                <!-- START Top Navbar-->
                <nav role="navigation" class="navbar topnavbar">
                    <!-- START navbar header-->
                    <div class="navbar-header">
                        <a href="#/" class="navbar-brand">
                            <div class="brand-logo"> <img src="{{url('assets/images/logo-blue.png')}}" width="60" alt="App Logo" class="img-responsive"> </div>
                            <div class="brand-logo-collapsed"> <img src="{{url('assets/images/logo-blue.png')}}" alt="App Logo" class="img-responsive"> </div>
                        </a>
                    </div>
                    <!-- END navbar header-->
                    <!-- START Nav wrapper-->
                    <div class="nav-wrapper">
                        <!-- START Left navbar-->
                        <ul class="nav navbar-nav">
                            <li>
                                <!-- Button to show/hide the sidebar on mobile. Visible on mobile only.-->
                                <a href="#" data-toggle-state="aside-toggled" data-no-persist="true" class="visible-xs sidebar-toggle">
                                    <em class="fa fa-navicon"></em>
                                </a>
                            </li>
                            <!-- START User avatar toggle-->
                            <li>
                                <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
                                <a id="user-block-toggle" href="#user-block" data-toggle="collapse">
                                    <em class="icon-user"></em>
                                </a>
                            </li>
                            <!-- END User avatar toggle-->
                            <!-- START lock screen-->
                            <li>
                                <a href="{{url('/settings/changepassword')}}" title="Password settings">
                                    <em class="icon-lock"></em>
                                </a>
                            </li>
                            <!-- END lock screen-->
                            <!-- START logout item-->
                            <li>
                                <a href="{{url('/auth/logout')}}" title="Logout">
                                    <em class="icon-power"></em>
                                </a>
                            </li>
                            <!-- END logout item-->
                        </ul>
                        <!-- END Left navbar-->
                        <!-- START Right Navbar-->
                        <ul class="nav navbar-nav navbar-right">
                            <!-- START Offsidebar button-->
                            <li>

                            </li>
                            <!-- END Offsidebar menu-->
                        </ul>
                        <!-- END Right Navbar-->
                    </div>
                    <!-- END Nav wrapper-->
                    <!-- START Search form-->
                    <form role="search" action="search.html" class="navbar-form">
                        <div class="form-group has-feedback">
                            <input type="text" placeholder="Type and hit enter ..." class="form-control">
                            <div data-search-dismiss="" class="fa fa-times form-control-feedback"></div>
                        </div>
                        <button type="submit" class="hidden btn btn-default">Submit</button>
                    </form>
                    <!-- END Search form-->
                </nav>
                    <!-- END Top Navbar-->
            </header>
        @show

        @include('layouts.partials.side-menu')

        <section>
            @section('page-wrapper-wrap')
                <div class="content-wrapper">
                    {{--@section('before_page_content') @show--}}

                    @section('page-header')
                        <div class="content-heading">
                            <!-- START Language list-->
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" data-toggle="dropdown" class="btn btn-default" aria-expanded="false">English</button>
                                    <ul role="menu" class="dropdown-menu dropdown-menu-right animated fadeInUpShort">
                                        <li><a href="#" data-set-lang="en">English</a></li>
                                        <li><a href="#" data-set-lang="es">Spanish</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- END Language list    -->
                            @yield('page_title')
                            <small data-localize="dashboard.WELCOME"></small>
                        </div>
                    @show

                    @section('page-wrapper')
                        @yield('content')
                    @show
                </div>
            @show
        </section>

        @section('after')
            @include('layouts.partials.after')
        @show

        @section('footer') @show

        <!-- Footer -->
        <footer>
            <span> © {{ date( "Y", time() ) }} TechEsthete </span>
            <div class="site-footer-right" style="display: none;"> Developed by <a href="http://techesthete.net">TechEsthete</a> </div>
        </footer>
        <!-- /Footer -->
    </div>
@show
<script type="text/javascript">
    var base_url = window.location.origin;
    console.log(base_url);
</script>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="{{url('vendor/modernizr/modernizr.custom.js')}}"></script>
<!-- JQUERY-->
<script src="{{url('vendor/jquery/dist/jquery.js')}}"></script>
<!-- BOOTSTRAP-->
<script src="{{url('vendor/bootstrap/dist/js/bootstrap.js')}}"></script>
<!-- STORAGE API-->
<script src="{{url('vendor/jQuery-Storage-API/jquery.storageapi.js')}}"></script>
<!-- PARSLEY-->
<script src="{{url('vendor/parsleyjs/dist/parsley.min.js')}}"></script>

<!-- this page specific scripts -->
<script src="{!!url('assets/daterangepicker/moment.min.js')!!}"></script>
<script src="{!!url('assets/daterangepicker/moment.timezone.js')!!}"></script>
<script src="{!!url('assets/daterangepicker/daterangepicker.js')!!}"></script>
<script src="{!!url('assets/js/form-validator.js')!!}"></script>

@section('vendor_scripts') @show

<!-- =============== PAGE VENDOR SCRIPTS ===============-->
@section('page_vendor_scripts') @show

@section('app_scripts') @show

@section('scripts') @show

<!-- =============== APP SCRIPTS ===============-->
<script src="{{url('assets/js/app.js')}}"></script>

<script>

//    (function() {
//        // Reset Current
//        $('#inlineDatepicker').datepicker( { format: 'dd-mm-yyyy' } );
//
//    })();

//    (function(document, window, $) {
//        'use strict';
//
//        var Site = window.Site;
//        $(document).ready(function() {
//            Site.run();
//        });
//    })(document, window, jQuery);
</script>
<script type="text/javascript">

    $(document).ready(function () {
        jQuery('.clock-wrapper').show(function(){
            jQuery('#clock').makeClock();
        });
    });
    addTimezones();
    function getLastMonday() {
        var result = moment().tz(thisTimeZone);
        while (result.day() !== 1) {
            result.subtract(1, 'day');
        }
        return result;
    }

    function getQuarterDate() {
        var result = moment().tz(thisTimeZone);
        result.subtract(1, 'month');
        while ((result.month() + 1) % 3 != 0) {
            result.subtract(1, 'month');
        }
        return result.add(1, 'month').startOf('month');
    }

    function getHalfDate() {
        var result = moment().tz(thisTimeZone);
        result.subtract(1, 'month');
        while ((result.month() + 1) % 6 != 0) {
            result.subtract(1, 'month');
        }
        return result.add(1, 'month').startOf('month');
    }
</script>
</body>

</html>