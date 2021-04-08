<link rel="stylesheet" type="text/css" href="assets/css/dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>

<style>
    TH {font-size:14px}
    TD {font-size:12px}

    .rcorners {
        border-radius: 8px 8px 8px 8px;
        border: 2px solid #FFFFFF;
        padding: 0px;
    }

    .rcornersLeft {
        border-radius: 25px 0px 0px 25px;
        border: 1px solid #949494;
        padding: 0px;
        margin: 0px;
        cursor: pointer;
    }

    .rcornersRight {
        border-radius: 0px 25px 25px 0px;
        border: 1px solid #949494;
        padding: 0px;
        margin: 0px;
        cursor: pointer;
    }

    .dataTables_scrollBody { background-image: none !important;}

    hr {
        border: 1px solid #efefef;
    }

    h3 {
        margin-top:    0.5em;
        margin-bottom: -10px;
    }

    .activeToggle {
        background-color: rgba(73, 252, 82, 0.53)
    }

    .dataTables_filter {
        display: none;
    }

    .dataTables_length {
        display: none;
    }

    .dataTables_info {
        display: none;
    }

    tbody tr.selected {
        background-color: #B0BED9 !important;
    }

    td {overflow:hidden;}

    .truncate {
        /*width: 200px;*/
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        -webkit-line-clamp: 1;
    }

    /*To get the sort icons aligned properly*/
    .css_right {float:right}

    table.dataTable thead .sorting:after {
        /*float: none;*/
    }

    table.dataTable thead .sorting_desc:after {
        content: '';
        float: right;
    }

    table.dataTable thead .sorting_asc:after {
        content: '';
        float: right;
    }

    div.dataTables_scrollBody thead th::after {
        display:none;
    }
</style>

<div id="detailDataNoData" class="row" style="display:none">
    <div class="col-md-10 col-md-offset-1 rcorners" align="center">
        <b>Uhh oh. It looks like you're quicker than we are. We don't have any data yet for<br>
        the asin <span class="asin"></span> with the keyword <span class="keyword"></span>.<br>
        Check back again in a few minutes for the first data point!</b>
    </div>
</div>

<div id="detailDataRow" class="row">
    <div class="col-md-10 col-md-offset-1 rcorners" style="background:white" height="400px">

        <div class="row" style="margin-left:1%;">
            <img style="float:left" id="prodImg" height="75">
            <div style="display:inline-block; width:60%; margin-left:1%">
                <div class="truncate" id="prodTitle">placeholder</div>
                <span id="prodAsin">asinplaceholder</span><br>
                <span id="prodKeyword"></span>
            </div>

        </div>
        <div>
            <div class="col-md-6">
                <div width="100%" height="50px" align="right" class="row-fluid">
                    <span id="rankToggle" class="rcornersLeft activeToggle">&nbsp;&nbsp;Rank&nbsp;&nbsp;</span><span id="bsrToggle" class="rcornersRight">&nbsp;&nbsp;BSR&nbsp;&nbsp;</span>
                </div>
                <div class="ct-chart ct-golden-section">
                    <canvas id="dataGraph"></canvas>
                </div>
            </div>

            <div class="col-md-6">

                <table id="detailTable" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Rank</th>
                        <th>BSR</th>
                        <th>Date</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                    </tr>
                    </tbody>
                </table>

                <div align="center" style="font-size:16px; height: 100px; line-height: 100px">
                    Avg. Rank: <span id="avgRank">999</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Avg. BSR: <span id="avgBsr">999</span>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    var detailChart;
    var showRank = true;

    var $chart = $('.ct-chart');
    var $toolTip = $chart
            .append('<div class="tooltip"></div>')
            .find('.tooltip')
            .hide();

    $(document).ready(function() {

        initDetailData();

        initWatchedProductTable();

        var table = $('#example').DataTable();

        $('#example tbody').on( 'click', '.removeProductLink', function (e) {
            console.log($(e));
            e.preventDefault();
            e.stopPropagation();
            var clickedLink = $(this);
            var url = clickedLink.data('url');
            var idArray = url.match('(?:keywordTracker\\/remove\\/)(\\d+)');

            var id = '';
            if (idArray[1]) {
                id = idArray[1];
            }

            $.post(url,{_token: "{{csrf_token()}}" })
                    .done(function( data ) {
                        if (data == 1) {

                            clickedLink.parent().parent().remove(); // Remove from current table

                            // Remove from watchedProduct array
                            $.each(watchedProduct, function(index, single) {

                                if (single.id == id) {

                                    // Remove from watchedProduct array
                                    watchedProduct.splice(index, 1);

                                    // Refresh watched product table
                                    $("#example").DataTable().destroy();
                                    initWatchedProductTable();
                                    return false;
                                }

                            });
                        }
                    });
        });

        $('#example tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');

                var asin = $('tr.selected td:eq(2)').text();
                var keyword = $('tr.selected td:eq(3)').text();

                doAjax(asin, keyword);

            }
        } );

        $('#button').click( function () {
            table.row('.selected').remove().draw( false );
        } );

        $('#rankToggle').click( function () {
            $("#bsrToggle").removeClass('activeToggle');
            $("#rankToggle").addClass('activeToggle');
            toggleGraphMode(true);
        } );

        $('#bsrToggle').click( function () {
            $("#rankToggle").removeClass('activeToggle');
            $("#bsrToggle").addClass('activeToggle');
            toggleGraphMode(false);
        } );


        if ("#example tbody tr :eq(")
        var asin = $("#example tbody tr td").eq(2).text();
        var keyword = $("#example tbody tr td").eq(3).text();

        doAjax(asin, keyword);
        $("#example tbody tr").first().addClass('selected');
//            $("#detailTitle").text('Details for "' + keyword + '" for ' + asin);

    } );

    function initWatchedProductTable() {
        $("#example").DataTable({
            data: watchedProduct,
            autoWidth: false,
            columnsDef: [

            ],
            columns: [
                { data: 'image' },
                { data: 'title' },
                { data: 'product' },
                { data: 'keyword' },
                { data: 'updated_at_human' },
                { data: 'remove' }
            ],
            "order": [[ 2, "desc" ]]

        });
    }


</script>

{{--Functions--}}
<script>

    function toggleGraphMode(type) {
        showRank = type;

        initDetailData();
    }

    function doAjax(asin, keyword) {
        $.ajax({
            url: "ajax/tableDetail?asin=" + asin + '&keyword=' + keyword
        })
                .done(function( r ) {
                    detailData = JSON.parse(r);

                    var noData = (detailData.table.length == 0);
                    if (noData) {
                        // Hide stuff and show a message
                        $("#detailDataRow").hide();
                        $("#detailDataNoData").show();
                        $(".asin").text(asin);
                        $(".keyword").text(keyword);

                    } else {
                        // Display the data!

                        // But first lets unhide everything, just in case its hidden
                        $("#detailDataRow").show();
                        $("#detailDataNoData").hide();

                        // Now we can show the data
                        $("#avgRank").text(detailData.tableMeta.avgRank);
                        $("#avgBsr").text(detailData.tableMeta.avgBsr);
                        initDetailData();
                        $("#prodImg").attr('src', detailData.image);
                        $("#prodTitle").text(detailData.title);
                        $("#prodAsin").text(asin);
//                        $("#prodAsin").text('ASIN: ' + asin);
                        $("#prodKeyword").text('Keyword: "' + keyword + '"');
                    }

                });
    }
</script>
{{--Init Detail Data--}}
<script>
    function initDetailData() {

        var detailTable = $("#detailTable");
        detailTable.DataTable().destroy();
        detailTable.DataTable({
            data: detailData.table,
//            renderer: "jqueryui",
            columnsDef: [

            ],
            columns: [
                { data: 'rank' },
                { data: 'bsr' },
                { data: 'created_at_date' }
            ],
            scrollY:        200,
            deferRender:    true,
            scroller:       true,
            "order": [[ 2, "desc" ]]
        });

        if (detailChart) {detailChart.destroy();}

        var tmpData;

        if (showRank) {
            tmpData = detailData.graph.rank_data;
        } else {
            tmpData = detailData.graph.bsr_data;
        }

        var ctx = $("#dataGraph").get(0).getContext("2d");
        var chart = {
            labels: detailData.graph.labels,
            datasets: [{
                data: tmpData,
                fillColor: "#EAEAEA",
                strokeColor: "#949494",
                pointColor: "#949494",
                scaleType: "number"
            }]};
                    detailChart = new Chart(ctx).Line(chart, {
                        responsive: true,
                        pointHitDetectionRadius : 5
                    } );

    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
<script src="https://cdn.datatables.net/scroller/1.3.0/js/dataTables.scroller.min.js"></script>
