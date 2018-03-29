<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <title>魔栗妈咪学院</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!--忽略将页面中的数字识别为电话号码-->
    <meta name="format-detection" content="telephone=no" />
    <!--忽略Android平台中对邮箱地址的识别-->
    <meta name="format-detection" content="email=no" />
    @include('public.head')
    <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/css/swiper.min.css?v={{$resource_version}}" />
    <link rel="stylesheet" href="{{ config('course.static_url') }}/assets/mobile/review/css/style.css?v={{$resource_version}}" />
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/signin/css/sweetalert.css">
    <style>
        .content {
            background-color: #fff;
        }
        .activity-title {
            font-size: 48px;  line-height: 72px;  margin-left: 39px;  margin-right: 27px;  height: 204px;  display: flex;  flex-direction: row;  align-items: center;  justify-content: space-between;
        }
        .detail {
            padding-left: 30px;  padding-right: 30px; font-size: 30px;
        }
        .audio {
            display: none;
        }
        .voice-container {
            background-color: #FCFCFC;  border-radius: 10px;  border: solid #e2e2e2 1px;  height: 164px;  display: flex;  align-items: center;  flex-direction: row;  margin-top: 70px;  margin-bottom: 65px;
        }
        .voice-play-button {
            width: 84px;  height: 84px;  margin-left: 30px;  margin-right: 30px;
        }
        .voice-info-c {
            display: flex;  align-items: center;  flex-direction: row;  width: 520px;  height: 50px;  line-height: 1;
        }
        .voice-title {
            font-size: 28px;  color: rgb(102, 102, 102);  line-height: 0;
        }
        .voice-teacher {
            font-size: 24px;  color: rgb(153, 153, 153);  flex: 1;  line-height: 0;
        }
        .voice-length {
            background-color: #a3a3a3; height: 3px; width: 490px
        }
        .voice-progress {
            background-color: #f0ad4e; height: 3px; width: 0
        }
        .voice-time {
            color: rgb(102, 102, 102); font-size: 22px; margin-right: 21px;
        }
        .activity-footer {
            line-height: 1.75em;  font-size: 24px; color: rgb(102,102,102);  margin: 40px 38px 140px;
        }
        .activity-teacher-container {
            display: flex; align-items: flex-start; flex-direction: column; padding-bottom: 61px; border: 2px solid rgb(226,226,226); margin-bottom: 30px; padding-left: 102px;
        }
        .activity-teacher-avatar {
            width: 116px; height: 116px; margin-right: 17px; border-radius: 58px;border: solid rgb(225,185,125) 2px;
        }
        .activity-teacher-name{
            line-height: 1em; font-size: 34px; color: rgb(51,51,51); margin-top: 10px;
        }
        .activity-teacher-from {
            line-height: 1em; margin-top: 16px; margin-bottom: 5px; color: rgb(102,102,102); font-size: 26px
        }
        .activity-teacher-position {
            line-height: 1em; color: rgb(102,102,102); font-size: 26px
        }
        .activity-alert-bg {
            position: fixed; top: 0; bottom: 0; left: 0; right: 0; display: flex; align-items: center; justify-content: center; background-color: #000000; opacity: 0.5
        }
        .activity-alert-content {
            width: 560px; height: 300px; background-color: #fff; opacity: 100; position: fixed; left: 85px; top: 50%; margin-top: -150px; border-radius: 8px
        }
        .activity-alert-header {
            line-height: 1rem; text-align: center; height: 75px; font-size: 34px; background: -webkit-linear-gradient(left, #e8c35f, #dab04e, #c79736); color: #fff; border-top-left-radius: 8px; border-top-right-radius: 8px
        }
        .activity-alert-message {
            text-align: center; height: 124px; color: #999; padding-top: 10px; border-bottom: solid #d2d3d5 1px; font-size: 30px; line-height: 0.6rem;
        }
        .activity-alert-confirm {
            text-align: center; height: 100px; font-size: 30px; color: #ad7a00
        }
    </style>
    <!--移动端版本兼容 -->
    <script type="text/javascript" src="{{$su}}/mobile/v2/js/pxrem.min.js"></script>
    <!--移动端版本兼容 end -->
</head>

<body style="background-color: #f5f6fe; margin-bottom: 0">
<div class="content">
    <img src="{!! $activityData['img'] !!}">
    <div class="content activity-title">
        <span style="width: 420px">{!! $activityData['title'] !!}</span>
        @if(!$is_listen)
        <div style="display: flex; flex-direction: column; align-items: center; margin-top: -50px">
            <img style="width: 150px; height: 150px" src="{!! $activityData['qrcode'] !!}">
            <span style="font-size: 20px; color: rgb(51,51,51); line-height: 34px">扫码参加活动</span>
        </div>
        @endif
    </div>
</div>

<div style="height: 85px; display: flex; flex-direction: row; align-items: center; padding-left: 35px; background-color: #fff; border-bottom: solid 1px #eee; margin-top: 15px">
    <img style="width: 31px; height: 31px; margin-right: 13px;" src="{{ config('course.static_url') }}/mobile/images/c_activity_detail.png">
    <span style="color: rgb(51,51,51); font-size: 30px; line-height: 0">专栏介绍</span>
</div>
<div class="content detail">
    <div>{!! array_key_exists('text1', $activityData) ? $activityData['text1'] : '' !!}</div>
    <span style="font-size: 30px; line-height: 60px; color: rgb(234,155,86); margin-left: 229px; margin-bottom: 30px">
        @if($is_listen)
            【领取锦囊】
        @else
            【免费试听】
        @endif
    </span>
    <div onclick="goToCourse(436)">{!! array_key_exists('text2', $activityData) ? $activityData['text2'] : '' !!}</div>
    <div class="voice-container">
        <img class="voice-play-button" id="play0" data-id="0" src="{{ config('course.static_url') }}/mobile/images/c_activity_play.png">
        <div style="line-height: 1rem">
            <span class="voice-title">三岁看到老，智慧养成要趁早</span>
            <div class="voice-length">
                <div class="voice-progress"></div>
            </div>
            <div class="voice-info-c">
                <span class="voice-teacher">余文-儿童保健科主任医师</span>
                <span class="voice-time voice-time-curr">0:00</span>
                <span class="voice-time voice-time-total">--:--</span>
            </div>
        </div>
        <audio id="audio0" class="audio" controls="controls">
            <source src="{!! array_key_exists('voice1', $activityData) ? $activityData['voice1'] : '' !!}" type="audio/mpeg"> 您的浏览器不支持音频标签。
        </audio>
    </div>

    <div onclick="goToCourse(496)">{!! array_key_exists('text3', $activityData) ? $activityData['text3'] : '' !!}</div>

    <div class="voice-container">
        <img class="voice-play-button" id="play1" data-id="1" src="{{ config('course.static_url') }}/mobile/images/c_activity_play.png">
        <div style="line-height: 1rem">
            <span class="voice-title">智慧“初动” 宝宝大脑发育第一步</span>
            <div class="voice-length">
                <div class="voice-progress"></div>
            </div>
            <div class="voice-info-c">
                <span class="voice-teacher">陈倩-妇产科主任医师</span>
                <span class="voice-time voice-time-curr">0:00</span>
                <span class="voice-time voice-time-total">--:--</span>
            </div>
        </div>
        <audio id="audio1" class="audio" controls="controls">
            <source src="{!! array_key_exists('voice2', $activityData) ? $activityData['voice2'] : '' !!}" type="audio/mpeg"> 您的浏览器不支持音频标签。
        </audio>
    </div>

    <div onclick="goToCourse(497)">{!! array_key_exists('text4', $activityData) ? $activityData['text4'] : '' !!}</div>

    <div class="voice-container">
        <img class="voice-play-button" id="play2" data-id="2" src="{{ config('course.static_url') }}/mobile/images/c_activity_play.png">
        <div style="line-height: 1rem">
            <span class="voice-title">营养补充一网打尽，打造聪明宝宝</span>
            <div class="voice-length">
                <div class="voice-progress"></div>
            </div>
            <div class="voice-info-c">
                <span class="voice-teacher">沈月华-新生儿科主任医师</span>
                <span class="voice-time voice-time-curr">0:00</span>
                <span class="voice-time voice-time-total">--:--</span>
            </div>
        </div>
        <audio id="audio2" class="audio" controls="controls">
            <source src="{!! array_key_exists('voice3', $activityData) ? $activityData['voice3'] : '' !!}" type="audio/mpeg"> 您的浏览器不支持音频标签。
        </audio>
    </div>

    <div onclick="goToCourse(445)">{!! array_key_exists('text5', $activityData) ? $activityData['text5'] : '' !!}</div>

    <div class="voice-container">
        <img class="voice-play-button" id="play3" data-id="3" src="{{ config('course.static_url') }}/mobile/images/c_activity_play.png">
        <div style="line-height: 1rem">
            <span class="voice-title">激发宝宝语言潜能，宝宝智力发育的捷径</span>
            <div class="voice-length">
                <div class="voice-progress"></div>
            </div>
            <div class="voice-info-c">
                <span class="voice-teacher">余文-儿童保健科主任医师</span>
                <span class="voice-time voice-time-curr">0:00</span>
                <span class="voice-time voice-time-total">--:--</span>
            </div>
        </div>
        <audio id="audio3" class="audio" controls="controls">
            <source src="{!! array_key_exists('voice4', $activityData) ? $activityData['voice4'] : '' !!}" type="audio/mpeg"> 您的浏览器不支持音频标签。
        </audio>
    </div>
    <div>{!! array_key_exists('text6', $activityData) ? $activityData['text6'] : '' !!}</div>
    <div class="activity-teacher-container">
        <div style="width: 493px; height: 4px; margin-top: -3px; background: #fff"></div>
        <div style="display: flex; flex-direction: row; margin-top: 77px">
            <img class="activity-teacher-avatar" src="http://wyeth-uploadsites.nibaguai.com/wyethcourse/course/teacher/b522affad115101d0fe7091e7c909dae.jpg">
            <div style="display: flex; flex-direction: column">
                <span class="activity-teacher-name">余文</span>
                <span class="activity-teacher-from" style="">上海交通大学国际和平妇幼保健院</span>
                <span class="activity-teacher-position" style="">儿童保健科主任医师</span>
            </div>
        </div>
        <div style="display: flex; flex-direction: row; margin-top: 77px">
            <img class="activity-teacher-avatar" src="http://wyeth-uploadsites.nibaguai.com/wyethcourse/default/2d43ea7c7f6053dd02b0a8c376ccbd36.png">
            <div style="display: flex; flex-direction: column">
                <span class="activity-teacher-name">陈倩</span>
                <span class="activity-teacher-from" style="">北京大学第一医院</span>
                <span class="activity-teacher-position" style="">妇产科主任医师</span>
            </div>
        </div>
        <div style="display: flex; flex-direction: row; margin-top: 77px">
            <img class="activity-teacher-avatar" src="http://7xk3aj.com1.z0.glb.clouddn.com/wyethcourse/25572958f8c93df9cd067d120870cc5f.jpg">
            <div style="display: flex; flex-direction: column">
                <span class="activity-teacher-name">沈月华</span>
                <span class="activity-teacher-from" style="">上海交通大学国际和平妇幼保健院</span>
                <span class="activity-teacher-position" style="">新生儿科主任医师</span>
            </div>
        </div>
    </div>
    <div>{!! array_key_exists('text7', $activityData) ? $activityData['text7'] : '' !!}</div>
</div>

<p class="activity-footer">
    “魔栗妈咪学院”版权归属育儿网所有，相关课程内容由育儿网提供。平台相关内容不作为医学诊断参考，如情况严重，建议及时就医。
</p>

@if($is_listen == 2)
<div id="alert_bg" class="activity-alert-bg">
</div>
<div id="alert_content" class="activity-alert-content">
    <div class="activity-alert-header">领取成功</div>
    <div class="activity-alert-message">恭喜你！<br>成功领取VIP智慧宝贝的聪明锦囊</div>
    <div class="activity-alert-confirm" onclick="alertConfirm()">确定</div>
</div>
@endif

@if(!$is_listen)
<div style="background-color: #fff; position: fixed; bottom: 0; height: 120px; width: 750px">
    <div style="width: 723px; height: 100px; text-align: center; border-radius: 10px; background-color: rgb(227, 128, 0); bottom: 20px; margin-left: 13px; color: #fff; font-size: 32px" onclick="location.href='http://e.cn.miaozhen.com/r/k=2070338&p=7CTWx&dx=__IPDX__&rt=2&ns=__IP__&ni=__IESID__&v=__LOC__&xa=__ADPLATFORM__&tr=__REQUESTID__&mo=__OS__&m0=__OPENUDID__&m0a=__DUID__&m1=__ANDROIDID1__&m1a=__ANDROIDID__&m2=__IMEI__&m4=__AAID__&m5=__IDFA__&m6=__MAC1__&m6a=__MAC__&vo=3b2c192fd&vr=2&o=http%3A%2F%2Fwyeth.woaap.com%2Fdev%2FDTC_member%2Findex%3Ftype%3Dweiketang'">立即参加</div>
</div>
@endif


<script src="{{config('course.static_url')}}/js/jquery.min.js"></script>

<script>
    function alertConfirm () {
        $("#alert_bg").hide();
        $("#alert_content").hide();
    }
    //统计自动下行的数据

    function goToCourse(cid) {
        if ('{!! $is_listen !!}}' != 0) {
//            location.href = '/mobile/end?cid=' + cid
        }
    }

    function GetQueryString(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            if(pair[0] == variable){return pair[1];}
        }
        return '';
    }

    function eventTester (e, index){
        var audio = $('#audio' + index)[0];
        var time = $(".voice-time-total")[index];
        var limit;
        @if($is_listen)
        limit = audio.duration;
        @else
        limit = 14;
        @endif
        audio.addEventListener(e, function(){
            console.log(e, index);
            if (e == 'play') {
                if (audio.currentTime < limit) {
                    setTimeout("checkTime(" + index + ")", 1000);
                } else {
                    resetTime(index)
                    audio.load();
                }
            } else if (e == 'loadedmetadata') {
                @if($is_listen)
                    $(time).html(getDisplayTime(audio.duration));
                @else
                    $(time).html(getDisplayTime(15))
                @endif
            }
        });
    }

    $(".voice-play-button").click(function () {
        var buttons = $(".voice-play-button");
        for (var i = 0; i < buttons.length; i++) {
            var audio = $("#audio" + i);
            if (i == $(this).attr('data-id')) {
                if (audio[0].paused) {
                    audio[0].play();
                    eventTester('play', i);
                    $(this).attr('src', '{{ config('course.static_url') }}/mobile/images/c_activity_pause.png')
                } else {
                    audio[0].pause();
                    $(this).attr('src', '{{ config('course.static_url') }}/mobile/images/c_activity_play.png')
                }
            } else {
                $(buttons[i]).attr('src', '{{ config('course.static_url') }}/mobile/images/c_activity_play.png')
                audio[0].pause();
            }
        }
    });

    function checkTime(index) {
        var audio = $('#audio' + index)[0];
        var limit;
        @if($is_listen)
            limit = audio.duration;
        @else
            limit = 14;
        @endif
        $($('.voice-progress')[index]).css('width', audio.currentTime / limit * 490);
        $($(".voice-time-curr")[index]).html(getDisplayTime(Math.ceil(audio.currentTime)));
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
        $($(".voice-time-curr")[index]).html('00:00');
        $($('.voice-progress')[index]).css('width', 0);
        $($('.voice-play-button')[index]).attr('src', '{{ config('course.static_url') }}/mobile/images/c_activity_play.png')
    }

    function getDisplayTime(time) {
        var m = Math.floor(time / 60);
        if (m < 10) {
            m = '0' + m;
        }
        var s = Math.floor(time % 60)
        if (s < 10) {
            s = '0' + s;
        }
        return m + ':' + s;
    }

    eventTester("loadedmetadata", 0);
    eventTester("loadedmetadata", 1);
    eventTester("loadedmetadata", 2);
    eventTester("loadedmetadata", 3);

</script>
<script src="{{$su}}/js/lodash.min.js"></script>
@include('mobile.share', ['share' =>
                                [
                                    'title' => '让宝宝聪慧过人的锦囊妙计，孕期就要开始用！',
                                    'link' => config('app.url') . '/mobile/columnActivity?_hw_c=share',
                                    'imgUrl' => 'http://wyeth-course.nibaguai.com/mobile/images/200-200.jpg',
                                    'desc' => '为什么宝宝看起来总是笨笨的？那是因为你在孕期就慢人一步！'
                                ]])

@include('public.statistics')
<script>
    CIData.push(['trackEvent', 'wyeth', 'column_activity', 'wyeth_channel', getQueryStringByName('_hw_c')]);

    //根据QueryString参数名称获取值

    function getQueryStringByName(name){
        var result = location.search.match(new RegExp("[\?\&]" + name+ "=([^\&]+)","i"));
        if(result == null || result.length < 1){
            return "";
        }
        return result[1];
    }
</script>
</body>
</html>