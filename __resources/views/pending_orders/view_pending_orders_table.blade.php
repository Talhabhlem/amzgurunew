<div class="clearfix margin-bottom-15">

    <form role="form" style="margin-right:15px;" class="form-inline pull-right te-ajax-form" id="orders_table_search_form" action="pending_orders/ajax_get_orders" >
        <div class="form-group">
            <label for="search_keyword" >Order ID Filter: </label>
            <input type="text" placeholder="Enter Order ID" id="search_keyword" name="search_keyword" class="form-control" value="<?php echo $search_keyword;?>" />
        </div>
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
    </form>

</div>

<div class="table-responsive">
    <form action="pending_orders/ajax_action_orders" id="actions_orders_form" class="te-ajax-form">
        <table id="orders_table" data-action="pending_orders/ajax_get_orders"
               data-order="<?php echo $order;?>" data-order_by="<?php echo $order_by;?>"
               class="table te-ajax-paginate table-hover">
            <thead>
            <tr>
                <th class="text-left"><span>Order ID</span></th>
                <th class="text-left"><span>Purchase Date</span></th>
                <th class="text-left"><span>Loaded On</span></th>
                <th class="text-left"><span>Status</span></th>
            </tr>
            </thead>
            <tbody>
            <?php if(count($orders)==0) { ?>
            <tr >
                <td style="background:transparent;" colspan="3">
                    <div class="alert alert-warning fade in">
                        <i class="fa fa-times-circle fa-fw fa-lg"></i>
                        No Pending Orders found.
                    </div>
                </td>
            </tr>
            <?php } ?>
            <?php
            foreach($orders as $indexx => $act) {
            $trClass = '';
            if($act->bug == 'yes') {
                $trClass = 'bg-red-300';
            } else {
                $trClass = $indexx%2==0 ? '':'active';
            }
            ?>
            <tr class="<?php echo $trClass;?>">
                <td>
                    <?php echo str_replace($search_keyword,"<span class='highlighted'>$search_keyword</span>",$act->order_id);?>
                </td>
                <td>
                    <?php echo $act->purchase_date;?>
                </td>
                <td>
                    <?php echo $act->created_on;?>
                </td>
                <td>
                    <?php echo $act->bug == 'yes' ? 'Bug' : 'Pending';?>
                </td>

            </tr>
            <?php } ?>

            </tbody>
        </table>
    </form>

</div>

<div id="orders_table_pagination" class="te-pagination-wrapper">
    <?php echo $page_links;?>
    <div class="te-table-bottom-text pull-right margin-top-30" >
        ( total <?php echo $total;?> pending orders )
    </div>
</div>
