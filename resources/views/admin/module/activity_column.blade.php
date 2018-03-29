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
    <section id="main-content">
        <section class="wrapper site-min-height">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            专栏活动编辑
                        </header>
                        <form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="/admin/app_config/activity_column/store" enctype="multipart/form-data">
                            <div class="panel-body">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <img data-src="" src="{!! array_key_exists('img', $data) ? $data['img'] : '' !!}" alt="">
                                        <input name="hideImg" value="{!! array_key_exists('img', $data) ? $data['img'] : '' !!}" style="display: none">
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
                            <div class="panel-body">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <img data-src="" src="{!! array_key_exists('qrcode', $data) ? $data['qrcode'] : '' !!}" alt="">
                                        <input name="hideQrcode" value="{!! array_key_exists('qrcode', $data) ? $data['qrcode'] : '' !!}" style="display: none">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                       <span class="btn btn-white btn-file">
                                       <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                       <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                       <input type="file" class="default" name="qrcode">
                                       </span>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <input class="form-control" placeholder="请输入活动title" value="{!! array_key_exists('title', $data) ? $data['title'] : '' !!}" name="title">
                            </div>
                            <div class="panel-body">
                                <input class="form-control" placeholder="请输入音频1链接" value="{!! array_key_exists('voice1', $data) ? $data['voice1'] : '' !!}" name="voice1">
                            </div>
                            <div class="panel-body">
                                <input class="form-control" placeholder="请输入音频2链接" value="{!! array_key_exists('voice2', $data) ? $data['voice2'] : '' !!}" name="voice2">
                            </div>
                            <div class="panel-body">
                                <input class="form-control" placeholder="请输入音频3链接" value="{!! array_key_exists('voice3', $data) ? $data['voice3'] : '' !!}" name="voice3">
                            </div>
                            <div class="panel-body">
                                <input class="form-control" placeholder="请输入音频4链接" value="{!! array_key_exists('voice4', $data) ? $data['voice4'] : '' !!}" name="voice4">
                            </div>
                            <div class="panel-body">
                                <div class="panel-body" style="padding:0px">
                                    <script id="text1" name="text1" type="text/plain"
                                            style="margin-left:15px;width:1024px;height:300px;">{!! array_key_exists('text1', $data) ? $data['text1'] : '' !!}</script>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="panel-body" style="padding:0px">
                                    <script id="text2" name="text2" type="text/plain"
                                            style="margin-left:15px;width:1024px;height:300px;">{!! array_key_exists('text2', $data) ? $data['text2'] : '' !!}</script>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="panel-body" style="padding:0px">
                                    <script id="text3" name="text3" type="text/plain"
                                            style="margin-left:15px;width:1024px;height:300px;">{!! array_key_exists('text3', $data) ? $data['text3'] : '' !!}</script>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="panel-body" style="padding:0px">
                                    <script id="text4" name="text4" type="text/plain"
                                            style="margin-left:15px;width:1024px;height:300px;">{!! array_key_exists('text4', $data) ? $data['text4'] : '' !!}</script>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="panel-body" style="padding:0px">
                                    <script id="text5" name="text5" type="text/plain"
                                            style="margin-left:15px;width:1024px;height:300px;">{!! array_key_exists('text5', $data) ? $data['text5'] : '' !!}</script>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="panel-body" style="padding:0px">
                                    <script id="text6" name="text6" type="text/plain"
                                            style="margin-left:15px;width:1024px;height:300px;">{!! array_key_exists('text6', $data) ? $data['text6'] : '' !!}</script>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="panel-body" style="padding:0px">
                                    <script id="text7" name="text7" type="text/plain"
                                            style="margin-left:15px;width:1024px;height:300px;">{!! array_key_exists('text7', $data) ? $data['text7'] : '' !!}</script>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button class="btn btn-danger" type="submit">保存</button>
                                    <button class="btn btn-default" type="reset">重置</button>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </section>
    </section>
</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>

<!--  ueditor -->
<script type="text/javascript" charset="utf-8" src="/admin_style/flatlab/assets/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/admin_style/flatlab/assets/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/admin_style/flatlab/assets/ueditor/lang/zh-cn/zh-cn.js"></script>

<!-- for this page -->
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>

<script>
    var Script = function () {
        $().ready(function() {
            var text1Editor = UE.getEditor('text1', {
                enableAutoSave: false
                , serverUrl: "/admin/course_review/ueditor_image_upload"
                , toolbars: [[
                    'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', '|',
                    'lineheight', '|',
                    'paragraph', 'fontfamily', 'fontsize', 'forecolor', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'simpleupload', '|'
                ]]
                , fontsize: [20, 24, 28, 30, 32, 36, 48]
            });
            var text2Editor = UE.getEditor('text2', {
                enableAutoSave: false
                , serverUrl: "/admin/course_review/ueditor_image_upload"
                , toolbars: [[
                    'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', '|',
                    'lineheight', '|',
                    'paragraph', 'fontfamily', 'fontsize', 'forecolor', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'simpleupload', '|'
                ]]
                , fontsize: [20, 24, 28, 30, 32, 36, 48]
            });
            var text3Editor = UE.getEditor('text3', {
                enableAutoSave: false
                , serverUrl: "/admin/course_review/ueditor_image_upload"
                , toolbars: [[
                    'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', '|',
                    'lineheight', '|',
                    'paragraph', 'fontfamily', 'fontsize', 'forecolor', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'simpleupload', '|'
                ]]
                , fontsize: [20, 24, 28, 30, 32, 36, 48]
            });
            var text4Editor = UE.getEditor('text4', {
                enableAutoSave: false
                , serverUrl: "/admin/course_review/ueditor_image_upload"
                , toolbars: [[
                    'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', '|',
                    'lineheight', '|',
                    'paragraph', 'fontfamily', 'fontsize', 'forecolor', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'simpleupload', '|'
                ]]
                , fontsize: [20, 24, 28, 30, 32, 36, 48]
            });
            var text5Editor = UE.getEditor('text5', {
                enableAutoSave: false
                , serverUrl: "/admin/course_review/ueditor_image_upload"
                , toolbars: [[
                    'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', '|',
                    'lineheight', '|',
                    'paragraph', 'fontfamily', 'fontsize', 'forecolor', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'simpleupload', '|'
                ]]
                , fontsize: [20, 24, 28, 30, 32, 36, 48]
            });
            var text6Editor = UE.getEditor('text6', {
                enableAutoSave: false
                , serverUrl: "/admin/course_review/ueditor_image_upload"
                , toolbars: [[
                    'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', '|',
                    'lineheight', '|',
                    'paragraph', 'fontfamily', 'fontsize', 'forecolor', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'simpleupload', '|'
                ]]
                , fontsize: [20, 24, 28, 30, 32, 36, 48]
            });
            var text7Editor = UE.getEditor('text7', {
                enableAutoSave: false
                , serverUrl: "/admin/course_review/ueditor_image_upload"
                , toolbars: [[
                    'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', '|',
                    'lineheight', '|',
                    'paragraph', 'fontfamily', 'fontsize', 'forecolor', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'simpleupload', '|'
                ]]
                , fontsize: [20, 24, 28, 30, 32, 36, 48]
            });
        });
    }();
</script>
</body>
</html>