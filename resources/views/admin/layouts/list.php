<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title>课程管理</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/css/style.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/css/style_responsive.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/css/style_default.css" rel="stylesheet" id="style_color" />
   <link href="/admin_style/adminlab/assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/uniform/css/uniform.default.css" />
    <style type="text/css">
        body{ background:#f7f7f7 !important;}
    </style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
   <!-- BEGIN CONTAINER -->
   <div class="row-fluid">
      <!-- BEGIN PAGE -->
      <div>
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                  <h3 class="page-title">
                      课程管理
                  </h3>
                   <ul class="breadcrumb">
                       <li><a href="#"><i class="icon-home"></i></a><span class="divider">&nbsp;</span></li>
                       <li><a href="/admin/course">课程管理</a> <span class="divider-last">&nbsp;</span></li>
                   </ul>
                  <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN ADVANCED TABLE widget-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN EXAMPLE TABLE widget-->
                    <div class="widget">
                        <div class="widget-title">
                            <h4><i class="icon-reorder"></i>课程管理</h4>
                        </div>
                        <div class="widget-body">
                            <div class="row-fluid">
                                <label>名称: <input type="text" name="title" class="input-medium"></label>
                            </div>
                            <table class="table table-striped table-bordered" id="sample_1">
                            <thead>
                                <tr>
                                    <th width="10">
                                        <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                    </th>
                                    <th width="10">ID</th>
                                    <th width="100">课程名称</th>
                                    <th width="100">课程日期</th>
                                    <th width="100">开始时间</th>
                                    <th width="100">结束时间</th>
                                    <th width="30">状态</th>
                                    <th width="100">操作</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($list)) {
                                    foreach ($list as $k => $item) {
                                        ?>
                                        <tr class="odd gradeX">
                                            <td><input type="checkbox" class="checkboxes" value="<?php echo $item->id; ?>"/></td>
                                            <td><?php echo $item->id; ?></td>
                                            <td><?php echo $item->title; ?></td>
                                            <td><?php echo $item->start_day; ?></td>
                                            <td><?php echo $item->start_time; ?></td>
                                            <td><?php echo $item->end_time; ?></td>
                                            <td class="center"><span class="label label-success"><?php echo $item->status; ?></span></td>
                                            <td class="">
                                                <a href="#" class="label label-success"><i class="icon-pencil icon-white"></i> 编辑</a>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                                ?>
                                </tbody>
                        </table>
                            <div class="row-fluid"><div class="span6"><div class="dataTables_info" id="sample_1_info">Showing 1 to 10 of 25 entries</div></div><div class="span6"><div class="dataTables_paginate paging_bootstrap pagination"><ul><li class="prev disabled"><a href="#">← Prev</a></li><li class="active"><a href="#">1</a></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li class="next"><a href="#">Next → </a></li></ul></div></div></div>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE widget-->
                </div>
            </div>

            <!-- END ADVANCED TABLE widget-->

            <!-- END PAGE CONTENT-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->
   </div>
   <!-- END CONTAINER -->

   <!-- BEGIN JAVASCRIPTS -->
   <!-- Load javascripts at bottom, this will reduce page load time -->
   <script src="/admin_style/adminlab/js/jquery-1.8.3.min.js"></script>
   <script src="/admin_style/adminlab/assets/bootstrap/js/bootstrap.min.js"></script>
   <script src="/admin_style/adminlab/js/jquery.blockui.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="/admin_style/adminlab/js/excanvas.js"></script>
   <script src="/admin_style/adminlab/js/respond.js"></script>
   <![endif]-->   
   <!--<script type="text/javascript" src="/admin_style/adminlab/assets/uniform/jquery.uniform.min.js"></script>-->
   <!--<script type="text/javascript" src="/admin_style/adminlab/assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="/admin_style/adminlab/assets/data-tables/DT_bootstrap.js"></script>-->
   <script src="/admin_style/adminlab/js/scripts.js"></script>
   <script>
      jQuery(document).ready(function() {       
         // initiate layout and plugins
         App.init();
      });
   </script>
</body>
<!-- END BODY -->
</html>
