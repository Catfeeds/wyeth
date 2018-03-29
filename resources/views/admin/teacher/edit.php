<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>教师编辑</title>
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
            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
                <div class="span12">
                    <h3 class="page-title">
                        编辑教师
                    </h3>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span>
                        </li>
                        <li>
                            <a href="/admin/teacher">教师管理</a> <span class="divider">&nbsp;</span>
                        </li>
                        <li><a href="/admin/teacher/edit/<?php echo $id; ?>">教师编辑</a><span class="divider-last">&nbsp;</span></li>
                    </ul>
                </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN SAMPLE FORM widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>教师编辑</h4>
                        </div>
                        <div class="widget-body form">
                            <!-- BEGIN FORM-->
                            <form action="#" method="post" class="form-horizontal add_form" enctype="multipart/form-data">
                                <div class="control-group">
                                    <label class="control-label">教师名称</label>
                                    <div class="controls">
                                        <input type="text" class="span3 " class="name" value="<?php echo $info->name; ?>" name="name" datatype="*" nullmsg="aaa" errormsg="请填写教师名称" />
                                        <span class="help-inline"></span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">教师头像</label>
                                    <div class="controls">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail" style="width: 200px;">
                                                <img src="<?php echo !empty($info->avatar)?$info->avatar:'/admin_style/img/no_image.png'; ?>" alt="" />
                                            </div>
                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                            <div>
                                       <span class="btn btn-file"><span class="fileupload-new">选择图片</span>
                                       <span class="fileupload-exists">更改</span>
                                       <input type="file" class="default" name="avatar" /></span>
                                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">删除</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">任职医院</label>
                                    <div class="controls">
                                        <input type="text" class="span3 " class="name" name="hospital" value="<?php echo $info->hospital; ?>" datatype="*" errormsg="请填写任职医院" />
                                        <span class="help-inline"></span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">职位</label>
                                    <div class="controls">
                                        <input type="text" class="span3 " class="name" name="position" value="<?php echo $info->position; ?>" datatype="*" errormsg="请填写教师职位" />
                                        <span class="help-inline"></span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">教师简介</label>
                                    <div class="controls">
                                        <textarea name="desc" class="span3 " rows="5"><?php echo $info->desc; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success">保存</button>
                                    <button type="reset" class="btn">重置</button>
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
<script src="/admin_style/adminlab/js/jquery.blockui.js"></script>
<script src="/admin_style/adminlab/js/scripts.js"></script>
<script>
    jQuery(document).ready(function() {
        $(".add_form").Validform({
            tiptype:3,
            label:".label",
            showAllError:true,
            ajaxPost:false
        });
        App.init();
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>