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
                        <form action="#" method="get" id="formType">
                            <input id="inputType" type="text" name="type" value="0" style="display: none">
                        </form>
                        <header class="panel-heading tab-bg-dark-navy-blue ">
                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#" aria-expanded="false">内容标签</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#" aria-expanded="false">月龄标签</a>
                                </li>
                            </ul>
                        </header>
                        <div class="panel-body">
                            <div class="tab-content">
                                <section id="unseen">
                                    <div class="row-fluid" style="margin-bottom: 10px; display: flex; flex-direction: row; align-items: center; justify-content: space-between">
                                        <div class="clearfix">
                                            <div class="btn-group">
                                                <button data-toggle="modal" data-target="#addModal" id="editable-sample_new" class="btn green">
                                                    添加新标签 <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <form action="#" method="get" class="col-lg-4">
                                            <div class="input-group input-group-sm col-lg-12">
                                                <span class="input-group-addon">标签名称</span>
                                                <input type="text" class="form-control" name="name" value="{{$params['name']}}" >
                                                <span class="input-group-btn">
                                                    <button class="btn btn-white" name="sort" type="submit" value="id">搜索并按id排序</button>
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
                                            <th>图标</th>
                                            <th>兴趣图标</th>
                                            <th>关联课程数</th>
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
                                            <td>
                                                @if($item->img)
                                                    <img src="{{ $item->img }}" style="width: 58px; height: 58px; object-fit: cover">
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->interest_img)
                                                    <img src="{{ $item->interest_img }}" style="width: 58px; height: 58px; object-fit: cover">
                                                @endif
                                            </td>

                                            <?php if ($item->course_num) {?>
                                            <td><a href="/admin/course/index?tid=<?php echo $item->id; ?>"><?php echo $item->course_num; ?></a></td>
                                            <?php } else {?>
                                            <td><?php echo $item->course_num; ?></td>
                                            <?php }?>
                                            <td class="">
                                                <!--                                                    <a href="/admin/city/add/--><?php //echo $item->id; ?><!--" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i>编辑</a>-->
                                                <button data-toggle="modal" data-target="#editModal" onclick="clickEdit('<?php echo $item->id; ?>', '<?php echo $item->name; ?>', '<?php echo $item->img; ?>', '<?php echo $item->interest_img; ?>')" class="btn btn-primary btn-xs"><i class="fa fa-pencil "></i>编辑</button>
                                                <button onclick="deleteConfirm('<?php echo $item->id; ?>', '<?php echo $item->course_num; ?>')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i>删除</button>
                                            </td>
                                        </tr>
                                        <?php }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php echo $list->render(); ?>
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

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">添加标签</h4>
            </div>
            <form class="cmxform tasi-form" id="editForm" method="post" action="/admin/tags/add" enctype="multipart/form-data">
                <div class="form-group" style="display: none">
                    <div class="col-lg-10">
                        <input class=" form-control" name="type" id="type" type="text" value="{{$type}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="control-label col-lg-2" style="margin: 20px 0 0 0">标签名称</label>
                    <div class="col-lg-10" style="flex-grow: 1; margin: 10px 0 20px 0">
                        <input class=" form-control" name="name" id="tagName" minlength="2" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="control-label col-lg-2">图标</label>
                    <div class="col-md-10">
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
                    <label for="title" class="control-label col-lg-2">兴趣图标</label>
                    <div class="col-md-10">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img id="addTagIcon" data-src="" src="" alt="">
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                            </div>
                            <div>
                               <span class="btn btn-white btn-file">
                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                               <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                               <input type="file" class="default" name="interest_img">
                               </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">添加</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">编辑标签</h4>
            </div>
            <form class="cmxform tasi-form" id="editForm" method="post" action="/admin/tags/edit" enctype="multipart/form-data">
                <div class="form-group" style="display: none;">
                    <input id="editId" name="id">
                </div>
                <div class="form-group" style="display: none">
                    <div class="col-lg-10">
                        <input class=" form-control" name="type" id="type" type="text" value="{{$type}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="control-label col-lg-2">标签名称</label>
                    <div class="col-md-10" style="flex-grow: 1; margin: 10px 0 20px 0">
                        <input class=" form-control" name="name" id="editTagName" minlength="2" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="title" class="control-label col-lg-2">图片</label>
                    <div class="col-md-10">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img id="editTagImg" data-src="" src="" alt="">
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
                    <label for="title" class="control-label col-lg-2">兴趣图标</label>
                    <div class="col-md-10">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img id="editTagIcon" data-src="" src="" alt="">
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                            </div>
                            <div>
                               <span class="btn btn-white btn-file">
                               <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
                               <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                               <input type="file" class="default" name="interest_img">
                               </span>
                            </div>
                        </div>
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
<script type="text/javascript">
    var type = <?php echo $type ?>

    function deleteConfirm(id, course_num) {
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
            message: '该标签与' + course_num + '节课相关联，确定删除吗？',
            callback: function(result) {
                if(result) {
                    $.post("/admin/tags/delete", {'id': id}, function (data) {
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

    function clickEdit(id, name, img, interest_img) {
        $("#editId").val(id)
        $('#editTagName').val(name);
        $("#editTagImg").attr('src', img);
        $('#editTagIcon').attr('src', interest_img)
    }

    // 选择展示哪个标签页
    $('#myTab li:eq(' + type + ') a').tab('show');

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ($(e.target).text() == '内容标签') {
            $('#inputType').val(0);
        } else if ($(e.target).text() == '月龄标签') {
            $('#inputType').val(1);
        } else {
            $('#inputType').val(2);
        }
        $('#formType').submit();
        console.log($(e.target).text()); // 激活的标签页
        console.log(e.relatedTarget); // 前一个激活的标签页
    })
</script>
</body>
</html>