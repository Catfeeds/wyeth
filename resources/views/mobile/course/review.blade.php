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
<div style="margin-bottom: 1rem;">
    <img src="{{config('course.static_url')}}/mobile/img/review/review_banner.jpg" width="100%" />
</div>
<form action="" method="post">
    <textarea style="font-size: 0.9rem" rows="6" placeholder="请详细描述你的问题..." name="question" id="question"></textarea>
    <label style="margin: -1rem 0 0 0.6rem;
                  display: block;
                  font-size: 0.8rem;
                  color: #8f8f94;">
        <input type="checkbox" value="1" name="allow_answer" id="allow_answer" checked/>
        我同意专业医生24小时内为我解答问题
    </label>
    <button style="display: block;
                   margin: 1.2rem auto;
                   width: 60%;
                   height: 2rem;
                   border-radius: 1rem;
                   border: 0;
                   background: orange;
                   color: white;
                   font-size: 1.2rem;" id="send">
        提交
    </button>
</form>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/jquery-2.1.4.min.js"></script>
<script>
$(function(){
    $('#send').click(function(){
        var question = $('#question').val();
        var allow_answer = $('#allow_answer').prop('checked');
        if (question.replace(/(^s*)|(s*$)/g, "").length == 0)
        {
            alert('问题描述不能为空');
            return false;
        }
        if (!allow_answer)
        {
            alert('您需要同意医生为你解答问题');
            return false;
        }
    });
});


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