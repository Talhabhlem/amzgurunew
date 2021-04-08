@extends('layouts.master')
@section('title', 'Settings')
@section('page_title','Setup Amazon Account')
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

@section('page-header')
    <div class="clearfix">
            <h1 class="page-title pull-left">Api Settings</h1>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        function Api_setting_form_success(thisForm, resp) {
            //thisForm.reset_form();
            jQuery(thisForm).find('.bs-alert').remove();
            thisForm.prepend(bs_alert('success','API settings saved successfully.'));
        }
    </script>
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
        <div class="col-md-6">
        <form role="form" class="te-ajax-form" action="settings/ajax_save_amazon_settings" id="Api_setting_form">
            <div class="form-group form-material">
                <label for="merchant_id">Merchant ID</label>
                <input class="form-control required" autocomplete="off" type="text"  placeholder="Merchant ID" id="merchant_id" name="merchant_id" value="{{$amazon->merchant_id}}" />
            </div>
            <div class="form-group form-material">
                <label for="marketplace_id">Market Place ID</label>
                <input class="form-control required" autocomplete="off" type="text"  placeholder="Market Place ID" id="marketplace_id" name="marketplace_id" value="{{$amazon->marketplace_id}}" />
            </div>
            <div class="form-group form-material">
                <label for="access_key">Access Key</label>
                <input class="form-control required" autocomplete="off" type="text"  placeholder="Access Key" id="access_key" name="access_key" value="{{$amazon->access_key}}" />
            </div>
            <div class="form-group form-material">
                <label for="secret_key">Secret Key</label>
                <input class="form-control required" autocomplete="off" type="text"  placeholder="Secret Key" id="secret_key" name="secret_key" value="{{$amazon->secret_key}}" />
            </div>

            <div class="form-group form-material">
                <?php
                $options = array();
                $options ['Select Your Region']= '';
                $options['Canada'] = 'https://mws.amazonservices.ca/';
                $options['USA'] = 'https://mws.amazonservices.com/';
                $options['Mexico'] = 'https://mws.amazonservices.com.mx/';
                $options['Germany'] = 'https://mws-eu.amazonservices.com/';
                $options['Spain'] = 'https://mws-eu.amazonservices.com/';
                $options['France'] = 'https://mws-eu.amazonservices.com/';
                $options['India'] = 'https://mws.amazonservices.in/';
                $options['Italy'] = 'https://mws-eu.amazonservices.com/';
                $options['United Kingdom'] = 'https://mws-eu.amazonservices.com/';
                $options['Japan'] = 'https://mws.amazonservices.jp/';
                $options['China'] = 'https://mws.amazonservices.com.cn/';  
                ?>
                <label for="region">Region</label>
                @if(!empty($amazon->region))
                    @foreach($options as $name=>$value)
                        @if($value==$amazon->region)
                            <input type="text" readonly="readonly" value="{{$name}}" style="width: 100%;border: none;" />
                        @endif
                    @endforeach
                @else
                <select class="form-control required" autocomplete="off" type="text"  placeholder="Region" id="region" name="region" >
                    @foreach($options as $name=>$value)
                        <option value='{{$value}}' @if($value==$amazon->region) {{ 'selected=selected' }}  @endif >{{$name}}</option>
                    @endforeach
                </select>
                @endif
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Save Settings</button>
        </form>
    </div>
    </div>
@endsection