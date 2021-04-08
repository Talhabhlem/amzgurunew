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

    <title>@yield('title')</title>

    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="/vendor/fontawesome/css/font-awesome.min.css">
    <!-- SIMPLE LINE ICONS-->
    <link rel="stylesheet" href="/vendor/simple-line-icons/css/simple-line-icons.css">
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="/assets/css/bootstrap.css" id="bscss">
    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="/assets/css/app.css" id="maincss">

    @yield('styles')
    @yield('head')

</head>
<body class="@yield('body_classes')">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

@section('header')
@show
<!-- Page -->
<div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
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
<script type="text/javascript">
    base_url = '{!! url() !!}';
</script>

<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="/assets/js/app.js"></script>


<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="/vendor/modernizr/modernizr.custom.js"></script>
<!-- JQUERY-->
<script src="/vendor/jquery/dist/jquery.js"></script>
<!-- BOOTSTRAP-->
<script src="/vendor/bootstrap/dist/js/bootstrap.js"></script>
<!-- STORAGE API-->
<script src="/vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
<!-- PARSLEY-->
<script src="/vendor/parsleyjs/dist/parsley.min.js"></script>

@yield('scripts')
</body>
</html>