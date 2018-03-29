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
    <link href="/js/select2/select2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/mobile/signin/css/sweetalert.css">
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
                            游戏配置
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <div class="form-horizontal">
                                    <!--<form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="/admin/course/store" enctype="multipart/form-data">-->
                                    <section class="panel">
                                        <div class="panel-body">
                                            <div class="tab-content">
                                                <div id="about"  class="tab-pane active">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <section class="panel">
                                                                <div class="panel-body">
                                                                    <section id="unseen">
                                                                        <div class="row-fluid">
                                                                            <form action="#" method="get">
                                                                                <div class="input-group input-group-sm m-bot15 col-lg-4">
                                                                                    <span class="input-group-addon">用户名字</span>
                                                                                    <input type="text" class="form-control" name="nickname" value="{{!empty($params['nickname'])?$params['nickname']:''}}">
                                                                                    <span class="input-group-btn">
                                                                                            <button class="btn btn-white" type="submit">搜索</button>
                                                                                        </span>

                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <a href="/admin/signin/list/print/{{$sign_config->cid}}" style="float:right">
                                                                            <button id="editable-sample_new" class="btn green">
                                                                                打印名单 <i class="fa fa-print"></i>
                                                                            </button>
                                                                        </a>
                                                                        <table class="table table-bordered table-striped table-condensed">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>排名</th>
                                                                                <th>签到数量</th>
                                                                                <th>用户昵称</th>
                                                                                <th>用户openid</th>
                                                                                <th>创建时间</th>
                                                                                <th>操作</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody id="signin_list">
                                                                            @if(!empty($list))
                                                                            @foreach($list as $k => $item)
                                                                            <tr>
                                                                                <td>{{$item->order}}</td>
                                                                                <td>{{$item->signin_num}}</td>
                                                                                <td>{{$item->nickname}}</td>
                                                                                <td>{{$item->openid}}</td>
                                                                                <td>{{$item->created_at}}</td>
                                                                                <td>
                                                                                    @if($item->order <= $win_num)
                                                                                    <a href="javascript:void(0)" class="btn btn-info btn-xs" hw-user-info="" data="{{$item->id}}" ><i class="fa fa-user"></i></a>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                            @endif
                                                                            </tbody>
                                                                        </table>
                                                                        <?php echo $query->appends(['nickname' => !empty($params['nickname'])?$params['nickname']:''])->render();?>
                                                                    </section>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
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
<script src="/js/bootbox.4.4.0.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="/admin_style/flatlab/js/respond.min.js" ></script>
<script src="/admin_style/flatlab/js/jquery.validate.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap-switch.js"></script>
<!--this page plugins-->

<script type="text/javascript" src="/admin_style/flatlab/assets/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/js/select2/select2.full.js"></script>
<script src="/js/select2/zh-CN.js"></script>
<script src="/js/lodash.js"></script>

<script src="/mobile/signin/js/sweetalert.min.js"></script>

<script>
    $(function() {
        $('#signin_list a[hw-user-info]').click(function (){
            var id = $(this).attr('data');
            get_user_info(id);

        });
    });

    function get_user_info (id) {
        $.ajax({
            url: '/admin/signin/victory/info',
            type: 'GET',
            dataType: 'json',
            data: {id : id},
            success: function (result) {
                if (result.status == 1) {
                    swal(result.msg);
                } else if (result.status == 0) {
                    var params = {};
                    var data = result.data;
                    params.sid = data.signin_item_id;
                    params.realname = data.realname;
                    params.mobile = data.mobile;
                    params.address = data.address;
                    params.remark = data.remark;
                    user_info_box(params);
                } else if (result.status == 3) {
                    swal(result.msg);
                }
            }
        })
    }

    function user_info_box (params) {
        bootbox.dialog({
            title : "领奖信息",
            message : '<table class="table table-bordered table-striped table-condensed">'+
            "<thead>"+
            "<tr>"+
            "<th width='20%'>真实姓名</th>"+
            "<th>"+params.realname+"</th>" +
            "</tr>" +
            "<tr>"+
            "<th width='20%'>手机号</th>"+
            "<th>"+params.mobile+"</th>" +
            "</tr>"+
            "</tr>" +
            "<tr height='70px'>"+
            "<th width='20%'>家庭住址</th>"+
            "<th>"+params.address+"</th>" +
            "</tr>"+
            "<tr height='100px'>"+
            "<th width='20%'>备注</th>"+
            "<th>"+params.remark+"</th>" +
            "</tr>"+
            "</thead>"+
            '</table>',
            buttons : {
                "cancel" : {
                    "label" : "<i class='icon-info'></i> 确定",
                    "className" : "btn-sm btn-danger",
                    "callback" : function() { }
                }
            }
        });
    }

</script>
</body>
</html>