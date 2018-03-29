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
    <style>
        .select2-results__options {
            overflow-y: scroll;
            max-height: 200px;
        }
        .select2-container--bootstrap {
            border: solid #eee 1px;
            border-radius: 5px;
            padding-top: 5px;
        }
        .edit-button_group {
            margin: 10px 20px;
            display: flex;
            flex-direction: row
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
        <section class="wrapper site-min-height" id="scrollItem">
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            @if($info->id)
                             编辑课程：{{ $info->title }}
                                @else
                            创建课程
                                @endif
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="/admin/course/store" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="{{ $info->id or 0 }}">
                                    <section class="panel">
                                        <header class="panel-heading tab-bg-dark-navy-blue ">
                                            <ul class="nav nav-tabs">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#home" aria-expanded="false">基本</a>
                                                </li>
                                                <li class="">
                                                    <a data-toggle="tab" href="#about" aria-expanded="false">讲师</a>
                                                </li>
                                                <li class="">
                                                    <a data-toggle="tab" href="#tagsTab" aria-expanded="false">标签</a>
                                                </li>
                                            </ul>
                                        </header>
                                        <div class="panel-body">
                                            <div class="tab-content">
                                                <div id="home" class="tab-pane active">
                                                    <div class="form-group">
                                                        <label for="title" class="control-label col-lg-2">课程名称</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="title" name="title" minlength="2" type="text" value="<?php echo $info->title; ?>">
                                                        </div>
                                                    </div>
                                                    <!-- 课程 -->
                                                    <div class="form-group">
                                                        <label for="title" class="control-label col-lg-2">套课 | 课程分类</label>
                                                        <div class="col-lg-6">
                                                            <select name="cid" class="form-control m-bot15 valid">
                                                                <option value="0">不放入套课</option>
                                                                @foreach ($courseCats as $courseCat)
                                                                    <option @if ($courseCat['id'] == $info['cid']) selected="selected" @endif value="{{$courseCat['id']}}">{{$courseCat['name']}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="is_competitive" class="control-label col-lg-2">套课 | 课程分类</label>
                                                        <div class="col-lg-6">
                                                            <select name="is_competitive" class="form-control m-bot15 valid">
                                                                <option value="0" @if(!$info->is_competitive) selected @endif>非精品</option>
                                                                <option value="1" @if($info->is_competitive) selected @endif>精品</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cat_order" class="control-label col-lg-2">套课内顺序</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="cat_order" name="cat_order" type="text" value="<?php echo $info->cat_order; ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="title" class="control-label col-lg-2">期数</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="number" name="number" type="text" datatype="n" value="<?php echo $info->number; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">课程日期</label>
                                                        <div class="col-md-3 col-xs-11">
                                                            <input class="form-control form-control-inline input-medium date-picker" size="16" type="text" name="start_day" value="<?php echo $info->start_day; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">开始时间</label>
                                                        <div class="col-md-3 col-xs-11">
                                                            <div class="input-group bootstrap-timepicker">
                                                                <input type="text" class="form-control timepicker-24-start" name="start_time" value="<?php echo isset($info->start_time) ? $info->start_time : "20:00:00"; ?>">
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
                                                                <input type="text" class="form-control timepicker-24-end" name="end_time" value="<?php echo isset($info->end_time) ? $info->end_time : "21:00:00"; ?>">
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
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">课程头图</label>
                                                        <div class="col-md-9">
                                                                @for($i = 0; $i < 2; $i++)
                                                                <div class="fileupload fileupload-new" data-provides="fileupload" style="display: inline-block; margin-left: 10px">
                                                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                        <img src="<?php echo !empty($info->banners[$i]) ? $info->banners[$i] : '/admin_style/img/no_image.png'; ?>" alt="">
                                                                    </div>
                                                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                                    <div>
                                                                    <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                        <input type="file" class="default" name="banner[]">
                                                                    </span>
                                                                    {{--<span class="btn btn-danger del-banner" style="margin-left: 10px"> 删除</span>--}}
                                                                    </div>
                                                                </div>
                                                                @endfor
                                                            {{--<span id="btn_add_banner" class="btn btn-primary" onclick="addBanner()" style="margin-left: 10px">添加</span>--}}
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">广告图</label>
                                                        <div class="col-md-9">
                                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                    <img src="<?php echo !empty($info->ad_img) ? $info->ad_img : '/admin_style/img/no_image.png'; ?>" alt="">
                                                                </div>
                                                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                                <div>
                                                                    <span class="btn btn-white btn-file">
                                                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                                        <input type="file" class="default" name="ad_img">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ad_link" class="control-label col-lg-2">广告链接</label>
                                                        <div class="col-lg-6">
                                                            <input class="form-control" id="" name="ad_link" type="text" datatype="n" value="<?php echo $info->ad_link; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">所属品牌</label>
                                                        <div class="col-lg-4">
                                                            <select name="brand" class="form-control m-bot15">
                                                                <option value="0" @if ($info->type == 0) selected='selected' @endif>无</option>
                                                                @foreach($brands as $brand)
                                                                    <option value="{!! $brand->id !!}}" @if ($info->brand == $brand->id) selected='selected' @endif>{!! $brand->name !!}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="desc" class="control-label col-lg-2">说明</label>
                                                        <div class="col-lg-6">
                                                            <textarea class="form-control " id="desc" name="desc"><?php echo $info->desc; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="notice" class="control-label col-lg-2">注意事项</label>
                                                        <div class="col-lg-6">
                                                            <textarea class="form-control " id="notice" name="notice"><?php echo $info->notice; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">课程适合阶段</label>
                                                        <div class="col-lg-6">
                                                            <div class="shihe_1" style="float: left;">
                                                                <select name="fromType" class="form-control m-bot15"  data="<?php echo $info->stage_from;?>" onchange="shihe(this);" style="float: left; width: 100px;">
                                                                    <option value="100" {{substr(trim($info->stage_from), 0 ,1) == 1 ? "selected=selected" : ""}}>备孕</option>
                                                                    <option value="2" {{substr(trim($info->stage_from), 0 ,1) == 2 ? "selected=selected" : ""}}>孕中</option>
                                                                    <option value="3" {{substr(trim($info->stage_from), 0 ,1) == 3 ? "selected=selected" : ""}}>宝宝</option>
                                                                </select>
                                                                <select class="yunzhong form-control m-bot15" name="fromMonth" style="{{substr(trim($info->stage_from), 0 ,1) == 2 ? 'display: block' : 'display: none'}}; float: left; width: 100px;">
                                                                    <option value="00" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 0 ? "selected=selected" : ""}}>0个月</option>
                                                                    <option value="01" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 1 ? "selected=selected" : ""}}>1个月</option>
                                                                    <option value="02" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 2 ? "selected=selected" : ""}}>2个月</option>
                                                                    <option value="03" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 3 ? "selected=selected" : ""}}>3个月</option>
                                                                    <option value="04" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 4 ? "selected=selected" : ""}}>4个月</option>
                                                                    <option value="05" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 5 ? "selected=selected" : ""}}>5个月</option>
                                                                    <option value="06" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 6 ? "selected=selected" : ""}}>6个月</option>
                                                                    <option value="07" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 7 ? "selected=selected" : ""}}>7个月</option>
                                                                    <option value="08" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 8 ? "selected=selected" : ""}}>8个月</option>
                                                                    <option value="09" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 9 ? "selected=selected" : ""}}>9个月</option>
                                                                    <option value="10" {{substr(trim($info->stage_from), 0 ,1) == 2 && substr(trim($info->stage_from), 1 ,2) == 10 ? "selected=selected" : ""}}>10个月</option>
                                                                </select>
                                                                <select class="baobao form-control m-bot15" name="fromYear" style="{{substr(trim($info->stage_from), 0 ,1) == 3 ? 'display: block' : 'display: none'}}; float: left; width: 100px;">
                                                                    <option value="00" {{substr(trim($info->stage_from), 0 ,1) == 3 && substr(trim($info->stage_from), 1 ,2) == 0 ? "selected=selected" : ""}}>0岁</option>
                                                                    <option value="01" {{substr(trim($info->stage_from), 0 ,1) == 3 && substr(trim($info->stage_from), 1 ,2) == 1 ? "selected=selected" : ""}}>1岁</option>
                                                                    <option value="02" {{substr(trim($info->stage_from), 0 ,1) == 3 && substr(trim($info->stage_from), 1 ,2) == 2 ? "selected=selected" : ""}}>2岁</option>
                                                                    <option value="03" {{substr(trim($info->stage_from), 0 ,1) == 3 && substr(trim($info->stage_from), 1 ,2) == 3 ? "selected=selected" : ""}}>3岁</option>
                                                                    <option value="04" {{substr(trim($info->stage_from), 0 ,1) == 3 && substr(trim($info->stage_from), 1 ,2) == 4 ? "selected=selected" : ""}}>4岁</option>
                                                                    <option value="05" {{substr(trim($info->stage_from), 0 ,1) == 3 && substr(trim($info->stage_from), 1 ,2) == 5 ? "selected=selected" : ""}}>5岁</option>
                                                                    <option value="06" {{substr(trim($info->stage_from), 0 ,1) == 3 && substr(trim($info->stage_from), 1 ,2) == 6 ? "selected=selected" : ""}}>6岁</option>
                                                                </select>
                                                            </div>
                                                            <div style="float:left; margin: 0 10px; line-height: 32px;">至</div>
                                                            <div class="shihe_2" style="float: left">
                                                                <select name="toType" class="form-control m-bot15" onchange="shihe(this);" data="<?php echo $info->stage_from;?>" style="float: left; width:100px;">
                                                                    <option value="100" {{substr(trim($info->stage_to), 0 ,1) == 1 ? "selected=selected" : ""}}>备孕</option>
                                                                    <option value="2" {{substr(trim($info->stage_to), 0 ,1) == 2 ? "selected=selected" : ""}}>孕中</option>
                                                                    <option value="3" {{substr(trim($info->stage_to), 0 ,1) == 3 ? "selected=selected" : ""}}>宝宝</option>
                                                                </select>
                                                                <select class="yunzhong form-control m-bot15" name="toMonth" style="{{substr(trim($info->stage_to), 0 ,1) == 2 ? 'display: block' : 'display: none'}}; float: left; width:100px;">
                                                                    <option value="00" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 0 ? "selected=selected" : ""}}>0个月</option>
                                                                    <option value="01" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 1 ? "selected=selected" : ""}}>1个月</option>
                                                                    <option value="02" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 2 ? "selected=selected" : ""}}>2个月</option>
                                                                    <option value="03" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 3 ? "selected=selected" : ""}}>3个月</option>
                                                                    <option value="04" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 4 ? "selected=selected" : ""}}>4个月</option>
                                                                    <option value="05" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 5 ? "selected=selected" : ""}}>5个月</option>
                                                                    <option value="06" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 6 ? "selected=selected" : ""}}>6个月</option>
                                                                    <option value="07" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 7 ? "selected=selected" : ""}}>7个月</option>
                                                                    <option value="08" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 8 ? "selected=selected" : ""}}>8个月</option>
                                                                    <option value="09" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 9 ? "selected=selected" : ""}}>9个月</option>
                                                                    <option value="10" {{substr(trim($info->stage_to), 0 ,1) == 2 && substr(trim($info->stage_to), 1 ,2) == 10 ? "selected=selected" : ""}}>10个月</option>
                                                                </select>
                                                                <select class="baobao form-control m-bot15" name="toYear" style="{{substr(trim($info->stage_to), 0 ,1) == 3 ? 'display: block' : 'display: none'}}; float: left; width:100px;">
                                                                    <option value="00" {{substr(trim($info->stage_to), 0 ,1) == 3 && substr(trim($info->stage_to), 1 ,2) == 0 ? "selected=selected" : ""}} >0岁</option>
                                                                    <option value="01" {{substr(trim($info->stage_to), 0 ,1) == 3 && substr(trim($info->stage_to), 1 ,2) == 1 ? "selected=selected" : ""}}>1岁</option>
                                                                    <option value="02" {{substr(trim($info->stage_to), 0 ,1) == 3 && substr(trim($info->stage_to), 1 ,2) == 2 ? "selected=selected" : ""}}>2岁</option>
                                                                    <option value="03" {{substr(trim($info->stage_to), 0 ,1) == 3 && substr(trim($info->stage_to), 1 ,2) == 3 ? "selected=selected" : ""}}>3岁</option>
                                                                    <option value="04" {{substr(trim($info->stage_to), 0 ,1) == 3 && substr(trim($info->stage_to), 1 ,2) == 4 ? "selected=selected" : ""}}>4岁</option>
                                                                    <option value="05" {{substr(trim($info->stage_to), 0 ,1) == 3 && substr(trim($info->stage_to), 1 ,2) == 5 ? "selected=selected" : ""}}>5岁</option>
                                                                    <option value="06" {{substr(trim($info->stage_to), 0 ,1) == 3 && substr(trim($info->stage_to), 1 ,2) == 6 ? "selected=selected" : ""}}>6岁</option>
                                                                </select>
                                                            </div>
                                                            <span class="help-inline"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="notice" class="control-label col-lg-2">状态</label>
                                                        <div class="col-lg-6">
                                                            <input type="checkbox" data-toggle="switch" value="1" name="display_status" <?php echo $info->display_status == 1 ? 'checked="checked"' : ''; ?>/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">课程状态</label>
                                                        <div class="col-lg-10">
                                                            <label class="checkbox-inline">
                                                                <input type="radio" id="status" name="status" value="1" <?php echo $info->status == 1 ? 'checked="checked"' : ''; ?>>报名中
                                                            </label>
                                                            <label class="checkbox-inline">
                                                                <input type="radio" id="status" name="status" value="3" <?php echo $info->status == 3 ? 'checked="checked"' : ''; ?>>已结束
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="sign_limit" class="control-label col-lg-2">报名上限</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="sign_limit" name="sign_limit" minlength="2" type="text" value="<?php echo $info->sign_limit; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="course_recommend" class="control-label col-lg-2">推荐课程</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="course_recommend" name="course_recommend" minlength="2" type="text" value="<?php echo $info->course_recommend; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cat_title" class="control-label col-lg-2">套课展示标题</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="cat_title" name="cat_title" type="text" value="<?php echo $info->cat_title; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cat_desc" class="control-label col-lg-2">套课展示简介</label>
                                                        <div class="col-lg-6">
                                                            <textarea class=" form-control" id="cat_desc" name="cat_desc" type="text" value="<?php echo $info->cat_desc; ?>"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="price" class="control-label col-lg-2">课程价格（MQ）</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="price" name="price" type="text" value="<?php echo $info->price; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-lg-offset-2 col-lg-10">
                                                            <button class="btn btn-danger" id="editCourse" type="button">保存</button>
                                                            <button class="btn btn-default" type="reset">重置</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="about" class="tab-pane">
                                                    <div class="">
                                                        <label><input name="choose" type="radio" value="1" checked onclick="radioChange()" />选择现有讲师 </label>
                                                        <label><input name="choose" type="radio" value="2" onclick="radioChange()" />新增讲师 </label>
                                                    </div>
                                                    <div class="form-group" id="selectModal" style="margin-top: 20px;">
                                                        <label for="notice" class="control-label col-lg-2">讲师名称：</label>
                                                            <div id="selectorLecturer" class="col-lg-8" style="display: flex; flex-direction: row;  align-items: center; margin-top: 10px">
                                                                <select id="tagSelectLecturer" name="teacher_id" class="form-control teacherSelects" multiple="multiple" style="width: 300px">
                                                                    @foreach ($info->tags as $index => $tag)
                                                                        @if($courseTags[$index]['type'] == 2)
                                                                        <option selected="selected" value="id#{{ $tag->id }}">{{ $tag->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        <div class="col-lg-10" style="margin-top: 20px">
                                                            <button class="btn btn-danger" type="button" onclick="saveLecturerTag()">保存</button>
                                                        </div>
                                                    </div>
                                                    {{--<div id="createModal" style="display: none; margin-top: 20px">--}}
                                                        {{--<span class="btn btn-primary" onclick="confirmExit()">新增讲师</span>--}}
                                                    {{--</div>--}}
                                                </div>
                                                <div id="profile" class="tab-pane">
                                                    <div class="form-group ">
                                                        <label for="firend_title" class="control-label col-lg-2">好友分享标题</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="firend_title" name="firend_title" minlength="2" type="text" value="<?php echo $info->firend_title; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="firend_subtitle" class="control-label col-lg-2">好友分享副标题</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="firend_subtitle" name="firend_subtitle" minlength="2" type="text" value="<?php echo $info->firend_subtitle; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="share_title" class="control-label col-lg-2">朋友圈分享语</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="share_title" name="share_title" minlength="2" type="text" value="<?php echo $info->share_title; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">分享图片</label>
                                                        <div class="col-md-9">
                                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                    <img src="<?php echo !empty($info->share_picture) ? $info->share_picture : '/admin_style/img/no_image.png'; ?>" alt="">
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
                                                            <input class=" form-control" id="living_firend_title" name="living_firend_title" minlength="2" type="text" value="<?php echo $info->living_firend_title; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="living_firend_subtitle" class="control-label col-lg-2">直播中好友分享副标题</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="living_firend_subtitle" name="living_firend_subtitle" minlength="2" type="text" value="<?php echo $info->living_firend_subtitle; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="living_share_title" class="control-label col-lg-2">直播中朋友圈分享语</label>
                                                        <div class="col-lg-6">
                                                            <input class=" form-control" id="living_share_title" name="living_share_title" minlength="2" type="text" value="<?php echo $info->living_share_title; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-lg-2">直播中分享图片</label>
                                                        <div class="col-md-9">
                                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                                    <img src="<?php echo !empty($info->living_share_picture) ? $info->living_share_picture : '/admin_style/img/no_image.png'; ?>" alt="">
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
                                                    <div class="form-group">
                                                        <div class="col-lg-offset-2 col-lg-10">
                                                            <button class="btn btn-danger" type="submit">保存</button>
                                                            <button class="btn btn-default" type="reset">重置</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="tagsTab" class="tab-pane">
                                                    <div style="display: flex; flex-direction: column">
                                                        <label for="notice" class="col-lg-10">内容标签</label>
                                                        @foreach ($info->tags as $index => $tag)
                                                            @if($courseTags[$index]['type'] == 0)
                                                            <div id="selector{{$index}}" class="col-lg-8" style="display: flex; flex-direction: row;  align-items: center; margin-top: 10px">
                                                                <select id="tagSelect{{$index}}" name="tags[]" class="form-control selects" multiple="multiple" style="width: 300px">
                                                                    <option selected="selected" value="id#{{ $tag->id }}">{{ $tag->name }}</option>
                                                                </select>
                                                                <input id="weight{{$index}}" name="weights[]" class="form-control weightInput" placeholder="请输入关联度" value="{{ $courseTags[$index]['weight'] }}">
                                                                <span onclick="deleteSelect({{$index}})" class="btn-danger" style="cursor: pointer; border-radius: 8px; margin-left: 12px; width: 80px; text-align: center; padding: 3px 10px;">删除</span>
                                                            </div>
                                                            @endif
                                                        @endforeach
                                                        <div class="edit-button_group" id="btnGroup">
                                                            <span class="btn-success" style="cursor: pointer; border-radius: 8px; text-align: center; padding: 3px 10px;" onclick="addTagInput()">添加</span>
                                                            {{--<span class="btn-primary" style="cursor: pointer; border-radius: 8px; text-align: center; margin-left: 20px; padding: 3px 10px;" onclick="saveCourseTag()">保存内容标签</span>--}}
                                                        </div>
                                                    </div>
                                                    <div style="display: flex; flex-direction: column; margin-top: 15px;">
                                                        <label for="notice" class="col-lg-10">月龄标签</label>
                                                        <div>
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox" id="ageCheckbox1" name="pre_tag[]" value="358"> 孕期
                                                            </label>
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox" id="ageCheckbox2" name="pre_tag[]" value="360"> 0-12月
                                                            </label>
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox" id="ageCheckbox3" name="pre_tag[]" value="361"> 12-24月
                                                            </label>
                                                            <label class="checkbox-inline">
                                                                <input type="checkbox" id="ageCheckbox4" name="pre_tag[]" value="362"> 24+月
                                                            </label>
                                                        </div>
                                                        {{--<div class="edit-button_group">--}}
                                                            {{--<span class="btn-primary" style="cursor: pointer; border-radius: 8px; text-align: center; padding: 3px 10px;" onclick="saveAgeTags()">保存月龄标签</span>--}}
                                                        {{--</div>--}}
                                                    </div>
                                                    <div style="display: flex; flex-direction: column; margin-top: 15px">
                                                        <label for="notice" class="col-lg-10">显示标签</label>
                                                        <div style="margin: 10px 20px">
                                                            <select id="d_select" class="form-control" name="dis_tags[]" multiple="multiple" style="width: 300px;">
                                                                @foreach($info->tags as $index => $tag)
                                                                    @if($courseTags[$index]['type'] == \App\Models\Tag::TAG_DISPLAY)
                                                                    <option selected="selected" value="{{ $tag->id }}">{{ $tag->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        {{--<div class="edit-button_group" id="btnGroup">--}}
                                                            {{--<span class="btn-primary" style="cursor: pointer; border-radius: 8px; text-align: center; padding: 3px 10px;" onclick="saveDisplayTag()">保存显示标签</span>--}}
                                                        {{--</div>--}}
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
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/js/lodash.js"></script>

<!--this page  script only-->
<script src="/js/bootbox.4.4.0.min.js"></script>

<script>
   $(document).ready(function () {
       for (var j = 1; j <= 4; j++) {
           var box = $("#ageCheckbox" + j)
           @foreach($courseTags as $index => $item)
            if ('{{$item['type']}}' ==  '{{\App\Models\Tag::TAG_PREGNANT}}') {
               if (box.val() == '{{$item['tid']}}') {
                   box.attr('checked', true);
               }
           }
           @endforeach
       }
    });

var stageTo = '{{ json_encode($info->stage_to) }}';
var stageFrom = '{{ json_encode($info->stage_from) }}';
function shihe(obj) {
    var val = $(obj).val();
    if(val == '2') {
        $(obj).parent().find(".yunzhong").show();
        $(obj).parent().find(".baobao").hide();
    } else if(val == '3') {
        $(obj).parent().find(".baobao").show();
        $(obj).parent().find(".yunzhong").hide();
    } else {
        $(obj).parent().find(".baobao").hide();
        $(obj).parent().find(".yunzhong").hide();
    }
}

function handleResult(data) {
    var results = [];
    var t = $(".selects");
    var vals = [];
    for (var c = 0; c < t.length; c++) {
        vals.push(t[c].value);
    }
    if (data.items) {
        _.forEach(data.items, function(item) {
            if ($.inArray('id#' + item.id, vals) == -1) {
                results.push(_.extend(item, {'id': 'id#' + item.id}));
            }
        });
        data.results = results;
    }
    return data;
}

   function handleDisplayResult(data) {
       var results = [];
       var t = $(".selects");
       var vals = [];
       for (var c = 0; c < t.length; c++) {
           vals.push(t[c].value);
       }
       if (data.items) {
           _.forEach(data.items, function(item) {
               if ($.inArray(item.id, vals) == -1) {
                   results.push(_.extend(item, {'id': item.id}));
               }
           });
           data.results = results;
       }
       return data;
   }

$(function() {
    // stage init
    var fromType = stageFrom.substring(0,1);
    var fromTime = stageFrom.substring(1,3);
    var toType = stageTo.substring(0,1);
    var toTime = stageTo.substring(1,3);
    if(fromType == 1){
        $('select[name="fromType"]').children().eq(0).attr('selected','selected');
    }else if(fromType == 2){
        $('select[name="fromType"]').children().eq(1).attr('selected','selected');
        $('select[name="fromMonth"]').show().children().eq(fromTime).attr('selected','selected');
    }else if(fromType == 3){
        $('select[name="fromType"]').children().eq(2).attr('selected','selected');
        $('select[name="fromYear"]').show().children().eq(fromTime).attr('selected','selected');
    }
    if(toType == 1){
        $('select[name="toType"]').children().eq(0).attr('selected','selected');
    }else if(toType == 2){
        $('select[name="toType"]').children().eq(1).attr('selected','selected');
        $('select[name="toMonth"]').show().children().eq(toTime).attr('selected','selected');
    }else if(toType == 3){
        $('select[name="toType"]').children().eq(2).attr('selected','selected');
        $('select[name="toYear"]').show().children().eq(toTime).attr('selected','selected');
    }

    $('#editCourse').click(function(){
        var fromType = $('select[name="fromType"]').val();
        var toType =   $('select[name="toType"]').val();
        if(fromType == 2){
            var toMonth = $('select[name="toMonth"]').val();
            var fromMonth = $('select[name="fromMonth"]').val();
            if(toType == 100){
                boxlog('您好,请选择正确的课程适合阶段。');
                return false;
            }
            if(toType == 2 && toMonth < fromMonth ){
                boxlog('您好,请选择正确的课程适合阶段。');
                    return false;
            }

        }
        if(fromType == 3){
            var toYear = $('select[name="toYear"]').val();
            var fromYear = $('select[name="fromYear"]').val();
            if(toType == 100 || toType == 2){
                boxlog('您好,请选择正确的课程适合阶段。');
                return false;
            }
            if(toType == 3 && toYear < fromYear){
                boxlog('您好,请选择正确的课程适合阶段。');
                return false;
            }
        }

        var teacher_id = $('#tagSelectLecturer').val();
        if (!teacher_id) {
            boxlog('您好,请为课程选择讲师');
            return false;
        }

        var tags = $(".selects");
        var length = tags.length;
        if (length <= 0) {
            boxlog('您好,请正确填写内容标签');
            return false;
        }

        var isset_pre = false;
        for (var i = 1; i <= 4; i++) {
            if ($("#ageCheckbox" + i)[0].checked) {
                isset_pre = true;
            }
        }
        if (!isset_pre) {
            boxlog('您好，请选择月龄标签');
            return false;
        }

        var display_ids = $('#d_select').val();
        if (!display_ids) {
            boxlog('您好，请为课程设置显示标签');
            return false;
        }
        $('#courseForm').submit();
    });
    var tag_ids = [];
    var formatRepo = function (repo) {
        if (repo.loading) {
            return repo.text;
        }
        var text = repo.text;
        if (repo.name) {
            text = repo.name;
        }
        return "<div class='select2-result-repository clearfix'>" + text + "</div>";
    };

    var formatRepoSelection = function (repo) {
        return repo.name || repo.text;
    };

    var tagsLenght = '<?php echo count($info->tags) ?>';

    for (var count = 0; count < tagsLenght; count++) {
        $('#tagSelect' + count).select2({
            theme: 'bootstrap',
            language: "zh-CN",
            placeholder: "输入标签名",
            multiple: false,
            ajax: {
                url: "/admin/tag/search",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    return handleResult(data)
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
    }

    /** 显示标签 start **/
    $('#d_select').select2({
        theme: 'bootstrap',
        language: "zh-CN",
        placeholder: "输入标签名",
        multiple: true,
        ajax: {
            url: "/admin/display_tags/search",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                return handleDisplayResult(data)
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 0,
        maximumSelectionLength: 3,
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });
    /** 显示标签 end **/

    $('#tagSelectLecturer').select2({
        theme: 'bootstrap',
        language: "zh-CN",
        placeholder: "点击输入老师名称",
        multiple: false,
        ajax: {
            url: "/admin/lecturers/search",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                var results = [];
                if (data.items) {
                    _.forEach(data.items, function(item) {
                        results.push(_.extend(item, {'id': item.tid}));
                    });
                    data.results = results;
                }
                return data;
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 0,
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });



    $("#courseForm").validate({
        rules: {
            title: "required",
//            number: "required",
            start_day: "required",
            //img: "required",
            desc: "required",
            notice: "required",
            teacher_name: "required",
            //teacher_avatar: "required",
            teacher_desc: "required"
        },
        messages: {
            title: "请填写课程名称",
//            number: "请填写课程期数",
            start_day: "请填写课程日期",
            //img: "请选择课程缩略图",
            desc: "请填写课程描述",
            notice: "清填写课程注意事项",
            teacher_name: "请填写医师名称",
            //teacher_avatar: "请选择医师头像",
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
        defaultTime:false
    });
    $('.timepicker-24-end').timepicker({
        autoclose: true,
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false,
        defaultTime:false
    });
    $("[data-toggle='switch']").wrap('<div class="switch" />').parent().bootstrapSwitch();

});
    $(document).ready(function(){
        var qcrode_status = $("input[name='qrcode_type']:checked").val();
        if(qcrode_status == 0){
            $('#qrcode_group').hide();
        } else {
            $('#qrcode_group').show();
        }

       $('#qrcode_type_one').click(function(){
           $('#qrcode_group').hide();
       }) ;
        $('#qrcode_type_two').click(function(){
            $('#qrcode_group').show();
        }) ;
    })
    function boxlog(msg){
        bootbox.dialog({
            title : "修改课程",
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

    function addBanner() {
        var banner = '<div class="fileupload fileupload-new" data-provides="fileupload" style="display: inline-block; margin-left: 10px">' +
            '<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">' +
            '<img src="/admin_style/img/no_image.png" alt="">' +
            '</div>' +
            '<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>' +
            '<div>' +
            '<span class="btn btn-white btn-file">' +
            '<span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>' +
            '<span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>' +
            '<input type="file" class="default input-banner" name="banner[]">' +
            '</span>' +
//            '<span class="btn btn-danger del-banner" style="margin-left: 10px"> 删除</span>' +
            '</div>' +
            '</div>'
        $("#btn_add_banner").before(banner);

        $(".del-banner").bind('click', function() {
            $(this).parent().parent().remove();
        });
    }
   $(".del-banner").bind('click', function() {
       $(this).parent().parent().remove();
   });

    var index = '<?php echo count($info->tags) ?>';

    function addTagInput () {
        if ($(".selects").length < 3) {
            var id = 'tagSelect' + index;
            var divId = 'selector' + index;
            var weightId = 'weight' + index;
            var selector = '<div id="' + divId + '" class="col-lg-8" style="display: flex; flex-direction: row;  align-items: center; margin-top: 10px">'+
                '<select id="' + id + '" class="form-control selects" name="tags[]" multiple="multiple" style="width: 300px">' +
                '</select>'+
                '<input id="' + weightId + '" class="form-control weightInput" name="weights[]" placeholder="请输入关联度">'+
                '<span class="btn-danger" onclick="deleteSelect(' + index + ')" style="cursor: pointer; border-radius: 8px; margin-left: 12px; width: 80px; text-align: center; padding: 3px 10px;">删除</span>'+
                '</div>';
            $("#btnGroup").before(selector);

            var formatRepo = function (repo) {
                if (repo.loading) {
                    return repo.text;
                }
                var text = repo.text;
                if (repo.name) {
                    text = repo.name;
                }
                return "<div class='select2-result-repository clearfix'>" + text + "</div>";
            };

            var formatRepoSelection = function (repo) {
                return repo.name || repo.text;
            };

            $("#" + id).select2({
                theme: 'bootstrap',
                language: "zh-CN",
                multiple: false,
                placeholder: '输入标签名',
                ajax: {
                    url: "/admin/tag/search",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        return handleResult(data)
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 0,
                templateResult: formatRepo, // omitted for brevity, see the source of this page
                templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
            });
            index++;
        }
    }

    function deleteSelect(i) {
        var id = 'selector' + i
        $("#" + id).remove();
    }

    function saveCourseTag() {
        var tags = $(".selects");
        var length = tags.length;
        var postStr = '{"id":"<?php echo $id ?>","tags":['
        if (length <= 0) {
            boxlog('您好,请正确填写内容标签');
            return false;
        } else {
            for (var i = 0; i < length; i++) {
                var degree = parseFloat($(".weightInput")[i].value);
                var t = tags[i].value;
                if (t && degree) {
                    if (degree > 1 || degree < 0) {
                        noty({text: '权重范围错误', type: "error", timeout: 2000});
                        return;
                    } else {
                        var tagId = t.split('#')[1];
                        postStr = postStr + '{"id":' + tagId + ',"weight":' + degree + '}'
                        if (i != length - 1) {
                            postStr += ','
                        }
                    }

                } else {
                    noty({text: '请完整填写数据', type: "error", timeout: 2000});
                    return;
                }
            }
            postStr += ']}';
            $.post('/admin/course/setTags', JSON.parse(postStr), function (data) {
                if (data.status == 1) {
                    noty({text: data.msg, type: "success", timeout: 2000});
                    setTimeout('window.location.reload()', 1000);
                } else {
                    noty({text: data.msg, type: "error", timeout: 2000});
                }
            }, 'json');
        }

    }

    function saveAgeTags() {
        var id_arr = [];
        for (var i = 1; i <= 4; i++) {
            var box = $("#ageCheckbox" + i)
            if (box[0].checked == true) {
                id_arr.push(box.val())
            }
        }
        $.post('/admin/course/setAgeTags', { tids: id_arr, cid: "<?php echo $id ?>" }, function (data) {
            if (data.status == 1) {
                noty({text: data.msg, type: "success", timeout: 2000});
            } else {
                noty({text: data.msg, type: 'error', timeout: 2000})
            }
        })
    }

    function saveLecturerTag() {
        var lecturer = $('#tagSelectLecturer').val();
        if (lecturer) {
            $.post('/admin/course/setTeachers', { 'cid': '<?php echo $id ?>', 'tid': lecturer}, function (data) {
                if (data.status == 1) {
                    noty({text: data.msg, type: "success", timeout: 2000});
                    setTimeout('window.location.reload()', 1000);
                } else {
                    noty({text: data.msg, type: "error", timeout: 2000});
                }
            }, 'json');
        } else {
            noty({text: '未选择讲师', type: "error", timeout: 2000});
        }
    }

    function saveDisplayTag() {
        var postStr = '{"id":"<?php echo $id ?>","display_tags":"' + $('#d_select').val() + '"}';
        $.post('/admin/course/setDisplayTags', JSON.parse(postStr), function (data) {
            if (data.status == 1) {
                noty({text: data.msg, type: "success", timeout: 2000});
                setTimeout('window.location.reload()', 1000);
            } else {
                noty({text: data.msg, type: "error", timeout: 2000});
            }
        }, 'json');
    }

    function radioChange () {
        var group = $("[name='choose']").filter(":checked");
        if (group.val() == 1) {
            $('#selectModal').show()
            $('#createModal').hide()
        } else {
            $('#selectModal').hide()
            $('#createModal').show()
        }
    }

    function confirmExit() {
        bootbox.confirm({
            buttons: {
                confirm: {
                    label: '确认',
                    className: 'btn-primary'
                },
                cancel: {
                    label: '取消',
                    className: 'btn-default'
                }
            },
            message: '离开当前页面将丢失所有修改，请确认改动已保存后进行跳转',
            callback: function(result) {
                if(result) {
                    location.href = '/admin/lecturer/add/0';
                } else {
                    return true;
                }
            }
        });
    }

</script>
</body>
</html>