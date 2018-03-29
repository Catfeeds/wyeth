<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>微信群设置</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />
    <link href="/admin_style/adminlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="/admin_style/adminlab/css/style.css" rel="stylesheet" />
    <link href="/admin_style/adminlab/css/style_responsive.css" rel="stylesheet" />
    <link href="/admin_style/adminlab/css/style_default.css" rel="stylesheet" id="style_color" />

    <link href="/admin_style/adminlab/assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/uniform/css/uniform.default.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/chosen-bootstrap/chosen/chosen.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/jquery-tags-input/jquery.tagsinput.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/clockface/css/clockface.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <!--<link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/bootstrap-datepicker/css/datepicker.css" />-->
    <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" href="/admin_style/adminlab/assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />
    <link rel="stylesheet" href="/admin_style/adminlab/assets/data-tables/DT_bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/bootstrap-daterangepicker/daterangepicker.css" />
    <link href="/admin_style/adminlab/assets/validform/style.css" rel="stylesheet" id="style_color" />
    <style type="text/css">
        body{ background:#f7f7f7 !important;}
    </style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">

<!-- BEGIN CONTAINER -->
<div class="row-fluid">
    <!-- BEGIN PAGE -->
    <div>
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid">
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN SAMPLE FORM widget-->
                    <div class="widget">
                        <div class="widget-body form">
                            <!-- BEGIN FORM-->
                            <form action="#" class="form-horizontal add_form" method="post" enctype="multipart/form-data">
                                <div class="control-group">
                                    <label class="control-label" style="width: 100px; margin-left: 50px;">群名称</label>
                                    <div class="controls" style="margin-left: 100px;">
                                        <input type="text" class="span2" style="width: 200px;" name="name" value="<?php echo !empty($info->name)?$info->name:''; ?>" />
                                        <span class="help-inline"></span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" style="width: 100px; margin-left: 50px;">群二维码</label>
                                    <div class="controls" style="margin-left: 100px;">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="<?php echo !empty($info->img)?$info->img:'/admin_style/img/no_image.png'; ?>" alt="" />
                                            </div>
                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                            <div style="margin-left: 50px;">
                                       <span class="btn btn-file"><span class="fileupload-new">选择图片</span>
                                       <span class="fileupload-exists">更改</span>
                                       <input type="file" class="default" name="img" /></span>
                                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">删除</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" style="width: 100px; margin-left: 50px;">限制加群人数</label>
                                    <div class="controls" style="margin-left: 100px;">
                                        <input type="text" class="span2 popovers" style="width: 200px;" name="limit_num" data-placement="top" value="<?php echo !empty($info->limit_num)?$info->limit_num:'0'; ?>" data-trigger="hover" data-content="限制加群人数，达到后则不再展示该群信息。
                                        0为不限." />
                                        <span class="help-inline"></span>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" style="margin-left: 50px;" class="btn btn-success">保存</button>
                                    <button type="button" class="btn">重置</button>
                                </div>
                            </form>
                            <!-- END FORM-->
                        </div>
                    </div>
                    <!-- END SAMPLE FORM widget-->
                </div>
            </div>
        </div>
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN JAVASCRIPTS -->
<!-- Load javascripts at bottom, this will reduce page load time -->
<script src="/admin_style/adminlab/js/jquery-1.8.3.min.js"></script>
<script src="/admin_style/adminlab/assets/validform/Validform_v5.3.2_min.js"></script>
<script src="/admin_style/adminlab/assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/admin_style/adminlab/assets/bootstrap/js/bootstrap-fileupload.js"></script>

<script type="text/javascript" src="/admin_style/adminlab/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/admin_style/adminlab/assets/uniform/jquery.uniform.min.js"></script>

<script type="text/javascript" src="/admin_style/adminlab/assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>

<script type="text/javascript" src="/admin_style/adminlab/assets/My97DatePicker/WdatePicker.js"></script>
<script src="/admin_style/adminlab/js/scripts.js"></script>
<script>
    jQuery(document).ready(function() {
        App.init();
    });

</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>