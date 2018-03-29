<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>
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

    <!--dynamic table-->
    <link href="/admin_style/flatlab/assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" />
    <link href="/admin_style/flatlab/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="/admin_style/flatlab/assets/data-tables/DT_bootstrap.css" />
    <!--right slidebar-->
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">
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
<iframe id = "sf" name="form_submit"  src="about:blank" style="display:none";>

</iframe>

<section id="container" class="">
    <!--header start-->
    <?php echo $header; ?><!--header end-->
<!--sidebar start-->
<?php echo $sidebar;?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        模板消息推送
                        <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <a href="javascript:;" class="fa fa-times"></a>
    </span>
                    </header>
                    <div class="panel-body">
                        <form id="submit_form" action="/admin/query/tplmsg2" class="form-horizontal tasi-form" method="post" enctype="multipart/form-data"  tyle="margin-bottom: 10px;" onsubmit="return myFunction();" >
                            <div class="form-group">
                                <label class="control-label col-lg-2" style="float:left">日期范围(周)</label>
                                <div class="col-md-4">
                                    <div class="input-group input-large">
                                        <input type="text" class="form-control dpd1" name="from">
                                        <span class="input-group-addon">至</span>
                                        <input type="text" class="form-control dpd2" name="to">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label col-lg-2">推送课程id(逗号隔开)</label>
                                <div class="col-lg-4">
                                    <input id="cids" class="form-control" name="cid" type="text">
                                    <input style="margin-left: 15px" name="kk" type="checkbox" value="kk"> 开课
                                    <input style="margin-left: 15px" name="hg" type="checkbox" value="gh"> 回顾
                                    <input style="margin-left: 15px" name="11" type="checkbox" value="11"> 模板1+1
                                    <input style="margin-left: 15px" name="auto" type="checkbox" value="auto" onchange="change(this)"> 自动生成
                                </div>

                            </div>
                            <div class="form-group">
                                <?php if($state){ ?>
                                <button class="btn btn-default" onclick="return doNothing()" style="margin-left: 15px;">生成中...</button>
                                <?php }else {?>
                                <button class="btn btn-primary" type="submit"  style="margin-left: 15px;">生成数据</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </section>

                <section class="panel">
                    <header class="panel-heading">
                        下载文件
                        <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <a href="javascript:;" class="fa fa-times"></a>
    </span>
                    </header>
                    <div class="panel-body">
                            <div class="form-group">
                                <div>
                                    <span style="margin-left: 25px;">时间范围：<?php echo $info ?></span>
                                    <a href="/admin/query/export" class="btn btn-primary btn-xs" style="margin-left: 50px;">下载</a>
                                    <?php if($state){ ?>
                                    <span style="margin-left: 25px; color:#F00;">上次的信息还在生成</span>
                                    <?php } ?>
                                </div>
                    </div>
                </section>
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
<script src="/admin_style/flatlab/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/admin_style/flatlab/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="/admin_style/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="/admin_style/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="/admin_style/flatlab/assets/advanced-datatable/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/admin_style/flatlab/assets/data-tables/DT_bootstrap.js"></script>
<script src="/admin_style/flatlab/js/respond.min.js" ></script>

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

<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>
<script src="/admin_style/layer/layer.js"></script>

<script>
    $(function(){
        $(".dpd1").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        $(".dpd2").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>
<script>
    function myFunction()
    {
        //alert("正在后台生成数据，请稍后刷新网页");
        if (window.confirm("确认无误？")) {
            return true;
        } else {
            return false;
        }
    }
    function doNothing() {
        return false;
    }
    function change(obj) {
        if ($(obj).prop("checked")) {
            $("#cids").attr("value",'');
            $("#cids").attr("disabled",true);
        } else {
            $("#cids").removeAttr('disabled');
        }
    }

</script>

</body>
</html>
