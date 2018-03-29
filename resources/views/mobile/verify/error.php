<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title>提示信息</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="/admin_style/adminlab/css/style_default.css" rel="stylesheet" id="style_color" />
    <script type="text/javascript" src="/admin_style/js/jquery-1.11.1.min.js"></script>
    <style type="text/css">
        *{ margin: 0px; padding: 0px;}
        .cl{ width: 100%; float: left; clear: both;}
        .cl2{ width: 96%; float: left; clear: both; margin-left: 2%; margin-right: 2%;}
        body{ background:#eeeff3 !important; font-size: 14px; font-family: "微软雅黑"; width: 100%; max-width: 750px; margin: 0px auto; clear: both; overflow: hidden;}
        .close_btn{ float:left; margin-left:3%; width:94%; clear:both; margin-top:20px; background: #03bd00; border: none; text-decoration: none; font-size: 14px; border-radius: 3px; height:45px; line-height:45px; color: #FFFFFF; text-align: center;}
        .close_btn:hover,.close_btn:active{ color:#FFF;}
        </style>
</head>
<body>
    <center style="line-height:150px;"><?php echo $msg; ?></center>
    <?php if($url == 'close'){ ?>
    <a class="close_btn" href="javascript:;">关闭</a>
    <?php } ?>
    <?php if(!empty($url) && $url != 'close'){ ?>
<script type="text/javascript">
    setTimeout("window.location.href='<?php echo $url; ?>'",2000);
</script>
    <?php } ?>
<script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="{{$su}}/js/lodash.js"></script>
<script src="{{config('course.static_url')}}/js/weixin1.js?v={{$rv}}"></script>
<script type="text/javascript">
    var wxOptions = {
        debug: false,
        reqUrl: document.URL,
        jsApiList: [
            'hideOptionMenu', 'closeWindow'
        ]
    };
    WeiXinSDK.init(wxOptions);
    $('.close_btn').click(function(){
        wx.closeWindow();
    });
</script>
</body>
</html>
