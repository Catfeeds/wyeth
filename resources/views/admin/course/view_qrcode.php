<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title>提示信息</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/css/style.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/css/style_responsive.css" rel="stylesheet" />
   <link href="/admin_style/adminlab/css/style_default.css" rel="stylesheet" id="style_color" />

   <link href="/admin_style/adminlab/assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="/admin_style/adminlab/assets/uniform/css/uniform.default.css" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top" style="background: #FFF !important; overflow: hidden;">
   <div id="container" class="row-fluid" style="margin-top: 0px; text-align: center;">
       <div class="space20"></div>
       <p><b>直播二维码</b></p>
       <p><img src="/admin/course/qr/<?=$id?>" style="width: 150px;"></p>
   </div>
   <!-- Load javascripts at bottom, this will reduce page load time -->
   <script src="/admin_style/adminlab/js/jquery-1.8.3.min.js"></script>
   <script src="/admin_style/adminlab/assets/bootstrap/js/bootstrap.min.js"></script>
   <script src="/admin_style/adminlab/js/jquery.blockui.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="/admin_style/adminlab/js/excanvas.js"></script>
   <script src="/admin_style/adminlab/js/respond.js"></script>
   <![endif]-->
   <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>