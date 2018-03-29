<!DOCTYPE html>
<html lang="zh-CN">
<head>
<title>惠氏VIP特权购</title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>
*{margin:0;padding;0;}
*::-webkit-scrollbar{width:0px;}
:focus{outline:none;}
a{-webkit-tap-highlight-color:rgba(0,0,0,0);}
body{background-color: #faedda;}
html,body{width:100%;height:100%;}
body{background:#faedda url("{{config('course.static_url')}}/h5_assets/vipbuy/images/bg1.jpg") no-repeat;background-size: cover;}
.page{position:absolute;top:0;bottom:0;left:0;right:0;max-width:750px;margin:auto;background-repeat: no-repeat;background-position: center top;background-size: cover;}
.page img{display:block;width:100%;margin:auto;}
.page .btn{position:relative;}
.page .btn a{display:block;position:absolute;left:0;right:0;top:0;width:44%;height:65%;margin:auto;}
@media (max-height:540px) {
    .page .img{width:auto;height:88%;}
    .page .btn img{width:78%;}
}
</style>
<script type="text/javascript" src="http://js.miaozhen.com/wx.1.0.js"></script>
</head>
<body>
    <div class="page page1" id="page1">
        <img src="{{config('course.static_url')}}/h5_assets/vipbuy/images/page1.png" class="img">
        <div class="btn">
            <img src="{{config('course.static_url')}}/h5_assets/vipbuy/images/btn1.png">
            <a href="###" onClick="showdiv('page1','page2')"></a>
        </div>
    </div>
    <div class="page page2" id="page2" style="display:none;">
        <img src="{{config('course.static_url')}}/h5_assets/vipbuy/images/page2.png" class="img">
        <div class="btn">
            <img src="{{config('course.static_url')}}/h5_assets/vipbuy/images/btn2.png">
            <a href="###" onclick="_mz_wx_custom(2); setTimeout(function(){window.open('http://lemaihuiyuangou.com/leqee?_mz_utm_source=60014','_self');},500); return false;"></a>
        </div>
    </div>

<script>
_mwx=window._mwx||{};
_mwx.siteId=8000519;
_mwx.openId='{{ $openid }}';
_mwx.debug=true;//代码调试阶段，加入此代码，正式上线之后去掉该代码
_mz_wx_view(1);

function showdiv(now,next){

    var nowPage=document.getElementById(now);
    var nextPage=document.getElementById(next);

    nowPage.style.display="none";
    nextPage.style.display="block";
    _mz_wx_custom(1);
    _mz_wx_view(2);
    document.body.style.backgroundImage="url({{config('course.static_url')}}/h5_assets/vipbuy/images/bg2.jpg)";
}
</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{{$package['appId']}}', // 必填，企业号的唯一标识，此处填写企业号corpid
        timestamp: {{$package['timestamp']}}, // 必填，生成签名的时间戳
        nonceStr: '{{$package['nonceStr']}}', // 必填，生成签名的随机串
        signature: '{{$package['signature']}}',// 必填，签名，见附录1
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage'
        ]
    });

    var shareUrl = 'http://vip.e-shopwyeth.com/h5/vipbuy';
    wx.ready(function(){
        // 分享朋友圈的数据
        wx.onMenuShareTimeline({
            title: 'VIP专享福利，全场低至7折！', // 分享标题
            link: _mz_wx_shareUrl(shareUrl), // 分享链接
            imgUrl: '{{config('course.static_url')}}/h5_assets/vipbuy/images/share.jpeg', // 分享图标
            success:function() {
                _mz_wx_timeline();
            }
        });

        // 分享给好友的数据
        wx.onMenuShareAppMessage({
            title: 'VIP特权购', // 分享标题
            desc: 'VIP专享福利，全场低至7折！', // 分享描述
            link: _mz_wx_shareUrl(shareUrl), // 分享链接
            imgUrl: '{{config('course.static_url')}}/h5_assets/vipbuy/images/share.jpeg', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success:function() {
                _mz_wx_friend();
            }
        });
    });
</script>
</body>
</html>
