<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>课程管理test</title>

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
    <link rel="stylesheet" href="/mobile/signin/css/sweetalert.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/admin_style/flatlab/js/html5shiv.js"></script>
    <script src="/admin_style/flatlab/js/respond.min.js"></script>
    <![endif]-->
    <script src="/mobile/signin/js/sweetalert.min.js"></script>
</head>

<body>

<style>
    .table td{
        max-width: 400px;
        word-break: break-all;
    }
</style>

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
    <div class="row-fluid">
        <form action="#" method="get">
            <div class="input-group input-group-sm m-bot15 col-lg-8">
                <span class="input-group-addon">课程名称</span>
                <input type="text" class="form-control" name="title" value="{{$params['title']}}" >
                <span class="input-group-addon">期数</span>
                <input type="text" class="form-control"  name="number" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="{{$params['number']}}" >
                <span class="input-group-addon">课程日期</span>
                <input type="text" class="form-control date-picker" name="start_day" value="{{$params['start_day']}}">
                <span class="input-group-addon">课程id</span>
                <input type="text" class="form-control" name="id" value="{{$params['id']}}">
                <span class="input-group-btn">
                    <button class="btn btn-white" name="sort" type="submit" value="id">搜索并按id排序</button>
                </span>
                <span class="input-group-btn">
                    <button class="btn btn-white" name="sort" type="submit" value="start_day">搜索并按日期排序</button>
                </span>
            </div>
        </form>
    </div>
<table class="display table table-bordered" id="hidden-table-info">
<thead>
<tr>
    <th style="min-width: 50px">ID</th>
    <th>课程名称</th>
    <th>套课cid</th>
    <th>课程日期</th>
    <th style="min-width: 100px">音频地址</th>
    <th>状态</th>
    <th>音频内容</th>
    <th>操作</th>
</tr>
</thead>
<tbody>
<?php if (!empty($list)) {
    foreach ($list as $k => $item) {
        ?>
        <tr class="gradeA">
            <td><?=$item->id?></td>
            <td>
                <a href="/admin/course/detail/<?php echo $item->id; ?>"><?=$item->title?></a>
            </td>
            <td><?=$item->cid?></td>
            <td><?=$item->start_day?> <?=$item->start_time?> - <?=$item->end_time?></td>
            <td style="width: 50px;"><audio src="<?=$item->audio?>" controls="controls"></audio></td>
            <td class="center"><?php echo $status_arr[$item->display_status]; ?></td>
            <td>
                <textarea id="textarea{{$item->id}}"><?=$item->audio_detail?></textarea>
                <a href="javascript:void(0)" data="{{$item->id}}" class="btn btn-primary btn-xs save-audio-detail">保存</a>
            </td>
            <td style="width: 150px;">
                <a href="/admin/course/edit/<?php echo $item->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                <a style="display: none" href="/admin/course/delete/<?php echo $item->id; ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                <a href="/admin/course/notify_setting/<?php echo $item->id; ?>" class="btn btn-info btn-xs"><i class="fa fa-envelope"></i></a>
                <?php if ($item->review_id) {?>
                    <a href="/admin/course_review/edit/<?php echo $item->review_id; ?>" class="btn btn-default btn-xs"><i class="fa fa-book">回顾</i></a>
                <?php } else {?>
                    <a href="/admin/course_review/add/<?php echo $item->id; ?>" class="btn btn-default btn-xs"><i class="fa fa-book">回顾</i></a>
                <?php }?>
                <a href="javascript:void(0)" status="{{$item->signin_status}}"
                   data="{{$item->id}}" class="btn btn-primary btn-xs" hw-open-game="">
                    @if($item->signin_status == 1)
                        <i class="fa fa-times" title="关闭游戏"></i>
                    @else
                        <i class="fa fa-check" title="开启游戏"></i>
                    @endif
                </a>
            </td>
        </tr>
    <?php }
}
?>
</tbody>
</table>
    <?php
        echo $list->appends([
        'title' => $params['title'],
        'number' => $params['number'] ,
        'start_day' => $params['start_day'],
        'sort' => $params['sort'],
    ])->render(); ?>
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
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<!--this page plugins-->
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>

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

    $(".date-picker").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $.noty.defaults.theme = 'relax';
    jQuery(document).ready(function ($) {
        $('.save-audio-detail').click(function () {
            var cid = $(this).attr('data');
            var detail = $('#textarea'+cid).val();
            console.log('---'+detail+'---');
            $.ajax({
                url: '/admin/course/addAudioDetail',
                type: 'POST',
                dataType: 'json',
                data: {
                    cid: cid,
                    detail: detail
                },
                success: function (result) {
                    if (result.status == 200) {
                        noty({text: "保存成功", type: "success", timeout: 2000});
                    } else {
                        noty({text: "保存失败", type: "error", timeout: 2000});
                    }

                }
            })
        });

        $('tbody a[hw-open-game]').click(function () {
            var thisStatus = $(this).attr('status');
            var thisId = $(this).attr('data');
            if (thisStatus == 0) {

                swal({
                            title: "开启游戏",
                            text: "你确定开启游戏?",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "是的,开启。",
                            cancelButtonText: "现在不开启",
                            closeOnConfirm: false
                        },
                        function () {
                            editSignStatus(thisId, 1, 1);
                        });

            } else if (thisStatus == 1) {

                swal({
                            title: "关闭游戏",
                            text: "你确定关闭游戏?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "是的,关闭。",
                            cancelButtonText: "现在不关闭",
                            closeOnConfirm: false
                        },
                        function () {
                            editSignStatus(thisId, 0, 2);
                        });
            }
        })
    });

    /**
     *
     * @param id 课程ID
     * @param newStatus 将要修改的状态
     * @param type 1开启  2关闭
     * @description 修改游戏状态
     */
    function editSignStatus(id, newStatus, type) {
        $.ajax({
            url: '/admin/signin/switch',
            type: 'GET',
            dataType: 'json',
            data: {
                courseId: id,
                signStatus: newStatus
            },
            success: function (result) {
                if (result.status == 200) {
                    if (type == 1) {
                        swal({
                                    title: "开启成功",
                                    text: "开启游戏成功!",
                                    type: "success",
                                    confirmButtonText: "确定",
                                },
                                function () {
                                    location.reload();
                                });
                    } else {
                        swal({
                                    title: "关闭成功",
                                    text: "关闭游戏成功!",
                                    type: "success",
                                    confirmButtonText: "确定",
                                },
                                function () {
                                    location.reload();
                                });
                    }
                } else {
                    swal("开启/关闭失败!", "游戏开启或关闭失败!", "error");
                }

            }
        })
    }
</script>
</body>
</html>
