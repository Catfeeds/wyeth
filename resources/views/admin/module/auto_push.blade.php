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
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet"/>


    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datepicker/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-timepicker/compiled/timepicker.css"/>
    <link rel="stylesheet" type="text/css"
          href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css"/>
    <!--right slidebar-->
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">
    <!--  summernote -->
    <link href="/admin_style/flatlab/assets/summernote/dist/summernote.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet"/>
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
                            编辑预约提醒模版(福利)
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action=""
                                      enctype="multipart/form-data">
                                    <div class="form-group ">
                                        <label for="notify_title" class="control-label col-lg-2">预约标题</label>
                                        <div class="col-lg-6">
                                            <textarea rows="5" class="form-control " id="notify_title"
                                                      name="notify_title">{{ isset($data['notify_title']) ? $data['notify_title'] : '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="notify_content" class="control-label col-lg-2">预约人</label>
                                        <div class="col-lg-6">
                                            <input class="form-control " id="notify_content"
                                                   name="notify_content"
                                                   placeholder="预约人为空则为用户的昵称"
                                                   value="{{ isset($data['notify_content']) ? $data['notify_content'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="notify_odate" class="control-label col-lg-2">预约项目</label>
                                        <div class="col-lg-6">
                                            <input class="form-control" id="notify_odate" name="notify_odate"
                                                   type="text"
                                                   value="{{ isset($data['notify_odate']) ? $data['notify_odate'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="notify_address" class="control-label col-lg-2">预约时间</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="notify_address" name="notify_address"
                                                   minlength="2" type="text"
                                                   placeholder="预约时间为空则为当天日期,如:2017-08-28"
                                                   value="{{ isset($data['notify_address']) ? $data['notify_address'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="notify_remark" class="control-label col-lg-2">预约备注</label>
                                        <div class="col-lg-6">
                                            <textarea rows="5" class="form-control " id="notify_remark"
                                                      name="notify_remark">{{ isset($data['notify_remark']) ? $data['notify_remark'] : '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="notify_url" class="control-label col-lg-2">预约跳转url</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="notify_url" name="notify_url" minlength="2"
                                                   type="text"
                                                   value="{{ isset($data['notify_url']) ? $data['notify_url'] : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group" id="user_shop_div">
                                        <label class="col-sm-2 control-label col-lg-2"
                                               for="inputSuccess">用户类型</label>
                                        <div class="col-lg-10">
                                            <label class="checkbox-inline">
                                                <input type="radio" id="user_shop" name="user_shop"
                                                       value="1" {{ !isset($data['user_shop']) || $data['user_shop'] == 2  ? 'checked' : '' }}>全部用户
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="radio" id="user_shop" name="user_shop"
                                                       value="2" {{ isset($data['user_shop']) && $data['user_shop'] == 1  ? 'checked' : '' }}>有主用户
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="radio" id="user_shop" name="user_shop"
                                                       value="3" {{ isset($data['user_shop']) && $data['user_shop'] == 0  ? 'checked' : '' }}>无主用户
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            <a class="btn btn-success" id="save">保存</a>
                                            <a class="btn btn-success" id="hw_btn_preview">预览</a>
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
<?php echo $footer; ?>
<!--footer end-->
</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="/admin_style/flatlab/js/respond.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.validate.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap-switch.js"></script>
<!--this page plugins-->
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/js/bootbox.4.4.0.min.js"></script>

<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript"
        src="/admin_style/flatlab/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>


<!--summernote-->
<script src="/admin_style/flatlab/assets/summernote/dist/summernote.min.js"></script>

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>

<script>

    // 验证
    validate = $("#courseForm").validate({
        rules: {
            notify_title: "required",
            notify_odate: "required",
        },
        messages: {
            notify_title:"请填写预约标题",
            notify_odate: "请填写预约项目",
        }
    });

    // bootbox
    bootbox.setLocale('zh_CN');
    $.noty.defaults.theme = 'relax';
    // hw_btn_preview
    $('#hw_btn_preview').on("click", function () {
        bootbox.prompt({
            title: "请输入测试者openid",
            value: "owtN6jkVHmd-ctim1pBNtWSqmXkU", //xj
//            value: "owtN6jiokFP8yfTRDL2O5GqX7icg", //xl
            callback: function(openid) {
                if (openid !== null) {
                    var postData = {
                        notify_title:$("#notify_title").val(),
                        notify_content:$("#notify_content").val(),
                        notify_odate:$("#notify_odate").val(),
                        notify_address:$("#notify_address").val(),
                        notify_remark:$("#notify_remark").val(),
                        notify_url:$("#notify_url").val(),
                        notify_openid: openid
                    };
                    $.post("/admin/auto_push/preview_tplmsg", postData, function (data) {
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

    // 保存
    $("#save").on('click', function () {
        var postData = $("#courseForm").serialize();
        if(!validate.form()) {
            return false;
        }
        $.post("/admin/auto_push/save", postData, function (data) {
            if (data.status) {
                noty({text: "保存成功", type: "success", timeout: 2000});
            } else {
                noty({text: data.msg, type: "error", timeout: 2000});
            }
        }, 'json');
        return false;
    });

</script>

</body>
</html>