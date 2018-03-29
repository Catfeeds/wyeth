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
    <style>
        .cus-label { margin-top: 10px; text-align: right }
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
                        <form action="#" method="get" id="formType">
                        </form>
                        <header class="panel-heading tab-bg-dark-navy-blue ">
                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#" aria-expanded="false">A版</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#" aria-expanded="false">B版</a>
                                </li>
                            </ul>
                        </header>
                        <div class="panel-body">
                            <div class="tab-content">
                                <section id="unseen">
                                    <div class="row-fluid" style="margin-bottom: 10px; display: flex; flex-direction: row; align-items: center; justify-content: space-between">
                                        <div class="clearfix">
                                            <div class="btn-group">
                                                <button data-toggle="modal" data-target="#editModal" id="editable-sample_new" class="btn green">
                                                    添加新广告 <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <form id="search" action="#" method="get" class="col-lg-8" style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end">
                                            <input id="inputType" type="text" name="version" value="{{$version}}" style="display: none">
                                            <div class="input-group input-group-sm col-lg-4">
                                                <span class="input-group-addon">用户/品牌 类型</span>
                                                <select name="type" class="form-control m-bot15" onchange="submitMyForm()">
                                                    {{--<option value="0" @if($params['type'] == 0) selected @endif>全部</option>--}}
                                                    <option value="1" @if($params['type'] == 1) selected @endif>无主/干货</option>
                                                    <option value="2" @if($params['type'] == 2) selected @endif>启赋</option>
                                                    <option value="3" @if($params['type'] == 3) selected @endif>金装</option>
                                                </select>
                                            </div>
                                            <div class="input-group input-group-sm col-lg-4">
                                                <span class="input-group-addon">位置</span>
                                                <select name="position" class="form-control m-bot15" onchange="submitMyForm()">
                                                    {{--<option value="0" @if($params['position'] == 0) selected @endif>全部</option>--}}
                                                    <option value="{{ \App\Models\Advertise::POSITION_INDEX_TOP }}" @if($params['position'] == 1) selected @endif>{{ \App\Models\Advertise::POSITION_T[1] }}</option>
                                                    <option value="{{ \App\Models\Advertise::POSITION_INDEX_MID }}" @if($params['position'] == 2) selected @endif>{{ \App\Models\Advertise::POSITION_T[2] }}</option>
                                                    <option value="{{ \App\Models\Advertise::POSITION_COURSE_MID }}" @if($params['position'] == 3) selected @endif>{{ \App\Models\Advertise::POSITION_T[3] }}</option>
                                                    <option value="{{ \App\Models\Advertise::POSITION_COURSE_BOTTOM }}" @if($params['position'] == 4) selected @endif>{{ \App\Models\Advertise::POSITION_T[4] }}</option>
                                                    <option value="{{ \App\Models\Advertise::POSITION_DISCOVERY }}" @if($params['position'] == 5) selected @endif>{{ \App\Models\Advertise::POSITION_T[5] }}</option>
                                                    <option value="{{ \App\Models\Advertise::POSITION_MINE }}" @if($params['position'] == 6) selected @endif>{{ \App\Models\Advertise::POSITION_T[6] }}</option>
                                                    <option value="{{ \App\Models\Advertise::POSITION_MQ_RULE }}" @if($params['position'] == 7) selected @endif>{{ \App\Models\Advertise::POSITION_T[7] }}</option>
                                                    <option value="{{ \App\Models\Advertise::POSITION_MAY_LIKE }}" @if($params['position'] == 8) selected @endif>{{ \App\Models\Advertise::POSITION_T[8] }}</option>
                                                    <option value="{{ \App\Models\Advertise::POSITION_INVITE_CARD }}" @if($params['position'] == 9) selected @endif>{{ \App\Models\Advertise::POSITION_T[9] }}</option>
                                                    <option value="{{ \App\Models\Advertise::POSITION_DYNAMIC }}" @if($params['position'] == 10) selected @endif>{{ \App\Models\Advertise::POSITION_T[10] }}</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="space15"></div>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>名称</th>
                                            <th>用户类型</th>
                                            {{--<th>品牌</th>--}}
                                            <th>位置</th>
                                            <th style="max-width: 300px">链接</th>
                                            <th>图片</th>
                                            <th>是否显示</th>
                                            <th>显示顺序</th>
                                            <th>轮换顺序</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($list)) {
                                        foreach ($list as $k => $item) {
                                        ?>
                                        <tr>
                                            <td>{!! $item->id !!}</td>
                                            <td>{!! $item->subject !!}</td>
                                            <td>
                                                @if($item->type == 1)
                                                    无主
                                                @elseif($item->type == 2)
                                                    启赋
                                                @elseif($item->type == 3)
                                                    金装
                                                @endif
                                            </td>
                                            {{--<td>{!! $item->brand !!}</td>--}}
                                            <td>
                                                {{ \App\Models\Advertise::POSITION_T[$item->position] }}
                                            </td>
                                            <td style="max-width: 300px; word-wrap: break-word">{!! $item->link !!}</td>
                                            <td style="max-width: 250px"><img src="{{ $item->img }}" style="max-width: 220px"></td>
                                            <td>
                                                @if($item->display == 0)
                                                    不显示
                                                @elseif($item->display == 1)
                                                    显示
                                                @endif
                                            </td>
                                            <td>{!! $item->order !!}</td>
                                            <td>
                                                @if($item->need_trans)
                                                    {{$item->need_trans}}
                                                @else
                                                    否
                                                @endif
                                            </td>

                                            <td class="">
                                                <button data-toggle="modal" data-target="#editModal" class="btn btn-primary btn-xs" onclick="clickEdit('{!! $item->id !!}', '{!! $item->type !!}', '{!! $item->brand_id !!}', '{!! $item->position !!}', '{!! $item->link !!}', '{!! $item->img !!}', '{!! $item->display !!}', '{!! $item->need_trans !!}', '{!! $item->subject !!}', '{!! $item->order !!}')"><i class="fa fa-pencil "></i>编辑</button>
                                                <button class="btn btn-danger btn-xs" onclick="deleteConfirm('{!! $item->id !!}')"><i class="fa fa-trash-o "></i>删除</button>
                                            </td>
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

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">编辑广告</h4>
            </div>
            <form class="cmxform tasi-form" id="editForm" method="post" action="/admin/advertise/edit" enctype="multipart/form-data">
                <div class="form-group" style="display: none;">
                    <input id="editId" name="id">
                </div>
                <div class="form-group">
                    <label for="subject" class="control-label col-lg-3 cus-label">名称</label>
                    <div class="col-md-9" style="flex-grow: 1; margin: 0 0 20px 0">
                        <input class=" form-control" id="subject" name="subject" minlength="2" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="type" class="control-label col-lg-3 cus-label">用户/课程 类型</label>
                    <div class="col-lg-9">
                        <div class="shihe_1" style="float: left;">
                            <select name="type" id="type" class="form-control m-bot15">
                                <option value="1">无主/干货</option>
                                <option value="2">启赋</option>
                                <option value="3">金装</option>
                            </select>
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                {{--<div class="form-group">--}}
                    {{--<label for="brand_id" class="control-label col-lg-2 cus-label">品牌</label>--}}
                    {{--<div class="col-lg-9">--}}
                        {{--<div class="shihe_1" style="float: left;">--}}
                            {{--<select name="brand_id" id="brand_id" class="form-control m-bot15">--}}
                                {{--<option value="0">无</option>--}}
                                {{--@foreach($brands as $item)--}}
                                    {{--<option value="{!! $item->id !!}">{!! $item->name !!}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        {{--<span class="help-inline"></span>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="position" class="control-label col-lg-3 cus-label">位置</label>
                    <div class="col-lg-9">
                        <div class="shihe_1" style="float: left;">
                            <select name="position" id="position" class="form-control m-bot15">
                                <option value="{{ \App\Models\Advertise::POSITION_INDEX_TOP }}" @if($params['position'] == 1) selected @endif>{{ \App\Models\Advertise::POSITION_T[1] }}</option>
                                <option value="{{ \App\Models\Advertise::POSITION_INDEX_MID }}" @if($params['position'] == 2) selected @endif>{{ \App\Models\Advertise::POSITION_T[2] }}</option>
                                <option value="{{ \App\Models\Advertise::POSITION_COURSE_MID }}" @if($params['position'] == 3) selected @endif>{{ \App\Models\Advertise::POSITION_T[3] }}</option>
                                <option value="{{ \App\Models\Advertise::POSITION_COURSE_BOTTOM }}" @if($params['position'] == 4) selected @endif>{{ \App\Models\Advertise::POSITION_T[4] }}</option>
                                <option value="{{ \App\Models\Advertise::POSITION_DISCOVERY }}" @if($params['position'] == 5) selected @endif>{{ \App\Models\Advertise::POSITION_T[5] }}</option>
                                <option value="{{ \App\Models\Advertise::POSITION_MINE }}" @if($params['position'] == 6) selected @endif>{{ \App\Models\Advertise::POSITION_T[6] }}</option>
                                <option value="{{ \App\Models\Advertise::POSITION_MQ_RULE }}" @if($params['position'] == 7) selected @endif>{{ \App\Models\Advertise::POSITION_T[7] }}</option>
                                <option value="{{ \App\Models\Advertise::POSITION_MAY_LIKE }}" @if($params['position'] == 8) selected @endif>{{ \App\Models\Advertise::POSITION_T[8] }}</option>
                                <option value="{{ \App\Models\Advertise::POSITION_INVITE_CARD }}" @if($params['position'] == 9) selected @endif>{{ \App\Models\Advertise::POSITION_T[9] }}</option>
                                <option value="{{ \App\Models\Advertise::POSITION_DYNAMIC }}" @if($params['position'] == 10) selected @endif>{{ \App\Models\Advertise::POSITION_T[10] }}</option>
                            </select>
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="link" class="control-label col-lg-3 cus-label">链接</label>
                    <div class="col-md-9" style="flex-grow: 1; margin: 0 0 20px 0">
                        <input class=" form-control" id="link" name="link" minlength="2" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="control-label col-lg-3 cus-label">图片</label>
                    <div class="col-lg-9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img id="addTagImg" data-src="" src="" alt="">
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                            </div>
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
                    <label for="display" class="control-label col-lg-3 cus-label">是否显示</label>
                    <div class="col-lg-9">
                        <div class="shihe_1" style="float: left;">
                            <select name="display" id="display" class="form-control m-bot15">
                                <option value="1">是</option>
                                <option value="0">否</option>
                            </select>
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="need_trans" class="control-label col-lg-3 cus-label">轮换顺序</label>
                    <div class="col-lg-9">
                        <div class="shihe_1" style="float: left;">
                            <select name="need_trans" id="need_trans" class="form-control m-bot15">
                                <option value="0">否</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                        <span class="help-inline"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="order" class="control-label col-lg-3 cus-label">显示顺序</label>
                    <div class="col-md-9" style="flex-grow: 1; margin: 0 0 20px 0">
                        <input class=" form-control" id="order" name="order" type="text">
                    </div>
                </div>
                <input name="version" value="{{$version}}" style="display: none">
                <div class="modal-footer" style="border-top: none">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary" id="">确认</button>
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
<script src="/admin_style/layer/layer.js"></script>
<!--this page  script only-->
<script src="/admin_style/flatlab/js/advanced-form-components.js"></script>
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/js/bootbox.4.4.0.min.js"></script>
<script>

//    $(document).ready(function () {
//        submitMyForm();
//    });
    var version = <?php echo $version ?>

    function clickEdit(id, type, brand_id, position, link, img, display, need_trans, subject, order) {
        $("#editId").val(id);
        $('#type').val(type);
        $("#brand_id").val(brand_id);
        $('#position').val(position);
        $("#link").val(link);
        $('#addTagImg').attr('src', img);
        $("#display").val(display);
        $("#need_trans").val(need_trans);
        $('#subject').val(subject);
        $('#order').val(order)
    }

    $('#editModal').on('hidden.bs.modal', function () {
        $("#editId").val('');
        $('#type').val(1);
        $("#brand_id").val(0);
        $('#position').val(1);
        $("#link").val('');
        $('#addTagImg').attr('src', '/admin_style/img/no_image.png');
        $("#display").val(1);
        $("#need_trans").val(0);
        $('#subject').val('');
        $('#order').val()
    })

    function deleteConfirm(id) {
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
            message: '确定删除该广告吗？',
            callback: function(result) {
                if(result) {
                    $.post("/admin/advertise/delete", {'id': id}, function (data) {
                        if (data.status == 1) {
                            noty({text: data.msg, type: "success", timeout: 2000});
                            window.location.reload();
                        } else {
                            noty({text: data.msg, type: "error", timeout: 2000});
                        }
                    }, 'json');
                } else {
                    return true;
                }
            }
        });
    }

    function submitMyForm () {
        $('#search').submit();
    }

    // 选择展示哪个标签页
    $('#myTab').find('li:eq(' + version + ') a').tab('show');

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ($(e.target).text() == 'A版') {
            $('#inputType').val(0);
        } else {
            $('#inputType').val(1);
        }
        $('#search').submit();
        console.log($(e.target).text()); // 激活的标签页
        console.log(e.relatedTarget); // 前一个激活的标签页
    })
</script>
</body>
</html>