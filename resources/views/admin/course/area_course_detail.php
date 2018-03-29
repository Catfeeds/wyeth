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
                            课程详情
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="" enctype="multipart/form-data">
                                    <div class="form-group ">
                                        <label for="title" class="control-label col-lg-2">课程名称</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="title" name="title" minlength="2" type="text" value="<?php echo $info->title; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="title" class="control-label col-lg-2">期数</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="number" name="number" type="text" datatype="n" value="<?php echo $info->number; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">课程日期</label>
                                        <div class="col-md-3 col-xs-11">
                                            <input disabled class="form-control form-control-inline input-medium date-picker" size="16" type="text" name="start_day" value="<?php echo $info->start_day; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">开始时间</label>
                                        <div class="col-md-3 col-xs-11">
                                            <div class="input-group bootstrap-timepicker">
                                                <input disabled type="text" class="form-control timepicker-24-start" name="start_time" value="<?php echo $info->start_time; ?>">
                                                <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">结束时间</label>
                                        <div class="col-md-3 col-xs-11">
                                            <div class="input-group bootstrap-timepicker">
                                                <input disabled type="text" class="form-control timepicker-24-end" name="end_time" value="<?php echo $info->end_time; ?>">
                                                <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">课程缩略图</label>
                                        <div class="col-md-9">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="<?php echo !empty($info->img) ? $info->img : '/admin_style/img/no_image.png'; ?>" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="desc" class="control-label col-lg-2">说明</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control " id="desc" name="desc" disabled ><?php echo $info->desc; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="notice" class="control-label col-lg-2">注意事项</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control " id="notice" name="notice" disabled ><?php echo $info->notice; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">课程适合阶段</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="stage" name="stage" type="text" datatype="n" value="<?php echo $info->stage; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="notice" class="control-label col-lg-2">状态</label>
                                        <div class="col-lg-6">
                                            <input disabled type="checkbox" data-toggle="switch" value="1" name="display_status" <?php echo $info->display_status == 1 ? 'checked="checked"' : ''; ?>/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="sign_limit" class="control-label col-lg-2">报名上限</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="sign_limit" name="sign_limit" minlength="2" type="text" value="<?php echo $info->sign_limit; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="teacher_name" class="control-label col-lg-2">讲师名称</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="teacher_name" name="teacher_name" minlength="2" type="text" value="<?php echo $info->teacher_name; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">讲师头像</label>
                                        <div class="col-md-9">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="<?php echo !empty($info->teacher_avatar) ? $info->teacher_avatar : '/admin_style/img/no_image.png'; ?>" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="teacher_hospital" class="control-label col-lg-2">讲师来源</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="teacher_hospital" name="teacher_hospital" minlength="2" type="text" value="<?php echo $info->teacher_hospital; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="teacher_position" class="control-label col-lg-2">讲师职称</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="teacher_position" name="teacher_position" minlength="2" type="text" value="<?php echo $info->teacher_position; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="teacher_desc" class="control-label col-lg-2">讲师简介</label>
                                        <div class="col-lg-6">
                                            <textarea disabled class="form-control " id="teacher_desc" name="teacher_desc"><?php echo $info->teacher_desc; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="firend_title" class="control-label col-lg-2">好友分享标题</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="firend_title" name="firend_title" minlength="2" type="text" value="<?php echo $info->firend_title; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="firend_subtitle" class="control-label col-lg-2">好友分享副标题</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="firend_subtitle" name="firend_subtitle" minlength="2" type="text" value="<?php echo $info->firend_subtitle; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="share_title" class="control-label col-lg-2">朋友圈分享语</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="share_title" name="share_title" minlength="2" type="text" value="<?php echo $info->share_title; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">分享图片</label>
                                        <div class="col-md-9">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="<?php echo !empty($info->share_picture) ? $info->share_picture : '/admin_style/img/no_image.png'; ?>" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="living_firend_title" class="control-label col-lg-2">直播中好友分享标题</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="living_firend_title" name="living_firend_title" minlength="2" type="text" value="<?php echo $info->living_firend_title; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="living_firend_subtitle" class="control-label col-lg-2">直播中好友分享副标题</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="living_firend_subtitle" name="living_firend_subtitle" minlength="2" type="text" value="<?php echo $info->living_firend_subtitle; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="living_share_title" class="control-label col-lg-2">直播中朋友圈分享语</label>
                                        <div class="col-lg-6">
                                            <input disabled class=" form-control" id="living_share_title" name="living_share_title" minlength="2" type="text" value="<?php echo $info->living_share_title; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">直播中分享图片</label>
                                        <div class="col-md-9">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="<?php echo !empty($info->living_share_picture) ? $info->living_share_picture : '/admin_style/img/no_image.png'; ?>" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
<!--this page  script only-->
<script src="/admin_style/flatlab/js/advanced-form-components.js"></script>

<script>
    var Script = function () {

        $().ready(function() {
            $("[data-toggle='switch']").wrap('<div class="switch" />').parent().bootstrapSwitch();
        });
    }();
</script>
</body>
</html>