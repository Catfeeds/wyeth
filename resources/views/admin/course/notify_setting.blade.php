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


    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <!--right slidebar-->
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">
    <!--  summernote -->
    <link href="/admin_style/flatlab/assets/summernote/dist/summernote.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />
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
    编辑课程通知模版
</header>
<div class="panel-body">
<div class=" form">
<form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="cid" value="<?=$id;?>">
<div class="form-group ">
    <label for="notify_content" class="control-label col-lg-2">上课通知简介</label>
    <div class="col-lg-6">
        <textarea rows="5" class="form-control " id="notify_title" name="notify_title"><?=$info->notify_title ?: '';?></textarea>
    </div>
</div>
<div class="form-group ">
    <label for="notify_content" class="control-label col-lg-2">上课通知内容</label>
    <div class="col-lg-6">
        <textarea rows="5" class="form-control " id="notify_content" name="notify_content"><?=$info->notify_content ?: '';?></textarea>
    </div>
</div>
<div class="form-group ">
    <label for="notify_content" class="control-label col-lg-2">上课通知时间</label>
    <div class="col-lg-6">
        <input class="form-control" id="notify_odate" name="notify_odate" type="text" value="<?=$info->notify_odate ?: '';?>">
    </div>
</div>
<div class="form-group ">
    <label for="notify_content" class="control-label col-lg-2">上课通知地点</label>
    <div class="col-lg-6">
        <input class=" form-control" id="notify_address" name="notify_address" minlength="2" type="text" value="<?=$info->notify_address ?: '';?>">
    </div>
</div>
<div class="form-group ">
    <label for="notify_remark" class="control-label col-lg-2">上课通知备注</label>
    <div class="col-lg-6">
        <textarea rows="5" class="form-control " id="notify_remark" name="notify_remark"><?=$info->notify_remark ?: '';?></textarea>
    </div>
</div>
<div class="form-group ">
    <label for="notify_url" class="control-label col-lg-2">上课通知跳转url</label>
    <div class="col-lg-6">
        <input class=" form-control" id="notify_url" name="notify_url" minlength="2" type="text" value="<?=$info->notify_url ?: '';?>">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label col-lg-2" for="send_type">模板类型</label>
    <div class="col-lg-10">
        <label class="checkbox-inline">
            <input type="radio" id="template_id1" name="template_id" value="1" {{ $info->notify_template_id == 1 ? 'checked' : '' }}>开课提醒
        </label>
        <label class="checkbox-inline">
            <input type="radio" id="template_id2" name="template_id" value="4" {{ $info->notify_template_id == 4 ? 'checked' : '' }}>课程回顾
        </label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label col-lg-2" for="send_type">发送模板消息方式</label>
    <div class="col-lg-10">
        <label class="checkbox-inline">
            <input type="radio" id="send_type1" name="send_type" value="1" checked>普通
        </label>
        <label class="checkbox-inline">
            <input type="radio" id="send_type2" name="send_type" value="2">指定openid
        </label>
    </div>
</div>
<div class="form-group " style="display: none;" id="openid_div">
    <label for="notify_remark" class="control-label col-lg-2">openid(一行一个openid)</label>
    <div class="col-lg-6">
        <textarea rows="10" class="form-control " id="openids" name="openids"></textarea>
    </div>
</div>
<div class="form-group" id="inclass_status_div">
    <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">上课状态(报名用户)</label>
    <div class="col-lg-10">
        <label class="checkbox-inline">
            <input type="radio" id="inclass_status" name="inclass_status" value="1" checked>全部用户
        </label>
        <label class="checkbox-inline">
            <input type="radio" id="inclass_status" name="inclass_status" value="2">上课
        </label>
        <label class="checkbox-inline">
            <input type="radio" id="inclass_status" name="inclass_status" value="3">未上课
        </label>
    </div>
</div>
<div class="form-group" id="user_shop_div">
    <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">用户类型(报名用户)</label>
    <div class="col-lg-10">
        <label class="checkbox-inline">
            <input type="radio" id="user_shop" name="user_shop" value="1" checked>全部用户
        </label>
        <label class="checkbox-inline">
            <input type="radio" id="user_shop" name="user_shop" value="2">有主用户
        </label>
        <label class="checkbox-inline">
            <input type="radio" id="user_shop" name="user_shop" value="3">无主用户
        </label>
    </div>
</div>
<div class="form-group" id="sign_time">
    <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">指定报名时间段</label>
    <div class="col-md-3 col-xs-11">
        <input type="text" class="form-control sign-start-date-picker" name="sign_start">
    </div>
    <div class="col-md-3 col-xs-11">
        <input type="text" class="form-control sign-end-date-picker" name="sign_end">
    </div>
</div>
<div class="form-group" id="crontab_push">
    <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">添加定时推送</label>
    <div class="col-md-3 col-xs-11">
        <input type="text" class="form-control course-push-date-picker">
    </div>
    <a class="btn btn-danger"  id="hw_btn_course_push">添加定时推送</a>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">备注</label>
    <div class="col-lg-6">
        <textarea rows="5" class="form-control " id="remark" name="remark"><?=$info->remark ?: '';?></textarea>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <a class="btn btn-success" id="save">保存</a>
        <a class="btn btn-success" id="hw_btn_preview">预览</a>
        <a class="btn btn-danger"  id="hw_btn_send_wx">发送微信模板</a>
        <a class="btn btn-danger"  id="hw_btn_send_sq">发送SQ模板</a>
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
<!--vertical center Modal start-->
    <!--vertical center Modal start-->
    <div class="modal fade modal-dialog-center " id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content-wrap">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">发送模版消息</h4>
                    </div>
                    <div class="modal-body">
                        真得发送出去了啊，不能反悔的啊！
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">取消</button>
                        <button class="btn btn-warning" type="button" id="preview_send" >确认</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- vertical center Modal end -->

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
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/js/bootbox.4.4.0.min.js"></script>

<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>


<!--summernote-->
<script src="/admin_style/flatlab/assets/summernote/dist/summernote.min.js"></script>

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>

<script>
    var cid = '<?=$id;?>';
    $(function() {
        // 验证
        validate = $("#courseForm").validate({
            rules: {
                notify_title: "required",
                notify_content: "required",
                notify_odate: "required",
                notify_address: "required"
            },
            messages: {
                notify_title:"请填写通知简介",
                notify_content: "请填写通知内容",
                notify_odate: "请填写通知时间",
                notify_address: "请填写通知地址"
            }
        });

        $('.course-push-date-picker').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss',
            autoclose: true
        });
        $('.sign-start-date-picker').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss',
            autoclose: true
        });
        $('.sign-end-date-picker').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss',
            autoclose: true
        });

        // bootbox
        bootbox.setLocale('zh_CN');
        $.noty.defaults.theme = 'relax';
        // hw_btn_preview
        $('#hw_btn_preview').on("click", function () {
            bootbox.prompt({
                title: "请输入测试者openid",
                //xj owtN6jkVHmd-ctim1pBNtWSqmXkU
                value: "owtN6jmOEj_wTck99vCkLoHZV1k0",
                callback: function(openid) {
                    if (openid !== null) {
                        var postData = {
                            notify_title:$("#notify_title").val(),
                            notify_content:$("#notify_content").val(),
                            notify_odate:$("#notify_odate").val(),
                            notify_address:$("#notify_address").val(),
                            notify_remark:$("#notify_remark").val(),
                            notify_url:$("#notify_url").val(),
                            notify_openid: openid,
                            template_id:$('input[name="template_id"]:checked').val(),
                            cid: cid
                        };
                        $.post("/admin/course/preview_tplmsg", postData, function (data) {
                            if (data == 1) {
                                noty({text: "发送成功", type: "success", timeout: 2000});
                            } else {
                                noty({text: data.msg || "发送失败", type: "error", timeout: 2000});
                            }
                        }, 'json');
                    }
                }
            });
        });

        // hw_btn_send_wx
        $('#hw_btn_send_wx').on("click", function () {
            bootbox.confirm("确认群发微信模板消息!", function(result){
                // 这里返回值是控制确认对话框是否关闭
                if (!result) {
                    return true;
                }
                if(!validate.form()) {
                    noty({text: "请填写完表单", type: "error", timeout: 2000});
                    return true;
                }
                var send_type = $("[name=send_type]:checked").val();
                var openids = $("#openids").val();
                if (send_type == '2' && !openids.length) {
                    noty({text: "请填写指定openid", type: "error", timeout: 2000});
                    return true;
                }
                var postData = $("#courseForm").serialize();
                $.post("/admin/course/send_tplmsg", postData, function (data) {
                    if (data.status == 1) {
                        noty({text: data.msg, type: "success", timeout: 2000});
                    } else {
                        noty({text: data.msg, type: "error", timeout: 2000});
                    }
                }, 'json');
            });
        });

        // hw_btn_send_sq
        $('#hw_btn_send_sq').on("click", function () {
            bootbox.confirm("确认群发手Q模板消息!", function(result){
                // 这里返回值是控制确认对话框是否关闭
                if (!result) {
                    return true;
                }
                if(!validate.form()) {
                    noty({text: "请填写完表单", type: "error", timeout: 2000});
                    return true;
                }
                var send_type = $("[name=send_type]:checked").val();
                var openids = $("#openids").val();
                if (send_type == '2' && !openids.length) {
                    noty({text: "请填写指定openid", type: "error", timeout: 2000});
                    return true;
                }
                var postData = $("#courseForm").serialize();
                $.post("/admin/course/sendSQTemplateMessage", postData, function (data) {
                    if (data.status == 1) {
                        noty({text: data.msg, type: "success", timeout: 2000});
                    } else {
                        noty({text: data.msg, type: "error", timeout: 2000});
                    }
                }, 'json');
            });
        });

        // 保存
        $("#save").on('click', function () {
            if(!validate.form()) {
                return false;
            }
            var postData = $("#courseForm").serialize();
            $.post("/admin/course/tplmsg_save", postData, function(data){
                if(data.status){
                    noty({text: "保存成功", type: "success", timeout: 2000});
                } else {
                    noty({text: data.msg, type: "error", timeout: 2000});
                }
            }, 'json');
            return false;
        });

        $("input[name=send_type]").click(function() {
            var send_type = $("[name=send_type]:checked").val();
            if (send_type == '2') {
                $("#openid_div").show();
                $("#inclass_status_div").hide();
                $("#user_shop_div").hide();
                $("#sign_time").hide();
                $("#crontab_push").hide();
            } else {
                $("#openid_div").hide();
                $("#inclass_status_div").show();
                $("#user_shop_div").show();
                $("#sign_time").show();
                $("#crontab_push").show();
            }
        });

        //添加定时推送
        $('#hw_btn_course_push').click(function () {
            bootbox.confirm("确认添加定时推送吗? 推送内容如有修改,请先保存╮(╯▽╰)╭", function(result){
                // 这里返回值是控制确认对话框是否关闭
                if (!result) {
                    return true;
                }
                var push_time = $('.course-push-date-picker').val();
                if(!push_time) {
                    noty({text: "请填写推送时间", type: "error", timeout: 2000});
                    return true;
                }
                var postData = {
                    'cid': cid,
                    'push_time': push_time,
                    'sign_start': $('.sign-start-date-picker').val(),
                    'sign_end': $('.sign-end-date-picker').val()
                };
                $.post("/admin/course_push/add", postData, function (data) {
                    if (data.status == 1) {
                        noty({text: data.msg, type: "success", timeout: 2000});
                    } else {
                        noty({text: data.msg, type: "error", timeout: 2000});
                    }
                }, 'json');
            });
        })
    });
</script>
</body>
</html>
