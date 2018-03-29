<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" />
<meta name="description" content="wyeth course" />
<meta name="format-detection" content="telephone=no">
@include('public.head')
<title>微课堂</title>
<link rel="stylesheet" href="{{config('course.static_url')}}/mobile/v2/css/common.css">
</head>
<body>
<div>
    <img style="margin:3rem auto 2rem;
                display: block;
                width:30%;" src="{{config('course.static_url')}}/mobile/img/review/review_ok.png"/>
    <p style="text-align: center;
              font-size: 1.2rem;
              font-weight: bold;
              color:black;">提交成功</p>
    <p style="margin: 1rem;
              text-align: center;
              font-size: 0.9rem;">我们将尽快安排专业医生来解答您的问题</p>
</div>
<a style="display: block;
          margin: 4rem auto;
          text-align: center;
          line-height: 2.4rem;
          height: 2.4rem;
          border-radius: 0.4rem;
          width: 80%;
          background: orange;
          color: white;" href="/mobile/index">返回妈妈微课堂</a>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/jquery-2.1.4.min.js"></script>

<script>
var timestamp = parseInt(new Date().getTime() / 1000);
var config = {};
var app_id = 'wx1453c24798e5e42e';
var req_url = encodeURIComponent(window.location.href);
var jsApiList = ['checkJsApi', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'hideMenuItems'];
$.ajax({
    type: "GET",
    async: false,
    cache: false,
    url: "http://v2.shheywow.com/api/v1/weixin/jsticket/get_config?appid=" + app_id + "&url=" + req_url + "&t=" + timestamp + "&jsapilist=" + jsApiList.toString(),
    dataType: "jsonp",
    jsonp: "callback",
    jsonpCallback: "getJsApiTicket",
    success: function(ret) {
        if (ret.errCode == 0) {
            config = ret.data;
        }
        config.debug = false;
        wx.config(config);
        wx.ready(function(){
            wx.hideMenuItems({
                menuList: [
                    'menuItem:share:appMessage',
                    'menuItem:share:timeline',
                    'menuItem:share:qq',
                    'menuItem:share:QZone',
                    'menuItem:share:weiboApp',
                    'menuItem:share:facebook'
                ] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
            });
        });
    }
});
</script>
@include('public.statistics')
</body>
</html>