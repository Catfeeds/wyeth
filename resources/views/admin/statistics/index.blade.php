<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>惠氏妈妈微课堂</title>

    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datepicker/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-timepicker/compiled/timepicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-colorpicker/css/colorpicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/jquery-multi-select/css/multi-select.css"/>

    <!--dynamic table-->
    <link href="/admin_style/flatlab/assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet"/>
    <link href="/admin_style/flatlab/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/admin_style/flatlab/assets/data-tables/DT_bootstrap.css"/>
    <!--right slidebar-->
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet"/>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/admin_style/flatlab/js/html5shiv.js"></script>
    <script src="/admin_style/flatlab/js/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        .txt-div {
            margin-right: 65px;
        }

        .row-bot {
            display: flex;
            flex-direction: row;
            align-items: flex-end;
        }

        .txt-gray {
            color: dimgrey;
        }

        body {
            /*background-color: white;*/
            color: rgba(0, 0, 0, 0.7);
        }
    </style>
</head>

<body>
<section id="container" class="">
    <!--header start-->
<?php echo $header; ?><!--header end-->
    <!--sidebar start-->
<?php echo $sidebar;?>
<!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            今日数据概览
                        </header>
                        <div class="panel-body">
                            <div class="tab-content">
                                <section id="unseen">

                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th>类别</th>
                                            <th class="text-center">pv</th>
                                            <th class="text-center">uv</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr>
                                            <td>今日pv/uv</td>
                                            <td class="text-center"><?php echo isset($all['pv']) ? $all['pv'] : '-' ?></td>
                                            <td class="text-center"><?php echo isset($all['uv']) ? intval($all['uv']) : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <td>昨日pv/uv</td>
                                            <td class="text-center"><?php echo isset($yesterday['pv']) ? $yesterday['pv'] : "-" ?></td>
                                            <td class="text-center"><?php echo isset($yesterday['uv']) ? intval($yesterday['uv']) : "-" ?></td>
                                        </tr>
                                        <tr>
                                            <td>广告位点击</td>
                                            <td class="text-center"><?php echo isset($ad['pv']) ? $ad['pv'] : '-' ?></td>
                                            <td class="text-center"><?php echo isset($ad['pv']) ? $ad['pv'] : '-' ?></td>
                                        </tr>
                                        <!--
                                        <tr>
                                            <td>今日报名</td>
                                            <td class="text-center"></td>
                                            <td class="text-center">1572</td>
                                        </tr>
                                         -->
                                        <tr>
                                            <td>昨日模板消息推送量</td>
                                            <td class="text-center"
                                                colspan="2"><?php echo isset($yesterday_push['uv']) ? intval($yesterday_push['uv']) : "-" ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </section>
                            </div>
                        </div>
                    </section>
                    <section class="panel" style="padding-bottom: 70px;">
                        <div class="panel-body">
                            <div class="tab-content">
                                <section>
                                    <h4>应用概览</h4>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <ul class="row-bot">
                                                <li class="txt-div">
                                                    <div>
                                                        <h2><?php echo isset($total_device) ? $total_device : 0 ?></h2>
                                                        <h6 class="txt-gray">累计设备</h6>
                                                    </div>
                                                </li>
                                                <li class="txt-div">
                                                    <div class="row-bot">
                                                        <h2>0</h2>
                                                        <i class="glyphicon glyphicon-export" id="dubious-equip-icon"
                                                           style="margin-bottom: 13px; margin-left: 16px; color: limegreen;"></i>
                                                        <h5 style="margin-left: 3px;" class="txt-gray" id="dubious-equip">0%</h5>
                                                    </div>
                                                    <h6 style="margin-top:0px;" class="txt-gray">可疑设备</h6>
                                                </li>
                                                <li class="txt-div">
                                                    <div class="row-bot">
                                                        <h2><?php echo isset($week_device) ? $week_device : "-" ?></h2>
                                                        <i class="glyphicon glyphicon-import" id="week-rate-icon"
                                                           style="margin-bottom: 13px; margin-left: 16px; color: #FF0000;"></i>
                                                        <h5 style="margin-left: 3px;"
                                                            class="txt-gray"><?php echo isset($week_rate) ? $week_rate : "-" ?></h5>
                                                    </div>
                                                    <h6 style="margin-top:0px;" class="txt-gray">近7日活跃设备</h6>
                                                </li>
                                                <li class="txt-div">
                                                    <div class="row-bot">
                                                        <h2><?php echo isset($month_device) ? $month_device : 0 ?></h2>
                                                        <i class="glyphicon glyphicon-import" id="month-rate-icon"
                                                           style="margin-bottom: 13px; margin-left: 16px; color: #FF0000;"></i>
                                                        <h5 style="margin-left: 3px;"
                                                            class="txt-gray"><?php echo isset($month_rate) ? $month_rate : "-" ?></h5>
                                                    </div>
                                                    <h6 style="margin-top:0px;" class="txt-gray">近30日活跃设备</h6>
                                                </li>
                                                <li class="txt-div">
                                                    <div class="row-bot">
                                                        <h2>00:00:00</h2>
                                                        <i class="glyphicon glyphicon-export"
                                                           style="margin-bottom: 13px; margin-left: 16px; color: limegreen;" id="time-perday-icon"></i>
                                                        <h5 style="margin-left: 3px;" class="txt-gray" id="time-perday">0%</h5>
                                                    </div>
                                                    <h6 style="margin-top:0px;" class="txt-gray">近7日设备日均使用时长</h6>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <div class="tab-content">
                                <header class="panel-heading">
                                    <div class="row">
                                        <div class="col-lg-12 row-bot" style="padding-left: 0px;">
                                            <h5 style="margin-top: 30px; margin-left:0px;">时段分布</h5>
                                            {{--<h5 style="position: absolute; right:100px; bottom: 0px; color:deepskyblue;">
                                                查看详情</h5>--}}
                                            <i class="glyphicon glyphicon-save"
                                               style="position: absolute; top:28px; right:60px; bottom: 0px; color: rgba(0,0,0,0.4);font-size: 16px;" id="save-icon"></i>

                                            <div class="dropdown"
                                                 style="position: absolute; top:25px; right:30px; bottom: 0px; font-size: 16px;">
                                                <div id="dropdownMenu1" data-toggle="dropdown">
                                                    <i class="glyphicon glyphicon-question-sign"
                                                       style="color: rgba(0,0,0,0.4); font-size: 18px;"></i>
                                                </div>
                                                <ul class="dropdown-menu"
                                                    style="margin-left: -450px;font-size: 12.5px; width:475px; padding: 15px 15px; color:#000000">
                                                    <li>
                                                        <p>数据指标说明</p>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <p>新增设备:
                                                            今昨两天,每小时首次安装应用的设备数。一台设备,只在首次安装应用时计作新增设备,重复安装应用的设备不会重复计量。
                                                            <br><br>
                                                            启动次数: 今昨两天,每小时应用被启动的总次数。
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </header>
                                <div class="row">
                                    <div class="col-lg-12 row-bot">
                                        <div style="padding-top: 20px;">
                                            <div class="row-bot">
                                                <ul class="nav nav-tabs">
                                                    <li onclick="viewDeviceNum()"><a href="#equipment"
                                                                                     data-toggle="tab">新增设备</a></li>
                                                    <li onclick="viewStartNum()"><a href="#num"
                                                                                    data-toggle="tab">启动次数</a></li>
                                                </ul>
                                                <div style="position: absolute; right: 50px;">
                                                    <i class="glyphicon glyphicon-resize-full"
                                                       style="color: #6eaaee" id="linechart-icon"></i>
                                                    <i class="fa fa-list" style="color: darkgrey; margin-left: 20px;"
                                                       id="table-icon"></i>
                                                </div>
                                            </div>
                                            <div class="tab-content" style="margin-top: 20px;">
                                                <div class="tab-pane fade in active" id="equipment">
                                                    <div id="device_addnum" style="width:1100px;height:500px;"></div>
                                                    <div id="device_div">
                                                        <table id="device_table" style="width:900px;"
                                                               class="table">
                                                            <tbody class="text-center">
                                                            <tr>
                                                                <td>时间段</td>
                                                                <td>今日新增</td>
                                                                <td>昨日新增</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <ul class="pagination" id="pagination1"
                                                            style="position: absolute; right:30px; margin-top: 30px;"></ul>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="num">
                                                    <div id="starts_num" style="width:1100px;height:500px;"></div>
                                                    <div id="starts_div">
                                                        <table id="starts_table" style="width:900px;"
                                                               class="table">
                                                            <tbody class="text-center">
                                                            <tr>
                                                                <td>时间段</td>
                                                                <td>今日启动</td>
                                                                <td>昨日启动</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <ul class="pagination" id="pagination2"
                                                            style="position: absolute; right:30px; margin-top: 30px;"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->
    <!--footer start-->
<?php echo $footer;?>
<!--footer end-->
</section>


<!-- js placed at the end of the document so the pages load faster -->


<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/admin_style/flatlab/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript"
        src="/admin_style/flatlab/assets/advanced-datatable/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/data-tables/DT_bootstrap.js"></script>
<script src="/admin_style/flatlab/js/respond.min.js"></script>

<script type="text/javascript" src="/admin_style/flatlab/assets/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.quicksearch.js"></script>

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>


<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>
<script src="/admin_style/js/jqPaginator.js"></script>
<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>

<script type="text/javascript">
    var form_flag = 0;
    var content_flag = 0;
    var new_add_yesterday = Object.values(eval("<?php echo json_encode($new_add_yesterday);?>"));
    var new_add_today = Object.values(eval("<?php echo json_encode($new_add_today);?>"));
    var time = ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00"
        , "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"]


    $('#week-rate-icon')["0"].style.color = parseFloat("<?php echo $week_rate;?>") >= 0?'limegreen':'#FF0000';
    $('#week-rate-icon')["0"].className = parseFloat("<?php echo $week_rate;?>") >= 0?'glyphicon glyphicon-export':'glyphicon glyphicon-import';
    $('#month-rate-icon')["0"].style.color = parseFloat("<?php echo $month_rate;?>") >= 0?'limegreen':'#FF0000';
    $('#month-rate-icon')["0"].className = parseFloat("<?php echo $month_rate;?>") >= 0?'glyphicon glyphicon-export':'glyphicon glyphicon-import';
    $('#dubious-equip-icon')["0"].style.color = parseInt($("#dubious-equip").text()) >= 0?'limegreen':'#FF0000';
    $('#dubious-equip-icon')["0"].className = parseInt($("#dubious-equip").text()) >= 0?'glyphicon glyphicon-export':'glyphicon glyphicon-import';
    $('#time-perday-icon')["0"].style.color = parseInt($("#time-perday").text()) >= 0?'limegreen':'#FF0000';
    $('#time-perday-icon')["0"].className = parseInt($("#time-perday").text()) >= 0?'glyphicon glyphicon-export':'glyphicon glyphicon-import';


    // 下载数据报表csv
    $("#save-icon").on('click',function () {

    })

    // echarts路径配置
    require.config({
        paths: {
            echarts: 'http://echarts.baidu.com/build/dist'
        }
    });
    viewDeviceNum();


    //新增设备图表
    function viewDeviceNum() {

        content_flag = 0;

        if (form_flag === 0) {
            // 新增设备，折线图形式呈现
            $('#device_addnum').show();
            $('#device_div').hide();
            require(
                [
                    'echarts',
                    'echarts/chart/line' // 加载line模块，按需加载
                ],
                function (ec) {
                    // 基于准备好的dom，初始化echarts图表
                    var myChart = ec.init(document.getElementById('device_addnum'));

                    var option = {
                        label: {
                            normal: {
                                show: true
                            }
                        },
                        tooltip: {
                            show: true,
                            enterable: true,
                            trigger: 'axis',
                            axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                                type: 'line'        // 默认为直线，可选为：'line' | 'shadow'
                            },
                            formatter: function (params)//数据格式
                            {
                                var relVal = params[0].name + "<br/><br/>";
                                relVal += " " + params["0"].seriesName + " : &nbsp;&nbsp;  " + params["0"].value + " " + "<br/>";
                                relVal += " " + params["1"].seriesName + " : &nbsp;&nbsp;  " + params["1"].value + " " + "<br/>";
                                return relVal;
                            }
                        },
                        color: ["#6eaaee", "#05eeb3"],
                        legend: {
                            data: ['今日新增', '昨日新增'],
                            x: "right"
                        },
                        xAxis: [
                            {
                                splitLine: {
                                    show: true,
                                    lineStyle: {
                                        color: ["#eaeaea"]
                                    }
                                },//网格线
                                splitArea: {show: false},
                                type: 'category',
                                data: time
                            }
                        ],
                        yAxis: [
                            {
                                splitLine: {
                                    show: true,
                                    lineStyle: {
                                        color: ["#eaeaea"]
                                    }
                                },//网格线
                                splitArea: {show: false},
                                type: 'value'
                            }
                        ],
                        series: [
                            {
                                "name": "今日新增",
                                "type": "line", // 折线
                                "symbol": "none", // 去掉点
                                "smooth": true, // 曲线平滑
                                "data": new_add_today,
                                "itemStyle": {
                                    "normal": {
                                        "lineStyle": {
                                            "color": "#6eaaee",
                                            "width": 3
                                        }
                                    }
                                }
                            },
                            {
                                "name": "昨日新增",
                                "type": "line", // 折线
                                "symbol": "none", // 去掉点
                                "smooth": true, // 曲线平滑
                                "data": new_add_yesterday,
                                "itemStyle": {
                                    "normal": {
                                        "lineStyle": {
                                            "color": "#05eeb3",
                                            "width": 3
                                        }
                                    }
                                }
                            }
                        ]
                    };
                    // 为echarts对象加载数据
                    myChart.setOption(option);
                }
            );
        } else {
            // 新增设备，表格形式呈现
            $('#device_addnum').hide();
            $('#device_div').show();
        }
    }

    // 启动次数图表
    function viewStartNum() {
        content_flag = 1;
        if (form_flag === 0) {
            // 启动次数，折线形式呈现
            $('#starts_num').show();
            $('#starts_div').hide();
            require(
                [
                    'echarts',
                    'echarts/chart/line' // 加载line模块，按需加载
                ],
                function (ec) {
                    // 基于准备好的dom，初始化echarts图表
                    var myChart = ec.init(document.getElementById('starts_num'));

                    var option = {
                        label: {
                            normal: {
                                show: true
                            }
                        },
                        tooltip: {
                            show: true,
                            enterable: true,
                            trigger: 'axis',
                            axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                                type: 'line'        // 默认为直线，可选为：'line' | 'shadow'
                            },
                            formatter: function (params)//数据格式
                            {
                                var relVal = params[0].name + "<br/><br/>";
                                relVal += " " + params["0"].seriesName + " :   " + params["0"].value + " " + "<br/>";
                                relVal += " " + params["1"].seriesName + " :   " + params["1"].value + " " + "<br/>";
                                return relVal;
                            }
                        },
                        color: ["#6eaaee", "#05eeb3"],
                        legend: {
                            data: ['今日新增', '昨日新增'],
                            x: "right"
                        },
                        xAxis: [
                            {
                                splitLine: {
                                    show: true,
                                    lineStyle: {
                                        color: ["#eaeaea"]
                                    }
                                },//网格线
                                splitArea: {show: false},
                                type: 'category',
                                data: time
                            }
                        ],
                        yAxis: [
                            {
                                splitLine: {
                                    show: true,
                                    lineStyle: {
                                        color: ["#eaeaea"]
                                    }
                                },//网格线
                                splitArea: {show: false},
                                type: 'value'
                            }
                        ],
                        series: [
                            {
                                "name": "今日新增",
                                "type": "line", // 折线
                                "symbol": "none", // 去掉点
                                "smooth": true, // 曲线平滑
                                "data": [],
                                "itemStyle": {
                                    "normal": {
                                        "lineStyle": {
                                            "color": "#6eaaee",
                                            "width": 3
                                        }
                                    }
                                }
                            },
                            {
                                "name": "昨日新增",
                                "type": "line", // 折线
                                "symbol": "none", // 去掉点
                                "smooth": true, // 曲线平滑
                                "data": [],
                                "itemStyle": {
                                    "normal": {
                                        "lineStyle": {
                                            "color": "#05eeb3",
                                            "width": 3
                                        }
                                    }
                                }
                            }
                        ]
                    };

                    // 为echarts对象加载数据
                    myChart.setOption(option);
                }
            );
        } else {
            // 启动次数，表格形式呈现
            $('#starts_num').hide();
            $('#starts_div').show();
        }
    }

    // 折线图图标点击
    $('#linechart-icon').on('click', function () {
        $(this).css("color", "#6eaaee")
        $('#table-icon').css("color", "darkgrey")
        form_flag = 0;
        content_flag === 0 ? viewDeviceNum() : viewStartNum()
    })
    // 表格图标点击
    $('#table-icon').on('click', function () {
        $(this).css("color", "#6eaaee")
        $('#linechart-icon').css("color", "darkgrey")
        form_flag = 1;
        content_flag === 0 ? viewDeviceNum() : viewStartNum()
    });


    $('#pagination1').jqPaginator({
        totalCounts: 24,
        pageSize: 10,
        visiblePages: 10,
        currentPage: getPage(),
        onPageChange: function (num, type) {
            $("#device_table").html("");
            var theadHtml = "<tr><th>" + "时间段" + "</th><th>" + "今日新增" + "</th><th>" + "昨日新增" + "</th></tr>";
            $("#device_table").append(theadHtml);
            for (var i = 10 * (num - 1); i < (num === 3 ? 24 : 10 * num); i++) {
                var trHtml = "<tr><td>" + time[i] + "</td><td>" + new_add_today[i] + "</td><td>" + new_add_yesterday[i] + "</td></tr>";
                $("#device_table").append(trHtml);
            }
        }
    });
    // $('#pagination2').jqPaginator({
    //     totalCounts: 0,
    //     totalPages: 0,
    //     pageSize: 10,
    //     visiblePages: 10,
    //     currentPage: getPage(),
    //     onPageChange: function (num, type) {
    //         $("#starts_table").html("");
    //         var theadHtml = "<tr><th>" + "时间段" + "</th><th>" + "今日新增" + "</th><th>" + "昨日新增" + "</th></tr>";
    //         $("#device_table").append(theadHtml);
    //     }
    // });


    function getPage() {
        var reg = new RegExp("(^|&)" + 'page' + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) {
            return parseInt(unescape(r[2]));
        } else {
            return 1;
        }
    }
</script>
</body>
</html>
