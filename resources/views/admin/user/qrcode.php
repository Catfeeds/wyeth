<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>用户管理</title>

    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

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
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            微信用户列表
                        </header>
                        <div class="panel-body">
                            <section id="unseen">
                                <div class="row-fluid">
                                    <div class="clearfix">
                                        <div class="btn-group">
                                            <a href="/admin/user/qrcode_add/0">
                                            <button id="editable-sample_new" class="btn green">
                                                Add New <i class="fa fa-plus"></i>
                                            </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="space15"></div>
                                <table class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>图片</th>
                                        <th>省份</th>
                                        <th>阶段</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($list)) {
                                        foreach ($list as $k => $item) {
                                            ?>
                                            <tr>
                                                <td><?php echo $item->id; ?></td>
                                                <td><img src="<?php echo $item->img; ?>" width="100"></td>
                                                <td><?php echo $item->province; ?></td>
                                                <td><?php if($item->stage == 1){ echo '孕期';}elseif($item->stage == 2){ echo '0-12月';}else{ echo '未知';} ?></td>
                                                <td class="">
                                                    <a href="/admin/user/qrcode_add/<?php echo $item->id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i>编辑</a>
                                                    <a href="/admin/user/qrcode_delete/<?php echo $item->id; ?>" onclick="return confirm('您确定要删除该内容吗？');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i>删除</a>
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
                    </section>
                </div>
            </div>            <!-- page end-->
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
<!--right slidebar-->
<script src="/admin_style/flatlab/js/slidebars.min.js"></script>

<!--common script for all pages-->
<script src="/admin_style/flatlab/js/common-scripts.js"></script>

<script>
    $(document).ready(function() {

    } );
</script>
</body>
</html>
