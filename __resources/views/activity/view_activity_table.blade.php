<div class="clearfix margin-bottom-15" style="padding: 20px;">

    <form role="form" style="margin-right:15px;" class="form-inline pull-right te-ajax-form" id="activity_table_search_form" action="activity/ajax_get_activity" >
        <div class="form-group">
            <label for="search_keyword" >Order ID Filter: </label>
            <input type="text" placeholder="Enter Order ID" id="search_keyword" name="search_keyword" class="form-control" value="{{$search_keyword}}" />
        </div>
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
    </form>

</div>

    <form action="activity/ajax_action_activity" id="actions_activity_form" class="te-ajax-form">
        <table id="activity_table" data-action="activity/ajax_get_activity"
               data-order="{{$activity_order}}" data-order_by="{{$activity_order_by}}"
               class="table te-ajax-paginate table-hover">
            <thead>
            <tr>
                <th class="text-left"><span>ID</span></th>
                <th class="text-left"><span>Order ID</span></th>
                <th class="text-left"><span>Loaded on</span></th>
                <th class="text-left"><span>Purchase Date</span></th>
                <th class="text-left"><span>Sale Type</span></th>
                <th class="text-left"><span>Status</span></th>
            </tr>
            </thead>
            <tbody>
            @if(count($activity)==0)
            <tr>
                <td style="background:transparent;" colspan="9">
                    <div class="alert alert-warning fade in">
                        <i class="fa fa-times-circle fa-fw fa-lg"></i>
                        No Orders found.
                    </div>
                </td>
            </tr>
            @endif
            @foreach($activity as $indexx => $act)

               <?php $trClass = ''; ?>
            @if($act->status == 'pending')
                <?php  $trClass = 'bg-orange-200'; ?>
            @else
                <?php $trClass = $indexx%2==0 ? '':'active'; ?>
            @endif
            <tr class="{{$trClass}}">
                <td>
                    {{$act->id}}
                </td>
                <td>
                 <a target="_blank" href="https://sellercentral.amazon.com/gp/orders-v2/details?ie=UTF8&orderID={{$act->order_id}}">   {{str_replace($search_keyword,"<span class='highlighted'>$search_keyword</span>",$act->order_id)}}</a>
                </td>
                <td>
                   {{date('d-M-Y h:i:s A',strtotime($act->loaded_on))}}
                </td>
                <td>
                    {{date('d-M-Y h:i:s A',strtotime($act->purchase_date))}}
                </td>
                <td>
                    {{$act->sale_type == 'new' ? 'From Recent Sale':''}}
                </td>
                <td>
                   {{$act->status == '' ? "Shipped":ucfirst($act->status)}}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </form>

<div id="activity_table_pagination" class="te-pagination-wrapper" style="padding: 5px 20px;">
    <?php echo $activity_page_links;?>
</div>



