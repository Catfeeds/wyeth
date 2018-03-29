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
            margin-bottom: 0px;
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
                        <div class="panel-body" style="padding-bottom: 90px; background-color: #f1f2f7;">
                            <div class="tab-content">
                                <section id="unseen">
                                    <div class="row-div"
                                         style="align-items: right; font-size: 18px; margin-bottom: 17px; margin-left: 5px;">
                                        <span style="color: #1387ff; margin-top: 2px;">转化漏斗</span>
                                        <span>&nbsp;&nbsp;>&nbsp;&nbsp; </span>
                                        <div style="align-items: center; margin-top: 1px;">
                                            <p>编辑转化漏斗</p>
                                        </div>
                                    </div>
                                    <div style="padding: 35px; background-color: #ffffff; margin-top: 5px; border: 1px rgba(115,130,147,0.2) solid; border-radius: 4px;">
                                        <div class="row-div"
                                             style="border-bottom: 2px rgba(115,130,147,0.3) solid; padding-bottom: 35px; margin-top: 5px;">
                                            <h5 style="width: 105px;">转化漏斗名称</h5>
                                            <input type="text" class="form-control" placeholder="请输入" name="funnelnm"
                                                   style="width: 200px; border-radius: 12px">
                                        </div>
                                        <div style="padding-top: 25px; margin-bottom: 20px;">
                                            <p>以用户逐步访问的页面或触发的事件为依据，构造转化漏斗，系统将逐步过滤，计算出用户在整个过程中的转化率。</p>
                                        </div>
                                        <div style="border-bottom: 2px rgba(115,130,147,0.3) solid; padding-bottom: 35px;">
                                            <ul id="stepUl">
                                                <li id="idx1">
                                                    <div class="row-div" style="padding:12px 20px 12px 20px;
                                                    background-color: rgba(241,242,247,0.7); border-radius:4px; width:840px;">
                                                        <h5 id="stepname">步骤1</h5>
                                                        <p style="margin-left: 65px; color: red">*&nbsp;</p>
                                                        <h5>分析依据</h5>
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                    class="btn dropdown-toggle row-div"
                                                                    id="dropdownMenu1" data-toggle="dropdown"
                                                                    style="margin-left: 13px; border:1px rgba(115,130,147,0.2) solid; border-radius: 8px; background-color: white;">
                                                                <input placeholder="选择用户触发事件" name="event"
                                                                       style="border: 0 solid white; margin-bottom: 0;  width: 160px; text-align: left">
                                                                <span class="caret"
                                                                      style="margin-left:7px;"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" id="dropdown-menu1"
                                                                style="min-width: auto;left:13px; height: 350px; overflow: scroll;">
                                                                {{--<li>
                                                                    <a tabindex="-1">actionTimeEnd</a>
                                                                </li>
                                                                <li>
                                                                    <a tabindex="-1">actionTimeStart</a>
                                                                </li>
                                                                <li>
                                                                    <a tabindex="-1">audio_jump</a>
                                                                </li>
                                                                <li>
                                                                    <a tabindex="-1">audio_pause</a>
                                                                </li>
                                                                <li>
                                                                    <a tabindex="-1">audio_play</a>
                                                                </li>--}}
                                                            </ul>
                                                        </div>

                                                        <h5 style="margin-left: 65px;">步骤名称</h5>
                                                        <input type="text" class="form-control" placeholder="步骤名称"
                                                               id="content1"
                                                               style="margin-left: 15px; width: 180px;height:30px; border-radius: 10px"/>
                                                        <i class="fa fa-minus-square-o" id="rmstep"
                                                           onclick="deleteStep(1)"
                                                           style="margin-left: 60px; font-size: 17px; color: rgba(115,130,147,0.7)"></i>
                                                    </div>
                                                </li>
                                            </ul>

                                            {{--新增步骤--}}
                                            <button type="button"
                                                    class="btn"
                                                    style="background-color: #0b93ff; border-radius: 15px; color: rgba(255,255,255,1); margin-top: 20px;"
                                                    onclick="addStep()">
                                                <p>&nbsp;新增步骤&nbsp;</p>
                                            </button>
                                        </div>
                                        <div class="row-div" style="margin-top: 30px;">
                                            <button type="button"
                                                    class="btn"
                                                    style="background-color: #0b93ff; border-radius: 6px; color: rgba(255,255,255,1); padding: 5px 60px 5px 60px;"
                                                    onclick="saveFunnel()">
                                                <p>&nbsp;保存&nbsp;</p>
                                            </button>
                                            <div onclick="clearData()">
                                                <p style="color:rgba(115,130,147,0.8); margin-top: 5px; margin-left: 14px;">
                                                    清空</p></div>
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
    getEventList();
    var key_list = [];
    var event_list = [];
    var aHtml = "";

    // 获取事件列表
    function getEventList() {
        $.get("/admin/event/get_list", {}, function (data) {
            console.log(data);
            key_list = Object.keys(data);
            event_list = data;
            var event = Object.values(data);
            for (var i = 0; i < event.length; i++) {
                aHtml += "<li><a>" + event[i] + "</a></li>";
            }
            $('#dropdown-menu1').append(aHtml);
        }, 'json');
    }

    var stepnum = $('#stepUl')['0'].children.length;
    var arr = (window.location.href).split('/');
    var idx = parseInt(arr[arr.length - 1].split('?')[0]);
    if (idx !== 0) {
        $.get("/admin/funnel/detail/" + idx, {}, function (data) {
            console.log(data);
            if (data.status === 1) {
                data = data.msg;
                $('input[name="funnelnm"]').val(data.name);
                for (var i = 0; i < data.steps.length; i++) {
                    if (i !== 0) {
                        addStep();
                    }
                    $('#dropdownMenu' + (i + 1) + ' input').val(event_list[data.steps[i].event_id]);
                    $("#content" + (i + 1)).val(data.steps[i].step_name);
                }
            } else {
                noty({text: data.msg, type: "error", timeout: 2000});
            }
        }, 'json');
    }

    // 选择用户触发事件
    $(document).on("click", '#dropdown-menu1 li a', function () {
        $('#dropdownMenu1 input').val($(this).text());
    });

    function addStep() {

        if (stepnum < 5) {
            var idx = stepnum + 1;
            stepnum++;
            $('#stepUl').append("<li style=\"margin-top:20px;\" id=\"idx" + idx + "\">\n" +
                "                                                    <div class=\"row-div\" style=\"padding:12px 20px 12px 20px;\n" +
                "                                                    background-color: rgba(241,242,247,0.7); border-radius:4px; width:840px;\">\n" +
                "                                                        <h5  id=\"stepname\">步骤" + (idx) + "<\/h5>\n" +
                "                                                        <p style=\"margin-left: 65px; color: red\">*&nbsp;<\/p>\n" +
                "                                                        <h5>分析依据<\/h5>\n" +
                "                                                        <div class=\"dropdown\">\n" +
                "                                                            <button type=\"button\"\n" +
                "                                                                    class=\"btn dropdown-toggle row-div\"\n" +
                "                                                                    id=\"dropdownMenu" + idx + "\" data-toggle=\"dropdown\"\n" +
                "                                                                    style=\"margin-left: 13px; border:1px rgba(115,130,147,0.2) solid; border-radius: 8px;background-color: white;\">\n" +
                "                                                                {{--<p style=\"\">--}}\n" +
                "                                                                <input placeholder=\"选择用户触发事件\" name=\"event\" style=\"border: 0 solid white; margin-bottom: 0;  width: 160px; text-align: left\">\n" +
                "                                                                {{--</p>--}}\n" +
                "                                                                <span class=\"caret\"\n" +
                "                                                                      style=\"margin-left:7px;\"><\/span>\n" +
                "                                                            <\/button>\n" +
                "                                                            <ul class=\"dropdown-menu\" id=\"dropdown-menu" + idx + "\"\n" +
                "                                                                style=\"min-width: auto;left:13px; height: 350px; overflow: scroll;\">\n" +
                "                                                            <\/ul>\n" +
                "                                                        <\/div>\n" +
                "\n" +
                "                                                        <h5 style=\"margin-left: 65px;\">步骤名称<\/h5>\n" +
                "                                                        <input type=\"text\" class=\"form-control\" placeholder=\"步骤名称\"\n" +
                "                                                               id=\"content" + idx + "\"\n" +
                "                                                               style=\"margin-left: 15px; width: 180px;height:30px; border-radius: 10px\"\/>\n" +
                "                                                        <i class=\"fa fa-minus-square-o\" id=\"rmstep\" onclick=\"deleteStep(" + idx + ")\"\n" +
                "                                                           style=\"margin-left: 60px; font-size: 17px; color: rgba(115,130,147,0.7)\"><\/i>\n" +
                "                                                    <\/div>\n" +
                "                                                <\/li>");
            $('#dropdown-menu' + idx).append(aHtml);
            $(document).on("click", '#dropdown-menu' + idx + ' li a', function () {
                $('#dropdownMenu' + idx + ' input').val($(this).text());
            });
        } else {
            noty({text: '漏斗步骤上限为5步！', type: "error", timeout: 2000});
        }
    }


    // 保存 提交数据
    function saveFunnel() {
        var name = $('input[name="funnelnm"]').val();
        if (name === "" || name.length > 20) {
            noty({text: '漏斗名称不能为空且不能超过20个字符！', type: "error", timeout: 2000});
            return;
        }
        if (stepnum === 1) {
            noty({text: '漏斗不能少于两步！', type: "error", timeout: 2000});
            return;
        }
        for (var i = 1; i <= stepnum; i++) {
            if ($('#dropdownMenu' + i + ' input').val() === "") {
                noty({text: '用户触发事件不能为空！', type: "error", timeout: 2000});
                return;
            }
        }
        var steps = [];
        for (var j = 1; j <= stepnum; j++) {
            var event_name = $('#dropdownMenu' + j + ' input').val();
            var id = '';
            console.log(key_list);
            console.log(event_list);
            console.log(event_name);
            for(var key in key_list) {
                if(event_list[key_list[key]] === event_name) {
                    id = key_list[key];
                }
            }
            var item = {
                event_id: id,
                step_name: $("#content" + j).val() ? $("#content" + j).val() : $('#dropdownMenu' + j + ' input').val()
            }
            steps.push(item);
        }
        console.log(name);
        console.log(steps);
        if (idx !== 0) {
            // 修改
            $.post("/admin/funnel/update", {'id': idx, 'name': name, 'steps': steps}, function (data) {
                if (data.status === 1) {
                    noty({text: '编辑成功' + data.msg, type: "success", timeout: 2000});
                    window.location.href = "/admin/funnel"; // 跳转到列表页
                } else {
                    noty({text: '编辑失败,' + data.msg, type: "error", timeout: 2000});
                }
            }, 'json');
        } else {
            // 增加
            $.post("/admin/funnel/add", {'name': name, 'steps': steps}, function (data) {
                if (data.status === 1) {
                    noty({text: '漏斗创建成功' + data.msg, type: "success", timeout: 2000});
                    window.location.href = "/admin/funnel"; // 跳转到列表页
                } else {
                    noty({text: '创建失败,' + data.msg, type: "error", timeout: 2000});
                }
            }, 'json');
        }
    }

    // 清除
    function clearData() {
        $('input[name="funnelnm"]').val("");
        for (var j = 1; j <= stepnum; j++) {
            $('#dropdownMenu' + j + ' input').val("");
            $("#content" + j).val("");
        }
    }

    function deleteStep(num) {
        var str = 'idx' + num;
        if (num <= stepnum && stepnum !== 1) {
            $("#" + str).remove();
            for (var i = num + 1; i <= stepnum; i++) {
                $("#dropdown-menu" + i)['0'].id = "dropdown-menu" + (i - 1);
                $("#dropdownMenu" + i)['0'].id = "dropdownMenu" + (i - 1);
                $("#content" + i)['0'].id = "content" + (i - 1);
                $("#idx" + i)['0'].id = "idx" + (i - 1);
            }
            stepnum--;
            for (var i = num; i <= stepnum; i++) {
                $("#idx" + i + " #stepname").html("步骤" + (i));
                $("#idx" + i + " #rmstep")["0"].attributes[2].value = "deleteStep(" + i + ")"; //id一定要放在第二个属性
            }
        }
    }

</script>
</body>
</html>
