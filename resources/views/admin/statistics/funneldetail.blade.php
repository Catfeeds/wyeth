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
            align-items: center;
        }

        body {
            font-size: 13px;
            color: #636363;
        }

        p {
            margin-bottom: 0;
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
                        <div class="panel-body" style="padding-bottom: 90px;">
                            <div class="tab-content">
                                <section id="unseen">
                                    <div class="row-div"
                                         style="align-items: right; font-size: 18px; margin-bottom: 17px; margin-left: 5px;">
                                        <h4 style="color: #1387ff;">转化漏斗</h4>
                                        <h4>&nbsp;&nbsp;>&nbsp;&nbsp; </h4>
                                        <div class="row-div" style="align-items: center; margin-top: 1px;">
                                            <h4 id="start"></h4>
                                            <i class="fa fa-long-arrow-right"
                                               style="margin-left: 10px; margin-right: 10px;"></i>
                                            <h4 id="end"></h4>
                                        </div>
                                    </div>
                                    <div class="row-div col-lg-10" style="justify-content: space-between; padding-left: 10px;">
                                        <div>
                                            <h3 id="data1">0.00%</h3>
                                            <p>总体转化率</p>
                                        </div>
                                        <div>
                                            <h3 class="number" id="data2">1,980</h3>
                                            <p>总人数</p>
                                        </div>
                                        <div>
                                            <h3 class="number" id="data3">0</h3>
                                            <p>达成目标人数</p>
                                        </div>
                                        <div>
                                            <h3 class="number" id="data4">1,980</h3>
                                            <p>未达成目标人数</p>
                                        </div>
                                        <div>
                                            <h3 id="data5">第3步：B超单解读</h3>
                                            <p>转化率最低的步骤</p>
                                        </div>
                                    </div>
                                    <div class="row col-lg-12">
                                        <div style="padding: 30px 10px 8px 10px; margin-top: 20px;
                                        border-bottom:2px rgba(115,130,147,0.3) solid;">
                                            <h5 style="color: #585858">转化漏斗详情</h5>
                                        </div>
                                        {{--echarts柱状图--}}
                                        <div style="width: 1000px; height:500px;" id="bar"></div>
                                        <div style="margin-top: 20px;">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr style="border-top: 2px solid #ddd;">
                                                    <th>步骤</th>
                                                    <th>步骤名称</th>
                                                    <th>分析依据</th>
                                                    <th>用户数</th>
                                                    <th>较上一步转化率</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
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
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/admin_style/layer/layer.js"></script>
<script src="/admin_style/js/jqPaginator.js"></script>
<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
<script type="text/javascript">


    var data = JSON.parse('<?php echo json_encode($data);?>');
    data = data["0"].conversion

    for(var i=0;i<data.length;i++) {
        var item = data[i];
        if(i===0) {
            item["rate"]=(100).toFixed(2);
        } else {
            if(data[i-1].value===0) {
                item["rate"]=(0).toFixed(2);
            } else {
                item["rate"]=((data[i].value/data[i-1].value)*100).toFixed(2);
            }
        }
    }
    console.log(data);
    $("#start").text(data[0].step_name);
    $("#end").text(data[data.length-1].step_name);
    $("#data1").text(data[data.length-1].value===0||data[0].value===0?'0.00%':""+((data[data.length-1].value/data[0].value)*100).toFixed(2)+"%");
    $("#data2").text(data[0].value);
    $("#data3").text(data[data.length-1].value);
    $("#data4").text(data[0].value-data[data.length-1].value);
    var step_names = [];
    var rates = [];
    var min=data[0].rate;
    var idx=0;
    for(var i=0;i<data.length;i++){
        if(data[i].rate<min)
        {
            idx=i;
            min=data[i].rate;
        }
        step_names.push(data[i].step_name);
        rates.push(data[i].rate);
    }
    $("#data5").text("第"+(idx+1)+"步："+data[idx].step_name);

    // 路径配置
    require.config({
        paths: {
            echarts: 'http://echarts.baidu.com/build/dist'
        }
    });

    // 使用
    require(
        [
            'echarts',
            'echarts/chart/line',
            'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('bar'));

            var option = {
                tooltip: {
                    show: true,
                    formatter: function (params)//数据格式
                    {
                        relVal = " " + params[1] + " : &nbsp; " + params[2] + "% " + "<br/>";
                        return relVal;
                    }
                },
                legend: {
                    data:[]
                },
                xAxis : [
                    {
                        type : 'category',
                        data : step_names
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        axisLabel: {
                            formatter: '{value} %'
                        }
                    }
                ],
                series : [
                    {
                        "name": "柱状",
                        "type":"bar",
                        "data": rates,
                        "barGap":'50%', //两个柱子距离
                        "barWidth": 50,
                        itemStyle:{
                            normal:{
                                color:'#1387ff',   //柱状颜色
                                label : {
                                    show : true,  //柱头数字
                                    position : 'top',
                                    formatter: '{c}%',
                                    textStyle : {
                                        fontSize : '15',
                                        fontFamily : '微软雅黑',
                                        color: "#686868"
                                        // fontWeight : 'bold'
                                    }
                                }
                            }
                        }
                    },
                    {
                        name:'折线',
                        type:'line',
                        itemStyle : {  /*设置折线颜色*/
                            normal : {
                                color:'#1387ff'
                            }
                        },
                        data: rates
                    }
                ]
            };

            // 为echarts对象加载数据
            myChart.setOption(option);
        }
    );

    for (var i = 0; i < data.length; i++) {
        var trHtml = "<tr><td>" + (i+1) + "</td><td>" + data[i].step_name + "</td><td>"+data[i].event_id+"</td><td>" + data[i].value + "</td><td>" + data[i].rate + "%</td></tr>";
        $(".table").append(trHtml);
    }
</script>
</body>
</html>
