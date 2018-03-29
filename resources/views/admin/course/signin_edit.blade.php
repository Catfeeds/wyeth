<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
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
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />
    <link href="/js/select2/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/admin_style/flatlab/assets/jquery-multi-select/css/multi-select.css" />

    <link rel="stylesheet" href="/mobile/signin/css/sweetalert.css">
    <style>
        .sign_switch_from{
            display:none;
        }
    </style>
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
                            游戏配置
                        </header>
                            <div class="panel-body">
                                <div class=" form">
                                    <div class="form-horizontal">
                                    <!--<form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="/admin/course/store" enctype="multipart/form-data">-->
                                        <section class="panel">
                                            {{--<header class="panel-heading tab-bg-dark-navy-blue ">
                                                <ul class="nav nav-tabs">
                                                    <li class="active">
                                                        <a data-toggle="tab" href="#about" aria-expanded="false">名单</a>
                                                    </li>
                                                    <li>
                                                        <a data-toggle="tab" href="#home" aria-expanded="false">基本</a>
                                                    </li>
                                                    <!--<li class="">
                                                        <a data-toggle="tab" href="#profile" aria-expanded="false">分享</a>
                                                    </li>
                                                    <li class="">
                                                        <a data-toggle="tab" href="#signin" aria-expanded="false">游戏</a>
                                                    </li>-->
                                                </ul>
                                            </header>--}}
                                            <div class="panel-body">
                                                <div class="tab-content">
                                                    <div id="home" class="tab-pane active">
                                                        @if ($type == 1)
                                                        <form  method="post" action="/admin/signin/config/save" enctype="multipart/form-data" id="sign_conf_fm">
                                                        @else
                                                        <form  method="post" action="/admin/signin/config/create" enctype="multipart/form-data" id="sign_conf_fm">
                                                        @endif
                                                            @if ($type == 1)
                                                            <input type="hidden" name="cid" value="{{$info->id}}" >
                                                            @endif
                                                            <div class="form-group sign-form">
                                                                <label for="teacher_name" class="control-label col-lg-2">获奖人数</label>
                                                                <div class="col-lg-6" style="width:150px">
                                                                    <input class=" form-control" id="win_number" oninput='this.value=this.value.replace(/\D/gi,"")' name="win_num" type="text" value="{{isset($sign_config->win_num) ? $sign_config->win_num : ''}}">
                                                                </div>
                                                            </div>
                                                            @if ($type == 0)
                                                            <div class="form-group">
                                                                <label class="control-label col-lg-2">关联课程</label>
                                                                <div class="col-md-9">
                                                                    <select multiple="multiple" class="multi-select" id="cids" name="cids[]">
                                                                        <?php foreach ($courses as $course) { ?>
                                                                        <option value="<?=$course['id']?>"><?=$course['title']?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <div class="form-group ">
                                                                <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">游戏开放平台</label>
                                                                <div class="col-lg-10 bs-docs-example ">
                                                                    <label class="checkbox-inline">
                                                                        <input type="checkbox" value="option1" name="platfrom"> 微信
                                                                    </label>
                                                                    <label class="checkbox-inline">
                                                                        <input type="checkbox" value="option2" name="platfrom"> QQ
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group sign-form">
                                                                <label for="teacher_name" class="control-label col-lg-2">好友分享标题</label>
                                                                <div class="col-lg-6">
                                                                    <input class=" form-control" id="share_title" name="fri_share_title"  type="text" value="{{isset($sign_config->fri_share_title) ? $sign_config->fri_share_title : '你懒你先睡，我美我拿奖！'}}">
                                                                </div>
                                                            </div>

                                                            <div class="form-group sign-form">
                                                                <label for="teacher_name" class="control-label col-lg-2">好友分享描述</label>
                                                                <div class="col-lg-6">
                                                                    <input class=" form-control" id="share_desc" name="fri_share_desc"  type="text" value="{{isset($sign_config->fri_share_desc) ? $sign_config->fri_share_desc : '我正在听妈妈微课堂直播，一起来签到，马上赢奶粉！'}}">
                                                                </div>
                                                            </div>

                                                            <div class="form-group sign-form">
                                                                <label for="teacher_name" class="control-label col-lg-2">朋友圈分享语</label>
                                                                <div class="col-lg-6">
                                                                    <input class=" form-control" id="circle_share_desc" name="circle_share_desc"  type="text" value="{{isset($sign_config->fri_circle_share_title) ? $sign_config->fri_circle_share_title : '我正在听妈妈微课堂直播，一起来签到，马上赢奶粉！'}}">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label col-lg-2">分享图片</label>
                                                                <div class="col-md-9">
                                                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                        <img src="{{isset($sign_config->share_img) && !empty($sign_config->share_img) ? $sign_config->share_img : '/mobile/signin/images/share.jpg'}}" alt="">
                                                                        </div>
                                                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                                        <div>
                                                                           <span class="btn btn-white btn-file">
                                                                           <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                           <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                           <input type="file" class="default" name="sign_share_picture" accept="image/png">
                                                                           </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--品牌按钮-->
                                                            <div class="form-group">
                                                                <label class="control-label col-lg-2">设置图片素材</label>
                                                                <div class="col-md-9">
                                                                    <header>
                                                                        <h4>游戏首页</h4>
                                                                    </header>
                                                                    <!--品牌图片-->
                                                                    {{--<div class="fileupload fileupload-new left" data-provides="fileupload" style="float:left">
                                                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                            <img src="{{isset($sign_config->brand_img) && !empty($sign_config->brand_img) ? $sign_config->brand_img : '/admin_style/img/no_image.png'}}" alt="">
                                                                        </div>
                                                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 200px; height: 150px; line-height: 20px;"></div>
                                                                        <div>
                                                                        <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                        <input type="file" class="default" name="brand_img">
                                                                        </span>
                                                                        </div>
                                                                        <span>品牌按钮<br>png格式，尺寸294*105px</span>
                                                                    </div>--}}
                                                                    <!--活动规则-->
                                                                    <div class="fileupload fileupload-new left" data-provides="fileupload" style="float:left;">
                                                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                            <img src="{{isset($sign_config->rule_img) && !empty($sign_config->rule_img) ? $sign_config->rule_img : '/mobile/signin/images/game-rule_v2.png'}}" alt="">
                                                                        </div>
                                                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 200px; height: 150px; line-height: 20px;"></div>
                                                                        <div>
                                                                        <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                        <input type="file" class="default" name="rule_img" accept="image/png">
                                                                        </span>
                                                                        </div>
                                                                        <span>活动规则<br>png格式，尺寸650*1050px</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--统一奖品大图-->
                                                            <div class="form-group">
                                                                <label class="control-label col-lg-2"></label>
                                                                <div class="col-md-9">
                                                                    <header>
                                                                        <h4>品牌展示</h4>
                                                                    </header>
                                                                    <!--产品介绍-->
                                                                    <div class="fileupload fileupload-new left" data-provides="fileupload" style="float:left">
                                                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                            <img src="{{isset($sign_config->intro_img) && !empty($sign_config->intro_img) ? $sign_config->intro_img : '/mobile/signin/images/proPowder.png'}}" alt="">
                                                                        </div>
                                                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 200px; height: 150px; line-height: 20px;"></div>
                                                                        <div>
                                                                        <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                        <input type="file" class="default" name="intro_img" accept="image/png">
                                                                        </span>
                                                                        </div>
                                                                        <span>品牌展示<br>png格式，尺寸635*315px</span>
                                                                    </div>
                                                                    <!--课程介绍-->
                                                                {{--<div class="fileupload fileupload-new right" data-provides="fileupload" style="float:left;margin-left:50px;">
                                                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                        <img src="{{isset($sign_config->teacher_img) && !empty($sign_config->teacher_img) ? $sign_config->teacher_img : '/admin_style/img/no_image.png'}}" alt="">
                                                                    </div>
                                                                    <div class="fileupload-preview fileupload-exists thumbnail" style="width: 200px; height: 150px; line-height: 20px;"></div>
                                                                    <div>
                                                                    <span class="btn btn-white btn-file">
                                                                    <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                    <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                    <input type="file" class="default" name="teacher_img">
                                                                    </span>
                                                                    </div>
                                                                    <span>课程介绍<br>png格式，尺寸640*185px</span>
                                                                </div>--}}
                                                                </div>
                                                            </div>

                                                            <!--好友签到页-->
                                                            {{--<div class="form-group">
                                                                <label class="control-label col-lg-2"></label>
                                                                <div class="col-md-9">
                                                                    <header>
                                                                        <h4>好友签到页</h4>
                                                                    </header>
                                                                    <!--课程介绍-->
                                                                    --}}{{--<div class="fileupload fileupload-new right" data-provides="fileupload" style="float:left;margin-left:50px;">
                                                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                            <img src="{{isset($sign_config->teacher_img) && !empty($sign_config->teacher_img) ? $sign_config->teacher_img : '/admin_style/img/no_image.png'}}" alt="">
                                                                        </div>
                                                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 200px; height: 150px; line-height: 20px;"></div>
                                                                        <div>
                                                                        <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                        <input type="file" class="default" name="teacher_img">
                                                                        </span>
                                                                        </div>
                                                                        <span>课程介绍<br>png格式，尺寸640*185px</span>
                                                                    </div>--}}{{--
                                                                    <!--好友参加-->
                                                                    <div class="fileupload fileupload-new left" data-provides="fileupload" style="float:left;">
                                                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                            <img src="{{isset($sign_config->living_img) && !empty($sign_config->living_img) ? $sign_config->living_img : '/admin_style/img/no_image.png'}}" alt="">
                                                                        </div>
                                                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 200px; height: 150px; line-height: 20px;"></div>
                                                                        <div>
                                                                        <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                        <input type="file" class="default" name="living_img" accept="image/*">
                                                                        </span>
                                                                        </div>
                                                                        <span>好友参加按钮<br>png格式，尺寸640*160px</span>
                                                                    </div>
                                                                </div>
                                                            </div>--}}

                                                            <!--成功页-->
                                                            <div class="form-group">
                                                                <label class="control-label col-lg-2"></label>
                                                                <div class="col-md-9">
                                                                    <header>
                                                                        <h4>成功页</h4>
                                                                    </header>
                                                                    <!--奖品-->
                                                                    <div class="fileupload fileupload-new right" data-provides="fileupload" style="float:left;">
                                                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                            <img src="{{isset($sign_config->prize_img) && !empty($sign_config->prize_img) ? $sign_config->prize_img : '/mobile/signin/images/link3.png'}}" alt="">
                                                                        </div>
                                                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 200px; height: 150px; line-height: 20px;"></div>
                                                                        <div>
                                                                        <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                        <input type="file" class="default" name="prize_img" accept="image/png">
                                                                        </span>
                                                                        </div>
                                                                        <span class="control-label">奖品<br>png格式，尺寸688*462px</span>
                                                                    </div>
                                                                    <!--领奖按钮-->
                                                                    <div class="fileupload fileupload-new left" data-provides="fileupload" style="float:left;margin-left:50px;">
                                                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                            <img src="{{isset($sign_config->award_img) && !empty($sign_config->award_img) ? $sign_config->award_img : '/mobile/signin/images/receive.png'}}" alt="">
                                                                        </div>
                                                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 200px; height: 150px; line-height: 20px;"></div>
                                                                        <div>
                                                                        <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                        <input type="file" class="default" name="award_img" accept="image/png">
                                                                        </span>
                                                                        </div>
                                                                        <span class="control-label">领奖按钮<br>png格式，尺寸366*102px</span>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <!--用户资料页头图片-->
                                                            <div class="form-group">
                                                                <label class="control-label col-lg-2"></label>
                                                                <div class="col-md-9">
                                                                    <header>
                                                                        <h4>用户资料页</h4>
                                                                    </header>
                                                                    <div class="fileupload fileupload-new right" data-provides="fileupload">
                                                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                            <img src="{{isset($sign_config->user_info_title) && !empty($sign_config->user_info_title) ? $sign_config->user_info_title : '/mobile/signin/images/header.png'}}" alt="">
                                                                        </div>
                                                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width: 200px; height: 150px; line-height: 20px;"></div>
                                                                        <div>
                                                                        <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                        <input type="file" class="default" name="user_info_title" accept="image/png">
                                                                        </span>
                                                                        </div>
                                                                        <span class="control-label">logo<br>png格式，尺寸639*92px</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="col-lg-offset-2 col-lg-10">
                                                                    <button class="btn btn-danger" id="sign_config_submit" type="button">保存</button>
                                                                    <button class="btn btn-default" type="reset">重置</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!--<div id="profile"  class="tab-pane">2</div>
                                                    <div id="signin"  class="tab-pane">3</div>-->
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
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
<script src="/js/bootbox.4.4.0.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="/admin_style/flatlab/js/respond.min.js" ></script>
<script src="/admin_style/flatlab/js/jquery.validate.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap-switch.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.quicksearch.js"></script>
<!--this page plugins-->

<script type="text/javascript" src="/admin_style/flatlab/assets/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/js/select2/select2.full.js"></script>
<script src="/js/select2/zh-CN.js"></script>
<script src="/js/lodash.js"></script>

<script src="/mobile/signin/js/sweetalert.min.js"></script>

<script>
    $(function() {
        $('#cids').multiSelect();
        $('#sign_config_submit').click(function() {
            var win_num = $('input[name=win_num]').val();
            var share_title = $('#share_title').val();
            var share_desc = $('#share_desc').val();
            var circle_share_desc = $('#circle_share_desc').val();
            var cids = $('#cids').val();
            var sid = $('form input[name=cid]').val();
            if (win_num.length == 0) {
                boxlog('请填写获奖人数');
                return false;
            }
            if (!cids && !sid) {
                boxlog('请绑定课程');
                return false;
            }
            if (share_title.length == 0) {
                boxlog('请填写分享给朋友的标题');
                return false;
            }

            if (share_desc.length == 0) {
                boxlog('请填写分享给朋友的描述');
                return false;
            }

            if (circle_share_desc.length == 0) {
                boxlog('请填写分享到朋友圈分享语');
                return false;
            }
            $('#sign_conf_fm').submit();
        });

        $('#signin_list a[hw-user-info]').click(function (){
            var id = $(this).attr('data');
            get_user_info(id);

        });
    });

    function get_user_info (id) {
        $.ajax({
            url: '/admin/signin/victory/info',
            type: 'GET',
            dataType: 'json',
            data: {id : id},
            success: function (result) {
                if (result.status == 1) {
                    swal(result.msg);
                } else if (result.status == 0) {
                    var params = {};
                    var data = result.data;
                    params.sid = data.signin_item_id;
                    params.realname = data.realname;
                    params.mobile = data.mobile;
                    params.address = data.address;
                    params.remark = data.remark;
                    user_info_box(params);
                } else if (result.status == 3) {
                    swal(result.msg);
                }
            }
        })
    }

    function user_info_box (params) {
        bootbox.dialog({
            title : "领奖信息",
            message : '<table class="table table-bordered table-striped table-condensed">'+
            "<thead>"+
            "<tr>"+
            "<th width='20%'>真实姓名</th>"+
            "<th>"+params.realname+"</th>" +
            "</tr>" +
            "<tr>"+
            "<th width='20%'>手机号</th>"+
            "<th>"+params.mobile+"</th>" +
            "</tr>"+
            "</tr>" +
            "<tr height='70px'>"+
            "<th width='20%'>家庭住址</th>"+
            "<th>"+params.address+"</th>" +
            "</tr>"+
            "<tr height='100px'>"+
            "<th width='20%'>备注</th>"+
            "<th>"+params.remark+"</th>" +
            "</tr>"+
            "</thead>"+
            '</table>',
            buttons : {
                "cancel" : {
                    "label" : "<i class='icon-info'></i> 确定",
                    "className" : "btn-sm btn-danger",
                    "callback" : function() { }
                }
            }
        });
    }

    function boxlog(msg){
        bootbox.dialog({
            title : "游戏配置",
            message : "" +
            "<div class='well ' style='margin-top:25px;padding:20px;word-break:break-all;'>"+msg+"</div>",
            buttons : {
                "cancel" : {
                    "label" : "<i class='icon-info'></i> 确定",
                    "className" : "btn-sm btn-danger",
                    "callback" : function() { }
                }
            }
        });
    }
</script>
</body>
</html>