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
    课程列表
    <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <a href="javascript:;" class="fa fa-times"></a>
    </span>
</header>
<div class="panel-body">
<div class="adv-table">
    <div class="row-fluid">
        <form action="#" method="get">
            <div class="input-group input-group-sm m-bot15 col-lg-4">
                <span class="input-group-addon">课程名称</span>
                <input type="text" class="form-control" name="title" value="<?php echo !empty($params['title'])?$params['title']:''; ?>">
                <span class="input-group-btn">
                    <button class="btn btn-white" type="submit">搜索</button>
                </span>
            </div>
        </form>
    </div>
<table class="display table table-bordered" id="hidden-table-info">
<thead>
<tr>
    <th>课程名称</th>
    <th>期数</th>
    <th>课程日期</th>
    <th>时间</th>
    <th>报名人数</th>
    <!--<th>教师</th>
    <th>主持人</th>
    <th>课件</th>-->
    <th>状态</th>
    <th>操作</th>
</tr>
</thead>
<tbody>
<?php if(!empty($list)) {
    foreach ($list as $k => $item) {
        ?>
        <tr class="gradeA">
            <td><?=$item->title?></td>
            <td><?=$item->number?></td>
            <td><?=$item->start_day?></td>
            <td><?=$item->start_time?> - <?=$item->end_time?></td>
            <td><?=$item->realnum;?>/<?=$item->sign_limit;?></td>
            <!--<td>
                <a href="javascript:;" onclick="layer.open({type: 2,title:'查看二维码',area: ['460px', '320px'],content: '/admin/course/view_qrcode/<?php echo $item->id; ?>?type=teacher'});" class="btn btn-success btn-xs" title="用于教师的绑定"><i class="fa fa-qrcode"></i></a>
                <span><?=$item->teacher_uid==0 ? '未绑定' : '<a href="javascript:;" onclick="unbind(this,\''.$item->id.'\',\'teacher\');">解绑</a>'?></span>
            </td>
            <td>
                <a href="javascript:;" onclick="layer.open({type: 2,title:'查看二维码',area: ['460px', '320px'],content: '/admin/course/view_qrcode/<?php echo $item->id; ?>?type=anchor'});" class="btn btn-success btn-xs" title="用于主持人的绑定"><i class="fa fa-qrcode"></i></a>
                <span><?=$item->anchor_uid==0 ? '未绑定' : '<a href="javascript:;" onclick="unbind(this,\''.$item->id.'\',\'anchor\');">解绑</a>'?></span>
            </td>
            <td><a href="javascript:;" onclick="layer.open({type: 2,title:'上传课件',area: ['460px', '480px'],content: ['/admin/course/upload_att/<?php echo $item->id; ?>','yes']});" class="btn btn-success btn-xs">已上传<?php echo $item->ware_count; ?>张</a></td>-->
            <td class="center"><?php echo $status_arr[$item->display_status]; ?></td>
            <td style="width: 150px;">
                <!--<a href="/admin/course/edit/<?php echo $item->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                <a href="/admin/course/delete/<?php echo $item->id; ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                <a href="/admin/course/notify_setting/<?php echo $item->id; ?>" class="btn btn-info btn-xs"><i class="fa fa-envelope"></i></a>
                <?php if ($item->review_id) { ?>
                    <a href="/admin/course_review/edit/<?php echo $item->review_id; ?>" class="btn btn-default btn-xs"><i class="fa fa-book">回顾</i></a>
                <?php } else { ?>
                    <a href="/admin/course_review/add/<?php echo $item->id; ?>" class="btn btn-default btn-xs"><i class="fa fa-book">回顾</i></a>
                <?php } ?>-->
                <a href="/admin/area_course/detail/<?php echo $item->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-envelope-o"></i>  详情</a>
            </td>
        </tr>
    <?php }
}
?>
</tbody>
</table>
    <?php echo $list->appends(['title' => !empty($params['title'])?$params['title']:''])->render(); ?>
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
<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>

<script>
    function unbind(obj,id,type){
        layer.confirm('您确定要解绑吗？',function(index){
            var url = '';
            if(type == 'teacher'){
                url = '/admin/course/unbindt/'+id;
            }
            if(type == 'anchor'){
                url = '/admin/course/unbinda/'+id;
            }
            if(url == ''){
                layer.msg('参数错误');
                return false;
            }
            $.post(url,{
                    'unbind':'unbind'
                },function(result){
                    if(result.status == '1'){
                        $(obj).parent().html('未绑定');
                    }else{
                        alert(result.msg);
                        return false;
                    }
                },'json'
            );
            layer.close(index);
        });
    }

    function to_anchor(that)
    {
        parent.window.open($(that).attr('url'));
    }
</script>
</body>
</html>
