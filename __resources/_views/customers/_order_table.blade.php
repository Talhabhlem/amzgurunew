<div class="clearfix margin-bottom-15">
    <form role="form" style="margin-right:15px;" class="form-inline pull-right te-ajax-form" id="event_table_search_form" action="customers/ajax_get_orders/{{$customer_id}}" >
        <div class="form-group">
            <label for="search_keyword" >Keyword Filter: </label>
            <input type="text" placeholder="Enter Keyword" id="search_keyword" name="search_keyword" class="form-control" value="{{$search_keyword}}" />
        </div>
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
    </form>
</div>
<div class="table-responsive">
    <form action="events/ajax_action_event" id="actions_events_form" class="te-ajax-form">
        <table id="customers_table" data-action="customers/ajax_get"
               data-order="{{$order}}" data-order_by="{{$order_by}}"
               class="table te-ajax-paginate table-hover te-ajax-sort">
            <thead>
            <tr>
                <th class="enablesort text-left" ><a href="javascript:void(0)" class="sorting" data-sortby="name"><span>Order Id</span></a></th>
                <th class="text-left"><span>Order Total</span></th>
                <th class="text-left"><span>Purchase Date</span></th>
            </tr>
            </thead>
            <tbody>
            @if (count($orders) == 0)
                <tr>
                    <td style="background:transparent;" colspan="9">
                        <div class="alert alert-warning fade in">
                            <i class="fa fa-times-circle fa-fw fa-lg"></i>
                            No Customer found.
                        </div>
                    </td>
                </tr>
            @endif
            @foreach ($orders as $indexx=>$order)
                <tr class="{{$indexx%2==0 ? '':'active'}}">
                    <td class="padding-left-15">{!!str_replace($search_keyword,"<span class='highlighted'>".$search_keyword."</span>",$order->order_id)!!}</td>
                    <td>{{$order->OrderTotal}}</td>
                    <td>
                        {{date("d-M-Y", strtotime($order->purchase_date))}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </form>
</div>
<div id="customers_table_pagination" class="te-pagination-wrapper padding-left-15">
    {!! $page_links !!}
</div>

