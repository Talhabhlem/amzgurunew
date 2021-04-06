var graph_style = 'solid';

function setParam(params, name, value) {
    if (value != '') {
        params[name] = value;
    }
}

function generate_daily_chart(param)
{
    var args = 'sku=' + param + '&from_date=' + $("#from_date").val() + '&to_date=' + $("#to_date").val();
    $.ajax({                 headers: { 'X-CSRF-TOKEN' : jQuery('meta[name="_token"]').attr('content') },
        type: 'POST' , url: "analysis/daily_graph" , data: args , async: false , success: function (resp)  {
            build_jqplot(resp);
        }});
}

function generate_weekly_chart(param) {

    var args = 'sku=' + param + '&from_date=' + $("#from_date").val() + '&to_date=' + $("#to_date").val();
    $.ajax({                headers: { 'X-CSRF-TOKEN' : jQuery('meta[name="_token"]').attr('content') },
        type: 'POST' , url: "analysis/chart_of_week" , data: args , async: false , success: function (resp)  {
            build_jqplot(resp);
        }});
}

function build_jqplot(resp) {

    var sale = resp;

    var title;
    var salesUrl;
    var saleFormat= '<div class="inline-block; background:#FFF;"><strong>Day</strong> :%s<br /><strong style="color:#62a8ea;">Units Sold: </strong>%s<br/> <strong>Pending: </strong>(%s)<br/></div>';
    var priceFormat= 'Day :%s<br /><strong style="color:#46be8a;">Average:</strong> %s<br /><strong>Price:</strong> %s<br />';
    var eventFormat= '<strong>Day :</strong>%s<br /><span style="display:none;">%s</span><span style="display:inline-block; background:#FFF; color:#57c7d4;"><strong>Event:</strong> %s</span><br/>';
    var url;
    var params = {};


    all_data = jQuery.parseJSON(sale);
    console.log(all_data);
    //alert(sale);
    var cost_data = null;
    $.jqplot.config.enablePlugins = true;
    $('#jqplot_chart_area').empty();
    // $('#chartView').removeData('modal');
    $('#jqplot_chart_area').data('cost', all_data['price']);
    $('#chartView').data('sales', all_data['sales']);
    graph_style = all_data['line_pattern'];
    //alert($("#from_date").val());
    //alert($("#to_date").val());
    setParam(params, 'from_date', $("#from_date").val().trim());
    setParam(params, 'to_date', $("#to_date").val().trim());
    //alert(params['from_date']);
    var rs = $('#chartView').data('sales');

    var max = Math.ceil((Math.max.apply(Math, all_data['sales'].map(function (v) {
                            return v[1];
                        }))) / 10) * 10;
    if (max == 0)
        max = 10;
    if (Math.floor(max) != max)
        max = max + 10;
    $('#jqplot_chart_area').data('maxsales', max)


    var max = Math.ceil((Math.max.apply(Math, all_data['price'].map(function (v) {return v[1]; }))) / 10) * 10;
    if (max == 0)
        max = 10;
    var maxprice = max;
    var cost = parseFloat($('#jqplot_chart_area').data('cost')).toFixed(2);

    if (!isNaN(cost))
        title += '  (cost: $' + cost + ')';
    plot = $.jqplot('jqplot_chart_area', [all_data['price'], all_data['sales'], all_data['events']], {
        animate: true
        , title: ""
        , legend: {
            show: true
            , labels: [
                '<span style="color:#46be8a; font-weight: bold; ;">Unit Prices</span>',
                '<span style="color:#62a8ea; font-weight: bold; ;">No Of Units</span>',
                '<span style="color:#57c7d4; font-weight: bold; ;">Events</span>'
            ]
            , location: 'nw'
            , placement: 'outsideGrid'
        }
        , axesDefault: {
            show: false
        }
        , axes: {

            xaxis: {
                renderer: $.jqplot.DateAxisRenderer
                , tickRenderer: $.jqplot.CanvasAxisTickRenderer
                , tickOptions: {
                    angle: -30
                }
                , padMin: 1000

            }

            , yaxis: {
                min: 0
                , max: maxprice
                , numberTicks: 11
                , tickOptions: {
                    formatString: '$%1.2f'
                    , gridLineWidth: 0
                }

            }
            , y2axis: {
                min: 0
                , max: $('#jqplot_chart_area').data('maxsales') + 2
                , numberTicks: 11
                , tickOptions: {
                    formatString: '%1.0f'
                    , gridLineWidth: 0
                }
            }
        }
        ,

        seriesDefaults:{
            rendererOptions: {
                smooth: true
            }
        }
        , seriesColors: ['#46be8a', '#62a8ea', '#57c7d4']
        , series: [
            {
                yaxis: 'yaxis'
                , showLine: true
                , linePattern:'solid'
                , highlighter: {
                show: false
                , yvalues: 3
                , formatString: priceFormat
                , tooltipLocation: 'ne'
            },
                markerOptions: { style:'filledCircle', size:10 }
            }
            , {
                yaxis: 'y2axis'
                , showLine: true
                , linePattern:'solid'
                , highlighter: {
                    show: false
                    , yvalues: 3
                    , formatString: saleFormat
                    , tooltipLocation: 'ne'
                },
                markerOptions: { style:'filledCircle', size:10 }
            } , {
                yaxis: 'y2axis' ,
                showLine: false
                , highlighter: {
                    show: false
                    , yvalues: 3
                    , formatString: eventFormat
                    , tooltipLocation: 'ne'
                },
                markerOptions: { style:'filledCircle', size:10 }

            }
        ]
    });

    //$("#chartView").modal('show');
    plot.replot();

}
