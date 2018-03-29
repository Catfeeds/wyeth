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
    <!--移动端版本兼容 end -->
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
<div class="sigin-timebar" style="font-size: 0.55rem;">
    邀 请 好 友 帮 忙 签 到 赢 奶 粉!
</div>
<div class="sigin-block">
    <div class="cicon icon-uberbar">
        <div class="userpic">
            <img src="{{$userInfo->avatar}}">
        </div>
        <h3>您的好友</h3>
        <p>{{$userInfo->nickname}}</p>
        <p class="invite" id="signin_num">已邀请{{$signInfo->signin_num}}位好友</p>
        <div class="rank">
            <h2 class="big">第<span class="number" id="number">999</span>名</h2>
            目前成绩
        </div>
    </div>


    <!--产品介绍-->
    <div class="proPct">
        <a href="javaScript:void(0);" style="width: 680px;height: 337px;">
            <img src="{{isset($signinGameConfig->intro_img) && !empty($signinGameConfig->intro_img) ? $signinGameConfig->intro_img : config('course.static_url').'/mobile/signin/images/proPowder.png'}}" alt="">
        </a>
    </div>
    <div class="cicon icon-btn-sigin tcenter <?php if ($checkStatus == 1) { echo 'active';}?>" id="toGign" data="{{$signInfo->id}}" status="{{$checkStatus}}">
        <div class="cicon icon-btn-sigin-ok"></div>
    </div>
    <!--讲师介绍-->
    <div class="cicon  tcenter new-siginbar">
        <div class="link2" style="width: 638px;height: 165px;">
            <!--好友参加-->
            <a href="/mobile/living?cid={{$signInfo->cid}}" class="toLiving" style="display: inline-block;margin-left: 8px;">
                <img src="{{isset($signinGameConfig->living_img) && !empty($signinGameConfig->living_img) ? $signinGameConfig->living_img : config('course.static_url').'/mobile/signin/images/proGime.png'}}">
            </a>
        </div>
    </div>

    <div class="btn-rule" id="btnShowRule">查看游戏规则</div>
</div>
<div id="pageRule" class="modal transparent " style="text-align: center;">
    <img src="{{isset($signinGameConfig->rule_img) && !empty($signinGameConfig->rule_img) ? $signinGameConfig->rule_img : config('course.static_url').'/mobile/signin/images/game-rule_v2.png?v='.$resource_version}}" style="    height: 85%;position: absolute;top: 50%;left: 50%;-webkit-transform: translate(-50%, -50%);transform: translate(-50%, -50%);">
    <a class="cicon icon-btn-close">关闭</a>
</div>
<div id="signSuccess" class="modal transparent" style="text-align: center;">
    <img src="{{config('course.static_url')}}/mobile/signin/images/success-sign.png">
    <a class="cicon icon-btn-close">关闭</a>
</div>
<script src="{{config('course.static_url')}}/mobile/signin/js/libs/zepto/zepto.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/libs/seajs/3.0.0/sea.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/config.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/common.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/sweetalert.min.js"></script>

<script>
    $('#pageRule .icon-btn-close').on('click touchend',function(e){
        e.preventDefault();
        $('#pageRule').removeClass('active');
    });
    $('#signSuccess .icon-btn-close').on('click touchend',function(e){
        e.preventDefault();
        $('#signSuccess').removeClass('active');
    });
    $('.toLiving').click(function(){
        _hmt.push(['_trackEvent', '签到页面-看直播赢奶粉','看直播赢奶粉']);
    })
    $('#btnShowRule').on('click touchend',function(e){
        e.preventDefault();
        _hmt.push(['_trackEvent', '签到页面-查看规则','查看规则']);
        $('#pageRule').addClass('active');
    });
    $('#toGign div').on('click',function(){
        var signId = $(this).parent().attr('data');
        var signStatus = $(this).parent().attr('status');
        if (signStatus == 1) {
            swal('您已签过到');
            return false;
        }
        $.ajax({
            url: '/mobile/game/signin/insert',
            type: 'GET',
            dataType: 'json',
            data: {signId: signId},
            success : function(data){
                if (data.status != 0) {
                    swal(data.error_msg);
                } else {
                    $('#toGign').addClass('active');
                    showSuccess('true');
                    _hmt.push(['_trackEvent', '签到页面-签到成功','签到成功']);
                    updataOrderAndSignNum('{{$signInfo->id}}');
                }
            },
            error : function (){

            }
        })

    });
    function updataOrderAndSignNum(signId){
        $.ajax({
            url: '/mobile/game/signin/getSignOrder',
            type: 'GET',
            dataType: 'json',
            data: {
                signId: signId,
            },
            success :function(result){
                if(result.status == 0) {
                    document.getElementById('number').innerHTML = result.data.order;
                    document.getElementById('signin_num').innerHTML = '已邀请'+result.data.signNum+'位好友';
                }
            }
        })
    }
    function getMyOrder(){
        var signId = '{{$signInfo->id}}';
        $.ajax({
            url: '/mobile/game/signin/getSignOrder?signId='+signId,
            type: 'GET',
            dataType: 'json',
            data: {},
            success : function(data){
                $('.number').html(data.data.order);
            },
            error : function (){

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
                        link: '{{config('app.url')}}/mobile/game/signin/jump/{{$signInfo->id}}', // 分享链接
                        imgUrl: '{{isset($signinGameConfig->share_img) && !empty($signinGameConfig->share_img) ? $signinGameConfig->share_img : config('course.static_url').'/mobile/signin/images/share.jpg'}}', // 分享图标
                        success: function () {
                            _hmt.push(['_trackEvent', '签到页面-分享到朋友圈成功', '分享到朋友圈']);
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                            _hmt.push(['_trackEvent', '签到页面-取消分享到朋友圈', '取消分享到朋友圈']);
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
                            _hmt.push(['_trackEvent', '签到页面-分享给好友成功', '分享给好友']);
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                            _hmt.push(['_trackEvent', '签到页面-取消分享给好友', '取消分享给好友']);
                        }
                    });

                });
            }
        })
    }
    function showSuccess(show) {
        if (show == 'true') {
            $('#signSuccess').addClass('active');
//            alert('asdfg');
        } else {
            $('#signSuccess').removeClass('active');
//            alert('asdfg111111111');
        }
    }
    $(function() {
        _hmt.push(['_trackEvent', '签到页面-进入签到页面','进入签到页面', '{{$signInfo->id}}']);
        getMyOrder();
        shareSign();
    });
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
