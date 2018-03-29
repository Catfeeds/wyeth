<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>课程管理</title>

    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/jquery-multi-select/css/multi-select.css" />

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
<?php echo $header; ?><!--header end-->
<!--sidebar start-->
<?php echo $sidebar;?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
<section class="wrapper">
<!-- page start-->
<div class="row">
<div class="col-sm-12">
<section class="panel">
<header class="panel-heading">
    课程申请列表
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <a href="javascript:;" class="fa fa-times"></a>
    </span>
</header>
<div class="panel-body">
    <form action="#" class="form-horizontal tasi-form" style="margin-bottom: 10px;">
        <div class="form-group">
            <label class="control-label col-md-2">申请日期范围</label>
            <div class="col-md-4 col-md-pull-1">
                <div class="input-group input-large">
                    <input type="text" class="form-control dpd1" name="from">
                    <span class="input-group-addon">至</span>
                    <input type="text" class="form-control dpd2" name="to">
                </div>
            </div>
            <label class="control-label col-md-2">申请信息状态</label>
            <div class="col-md-2 col-md-pull-1">
                <div class="input-group input-large">
                    <select class="form-control m-bot15" name="status">
                        <option value="all">全部</option>
                        <option value="0">待审核</option>
                        <option value="1">审核通过</option>
                        <option value="2">已驳回</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="type" value="export" />
            <button class="btn btn-default" type="submit">导出excel</button>
        </div>
    </form>
<div class="adv-table">
<table class="display table table-bordered" id="hidden-table-info">
<thead>
<tr>
    <th>ID</th>
    <th>申请区域</th>
    <th>课程主题</th>
    <th>申请上课日期</th>
    <th>讲师姓名</th>
    <th>讲师来源</th>
    <th>讲师职称</th>
    <th>操作</th>
</tr>
</thead>
<tbody>
<?php if(!empty($list)) {
    foreach ($list as $k => $item) {
        ?>
        <tr class="gradeA">
            <td><?=$item->id?></td>
            <td><?=$item->area?></td>
            <td><?=$item->title?></td>
            <td><?=$item->start_day?>~<?=$item->end_day?></td>
            <td><?=$item->teacher_name;?></td>
            <td><?=$item->teacher_source;?></td>
            <td><?=$item->teacher_position;?></td>
            <td>
                <?=$item->action;?>
                <div class="modal fade modal-dialog-center" id="myModal<?=$item->id?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content-wrap">
                            <div class="modal-content">
                                <form action="/admin/course/applyVerify" method="get">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title">驳回原因</h4>
                                </div>
                                <div class="modal-body">
                                    <textarea name="refuse_reason" style="width: 100%;"></textarea>
                                    <input type="hidden" name="id" value="<?=$item->id?>" />
                                    <input type="hidden" name="type" value="2" />
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn btn-default" type="button">关闭</button>
                                    <button class="btn btn-warning" type="submit">确认</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    <?php }
}
?>
</tbody>
</table>
<?php echo $list->render(); ?>
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
<script type="text/javascript" language="javascript" src="/admin_style/flatlab/assets/advanced-datatable/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/data-tables/DT_bootstrap.js"></script>
<script src="/admin_style/flatlab/js/respond.min.js" ></script>

<script type="text/javascript" src="/admin_style/flatlab/assets/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.quicksearch.js"></script>

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>

<script>
    $(function(){
        $(".dpd1").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        $(".dpd2").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>
</body>
</html>
