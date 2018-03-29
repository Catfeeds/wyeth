<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="">

    <title>提示信息</title>

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

<body class="body-404">

<div class="container">

    <section class="error-wrapper">
        <h2><?php echo isset($msg)?$msg:''; ?></h2>
        <?php !isset($url) && $url = ''; ?>
        <?php if($url != 'no'){ ?>
            <p>正在跳转，请稍后...</p>
            <script type="text/javascript">
                <?php if(!empty($url)){ ?>
                setTimeout("location.href='<?php echo $url; ?>';", 3000);
                <?php }else{ ?>
                setTimeout("history.back();", 3000);
                <?php } ?>
            </script>
        <?php } ?>
    </section>

</div>

</body>
</html>
