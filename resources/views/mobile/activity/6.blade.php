<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>魔栗孕育指南</title>
    <meta name="description" content="fenlibao"/>
    <meta name="format-detection" content="telephone=no">
    @include('public.head')
    <link rel="stylesheet" href="{{$su}}/mobile/v2/css/index.min.css?v={{$rv}}">
    <style>
        body.modal-open {
            position: fixed;
            width: 100%;
        }
        .main-body {
            display: -webkit-flex;  -webkit-flex-direction: column;  -webkit-align-items: center; -webkit-box-align: center;
            display: flex;  flex-direction: column;  align-items: center;
            background: url('{{$configs->bg}}') no-repeat center 0;
            height: 1734px;
        }

        .container-course {
            display: -webkit-flex;display: flex; -webkit-flex-direction: column; flex-direction: column; margin-top: 66px; position: relative;
        }

        .text-course-tips {
            font-size: 30px;  line-height: 30px;  color: #333333;
        }

        .container-voice {
            width: 650px;  height: 170px;  border-radius: 20px;  background-color: #fff; box-shadow: 2px 2px 4px #d4d4d4;  display: -webkit-flex; display: flex;  -webkit-flex-direction: row;  flex-direction: row;  -webkit-align-items: center;  -webkit-box-align: center;  align-items: center;
        }

        .btn-voice-play {
            width: 83px;  height: 83px;  margin-left: 25px;  margin-right: 28px;
        }
        .text-voice-title {
            color: #666;  font-size: 30px;  line-height: 30px;
        }
        .text-voice-teacher, .text-voice-time, .text-voice-current, .text-voice-divider {
            color: #999;  font-size: 24px;  line-height: 28px;
        }
        .container-voice-time {
            width: 490px;  display: -webkit-flex;  display: flex;  -webkit-justify-content: space-between;  justify-content: space-between; -webkit-box-flex: 1; flex: 1; -webkit-box-align: center; -webkit-align-items: center; align-items: center; margin-bottom: 5px;
        }

        .share-page {
            display: -webkit-flex; display: flex; -webkit-flex-direction: column;  flex-direction: column; -webkit-align-items: center; -webkit-box-align: center;  align-items: center;
        }
        .share_page_block1 {
            background: url('{{$su}}/mobile/images/activity/5/share_bg1.png') no-repeat center 0;  width: 100%;  height: 822px;
        }
        .share_page_block2 {
            background: url('{{$su}}/mobile/images/activity/5/share_bg2_v2.png') no-repeat center 0;  width: 100%;  height: 284px;
        }
        .share_page_block_none {
            background: url('{{$su}}/mobile/images/activity/2/share_bg2.png') repeat center 0;  width: 100%; -webkit-flex: 1;  flex: 1;
        }
        .share_page_block3 {
            background: url('{{$su}}/mobile/images/activity/2/share_bg3_v2.png') no-repeat center 0;  width: 100%;  height: 104px;
        }
        .share_unlock_btn {
            width: 176px; height: 176px; margin: 25px auto;
        }

        .pop-window {
            position: fixed; top: 0; left: 0;width: 100%; height: 100%; display: none;
        }
        .pop-window-bg {
            width: 100%; height: 100%; background-color: #000000; opacity: 0.7
        }
        .pop-window-step {
            opacity: 1; position: absolute; top: 14%; display: -webkit-flex; display: flex; -webkit-flex-direction: column; flex-direction: column; -webkit-align-items: center; -webkit-box-align: center; align-items: center;
        }
        .pop-window-step-tips {
            font-size: 36px;  color: #fff; line-height: 36px; margin-top: 10px;
        }
        .pop-window-rule {
            width: 584px; height: 865px; border-radius: 20px; margin-left: -287px; left: 50%; padding-left: 57px; opacity: 1; position: absolute; top: 111px;background: url("{{$su}}/mobile/images/activity/2/rule_bg_v2.png"); overflow-y: auto;
        }
        .pop-window-rule-close {
            width: 94px; height: 94px; position: absolute; top: 1016px; left: 50%; margin-left: -57px;
        }
        .text-rule-desc {
            color: #666;  font-size: 26px;  line-height: 38px;  display: block;  max-width: 480px;
        }
        .pop-window-register {
            width: 560px; height: 300px; position: absolute; top: 380px; left: 50%; margin-left: -280px; background: white; border-radius: 8px;
        }
        .text-register-title {
            text-align: center; height: 75px; line-height: 75px; background: linear-gradient(to right, #E8C35F, #DAB04E, #C79736); color: white; font-size: 34px; border-top-left-radius: 8px; border-top-right-radius: 8px;
        }
        .text-register-desc {
            text-align: center; height: 125px; line-height: 125px; color: #999; font-size: 30px; border-bottom: 1px #D2D3D5 solid;
        }
        .btn-cancel {
            width: 280px; height: 100px; line-height: 100px; text-align: center; color: #666; font-size: 34px; border-bottom-left-radius: 8px; border-right: 1px #D2D3D5 solid;
        }
        .btn-register {
            width: 560px; height: 100px; line-height: 100px; text-align: center; color: #AD7A00; font-size: 34px; border-bottom-right-radius: 8px;
        }
        .audio {
            display: none;
        }

        /*横条样式*/
        input[type=range] {
            -webkit-appearance: none;/*清除系统默认样式*/
            width: 490px;
            background: -webkit-linear-gradient(#e3a91c, #e3a91c) no-repeat, #ddd;/*设置左边颜色为#61bd12，右边颜色为#ddd*/
            background-size: 0 100%;/*设置左右宽度比例*/
            height: 6px;/*横条的高度*/
            border-radius: 3px;
        }
        /*拖动块的样式*/
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;/*清除系统默认样式*/
            height: 30px;/*拖动块高度*/
            width: 30px;/*拖动块宽度*/
            background: #fff;/*拖动块背景*/
            border-radius: 15px; /*外观设置为圆形*/
            border: solid 1px #ddd; /*设置边框*/
        }
    </style>
    <!--移动端版本兼容 -->
    <script type="text/javascript" src="{{$su}}/mobile/v2/js/pxrem.min.js"></script>
    <!--移动端版本兼容 end -->
    <script> var CIData = CIData || [];</script>
</head>
<body>

@if(!$share_str)
    <div class="main-body" id="#content-container">
        <span style="position: absolute; right: 29px; top: 21px; font-size: 26px; color: #D69A26; opacity: 0" onclick="openRule()">活动规则</span>

        <div style="-webkit-align-self: flex-start; align-self: flex-start; padding-bottom: 17px; width: 690px; margin: 350px 30px 0 52px;">
            <img src="{{$su}}/mobile/images/activity/2/icon_heart.png" style="width: 25px; height: 23px; display: inline">
            <span id="read_num" style="color: #EBA317; font-size: 26px;"></span>
        </div>
        @foreach($configs->courses as $index => $course)
            <div class="container-course" style="margin-top: @if($index == 0) 110px @elseif($index == 1) 145px @else 50px @endif">
                <div class="container-voice" style="border: #FAC13B 3px solid;">
                    <img src="{{$su}}/mobile/images/activity/2/icon_play.png" class="btn-voice-play" data-id="{{$index}}">
                    <div style="display: flex;flex-direction: column;padding: 20px 0 24px 0; height: 170px">
                        <span class="text-voice-title">{{$course->name}}</span>
                        <div class="container-voice-time">
                            <span class="text-voice-teacher">{{$course->doctor}}</span>
                            <span class="text-voice-time"></span>
                        </div>
                        <div style="position: relative; ">
                            <input id="myRange{{$index}}" type="range" value="0" max="103" min="0" step="1" class="play-progress" style="position: absolute;"/>
                        </div>
                    </div>
                </div>
                @if($invite_num <= 0 && $index > 0)
                <div style="width: 650px;height:170px;border-radius:20px;background-color: rgba(0,0,0,0.12); position: absolute; top: 0;">
                    <div style="width: 90px; height: 90px; border-radius: 45px; background: #FFA42F; opacity: 1; padding-top: 21px; margin: 40px 20px 20px;">
                        <img src="{{$su}}/mobile/images/activity/2/icon_lock.png" style="width: 34px; height: 44px; margin: auto">
                    </div>
                </div>
                @endif
                <audio id="audio{{$index}}" class="audio" controls="controls" preload="metadata">
                    <source src="{{$course->audio}}" type="audio/mpeg"> 您的浏览器不支持音频标签。
                </audio>
            </div>
        @endforeach

        @if($invite_num)
            @if($user->crm_status)
                <img src="{{$su}}/mobile/images/activity/2/btn_get_more_v2.png" style="width: 312px; height: 94px; margin-top: 203px" onclick="window.location.href = '/mobile/index?defaultPath=/all&forcejump=1'">
            @else
                <img src="{{$su}}/mobile/images/activity/2/btn_get_more_v2.png" style="width: 312px; height: 94px; margin-top: 203px" onclick="goRegister()">
            @endif
        @else
            <img src="{{$su}}/mobile/images/activity/2/btn_share.png" style="width: 332px; height: 89px; position: fixed; bottom: 40px; left: 50%; margin-left: -166px" onclick="openStep()">
        @endif
    </div>
@else
    <div class="share-page">
        <div class="share_page_block1"></div>
        <div class="share_page_block2">
            <img src="{{$share_qr}}" class="share_unlock_btn">
        </div>
        <div class="share_page_block_none"></div>
        <div class="share_page_block3"></div>
    </div>
@endif

{{--邀请卡弹窗--}}
<div class="pop-window" id="stepWindow">
    <div class="pop-window-bg" onclick="closeStep()">111</div>
    <canvas class="pop-window-step" id="canvas_step" width="750px" height="750px"></canvas>
    <div class="pop-window-step">
        <img style="width: 750px; height: 750px; opacity: 1; margin-bottom: 64px" id="imgShow">
        <span class="pop-window-step-tips">保存卡片邀请好友完成注册</span>
        <span class="pop-window-step-tips">解锁课程一起来赢声波戒指</span>
    </div>

</div>
{{--邀请卡弹窗end--}}

{{--规则弹窗--}}
<div class="pop-window" id="ruleWindow">
    <div class="pop-window-bg" onclick="closeRule()"></div>
    <div class="pop-window-rule">
        <div style="margin-top: 103px; height: 760px; overflow: scroll">
            <span class="text-rule-desc">1.魔栗孕育指南第1节课免费试听，第2/3节课需要邀请成功邀请1名好友注册惠氏妈妈俱乐部，即可解锁。</span>
            <span class="text-rule-desc">2.解锁当期全部课程后，转发活动页，可参与抽奖，奖品为惠氏妈妈俱乐部提供的马良行定制声波戒指。</span>
            <span class="text-rule-desc">3.任务解锁成功通知、中奖通知，将通过惠氏妈妈俱乐部模板消息另行通知，敬请持续关注惠氏妈妈俱乐部公众号。</span>
            <span class="text-rule-desc">4.活动开奖时间为2018年4月8日。</span>
            <span class="text-rule-desc">5.中奖通知发出后，请中奖者于3个工作日内填写地址和相关信息，未如期填写信息则视为自动放弃。</span>
            <span class="text-rule-desc">6.因奖品为定制首饰，需要20个工作日制作。实际奖品发放时间将于中奖通知发布时，同时公布，敬请关注。</span>
        </div>
    </div>
    <img src="{{$su}}/mobile/images/activity/2/rule_colse.png" class="pop-window-rule-close" onclick="closeRule()">
</div>
{{--规则弹窗end--}}

<div class="pop-window" id="registerWindow">
    <div class="pop-window-bg" onclick="closeRegister()"></div>
    <div class="pop-window-register">
        <div class="text-register-title">注册</div>
        <div class="text-register-desc">为了更好的体验，请您先注册</div>
        <div style="display: flex; flex-direction: row">
            {{--<div class="btn-cancel">取消</div>--}}
            <div class="btn-register" onclick="goRegister()">确定</div>
        </div>
    </div>
</div>

<div id="toast" style="width: 240px; height: 240px; position: fixed; background: url('{{$su}}/mobile/images/activity/2/icon_done.png'); top: 452px; left: 50%; margin-left: -120px; text-align: center; padding-top: 170px; color: #D69A26; font-size: 26px; display: none">解锁成功</div>

<script src="{{$su}}/js/jquery.min.js"></script>
<script src="{{$su}}/js/lodash.min.js"></script>
<script src="{{$su}}/mobile/v2/js/index.min.js"></script>
<script src="{{$su}}/js/jquery.qrcode.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="{{config('course.static_url')}}/js/weixin1.js?v={{$rv}}"></script>

<script>
    //    var scene_str = '';
    var step_texts = [
        '{{$su}}/mobile/images/activity/2/step_text_2.png',
        '{{$su}}/mobile/images/activity/2/step_text_3.png',
        '{{$su}}/mobile/images/activity/2/step_text_4.png'
    ];
    var step_ts = [
        '{{$su}}/mobile/images/activity/2/step_t_2.png',
        '{{$su}}/mobile/images/activity/2/step_t_3.png',
        '{{$su}}/mobile/images/activity/2/step_t_4.png'
    ];
    var step_w_h = [
        { w: 156, h: 48 },
        { w: 300, h: 47 },
        { w: 204, h: 47 }
    ];
    //    var randomNum = Math.floor(Math.random() * 4);
    {{--$.ajax({--}}
    {{--url: '/mobile/hd/qrcode?aid={{$aid}}&openid={{$user->openid}}&shareindex=' + randomNum,--}}
    {{--type: 'GET',--}}
    {{--success: function(result, status) {--}}
    //            if (result.ret == 1) {
    scene_str = '{{array_key_exists("scene_str", $qrInfo) ? $qrInfo["scene_str"] : ""}}';

            @if(!$share_str)

    var canvas = document.getElementById("canvas_step");
    var ctx = canvas.getContext("2d");

    var step_bg = new Image();
    step_bg.setAttribute('crossOrigin', 'anonymous');
    step_bg.src = '{{$su}}/mobile/images/activity/5/step_bg.png';
    step_bg.onload = function () {
        ctx.drawImage(step_bg, 0, 0, 750, 750);

        var avatar = new Image();
        var avatarSrc = '{{$user->avatar}}';
        avatar.src = avatarSrc.replace('http://thirdwx.qlogo.cn', 'http://mama-weiketang-wyeth.woaap.com/wxqlogo');
        avatar.onload = function () {
            var r = 78;
            var d = 2 * r;
            var cx = 128 + r;
            var cy = 119 + r;
            ctx.beginPath();
            ctx.arc(cx,cy,r + 2,0,Math.PI*2,false);
            ctx.strokeStyle="rgba(202,158,65,1)";
            ctx.stroke();

            ctx.save();
            ctx.arc(cx, cy, r, 0, 2 * Math.PI);
            ctx.clip();
            ctx.drawImage(avatar, 128, 119, d, d);
            ctx.restore();

            var qrcode = new Image;
            qrcode.src = '{{array_key_exists("url", $qrInfo) ? $qrInfo["url"] : ""}}'.replace('https://mp.weixin.qq.com', 'http://mama-weiketang-wyeth.woaap.com/mpweixin');
            qrcode.onload = function () {
                ctx.save();
                ctx.drawImage(qrcode, 146, 582, 130, 130);
                ctx.stroke();
                ctx.closePath();

                var text = new Image();
                text.setAttribute('crossOrigin', 'anonymous');
                text.src = step_texts['{{$random}}'];
                text.onload = function () {
                    ctx.drawImage(text, 291, 115, 414, 165);

                    var t = new Image();
                    t.setAttribute('crossOrigin', 'anonymous');
                    t.src = step_ts['{{$random}}'];
                    t.onload = function () {
                        ctx.drawImage(t, 327, 644, step_w_h['{{$random}}'].w, step_w_h['{{$random}}'].h);

                        var imgSrc = canvas.toDataURL("image/png");
                        canvas.style.display = "none";
                        var imgShow = document.getElementById('imgShow');
                        imgShow.setAttribute('src', imgSrc);
                    }
                };
            };
        };
    };

    $.ajax({
        url: "springSecretCount",
        type: "GET",
        success: function (result, status) {
            $("#read_num").text($.parseJSON(result).count);
        },
        error: function(xhr, status, error) {
            console.log(xhr, status, error);
        }
    });

    //出现遮罩层给body 加 modal-open，阻止滚动穿透
    var ModalHelper = (function(bodyCls) {
        var scrollTop;
        return {
            afterOpen: function() {
                scrollTop = document.scrollingElement.scrollTop;
                document.body.classList.add(bodyCls);
                document.body.style.top = -scrollTop + 'px';
            },
            beforeClose: function() {
                document.body.classList.remove(bodyCls);
                // scrollTop lost after set position:fixed, restore it back.
                document.scrollingElement.scrollTop = scrollTop;
            }
        };
    })('modal-open');

    function openRule() {
        $("#ruleWindow").show();
        $("body").css("overflow", "hidden");
        ModalHelper.afterOpen();
    }

    function closeRule() {
        $("#ruleWindow").hide();
        $("body").css("overflow", "auto");
        ModalHelper.beforeClose();
    }

    function openStep() {
        $("#stepWindow").show();
        $("body").css("overflow", "hidden");
        ModalHelper.afterOpen();
        CIData.push(['trackEvent', 'wyeth', 'get_invite_card_aid{{$aid}}', 'wyeth_channel', getQueryStringByName('_hw_c')]);
    }

    function closeStep() {
        $("#stepWindow").hide();
        $("body").css("overflow", "auto");
        ModalHelper.beforeClose();
    }

    function openRegister() {
        $("#registerWindow").show();
        $("body").css("overflow", "hidden");
        ModalHelper.afterOpen();
        CIData.push(['trackEvent', 'wyeth', 'get_invite_card_aid{{$aid}}', 'wyeth_channel', getQueryStringByName('_hw_c')]);
    }

    function closeRegister() {
        $("#registerWindow").hide();
        $("body").css("overflow", "auto");
        ModalHelper.beforeClose();
    }

    function goRegister() {
        window.location.href = '/mobile/registerCrm?redirect={{config("app.url")}}/mobile/index?defaultPath=/all&forcejump=1'
    }

    $(document).ready(function () {
        if ('{{$invite_num}}' > 0 && (!localStorage.springInviteNum || localStorage.springInviteNum != '{{$invite_num}}')) {
            localStorage.springInviteNum = '{{$invite_num}}';
            $("#toast").show();
            setTimeout(function () {
                $("#toast").hide();
            }, 2500);
        }
    });

    $(".btn-voice-play").click(function () {
        if ('{{$user->crm_status}}') {
            var buttons = $(".btn-voice-play");
            var progress = $(".play-progress");
            var index = $(this).attr('data-id');
            var invite_num = '{{$invite_num}}';
            if ('{{$aid}}' != 4 || index < 1 || invite_num > 1) {
                for (var i = 0; i < buttons.length; i++) {
                    var audio = $("#audio" + i);
                    if (i == index) {
                        if (audio[0].paused) {
                            audio[0].play();
                            eventTester('play', i);
                            $(this).attr('src', '{{ config('course.static_url') }}/mobile/images/activity/2/icon_pause.png');
    //                        $(progress[i]).show();
                        } else {
                            audio[0].pause();
                            $(this).attr('src', '{{ config('course.static_url') }}/mobile/images/activity/2/icon_play.png');
    //                        $(progress[i]).hide();
                        }
                    } else {
                        $(buttons[i]).attr('src', '{{ config('course.static_url') }}/mobile/images/activity/2/icon_play.png');
    //                    $(progress[i]).hide();
                        audio[0].pause();
                    }
                }
            }
        } else {
            openRegister()
        }
    });

    // 解决IOS系统无法播放问题
    function forceSafariPlayAudio() {
        for (var i = 0; i < 3; i++) {
            $('#audio' + i)[0].load();
        }
    }

    document.addEventListener("WeixinJSBridgeReady", forceSafariPlayAudio, false);

    /**
     * 音频事件监听
     **/
    function eventTester (e, index) {
        var audio = $('#audio' + index)[0];
        var time = $(".text-voice-time")[index];
        var progress = $(".play-progress")[index];
        var limit = audio.duration;
        var invite_num = '{{$invite_num}}';
        audio.addEventListener(e, function(){
            if (e == 'play') {
                if (index < 1 || invite_num > 0) {
                    if (audio.currentTime < limit) {
                        setTimeout("checkTime(" + index + ")", 1000);
                    } else {
                        audio.load();
                    }
                }
            } else if (e == 'loadedmetadata') {
                $(time).html(getDisplayTime(audio.duration));
                progress.max = audio.duration;
            }
        });
    }

    /**
     * 获取显示时间
     * @param time
     * @returns {string}
     */
    function getDisplayTime(time) {
        var m = Math.floor(time / 60);
        if (m < 10) {
            m = '0' + m;
        }
        var s = Math.floor(time % 60);
        if (s < 10) {
            s = '0' + s;
        }
        return m + ':' + s;
    }

    function checkTime(index) {
        var audio = $('#audio' + index)[0];
        var limit = Math.floor(audio.duration);
        $('.play-progress')[index].value = audio.currentTime;
        $($("input[type=range]")[index]).css('background-size', audio.currentTime / audio.duration * 100 + '% 100%');
        if (audio.paused) {
            return '';
        } else if (audio.currentTime < limit) {
            setTimeout("checkTime(" + index + ")", 1000);
        } else {
            audio.load();
            resetTime(index);
            return '';
        }
    }

    function resetTime(index) {
        $($('.current-progress')[index]).css('width', 0);
        $($(".btn-voice-play")[index]).attr('src', '{{ config('course.static_url') }}/mobile/images/activity/2/icon_play.png');
    }

    for (var count = 0; count < 3; count++) {
        eventTester('loadedmetadata', count)
    }

    @else

    if (window.innerHeight > 1200) {
        $(".share-page").css({
            'height': window.innerHeight
        })
    }

    @endif

    //根据QueryString参数名称获取值
    function getQueryStringByName(name){
        var result = location.search.match(new RegExp("[\?\&]" + name+ "=([^\&]+)","i"));
        if(result == null || result.length < 1){
            return "";
        }
        return result[1];
    }

    //    微信分享
    var appUrl = '{{config('app.url')}}';

    var shareUrl = appUrl + '/mobile/hd?aid={{$aid}}&share_str=1&scene_str=' + scene_str;
    var shareTitle = '{{$configs->share_title}}';
    var sharePicture = '{{$configs->share_pic}}';
    var shareDesc = '{{$configs->share_desc}}';

    var token;
    $.getJSON('/token', function (data) {
        if ('token' in data) {
            token = data.token;
        }
    });

    var wxOptions = {
        debug: false,
        reqUrl: document.URL,
        shareTimelineData: {
            title: shareTitle,
            link: shareUrl,
            imgUrl: sharePicture,
            success: function() {
                if (token) {
                    addShareLog();
                }
            }
        },
        shareAppData: {
            title: shareTitle,
            link: shareUrl,
            imgUrl: sharePicture,
            desc: shareDesc,
            success: function() {
                if (token) {
                    addShareLog()
                }
            }
        }
    };
    WeiXinSDK.init(wxOptions);

    function addShareLog() {
        $.ajax({
            url: '/mobile/hd/addShareLog',
            type: 'POST',
            data: {
                aid: '{{$aid}}',
                url: appUrl + '/mobile/hd?aid={{$aid}}',
                openid: '{{$user->openid}}'
            },
            success: function (result, status) {
//                alert('!!!');
//                $("#read_num").text('hahh');
            },
            error: function(xhr, status, error) {
                console.log(xhr, status, error);
            }
        })
    }
    //    微信分享end

    for (var i = 0; i < 3; i++) {
        addProgressListener(i)
    }

    function addProgressListener(i) {
        var range = document.getElementById('myRange' + i);
        if (range.addEventListener) {
            range.addEventListener("input", function () {
                console.log(range.value);
                var audio = $('#audio' + i)[0];
                audio.currentTime = range.value;
                $($("input[type=range]")[i]).css('background-size', audio.currentTime / audio.duration * 100 + '% 100%');
            });
            range.addEventListener("change", function () {
                console.log('change');
                eventTester('play', i);
            });
        } else {
            range.attachEvent("input", function () {
                console.log(range.value);
                var audio = $('#audio' + 0)[0];
                audio.currentTime = range.value;
                $($("input[type=range]")[0]).css('background-size', audio.currentTime / audio.duration * 100 + '% 100%');
            });
            range.attachEvent("change", function () {
                console.log('change');
                eventTester('play', 0);
            });
        }
    }

</script>
<!-- baidu statistics -->
@include('public.statistics')
<script>
    CIData.push(['trackEvent', 'wyeth', 'activity_{{$aid}}', 'wyeth_channel', getQueryStringByName('_hw_c')]);
</script>
</body>