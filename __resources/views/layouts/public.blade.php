<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="author" content="@yield('author')">
    <meta name="description" content="@yield('description')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{{ csrf_token() }}}">

    <title>@yield('title', trans('lcp::app.title'))</title>

    <link rel="stylesheet" href="{!! url('/assets/css/bootstrap.min.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/css/bootstrap-extend.min.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/css/site.min.css') !!}">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{!! url('/assets/css/app.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/css/site.min.css') !!}">

    <link rel="apple-touch-icon" href="{!! url('/assets/images/apple-touch-icon.png') !!}">
    <link rel="shortcut icon" href="{!! url('/assets/images/favicon.ico') !!}">

    <link rel="stylesheet" href="{!! url('/assets/vendor/animsition/animsition.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/vendor/asscrollable/asScrollable.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/vendor/switchery/switchery.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/vendor/intro-js/introjs.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/vendor/slidepanel/slidePanel.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/vendor/flag-icon-css/flag-icon.css') !!}">


    <!-- Fonts -->
    <link rel="stylesheet" href="{!! url('/assets/fonts/web-icons/web-icons.min.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/fonts/brand-icons/brand-icons.min.css') !!}">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    <!-- Inline -->

    <link rel="stylesheet" href="{!! url('/assets/fonts/brand-icons/brand-icons.min.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/examples/css/pages/login-v3.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/examples/css/pages/forgot-password.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/css/custom.css') !!}">
    <link rel="stylesheet" href="{!! url('/assets/vendor/jquery/jquery.js') !!}">
    <!--[if lt IE 9]>
    <script src="{!! url('/assets/vendor/html5shiv/html5shiv.min.js') !!}"></script>
    <![endif]-->
    <!--[if lt IE 10]>
    <script src="{!! url('/assets/vendor/media-match/media.match.min.js') !!}"></script>
    <script src="{!! url('/assets/vendor/respond/respond.min.js') !!}"></script>
    <![endif]-->

    <!-- Scripts -->
    <script src="{!! url('/assets/vendor/modernizr/modernizr.js') !!}"></script>
    <script src="{!! url('/assets/vendor/breakpoints/breakpoints.js') !!}"></script>
    <script type="text/javascript">
        base_url = '{!! url() !!}';
    </script>


    @yield('styles')
    @yield('head')
</head>
<body class="@yield('body_classes')">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    @section('header')
    @show
            <!-- Page -->
    <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
        <div class="page-content vertical-align-middle">

        @yield('content')
    <footer class="page-copyright page-copyright-inverse">
        <p>WEBSITE BY <a href="http://techesthete.net">techesthete</a></p>
        <p>© 2015. All RIGHT RESERVED.</p>
        <div class="social">
            <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                <i class="icon bd-twitter" aria-hidden="true"></i>
            </a>
            <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                <i class="icon bd-facebook" aria-hidden="true"></i>
            </a>
            <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                <i class="icon bd-google-plus" aria-hidden="true"></i>
            </a>
        </div>
    </footer>
    @section('footer')
    @show


    </div>
    </div>
    <!-- End Page -->


    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{!! url('/assets/js/app.js') !!}"></script>


    <!-- End Page -->

    <!-- Core  -->
    <script src="{!! url('/assets/vendor/bootstrap/bootstrap.js')!!}"></script>
    <script src="{!! url('/assets/vendor/animsition/jquery.animsition.js')!!}"></script>
    <script src="{!! url('/assets/vendor/asscroll/jquery-asScroll.js')!!}"></script>
    <script src="{!! url('/assets/vendor/mousewheel/jquery.mousewheel.js')!!}"></script>
    <script src="{!! url('/assets/vendor/asscrollable/jquery.asScrollable.all.js')!!}"></script>
    <script src="{!! url('/assets/vendor/ashoverscroll/jquery-asHoverScroll.js')!!}"></script>

    <!-- Plugins -->
    <script src="{!! url('/assets/vendor/switchery/switchery.min.js')!!}"></script>
    <script src="{!! url('/assets/vendor/intro-js/intro.js')!!}"></script>
    <script src="{!! url('/assets/vendor/screenfull/screenfull.js')!!}"></script>
    <script src="{!! url('/assets/vendor/slidepanel/jquery-slidePanel.js')!!}"></script>

    <!-- Plugins For This Page -->
    <script src="{!! url('/assets/vendor/jquery-placeholder/jquery.placeholder.js')!!}"></script>

    <!-- Scripts -->
    <script src="{!! url('/assets/js/core.js')!!}"></script>
    <script src="{!! url('/assets/js/site.js')!!}"></script>

    <script src="{!! url('/assets/js/sections/menu.js')!!}"></script>
    <script src="{!! url('/assets/js/sections/menubar.js')!!}"></script>
    <script src="{!! url('/assets/js/sections/gridmenu.js')!!}"></script>
    <script src="{!! url('/assets/js/sections/sidebar.js')!!}"></script>

    <script src="{!! url('/assets/js/configs/config-colors.js')!!}"></script>
    <script src="{!! url('/assets/js/configs/config-tour.js')!!}"></script>

    <script src="{!! url('/assets/js/components/asscrollable.js')!!}"></script>
    <script src="{!! url('/assets/js/components/animsition.js')!!}"></script>
    <script src="{!! url('/assets/js/components/slidepanel.js')!!}"></script>
    <script src="{!! url('/assets/js/components/switchery.js')!!}"></script>

    <!-- Scripts For This Page -->
    <script src="{!! url('/assets/js/components/jquery-placeholder.js')!!}"></script>
    <script src="{!! url('/assets/js/components/material.js')!!}"></script>

    @yield('scripts')
</body>
</html>