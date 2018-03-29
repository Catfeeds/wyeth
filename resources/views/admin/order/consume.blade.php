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
    <style>
        .cus-label { margin-top: 10px; text-align: right }
    </style>
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
                            订单管理
                        </header>
                        <form action="#" method="get" id="formType">
                            <input id="inputType" type="text" name="type" value="0" style="display: none">
                        </form>
                        <div class="panel-body">
                            <div class="tab-content">
                                <section id="unseen">
                                    <div class="space15"></div>
                                    <div class="row-fluid">
                                        <form action="#" method="get">
                                            <div class="input-group input-group-sm m-bot15 col-lg-8">
                                                <span class="input-group-addon">订单编号</span>
                                                <input type="text" class="form-control" name="trade_id" value="{{$params['trade_id']}}" >
                                                <span class="input-group-addon">课程名</span>
                                                <input type="text" class="form-control"  name="course_name" value="{{$params['course_name']}}" >
                                                {{--<span class="input-group-addon">地区</span>--}}
                                                {{--<input type="text" class="form-control date-picker" name="area" value="{{$params['area']}}">--}}
                                                <span class="input-group-btn">
                                                    <button class="btn btn-white" name="sort" type="submit" value="id">搜索</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="space15"></div>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>订单编号</th>
                                            <th>课程名</th>
                                            <th>openid</th>
                                            <th>购买价格</th>
                                            <th>购买时间</th>
                                            {{--<th>操作</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($list)) {
                                        foreach ($list as $k => $item) {
                                        ?>
                                        <tr>
                                            <td>{!! $item->id !!}</td>
                                            <td>{!! $item->trade_id !!}</td>
                                            <td>{!! $item->course_name !!}</td>
                                            <td>{!! $item->open_id !!}</td>
                                            <td>{!! $item->mq !!}</td>
                                            <td>{!! $item->updated_at !!}</td>

                                            {{--<td class="">--}}
                                                {{--<button data-toggle="modal" data-target="#editModal" class="btn btn-primary btn-xs" onclick="clickEdit('{!! $item->id !!}', '{!! $item->type !!}', '{!! $item->brand_id !!}', '{!! $item->position !!}', '{!! $item->link !!}', '{!! $item->img !!}', '{!! $item->display !!}', '{!! $item->subject !!}', '{!! $item->order !!}')"><i class="fa fa-pencil "></i>编辑</button>--}}
                                                {{--<button class="btn btn-danger btn-xs" onclick="deleteConfirm('{!! $item->id !!}')"><i class="fa fa-trash-o "></i>删除</button>--}}
                                            {{--</td>--}}
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

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">编辑广告</h4>
            </div>
            <form class="cmxform tasi-form" id="editForm" method="post" action="/admin/advertise/edit" enctype="multipart/form-data">
                <div class="form-group" style="display: none;">
                    <input id="editId" name="id">
                </div>
                <div class="form-group">
                    <label for="subject" class="control-label col-lg-2 cus-label">名称</label>
                    <div class="col-md-9" style="flex-grow: 1; margin: 0 0 20px 0">
                        <input class=" form-control" id="subject" name="subject" minlength="2" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="type" class="control-label col-lg-2 cus-label">用户类型</label>
                    <div class="col-lg-9">
                        <div class="shihe_1" style="float: left;">
                            <select name="type" id="type" class="form-control m-bot15">
                                <option value="1">无主</option>
                                <option value="2">启赋</option>
                                <option value="3">金装</option>
                            </select>
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="link" class="control-label col-lg-2 cus-label">链接</label>
                    <div class="col-md-9" style="flex-grow: 1; margin: 0 0 20px 0">
                        <input class=" form-control" id="link" name="link" minlength="2" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="control-label col-lg-2 cus-label">图片</label>
                    <div class="col-lg-9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img id="addTagImg" data-src="" src="" alt="">
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                            </div>
                            <div>
                               <span class="btn btn-white btn-file">
                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                               <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                               <input type="file" class="default" name="img">
                               </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="display" class="control-label col-lg-2 cus-label">是否显示</label>
                    <div class="col-lg-9">
                        <div class="shihe_1" style="float: left;">
                            <select name="display" id="display" class="form-control m-bot15">
                                <option value="1">是</option>
                                <option value="0">否</option>
                            </select>
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="order" class="control-label col-lg-2 cus-label">显示顺序</label>
                    <div class="col-md-9" style="flex-grow: 1; margin: 0 0 20px 0">
                        <input class=" form-control" id="order" name="order" type="text">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary" id="">确认</button>
                </div>
            </form>
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
<script>
</script>
</body>
</html>