<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="/admin_style/flatlab/img/favicon.png">

    <title>主持人登陆</title>

    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/admin_style/flatlab/js/html5shiv.js"></script>
    <script src="/admin_style/flatlab/js/respond.min.js"></script>
    <![endif]-->
</head>

<body >

<div class="container">

    <img src="/admin_style/img/anchor_logo.png" style="margin: 40px auto 0; display: block; width: 300px;"/>
    <img src="/anchor/qr?<?=$params?>" style="margin: 20px auto; display: block; width: 300px;"/>
    <div class="label label-default" style="margin: 20px auto;
    display: block;
    width: 400px;
    padding: 20px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;">
        请使用微信扫描二维码，登录惠氏微课堂互动直播
    </div>
</div>

</body>
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script>
    var token = '<?=$token?>';
    var login = function (){
        $.get("/anchor/do_login", { token: token },
            function(data){
                if (data == 1){
                    location.href = "/anchor/index";
                } else if (data == 2) {
                    location.href = "/anchor/fail";
                }
                console.log(data);
            }
        );
    }
    setInterval('login()',5000);
</script>
</html>
