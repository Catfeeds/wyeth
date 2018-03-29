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
        .row-div {
            display: flex;
            flex-direction: row;
            align-items: flex-end;
        }

        body {
            font-size: 13px;
        }

        .right-div {
            position: absolute;
            right: 20px;
        }
    </style>
</head>

<body>
<section id="container">
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

                        <div class="panel-body">
                            <div class="tab-content">
                                <section id="unseen">
                                    <div class="row-div" style="align-items: right; font-size: 17px;">
                                        <span style="color: #19a9ff; margin-top: 2px;">事件分析</span>
                                        <span>&nbsp;&nbsp;>&nbsp;&nbsp; </span>
                                        <div class="dropdown">
                                            <div id="dropdownMenu1" data-toggle="dropdown" class="row-div"
                                                 style="align-items: center; margin-top: 1px;">
                                                <p></p>
                                                <i class="fa fa-angle-down"
                                                   style="margin-left:6px; margin-top: -7px;"></i>
                                            </div>
                                            <ul class="dropdown-menu" id="dropdown-menu1"
                                                style="height: 350px; overflow: scroll;">
                                            </ul>
                                        </div>
                                    </div>

                                    <div style="margin-top: 20px;">
                                        <div class="row-div col-lg-12">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab"
                                                                      onclick="click1()">消息数量</a></li>
                                                <li><a data-toggle="tab" onclick="click2()">消息数/启动次数</a>
                                                </li>
                                                <li><a data-toggle="tab" onclick="click3()">独立用户数</a></li>
                                            </ul>
                                            <div class="right-div row-div" style="right: 20px; align-items: center;">
                                                <label><input type="checkbox" name="checkBox"
                                                              value="compare" id="checkbox" checked>&nbsp;对比事件</label>


                                                <div class="dropdown">
                                                    <div id="dropdownMenu2" data-toggle="dropdown" class="row-div">
                                                        <label style="margin-left: 20px; color: #19a9ff; display: flex; align-items: center;">
                                                            <i class="fa fa-bars"></i>
                                                            <p style="margin:7px 5px 10px;"></p>
                                                        </label>
                                                    </div>
                                                    <ul class="dropdown-menu" id="dropdown-menu2"
                                                        style="margin-left: -60px; width:210px; height: 350px; overflow: scroll;">
                                                    </ul>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="tab-content" style="margin-top: 20px;">
                                            <div class="tab-pane fade in active">
                                                <div id="echarts_post1" style="width:1100px;height:500px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--事件详情--}}
                                    <div class="col-lg-12" style="padding-bottom: 80px;">
                                            <div class="row-div" style="padding: 30px 10px 10px 0; ">
                                                <h5>事件详情</h5>
                                                <i class="fa fa-arrow-circle-o-down"
                                                   style="position: absolute; top: 32px; right:65px; color: grey; font-size: 16px;"></i>
                                                <div class="dropdown" style="position: absolute; top: 32px; right:25px;">
                                                    <i class="fa fa-question-circle" id="dropdownMenu"
                                                       class="dropdown-toggle" data-toggle="dropdown"
                                                       style="color: grey; font-size: 17px;"></i>
                                                    <ul class="dropdown-menu" style="position: absolute; top: 30px; left:-250px; font-size: 12.5px; width:300px; padding: 15px 15px; color:rgba(0,0,0,0.8)">
                                                        <li>
                                                            <p>数据指标说明</p>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <p> 事件数: 所选日期内,事件发生的次数。 事件达成用户数: 所选日期内,触发某事件的设备数。
                                                                <br><br>
                                                                每活跃用户发生: 所选日期内,平均每个活跃设备中触发了该事件的设备的比例。
                                                                <br><br>
                                                                每启动发生数: 所选日期内,平均每次使用应用期间触发该事件的次数。
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>


                                        </div>
                                        <table class="table table-condensed table-bordered table-striped"
                                               id="table-detail">
                                            <thead>
                                            <tr>
                                                <td>日期</td>
                                                <td>消息数量</td>
                                                <td>消息数/启动次数</td>
                                                <td>独立用户数</td>
                                                {{--<td>消息数/独立用户数</td>--}}
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>

                                        </table>

                                        <div style="display: flex; flex-direction: row">
                                            <ul class="pagination"
                                                style="position: absolute; right:20px; margin-top: 18px;"></ul>
                                        </div>
                                    </div>

                                </section>
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
<script src="/js/bootbox.4.4.0.min.js"></script>
<script src="/admin_style/js/jqPaginator.js"></script>
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
<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
<script type="text/javascript">
    // 路径配置
    require.config({
        paths: {
            echarts: 'http://echarts.baidu.com/build/dist'
        }
    });

    $('#dropdownMenu1 i')["0"].style.visibility = "hidden";
    var arr = (window.location.href).split('/');
    var this_event = "";// 事件名
    var this_event_idx = arr[arr.length - 1].split('?')[0];
    var event_num = 0; // 事件数量
    var eventList = {};

    var ano_event = '课程页的PVUV';// 对比事件名
    var ano_event_idx = 'page_end';
    $('#dropdownMenu2 label p').text(ano_event); // 对比事件名
    getEventList();
    var type = 0; //0:消息数量  1：消息数/启动次数 2：独立用户数
    // xlist: 横坐标数组,这里是日期  ylist1：当前事件纵坐标值数组  ylist2：对比事件纵坐标值数组
    var xlist = [];
    getXlist(this_event_idx);
    // 根据type,this_event,ano_event请求ylist1,ylist2
    var ylist1 = [];
    var ylist2 = [];
    var pv_list = [];
    var uv_list = [];
    getYlist(this_event_idx, type).then(function (ylist) {
        ylist1 = ylist;
        pv_list = ylist;
        console.log('y1', ylist1);

    })
    getYlist(this_event_idx, 2).then(function (ylist) {
        uv_list = ylist;
        console.log('y1', ylist1);

    })
    getYlist(ano_event_idx, type).then(function (ylist) {
        ylist2 = ylist;
        console.log('y2', ylist2);
        setcharts(xlist, ylist1, ylist2);
        $('#table-detail tbody').html("");
        for (var i = 0; i < 10; i++) {
            var trHtml = "<tr><td>" + xlist[i] + "</td><td>" + pv_list[i] + "</td><td>" + "--" + "</td><td>" + uv_list[i] + "</td></tr>";
            $("#table-detail").append(trHtml);
        }
    })

    function getEventList() {
        $.get("/admin/event/get_list", {}, function (data) {
            console.log(data);
            var keys = Object.keys(data)
            event_num = keys.length;
            eventList = data;
            this_event = data[this_event_idx];
            $('#dropdownMenu1 p').text(this_event);
            $('#dropdownMenu1 i')["0"].style.visibility = "visible";
            for (var i = 0; i < event_num; i++) {
                var aHtml = "<li><a>" + data[keys[i]] + "</a></li>";
                $('#dropdown-menu1').append(aHtml);
                $('#dropdown-menu2').append(aHtml);
            }
        }, 'json');
    }

    function click1() {
        type = 0;
        // 根据type,this_event,ano_event请求ylist1,ylist2
        getYlist(this_event_idx, type).then(function (ylist) {
            ylist1 = ylist;
            pv_list = ylist;
        })
        getYlist(ano_event_idx, type).then(function (ylist) {
            ylist2 = ylist;
            setcharts(xlist, ylist1, ylist2);
        })
    }

    function click2() {
        type = 1;
        ylist1 = [];
        ylist2 = [];
        setcharts(xlist, ylist1, ylist2);
    }

    function click3() {
        type = 2;
        // 根据type,this_event,ano_event请求ylist1,ylist2
        getYlist(this_event_idx, type).then(function (ylist) {
            ylist1 = ylist;
        })
        getYlist(ano_event_idx, type).then(function (ylist) {
            ylist2 = ylist;
            setcharts(xlist, ylist1, ylist2);
        })
    }

    function getXlist(id) {
        $.get("/admin/event/get_detail/" + id, {id: id}, function (data) {
            xlist = Object.keys(data.data);
            xlist.reverse();
            // 分页页标
            $('.pagination').jqPaginator({
                totalCounts: xlist.length,
                pageSize: 10,
                visiblePages: 10,
                currentPage: getPage(),
                onPageChange: function (num, type) {
                    $('#table-detail tbody').html("");
                    if (ylist1.length !== 0) {
                        for (var i = 10 * (num - 1); i < (num === 3 ? ylist1.length : 10 * num); i++) {
                            var trHtml = "<tr><td>" + xlist[i] + "</td><td>" + pv_list[i] + "</td><td>" + "--" + "</td><td>" + uv_list[i] + "</td></tr>";
                            $("#table-detail").append(trHtml);
                        }
                    }
                }
            });
        }, 'json')
    }

    function getYlist(id, type) {
        if (type === 0 || type === 2) {
            var ylist = [];
            $.get("/admin/event/get_detail/" + id, {id: id}, function (data) {
                if(type === 0){
                    ylist = Object.values(data.pv_data);
                }else if(type === 1){
                    ylist = Object.values(data.data);
                }else if(type === 2){
                    ylist = Object.values(data.data);
                }
                ylist.reverse();
                console.log("正在请求ylist", ylist.join());
            }, 'json');
            var promise = new Promise(function (resolve, reject) {
                setTimeout(function () {
                    console.log('ylist', id, ylist);
                    resolve(ylist);
                }, 4000);
            });
            return promise;
        } else {
            setTimeout("return [];", 1000);
        }
    }

    // 选择事件
    $(document).on("click", '#dropdown-menu1 li', function () {
        $('#dropdownMenu1 p').text($(this).text());
        var keys = Object.keys(eventList);
        for (var i = 0; i < event_num; i++) {
            if (eventList[keys[i]] === $(this).text()) {
                this_event_idx = keys[i];
            }
        }
        this_event = $(this).text();
        getYlist(this_event_idx, 2).then(function (ylist) {
            uv_list = ylist;
        })
        // 请求ylist1
        getYlist(this_event_idx, type).then(function (ylist) {
            ylist1 = ylist;
            pv_list = ylist;
            setcharts(xlist, ylist1, ylist2);
            // 重置分页页标
            $('.pagination').jqPaginator({
                totalCounts: xlist.length,
                pageSize: 10,
                visiblePages: 10,
                currentPage: getPage(),
                onPageChange: function (num, type) {
                    $('#table-detail tbody').html("");
                    if (ylist1.length !== 0) {
                        for (var i = 10 * (num - 1); i < (num === 3 ? ylist1.length : 10 * num); i++) {
                            var trHtml = "<tr><td>" + xlist[i] + "</td><td>" + pv_list[i] + "</td><td>" + "--" + "</td><td>" + uv_list[i] + "</td></tr>";
                            $("#table-detail").append(trHtml);
                        }
                    }
                }
            });
        });
    });

    // 选择对比事件
    $(document).on("click", '#dropdown-menu2 li', function () {
        if ($('#checkbox')["0"].checked === true) {
            $('#dropdownMenu2 label p').text($(this).text());
            var keys = Object.keys(eventList);
            for (var i = 0; i < event_num; i++) {
                if (eventList[keys[i]] === $(this).text()) {
                    ano_event_idx = keys[i];
                }
            }
            ano_event = $(this).text();
            // 请求ylist2
            getYlist(ano_event_idx, type).then(function (ylist) {
                ylist2 = ylist;
                setcharts(xlist, ylist1, ylist2);
            })
        }
    });

    // click对比事件的input框
    $('#checkbox').on("click", function () {
        if(ano_event = $(this)["0"].checked === false) {
            ano_event = "";
            setcharts(xlist, ylist1, []);
        } else {
            getYlist(ano_event_idx, type).then(function (ylist) {
                ylist2 = ylist;
                setcharts(xlist, ylist1, ylist2);
            })
        }
    })


    function getPage() {
        var reg = new RegExp("(^|&)" + 'page' + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) {
            return parseInt(unescape(r[2]));
        } else {
            return 1;
        }
    }


    function setcharts(xlist, ylista, ylistb) {
        require(
            [
                'echarts',
                'echarts/chart/line' // 加载line模块，按需加载
            ],
            function (ec) {
                // 基于准备好的dom，初始化echarts图表
                var myChart = ec.init(document.getElementById('echarts_post1'));
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
                            if (params.length === 2) {
                                relVal += " " + params["1"].seriesName + " : &nbsp;&nbsp;  " + params["1"].value + " " + "<br/>";
                            }
                            return relVal;
                        }
                    },
                    color: ["#6eaaee", "#05eeb3"],
                    legend: {
                        data: this_event === ano_event ? [this_event] : [this_event, ano_event],
                        x: "center"
                    },
                    xAxis: [
                        {
                            splitLine: {
                                show: true,
                                lineStyle: {
                                    color: ["#eaeaea"]
                                }
                            },
                            //网格线
                            splitArea: {show: false},
                            type: 'category',
                            data: xlist,
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
                            "name": this_event,
                            "type": "line", // 折线
                            "symbol": "none", // 去掉点
                            "smooth": true, // 曲线平滑
                            "data": ylista,
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
                            "name": ano_event,
                            "type": "line", // 折线
                            "symbol": "none", // 去掉点
                            "smooth": true, // 曲线平滑
                            "data": ylistb,
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
                if (this_event === ano_event || ano_event === "")
                    option.series.splice(1, 1);
                myChart.setOption(option);
            }
        );
    }

</script>

</body>
</html>
