<!DOCTYPE html>
<html lang="zh">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="img/favicon.png">
        <title>课程管理</title>
        <!-- Bootstrap core CSS -->
        <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
        <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
        <!--external css-->
        <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <!--dynamic table-->
        <link href="/admin_style/flatlab/assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" />
        <link href="/admin_style/flatlab/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
        <link rel="stylesheet" href="/admin_style/flatlab/assets/data-tables/DT_bootstrap.css" />
        <!--right slidebar-->
        <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
        <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
        <script src="/admin_style/flatlab/js/html5shiv.js"></script>
        <script src="/admin_style/flatlab/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <section id="container" class="">
            <!--header start-->
            @include('admin.common.header')
            <!--sidebar start-->
            @include('admin.common.sidebar')
            <!--sidebar end-->
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">
                    <!-- page start-->
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="placeholder" style="width: 100%; height: 450px;"></div>
                            <div id="overview" style="width: 100%; height: 150px;"></div>
                        </div>
                    </div>
                    <!-- page end-->
                </section>
            </section>
            <!--main content end-->
            <!--footer start-->
            @include('admin.common.footer')
            <!--footer end-->
        </section>
        <script src="/admin_style/flatlab/js/jquery.js"></script>
        <script src="/admin_style/flatlab/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="/admin_style/flatlab/js/jquery-migrate-1.2.1.min.js"></script>
        <script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
        <script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
        <script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
        <script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
        <!--right slidebar-->
        <script src="/admin_style/flatlab/js/slidebars.min.js"></script>
        <!--common script for all pages-->
        <script src="/admin_style/flatlab/js/common-scripts.js"></script>
        <script src="/admin_style/flatlab/assets/flot/jquery.flot.js"></script>
        <script src="/admin_style/flatlab/assets/flot/jquery.flot.crosshair.js"></script>
        <script src="/admin_style/flatlab/assets/flot/jquery.flot.time.js"></script>
        <script src="/admin_style/flatlab/assets/flot/jquery.flot.selection.js"></script>
        <script src="/admin_style/flatlab/assets/flot/jquery.flot.resize.js"></script>

        <script>
        $(function() {

            var d = {!! json_encode($chat) !!};

            // first correct the timestamps - they are recorded as the daily
            // midnights in UTC+0100, but Flot always displays dates in UTC
            // so we have to add one hour to hit the midnights in the plot

            for (var i = 0; i < d.length; ++i) {
                d[i][0] = (d[i][0] * 1000) + (8 * 60 * 60 * 1000);
            }

            // helper for returning the weekends in a period

            function weekendAreas(axes) {

                var markings = [];
                var d = new Date(axes.xaxis.min);
                d.setUTCSeconds(0);
                d.setUTCMinutes(0);
                var i = d.getTime();
                do {
                    markings.push({ xaxis: { from: i, to: i + 10 * 60 * 1000 } });
                    i += 30 * 60 * 1000;
                } while (i < axes.xaxis.max);

                return markings;
            }

            var options = {
                series: {
                    lines: { show: true }
                },
                crosshair: { mode: "x" },
                xaxis: {
                    mode: "time",
                    // timeformat: "%M:%S",
                    tickLength: 5
                },
                yaxis: {
                    minTickSize: 1,
                    tickDecimals: 0
                },
                selection: {
                    mode: "x"
                },
                grid: {
                    // backgroundColor: { colors: ["#000", "#999"] },
                    markings: weekendAreas,
                    hoverable: true,
                    autoHighlight: false
                }
            };
            var plot = $.plot("#placeholder", [{label: "           ", data: d}], options);

            var legends = $("#placeholder .legendLabel");

            legends.each(function () {
                // fix the widths so they don't jump around
                $(this).css('width', $(this).width());
            });

            var updateLegendTimeout = null;
            var latestPosition = null;

            function updateLegend() {

                updateLegendTimeout = null;

                var pos = latestPosition;

                var axes = plot.getAxes();
                if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||
                    pos.y < axes.yaxis.min || pos.y > axes.yaxis.max) {
                    return;
                }

                var i, j, dataset = plot.getData();
                for (i = 0; i < dataset.length; ++i) {

                    var series = dataset[i];

                    // Find the nearest points, x-wise

                    for (j = 0; j < series.data.length; ++j) {
                        if (series.data[j][0] > pos.x) {
                            break;
                        }
                    }
                    var y,
                        p1 = series.data[j - 1],
                        p2 = series.data[j];
                    y = p1[1];

                    legends.eq(i).text('在线: ' + y);
                }
            }

            $("#placeholder").bind("plothover",  function (event, pos, item) {
                latestPosition = pos;
                if (!updateLegendTimeout) {
                    updateLegendTimeout = setTimeout(updateLegend, 50);
                }
            });

            var overview = $.plot("#overview", [d], {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 1
                    },
                    shadowSize: 0
                },
                xaxis: {
                    ticks: [],
                    mode: "time",
                },
                yaxis: {
                    ticks: [],
                    min: 0,
                    autoscaleMargin: 0.1,
                },
                selection: {
                    mode: "x"
                }
            });

            // now connect the two

            $("#placeholder").bind("plotselected", function (event, ranges) {

                // do the zooming
                $.each(plot.getXAxes(), function(_, axis) {
                    var opts = axis.options;
                    opts.min = ranges.xaxis.from;
                    opts.max = ranges.xaxis.to;
                });
                plot.setupGrid();
                plot.draw();
                plot.clearSelection();

                // don't fire event on the overview to prevent eternal loop

                overview.setSelection(ranges, true);
            });

            $("#overview").bind("plotselected", function (event, ranges) {
                plot.setSelection(ranges);
            });
            // $(window).on('resize', function() {
            //     plot.resize();
            //     overview.resize();
            // });
        });
        </script>
    </body>
</html>