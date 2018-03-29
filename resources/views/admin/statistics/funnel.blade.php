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
                        <div class="panel-body" style="padding-bottom: 90px;">
                            <div class="tab-content">
                                <section id="unseen">
                                    <h4 style="color: rgba(0,0,0,0.7)">转化漏斗</h4>
                                    <div class="col-lg-12"
                                         style="display: flex; flex-direction: row; margin-top: 30px;">
                                        <button type="button" class="btn btn-primary btn-sm"
                                                style="font-size: 14px; border-radius: 15px;" onclick="addFunnel()">
                                            + 新建漏斗
                                        </button>
                                        <div style="font-size: 14px; display: flex; flex-direction: row; justify-content: flex-end;">
                                            <button type="button" class="btn btn-sm"
                                                    style="font-size: 14px; background-color:rgba(255,60,60,0.9); color: #fff;"
                                                    onclick="deleteFunnels()">
                                                <span class="glyphicon glyphicon-trash" style="font-size: 12px;"></span>
                                                删除
                                            </button>
                                            <div class="input-group col-md-3"
                                                 style="margin-left: 25px;margin-right: 25px;">
                                                <input type="text" class="form-control" placeholder="请输入漏斗名称"
                                                       name="id"
                                                       value="<?php echo !empty($params['id']) ? $params['id'] : ''?>"/>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary"
                                                            type="submit"> 搜索</button>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <table class="table table-condensed table-hover table-funnel"
                                               style="margin-top: 15px;">
                                            <thead>
                                            <tr>
                                                <th><label><input type="checkbox" name="checkBox"
                                                                  value='all'></label></th>
                                                <th>转化漏斗名称</th>
                                                <th>起始步骤</th>
                                                <th>转化目标</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <ul class="pagination" id="pagination"
                                            style="position: absolute; right:30px; margin-top: 30px;"></ul>
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
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/admin_style/js/jqPaginator.js"></script>
<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
<script type="text/javascript">

    var data = JSON.parse('<?php echo json_encode($data);?>');
    console.log(data);
    var trHtml = "";
    for (var i = 0; i < data.funnels.length; i++) {
        var item = data.funnels[i];
        var id = parseInt(item.id);
        var name = item.name;
        var start_step = item.steps[0].step_name;
        var end_step = item.steps[item.steps.length - 1].step_name;
        trHtml += "<tr><td><label><input type=\"checkbox\" name=\"checkBox\" value=\"" + id + "\"></label>\n" +
            "        </td>\n" +
            "        <td>" + name + "</td>\n" +
            "        <td>" + start_step + "</td>\n" +
            "    <td>" + end_step + "</td>\n" +
            "    <td style=\"display: flex; flex-direction: row; font-size: 15px; color:#6eaaee;\">\n" +
            "        <a href=\"/admin/funnel/conversion/" + id + "\"><i class=\"fa fa-bar-chart-o\"></i></a>\n" +
            "    <a href=\"/admin/funnel/edit/" + id + "\"><i class=\"fa fa-pencil-square-o\"\n" +
            "    style=\"margin-left: 20px; font-size: 16px;\"></i></a>\n" +
            "    </td>\n" +
            "    </tr>"
    }
    $(".table-funnel tbody").append(trHtml);

    $('.table-funnel thead tr th label input').on('click', function () {
        if ($(this)["0"].checked === true) {
            $('.table-funnel tbody tr td label input').each(function () {
                $(this)["0"].checked = true;
            });
        } else {
            $('.table-funnel tbody tr td label input').each(function () {
                $(this)["0"].checked = false;
            });
        }
    });

    $('.table-funnel tbody tr td label input').on('click', function () {
        if ($(this)["0"].checked === false) {
            $('.table-funnel thead tr th label input')["0"].checked = false;
        }
    });

    function deleteFunnels() {
        bootbox.confirm({
            buttons: {
                confirm: {
                    label: '确认',
                    className: 'btn-primary'
                },
                cancel: {
                    label: '取消',
                    className: 'btn-default'
                }
            },
            message: '确定删除这些事件吗？',
            callback: function (result) {
                var funnels = [];
                $('.table-funnel tbody tr td label input[name="checkBox"]:checked').each(function () {
                        funnels.push(parseInt($(this).context.value));
                    }
                );
                console.log(funnels);
                // 请求接口进行删除操作
                $.post("/admin/funnel/delete", {'ids_rm': funnels}, function (data) {
                    console.log(data);
                    if (data.status === 1) {
                        noty({text: '删除成功', type: "success", timeout: 2000});
                        window.location.reload(); // 跳转到列表页
                    } else {
                        noty({text: '删除失败,', type: "error", timeout: 2000});
                    }
                }, 'json');
            }
        });
    }

    function searchFunnels() {
        var str = $('input[name="content"]').val();
        // 匹配list中是否存在该事件
        if (str) {

        } else {
            return true;
        }
    }


    function addFunnel() {
        window.location.href = "/admin/funnel/edit/0";
    }

    $('#pagination').jqPaginator({
        totalCounts: parseInt(data.total),
        pageSize: 10,
        currentPage: getPage(),
        visiblePages: 10,
        onPageChange: function (num, type) {
            if (getPage() !== num)
                window.location.href = "/admin/funnel?page=" + num;
        }
    });

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
