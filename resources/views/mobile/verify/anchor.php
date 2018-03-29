<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title>主持人绑定</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="/admin_style/adminlab/css/style_default.css" rel="stylesheet" id="style_color" />
    <script type="text/javascript" src="/admin_style/js/jquery-1.11.1.min.js"></script>
    <style type="text/css">
        *{ margin: 0px; padding: 0px;}
        .cl{ width: 100%; float: left; clear: both;}
        .cl2{ width: 92%; float: left; clear: both; margin-left: 4%; margin-right: 4%;}
        body{ background:#eeeff3 !important; font-size: 14px; font-family: "微软雅黑"; width: 100%; max-width: 750px; margin: 0px auto; clear: both; overflow: hidden;}
        .verify_top{ width: 100%; float: left; clear: both; padding-bottom: 15px; background: #4cb131; text-align: center; line-height: 22px; color: #FFFFFF;}
        .verify_top p{ float: left; width: 100%; clear: both;}
        .verify_top p.logo{ margin-top: 20px; background:#4cb131;}
        .verify_top p.logo img{ width: 85px; height: 85px; background:#4cb131;}
        .verify_top p.logo_tt{ margin-bottom: 20px;}

        .bind_tip{ margin-top: 30px; font-size: 12px; margin-bottom: 4px;}
        .bind_tip2{ font-size: 12px;}
        .bind_tip2 img{ float: left; margin-top: 0px; height: 12px; width: 12px;}
        .confirm_p{ margin-top: 30px;}
        .confirm_p a{ width: 100%; float: left; clear: both; background: #03bd00; border: none; font-size: 16px; text-decoration: none; font-size: 14px; border-radius: 3px; height:45px; line-height:45px; color: #FFFFFF; text-align: center;}
    </style>
</head>
<body>
    <div class="verify_top">
        <p class="logo"><img src="/admin_style/img/huishi_logo.png"></p>
        <p class="logo_tt">惠氏微课堂</p>
        <p class="title"><?php echo $course_info->title; ?></p>
        <p class="time"><?php echo $course_info->start_day; ?> <?php echo substr($course_info->start_time,0,5); ?>-<?php echo substr($course_info->end_time,0,5); ?></p>
        <p class="jiangshi">主持人：Miss惠</p>
    </div>
    <div class="cl2 bind_tip">绑定后，将获得以下权限</div>
    <div class="cl2 bind_tip2"><img src="/admin_style/img/checkbox_icon.png">以主持人身份与用户互动交流</div>
    <div class="cl2 confirm_p"><a href="?confirm=yes">确认绑定</a> </div>
<script type="text/javascript">
    $(document).ready(function(){
        var ww = $(".verify_top").width();
        //$(".verify_top").css('height',(ww*0.6)+'px');
    });
</script>
</body>
</html>