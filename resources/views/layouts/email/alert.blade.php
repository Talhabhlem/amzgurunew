<?php
$date_interval = "";
if(date('Y-m-d',strtotime($from_date)) == date('Y-m-d',strtotime($to_date))) {
    $date_interval = $total.'</strong> products were sold on <strong>'.date('F d, Y',strtotime($from_date));
} else {
    $date_interval = $total.'</strong> products were sold from <strong>'.date('F d, Y',strtotime($from_date)).'</strong> to <strong>'.date('F d, Y',strtotime($to_date));
}
?>
<html>
<head></head>
<body style="font-family: Arial, sans-serif;">
<style>
    table.sales-email-table tr td.status-box {
        font-weight: bold;
        font-size: 24px;
    }
    table.sales-email-table tr td.status-box.plus ,table.sales-email-table tr th.status-box.plus {
        color: #00ff00 !important;
    }
    table.sales-email-table tr td.status-box.minus, table.sales-email-table tr th.status-box.minus {
        color: #ff0000 !important;
    }
</style>
<div class="table-responsive">
    <h2 style="color: #62a8ea;"><?php echo 'EcommElite '.$package.' Alert'; ?></h2>
    <div id="sales_table_pagination" style="margin-bottom:10px;">
        <div style="float:right;font-size:13px; color:#333;">(<strong>{!!$date_interval!!}</strong>)</div>
    </div>

    <div style="clear:both; height:10px; "></div>

    <table  class="sales-email-table table te-ajax-paginate te-ajax-sort dataTable dtr-inline" cellpadding="6" cellspacing="0">
        <thead>
        <tr>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;" colspan="2" class="text-left enablesort"><span>Product</span></th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;" class="text-left enablesort" colspan="3" ><span>Unit Items Sold</span></th>
            <!--                <th style=background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;"  rowspan="2" class="text-left enablesort"><span>Unit Items Sold(%)</span></th>-->
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;" class="text-left enablesort" colspan="3" ><span>Sale Amount ($)</span></a></th>
            <!--                <th style=background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;" rowspan="2" class="text-left enablesort"><span>Sale Percentage</span></th>-->
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;" class="text-left enablesort" colspan="3" ><span>Profit</span></th>
        </tr>
        <tr>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">Name</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">SKU</th>
            <?php if(strtolower($package) == 'daily') { ?>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">day before yesterday</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">yesterday</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">status</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">day before yesterday</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">yesterday</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">status</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">day before yesterday</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">yesterday</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">status</th>
            <?php } else if(strtolower($package) == 'weekly'){ ?>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">last week</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">this week</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">status</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">last week</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">this week</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">status</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">last week</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">this week</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">status</th>
            <?php } else if(strtolower($package) == 'monthly'){ ?>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">last month</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">this month</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">status</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">last month</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">this month</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">status</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">last month</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">this month</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">status</th>
            <?php } ?>
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
        $tl_u=0;$tl_s=0;$tl_p=0;
        foreach($sales as $sindex=> $sale) {

        $presale = null;
        foreach ($previous['sales'] as $key => $pre) {
            if ( $pre->sku ==  $sale->sku) {
                $presale = $pre;
            }
        }

        ?>
        <tr class="<?php echo $sindex%2==0 ? 'active-row':'';?>" style="<?php echo $sindex%2==0 ? 'background:#f3f7f9;':'';?>">

            <td style="font-weight:normal; border-left: 1px solid font-size: 13px;"><?php echo $sale->title;?></td>
            <td style="font-weight:normal; border-left: 1px solid #font-size: 13px;"><?php echo $sale->sku;?></td>
            <td style="font-weight: normal; border-left: 1px solid #e4eaec;font-size: 13px;" class="center-text">
                <?php
                $lastqty =  $presale ?  number_format($presale->total_qty,0) : 0 ;
                echo $lastqty;
                $tl_u += $lastqty;
                ?>
            </td>

            <td style="font-weight: normal; border-left: 1px solid #e4eaec;font-size: 13px;" class="center-text">
                <?php echo number_format($sale->total_qty,0); $t_u += $sale->total_qty; ?>
            </td>
            <?php
            $units_sold_status = '=';
            if ( $sale->total_qty > $lastqty ) {
                $units_sold_status = '+';
            }else if ( $sale->total_qty < $lastqty ) {
                $units_sold_status = '-';
            }
            ?>
            <td style="text-align:center; font-weight: bold; border-left: 1px solid #e4eaec;font-size: 24px; color:<?php echo $units_sold_status=='-' ? '#ff0000':'#00ff00';?>;" class="center-text status-box "><?php echo $units_sold_status;?></td>

            <!--                <td style=font-weight: normal; border-left: 1px solid #e4eaec;font-size: 13px; class="center-text">-->
            <!--                    --><?php //
            //					$percent = $sale->qty_percentage*100;
            //					echo number_format($percent,2,'.','').'%';?>
            <!--                </td>-->

            <td style="font-weight: normal; border-left: 1px solid #e4eaec;font-size: 13px;" class="center-text">
                <?php
                $lastsale =  $presale ?  $presale->total_price : 0 ;
                echo "$".number_format($lastsale,2);
                $tl_s += $lastsale;
                ?>

            </td>


            <td style="font-weight: normal; border-left: 1px solid #e4eaec;font-size: 13px;"  class="center-text">
                <?php echo "$".number_format($sale->total_price,2); $t_s += $sale->total_price;?>
            </td>

            <?php
            $sale_status = '=';
            if ( $sale->total_price > $lastsale ) {
                $sale_status = '+';
            } else if ( $sale->total_price < $lastsale ) {
                $sale_status = '-';
            }
            ?>
            <td style="text-align:center; font-weight: bold; border-left: 1px solid #e4eaec;font-size: 24px; color:<?php echo $sale_status=='-' ? '#ff0000':'#00ff00';?>;"  class="center-text status-box {{$sale_status=='-' ? 'minus':'plus'}}">{{$sale_status}}</td>
            <td style="font-weight: normal; border-left: 1px solid #e4eaec;font-size: 13px;" class="center-text">
                <?php
                $lastprofit = '0.00';
                if ( $presale ) {

                    $cost = $presale->profit_cost;
                    $fee = $presale->profit_fee;
                    $fulfilment = $presale->profit_fulfilment;
                    $weight_handling = $presale->profit_weight_handling;
                    $lastprofit = $presale->item_profit;
                    echo '$'.number_format($lastprofit,2);
                } else {
                    echo '$0.00';
                }
                ?>
            </td>


            <?php
            $cost = $sale->profit_cost;
            $fee = $sale->profit_fee;
            $fulfilment = $sale->profit_fulfilment;
            $weight_handling = $sale->profit_weight_handling;
            $profit = $sale->item_profit;
            $t_p += $profit;
            $tl_p += $lastprofit;
            ?>
            <td style="font-weight: normal; border-left: 1px solid #e4eaec;font-size: 13px;" class="center-text">
                {{'$'.number_format($profit,2)}}
            </td>
            <?php
            $profit_status = '=';
            if ( $profit > $lastprofit ) {
                $profit_status = '+';
            } else if ( $profit < $lastprofit ) {
                $profit_status = '-';
            }
            ?>
            <td style="text-align:center; font-weight: bold; border-left: 1px solid #e4eaec;font-size: 24px; color:<?php echo $profit_status=='-' ? '#ff0000':'#00ff00';?>;"  class="center-text status-box  {{$profit_status=='-' ? 'minus':'plus'}}">{{$profit_status}}</td>

        </tr>
        <?php } ?>
        <tr>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;" colspan="2" style="text-align:right;">Total Unit Items Sold
                <?php
                $total_units_status = '=';
                if ( $t_u > $tl_u ) {
                    $total_units_status = '+';
                } else if ( $t_u < $tl_u ) {
                    $total_units_status = '-';
                }
                ?>
                (<span class="{{$total_units_status=='-' ? 'minus':'plus'}}">{{$total_units_status}}</span>)
            </th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">{{$tl_u}}</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">{{$t_u}}</th>


            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">Total Sale Amount
                <?php
                $total_sale_status = '=';
                if ( $t_s > $tl_s ) {
                    $total_sale_status = '+';
                } else if ( $t_s < $tl_s ) {
                    $total_sale_status = '-';
                }

                ?>
                (<span class="{{$total_sale_status=='-' ? 'minus':'plus'}}">{{$total_sale_status}}</span>)
            </th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">{{'$'.number_format($tl_s,2)}}</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">{{'$'.number_format($t_s,2)}}</th>

            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;" >Total Profit
                <?php
                $total_profit_status = '=';
                if ( $t_p > $tl_p ) {
                    $total_profit_status = '+';
                } else if ( $t_p < $tl_p ) {
                    $total_profit_status = '-';
                }
                ?>
                (<span class="{{$total_profit_status=='-' ? 'minus':'plus'}}">{{$total_profit_status}}</span>)
            </th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;">{{'$'.number_format($tl_p,2)}}</th>
            <th style="background:#62a8ea !important; color:#FFF;  font-weight: bold; font-size: 16px; border-left: 1px solid #e4eaec; border-bottom: 1px solid #e4eaec;" colspan="2">{{'$'.number_format($t_p,2)}}</th>

        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
