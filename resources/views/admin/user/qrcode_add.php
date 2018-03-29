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
        编辑用户群
    </header>
    <div class="panel-body">
        <div class=" form">
            <form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-lg-2">图片</label>
                    <div class="col-md-9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="<?php echo !empty($info->img)?$info->img:'/admin_style/img/no_image.png'; ?>" alt="">
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
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
                <div class="form-group ">
                    <label for="teacher_hospital" class="control-label col-lg-2">描述</label>
                    <div class="col-lg-6">
                        <input class=" form-control" id="word" name="word" minlength="2" type="text" value="<?php echo isset($info->word)?$info->word:''; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">省份</label>
                    <div class="col-lg-6">
                        <div class="shihe_1" style="float: left;">
                            <select name="province" class="form-control m-bot15">
                                <option value="天津" <?php echo isset($info->province)&&$info->province=='天津'?'selected':''; ?>>天津</option>
                                <option value="北京" <?php echo isset($info->province)&&$info->province=='北京'?'selected':''; ?>>北京</option>
                                <option value="广东" <?php echo isset($info->province)&&$info->province=='广东'?'selected':''; ?>>广东</option>
                                <option value="上海" <?php echo isset($info->province)&&$info->province=='上海'?'selected':''; ?>>上海</option>
                                <option value="四川" <?php echo isset($info->province)&&$info->province=='四川'?'selected':''; ?>>四川</option>
                                <option value="浙江" <?php echo isset($info->province)&&$info->province=='浙江'?'selected':''; ?>>浙江</option>
                                <option value="江苏" <?php echo isset($info->province)&&$info->province=='江苏'?'selected':''; ?>>江苏</option>
                                <option value="湖南" <?php echo isset($info->province)&&$info->province=='湖南'?'selected':''; ?>>湖南</option>
                                <option value="福建" <?php echo isset($info->province)&&$info->province=='福建'?'selected':''; ?>>福建</option>
                                <option value="山东" <?php echo isset($info->province)&&$info->province=='山东'?'selected':''; ?>>山东</option>
                            </select>
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2">阶段</label>
                    <div class="col-lg-6">
                        <div class="shihe_1" style="float: left;">
                            <select name="stage" class="form-control m-bot15">
                                <option value="1" <?php echo isset($info->stage)&&$info->stage==1?'selected':''; ?>>孕期</option>
                                <option value="2" <?php echo isset($info->stage)&&$info->stage==2?'selected':''; ?>>0-12月</option>
                            </select>
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button class="btn btn-danger" type="submit">保存</button>
                        <button class="btn btn-default" type="reset">重置</button>
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
<?php echo $footer;?>
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
</body>
</html>