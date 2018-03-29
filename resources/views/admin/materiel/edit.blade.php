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
        .select2-results__options { height: 200px; overflow-y: scroll }
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
                            @if($materiel)
                            编辑物料
                            @else
                            新增物料
                            @endif
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="" enctype="multipart/form-data">
                                    <div class="form-group ">
                                        <label for="name" class="control-label col-lg-2">图文标题</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="name" name="name" minlength="2" type="text" value="{{$materiel ? $materiel->name : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="link" class="control-label col-lg-2">推送链接</label>
                                        <div class="col-lg-6">
                                            <input class=" form-control" id="link" name="link" minlength="2" type="text" value="{{$materiel ? $materiel->link : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="brand" class="control-label col-lg-2 cus-label">品牌</label>
                                        <div class="col-md-9" style="flex-grow: 1; margin-bottom: 10px">
                                            <div class="shihe_1" style="float: left;">
                                                <select name="brand" class="form-control m-bot15">
                                                    @foreach($brands as $brand)
                                                        <option value="{{$brand->name}}" @if($materiel && $materiel->brand == $brand->name) selected @endif>{{$brand->name}}</option>
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
                                                <select id="user_platform_select" name="platform_name" class="form-control m-bot15">
                                                    @if($platform)
                                                    @foreach($platform as $p)
                                                        <option value="{{$p['id']}}" @if($materiel && $materiel->platform_name == $p['id']) selected @endif>{{$p['author_name']}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="date" class="control-label col-lg-2 cus-label">日期</label>
                                        <div class="col-md-9" style="flex-grow: 1; margin-bottom: 10px">
                                            <input type="text" class="form-control date-picker" id="date" name="date" value="{{$materiel ? $materiel->date : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="key_word" class="control-label col-lg-2 cus-label">关键词</label>
                                        <div style="margin: 10px 20px">
                                            <select id="key_word" class="form-control" multiple="multiple">
                                                @foreach($keywords as $k)
                                                    <option selected="selected" value="{{ $k->id }}">{{ $k->name }}</option>
                                                @endforeach
                                            </select>
                                            <input name="key_word" id="hide_key" style="display: none;" value="{{$materiel ? $materiel->key_word : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="banner" class="control-label col-lg-2 cus-label">头图<br><b style="color: #ff0000">(建议尺寸750*314)</b></label>
                                        <div class="col-lg-9">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img id="banner" data-src="" src="{{$materiel ? $materiel->banner : ''}}" alt="">
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
                                </form>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button id="submitButton" class="btn btn-danger" type="submit" onclick="submitMyForm()">保存</button>
                                        <button class="btn btn-default" type="reset">重置</button>
                                    </div>
                                </div>
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

<div class="modal fade" role="dialog" id="previewDialog" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">保存成功，扫描二维码预览图文</h4>
            </div>
            <div id="qrCode" style="display: flex; align-items: center; justify-content: center; padding: 10px">
            </div>
            <div class="modal-footer">
                <div class="col-lg-offset-2 col-lg-10">
                    <button class="btn btn-danger" onclick="location.href = '/admin/materiel'">进入列表</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/new_js/jquery-3.2.1.min.js"></script>
<script src="/js/bootbox.4.4.0.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="/admin_style/flatlab/js/respond.min.js" ></script>
<script src="/admin_style/flatlab/js/jquery.validate.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap-switch.js"></script>
<script src="/js/jquery.qrcode.min.js"></script>
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
    $(".date-picker").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $('#key_word').on('change', function (e) {
        $("#hide_key").val($('#key_word').val().toString());
    });
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

    $('#key_word').select2({
        tags: 'true',
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

    function submitMyForm() {
        $("#submitButton").attr('disabled', true);
        var formData = new FormData($("#courseForm")[0]);
        $.ajax({
            url: '/admin/materiel/edit/{{$id}}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if (data.status == 1) {
                    console.log(data.msg);
                    $("#qrCode").qrcode({width: 200, height: 200, text: '{{config('app.url') . '/mobile/index?defaultPath=/discover'}}' });
                    $("#previewDialog").modal('toggle');
                } else {
                    console.log('error');
                }
            }
        })
    }
</script>
</body>
</html>