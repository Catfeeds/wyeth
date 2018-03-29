<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    @include('public.head')
    <link rel="stylesheet"
          href="{{config('course.static_url')}}/mobile/boardcast/css/boardcast.css?v={{$resource_version}}"/>

    <!--游戏css-->
    <link rel="stylesheet"
          href="{{config('course.static_url')}}/mobile/signin/rank/css/common.css?v={{$resource_version}}">
    <link rel="stylesheet"
          href="{{config('course.static_url')}}/mobile/signin/rank/css/yi.min.css?v={{$resource_version}}">
    <link rel="stylesheet" type="text/css"
          href="{{config('course.static_url')}}/mobile/signin/rank/css/index.css?v={{$resource_version}}"/>
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/signin/css/sweetalert.css">
    <title>妈妈微课堂</title>
    <script type="text/javascript">
        var phoneWidth = parseInt(window.screen.width);
        var phoneScale = phoneWidth / 640;
        var ua = navigator.userAgent;
        if (/Android (\d+\.\d+)/.test(ua)) {
            var version = parseFloat(RegExp.$1);
            // andriod 2.3
            if (version > 2.3) {
                document.write('<meta name="viewport" content="width=640, minimum-scale = ' + phoneScale + ', maximum-scale = ' + phoneScale + ', target-densitydpi=device-dpi">');
                // andriod 2.3以上
            } else {
                document.write('<meta name="viewport" content="width=640, target-densitydpi=device-dpi">');
            }
            // 其他系统
        } else {
            document.write('<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">');
        }
        ;
        (function (win) {
            var doc = win.document;
            var docEl = doc.documentElement;
            var tid;

            function refreshRem() {
                var width = docEl.getBoundingClientRect().width;
                if (width > 640) { // 最大宽度
                    width = 640;
                }
                var rem = width / 10; // 将屏幕宽度分成10份， 1份为1rem
                docEl.style.fontSize = rem + 'px';
            }

            win.addEventListener('resize', function () {
                clearTimeout(tid);
                tid = setTimeout(refreshRem, 300);
            }, false);
            win.addEventListener('pageshow', function (e) {
                if (e.persisted) {
                    clearTimeout(tid);
                    tid = setTimeout(refreshRem, 300);
                }
            }, false);

            refreshRem();

        })(window);
    </script>
</head>
<body>
<!-- page页面内容 -->
<input type="hidden" id="userStatusBaiduStatistics" value="{{$userStatusBaiduStatistics}}">
<input type="hidden" id="isShareLink" value="{{$show_sign}}">
@if ($course['type'] == 'recorded')
    @if ($course['id'] =='392')
        <audio id="audio" class="hide" controls="controls">
            <source src="{{config('app.url').'/mp3/329.mp3'}}" type="audio/mpeg"> 您的浏览器不支持音频标签!
        </audio>
        
    @else
        <audio id="audio" class="hide" controls="controls">
            <source src="{{$course['audio']}}" type="audio/mpeg"> 您的浏览器不支持音频标签。
        </audio>     
    @endif
    <!--<audio id="audio" class="hide" controls="controls">
        <source src="{{$course['audio']}}" type="audio/mpeg"> 您的浏览器不支持音频标签。
    </audio>-->
@endif
<section class='p-ct'>
    <div class='translate-back f-hide'>
        <!-- index -->
        <div class='m-page page-index f-hide' data-page-type='info_pic3' data-statics='info_pic3'>
            <div class="page-con j-txtWrap lazy-img"
                 data-src="{{config('course.static_url')}}/mobile/boardcast/img/bg.jpg">
                <div class="top-state" data-src="{{config('course.static_url')}}/mobile/boardcast/img/index-sprite.png">
                    <span class="state-text" liveStatus="false" id="liveStatus">直播倒计时</span>
                    <span class="timer minute-decade">0</span>
                    <span class="timer minute-units">0</span>
                    <span class="timer second-decade">0</span>
                    <span class="timer second-units">0</span>
                    <div class="online-number"><span>0</span>人</div>
                    <!-- 隐藏的直播测试按钮 -->
                    @if ($course['type'] == 'recorded')
                        <div class="live-test" style="/*border:1px solid #F00;*/ width:10%; height:50%; margin-left:10%; margin-top:3%; float: left;"></div>
                    @endif
                    <div class="qa-test" style="/*border:1px solid #F00;*/ width:10%; height:50%; margin-right:17%; margin-top:3%; float: right;" ></div>
                </div>
                <div class="banner">
                    <div class="scroll-box" id="scroll-img">
                        <ul class="scroll-wrap">
                            @foreach ($coursewares as $courseware)
                                <li><a><img src="{{$courseware['img']}}" width="100%" height="100%"/></a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="index-sprite arrow-left"></div>
                    <div class="index-sprite arrow-right"></div>
                    <!-- <div class="index-sprite arrow-full"></div>  -->
                </div>
                <div class="lecturer">
                    <img src="{{$course['teacher_avatar']}}" class="head"/>
                    <span class="name">{{$course['teacher_name']}}</span>
                    <span class="description">{{$course['teacher_position']}}</span>
                    <span class="hospital">{{$course['teacher_hospital']}}</span>
                    <!-- 奥点云和腾讯云切换 -->
                    @if($course['isSwitchAudioSource'] == 'yes')
                    <div class="switch" id="switch"></div>
                    @endif
                    <div class="flower">
                        <img src="{{config('course.static_url')}}/mobile/boardcast/img/flower.png"/>
                        <span class="flower-number" data-number="{{$course['flowers']}}">+{{$course['flowers']}}</span>
                    </div>
                    <span class="flower-tip">献花</span>
                </div>
                <div class="button-area">
                    <div class="index-sprite button-play"></div>
                    <!--<div class="index-sprite button-question"></div>-->
                    <a class="index-sprite button-question" href="http://hswd.e-shopwyeth.com/wyethwx_wap/html/question_add.php?id={{$course['id']}}">                
                    </a>
                    <div class="index-sprite button-message"></div>
                    <div class="index-sprite button-share"></div>
                </div>
                <div class="index-sprite tip-refresh f-hide"></div>
            </div>
        </div>
        <!-- end -->

        <!-- cover -->
        <div class="cover f-hide">
            @if ($flatingLayer['type'] == '1')
                <div class="cover-ad f-hide" id="cover-ad" cover-type="{{$flatingLayer['type']}}">
                    <img class="tip-gif" src=""/>
                    <span class="tip-text" id="tip-text">直播暂未开始，请留意直播倒计时</span>
                    <span class="tip-timer" id="tip-timer">跳过 5</span>
                    <span class="tip-play f-hide" id="tip-play">点此跳过广告</span>
                </div>
            @elseif ($flatingLayer['type'] == '2')
                <div class="cover-ad-video f-hide" id="cover-ad" cover-type="{{$flatingLayer['type']}}">
                    <div class="video">
                        <video id="video" width="90%" style="margin-left:5%;" webkit-playsinline="" controls="controls"
                               poster="{{$flatingLayer['cover']}}">
                            <source src="{{$flatingLayer['video']}}" type="video/mp4">
                        </video>
                        <p id="tip-text">直播暂未开始，请留意直播倒计时</p>
                        <p id="tip-timer">跳过 5</p>
                        <p class="f-hide" id="tip-play">点此跳过广告</p>
                        <p class="f-hide" id="tip-blank"></p>
                    </div>
                </div>
            @endif
        </div>
        <!-- end -->

        <!-- question -->
        <div class="page-question f-hide" data-src="images/question/question-sprite.png" id="page-question">
            <div class="question-sprite top-title">
                <div class="question-sprite close">

                </div>
            </div>
        </div>
        <!-- end -->

        <!-- message -->
        <div class="page-message f-hide" data-src="images/message/message-sprite.png" id="page-chat">
            <div class="message-sprite top-title">
                <div class="message-sprite close"></div>
            </div>
            <chat></chat>
        </div>
        <!-- end -->
        <?php if ($course['signin_status'] == 1) { ?>
        <div class="signBtn" id="signinShow" data="0"><img
                    src="{{config('course.static_url')}}/mobile/signin/images/door.png?v={{$resource_version}}"></div>
        <?php }?>
    </div>
</section>
<!-- 正文介绍 end-->

<!--pageLoading-->
<section class="u-pageLoading">
    <div class="loading-txt">加载中...</div>
</section>
<div id="face">
    <img src="{{config('course.static_url')}}/mobile/boardcast/img/face.png">
</div>

<!-- 课程结束时由主持人触发评价窗口 -->
<div id="estimation">
    <div class="hs_dialog">
        <div class="score">
            <a data-hw-value="0" href="javascript:;" data-text="非常差"></a>
            <a data-hw-value="1" href="javascript:;" data-text="不太好"></a>
            <a data-hw-value="2" href="javascript:;" data-text="一般，需要改进"></a>
            <a data-hw-value="3" href="javascript:;" data-text="很不错，仍可改进"></a>
            <a data-hw-value="4" href="javascript:;" data-text="非常棒"></a>
        </div>
        <h4>您的评价，会帮助我做的更好！</h4>
        <p id="scoretext" class="hide">还可以</p>
        <div class="hs_form hide">
            <form action="">
                <textarea placeholder="请留下您宝贵的意见和建议" class="hs_text"></textarea>
                <a class="hs_btn" href="javascript:;">提交</a>
            </form>
        </div>
        <div class="hs_finish hide">
            <p class="orange">您的帮助，会帮助我们做的更好！</p>
            <p id="userscore">--</p>
            <a href="/mobile/index?_hw_c=livingroom0602"><img src="{{$scorePicture}}" width="100%"/></a>
        </div>
    </div>
</div>

<!--直播小游戏-->
<div class="signShareMask" style="display:none">
    <div class="signMask cover"></div>
    <img src="{{config('course.static_url')}}/mobile/signin/images/showShare.png?v={{$resource_version}}">
</div>

<div class="modal transparent" id="pageRank" style="height: 100%;width:100%;z-index: 10;">
    <div class="rank-block" style="height: 100%;background-size: 100% 100%;">
        <div style="height: 78px;width:100%;" class="proBgTime">
            <div class="top-state" data-src="http://wyethcoursedev2.shheywow.com/mobile/boardcast/img/index-sprite.png">
                <span class="state-text" livestatus="true" id="liveStatus">直播中</span>
                <span class="timer minute-decade">5</span>
                <span class="timer minute-units">9</span>
                <span class="timer second-decade">4</span>
                <span class="timer second-units">3</span>
                <div class="online-number">361</div>
            </div>
        </div>
        <div class="cicon icon-rank1 tcenter">
            <div class="list" id="rankOrder">

            </div>
            <a class="cicon icon-btn-close1" style="margin-left: 3.96rem;">关闭</a>
        </div>
        {{--<div class="btn-area">
            <a class="cicon icon-btn-invite fr" id="checnkSign"></a>
            <a class="fl btnShowRule"><img src="{{isset($signinGameConfig->brand_img) && !empty($signinGameConfig->brand_img) ? $signinGameConfig->brand_img : config('course.static_url').'/mobile/signin/images/btn-link1_v1.png'}}"></a>
        </div>
        <div class="cicon icon-rank2 tcenter">
            <div class="list" id="friendList">

                <!--签到好友列表-->

            </div>
        </div>--}}
        <div class="proPicture">
            <li>
                <a href="javaScript:void(0);"><img src="{{isset($signinGameConfig->intro_img) && !empty($signinGameConfig->intro_img) ? $signinGameConfig->intro_img : config('course.static_url').'/mobile/signin/images/proPowder.png'}}" alt=""></a>
            </li>
            <li>
                <a href="javaScript:void(0);" id="checnkSign"><img src="{{config('course.static_url')}}/mobile/signin/images/proSignNew.png" alt=""></a>
            </li>
        </div>
        <div class="btn-rule btnShowRule" style="margin-top: 0.25rem;">查看游戏规则</div>
    </div>
</div>
<div id="pageGameRule" class="modal transparent">
    <img src="{{isset($signinGameConfig->rule_img) && !empty($signinGameConfig->rule_img) ? $signinGameConfig->rule_img : config('course.static_url').'/mobile/signin/images/game-rule_v2.png?v='.$resource_version}}" style="height:85%;">
    <a class="cicon icon-btn-close">关闭</a>
</div>
<!--脚本加载-->
<script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/jquery-2.1.4.min.js"></script>
<script src="{{config('course.static_url')}}/js/iscroll-probe.js"></script>
<script src="{{config('course.static_url')}}/js/lodash.js"></script>
<script src="{{config('course.static_url')}}/mobile/boardcast/js/plugins/hhSwipe.js"></script>
<script src="{{config('course.static_url')}}/mobile/boardcast/js/plugins/jquery-browser.js"></script>
<script src="{{config('course.static_url')}}/mobile/boardcast/js/plugins/jquery.qqFace.js"></script>
<script>
    var slider = Swipe(document.getElementById('scroll-img'), {
        width: 640,
        auto: 5000,
        continuous: true
    });
</script>
<!-- connect -->
<script type="text/javascript" src="{{config('course.static_url')}}/mobile/boardcast/js/jquery.tmpl.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/sweetalert.min.js"></script>
<script type="text/javascript" src="{{config('course.static_url')}}/js/buzz.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    var type = '{{$course['type']}}';
    if (type == 'recorded') {
        var myAudio = document.getElementById("audio");
        var startTime = {{$course['startTime']}};
    }
    var qcloudUrl = '{{$video['qcloudUrl'][2]}}';
</script>
<script type="text/javascript" src="{{config('course.static_url')}}/mobile/boardcast/js/boardcast.js?v={{$resource_version}}"></script>

<!-- signin start-->
<script>
    var slider = Swipe(document.getElementById('scroll-img'), {
        width: 640,
        auto: 5000,
        continuous: true
    });
//    $('#pageRank').css({'height': $(window).height() - 78, top: 78});
    $('#pageGameRule .icon-btn-close').on('click touchend', function (e) {
        e.preventDefault();
        $('#pageGameRule').removeClass('active');
    })
    $('#pageRank .icon-btn-close1').on('click touchend', function (e) {
        e.preventDefault();
        $('#signinShow').css('display', 'block');
        $('#pageRank').removeClass('active');
        var courseId = '{{$course['id']}}';
        var new_param = {};
        var old_param = {};
        old_param.share_url = '{{$video['appUrl']}}/mobile/living?cid={{$course['id']}}&from_openid={{$user['openid']}}';
        old_param.share_title = '{{$share['living_share_title']}}';
        old_param.share_img = '{{$share['living_share_picture']}}';
        old_param.share_desc = '{{$share['living_firend_subtitle']}}';
        signShare(2, cid, '', new_param, old_param);
    })
    $('.btnShowRule').on('click', function (e) {
        e.preventDefault();
        _hmt.push(['_trackEvent', '排行榜页面-查看游戏规则', '查看游戏规则']);
        $('#pageGameRule').addClass('active');
    })
    $('#signinShow').on('click', function () {
        $('#pageRank').addClass('active');
        $('#signinShow').css('display', 'none');
        $('#pageGameRule').addClass('active');
        //判断当前用户是否发起活动 没有则发起
        var courseId = '{{$course['id']}}';
        checkSign(courseId);
        _hmt.push(['_trackEvent', '排行榜页面-课程'+courseId+'唤起游戏', '游戏唤起按钮点击']);
    })
    $('#checnkSign').on('click', function () {
        _hmt.push(['_trackEvent', '排行榜页面-点击邀请好友', '点击邀请好友']);
        $('.signShareMask').css('display', 'block');

    });
    $('.signShareMask').on('click', function () {
        $('.signShareMask').css('display', 'none');
    })
    function checkSign(cid) {
        $.ajax({
            url: '/mobile/game/signin/checkSign',
            type: 'GET',
            dataType: 'json',
            data: {cid: cid},
            success: function (result) {
                var signId = result.data.signid;
                //获取最近签到好友
                getSignFriend(signId);
                //获取排行名
                //getSignOrder(signId);
                //获取排行榜
                getRank(signId);

                var new_param = {};
                var old_param = {};
                new_param.share_url = '{{$video['appUrl']}}/mobile/game/signin/jump/' + signId + '?from_openid={{$user['openid']}}';
                new_param.share_title = '{{isset($signinGameConfig->fri_share_title) && !empty($signinGameConfig->fri_share_title) ? $signinGameConfig->fri_share_title : '你懒你先睡,我美我拿奖!'}}';
                new_param.share_img = '{{isset($signinGameConfig->share_img) && !empty($signinGameConfig->share_img) ? $signinGameConfig->share_img : config('course.static_url').'/mobile/signin/images/share.jpg'}}';
                new_param.share_desc = '{{isset($signinGameConfig->fri_share_desc) && !empty($signinGameConfig->fri_share_desc) ? $signinGameConfig->fri_share_desc : '我正在听妈妈微课堂直播，一起来签到，马上赢奶粉'}}';
                new_param.fri_share_title = '{{isset($signinGameConfig->fri_circle_share_title) && !empty($signinGameConfig->fri_circle_share_title) ? $signinGameConfig->fri_circle_share_title : '我正在听妈妈微课堂直播，一起来签到，马上赢奶粉'}}';
                old_param.share_url = '{{$video['appUrl']}}/mobile/living?cid=' + cid + '&from_openid={{$user['openid']}}';
                old_param.share_title = '{{$share['living_share_title']}}';
                old_param.share_img = '{{$share['living_share_picture']}}';
                old_param.share_desc = '{{$share['living_firend_subtitle']}}';
                old_param.fri_share_title = '{{$share['living_firend_subtitle']}}';
                signShare(1, cid, signId, new_param, old_param);

            }
        })
    }
    function signShare(type, cid, signId, new_param, old_param) {
        var param = {};
        if (type == 1) {
            param = new_param;
        } else {
            param = old_param;
        }
        wx.ready(function () {
            wx.onMenuShareTimeline({
                title: param.fri_share_title, // 分享标题
                link: param.share_url, // 分享链接
                imgUrl: param.share_img, // 分享图标
                success: function () {
                    signShare(2, cid, signId, new_param, old_param);
                    $('.signShareMask').css('display', 'none');
                    _hmt.push(['_trackEvent', '排行榜页面-分享'+signId+'游戏到朋友圈', '分享到朋友圈']);
                },
                cancel: function () {
                    signShare(2, cid, signId, new_param, old_param);
                    $('.signShareMask').css('display', 'none');
                    _hmt.push(['_trackEvent', '排行榜页面-取消分享游戏到朋友圈', '取消分享到朋友圈']);
                }
            });
            wx.onMenuShareAppMessage({
                title: param.share_title, // 分享标题
                desc: param.share_desc, // 分享描述
                link: param.share_url, // 分享链接
                imgUrl: param.share_img, // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    signShare(2, cid, signId, new_param, old_param);
                    $('.signShareMask').css('display', 'none');
                    _hmt.push(['_trackEvent', '排行榜页面-分享'+signId+'游戏给好友', '分享给好友']);
                },
                cancel: function () {
                    signShare(2, cid, signId, new_param, old_param);
                    $('.signShareMask').css('display', 'none');
                    _hmt.push(['_trackEvent', '排行榜页面-取消分享游戏给好友', '取消分享给好友']);
                }
            });
        });
    }
    function getSignFriend(signId) {
        $.ajax({
            url: '/mobile/game/signin/getSignFriend',
            type: 'GET',
            dataType: 'json',
            data: {
                signId: signId,
                limit: 50
            },
            success: function (result) {
                var html = '';
                $.each(result, function (index, friend) {
                    html += ('<li>' + friend.nickname + '</li>');
                });
                $('#friendList').html('<ul>' + html + '</ul>');
            }
        })
    }
    function getSignOrder(signId) {
        $.ajax({
            url: '/mobile/game/signin/getSignOrder',
            type: 'GET',
            dataType: 'json',
            data: {
                signId: signId,
            },
            success: function (result) {

            }
        })
    }
    function getRank(signId) {
        $.ajax({
            url: '/mobile/game/signin/getSign',
            type: 'GET',
            dataType: 'json',
            data: {
                signId: signId,
            },
            success: function (result) {
                result = result.data;
                var html = '';
                $.each(result, function (index, order) {
                    html += ('<li>' +
                    '<span class="fr rank">' + order.order + '</span>' +
                    '<span class="fr invite">已邀请' + order.sign_num + '位好友</span>' +
                    '<span class="fl pic"><img src="' + order.avatar + '"></span>' +
                    '<span class="name">' + order.nickname + '</span>' +
                    '</li>');
                });
                $('#rankOrder').html('<ul>' + html + '</ul>');
            }
        })
    }
    //如果是分享出去的页面被游戏打开 则显示排行榜弹窗
    jQuery(document).ready(function ($) {
        var isShareLink = $('#isShareLink').val();
        var gameStartStatus = '{{$course['signin_status']}}';
        if (isShareLink == 1 && gameStartStatus == 1) {
            $('#signinShow').click();
        }
    });
</script>
<!-- signin end  -->
<script>
    var staticUrl = '{{config('course.static_url')}}';
    var cid = '{{$course['id']}}';
    var uid = '{{$user['uid']}}';
    var openid = '{{$user['openid']}}';
    var userType = '{{$user['user_type']}}';
    var token = '{{$user['token']}}';
    var countdownSeconds = '{{$course['countdownSeconds']}}'; //课程倒计时的秒数
    var chatDomain = '{{config('course.chat_domain')}}';
    var chatChannel = '{{ config('course.chat_channel') }}';

    $.ajaxSetup({
        beforeSend: function (xhr) {
            if (!token) {
                console.log('token empty before ajax send');
                return false;
            }
            xhr.setRequestHeader('Authorization', 'bearer ' + token);
        }
    });

    manager._init({
        //course
        course: {
            id: cid,
            openid: openid,
            countdownSeconds: countdownSeconds, //课程倒计时的秒数
            presentedFlowersNumbers: {{$course['flowers']}} //当前课花的数量
        },
        //user
        user: {
            id: uid,
            user_type: userType,
            token: token,
            openid: '{{$user['openid']}}',
        },
        //
        //replyNotifyStatus:,
        //package: '',
        //video
        video: {
            app_url: '{{$video['appUrl']}}',
            static_url: '{{$video['static_url']}}',
            start_day: '{{$course['start_day']}}',
            title: '',
            chat_domain: chatDomain,
            chat_channel: chatChannel, //curl转向不同的地址
            hlsUrl: '{{$video['hlsUrl']}}',
            isPublish: '{{$video['isPublish']}}',
        },
        attr: {
            //点击播放按钮浮层图片
            gifSrc: '{{$flatingLayer['img']}}',
        },
        share: {
            living_firend_title: '{{$share['living_firend_title']}}',
            living_firend_subtitle: '{{$share['living_firend_subtitle']}}',
            living_share_title: '{{$share['living_share_title']}}',
            living_share_picture: '{{$share['living_share_picture']}}',
            app_url: '<?php echo config('app.url'); ?>',
        },
        playStatus: '{{$course['playStatus']}}'
    });
</script>

<!-- app build js -->
<link href="{{config('course.static_url')}}/mobile/css/animate.min.css" rel="stylesheet">
<script src="{{config('course.static_url')}}/build/{{$assetConfig['mobile-living']['js']}}"></script>
<script src="{{config('course.static_url')}}/mobile/boardcast/js/main.js"></script>
<!-- baidu statistics -->
@include('public.statistics')
</body>
</html>
