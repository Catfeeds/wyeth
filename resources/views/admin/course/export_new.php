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
                            数据导出
                            <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <a href="javascript:;" class="fa fa-times"></a>
    </span>
                        </header>
                        <div class="panel-body">
                            <form id="submit_form" action="/admin/course_export_new/generate" class="form-horizontal tasi-form" method="post" enctype="multipart/form-data"  tyle="margin-bottom: 10px;" onsubmit="return myFunction();" >
                                <div class="form-group">
                                    <label class="control-label col-lg-2" style="float:left">日期范围(周)</label>
                                    <div class="col-md-4">
                                        <div class="input-group input-medium">
                                            <input type="text" class="form-control dpd1" name="from">
                                            <span class="input-group-addon">至</span>
                                            <input type="text" class="form-control dpd2" name="to">
                                        </div>
                                    </div>
                                    <input type="hidden" name="type" value="export" />
					</div> 

								<div class="form-group ">
								<label class="control-label col-lg-2">本周新课id(逗号隔开)</label>
                                                        <div class="col-lg-4">
                                                            <input class=" form-control" name="cid" type="text">
                                                        </div>
                                                    </div>

                                <div class="form-group">
                                    <label class="control-label col-lg-2">其他平台数据,提问数</label>
                                    <div class="controls col-md-9">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <span class="btn btn-white btn-file">
                                                <span class="fileupload-new"><i class="fa fa-paper-clip" style="margin-left:5px;"></i>选择表格文件(.csv)</span>
                                                <span class="fileupload-exists"><i class="fa fa-undo"></i> 更换</span>
                                                <input type="file" accept="text/csv" class="default" name="file" >
                                                </span>
                                            <span class="fileupload-preview" style="margin-left:5px;"></span>
                                            <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
                                            <a href="/admin/course_export_new/export?type=example&id=0" class="btn btn-primary btn-xs" style="margin-left: 15px">表格模板</a>
                                        </div>
                                    </div>
                                </div>
	<div class="form-group">
   <button class="btn btn-default" type="submit"  style="margin-left: 15px;">生成数据表</button>
                                </div>
	</div>
                            </section>
                            <div class="adv-table">
                                <table class="display table table-bordered" id="hidden-table-info">
                                    <thead>
                                    <tr>
                                        <th>时间范围</th>
                                        <th>操作（导出）</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($list)) {
                                        foreach ($list as $k => $item) {
                                            ?>
                                            <tr class="gradeA">
                                                <td><?=$item->start_day?>~<?=$item->end_day?></td>
                                                <td>
                                                    <a href="/admin/course_export_new/export?type=all_course_week&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">全课程表</a>
                                                    <span style="margin-left: 30px;"></span>
                                                    <a href="/admin/course_export_new/export?type=week_summary&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">周报summary</a>
						                            <span style="margin-left: 30px;"></span>
                                                    <a href="/admin/course_export_new/export?type=week_new_course&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">广告summary</a>
                                                    <span style="margin-left: 30px;"></span>
                                                    <a href="/admin/course_export_new/export?type=week_diversion&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">首页流量分析</a>
                                                    <span style="margin-left: 30px;"></span>
                                                    <a href="/admin/course_export_new/export?type=signup_by_channel&id=<?php echo $item->id; ?>" class="btn btn-primary btn-xs">自动下行数据</a>
 </td>
                                            </tr>
                                        <?php }
                                    }
                                    ?>
                                    </tbody>
                                </table>
				<?php echo $list->render(); ?>
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
		if (window.confirm("确认生成数据？")) {
		return true;
		} else {
		return false;
		}
    }

</script>

</body>
</html>
