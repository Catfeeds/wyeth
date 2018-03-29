<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

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
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/jquery-multi-select/css/multi-select.css" />

    <!--right slidebar-->
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">

    <!--  summernote -->
    <link href="/admin_style/flatlab/assets/summernote/dist/summernote.css" rel="stylesheet">

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
<?php echo $header; ?>
<!--header end-->
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
                        <header class="panel-heading">
                            讲师列表
                        </header>
                        <div class="panel-body">
                            <div class="tab-content">
                                <section id="unseen">
                                    <div class="row-fluid" style="margin-bottom: 10px;">
                                        <div class="clearfix">
                                            <div class="btn-group" style="display: flex;">
                                                <a href="/admin/lecturer/add/0">
                                                    <button class="btn green">
                                                        添加讲师 <i class="fa fa-plus"></i>
                                                    </button>
                                                </a>
                                                <a href="/admin/lecturer/export" style="margin-left: 15px">
                                                    <button class="btn green">
                                                        导出excel <i class="glyphicon glyphicon-log-out"></i>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space15"></div>
                                    <div class="row-fluid">
                                        <form action="#" method="get">
                                            <div class="input-group input-group-sm m-bot15 col-lg-8">
                                                <span class="input-group-addon">姓名</span>
                                                <input type="text" class="form-control" name="name" value="{{$params['name']}}" >
                                                <span class="input-group-addon">ID</span>
                                                <input type="text" class="form-control"  name="id" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="{{$params['id']}}" >
                                                <span class="input-group-addon">地区</span>
                                                <input type="text" class="form-control date-picker" name="area" value="{{$params['area']}}">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-white" name="sort" type="submit" value="id">搜索并按id排序</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                    <table id="tableLecturer" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th style="width: 60px">名称</th>
                                            <th>头像</th>
                                            <th style="width: 180px">所在医院</th>
                                            <th style="width: 120px">职位</th>
                                            <th>简介</th>
                                            <th style="width: 60px">课程数</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($list)) {
                                            foreach ($list as $k => $item) {?>
                                                <tr>
                                                    <td><?php echo $item->id; ?></td>
                                                    <td><?php echo $item->name; ?></td>
                                                    <td><img src="<?php echo $item->avatar; ?>" style="width: 100px; height: 100px; object-fit: cover"></td>
                                                    <td><?php echo $item->hospital; ?></td>
                                                    <td><?php echo $item->position; ?></td>
                                                    <td><?php echo $item->desc; ?></td>
                                                    <?php if ($item->course_num) {?>
                                                        <td><a href="/admin/course/index?tid=<?php echo $item->tid; ?>"><?php echo $item->course_num; ?></a></td>
                                                    <?php } else {?>
                                                        <td><?php echo $item->course_num; ?></td>
                                                    <?php }?>
                                                    <td class="">
                                                        <a href="/admin/lecturer/add/<?php echo $item->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i>编辑</a>
                                                    </td>
                                                </tr>
                                        <?php }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php echo $list->appends($params)->render(); ?>
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

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">添加标签</h4>
            </div>
            <label for="title" class="control-label col-lg-2" style="margin: 20px 0 0 0">标签名称</label>
            <div class="col-lg-12" style="flex-grow: 1; margin: 10px 0 20px 0">
                <input class=" form-control" id="tagName" minlength="2" type="text">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="submitAdd">添加</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">编辑标签</h4>
            </div>
            <label for="title" class="control-label col-lg-2" style="margin: 20px 0 0 0">标签名称</label>
            <div class="col-lg-12" style="flex-grow: 1; margin: 10px 0 20px 0">
                <input class=" form-control" data-id="" id="editTagName" minlength="2" type="text">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="submitEdit">修改</button>
            </div>
        </div>
    </div>
</div>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/js/jquery.js"></script>
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


<!--summernote-->
<script src="/admin_style/flatlab/assets/summernote/dist/summernote.min.js"></script>

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>
<!--this page  script only-->
<script src="/admin_style/flatlab/js/advanced-form-components.js"></script>
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/js/bootbox.4.4.0.min.js"></script>
</body>
</html>