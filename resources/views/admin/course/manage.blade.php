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
                            课件列表
                        </header>
                        <p style="color: red; font-size: 10px; margin-left: 10px; margin-bottom: 0">（*长图文下载文件为html直接打开后保存相关图片即可）</p>
                        <div class="panel-body">
                            <div class="tab-content">
                                <section id="unseen">
                                    <div class="space15"></div>
                                    <div class="row-fluid">
                                        <form action="#" method="get" id="search">
                                            <div class="input-group input-group-sm m-bot15 col-lg-10">
                                                <span class="input-group-addon">课程ID</span>
                                                <input type="text" class="form-control" name="id" onkeyup='this.value=this.value.replace(/\D/gi,"")' value="{{$params['id']}}" >
                                                <span class="input-group-addon">课程名</span>
                                                <input type="text" class="form-control"  name="title" value="{{$params['title']}}" >
                                                <span class="input-group-addon">专家名</span>
                                                <input type="text" class="form-control date-picker" name="teacher" value="{{$params['teacher']}}">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-white" name="sort" type="submit" value="id">搜索并按id排序</button>
                                                </span>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon">课程状态</span>
                                                    <select name="status" class="form-control m-bot15" onchange="submitMyForm()">
                                                        <option value="0" @if($params['status'] == 0) selected @endif>全部</option>
                                                        <option value="1" @if($params['status'] == 1) selected @endif>有效</option>
                                                        <option value="2" @if($params['status'] == 2) selected @endif>无效</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <table id="tableLecturer" class="table table-bordered table-striped table-condensed">
                                        <thead>
                                        <tr>
                                            <th>课程ID</th>
                                            <th style="max-width: 150px">课程名称</th>
                                            <th>专家</th>
                                            <th>状态</th>
                                            <th style="max-width: 320px">音频文件/视频地址</th>
                                            <th>图片课件</th>
                                            <th style="max-width: 150px">长图文</th>
                                            @if($user_info->user_type != 2)
                                            <th>操作</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($list)) {
                                        foreach ($list as $k => $item) {?>
                                        <tr>
                                            <td><?php echo $item->cid; ?></td>
                                            <td style="max-width: 150px"><?php echo $item->title; ?></td>
                                            <td><?php echo $item->teacher; ?></td>
                                            <td>
                                                @if($item->status)
                                                    有效
                                                @else
                                                    无效
                                                @endif
                                            </td>
                                            @if($item->review_type == 1 && $item->audio)
                                                <td style="max-width: 320px; word-wrap: break-word"><audio style="width:280px; height: 30px " src="<?=$item->audio?>" controls="controls" preload="metadata"></audio></td>
                                            @elseif($item->video)
                                                <td style="max-width: 320px; word-wrap: break-word"><?php echo $item->video; ?></td>
                                            @else
                                                @if($user_info->user_type != 2)
                                                <td style="max-width: 320px; word-wrap: break-word">
                                                    请前往编辑进行上传
                                                </td>
                                                @else
                                                <td>暂无内容</td>
                                                @endif
                                            @endif
                                            <td>
                                                @if($user_info->user_type != 2)
                                                <a href="javascript:;" onclick="layer.open({type: 2,title:'上传课件',area: ['460px', '480px'],content: ['/admin/course/upload_att/<?php echo $item->cid; ?>','yes']});">上传</a>
                                                @endif
                                                <a href="/admin/course_review/download/<?php echo $item->cid; ?>">下载</a>
                                            </td>
                                            <td style="max-width: 150px">
                                                <a href="/admin/course_review/download_html/<?php echo $item->id; ?>">下载</a>
                                            </td>
                                            @if($user_info->user_type != 2)
                                            <td class="">
                                                <a href="/admin/course_review/edit/<?php echo $item->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i>编辑</a>
                                                <a href="/admin/course_review/edit/<?php echo $item->id; ?>#chapter_points" class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i>设置课程大纲</a>
                                            </td>
                                            @endif
                                        </tr>
                                        <?php }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php echo $list->appends($params)->render(); ?>
                                </section>
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
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.multi-select.js"></script>s
<script type="text/javascript" src="/admin_style/flatlab/assets/jquery-multi-select/js/jquery.quicksearch.js"></script>


<!--summernote-->
<script src="/admin_style/flatlab/assets/summernote/dist/summernote.min.js"></script>

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>
<!--this page  script only-->
<script src="/admin_style/flatlab/js/advanced-form-components.js"></script>
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/js/bootbox.4.4.0.min.js"></script>
<script>
    function submitMyForm () {
        $('#search').submit();
    }
</script>
</body>
</html>