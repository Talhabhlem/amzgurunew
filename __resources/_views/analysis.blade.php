@extends('layouts.master')

@section('vendor_styles')
    <!-- ANIMATE.CSS-->
    <link rel="stylesheet" href="../vendor/animate.css/animate.min.css">
    <!-- WHIRL (spinners)-->
    <link rel="stylesheet" href="../vendor/whirl/dist/whirl.css">
@endsection

@section('page_vendor_styles')
    <!-- DATATABLES-->
    <link rel="stylesheet" href="../vendor/datatables-colvis/css/dataTables.colVis.css">
    <link rel="stylesheet" href="../vendor/datatables/media/css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="../vendor/dataTables.fontAwesome/index.css">
@endsection

@section('styles')
    <link rel="stylesheet" href="assets/daterangepicker/daterangepicker.css" type="text/css"/>
@endsection

@section('title', 'Analysis - EcommElite')

@section('page-header')
    <div class="content-heading">
        <!-- START Language list-->
        <div class="pull-right">
            <div class="btn-group">
                <button type="button" data-toggle="dropdown" class="btn btn-default" aria-expanded="false">Overall Sales Analysis</button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right animated fadeInUpShort">
                    <li role="presentation">
                        <a role="menuitem" title="Daily Sales" href="#modalSalesAnalysis" data-target="#modalSalesAnalysis"   data-toggle="modal"  data-sku="ALL_SKU" data-chart-type="daily"  >Daily Analysis</a>
                    </li>
                    <li role="presentation">
                        <a role="menuitem" title="Weekly Sales" href="#modalSalesAnalysis" data-target="#modalSalesAnalysis" data-toggle="modal"   data-sku="ALL_SKU" data-chart-type="weekly">Weekly Analysis</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END Language list    -->
        {{--@section('page_title','Sales Analysiss') --}}
        Sales Analysis
        <small data-localize="dashboard.WELCOME"></small>
    </div>

    <div style="" id="v_analytics_total">
        @include('analytics.total')
    </div>
@endsection

@section('before_page_content')
    <?php
    if($api_setup == 'no' ) {
    ?>
    <div class="page-header clearfix" style="padding-bottom: 0;padding-top: 0;">
        <div role="alert" class="alert alert-social alert-google-plus margin-bottom-0 margin-top-30 alert-dismissible">
            <button aria-label="Close" data-dismiss="alert" class="close" type="button">
                <span aria-hidden="true">×</span>
            </button>
            <i class="icon fa fa-exclamation-triangle" aria-hidden="true"></i>
            <h4>API ERROR</h4>
            <p>
                Data cannot be fetched. Your Amazon MarketPlaceID & Secret Keys are missing.
            </p>
            <p class="margin-top-15">
                <a href="<?php echo url('settings/amazon');?>" class="btn btn-primary btn-inverse btn-outline" type="button"><i class="icon fa fa-key"></i> Click here to Setup Amazon Account</a>
            </p>
        </div>

    </div>
    <?php
    }
    ?>
    <div style="clear: both"></div>
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
                <div class="clearfix" style="padding-left: 15px; padding-right: 15px;">
                    <form role="form" style="margin-right:0px;" class="form-inline pull-right te-ajax-form" id="sales_table_search_form" action="analysis/alternate_get_sales_method" >

                        <div class="form-group" style="margin-right: 5px;">
                            <label for="search_keyword" >Filter Products: </label>
                            <input type="text" placeholder="Search By SKU" id="search_keyword" name="search_keyword" class="form-control" value="<?php echo $search_keyword;?>" />
                            <input type="hidden" name="from_date" id="from_date" value="<?php echo $from_date;?>" />
                            <input type="hidden" name="to_date" id="to_date" value="<?php echo $to_date;?>" />
                            <div id="reportrange" style="margin-left:10px;" class="pull-right daterange-filter">
                                <i class="icon-calendar"></i>
                                <span></span> <b class="caret"></b>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>

                <div id="view_sales_table">
                    @include('analytics.sales_table')
                </div>
            </div>

            <!-- End Example Contextual Classes -->
        </div>
    </div>
    <!-- End Panel -->

@endsection

@section('scripts')

    <!-- Modal -->
    <div class="modal modal-primary fade modal-3d-flip-vertical"  id="modalSalesAnalysis" aria-hidden="true"
         aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
        <div class="modal-dialog"  style="height: auto; max-width:880px; width:98%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Daily Sales Analysis</h4>
                </div>
                <div class="modal-body">
                    <div id="jqplot_chart_area" style="margin-left: 10px; height: 500px; max-width:880px; width:98%;"/>
                </div>
                <div class="modal-footer" style="display: none;">
                    <button type="button" class="btn btn-default margin-0" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <script src="{!! url('assets/js/jqplot/jquery.jqplot.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/js/jqplot/jqplot.dateAxisRenderer.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/js/jqplot/jqplot.canvasTextRenderer.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/js/jqplot/jqplot.canvasAxisTickRenderer.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/js/jqplot/jqplot.canvasOverlay.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/js/jqplot/jqplot.barRenderer.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/js/jqplot/jqplot.cursor.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/js/jqplot/jqplot.highlighter.min.js') !!}" type="text/javascript"></script>
    <script src="{!! url('assets/js/jqplot/jqplot.draw.js') !!}" type="text/javascript"></script>

    <!-- Data Range picker scripts -->
    <script src="assets/daterangepicker/moment.min.js"></script>
    <script src="assets/daterangepicker/moment.timezone.js"></script>
    <script src="assets/daterangepicker/daterangepicker.js"></script>
    <!-- END Data Range picker scripts -->

    <script>
        function actions_sales_form_success(thisForm,resp) {
            jQuery("#view_sales_table").html(resp['view_table_html']);
            jQuery("#v_analytics_total").html(resp['view_stats_html']);
        }

        function sales_table_search_form_success(thisForm,resp) {
            jQuery("#view_sales_table").html(resp['view_table_html']);
            jQuery("#v_analytics_total").html(resp['view_stats_html']);
        }
        jQuery(document).ready(function() {
            jQuery('#modalSalesAnalysis').on('show.bs.modal', function (e) {
                console.log(e);
                var thisClickedButton = jQuery(e.relatedTarget);
                var chartType = thisClickedButton.data('chart-type');
                var sku = thisClickedButton.data('sku');
                jQuery(this).find('.modal-body #jqplot_chart_area').html('').show_loading();
                if(chartType == 'daily') {
                    jQuery(this).find('.modal-title').text('Daily Sales Analysis');
                } else if(chartType == 'weekly') {
                    jQuery(this).find('.modal-title').text('Weekly Sales Analysis');
                } else {
                    jQuery(this).find('.modal-title').text('Invalid Button Clicked');
                    jQuery(this).find('.modal-body').html(bs_alert('danger','Invalid Data Given to the Chart','Graph Error.'));
                }
            });

            jQuery('#modalSalesAnalysis').on('shown.bs.modal', function (e) {
                console.log(e);
                var thisClickedButton = jQuery(e.relatedTarget);

                var chartType = thisClickedButton.data('chart-type');
                var sku = thisClickedButton.data('sku');


                if(chartType == 'daily') {
                    generate_daily_chart(sku);
                } else if(chartType == 'weekly') {
                    generate_weekly_chart(sku);
                }
                jQuery(this).find('.modal-body #jqplot_chart_area').hide_loading();


            });

            addTimezones();

            jQuery('#reportrange').daterangepicker({
                        startDate:moment('<?php echo $from_date;?>'),
                        endDate:moment('<?php echo $to_date;?>'),
                        showDropdowns: true,
                        showWeekNumbers: true,
                        timePicker: false,
                        timePickerIncrement: 1,
                        timePicker12Hour: true,
                        ranges: {
                            'This Week': [getLastMonday(), moment().tz(thisTimeZone)],
                            'Last Week': [getLastMonday().subtract(7,'days'), getLastMonday().subtract(1,'days')],
                            'Last Month': [moment().tz(thisTimeZone).subtract(1, 'month').startOf('month'), moment().tz(thisTimeZone).subtract(1, 'month').endOf('month')],
                            'Last 7 Days': [moment().tz(thisTimeZone).subtract(7,'days'), moment().tz(thisTimeZone)],
                            'Last 15 Days': [moment().tz(thisTimeZone).subtract(15,'days'), moment().tz(thisTimeZone)],
                            'Last 30 Days': [moment().tz(thisTimeZone).subtract(30,'days'), moment().tz(thisTimeZone)],
                            'Month to Date': [moment().tz(thisTimeZone).startOf('month'), moment().tz(thisTimeZone)],
                            'Quarter to Date': [getQuarterDate(), moment().tz(thisTimeZone)],
                            'Year To Date': [moment().tz(thisTimeZone).startOf('year'), moment().tz(thisTimeZone)]
                        },
                        opens: 'left',
                        buttonClasses: ['btn btn-default'],
                        applyClass: 'btn-small btn-primary',
                        cancelClass: 'btn-small',
                        format: 'MM/DD/YYYY',
                        separator: ' to ',
                        locale: {
                            applyLabel: 'Submit',
                            fromLabel: 'From',
                            toLabel: 'To',
                            customRangeLabel: 'Custom Range',
                            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            firstDay: 1
                        }
                    },
                    function(start, end) {
                        //start.tz(thisTimeZone);
                        // end.tz(thisTimeZone);
                        jQuery('#from_date').val(start.format('YYYY-M-D')+" 00:00:00");
                        jQuery('#to_date').val(end.format('YYYY-M-D')+" 23:59:59");
                        jQuery('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                        jQuery('#sales_table_search_form').submit();
                    }
            );
            //Set the initial state of the picker label
            jQuery('#reportrange span').html('<?php echo date('F d, Y',strtotime($from_date));?> - <?php echo date('F d, Y',strtotime($to_date));?>');
            console.log(jQuery('#reportrange span').html());
        });
    </script>
@endsection

@section('site-menu-footer')
    <a href="{{url('settings/amazon')}}" class="fold-show"> <span class="icon fa fa-key" aria-hidden="true"></span> </a>
    <a href="{{url('settings/changepassword')}}" class="fold-show"> <span class="icon fa fa-unlock-alt" aria-hidden="true"></span> </a>
    <a href="{{url('auth/logout')}}" class="fold-show"> <span class="icon wb-power" aria-hidden="true"></span> </a>
@endsection

@section('vendor_scripts')
    <!-- MATCHMEDIA POLYFILL-->
    <script src="../vendor/matchMedia/matchMedia.js"></script>
    <!-- JQUERY EASING-->
    <script src="../vendor/jquery.easing/js/jquery.easing.js"></script>
    <!-- ANIMO-->
    <script src="../vendor/animo.js/animo.js"></script>
    <!-- SLIMSCROLL-->
    <script src="../vendor/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- SCREENFULL-->
    <script src="../vendor/screenfull/dist/screenfull.js"></script>
    <!-- LOCALIZE-->
    <script src="../vendor/jquery-localize-i18n/dist/jquery.localize.js"></script>
    <!-- RTL demo-->
    <script src="assets/js/demo/demo-rtl.js"></script>
@endsection

@section('page_vendor_scripts')
    <!-- DATATABLES-->
    <script src="../vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-colvis/js/dataTables.colVis.js"></script>
    <script src="../vendor/datatables/media/js/dataTables.bootstrap.js"></script>
@endsection
