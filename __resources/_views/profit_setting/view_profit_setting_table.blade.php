<div class="table-responsive">

    <form action="profit/ajax_save_profit_summery" id="actions_profit_form" class="te-ajax-form">
        <?php //p_rr($pending_order_data);?>
        <table id="profit_table" data-action="profit/ajax_get_profit_setting" data-order="{{$order}}" data-order_by="{{$order_by}}" class="table table-hover te-ajax-paginate te-ajax-sort" data-loading_target="#view_profit_table" style="margin-top: 15px;">
            <thead>
            <tr>
                <th class="text-left enablesort" style="max-width:250px;"><a class="@if( $order_by=='title' ) {{strtolower( $order ) =='asc' ? 'desc' : 'asc'}} @else {{'sorting'}} @endif" href="#" data-sortby="title"><span>Product Name</span></a></th>
                <th class="text-left enablesort" style="width:120px;"><a class="@if( $order_by=='sku' ){{strtolower( $order ) =='asc' ? 'desc' : 'asc'}} @else {{'sorting'}} @endif" href="#" data-sortby="sku"><span>SKU</span></a></th>
                <th class="text-left"><a class="@if( $order_by=='unit_price' ) {{strtolower( $order ) =='asc' ? 'desc' : 'asc'}}@else  {{'sorting'}} @endif" href="#" data-sortby="unit_price">Item Price ($)</a></th>
                <th class="text-left"><a class="@if( $order_by=='cost' ) {{strtolower( $order ) =='asc' ? 'desc' : 'asc'}}@else  {{'sorting'}} @endif" href="#" data-sortby="cost">Amazon Referral Fee ($)</a></th>
                <th class="text-left"><a class="@if( $order_by=='fee' ) {{strtolower( $order ) =='asc' ? 'desc' : 'asc'}}@else  {{'sorting'}} @endif" href="#" data-sortby="fee">Amazon Fulfilment ($)</a></th>
                <th class="text-left"><a class="@if( $order_by=='fulfilment' ) {{strtolower( $order ) =='asc' ? 'desc' : 'asc'}}@else  {{'sorting'}} @endif" href="#" data-sortby="fulfilment">Cost Per Unit ($) </a></th>
                <th class="text-left"><a class="@if( $order_by=='weight_handling' ) {{strtolower( $order ) =='asc' ? 'desc' : 'asc'}}@else  {{'sorting'}} @endif" href="#" data-sortby="weight_handling">Weight Handling & Storage ($)</a></th>
                <th class="text-left"><a class="@if( $order_by=='ppc_costs' ) {{strtolower( $order ) =='asc' ? 'desc' : 'asc'}}@else  {{'sorting'}} @endif" href="#" data-sortby="ppc_costs">PPC Costs  ($)</a></th>
                <th class="text-left"><a class="@if( $order_by=='misc_costs' ) {{strtolower( $order ) =='asc' ? 'desc' : 'asc'}}@else  {{'sorting'}} @endif" href="#" data-sortby="misc_costs">Misc Costs ($)</a></th>
                <th class="text-left"><span>Profit</span></th>
                <th class="text-left"><span>Calculator</span></th>
            </tr>
            </thead>
            <tbody>
            @if(count($sales)==0)
            <tr>
                <td style="background:transparent;" colspan="8">
                    <div class="alert alert-warning fade in">
                        <i class="fa fa-times-circle fa-fw fa-lg"></i>
                        No Sales found from <strong>{{date('F d, Y',strtotime($from_date))}}</strong> to <strong>{{date('F d, Y',strtotime($to_date))}}</strong>.
                    </div>
                </td>
            </tr>
            @endif
            <?php
            $cnt=0;
            $t_u=0;$t_s=0;$t_p=0;
            ?>
            @foreach($sales as $indexx=>$profit_row)
            <tr class="{{$indexx%2==0 ? '':'active'}}">
                <td>
                    <a href="http://www.amazon.com/gp/aw/d/{{$profit_row->asin}}" target="_blank">
                        {{ucwords(str_replace(strtolower($search_keyword),"<span class='highlighted'>".strtolower($search_keyword)."</span>",strtolower($profit_row->title)))}}
                    </a>
                </td>
                <td>
                    <a href="http://www.amazon.com/gp/aw/d/{{$profit_row->asin}}" target="_blank">
                        {{str_replace(strtoupper($search_keyword),"<span class='highlighted'>".strtoupper($search_keyword)."</span>",$profit_row->sku)}}
                    </a>
                </td>
                <td class="text-center"><input type="text" style="width:70px; margin:0 auto; text-align:center;" class="form-control" value="{{$profit_row->unit_price}}" readonly /></td>
                <td class="text-center"><input type="text" style="width:70px; margin:0 auto; text-align:center;" class="form-control" value="{{$profit_row->fee}}" readonly /></td>
                <td class="text-center"><input type="text" style="width:70px; margin:0 auto; text-align:center;" class="form-control" value="{{$profit_row->fulfilment}}" name="fulfilment[{{$profit_row->sku}}]" /></td>
                <td class="text-center"><input type="text" style="width:70px; margin:0 auto; text-align:center;" class="form-control" value="{{$profit_row->cost}}" name="cost[{{$profit_row->sku}}]" /></td>
                <td class="text-center"><input type="text" style="width:70px; margin:0 auto; text-align:center;" class="form-control" value="{{$profit_row->weight_handling}}" name="weight_handling[{{$profit_row->sku}}]" /></td>
                <td class="text-center"><input type="text" style="width:70px; margin:0 auto; text-align:center;" class="form-control" value="{{$profit_row->ppc_costs}}" name="ppc_costs[{{$profit_row->sku}}]" /></td>
                <td class="text-center"><input type="text" style="width:70px; margin:0 auto; text-align:center;" class="form-control" value="{{$profit_row->misc_costs}}" name="misc_costs[{{$profit_row->sku}}]" /></td>
                <td class="text-center">${{ number_format($profit_row->profit,2) }}</td>
                <td class="text-center"> <a target="__blank" href="{!! url('calculator?product_id=')!!}{{$profit_row->asin}}">calculate</a> </td>
                <td></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @if(count($sales)>0)
        <div class="margin-top-10 padding-top-15 padding-bottom-15 padding-left-15" style="border-top: 1px solid #e4eaec; text-align: center;">
            <input type="submit" value="Save" name="save_sku_cost" style="width:160px;" class="btn btn-primary btn-block">
        </div>
        @endif
    </form>
</div>

<div id="profit_table_pagination" class="te-pagination-wrapper padding-left-30 padding-right-30">
    {!!$page_links!!}
    <div class="te-table-bottom-text pull-right margin-top-30" >
        (<strong>{{$total}}</strong> products sold from <strong>{{date('F d, Y',strtotime($from_date))}}</strong> to <strong>{{date('F d, Y',strtotime($to_date))}}</strong>)
    </div>
</div>
