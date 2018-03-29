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

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/admin_style/flatlab/js/html5shiv.js"></script>
    <script src="/admin_style/flatlab/js/respond.min.js"></script>
    <![endif]-->
    <style>
        .cus-label { margin-top: 5px; text-align: right }
        .select2-results__options { height: 200px; overflow-y: scroll }
        .select2-selection__rendered input {width: 200px!important;}
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
                            <input id="inputType" type="text" name="type" value="0" style="display: none">
                        </form>
                        <div class="panel-body">
                            <div class="tab-content">
                                <section id="unseen">
                                    <div class="row-fluid" style="margin-bottom: 10px; display: flex; flex-direction: row; align-items: center;">
                                        <div class="clearfix">
                                            <div class="btn-group">
                                                <a href="/admin/materiel/edit/0" class="btn green btn-green">添加新图文<i class="fa fa-plus"></i></a>
                                                {{--<button data-toggle="modal" data-target="#editModal" id="editable-sample_new" class="btn green">--}}
                                                    {{--添加新图文 --}}
                                                {{--</button>--}}
                                            </div>
                                        </div>
                                        @if($user_info->user_type == 0)<a class="btn btn-info" style="margin-left: 10px" href="/admin/cms">编辑图文</a>@endif
                                        <div style="flex: 1"></div>
                                        <form action="#" method="get" class="col-lg-8" id="searchForm" style="display: flex; flex-direction: row">
                                            <div class="input-group input-group-sm col-lg-6">
                                                <span class="input-group-addon">平台</span>
                                                <select name="platform" class="form-control m-bot15" onchange="submitMyForm()">
                                                    <option value="0" @if($params['platform'] == 0) selected @endif>全部</option>
                                                    @if($platform)
                                                    @foreach($platform as $p)
                                                        <option value="{{$p['id']}}" @if($params['platform'] == $p['id']) selected @endif>{{$p['author_name']}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="input-group input-group-sm col-lg-6">
                                                <span class="input-group-addon">搜索</span>
                                                <input type="text" class="form-control" name="key_word" value="{{$params['key_word']}}" placeholder="名称/品牌/关键词" >
                                                <span class="input-group-btn">
                                                <button class="btn btn-white" name="sort" type="submit" value="id">搜索</button>
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
                                            <th>头图(建议尺寸750*314)</th>
                                            <th>品牌</th>
                                            <th>平台名</th>
                                            <th>日期</th>
                                            <th>推送链接</th>
                                            <th>物料下载</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($list)) {
                                        foreach ($list as $k => $item) {
                                        ?>
                                        <tr>
                                            <td><?php echo $item->id; ?></td>
                                            <td><?php echo $item->name; ?></td>
                                            <td style="max-width: 240px"><img src="<?php echo $item->banner; ?>" style="max-width: 200px"></td>
                                            <td>{{$item->brand}}</td>
                                            <td>
                                                @if($item->platform_name == 1)
                                                    惠氏妈妈俱乐部
                                                @elseif($item->platform_name == 2)
                                                    妈妈微课堂
                                                @elseif($item->platform_name == 3)
                                                    惠氏BabyNes贝睿思
                                                @else
                                                    无
                                                @endif
                                            </td>
                                            <td><?php echo $item->date; ?></td>

                                            <td>
                                                @if($item->link)
                                                    <a onclick="goTo('{!! $item->link !!}')">点击查看详情</a></td>
                                                @else
                                                    暂无内容
                                                @endif
                                            <td>
                                                @if($item->link)
                                                    <a href="/admin/materiel/download_html/<?php echo $item->id; ?>">下载</a>
                                                @else
                                                    暂无内容
                                                @endif
                                            </td>
                                            <td class="">
                                                <a href="/admin/materiel/edit/<?php echo $item->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i>编辑</a>
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
                <h4 class="modal-title" id="myModalLabel">编辑图文</h4>
            </div>
            <form class="cmxform tasi-form" id="editForm" method="post" action="/admin/materiel/edit" enctype="multipart/form-data">
                <div class="form-group" style="display: none;">
                    <input id="editId" name="id">
                </div>
                <div class="form-group">
                    <label for="name" class="control-label col-lg-2 cus-label">图文标题</label>
                    <div class="col-md-9" style="flex-grow: 1; margin-bottom: 10px">
                        <input class=" form-control" id="name" name="name" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="link" class="control-label col-lg-2 cus-label">推送链接</label>
                    <div class="col-md-9" style="flex-grow: 1; margin-bottom: 10px">
                        <input class=" form-control" id="link" name="link" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="key_s" class="control-label col-lg-2 cus-label">关键词</label>
                    <div style="margin: 10px 20px">
                        <select id="key_s" class="form-control" multiple="multiple" name="key_word">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="brand" class="control-label col-lg-2 cus-label">品牌</label>
                    <div class="col-md-9" style="flex-grow: 1; margin-bottom: 10px">
                        <div class="shihe_1" style="float: left;">
                            <select name="brand" class="form-control m-bot15">
                                @foreach($brands as $brand)
                                    <option value="{{$brand->name}}">{{$brand->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--<input type="text" class="form-control" id="brand" name="brand">--}}
                    </div>
                </div>
                <div class="form-group">
                    <label for="platform_name" class="control-label col-lg-2 cus-label">平台</label>
                    <div class="col-md-9" style="flex-grow: 1; margin-bottom: 10px">
                        <div class="shihe_1" style="float: left;">
                            <select id="user_platform_select" name="platform_name" class="form-control m-bot15" @if($user_type == 4) disabled @endif>
                                @if($platform)
                                @foreach ($platform as $p)
                                <option value="{{$p['id']}}" @if(intval($p['id']) == intval($user_info->user_platform)) selected @endif>{{$p['author_name']}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date" class="control-label col-lg-2 cus-label">日期</label>
                    <div class="col-md-9" style="flex-grow: 1; margin-bottom: 10px">
                        <input type="text" class="form-control date-picker" id="date" name="date">
                    </div>
                </div>
                {{--<div class="form-group">--}}
                    {{--<label for="platform_logo" class="control-label col-lg-2 cus-label">平台图标</label>--}}
                    {{--<div class="col-lg-9">--}}
                        {{--<div class="fileupload fileupload-new" data-provides="fileupload">--}}
                            {{--<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">--}}
                                {{--<img id="platform_logo" data-src="" src="" alt="">--}}
                            {{--</div>--}}
                            {{--<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 80px; max-height: 80px; line-height: 20px;">--}}
                            {{--</div>--}}
                            {{--<div>--}}
                               {{--<span class="btn btn-white btn-file">--}}
                               {{--<span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>--}}
                               {{--<span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>--}}
                               {{--<input type="file" class="default" name="platform_logo">--}}
                               {{--</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="banner" class="control-label col-lg-2 cus-label">头图</label>
                    <div class="col-lg-9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img id="banner" data-src="" src="" alt="">
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                            </div>
                            <div>
                               <span class="btn btn-white btn-file">
                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                               <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                               <input type="file" class="default" name="banner">
                               </span>
                            </div>
                        </div>
                    </div>
                </div>
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

<!--summernote-->
<script src="/admin_style/flatlab/assets/summernote/dist/summernote.min.js"></script>

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>
<!--this page  script only-->
<script src="/js/jquery.noty.2.3.8.packaged.min.js"></script>
<script src="/js/bootbox.4.4.0.min.js"></script>
<script src="/js/select2/select2.full.js"></script>
<script src="/js/select2/zh-CN.js"></script>
<script src="/js/lodash.js"></script>
<script type="text/javascript">

    function clickEdit(id, name, date, link, key_word, brand, platform_name, platform_logo, banner) {
        $("#editId").val(id);
        $('#name').val(name);
        $('#date').val(date);
        $("#link").val(link);
        $('#brand').val(brand);
        $('#key_s').val(key_word).trigger("change");
        $('#platform_name').val(platform_name);
        $('#platform_logo').attr('src', platform_logo);
        $('#banner').attr('src', banner);
    }

    $('#editModal').on('hidden.bs.modal', function () {
        $("#editId").val(0);
        $('#name').val('');
        $('#date').val('');
        $("#link").val('');
        $('#key_s').val('').trigger("change");
        $('#platform_name').val(0);
        $('#platform_logo').attr('src', '/admin_style/img/no_image.png');
        $('#banner').attr('src', '/admin_style/img/no_image.png');
    })

    function goTo (link) {
        location.href = link;
    }

    $(".date-picker").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

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
            message: '确定删除该图文吗？',
            callback: function(result) {
                if(result) {
                    $.post("/admin/materiel/delete", {'id': id}, function (data) {
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
        $('#searchForm').submit();
    }

    /** 显示标签 start **/
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

    $('#key_s').select2({
        width: '300px',
        theme: 'bootstrap',
        language: "zh-CN",
        placeholder: "输入标签名",
        multiple: true,
        ajax: {
            url: "/admin/keyword/search",
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
        dropdownCss: {height: '200px'},
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });
    /** 显示标签 end **/
</script>
</body>
</html>