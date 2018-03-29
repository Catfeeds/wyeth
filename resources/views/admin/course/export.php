<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>课程信息导出</title>

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
<?php echo $header; ?><!--header end-->
<!--sidebar start-->
<?php echo $sidebar; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
<section class="wrapper">
<!-- page start-->
<div class="row">
<div class="col-sm-12">
<section class="panel">
<header class="panel-heading">
    课程列表
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <a href="javascript:;" class="fa fa-times"></a>
    </span>
</header>
<div class="panel-body">
<div class="adv-table">
<table class="display table table-bordered" id="hidden-table-info">
<thead>
<tr>
    <th>ID</th>
    <th>课程名称</th>
    <th>期数</th>
    <th>操作（导出）</th>
</tr>
</thead>
<tbody>
<?php if (!empty($list)) {
    foreach ($list as $k => $item) {
        ?>
        <tr class="gradeA">
            <td><?=$item->id?></td>
            <td><?=$item->title?></td>
            <td><?=$item->number?></td>
            <td>
                <a href="/admin/course_export/export?type=sign_up&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">报名信息</a>
                <a href="/admin/course_export/export?type=course_info&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">上课信息</a>
                <a href="/admin/course_export/export?type=live_act&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">直播行为</a>
                <a href="/admin/course_export/export?type=msg_push_log&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">模版消息推送日志</a>
                <a href="/admin/course_export/export?type=questions_record&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">提问区消息</a>
                <a href="/admin/course_export/export?type=chat_record&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">讨论区消息</a>
                <a href="/admin/course_export/export?type=flower_record&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">送花记录</a>
                <a href="/admin/course_export/export?type=sign_up_stat&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">报名统计</a>
                <a href="/admin/course_export/export?type=course_stat&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">课程统计</a>
                <a href="/admin/course_export/export?type=course_rate&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">课后评分</a>
            </td>
        </tr>
    <?php }
}
?>
</tbody>
</table>

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
<?php echo $footer; ?>
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
<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>

<script>

    $(document).ready(function() {

        /*
         * Initialse DataTables, with no sorting on the 'details' column
         */
        var oTable = $('#hidden-table-info').dataTable( {
            "aoColumnDefs": [
                { "bSortable": false, "aTargets": [1,2,3] }
            ],
            "aaSorting": [[0, 'desc']]
        });

    } );
</script>
</body>
</html>
