@extends('layouts.master')
@section('title', 'Analysis- EcommElite')
@section('page_title','Sales Analysiss')
@section('app_styles')
    <link rel="stylesheet" href="{{url('assets/select2-4.0.0/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{url('vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
@endsection
@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Create Event</h1>
        <div class="page-header-actions pull-right">
            <div style="margin:15px 0px">
                <a class="btn btn-primary pull-right" href="{!! url('events/create')!!}">
                    <span class="fa fa-plus"></span>&nbsp; Add New Event
                </a>
            </div>

        </div>
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
                <div class="panel-body">
                <!-- Panel -->
                    <form autocomplete="off" role="form"  class="te-ajax-form" action="events/ajax_save_event" id="Events_form">
                        <div class="form-group form-material">
                            <label class="control-label" for="sku">Select Product</label>
                            <select class="form-control" id="sku" name="sku" required="required">
                                <option>Select A Product</option>
                                @if($selected_product)
                                <option value="{{$selected_product->sku}}"  @if ($selected_product->sku == @$event['sku']) {{'selected'}} @endif >{{$selected_product->title}}</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group form-material">
                            <label class="control-label" for="description">Event Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter event description here."><?php echo @$event['description'] ?></textarea>
                        </div>

                        <div class="form-group form-material">
                            <label class="control-label" for="inputWarning">Event Date</label>
                            <input style="width: 200px;" type="text" class="form-control" id="date_picker" name="event_date" placeholder="Event Date" value="<?php echo isset($event['event_date']) ? date('Y-m-d', strtotime($event['event_date'])) : date('Y-m-d', time()) ?>" />
                        </div>

                        <div class="form-group">
                            <div>
                                <button id="save_event" type="submit" class="btn-primary btn">Save Event</button>
                                <a href="<?php echo url('events');?>" class="btn-default btn">Cancel</a>
                            </div>
                        </div>

                        <input type="hidden" name="event_id" value="<?php echo @$event['id'];?>">

                    </form>
                </div>
@endsection

@section('app_scripts')
    <script src="{{url('assets/select2-4.0.0/js/select2.min.js')}}"></script>
    <script src="{{url('assets/js/bootstrap-datepicker.js')}}"></script>
@endsection

@section('scripts')
    <script type="text/javascript">

        jQuery(document).ready(function () {


            $('#date_picker').datepicker( { format: 'yyyy-mm-dd' } );

            jQuery('#Events_form').on('submit',function(){
                if(jQuery('#sku').val()=='Select A Product')
                {
                    jQuery(this).prepend(bs_alert('info', 'Please select a product.'));
                    return false;
                }
                else
                    return true;
            });
            jQuery("#sku").select2({
                ajax: {
                    headers: { 'X-CSRF-TOKEN' : jQuery('meta[name="_token"]').attr('content') },
                    url: base_url+"/events/ajax_product_name_search",
                    dataType: 'json',
                    type: "POST",
                    delay: 250,
                    data: function (params) {
                        console.log("data is called");
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, page) {
                        console.log("processResults is called");
                        console.log(data);
                        // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data
                        return {
                            results: data.items
                        };
                    },
                    results: function (data, page) {
                        console.log("results is called");
                        // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to alter remote JSON data
                        return {results: data.items};
                    },
                    cache: true
                },

                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                formatResult: FormatResult
//                templateResult: formatRepo, // omitted for brevity, see the source of this page
//                templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
            });
        });

        function FormatResult(item) {
            var markup = "";
            if (item.id !== undefined) {
                markup += "<option value='" + item.id + "'>" + item.text + "</option>";
            }
            return markup;
        }
        function Events_form_success(thisForm, resp) {
            if ($("#event_id").val() == "")
                thisForm.reset_form();
            jQuery(thisForm).find('.bs-alert').remove();
            thisForm.prepend(bs_alert('success', 'Event saved successfully.'));
        }

    </script>
@endsection