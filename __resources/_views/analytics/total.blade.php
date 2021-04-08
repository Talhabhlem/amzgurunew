<?php
$total_units_sold = $overall_stats['total_units_sold'];
$total_gross_sale = $overall_stats['total_gross_sale'];
$total_profit     = $overall_stats['total_profit'];
?>
<style>
    .counter-number {
        font-size: 16px;
    }
    .counter-icon {
        line-height: 1.2;
    }
</style>
<div class="">
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <!-- START widget-->
            <div class="panel widget bg-success">
                <div class="row row-table">
                    <div class="col-xs-4 text-center bg-success-dark pv-lg">
                        <em class="icon-share fa-3x"></em>
                    </div>
                    <div class="col-xs-8 pv-lg">
                        <div class="h1 m0 text-bold"><?php echo number_format($total_units_sold,0);?></div>
                        <div class="text-uppercase">Total Units Sold</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <!-- START widget-->
            <div class="panel widget bg-danger">
                <div class="row row-table">
                    <div class="col-xs-4 text-center bg-danger-dark pv-lg">
                        <em class="icon-star fa-3x"></em>
                    </div>
                    <div class="col-xs-8 pv-lg">
                        <div class="h1 m0 text-bold">$ <?php echo number_format($total_gross_sale,0);?></div>
                        <div class="text-uppercase">Total gross Sale</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <!-- START widget-->
            <div class="panel widget bg-warning">
                <div class="row row-table">
                    <div class="col-xs-4 text-center bg-warning-dark pv-lg">
                        <em class="icon-trophy fa-3x"></em>
                    </div>
                    <div class="col-xs-8 pv-lg">
                        <div class="h1 m0 text-bold">$ <?php echo number_format($total_profit,0);?></div>
                        <div class="text-uppercase">Total Profit</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END row-->
</div>
