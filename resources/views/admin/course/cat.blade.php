<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <title>惠氏妈妈微课堂</title>
    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />
</head>

<body>

<section id="container" class="">
    <!--header start-->
    <?php echo $header; ?>
    <!--header end-->
    <!--sidebar start-->
    <?php echo $sidebar; ?>
    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            套课 | <a class="btn btn-shadow btn-success" href="\admin\course\cat_add">添加</a>
                        </header>
                        <div class="panel-body">
                        <table class="display table table-bordered" id="hidden-table-info">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>名称</th>
                                <th>描述</th>
                                <th>图片</th>
                                {{--<th>链接</th>--}}
                                <th>价格（MQ）</th>
                                <th>类型</th>
                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($courseCats as $item)
                                <tr hw-item="{{$item->id}}">
                                    <td>{{$item->id}}</td>
                                    <td><a href="/admin/course/index?cid=<?php echo $item->id; ?>">{{$item->name}}</a></td>
                                    <td class="des">{{$item->description}}</td>
                                    <td class="img">
                                        @if ($item->img)
                                            <img width="100px" src="{{$item->img}}" />
                                        @else
                                            <img width="100px" src="/admin_style/img/no_image.png">
                                        @endif
                                    </td>
                                    {{--<td><a href="{{$item->link}}">链接</a></td>--}}
                                    <td>{{$item->price}}</td>
                                    <td>
                                        @if($item->show_type == 0)
                                            系列型
                                        @elseif($item->show_type == 1)
                                            专家型
                                        @elseif($item->show_type == 2)
                                            多图型
                                        @elseif($item->show_type == 3)
                                            介绍型
                                        @endif
                                    </td>
                                    <td>{{$item->displayorder}}</td>
                                    <td>
                                        <a href="cat_edit/{{$item->id}}" class="btn btn-primary btn-xs" title="编辑"><i class="fa fa-pencil"></i></a>
                                        <a  href="##" hw-delete="1" data-id="{{$item->id}}" class="btn btn-danger btn-xs" style="display: none" title="删除" data-toggle="modal"><i class="fa fa-trash-o "></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <?php
                        echo $courseCats->appends([])->render(); ?>
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
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="/admin_style/flatlab/js/respond.min.js" ></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/js/bootbox.4.4.0.min.js"></script>
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script>
$(function () {
    bootbox.setLocale('zh_CN');
    $.noty.defaults = {
        layout: 'top',
        theme: 'relax', // or 'relax'
        type: 'alert',
        text: '', // can be html or string
        dismissQueue: true, // If you want to use queue feature set this true
        template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
        animation: {
            open: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceInLeft'
            close: {height: 'toggle'}, // or Animate.css class names like: 'animated bounceOutLeft'
            easing: 'swing',
            speed: 500 // opening & closing animation speed
        },
        timeout: false, // delay for closing event. Set false for sticky notifications
        force: false, // adds notification to the beginning of queue when set to true
        modal: false,
        maxVisible: 5, // you can set max visible notification for dismissQueue true option,
        killer: false, // for close all notifications before show
        closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all notifications
        callback: {
            onShow: function() {},
            afterShow: function() {},
            onClose: function() {},
            afterClose: function() {},
            onCloseClick: function() {},
        },
        buttons: false // an array of buttons
    };
    $('[hw-delete=1]').on('click', function (e) {
        var id = $(e.currentTarget).data('id');
        bootbox.confirm("确认删除?", function(result) {
            if (result) {
                $.post('/admin/course/cat_del', {id: id}, function (data) {
                    if (data.status == 1) {
                        $('[hw-item="'+id+'"]').remove();
                        noty({text:"操作成功", type: "success", timeout: 2000});
                    }
                }, 'json');
            }
        });
        return false;
    });
});
</script>

</body>
</html>
