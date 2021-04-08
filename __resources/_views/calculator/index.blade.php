@extends('layouts.master')
@section('title', 'Calculator- EcommElite')
@section('page_title','Calculator')
@section('page-header')
    <div class="clearfix">
        <h1 class="page-title pull-left">Calculator</h1>
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
                    <div id="view_customers_table">
                        <div class="clearfix margin-bottom-15">
                            <form role="form" style="margin-left:15px;" class="form-inline" id="event_table_search_form" action="/calculator" method="">
                            {{-- {!! Form::open(array('style' => 'margin-left:15px','action' => 'CalculatorController@index', 'method' => 'POST', 'id' => 'lookup', 'class' => 'form-inline')) !!} --}}
                                <div class="form-group">
                                    <label for="search_keyword" >Enter Product Id: </label>
                                    <input type="text" placeholder="Product Id" id="search_keyword" name="product_id" class="form-control" value="{{ @$_REQUEST['product_id']}}" />
                                </div>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                            </form>
                           {{-- {!! Form::close() !!} --}}
                        </div>
                    </div>
                </div>
                @if(isset($product_detail))
                    @if(isset($product_detail['data']['0']['imageUrl']))
<!--                    <div class="row">
                            @if(isset($revenue))
                            <div class="col-md-4 col-sm-4">
                                <div class="counter counter-lg counter-inverse bg-blue-600 vertical-align height-100" style="margin-bottom: 10px">
                                    <div class="vertical-align-middle">
                                        <div class="counter-icon margin-bottom-5">{{$revenue}} </div>
                                        <span class="counter-number">Revenue</span>
                                    </div>
                                </div>
                             </div>   
                            @endif
                            @if(isset($cost))
                            <div class="col-md-4 col-sm-4">
                            <div class="counter counter-lg counter-inverse bg-red-600 vertical-align height-100" style="border-left:1px solid #e4eaec;margin-bottom: 10px">
                                <div class="vertical-align-middle">
                                    <div class="counter-icon margin-bottom-5">{{$cost}}</div>
                                    <span class="counter-number">Total Cost</span>
                                </div>
                            </div>
                            </div>
                            @endif
                            @if(isset($margin_impact))
                                                        <div class="col-md-4 col-sm-4">
                            <div class="counter counter-lg  counter-inverse bg-green-600 vertical-align height-100" style="border-left:1px solid #e4eaec;margin-bottom: 10px">
                                <div class="vertical-align-middle">
                                    <div class="counter-icon margin-bottom-5">{{$margin_impact}}</div>
                                    <span class="counter-number">Margin Impact</span>
                                </div>
                            </div>
                            </div>
                            @endif
                    </div>-->


                    <div class="col-md-12 col-sm-12">
                        <div  class="col-md-2 col-sm-2">
                            <img src="{{$product_detail['data']['0']['imageUrl']}}" alt="" />
                        </div>
                        <div class="col-md-10 col-sm-10">
                            <p><strong>{{$product_detail['data']['0']['title']}}</strong></p>
                            <p style="margin: 0">ASIN: {{$product_detail['data']['0']['asin']}}</p>
                            <p style="margin: 0">Product Dimensions: {{$product_detail['data']['0']['length']}} X {{$product_detail['data']['0']['width']}} X {{$product_detail['data']['0']['height']}}</p>
                            <p style="margin: 0">Unit Weight: {{$product_detail['data']['0']['weight']}} {{$product_detail['data']['0']['weightUnit']}}</p>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12" style="margin-top: 20px">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <form action="{{ url('calculator?product_id='.$product_detail['data']['0']['asin']) }}" method="POST">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <table class="table" style="table-layout:fixed;">
                                    <tr>
                                        <td style='width: 50%;font-weight:bold'>Revenue</td>
                                        <td style='width: 25%;font-weight:bold'>Your Fulfillment</td>
                                        <td style='width: 25%;font-weight:bold'>Amazon Fulfillment</td>
                                    </tr>
                                    <tr>
                                        <td>Item Price</td>
                                        <td><input type='text' class='a-input-text revenue pricing form-control' name="pricing" value="{{@$_POST['pricing']}}" tabindex="1" /></td>
                                        <td><input type='text' class='a-input-text revenue pricing form-control' name="revenue" value="{{@$_POST['revenue']}}" tabindex="13"  /></td>
                                    </tr>
                                    <tr>
                                        <td>Shipping</td>
                                        <td><input type='text' class='a-input-text revenue shipping form-control' name="shipping" value="{{@$_POST['shipping']}}" tabindex="2"  /></td>
                                        <td><input type='text' class='a-input-text revenue shipping input-readonly' name="amazon-shipping" value="<?php echo (isset($calulated_values)) ? '0.00' : '';?>"  readonly /></td>
                                    </tr>
                                    <tr>
                                        <td>Revenue Subtotal</td>
                                        <td><?php echo (isset($revenue)) ? $revenue : ''?></td>
                                        <td><?php echo (isset($amazon_revenue)) ? $amazon_revenue : ''?></td>
                                    </tr>
                                    <tr>
                                        <td colspan='3'><strong>Cost</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Amazon Referral Fee</td>
                                        <td><input type='text' class='a-input-text fulfillment order-handling input-readonly' name="referral-fee" value="<?php echo (isset($referral_fees) ? $referral_fees : '')?>" readonly /></td>
                                        <td><input type='text' class='a-input-text fulfillment order-handling input-readonly' name="amazon-referral-fee" value="<?php echo (isset($amazon_referral_fee)) ? $amazon_referral_fee : '';?>" readonly /></td>
                                    </tr>
                                    <tr>
                                        <td>Variable Closing Fee</td>
                                        <td><input type='text' class='a-input-text fulfillment input-readonly pick-pack' name="closing-fee"  value="<?php echo (isset($calulated_values)) ? $calulated_values['data']['mfnFees']['variableClosingFee'] : '';?>" readonly /></td>
                                        <td><input type='text' class='a-input-text fulfillment input-readonly pick-pack' name="amazon-closing-fee" value="<?php echo (isset($calulated_values)) ? $calulated_values['data']['mfnFees']['variableClosingFee'] : '';?>" readonly /></td>
                                    </tr>
                                    <tr>
                                        <td colspan='3'><strong>Fulfillment Cost</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Order Handling</td>
                                        <td><input type='text' class='a-input-text fulfillment order-handling form-control' name="order-handling" value="{{@$_POST['order-handling']}}"  tabindex="5" /></td>
                                        <td><input type='text' readonly class='a-input-text fulfillment order-handling input-readonly' name="amazon-order-handling" value="<?php echo (isset($calulated_values)) ? $calulated_values['data']['afnFees']['orderHandlingFee'] : '';?>" /></td>
                                    </tr>
                                    <tr>
                                        <td>Pick & Pack</td>
                                        <td><input type='text' class='a-input-text fulfillment form-control pick-pack' name="pick-pack" value="{{@$_POST['pick-pack']}}" tabindex="6" /></td>
                                        <td><input type='text' readonly class='a-input-text fulfillment input-readonly pick-pack' name="amazon-pick-pack" value="<?php echo (isset($calulated_values)) ? $calulated_values['data']['afnFees']['pickAndPackFee'] : '';?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>Outbound Shipping</td>
                                        <td><input type='text' class='a-input-text fulfillment form-control outbound-delivery' name="outbound-delivery"  value="{{@$_POST['outbound-delivery']}}" tabindex="7" /></td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td>Weight Handling</td>
                                        <td><input type='text' class='a-input-text fulfillment form-control weight-handling' name="weight-handling"  value="{{@$_POST['weight-handling']}}" tabindex="8" /></td>
                                        <td><input type='text' readonly class='a-input-text fulfillment input-readonly weight-handling' name="amazon-weight-handling"  value="<?php echo (isset($calulated_values)) ? $calulated_values['data']['afnFees']['weightHandlingFee'] : '';?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>30 Day Storage</td>
                                        <td><input type='text' class='a-input-text fulfillment form-control storage' name="storage" value="{{@$_POST['storage']}}"  tabindex="9" /></td>
                                        <td><input type='text' readonly class='a-input-text fulfillment input-readonly storage' name="amazon-storage" value="<?php echo (isset($calulated_values)) ? $calulated_values['data']['afnFees']['storageFee'] : '';?>"/></td>
                                    </tr>
                                    <tr>
                                        <td>Inbound Shipping</td>
                                        <td><input type='text' class='a-input-text fulfillment form-control inbound-delivery' name="inbound-delivery" value="{{@$_POST['inbound-delivery']}}"  tabindex="10" /></td>
                                        <td><input type='text' class='a-input-text fulfillment form-control inbound-delivery' name="amazon-inbound-delivery" value="{{@$_POST['amazon-inbound-delivery']}}" tabindex="14"  /></td>
                                    </tr>
                                    <tr>
                                        <td>Customer Service</td>
                                        <td><input type='text' class='a-input-text fulfillment form-control customer-service' name="customer-service" value="{{@$_POST['customer-service']}}"  tabindex="11" /></td>
                                        <td>Amazon Provided</td>
                                    </tr>
                                    <tr>
                                        <td>Prep Service</td>
                                        <td><input type='text' class='a-input-text fulfillment form-control prep-service' name="prep-service" value="{{@$_POST['prep-service']}}"  tabindex="12" /></td>
                                        <td><input type='text' class='a-input-text fulfillment form-control prep-service' name="amazon-prep-service" value="{{@$_POST['amazon-prep-service']}}" tabindex="15" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><input type='submit' class='click_me form-control btn btn-primary' value="submit" /></td>
                                    </tr>
                                </table>
                                </form>
                            </div>    
                        </div>
                        
                        <div class="col-md-6">
                            <table class="table" style="table-layout:fixed;">
                                <tr>
                                    <td style='width: 50%;font-weight:bold'>Revenue</td>
                                    <td style='width: 25%;font-weight:bold'>Your Fulfillment</td>
                                    <td style='width: 25%;font-weight:bold'>Amazon Fulfillment</td>
                                </tr>
                                <tr>
                                    <td><span style="font-weight:bold">Fulfillment Cost Subtotal</span></td>
                                    <td><?php echo (isset($fulfillment_cost_subtotal)) ? $fulfillment_cost_subtotal : ""?></td>
                                    <td><?php echo (isset($amazon_fulfillment_cost_subtotal)) ? $amazon_fulfillment_cost_subtotal : ""?></td>
                                </tr>
                                <tr>
                                    <td><span style="font-weight:bold">Cost Subtotal</span></td>
                                    <td><?php echo (isset($cost_subtotal)) ? '-'.$cost_subtotal : ""?></td>
                                    <td><?php echo (isset($amazon_cost_subtotal)) ? '-'.$amazon_cost_subtotal : ""?></td>
                                </tr>
                                <tr>
                                    <td><span style="font-weight:bold">Margin</span></td>
                                    <td><?php echo (isset($margin_impact)) ? '<span style="font-weight:bold">'.$margin_impact.'</span>' : ""?></td>
                                    <td><?php echo (isset($amazon_margin_impact)) ? '<span style="color:#090;font-weight:bold">'.$amazon_margin_impact.'</span>' : ""?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else
                    <div style="margin:0 0 0 30px;font-size: 20px;font-weight: bold">No Product Found</div>
                    @endif
                @endif
                <!-- End Example Contextual Classes -->
            </div>
        </div>
        <!-- End Panel -->



@endsection

@section('scripts')
    <script>
//        $(document).ready(function(){
//            $('.click_me').click(function(){
//                if($('.pricing').val() === ''){
//                    alert('Please enter the Price');
//                    return false;
//                }
//            });
//        })
    </script>
@endsection