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
                            今日数据概览
                            <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <a href="javascript:;" class="fa fa-times"></a>
    </span>
                        </header>
                        <div class="panel-body">

                            <div class="form-group" style="margin-left:3px;">
                                <div>
                                    <table class="display table table-bordered"  style="table-layout:fixed;">
                                        <tr>
                                            <th>类别</th>
                                            <th>pv</th>
                                            <th>uv</th>
                                        </tr>
                                        <tr>
                                            <th>今日pv/uv</th>
                                            <td><?php echo isset( $all['pv'])?$all['pv']:'-' ?></td>
                                            <td><?php echo isset($all['uv'])?intval($all['uv']):'-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>昨日pv/uv</th>
                                            <td><?php echo isset($yesterday['pv'])?$yesterday['pv']:"-" ?></td>
                                            <td><?php echo isset($yesterday['uv'])?intval($yesterday['uv']):"-" ?></td>
                                        </tr>
                                        <tr>
                                            <th>广告位点击</th>
                                            <td><?php echo isset($ad['pv'])?$ad['pv']:'-' ?></td>
                                            <td><?php echo isset($ad['uv'])?intval($ad['uv']):'-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>今日报名</th>
                                            <td><?php echo isset($sign['pv'])?$sign['pv']:'-' ?></td>
                                            <td><?php echo isset($sign['uv'])?intval($sign['uv']):'-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>昨日模板消息推送总量</th>

                                            <td colspan="2"><?php echo isset($yesterday_push['uv'])?intval($yesterday_push['uv']):"-" ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="panel">
                        <header class="panel-heading">
                            分时段数据查询
                            <span class="tools pull-right">
    <a href="javascript:;" class="fa fa-chevron-down"></a>
    <a href="javascript:;" class="fa fa-times"></a>
    </span>
                        </header>
                        <div class="panel-body">
                            <form id="submit_form" action="/admin/course_data_active/search" class="form-horizontal tasi-form" method="get" type="margin-bottom: 10px;" >
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <div class="input-group input-medium">
                                            <span class="input-group-addon">课程id</span>
                                            <input type="text" class="form-control" name="id" value="<?php echo $id?>" >
                                            <span class="input-group-addon">日期范围</span>
                                            <input type="text" class="form-control dpd1" name="from" value="<?php echo $from?>" >
                                            <span class="input-group-addon">至</span>
                                            <input type="text" class="form-control dpd2" name="to" value="<?php echo $to?>" >
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit" >获取数据</button>
                                </div>
                            </form>
                            <div class="form-group" style="margin-left:2px; margin-top:15px">
                                <div>
                                    <table class="display table table-bordered"  style="table-layout:fixed;">
                                        <tr>
                                            <th>类别</th>
                                            <th>pv</th>
                                            <th>uv</th>
                                        </tr>
                                        <tr>
                                            <th>pv/uv</th>
                                            <td><?php echo isset($all_s['pv'])?$all_s['pv']:"-" ?></td>
                                            <td><?php echo isset($all_s['uv'])?intval($all_s['uv']):"-" ?></td>
                                        </tr>
                                        <tr>
                                            <th>报名</th>
                                            <td><?php echo isset($sign_s['pv'])?$sign_s['pv']:"-" ?></td>
                                            <td><?php echo isset($sign_s['uv'])?intval($sign_s['uv']):"-" ?></td>
                                        </tr>
                                        <tr>
                                            <th>广告位点击</th>
                                            <td><?php echo isset($ad_s['pv'])?$ad_s['pv']:"-" ?></td>
                                            <td><?php echo isset($ad_s['pv'])?intval($ad_s['pv']):"-" ?></td>
                                        </tr>
                                        <tr>
                                            <th>模板消息推送量</th>

                                            <td colspan="2"><?php echo isset($push['uv'])?intval($push['uv']):"-" ?></td>
                                        </tr>
                                        <tr>
                                            <th>听课人数</th>
                                            <td colspan="2"><?php echo isset($in_class_num)?$in_class_num:"-" ?></td>
                                        </tr>
                                        <tr>
                                            <th>平均听课时长</th>
                                            <td colspan="2"><?php echo isset($listen_time_s)?$listen_time_s:"-" ?></td>
                                        </tr>
                                        <tr>
                                            <th>页面停留时长</th>
                                            <td colspan="2"><?php echo isset($stay_time_s)?$stay_time_s:"-" ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
        </section>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->
<?php echo $footer;?>
<!--footer end-->

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
</body>
</html>
