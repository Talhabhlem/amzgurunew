<div class="clearfix margin-bottom-15">


    <form role="form" style="margin-right:15px;" class="form-inline pull-right te-ajax-form" id="event_table_search_form" action="events/ajax_get_events" >
        <div class="form-group">
            <label for="search_keyword" >Keyword Filter: </label>
            <input type="text" placeholder="Enter Keyword" id="search_keyword" name="search_keyword" class="form-control" value="{{$search_keyword}}" />
        </div>
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
    </form>

</div>

<div class="table-responsive">
    <form action="events/ajax_action_event" id="actions_events_form" class="te-ajax-form">
        <table id="event_table" data-action="events/ajax_get_events"
               data-order="{{$event_order}}" data-order_by="{{$event_order_by}}"
               class="table te-ajax-paginate table-hover">
            <thead>
            <tr>
                <th class="text-left"><span>Event</span></th>
                <th width="120" class="text-left"><span>SKU</span></th>
                <th class="text-left"><span>Product</span></th>
                <th width="120" class="text-left"><span>Event Date</span></th>
                <th class="text-left"><span>Action</span></th>
            </tr>
            </thead>
            <tbody>
            @if (count($events) == 0)
            <tr>
                <td style="background:transparent;" colspan="9">
                    <div class="alert alert-warning fade in">
                        <i class="fa fa-times-circle fa-fw fa-lg"></i>
                        No Event found.
                    </div>
                </td>
            </tr>
            @endif
            @foreach ($events as $indexx=>$event)
            <tr class="{{$indexx%2==0 ? '':'active'}}">
                <td class="padding-left-15">
                    {!!str_replace($search_keyword,"<span class='highlighted'>".$search_keyword."</span>",$event->description)!!}
                </td>
                <td>
                    <a href="http://www.amazon.com/gp/aw/d/{{$sku_titles[$event->sku]['asin']}}" target="_blank">
                        {!!str_replace(strtoupper($search_keyword),"<span class='highlighted'>".strtoupper($search_keyword)."</span>",$event->sku)!!}
                    </a>
                </td>
                <td>
                    <a href="http://www.amazon.com/gp/aw/d/{{$sku_titles[$event->sku]['asin']}}" target="_blank">
                        {{$sku_titles[$event->sku]['asin']}}
                    </a>
                </td>
                <td>
                    {{date("d-M-Y", strtotime($event->event_date))}}
                </td>
                <td>
                    <a title="Delete Event" href="{{url('events/delete/' . $event->id)}}"><i class="fa fa-trash-o"></i></a> &nbsp;
                    <a title="Edit Event"   href="{{action('EventController@edit', [$event->id]) }}"><i class="fa fa-edit"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </form>
</div>
<div id="event_table_pagination" class="te-pagination-wrapper padding-left-15">
    {!! $events_page_links !!}
</div>

<!--<ul class="pagination pull-right">
    <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
</ul>-->


