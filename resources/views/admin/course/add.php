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
                            添加课程
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="" enctype="multipart/form-data">
                                    <div class="form-group ">
                                        <label for="title" class="control-label col-lg-2">课程名称</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="title" name="title" minlength="2" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="title" class="control-label col-lg-2">期数</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="number" name="number" datatype="n" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">课程日期</label>
                                        <div class="col-md-3 col-xs-11">
                                            <input class="form-control form-control-inline input-medium date-picker" size="16" type="text" name="start_day">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">开始时间</label>
                                        <div class="col-md-3 col-xs-11">
                                            <div class="input-group bootstrap-timepicker">
                                                <input type="text" class="form-control timepicker-24-start" name="start_time" value="16:30">
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
                                                <input type="text" class="form-control timepicker-24-end" name="end_time" value="17:30">
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
                                                    <img src="/admin_style/img/no_image.png" alt="">
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
                                        <label for="desc" class="control-label col-lg-2">说明</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control " id="desc" name="desc"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="notice" class="control-label col-lg-2">注意事项</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control " id="notice" name="notice">“魔栗妈咪学院”版权归属景栗科技所有，相关课程内容由景栗科技提供。平台相关内容不作为医学诊断参考，如情况严重，建议及时就医。</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">课程适合阶段</label>
                                        <div class="col-lg-6">
                                            <div class="shihe_1" style="float: left;">
                                                <select name="stage[1][1]" class="form-control m-bot15" onchange="shihe(this);" style="float: left; width: 70px;">
                                                    <option value="备孕">备孕</option>
                                                    <option value="孕中">孕中</option>
                                                    <option value="宝宝">宝宝</option>
                                                </select>
                                                <select class="yunzhong form-control m-bot15" name="stage[1][2]" style="display: none; float: left; width: 100px;">
                                                    <option value="0个月">0个月</option>
                                                    <option value="1个月">1个月</option>
                                                    <option value="2个月">2个月</option>
                                                    <option value="3个月">3个月</option>
                                                    <option value="4个月">4个月</option>
                                                    <option value="5个月">5个月</option>
                                                    <option value="6个月">6个月</option>
                                                    <option value="7个月">7个月</option>
                                                    <option value="8个月">8个月</option>
                                                    <option value="9个月">9个月</option>
                                                    <option value="10个月">10个月</option>
                                                </select>
                                                <select class="baobao form-control m-bot15" name="stage[1][3]" style="display: none; float: left; width: 70px;">
                                                    <option value="0岁">0岁</option>
                                                    <option value="1岁">1岁</option>
                                                    <option value="2岁">2岁</option>
                                                    <option value="3岁">3岁</option>
                                                    <option value="4岁">4岁</option>
                                                    <option value="5岁">5岁</option>
                                                    <option value="6岁">6岁</option>
                                                </select>
                                            </div>
                                            <div style="float:left; margin: 0 10px; line-height: 32px;">至</div>
                                            <div class="shihe_2" style="float: left">
                                                <select name="stage[2][1]" class="form-control m-bot15" onchange="shihe(this);" style="float: left; width:70px;">
                                                    <option value="备孕">备孕</option>
                                                    <option value="孕中">孕中</option>
                                                    <option value="宝宝">宝宝</option>
                                                </select>
                                                <select class="yunzhong form-control m-bot15" name="stage[2][2]" style="display: none; float: left; width:100px;">
                                                    <option value="0个月">0个月</option>
                                                    <option value="1个月">1个月</option>
                                                    <option value="2个月">2个月</option>
                                                    <option value="3个月">3个月</option>
                                                    <option value="4个月">4个月</option>
                                                    <option value="5个月">5个月</option>
                                                    <option value="6个月">6个月</option>
                                                    <option value="7个月">7个月</option>
                                                    <option value="8个月">8个月</option>
                                                    <option value="9个月">9个月</option>
                                                    <option value="10个月">10个月</option>
                                                </select>
                                                <select class="baobao form-control m-bot15" name="stage[2][3]" style="display: none; float: left; width:70px;">
                                                    <option value="0岁">0岁</option>
                                                    <option value="1岁">1岁</option>
                                                    <option value="2岁">2岁</option>
                                                    <option value="3岁">3岁</option>
                                                    <option value="4岁">4岁</option>
                                                    <option value="5岁">5岁</option>
                                                    <option value="6岁">6岁</option>
                                                </select>
                                            </div>
                                            <span class="help-inline"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="notice" class="control-label col-lg-2">状态</label>
                                        <div class="col-lg-6">
                                            <input type="checkbox" data-toggle="switch" value="1" name="display_status"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">适合人群</label>
                                        <div class="col-lg-10">
                                            <label class="checkbox-inline">
                                                <input type="radio" id="user_type" name="user_type" value="0" checked>全部用户
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="radio" id="user_type" name="user_type" value="1">微信用户
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="radio" id="user_type" name="user_type" value="2">手Q用户
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="sign_limit" class="control-label col-lg-2">报名上限</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="sign_limit" name="sign_limit" minlength="2" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="teacher_name" class="control-label col-lg-2">讲师名称</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="teacher_name" name="teacher_name" minlength="2" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">讲师头像</label>
                                        <div class="col-md-9">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="/admin_style/img/no_image.png" alt="">
                                                </div>
                                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                <div>
                                                   <span class="btn btn-white btn-file">
                                                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                   <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                   <input type="file" class="default" name="teacher_avatar">
                                                   </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="teacher_hospital" class="control-label col-lg-2">讲师来源</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="teacher_hospital" name="teacher_hospital" minlength="2" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="teacher_position" class="control-label col-lg-2">讲师职称</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="teacher_position" name="teacher_position" minlength="2" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="teacher_desc" class="control-label col-lg-2">讲师简介</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control " id="teacher_desc" name="teacher_desc"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="firend_title" class="control-label col-lg-2">好友分享标题</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="firend_title" name="firend_title" minlength="2" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="firend_subtitle" class="control-label col-lg-2">好友分享副标题</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="firend_subtitle" name="firend_subtitle" minlength="2" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="share_title" class="control-label col-lg-2">朋友圈分享语</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="share_title" name="share_title" minlength="2" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">分享图片</label>
                                        <div class="col-md-9">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="/admin_style/img/no_image.png" alt="">
                                                </div>
                                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                <div>
                                                   <span class="btn btn-white btn-file">
                                                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                   <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                   <input type="file" class="default" name="share_picture">
                                                   </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="living_firend_title" class="control-label col-lg-2">直播中好友分享标题</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="living_firend_title" name="living_firend_title" minlength="2" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="living_firend_subtitle" class="control-label col-lg-2">直播中好友分享副标题</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="living_firend_subtitle" name="living_firend_subtitle" minlength="2" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="living_share_title" class="control-label col-lg-2">直播中朋友圈分享语</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="living_share_title" name="living_share_title" minlength="2" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">直播中分享图片</label>
                                        <div class="col-md-9">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="/admin_style/img/no_image.png" alt="">
                                                </div>
                                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                <div>
                                                   <span class="btn btn-white btn-file">
                                                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                   <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                   <input type="file" class="default" name="living_share_picture">
                                                   </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="area_city_id" class="control-label col-lg-2">所属城市</label>
                                        <div class="col-lg-3">
                                            <select class="form-control m-bot15" name="area_city_id">
                                                <option value="0">请选择</option>
                                                <?php foreach ($area_city as $v) { ?>
                                                <option value="<?=$v['id'];?>"><?=$v['city'];?></option>
                                                <?php } ?>
                                            </select>
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
            $("#courseForm").validate({
                rules: {
                    title: "required",
                    number: "required",
                    start_day: "required",
                    img: "required",
                    desc: "required",
                    notice: "required",
                    teacher_name: "required",
                    teacher_avatar: "required",
                    teacher_desc: "required",
                },
                messages: {
                    title: "请填写课程名称",
                    number: "请填写课程期数",
                    start_day: "请填写课程日期",
                    img: "请选择课程缩略图",
                    desc: "请填写课程描述",
                    notice: "清填写课程注意事项",
                    teacher_name: "请填写医师名称",
                    teacher_avatar: "请选择医师头像",
                    teacher_desc: "请填写医师详细描述"
                }
            });
            $(".date-picker").datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            $('.timepicker-24-start').timepicker({
                autoclose: true,
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false,
                defaultTime:'16:30'
            });
            $('.timepicker-24-end').timepicker({
                autoclose: true,
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false,
                defaultTime:'17:30'
            });
            $("[data-toggle='switch']").wrap('<div class="switch" />').parent().bootstrapSwitch();
        });
    }();

    function shihe(obj){
        var val = $(obj).val();
        if(val == '孕中'){
            $(obj).parent().find(".yunzhong").show();
            $(obj).parent().find(".baobao").hide();
        }else if(val == '宝宝'){
            $(obj).parent().find(".baobao").show();
            $(obj).parent().find(".yunzhong").hide();
        }else{
            $(obj).parent().find(".baobao").hide();
            $(obj).parent().find(".yunzhong").hide();
        }
    }
</script>
</body>
</html>