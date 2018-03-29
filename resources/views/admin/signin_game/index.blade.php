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
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datepicker/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-timepicker/compiled/timepicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css"/>
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet"/>
    <link href="/js/select2/select2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/mobile/signin/css/sweetalert.css">
    <style>
        .sign_switch_from {
            display: none;
        }
    </style>
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
                            直播赢好礼活动
                        </header>
                        <div class="panel-body">
                            <section id="unseen">
                                <div class="row-fluid" style="float:right">
                                    <div class="clearfix">
                                        <div class="btn-group">
                                            <a href="/admin/course/signin/0">
                                                <button id="editable-sample_new" class="btn green">
                                                    创建一个游戏 <i class="fa fa-plus"></i>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <form action="#" method="get" style="width:600px">
                                        <div class="input-group input-group-sm m-bot15 col-lg-8">
                                            <span class="input-group-addon">课程id</span>
                                            <input type="text" class="form-control" autocomplete="off" name="cid" value="{{isset($params['cid']) ? $params['cid'] : ''}}" oninput='this.value=this.value.replace(/\D/gi,"")'>
                                            <span class="input-group-btn">
                                                <button class="btn btn-white" type="submit"><i class="fa fa-search"></i> 搜索</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th>游戏ID</th>
                                        <th>课程ID</th>
                                        <th>创建时间</th>
                                        <th>活动平台</th>
                                        <th>奖品分数</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($list)) {
                                    foreach ($list as $k => $item) {
                                    ?>
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->cid}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>微信</td>
                                        <td>{{$item->win_num}}</td>
                                        <td>
                                            <a href="/admin/course/signin/1/<?php echo $item->cid; ?>"
                                               class="btn btn-default btn-xs"><i class="fa fa-pencil"></i>编辑</a>
                                            <a href="/admin/signin/list/<?php echo $item->cid; ?>"
                                               class="btn btn-primary btn-xs"><i class="fa fa-user"></i>名单</a>
                                        </td>
                                    </tr>
                                    <?php }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php echo $query->render(); ?>
                            </section>
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
<script src="/admin_style/flatlab/js/respond.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.validate.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap-switch.js"></script>
<!--this page plugins-->

<script type="text/javascript" src="/admin_style/flatlab/assets/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/js/select2/select2.full.js"></script>
<script src="/js/select2/zh-CN.js"></script>
<script src="/js/lodash.js"></script>

<script src="/mobile/signin/js/sweetalert.min.js"></script>
</body>
</html>