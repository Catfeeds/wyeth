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
        .cus-label { margin-top: 5px; text-align: right }
        .pagination { display: inline-block;  padding-left: 0;  margin: 20px 0;  border-radius: 4px;  }
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
                                                <button data-toggle="modal" data-target="#editModal" id="editable-sample_new" class="btn green">
                                                    添加平台 <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div style="flex: 1"></div>
                                        {{--<form action="#" method="get" class="col-lg-4">--}}
                                            {{--<div class="input-group input-group-sm col-lg-12">--}}
                                                {{--<span class="input-group-addon">搜索</span>--}}
                                                {{--<input type="text" class="form-control" name="key_word" placeholder="名称/品牌/关键词" >--}}
                                                {{--<span class="input-group-btn">--}}
                                                {{--<button class="btn btn-white" name="sort" type="submit" value="id">搜索并按id排序</button>--}}
                                            {{--</span>--}}
                                            {{--</div>--}}
                                        {{--</form>--}}
                                    </div>
                                    <div class="space15"></div>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>名称</th>
                                            <th>头像</th>
                                            <td>操作</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($list)) {
                                        foreach ($list as $k => $item) {
                                        ?>
                                        <tr>
                                            <td>{{$item['id']}}</td>
                                            <td><a href="/admin/materiel?platform={{$item['id']}}">{{$item['author_name']}}</a></td>
                                            <td style="width: 80px; height: 80px;"><img src="{{$item['author_avatar']}}" style="width: 80px; height: 80px;"></td>
                                            <td>
                                                <button data-toggle="modal" data-target="#editModal" onclick="clickEdit('{{$item["id"]}}', '{{$item["author_name"]}}', '{{$item["author_avatar"]}}')" class="btn btn-primary btn-xs"><i class="fa fa-pencil "></i>编辑</button>
                                                <button class="btn btn-danger btn-xs" onclick="deleteConfirm('{{$item["id"]}}')"><i class="fa fa-trash-o "></i>删除</button>
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
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->
    <!--footer start-->
<?php echo $footer;?>
<!--footer end-->
</section>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">添加平台</h4>
            </div>
            <form class="cmxform tasi-form" id="editForm" method="post" action="/admin/platform/edit" enctype="multipart/form-data">
                <div class="form-group" style="display: none;">
                    <input id="editId" name="id">
                </div>
                <div class="form-group" style="display: none;">
                    <input id="old_name" name="old_name">
                </div>
                <div class="form-group">
                    <label for="platform_name" class="control-label col-lg-2 cus-label">名称</label>
                    <div class="col-md-9" style="flex-grow: 1; margin-bottom: 10px">
                        <input class=" form-control" id="platform_name" name="platform_name" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="platform_logo" class="control-label col-lg-2 cus-label">图标</label>
                    <div class="col-lg-9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img id="platform_logo" data-src="" src="" alt="">
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 80px; max-height: 80px; line-height: 20px;">
                            </div>
                            <div>
                               <span class="btn btn-white btn-file">
                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                               <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                               <input type="file" class="default" name="platform_logo">
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
<script src="/admin_style/js/jqPaginator.js"></script>
<script>
    function clickEdit(id, platform_name, platform_logo) {
        $("#editId").val(id);
        $('#old_name').val(platform_name);
        $('#platform_name').val(platform_name);
        $('#platform_logo').attr('src', platform_logo);
        console.log(id, platform_logo, platform_name);
    }

    $('#editModal').on('hidden.bs.modal', function () {
        $("#editId").val(0);
        $('#old_name').val('');
        $('#platform_name').val('');
        $('#platform_logo').attr('src', '/admin_style/img/no_image.png');
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
            message: '确定删除该平台吗？',
            callback: function(result) {
                if(result) {
                    $.post("/admin/platform/delete", {'id': id}, function (data) {
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

    $('#pagination').jqPaginator({
        totalPages: Math.ceil({{$total / 10}}),
        visiblePages: 10,
        currentPage: getPage(),
        onPageChange: function (num, type) {
            if (this.currentPage != num) {
                window.location = "/admin/platform?page=" + num;
            }
        }
    });


    function getPage() {
        var reg = new RegExp("(^|&)"+ 'page' +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r != null){
            return parseInt(unescape(r[2]));
        } else {
            return 1;
        }
    }
</script>
</body>
</html>