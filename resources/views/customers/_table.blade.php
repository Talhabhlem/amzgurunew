<div class="clearfix margin-bottom-15">
    <form role="form" style="margin-right:15px;" class="form-inline pull-right te-ajax-form" id="customers_table_search_form" action="customers/ajax_get" >
        <div class="form-group">
            <label for="search_keyword" >Keyword Filter: </label>
            <input type="text" placeholder="Enter Keyword" id="search_keyword" name="search_keyword" class="form-control" value="{{$search_keyword}}" />
        </div>
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
    </form>
</div>
<div class="table-responsive">
    <form action="events/ajax_action_event" id="actions_customers_form" class="te-ajax-form">
        <table id="customers_table" data-action="customers/ajax_get"
               data-order="{{$order}}" data-order_by="{{$order_by}}"
               class="table te-ajax-paginate table-hover te-ajax-sort">
            <thead>
            <tr>
                <th class="enablesort text-left" ><a href="javascript:void(0)" class="sorting" data-sortby="name"><span>Name</span></a></th>
                <th width="120" class="text-left"><span>Email</span></th>
                <th width="120" class="text-left"><span>Orders(#)</span></th>
                <th width="120" class="enablesort"><a href="javascript:void(0)" class="sorting" data-sortby="CountryCode"><span>CountryCode</span></a></th>
                <th width="120" class="enablesort text-left"><a href="javascript:void(0)" class="sorting" data-sortby="City"><span>City</span></a></th>
                <th width="120" class="text-left"><span>Phone</span></th>
                <th width="120" class="enablesort text-left"><a href="javascript:void(0)" class="sorting" data-sortby="created_at"><span>Created At</span></a></th>
            </tr>
            </thead>
            <tbody>
            @if (count($customers) == 0)
                <tr>
                    <td style="background:transparent;" colspan="9">
                        <div class="alert alert-warning fade in">
                            <i class="fa fa-times-circle fa-fw fa-lg"></i>
                            No Customer found.
                        </div>
                    </td>
                </tr>
            @endif
            @foreach ($customers as $indexx=>$customer)
                <tr class="{{$indexx%2==0 ? '':'active'}}">
                    <td class="padding-left-15">{!!str_replace($search_keyword,"<span class='highlighted'>".$search_keyword."</span>",$customer->name)!!}</td>
                    <td>{!!str_replace(strtoupper($search_keyword),"<span class='highlighted'>".strtoupper($search_keyword)."</span>",$customer->email)!!}</td>
                    <td><a class="blue-circle" href="{{action('CustomerController@orders', ['customer' => $customer->customer_id])}}">{{$customer->total_orders}}</a></td>
                    <td>{{$customer->CountryCode}}</td>
                    <td>{{$customer->City}}</td>
                    <td>{{$customer->Phone}}</td>
                    <td>
                        {{date("d-M-Y", strtotime($customer->created_at))}}
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

