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
                            编辑回顾课程
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="" enctype="multipart/form-data">
                                    <section class="panel">
                                        <header class="panel-heading tab-bg-dark-navy-blue ">
                                            <ul id="tabEditReview" class="nav nav-tabs">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#home" aria-expanded="false">基本</a>
                                                </li>
                                                <li class="">
                                                    <a data-toggle="tab" href="#chapter_points" aria-expanded="false">章节要点</a>
                                                </li>
                                            </ul>
                                        </header>
                                        <div class="panel-body">
                                            <div class="tab-content">
                                                <div id="home" class="tab-pane active">
                                                    <div class="form-group ">
                                                        <label for="title" class="control-label col-lg-2">所属课程</label>
                                                        <div class="col-lg-4">
                                                            <select name="cid" class="form-control m-bot15">
                                                                <?php foreach($course_list as $k=>$v){ ?>
                                                                    <option value="<?=$v->id;?>"
                                                                        <?php if($info->cid == $v->id) {?>
                                                                            selected="selected"
                                                                        <?php } ?>
                                                                        ><?=$v->number.'期～'.$v->title;?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">课程回顾类型</label>
                                                        <div class="col-lg-4">
                                                            <select name="review_type" class="form-control m-bot15">
                                                                <option value="1" <?php if ($info->review_type == '1') echo "selected='selected'"; ?>>音频</option>
                                                                <option value="2" <?php if ($info->review_type == '2') echo "selected='selected'"; ?>>视频</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">课程音频</label>
                                                        <div class="controls col-md-9">
                                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                                <span class="btn btn-white btn-file">
                                                                <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择音频文件</span>
                                                                <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                <input type="file" class="default" name="audio">
                                                                </span>
                                                                <span class="fileupload-preview" style="margin-left:5px;"></span>
                                                                <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
                                                            </div>
                                                            <audio src="<?php echo $info->audio; ?>" controls="controls"></audio>
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="container">
                                                    	<label class="control-label col-lg-2">添加回顾视频地址</label>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" id="video" name="video" type="text" @if ($info->video) value="{{$info->video}}" @else value="" @endif>
                                                                </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">手动上传视频</label>
                                                        <div class="controls col-lg-3">
                                                            <div id="container">
                                                                <a id="pickfiles" href="#">
                                                                    <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择视频文件</span>
                                                                    </span>
                                                                </a>
                                                                <table class="fileupload-preview" style=" margin-left:5px; display:inline">
                                                                    <thead></thead>
                                                                    <tbody id="fsUploadProgress"></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <section class="panel col-lg-10">
                                                            <header class="panel-heading head-border">
                                                                课程详情
                                                            </header>
                                                            <div class="panel-body" style="padding:0px">
                                                                <script id="guide" name="guide" type="text/plain"
                                                                        style="margin-left:15px;width:900px;height:300px;">{!! $info->guide !!}</script>
                                                            </div>
                                                        </section>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="notice" class="control-label col-lg-2">状态</label>
                                                        <div class="col-lg-6" style="z-index: 9999">
                                                            <input type="checkbox" data-toggle="switch" value="1" name="status" <?php echo $info->status == 1?'checked="checked"':''; ?>/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-lg-offset-2 col-lg-10">
                                                            <button class="btn btn-danger" type="submit">保存</button>
                                                            <button class="btn btn-default" type="reset">重置</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="questions_and_answers" class="tab-pane">
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-6">保存之前请先删除掉空白的问答</label>
                                                    </div>
                                                    <div class="form-group" id="questions">
                                                        @if ($info->q_and_a)
                                                        @foreach ($info->q_and_a as $k=>$v)
                                                        <div class="form-group question" style="margin-left:15px;" id="question{{$k}}">
                                                            <div class="form-group ">
                                                                <label class="control-label col-lg-2">提问</label>
                                                                <div class="col-lg-6">
                                                                    <input type="hidden" name="q_and_a[{{$k}}][q_uid]" @if(isset($v['q_uid'])) value="{{$v['q_uid']}}" @endif>
                                                                    <input class="form-control" name="q_and_a[{{$k}}][question]" type="text" value="{{$v['question']}}">
                                                                </div>
                                                                <span class="btn btn-white" num="{{$k}}">选择</span>
                                                            </div>
                                                            <div class="form-group ">
                                                                <label class="control-label col-lg-2">回答</label>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" name="q_and_a[{{$k}}][answer]" type="text" value="{{$v['answer']}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                        @else
                                                        <div class="form-group question" style="margin-left:15px;" id="question0">
                                                            <div class="form-group ">
                                                                <label class="control-label col-lg-2">提问</label>
                                                                <div class="col-lg-6">
                                                                    <input type="hidden" name="q_and_a[0][q_uid]">
                                                                    <input class="form-control" name="q_and_a[0][question]" type="text" value="">
                                                                </div>
                                                                <span class="btn btn-white" num="0">选择</span>
                                                            </div>
                                                            <div class="form-group ">
                                                                <label class="control-label col-lg-2">回答</label>
                                                                <div class="col-lg-6">
                                                                    <input class="form-control" name="q_and_a[0][answer]" type="text" value="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-lg-offset-2 col-lg-10">
                                                            <button class="btn btn-primary" type="submit">保存</button>
                                                            <button class="btn btn-default" type="reset">重置</button>
                                                            <button class="btn btn-success" type="button" id="add">增加</button>
                                                            <button class="btn btn-danger" type="button" id="delete">删除最后一组问答</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="chapter_points" class="tab-pane">
                                                    <div style="display:flex; flex-direction: row;width: 100%">
                                                        <div class="txt_audio" style="width: 35%;display:flex;flex-direction: column">
                                                            <div class="form-group">
                                                                <label class="control-label col-lg-6">课程文本数据</label>
                                                                <div style="margin-top: 10%;margin-left: 5%">
                                                                    <textarea  name="content" class="textarea thumbnail" style="width:80%; height: 400px;resize:none; "><?php echo $info->content?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-6">课程音频数据</label>
                                                            </div>
                                                            <div style="margin-top: 20px;">
                                                                <audio src="<?php echo $info->audio ?>" controls="controls"></audio>
                                                            </div>
                                                        </div>
                                                        <div style="display:flex;flex-direction: column; width: 100%">
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-6">保存之前请先删除掉空白行，每章至少要有一节</label>
                                                    </div>
                                                            <div class="form-group" id="gist">
                                                        @if ($info->section)
                                                        @foreach ($info->section as $k=>$v)
                                                        <div class="form-group" content="gist">
                                                            <label class="control-label col-md-1">第 {{$k + 1}} 章:</label>
                                                            <div style="display:flex;flex-direction:row;align-items:center">
                                                                <input placeholder="请填写本章名称(20字)" class="form-control" style="width:50%" name="section[{{$k}}][point]" type="text" value="{{$v['point']}}" onkeyup="checkSize(this)">
                                                                <button class="btn btn-success" type="button" value='{{$k+1}}' id="add-section" style="margin-left: 15px;">新增一节</button>
                                                                <button class="btn btn-danger" id="del-section" type="button" value='{{$k+1}}' style="margin-left: 15px">删除最后一节</button>
                                                         <div style="width:20%"> <span hidden="hidden" style="color:red;font-size:12px;margin-left:15px;width:100%">字数过多!!已删除超出部分</span></div>
							  </div>
                                                            <div class="col-lg-4">
                                                                <input placeholder="请填写时间(秒)" class="form-control" name="section[{{$k}}][second]" type="hidden" value="{{$v['second']}}">
                                                            </div>
                                                            <div  id={{$k+1}}>
                                                                <div  class="form-group">
                                                                </div>
								@if (!empty($v['section']))
								@foreach ($v['section'] as $a=>$item)
                                                                <div class="form-group" content="section" style="margin-left: 5%;">
                                                                    <label class="control-label col-md-1">第 {{$a +1}} 节:</label>
                                                                    <div style="display:flex;align-items:center">
                                                                        <input placeholder="请填写本节名称(20字以内)" class="form-control" style="width:46%"  name="section[{{$k}}][section][{{$a}}][point]" type="text" onkeyup="checkSize1(this)" value="{{$item['point']}}">
                                                                        
                                                                  
                                                                        <input placeholder="填写开始时间(秒)"  class="form-control" name="section[{{$k}}][section][{{$a}}][second]" type="text" style="width:15%;margin-left:15px"  value="{{$item['second']}}">
									 <div style="width:20%"> <span hidden="hidden" style="color:red;font-size:12px;margin-left:10px;">字数过多!!已删除超出部分</span></div>

                                                                    </div>
                                                                    </div>
								@endforeach
								@endif
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                        @else

                                                        <div class="form-group" content="gist" >
                                                            <label class="control-label col-md-1">第 1 章:</label>
                                                            <div  style="display:flex;flex-direction: row;align-items:center">
                                                                <input placeholder="请填写本章名称(20字以内)" class="form-control" style="width:50%"  name="section[0][point]" type="text" value="" onkeyup="checkSize(this)">
                                                                <button class="btn btn-success" type="button" value='1' id="add-section" style="margin-left: 15px;">新增一节</button>
                                                                <button class="btn btn-danger" id="del-section" type="button" value='1' style="margin-left: 15px;">删除最后一节</button>
                                                           <div style="width:20%"> <span hidden="hidden" style="color:red;font-size:12px;margin-left:15px;width:100%">字数过多!!已删除超出部分</span></div>

							    </div>
                                                            <div class="col-lg-4">
                                                                <input placeholder="开始时间(秒)" class="form-control" name="section[0][second]" type="hidden" value="">
                                                            </div>
                                                            <div  id="1">
                                                                <div class="form-group"></div>
                                                            </div>
                                                        </div>
                                                            @endif
                                                            </div>
                                                            <div class="form-group" style="display:flex;align-items: flex-end">
                                                                <div class="col-lg-offset-2 col-lg-10">
                                                                    <button class="btn btn-primary" type="submit">保存</button>
                                                                    <button class="btn btn-default" type="reset">重置</button>
                                                                    <button class="btn btn-success" type="button" id="add-gist">增加一章</button>
                                                                    <button class="btn btn-danger" type="button" id="delete-gist">删除最后一章</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                 </div>
                                            </div>
					                     </div>
                                    </section>
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
<div style="display: none;" id="guide_content"><?=$info->guide;?></div>
<div style="display: none;" id="desc_content"><?=$info->desc;?></div>

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

<!--  ueditor -->
<script type="text/javascript" charset="utf-8" src="/admin_style/flatlab/assets/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/admin_style/flatlab/assets/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/admin_style/flatlab/assets/ueditor/lang/zh-cn/zh-cn.js"></script>


<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<!--this page  script only-->
<script src="/admin_style/flatlab/js/advanced-form-components.js"></script>
<!-- 七牛上传 -->
<script type="text/javascript" src="/js/plupload/moxie.js"></script>
<script type="text/javascript" src="/js/plupload/plupload.dev.js"></script>
<script type="text/javascript" src="/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/assets/common/js/bootbox.min.js"></script>

<script>
    $(document).ready(function () {
        var str = window.location + '';
        console.log(str.indexOf('chapter_points'));
        if (str.indexOf('chapter_points') >= 0) {
            // 选择展示哪个标签页
            $('#tabEditReview li:eq(2) a').tab('show');
            setTimeout(function () {
                $(window).scrollTop(0);
            }, 50);

        }
    });

    function htmlEncode( html ) {
        return document.createElement( 'a' ).appendChild(
                document.createTextNode( html ) ).parentNode.innerHTML;
    };
    var courseReviewQuestions = {!! json_encode($courseReviewQuestions) !!};
    var Script = function () {
        $().ready(function() {
            $("#courseForm").validate({
                rules: {
                    cid: "required"
                },
                messages: {
                    title: "请选择所属课程"
                }
            });
            $("[data-toggle='switch']").wrap('<div class="switch" />').parent().bootstrapSwitch();
            var guideEditor = UE.getEditor('guide', {
                enableAutoSave: false
                , serverUrl: "/admin/course_review/ueditor_image_upload"
                , toolbars: [[
                    'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', '|',
                    'lineheight', '|',
                    'paragraph', 'fontfamily', 'fontsize', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'insertimage', '|'
                ]]
                , imageCompressEnable: false
            });
            var descEditor = UE.getEditor('desc', {
                enableAutoSave: false
                , serverUrl: "/admin/course_review/ueditor_image_upload"
                , toolbars: [[
                    'source', '|', 'undo', 'redo', '|',
                    'bold', 'italic', 'underline', '|',
                    'lineheight', '|',
                    'paragraph', 'fontfamily', 'fontsize', '|',

                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                    'link', 'insertimage', '|'
                ]]
                , imageCompressEnable: false
            });
        });
    }();
</script>

<script>
    //引入Plupload 、qiniu.js后
    var domain = '<?=$domain;?>';
    var uploader = Qiniu.uploader({
        runtimes: 'html5,flash,html4',    //上传模式,依次退化
        browse_button: 'pickfiles',       //上传选择的点选按钮，**必需**
        uptoken_url: '/admin/course_review/getuptoken',            //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
        // uptoken : '', //若未指定uptoken_url,则必须指定 uptoken ,uptoken由其他程序生成
        // unique_names: true, // 默认 false，key为文件名。若开启该选项，SDK为自动生成上传成功后的key（文件名）。
        // save_key: true,   // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK会忽略对key的处理
        domain: domain,   //bucket 域名，下载资源时用到，**必需**
        get_new_uptoken: false,  //设置上传文件的时候是否每次都重新获取新的token
        container: 'container',           //上传区域DOM ID，默认是browser_button的父元素，
        max_file_size: '1000mb',           //最大文件体积限制
        flash_swf_url: 'js/plupload/Moxie.swf',  //引入flash,相对路径
        max_retries: 3,                   //上传失败最大重试次数
        dragdrop: true,                   //开启可拖曳上传
        drop_element: 'container',        //拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
        chunk_size: '4mb',                //分块上传时，每片的体积
        auto_start: true,                 //选择文件后自动上传，若关闭需要自己绑定事件触发上传
        init: {
            'FilesAdded': function(up, files) {
                plupload.each(files, function(file) {
                    // 文件添加进队列后,处理相关的事情
                    var progress = new FileProgress(file, 'fsUploadProgress');
                    progress.setStatus("请等待...");
                    progress.bindUploadCancel(up);
                });
            },
            'BeforeUpload': function(up, file) {
                // 每个文件上传前,处理相关的事情
                var progress = new FileProgress(file, 'fsUploadProgress');
                var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                if (up.runtime === 'html5' && chunk_size) {
                    progress.setChunkProgess(chunk_size);
                }
            },
            'UploadProgress': function(up, file) {
                // 每个文件上传时,处理相关的事情
                var progress = new FileProgress(file, 'fsUploadProgress');
                var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                progress.setProgress(file.percent + "%", file.speed, chunk_size);
            },
            'FileUploaded': function(up, file, info) {
                // 每个文件上传成功后,处理相关的事情
                // 其中 info 是文件上传成功后，服务端返回的json，形式如
                // {
                //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                //    "key": "gogopher.jpg"
                //  }
                // 参考http://developer.qiniu.com/docs/v6/api/overview/up/response/simple-response.html

                var domain = up.getOption('domain');
                var res = $.parseJSON(info);
                var sourceLink = domain + '/' + res.key; // 获取上传成功后的文件的Url
                $('#video').val(sourceLink);
                var progress = new FileProgress(file, 'fsUploadProgress');
                progress.setComplete(up, info);
            },
            'Error': function(up, err, errTip) {
                //上传出错时,处理相关的事情
            },
            'UploadComplete': function() {
                //队列文件处理完毕后,处理相关的事情
            },
            'Key': function(up, file) {
                // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                // 该配置必须要在 unique_names: false , save_key: false 时才生效

                var ext = /\.[^\.]+$/.exec(file.name);
                var key = 'wyeth/course/video/' + new Date().getTime() + ext;
                // do something with key here
                return key
            }
        }
    });

    //精彩问答和章节要点
    $(function(){
        //精彩问答
        $('#add').click(function(){
            var num = $('.question').length;
            var html = (
                '<div class="form-group question" style="margin-left:15px;" id="question'+num+'">'+
                '<div class="form-group">'+
                '<label class="control-label col-lg-2">提问</label>'+
                '<div class="col-lg-6">'+
                '<input type="hidden" name="q_and_a['+num+'][q_uid]">'+
                '<input class="form-control" name="q_and_a['+num+'][question]" type="text" value="">'+
                '</div>'+
                '<span class="btn btn-white" num="'+num+'">选择</span>'+
                '</div>'+
                '<div class="form-group ">'+
                '<label class="control-label col-lg-2">回答</label>'+
                '<div class="col-lg-6">'+
                '<input class="form-control" name="q_and_a['+num+'][answer]" type="text" value="">'+
                '</div>'+
                '</div>'+
                '</div>'
            );
            $('#questions').append(html);
        });
        $('#delete').click(function(){
            var num = $('.question').length - 1;
            $('#question'+num).remove();
        });
        //选择按钮
        $('#questions').on('click', 'span[num]', function(){
            var num = $(this).attr('num');
            var message = "<select name='question' class='form-control m-bot15'>"+
                "<option uid=''></option>";
            $.each(courseReviewQuestions, function(i, n) {
                var content = htmlEncode(n.content);
                message += "<option uid='" + n.author_id + "'>" + content + "</option>";
            });
            message += "</select>";
            bootbox.dialog({
                message: message,
                title: "请选择问题",
                buttons: {
                    success: {
                        label: "确认",
                        className: "btn-success",
                        callback: function() {
                            var content = $('select[name="question"]').find("option:selected").text(),
                                uid = $('select[name="question"]').find("option:selected").attr('uid');
                            $('#questions input[name="q_and_a['+num+'][question]"]').val(content);
                            $('#questions input[name="q_and_a['+num+'][q_uid]"]').val(uid);
                        }
                    },
                    danger: {
                        label: "取消",
                        className: "btn-danger",
                        callback: function() {

                        }
                    }
                }
            });
        });
        //章节要点
        $('body').on('click','#add-gist',function(){
            var gist_num = $("#gist div[content='gist']").length + 1,
                name_num = $("#gist div[content='gist']").length;
            var html = (
                '<div class="form-group" content="gist">'+
                '<label class="control-label col-md-1">第 '+gist_num+' 章:</label>'+
                '<div style="display:flex;align-items:center">'+
                '<input placeholder="请填写本章名称(20字以内)" class="form-control" style="width:50%" name="section['+name_num+'][point]" type="text" value="" onkeyup="checkSize(this)">'+
                '<button class="btn btn-success" type="button" id="add-section" value='+gist_num+' style="margin-left: 15px;">新增一节</button>'+
                '<button class="btn btn-danger" type="button" id="del-section" value='+gist_num+' style="margin-left: 15px;">删除最后一节</button>'+
		'<div style="width:20%"> <span hidden="hidden" style="color:red;font-size:12px;margin-left:15px;">字数过多!!已删除超出部分</span></div>'+
                '</div>'+
                    '<div class="col-lg-4">'+
                '<input placeholder="开始时间(秒)" class="form-control" name="section['+name_num+'][second]" style="margin-left:10px;width:15%"  type="hidden" value="" >'+
                '</div>'+
                '<div  id='+gist_num+'>'+
                '<div  class="form-group">'+
                '</div>'+
                '</div>'+
                '</div>'
            );
            $('#gist').append(html);
        });
        $('body').on('click','#add-section',function(){
            console.log($(this).context.value);
            var num = $(this).context.value;
	    var real_num = num-1;
            var sec_num = $("#"+num).children('div').length;
	    var sec_name_num = sec_num-1; 
		console.log(sec_num);
            var sec_html = (
                '<div class="form-group" content="section" style="margin-left: 5%;">'+
                '<label class="control-label col-md-1">第 '+sec_num+' 节:</label>'+
                '<div  style="display:flex;align-items:center">'+
                '<input placeholder="请填写本节名称(20字以内)" class="form-control" style="width:46%"  name="section['+real_num+'][section]['+sec_name_num+'][point]" type="text" value="" onkeyup="checkSize1(this)">'+
                '<input placeholder="开始时间(秒)"  class="form-control" name="section['+real_num+'][section]['+sec_name_num+'][second]" style="width:15%;margin-left:15px"  type="text" value="">'+
                '<div style="width:20%"> <span hidden="hidden" style="color:red;font-size:12px;margin-left:15px;">字数过多!!已删除超出部分</span></div>'+
		        '</div>'+
                '</div>'
            );
            $("#"+num).append(sec_html);
        });
        $('#delete-gist').click(function(){
            if($("#gist div[content='gist']").length <=1){
                return ;
            }else{
                $("#gist div[content='gist']").slice(-1).remove();
            }
        });
        $("body").on('click','#del-section',function(){
            console.log("delete",$(this).context.value);
            var sec = $(this).context.value;
            if($("#"+sec).children('div').length <= 1) return;
            $("#"+sec).children('div').slice(-1).remove();
        });
    });
</script>
<script>
    function checkSize(obj) {
        var value = $(obj).val();
        var length = value.length;
        let sum =0;
        let num_1 = 0;
        let num_2 = 1;
        var beyond = false;

        for (let i = 0; i < value.length; i++) {
            if ((value.charCodeAt(i) >= 0) && (value.charCodeAt(i) <= 255)) {
                sum = sum + 1;
                num_1 += 1;
            } else {
                sum = sum + 2;
                num_2 += 1;
            }
            if (sum > 40) {
                beyond = true;
                value = value.slice(0,i);
                $(obj).next().next().next().children("span").prop("hidden",false);
		 $(obj).val(value);
                console.log(value);
            }else{
                 $(obj).next().next().next().children("span").prop("hidden",true);
                }
        }
        if (beyond) {
        }
    }
    function checkSize1(obj) {
        var value = $(obj).val();
        var length = value.length;
        let sum =0;
        var beyond = false;

        for (let i = 0; i < value.length; i++) {
            if ((value.charCodeAt(i) >= 0) && (value.charCodeAt(i) <= 255)) {
                sum = sum + 1;
            } else {
                sum = sum + 2;
            }
            if (sum >= 40) {
                beyond = true;
                value = value.slice(0,i);
                 $(obj).next().next().children("span").prop("hidden",false);
		$(obj).val(value);
                console.log(value);
            }else{
                 $(obj).next().next().children("span").prop("hidden",true);
                }
        }
        if (beyond) {
        }
    }
</script>
</body>
</html>
