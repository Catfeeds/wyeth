<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>妈妈微课堂</title>
    <meta name="description" content="" />
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache" content="no-cache">
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/signin/css/common.css?v={{$resource_version}}">
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/signin/css/yi.min.css?v={{$resource_version}}">
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/signin/css/sweetalert.css">
    <style>
        #__bs_notify__{display: none!important;}
    </style>
    <!--移动端版本兼容 -->
    <script type="text/javascript" src="{{config('course.static_url')}}/mobile/signin/js/pxrem.js"></script>
    <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/jquery-2.1.4.min.js"></script>
    <!--移动端版本兼容 end -->

    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

</head>
<body>
<div class="info-title">
    <div class="cicon"><img src="{{isset($signinGameConfig->user_info_title) && !empty($signinGameConfig->user_info_title) ? $signinGameConfig->user_info_title : config('course.static_url').'/mobile/signin/images/header.png'}}" alt=""></div>
</div>
<div class="form-area">
    <form id="userIndoFm">
        <div class="input-area">
            <div class="fl"><span class="cicon icon-user"></span>您的姓名</div>
            <input type="text" name="realname" maxlength="20" placeholder="请输入您的姓名">
        </div>
        <div class="input-area">
            <div class="fl"><span class="cicon icon-phone"></span>手机号码</div>
            <input type="tel" name="mobile" maxlength="11" placeholder="请输入您的手机号码">
        </div>
        <div class="input-area for-inline">
            <a class="fr getCodeBtn" data="0">获取验证码</a>
            <!-- <a class="fr disabled">60s</a> -->
            <input type="tel" name="code" maxlength="4" placeholder="请输入验证码" id="mobile">
        </div>
        <p class="tips"><span>*如您收不到短信验证，可编辑短信“KT”发送至</span> 106900292030，申请开通短信通知</p>
        <div class="input-area">
            <div class="fl"><span class="cicon icon-home"></span>收货地址</div>
            <input type="text" name="address" maxlength="200" placeholder="请输入您的收货地址">
        </div>
        <div class="input-area for-inline">
            <input type="text" class="fr getCode" name="description" style="width:80%;" maxlength="200">
            备&nbsp;&nbsp;&nbsp;&nbsp;注
        </div>
        <div class="center" style="padding-top:.3rem;">
            <a id="userInfoSub" class="cicon icon-btn-normal">提交信息</a>
        </div>
        <p class="warn">PS:检查一下信息是否正确，因自身原因未收到礼品，后果自负哦</p>
    </form>
</div>
<script src="{{config('course.static_url')}}/mobile/signin/js/libs/zepto/zepto.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/libs/seajs/3.0.0/sea.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/config.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/common.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/sweetalert.min.js"></script>
<script>
    $(function() {
        shareSign();
        $('#userInfoSub').on('click', function(){
            var realname = $('input[name=realname]').val();
            var mobile = $('input[name=mobile]').val();
            var code = $('input[name=code]').val();
            var address = $('input[name=address]').val();
            var description = $('input[name=description]').val();

            if (realname.length < 1) {
                swal('请填写用户姓名');
                return false;
            }
            if (mobile.length  < 1) {
                swal('请填写手机号');
                return false;
            }
            if (!(/^1[3|4|5|7|8]\d{9}$/.test(mobile))){
                swal('手机号不正确');
                return false;
            }
            if (code.length != 4) {
                swal('验证码有误');
                return false;
            }
            if (address.length < 1) {
                swal('收货地址不能为空');
                return false;
            }
            $.ajax({
                url: '/mobile/game/signin/userInsert',
                type: 'GET',
                dataType: 'json',
                data: {
                    signId:'{{$signId}}',
                    uName:realname,
                    uMobile:mobile,
                    uCode:code,
                    uAddr:address,
                    uDescription:description
                },
                success: function (data) {
                    console.info(data.status);
                    if (data.status == 0) {
                        _hmt.push(['_trackEvent', '提交用户信息页面-领奖成功','领奖信息提交成功']);
                        location.href = '/mobile/game/signin/user/submit/{{$signId}}';
                    } else if (data.status == 1) {
                        swal(data.error_msg);
                        return false;
                    } else if (data.status == 2){
                        swal(data.error_msg);
                        return false;
                    }
                }
            })
        });
        $('.getCodeBtn').on('click',function(){
            var mobile = $('input[name=mobile]').val();
            var clickStatus = $(".getCodeBtn").attr('data');
            if (clickStatus == 1) {
                return false;
            }
            if (mobile.length  < 1) {
                swal('请填写手机号');
                return false;
            }
            if (!(/^1[3|4|5|7|8]\d{9}$/.test(mobile))){
                swal('手机号不正确');
                return false;
            }
            //发送验证码
            $.ajax({
                url: '/mobile/game/signin/sendCode',
                type: 'GET',
                dataType: 'json',
                data: {mobile: mobile},
                success : function(data){
                    if (data.status != 0) {
                        // 验证码发送成功
                        swal("验证码发送成功");
                        $('.getCodeBtn').html("<span>60</span>秒");
                        $('.getCodeBtn').attr('data', '1');
                        countdown();
                        _hmt.push(['_trackEvent', '提交用户信息页面-获取验证码成功','获取短信验证码']);

                    } else {
                        _hmt.push(['_trackEvent', '提交用户信息页面-获取验证码失败','验证码发送失败']);
                        swal("验证码发送失败，请刷新重试");
                    }
                },
                error : function (){

                }
            })
        })
    })
    function countdown ()
    {

        timer=setInterval(
                function()
                {
                    var miao = $('.getCodeBtn span').html();
                    if (miao > 1) {
                        miao--;
                        $(".getCodeBtn span").html(miao);
                    } else {
                        $(".getCodeBtn").html('重新发送');
                        _hmt.push(['_trackEvent', '提交用户信息页面','验证码重新发送']);
                        $(".getCodeBtn").attr('data','0');
                        clearInterval(timer);
                    }
                },
                1000
        )
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
                        link: '{{config('app.url')}}/mobile/game/signin/jump/{{$signInfo->id}}', // 分享链接
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
                        link: '{{config('app.url')}}/mobile/game/signin/jump/{{$signInfo->id}}', // 分享链接
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
    (function() {
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?48d0daf26b11c052fb2a98dcb072f1bc";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
@include('mobile.signingame.footer')
</body>
</html>
