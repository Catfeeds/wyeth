<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>妈妈微课堂</title>
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache" content="no-cache">
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/signin/css/common.css?v={{$resource_version}}">
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/signin/css/yi.min.css?v={{$resource_version}}">
    <style>
        #__bs_notify__ {
            display: none !important;
        }
    </style>
    <!--移动端版本兼容 -->
    <script type="text/javascript" src="{{config('course.static_url')}}/mobile/signin/js/pxrem.js"></script>
    <!--移动端版本兼容 end -->
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
<div class="sigin-timebar" style="font-size: 0.55rem;">
    看 直 播 赢 奶 粉
</div>
<div class="task-block done">
    <div class="cicon icon-uberbar">
        <div class="userpic">
            <img src="{{$userInfo->avatar}}">
        </div>
        <h3>{{$userInfo->nickname}}</h3>
        <p class="invite">邀请{{$sign_info->signin_num}}位好友</p>
        <div class="rank">
            <h2 class="big">第<span class="number">999</span>名</h2>
            最终成绩
        </div>
    </div>
    <div class="link3">
        <img src="{{isset($signinGameConfig->prize_img) && !empty($signinGameConfig->prize_img) ? $signinGameConfig->prize_img : config('course.static_url').'/mobile/signin/images/link3.png'}}">
    </div>
    <?php if ($showAwardBtn == 1) { ?>
    <div class="footer-btn">
        <a class="link" href="/mobile/game/signin/user/{{$sign_info->id}}">
            <img src="{{isset($signinGameConfig->award_img) && !empty($signinGameConfig->award_img) ? $signinGameConfig->award_img : config('course.static_url').'/mobile/signin/images/receive.png'}}">
        </a>

    </div>
    <?php } ?>
</div>
<script src="{{config('course.static_url')}}/mobile/signin/js/libs/zepto/zepto.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/libs/seajs/3.0.0/sea.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/config.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/common.js"></script>
<script>
    $(function () {
        getMyOrder();
        shareSign();
        _hmt.push(['_trackEvent', '任务完成页面-进入完成页', '进入完成页']);
    });
    //获取当前游戏排名
    function getMyOrder() {
        var signId = '{{$sign_info->id}}';
        $.ajax({
            url: '/mobile/game/signin/getSignOrder?signId=' + signId,
            type: 'GET',
            dataType: 'json',
            data: {},
            success: function (data) {
                $('.number').html(data.data.order);
            }
        })
    }

    //游戏分享
    function shareSign() {
        var app_id = 'wx1453c24798e5e42e';
        var req_url = encodeURIComponent(window.location.href);
        var jsApiList = ['checkJsApi', 'onMenuShareTimeline', 'onMenuShareAppMessage'];
        var timestamp = parseInt(new Date().getTime() / 1000);

        $.ajax({
            type: "GET",
            async: false,
            cache: false,
            url: "http://v2.shheywow.com/api/v1/weixin/jsticket/get_config?appid=" + app_id + "&url=" + req_url + "&t=" + timestamp + "&jsapilist=" + jsApiList.toString(),
            dataType: "jsonp",
            jsonp: "callback",
            jsonpCallback: "getJsApiTicket",
            success: function (ret) {
                var config;
                if (ret.errCode == 0) {
                    config = ret.data;
                }
                config.debug = false;
                wx.config(config);
                wx.error(function (res) {
                    window.console.log(res);
                });
                //微信分享成功后记录到自已的数据库
                wx.ready(function () {
                    // 分享朋友圈的数据
                    wx.onMenuShareTimeline({
                        title: '{{isset($signinGameConfig->fri_circle_share_title) && !empty($signinGameConfig->fri_circle_share_title) ? $signinGameConfig->fri_circle_share_title : '我正在听妈妈微课堂直播，一起来签到，马上赢奶粉！'}}', // 分享标题
                        link: '{{config('app.url')}}/mobile/game/signin/jump/{{$sign_info->id}}', // 分享链接
                        imgUrl: '{{isset($signinGameConfig->share_img) && !empty($signinGameConfig->share_img) ? $signinGameConfig->share_img : config('course.static_url').'/mobile/signin/images/share.jpg'}}', // 分享图标
                        success: function () {
                            _hmt.push(['_trackEvent', '任务完成页面-分享到朋友圈成功', '分享到朋友圈']);
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                            _hmt.push(['_trackEvent', '任务完成页面-取消分享到朋友圈', '取消分享到朋友圈']);
                        }
                    });

                    // 分享给好友的数据
                    wx.onMenuShareAppMessage({
                        title: '{{isset($signinGameConfig->fri_share_title) && !empty($signinGameConfig->fri_share_title) ? $signinGameConfig->fri_share_title : '你懒你先睡，我美我拿奖！'}}', // 分享标题
                        desc: '{{isset($signinGameConfig->fri_share_desc) && !empty($signinGameConfig->fri_share_desc) ? $signinGameConfig->fri_share_desc : '我正在听妈妈微课堂直播，一起来签到，马上赢奶粉！'}}', // 分享描述
                        link: '{{config('app.url')}}/mobile/game/signin/jump/{{$sign_info->id}}', // 分享链接
                        imgUrl: '{{isset($signinGameConfig->share_img) && !empty($signinGameConfig->share_img) ? $signinGameConfig->share_img : config('course.static_url').'/mobile/signin/images/share.jpg'}}', // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () {
                            _hmt.push(['_trackEvent', '任务完成页面-分享给好友成功', '分享给好友']);
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                            _hmt.push(['_trackEvent', '任务完成页面-取消分享给好友', '取消分享给好友']);
                        }
                    });

                });
            }
        })
    }
</script>
<script>
    var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?48d0daf26b11c052fb2a98dcb072f1bc";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
@include('mobile.signingame.footer')
</body>
</html>
