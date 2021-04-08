<li class="dropdown dropdown-fw dropdown-mega ">
    <a class="dropdown-toggle " data-toggle="dropdown" href="#" aria-expanded="false"
       data-animation="fade" role="button"><strong>Data Status <i class="icon wb-chevron-down-mini" aria-hidden="true"></i></strong></a>
    <ul style="box-shadow: 0 0 11px 3px #526069;" class="dropdown-menu" role="menu">
        <li role="presentation">
            <div class="mega-content">
                <div class="row">

                    <?php if(date('d-M-Y',strtotime($_sale_dates['new_latest'])) != date('d-M-Y')) {?>

                    <div class="alert dark alert-info alert-dismissible" role="alert">
                        <button style="display: none;" type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>

                        <h4><i class="icon fa fa-exclamation-triangle" aria-hidden="true"></i> Loading Recent Data From Oldest To Newest...</h4>
                        <p><strong>PROGRESS:</strong> All Recent Data Fetched From <strong><?php echo date('F d, Y',strtotime($_sale_dates['new_first']));?></strong> up to <strong><?php echo date('F d, Y h:i A',strtotime($_sale_dates['new_latest']));?></strong></p>
                    </div>

                    <?php } else { ?>

                    <div class="alert dark alert-success alert-dismissible" role="alert">
                        <button style="display: none;" type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>

                        <h4><i aria-hidden="true" class="icon fa fa-check-square-o"></i> Recent Loaded Successfully</h4>
                        All Recent Data Is Successfully Loaded From: <strong><?php echo date('F d, Y h:i A',strtotime($_sale_dates['new_first']));?></strong> Up To: <strong><?php echo date('F d, Y h:i A',strtotime($_sale_dates['new_latest']));?></strong>
                    </div>
                    <?php } ?>

                    <?php if(strtotime($_sale_dates['old_latest'])>=strtotime($_sale_dates['new_first']) ||
                    $_sale_dates['above_from_new_row']['purchase_date'] == date('Y-m-d H:i:s',strtotime($_sale_dates['old_latest']))
                    || $cuser['old_data_status'] == 'completed') {?>
                    <div class=" margin-bottom-0 alert dark alert-success alert-dismissible" role="alert">
                        <button style="display: none;" type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>

                        <h4><i aria-hidden="true" class="icon fa fa-check-square-o"></i> Old Data Loaded Successfully</h4>
                        <p>
                            All Old Data Is Successfully Loaded Up To:
                            <strong>
                                <?php echo date('F d, Y h:i A' , strtotime($_sale_dates['new_first']) );?></strong>

                        </p>
                    </div>
                    <?php } else { ?>
                    <div class=" margin-bottom-0 alert dark alert-info alert-dismissible" role="alert">
                        <button style="display: none;" type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4><i class="icon fa fa-exclamation-triangle" aria-hidden="true"></i> Loading Old Data From Oldest To Newest...</h4>
                        <p>
                            <strong>PROGRESS:</strong> All Old Data Fetched From <strong><?php echo date('F d, Y',strtotime($_sale_dates['old_first']));?></strong> up to <strong><?php echo date('F d, Y h:i A',strtotime($_sale_dates['old_latest']));?></strong>
                        </p>
                    </div>

                    <?php } ?>

                </div>
            </div>
        </li>
    </ul>
</li>
<li class="clock-wrapper hidden-xs" >
    <a class="icon bg-blue-200">
        <i style="float:left; line-height:1.5; margin-right:5px;" class="icon fa fa-clock"></i>
        <span class="hidden-xs"><strong style="font-weight: bold;">NOW: </strong><strong class="bslue-800" id="clock">getting Amazon PDT time...</strong></span>
    </a>
</li>
