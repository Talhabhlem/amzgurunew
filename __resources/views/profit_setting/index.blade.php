@extends('layouts.master')
@section('title', 'Analysis- EcommElite')
@section('page_title','Profit Settings')

@section('page-header')
    <?php
    if($api_setup == 'no' )
    {
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
                <a href="<?php echo base_url('settings/amazon');?>" class="btn btn-primary btn-inverse btn-outline" type="button"><i class="icon fa fa-key"></i> Click here to Setup Amazon Account</a>
            </p>
        </div>

    </div>
    <?php
    }
    ?>
    <div style="clear: both"></div>
    <div class="clearfix">
        <h1 class="page-title pull-left">Profit Settings</h1>
    </div>
@endsection

@section('content')

                <div class="clearfix" style="padding-left: 15px; padding-right: 15px;">
                    <h2 class="pull-left"></h2>

                    <form role="form" style="margin-right:0px;margin-top:15px;" class="form-inline pull-right te-ajax-form" id="profit_table_search_form" action="profit/ajax_get_profit_setting" >

                        <div class="form-group" style="margin-right: 5px;">
                            <label for="search_keyword" >SKU Filter: </label>
                            <input type="text" placeholder="Enter SKU" id="search_keyword" name="search_keyword" class="form-control" value="<?php echo $search_keyword;?>" />
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
                <div class="" id="view_profit_table">

                        @include('profit_setting.view_profit_setting_table')
                </div>
@endsection
@section('scripts')

    <script>

        function actions_profit_form_success(thisForm,resp) {
            jQuery("#view_profit_table").html(resp['view_table_html']);
        }
        function profit_table_search_form_success(thisForm,resp) {
            jQuery("#view_profit_table").html(resp['view_table_html']);
        }
        jQuery(document).ready(function() {

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
                        jQuery('#profit_table_search_form').submit();
                    }
            );

            //Set the initial state of the picker label
            jQuery('#reportrange span').html('<?php echo date('F d, Y',strtotime($from_date));?> - <?php echo date('F d, Y',strtotime($to_date));?>');
            console.log(jQuery('#reportrange span').html());
        });
    </script>
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



