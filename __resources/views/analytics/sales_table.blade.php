<div class="table-responsive">

    <form action="analysis/ajax_action_sales" id="actions_sales_form" class="te-ajax-form">
        <?php //p_rr($pending_order_data);?>
        <table id="sales_table" data-action="analysis/alternate_get_sales_method" data-order="<?php echo $sales_order;?>" data-order_by="<?php echo $sales_order_by;?>" class="table table-hover te-ajax-paginate te-ajax-sort" data-loading_target="#view_sales_table" style="margin-top: 15px;">
            <thead>
            <tr>
                <th class="text-left"><span><i class="fa fa-area-chart"></i></span></th>
                <th class="text-left enablesort" style="max-width:250px;"><a class="<?php if( $sales_order_by=='title' ){ echo strtolower( $sales_order ) =='asc' ? 'desc' : 'asc';} else { echo 'sorting';}?>" href="#" data-sortby="title"><span>Product</span></a></th>
                <th class="text-left enablesort" style="width:120px;"><a class="<?php if( $sales_order_by=='sku' ){ echo strtolower( $sales_order ) =='asc' ? 'desc' : 'asc';} else { echo 'sorting';}?>" href="#" data-sortby="sku"><span>SKU</span></a></th>
                <th class="text-left enablesort"><a class="<?php if( $sales_order_by=='total_qty' ){ echo strtolower( $sales_order ) =='asc' ? 'desc' : 'asc';} else { echo 'sorting';}?>" href="#" data-sortby="total_qty"><span>No. Of Unit Items</span></a></th>
                <th class="text-left enablesort"><a class="<?php if( $sales_order_by=='qty_percentage' ){ echo strtolower( $sales_order ) =='asc' ? 'desc' : 'asc';} else { echo 'sorting';}?>" href="#" data-sortby="qty_percentage"><span>Unit Items Percentage</span></a></th>
                <th class="text-left enablesort"><a class="<?php if( $sales_order_by=='total_price' ){ echo strtolower( $sales_order ) =='asc' ? 'desc' : 'asc';} else { echo 'sorting';}?>" href="#" data-sortby="total_price"><span>Sale Amount</span></a></th>
                <th class="text-left enablesort"><a class="<?php if( $sales_order_by=='price_percentage' ){ echo strtolower( $sales_order ) =='asc' ? 'desc' : 'asc';} else { echo 'sorting';}?>" href="#" data-sortby="price_percentage"><span>Sale Amount Percentage</span></a></th>
                <th class="text-left enablesort"><span>Profit</span></th>
                <th class="text-left enablesort"><a class="<?php if( $sales_order_by=='latest_date' ){ echo strtolower( $sales_order ) =='asc' ? 'desc' : 'asc';} else { echo 'sorting';}?>" href="#" data-sortby="latest_date"><span>Latest Purchase Date</span></a></th>
            </tr>
            </thead>
            <tbody>
            <?php if(count($sales)==0) { ?>
            <tr>
                <td style="background:transparent;" colspan="9">
                    <div class="alert alert-warning fade in">
                        <i class="fa fa-times-circle fa-fw fa-lg"></i>
                        No Sales found from <strong><?php echo date('F d, Y',strtotime($from_date));?></strong> to <strong><?php echo date('F d, Y',strtotime($to_date));?></strong>.
                    </div>
                </td>
            </tr>
            <?php } ?>
            <?php
            $cnt=0;
            $t_u=0;$t_s=0;$t_p=0;
            foreach($sales as $indexx=>$sale) {
            ?>
            <tr class="<?php echo $indexx%2==0 ? '':'active';?>">
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline btn-dark dropdown-toggle" id="exampleIconDropdown4" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-area-chart" aria-hidden="true"></i>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exampleIconDropdown4" role="menu">
                            <li role="presentation">
                                <a role="menuitem" title="Daily Sales" data-toggle="modal"  href="#modalSalesAnalysis" data-target="#modalSalesAnalysis" data-sku="<?php echo $sale->sku;?>" data-chart-type="daily"  >Daily Analysis</a>
                            </li>
                            <li role="presentation">
                                <a role="menuitem" title="Daily Sales" data-toggle="modal"  href="#modalSalesAnalysis" data-target="#modalSalesAnalysis" data-sku="<?php echo $sale->sku;?>" data-chart-type="weekly"  >Weekly Analysis</a>
                            </li>
                        </ul>
                    </div>
                </td>
                <td>
                    <a href="http://www.amazon.com/gp/aw/d/<?php echo $sale->asin;?>" target="_blank"><?php echo $sale->title;?></a>
                </td>
                <td>
                    <a href="http://www.amazon.com/gp/aw/d/<?php echo $sale->asin;?>" target="_blank"><?php echo str_replace(strtoupper($search_keyword),"<span class='highlighted'>".strtoupper($search_keyword)."</span>",$sale->sku);?></a>
                </td>
                <td>
                    <?php echo number_format($sale->total_qty,0); $t_u += $sale->total_qty;?>
                </td>
                <td>{{number_format($sale->qty_percentage*100,2,'.','').'%'}}</td>
                <td>{{"$".number_format($sale->total_price,2)}} <?php $t_s += $sale->total_price;?></td>
                <td>{{number_format($sale->price_percentage*100,2,'.','').'%'}}</td>
                <td>{{'$'.number_format($sale->item_profit,2)}}</td>
                <td>{{date('d-M-Y h:i:s A',strtotime(\App\Helpers\TeHelper::te_change_timezone($sale->latest_date,'America/Los_Angeles')))}}</td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </form>
</div>

<div id="sales_table_pagination" class="te-pagination-wrapper padding-left-30 padding-right-30">
    <?php echo $sales_page_links;?>
    <div class="te-table-bottom-text pull-right margin-top-30" >
        (<strong><?php echo $total;?></strong> products sold from <strong><?php echo date('F d, Y',strtotime($from_date));?></strong> to <strong><?php echo date('F d, Y',strtotime($to_date));?></strong>)
    </div>
</div>
