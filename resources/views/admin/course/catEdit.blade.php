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
                            套课 | <a class="btn btn-shadow btn-info" href="/admin/course/cat">返回</a>
                        </header>
                        <div class="panel-body">
                            <div class=" form">
                                <form class="cmxform form-horizontal tasi-form" id="courseForm" method="post" action="/admin/course/cat_save" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="{{$courseCat->id}}">
                                    <div class="form-group ">
                                        <label for="title" class="control-label col-lg-2">名称(15字以内)</label>
                                        <div class="col-lg-6">
                                            <input class="form-control" type="text" name="name" value="{{$courseCat->name}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="title" class="control-label col-lg-2">描述</label>
                                        <div class="col-lg-6">
                                            <textarea class="form-control" name="description" rows="3" cols="100">{{$courseCat->description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">图片</label>
                                        <div class="col-md-9">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail" style="width: 200px;">
                                                    @if ($courseCat->img)
                                                        <img src="{{$courseCat->img}}" alt="">
                                                    @else
                                                        <img src="/admin_style/img/no_image.png" alt="">
                                                    @endif
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
                                        <label for="link" class="control-label col-lg-2">链接</label>
                                        <div class="col-lg-6">
                                            <input class="form-control" type="text" name="link" value="{{$courseCat->link}}" />
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="price" class="control-label col-lg-2">价格（MQ）</label>
                                        <div class="col-lg-6">
                                            <input class="form-control" type="text" name="price" value="{{$courseCat->price}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="show_type" class="control-label col-lg-2 cus-label">显示类型</label>
                                        <div class="col-lg-9">
                                            <div class="shihe_1" style="float: left;">
                                                <select name="show_type" id="show_type" class="form-control m-bot15">
                                                    <option value="0" @if($courseCat->show_type == '0') selected @endif>系列型</option>
                                                    <option value="1" @if($courseCat->show_type == '1') selected @endif>专家型</option>
                                                    <option value="2" @if($courseCat->show_type == '2') selected @endif>多图型</option>
                                                    <option value="3" @if($courseCat->show_type == '3') selected @endif>介绍型</option>
                                                </select>
                                            </div>
                                            <span class="help-inline"></span>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="title" class="control-label col-lg-2">排序</label>
                                        <div class="col-lg-6">
                                            <input class="form-control" type="text" name="displayorder" value="{{$courseCat->displayorder}}" />
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

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script>
    $("#courseForm").validate({
        rules: {
            name: "required"
        },
        messages: {
            name: "请填写课程名称"
        }
    });
</script>
</body>
</html>
