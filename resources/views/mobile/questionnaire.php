<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
        <meta name="format-detection" content="telephone=no" />
        @include('public.head')
    </head>
    <body topmargin="0" leftmargin="0">
        <iframe src="http://www.sojump.com/jq/6667978.aspx"
            id="iframepage"
            style="height:100%;visibility:inherit; width:100%;z-index:1;" frameborder="0" scrolling="yes">
        </iframe>

        <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/jquery-1.8.3.js"></script>
        <script >

        $(document).ready(function(){
            "use strict";
            var json_config = <?php echo json_encode($package); ?>;
            wx.config({
                debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: json_config.appId, // 必填，企业号的唯一标识，此处填写企业号corpid
                timestamp: json_config.timestamp, // 必填，生成签名的时间戳
                nonceStr: json_config.nonceStr,  // 必填，生成签名的随机串
                signature: json_config.signature, // 必填，签名，见附录1
                jsApiList: [
                    'checkJsApi',
                    'hideOptionMenu',
                ]
            });
            wx.ready(function(){
                wx.hideOptionMenu();
            });
        });

        </script>
    </body>
@include('public.statistics')
</html>