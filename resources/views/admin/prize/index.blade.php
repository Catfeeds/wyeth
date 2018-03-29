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
    <link href="/js/select2/select2.min.css" rel="stylesheet">

    <!--right slidebar-->
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">

    <!--  summernote -->
    <link href="/admin_style/flatlab/assets/summernote/dist/summernote.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />
    <style>
        .cus-div {margin: 10px 0}
        label.error {color: #f00}
    </style>
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
                        <header class="panel-heading tab-bg-dark-navy-blue ">
                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#" aria-expanded="false">有主</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#" aria-expanded="false">无主</a>
                                </li>
                            </ul>
                        </header>
                        <div class="panel-body">
                            <div class="tab-content">
                                <section id="unseen">
                                    <div class="row-fluid" style="margin-bottom: 10px; display: flex; flex-direction: row; align-items: center;">
                                        <div class="clearfix">
                                            <div class="btn-group">
                                                <button data-toggle="modal" data-target="#editModal" id="editable-sample_new" class="btn green">
                                                    添加奖品 <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="btn-group">
                                                <button data-toggle="modal" data-target="#noticeModal" id="editable-sample_new" class="btn green" style="margin-left: 20px">
                                                    修改中奖须知 <i class="fa fa-notice"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div style="flex: 1"></div>
                                        <form id="searchForm" action="#" method="get" class="col-lg-9" style="display: flex; flex-direction: row">
                                            <input id="aid" type="text" name="aid" value="{{$q_params['aid']}}" style="display: none">
                                            <div class="input-group inputgroup-sm col-lg-3">
                                                <span class="input-group-addon">状态</span>
                                                <select name="status" class="form-control" onchange="submitSearch()">
                                                    <option value="0" @if($q_params['status'] == 0) selected @endif>全部</option>
                                                    <option value="1" @if($q_params['status'] == 1) selected @endif>上线</option>
                                                    <option value="2" @if($q_params['status'] == 2) selected @endif>下线</option>
                                                    <option value="3" @if($q_params['status'] == 3) selected @endif>备选</option>
                                                </select>
                                            </div>
                                            <div class="input-group inputgroup-sm col-lg-3">
                                                <span class="input-group-addon">奖品类型</span>
                                                <select name="type" class="form-control" onchange="submitSearch()">
                                                    <option value="0" @if($q_params['type'] == 0) selected @endif>全部</option>
                                                    <option value="object" @if($q_params['type'] == 'object') selected @endif>实物奖品</option>
                                                    <option value="virtual" @if($q_params['type'] == 'virtual') selected @endif>虚拟奖品</option>
                                                </select>
                                            </div>
                                            <div class="input-group input-group-sm col-lg-6">
                                                <span class="input-group-addon">搜索</span>
                                                <input type="text" class="form-control" name="title" value="{{$q_params['title']}}" placeholder="奖品名称" >
                                                <span class="input-group-btn">
                                                <button class="btn btn-white" name="sort" type="submit">搜索</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="space15"></div>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>名称</th>
                                            <th>图片</th>
                                            <td>类型</td>
                                            <td>状态</td>
                                            <td>中奖数量/剩余数量</td>
                                            <td>中奖概率</td>
                                            <td>有效期</td>
                                            <td>操作</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($list)) {
                                        foreach ($list as $k => $item) {
                                        ?>
                                        <tr>
                                            <td>{{$item['item_id']}}</td>
                                            <td>{{$item['title']}}</td>
                                            <td style="width: 80px; height: 80px;"><img src="{{$item['pic']}}" style="width: 80px; height: 80px;"></td>
                                            <td>
                                                @if($item['type'] == 'object')
                                                    实物奖品
                                                @else
                                                    虚拟奖品
                                                @endif
                                            </td>
                                            <td>
                                                @if(array_key_exists('id', $item))
                                                    上线
                                                @elseif(array_key_exists('send_num', $item))
                                                    下线
                                                @else
                                                    备选
                                                @endif
                                            </td>
                                            <td>
                                                @if(array_key_exists('send_num', $item) && array_key_exists('left_num', $item))
                                                    {{$item['send_num']}} / {{$item['left_num']}}
                                                @elseif(!array_key_exists('send_num', $item) && array_key_exists('left_num', $item))
                                                    -/{{$item['left_num']}}
                                                @elseif(array_key_exists('send_num', $item) && !array_key_exists('left_num', $item))
                                                    {{$item['send_num']}}/-
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if(array_key_exists('odds', $item))
                                                    {{$item['odds'] / 100}} %
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if(array_key_exists('starttime', $item)){{date('Y-m-d', $item['starttime'])}} - {{date('Y-m-d', $item['endtime'])}} @else 未设置 @endif</td>
                                            <td>
                                                <button data-toggle="modal" data-target="#editModal" onclick="editClick('{{$k}}')" class="btn btn-primary btn-xs"><i class="fa fa-pencil "></i>编辑</button>
                                                @if(!array_key_exists('id', $item))<button class="btn btn-danger btn-xs" onclick="deleteClick('{{$item['item_id']}}', '{{array_key_exists('id', $item) ? $item['id'] : ''}}')"><i class="fa fa-trash-o "></i>删除</button>@endif
                                                @if(array_key_exists('id', $item))<button onclick="changeClick({{$item['id']}})" data-toggle="modal" data-target="#changeModal" class="btn btn-warning btn-xs">替换奖品</button>@endif
                                            </td>
                                        </tr>
                                        <?php }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </section>
                            </div>
                            <ul id="pagination" class="pagination"></ul>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </section>
<?php echo $footer;?>
<!--footer end-->
</section>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="myModalLabel">新增奖品</h4>
            </div>
            <form class="crmform tasi-form" id="editForm" method="post" action="/admin/prize/edit" enctype="multipart/form-data">
                <input id="aid" type="text" name="aid" value="{{$q_params['aid']}}" style="display: none">
                <input id="item_id" name="item_id" title="item_id" style="display: none">
                <input id="id" name="id" title="id" style="display: none">
                <input id="before_left" name="before_left" title="before_left" style="display: none">
                <div class="form-group">
                    <label class="control-label col-lg-2 cus-div" for="title"><i style="color: #f00">* </i>奖品名称</label>
                    <div class="col-md-10 cus-div">
                        <input class="form-control" name="title" id="title" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="pic" class="control-label col-lg-2 cus-div"><i style="color: #f00">* </i>奖品图片</label>
                    <div class="col-md-10">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img id="tag_img" data-src="" src="/admin_style/img/no_image.png" alt="">
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                            </div>
                            <div>
                                <span class="btn btn-white btn-file">
                                <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                                <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                <input type="file" class="default" name="pic">
                                </span>
                                <input type="text" name="imgHidden" id="imgHidden" style="display: none">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2 cus-div" for="status"><i style="color: #f00">* </i>状态</label>
                    <div class="col-md-10 cus-div">
                        <select id="status" name="status" disabled class="form-control">
                            <option value="0">备选</option>
                            <option value="1">上线</option>
                            <option value="2">下线</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2 cus-div" for="type"><i style="color: #f00">* </i>奖品类型</label>
                    <div class="col-md-10 cus-div">
                        <select id="type" name="type" class="form-control" onchange="typeChange(this.value)">
                            <option value="object">实物奖品</option>
                            <option value="virtual">虚拟奖品</option>
                        </select>
                    </div>
                </div>
                <div id="left_num_block" class="form-group" style="display: none;">
                    <label class="control-label col-lg-2 cus-div" for="left_num"><i style="color: #f00">* </i>剩余数量</label>
                    <div class="col-md-10 cus-div">
                        <input class="form-control" name="left_num" id="left_num" type="text">
                    </div>
                </div>
                <div id="jump_url_block" class="form-group" style="display: none;">
                    <label class="control-label col-lg-2 cus-div" for="jump_url"><i style="color: #f00">* </i>中奖地址</label>
                    <div class="col-md-10 cus-div">
                        <input class="form-control" name="jump_url" id="jump_url" type="text">
                    </div>
                </div>
                <div id="odds_block" class="form-group" style="display: none;">
                    <label class="control-label col-lg-2 cus-div" for="odds"><i style="color: #f00">* </i>中奖率</label>
                    <div class="col-lg-10 cus-div" style="display: flex; flex-direction: row; align-items: center">
                        <input class="form-control" style="width: 50%; margin-right: 2px" name="odds" id="odds" type="text"> %
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2 cus-div"><i style="color: #f00">* </i>奖品领取有效期</label>
                    <div class="col-md-10 cus-div   " style="display: flex; flex-direction: row; align-items: center">
                        <div class="input-group bootstrap-timepicker col-lg-5" style="flex: 1; margin-right: 10px">
                            <input type="text" class="form-control timepicker-24-start" name="starttime" id="start_time" value="">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>
                            </span>
                        </div>
                        至
                        <div class="input-group bootstrap-timepicker col-lg-6" style="flex: 1; margin-left: 10px">
                            <input type="text" class="form-control timepicker-24-end" name="endtime" id="end_time" value="">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div id="need_remark_block" class="form-group">
                    <input type="checkbox" id="need_remark" name="need_remark" style="margin: 10px 0 10px 20px">
                    <label class="" for="need_remark">强制中奖用户填写备注（月龄）</label>
                </div>
                <div class="modal-footer" style="border-top: none">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">修改</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="changeModal" tabindex="-1" role="dialog" aria-labelledby="changeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="myModalLabel">替换奖品</h4>
            </div>
            <form class="crmform tasi-form" id="changeForm" method="post" action="/admin/prize/change" enctype="multipart/form-data">
                <input id="change_id" name="id" style="display: none;">
                <input id="aid" type="text" name="aid" value="{{$q_params['aid']}}" style="display: none">
                <div class="form-group">
                    <label class="control-label col-lg-2 cus-div" for="change_item"><i style="color: #f00">* </i>替换奖品</label>
                    <div class="col-md-10 cus-div">
                        <select id="change_item" name="item_id" class="form-control">
                            @foreach($total_list as $item)
                                @if(!array_key_exists('id', $item))
                                    <option value="{{$item['item_id']}}">{{$item['title']}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2 cus-div" for="change_left_num"><i style="color: #f00">* </i>剩余数量</label>
                    <div class="col-md-10 cus-div">
                        <input class="form-control" name="left_num" id="change_left_num" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-2 cus-div" for="change_odds"><i style="color: #f00">* </i>中奖率</label>
                    <div class="col-md-10 cus-div" style="display: flex; flex-direction: row; align-items: center">
                        <input class="form-control" name="odds" id="change_odds" type="text" style="width: 40%"> %
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary" id="">修改</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="noticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="myModalLabel">修改中奖须知</h4>
            </div>
            <form class="crmform tasi-form" id="noticeForm" method="post" action="/admin/prize/notice_edit" enctype="multipart/form-data">
                <input id="aid" type="text" name="aid" value="{{$q_params['aid']}}" style="display: none">
                <div class="form-group">
                    <label class="control-label col-lg-2 cus-div" for="title"><i style="color: #f00">* </i>中奖须知</label>
                    <div class="col-md-10 cus-div">
                        <textarea class="form-control" name="content" id="content" type="text" rows="20">{{$content}}</textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary" id="">修改</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="/admin_style/flatlab/js/respond.min.js" ></script>
<script src="/admin_style/flatlab/js/jquery.validate.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap-switch.js"></script>
<script src="/admin_style/js/jqPaginator.js"></script>
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/js/bootbox.4.4.0.min.js"></script>
<!--this page plugins-->

<script type="text/javascript" src="/admin_style/flatlab/assets/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/bootstrap-daterangepicker/daterangepicker.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>
<script>
    var aid = '{{$q_params["aid"]}}';

    var page = parseInt('{{$page}}');
    $('#pagination').jqPaginator({
        totalPages: Math.ceil({{$total / 10}}),
        visiblePages: 10,
        currentPage: page,
        onPageChange: function (num) {
            if (this.currentPage != num) {
                window.location = "/admin/prize?page=" + num + buildQuery();
            }
        }
    });
    $('.timepicker-24-start').datepicker({
        format: 'yyyy-mm-dd'
    });
    $('.timepicker-24-end').datepicker({
        format: 'yyyy-mm-dd'
    });

    var editModal = $('#editModal');

    function editClick(index) {
        @foreach($list as $index => $item)
        if ('{{$index}}' == index) {
            $('#myModalLabel').html('编辑奖品');
            $("#item_id").val({{$item['item_id']}});
            $('#before_left').val({{$item['left_num']}});
            $("#title").val('{{$item['title']}}');
            $("#tag_img").attr('src', '{{$item['pic']}}');
            $('#imgHidden').val('{{$item['pic']}}');
            @if(array_key_exists('id', $item))
            $('#status').val(1);
            $('#id').val({{$item['id']}});
            statusChange(1);
            @elseif(array_key_exists('send_num', $item))
            $('#status').val(2);
            statusChange(2);
            $('#id').val('');
            @else
            $('#id').val('');
            $('#status').val(0);
            statusChange(0);
            @endif
            $("#type").val('{{$item['type']}}');
            typeChange('{{$item['type']}}');
            $("#left_num").val({{array_key_exists('left_num', $item) ? $item['left_num'] : ''}});
            $("#jump_url").val('{{array_key_exists('jump_url', $item) ? $item['jump_url'] : ''}}');
            $("#odds").val({{(array_key_exists('odds', $item) ? $item['odds'] : 0) / 100}});
            @if(array_key_exists('starttime', $item))
            $("#start_time").val('{{date('Y-m-d', $item['starttime'])}}');
            $("#end_time").val('{{date('Y-m-d', $item['endtime'])}}');
            @endif
        }
        @endforeach
    }

    editModal.on('hidden.bs.modal', function () {
        $('#myModalLabel').html('新增奖品');
        $('#id').val('');
        $("#item_id").val('');
        $('#before_left').val('');
        $("#title").val('');
        $("#tag_img").attr('src', '/admin_style/img/no_image.png');
        $('#imgHidden').val('');
        $('#status').val(0);
        statusChange(0);
        $("#type").val('object');
        typeChange('object');
        $("#left_num").val('');
        $('#jump_url').val('');
//        $('#jump_url_block').hide();
        $("#odds").val('');
        $("#start_time").val('{{date('Y-m-d')}}');
        $("#end_time").val('{{date('Y-m-d')}}');
    })

    function typeChange(value) {
        if (value == 'object') {
            if ($('#status').val() == 1) {
                $('#left_num_block').show();
            }
            $('#need_remark_block').show();
            $('#jump_url_block').hide();
        } else {
            if ($('#status').val() == 1) {
                $('#left_num_block').hide();
            }
            $("#need_remark_block").hide();
            $('#jump_url_block').show();
        }
    }

    function statusChange(value) {
        if (value == 1) {
            if ($("#type").val() == 'object') {
                $('#left_num_block').show();
                $('#jump_url_block').hide();
            } else {
                $('#left_num_block').hide();
                $('#jump_url_block').show();
            }
            $('#odds_block').show();
        } else {
            $('#left_num_block').hide();
            $('#jump_url_block').hide();
            $('#odds_block').hide();
        }
    }

    function deleteClick(item_id, id) {
        if (id) {
            bootbox.alert({
                buttons: {
                    ok: {
                        label: '确认',
                        className: 'btn-primary'
                    }
                },
                message: '线上奖品无法删除',
                callback: function(result) {
                    return true;
                }
            });
        } else {
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
                message: '奖品删除后将无法回复，确认删除吗？',
                callback: function (result) {
                    if (result) {
                        $.post("/admin/prize/delete", {'item_id': item_id, 'aid': aid}, function (data) {
                            if (data.status == 1) {
                                noty({text: data.msg, type: "success", timeout: 2000});
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                noty({text: data.msg, type: "error", timeout: 2000});
                            }
                        }, 'json');
                    } else {
                        return true;
                    }
                }
            })
        }
    }

    function changeClick(id) {
        $('#change_id').val(id);
    }

    $('#changeModal').on('hidden.bs.modal', function () {
        $('#change_id').val('');
        $('#change_left_num').val('');
        $('#change_odds').val('');
    })

    function buildQuery() {
        var str = '';
        @foreach($q_params as $key => $value)
            if ('{{$value}}') {
                str = str + '&{{$key}}' + '=' + '{{$value}}';
            }
        @endforeach
        return str;
    }

    function submitSearch() {
        $("#searchForm").submit();
    }

    $("#editForm").validate({
        rules: {
            title: 'required',
            status: 'required',
            type: 'required',
            left_num: 'required',
            jump_url: 'required',
            odds: {
                required: true,
                min: 0.01,
                max: 100
            },
            starttime: 'required',
            endtime: 'required'
        },
        messages: {
            title: '请填写奖品名称',
            status: '请选择奖品状态',
            type: '请徐选择奖品类型',
            left_num: '请填写剩余数量',
            jump_url: '请填写中奖地址',
            odds: {
                required: '请填写中奖率',
                min: '中奖率不得小于0.01',
                max: '中奖率不得大于100',
            },
            starttime: '请选择开始领取时间',
            endtime: '请选择结束领取时间'
        }
    });

    $("#changeForm").validate({
        rules: {
            item_id: 'required',
            left_num: 'required',
            odds: {
                required: true,
                min: 0.01,
                max: 100
            }
        },
        messages: {
            item_id: '请选择替换上线的奖品',
            left_num: '请填写剩余数量',
            odds: {
                required: '请填写中奖率',
                min: '中奖率不得小于0.01',
                max: '中奖率不得大于100',
            }
        }
    });

    var index = aid == 434 ? 1 : 0;

    // 选择展示哪个标签页
    $('#myTab').find('li:eq(' + index + ') a').tab('show');

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ($(e.target).text() == '有主') {
            $('#aid').val(406);
        } else {
            $('#aid').val(434);
        }
        $('#searchForm').submit();
    })
</script>
</body>
</html>