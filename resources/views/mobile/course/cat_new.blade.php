<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>微课堂</title>
    <meta name="description" content="fenlibao" />
    <meta name="format-detection" content="telephone=no">
    <meta name="author" content="ZHOUZON,345460126@qq.com">
    @include('public.head')
    <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/common.css?v={{ $resource_version}}">
    <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/cat.css?v={{ $resource_version}}">
    <style>
        #__bs_notify__{display: none!important;}
    </style>
    <!--移动端版本兼容 -->
    <script type="text/javascript" src="{{ config('course.static_url') }}/mobile/v2/js/pxrem.js"></script>
    <!--移动端版本兼容 end -->
    <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/js/libs/swiper/swiper-3.3.0.min.css">
    <style>
        .swiper-container {
            width: 100%;
            height: 100%;
        }
        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }
        .activity-title {
            margin-bottom: 0;font-size: 48px;  line-height: 72px;  margin-left: 39px;  margin-right: 27px;  height: 204px;  display: flex;  flex-direction: row;  align-items: center;  justify-content: space-between;
        }
        .content {
            background-color: #fff;
        }
        .detail {
            font-size: 30px; background-color: #fff; padding: 20px 30px;
        }
        .course_title {
            font-size: 30px; color: #000; line-height: 1.2em;
        }
        .course_desc {
            font-size: 26px; color: #666;line-height: 1.2em;
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
        .activity-teacher-container {
            display: flex; align-items: flex-start; flex-direction: column; padding-bottom: 61px; border: 2px solid rgb(226,226,226); margin-bottom: 30px;
        }
        .activity-teacher-avatar {
            width: 116px; height: 116px; border-radius: 58px;border: solid rgb(225,185,125) 2px;
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
    </style>
</head>
<body style="background: #f2f2f2;">
<div class="swiper-container for-home" style="margin-bottom: 0;">
    <div class="swiper-wrapper" style="height:350px;">
        <div class="swiper-slide"><img src="{{$courseCat['img']}}" style="width:100%;"></div>
    </div>
    <a href="javascript:" class="cicon icon-share">分享</a>
</div>
<!-- banner end -->
<div style=" background-color: #fff;">
    <p class="activity-title">{{$courseCat['name']}}</p>
</div>
<div class="wrap course-content">
    <p>
        {{$courseCat['description']}}
    </p>
    <p class="number">已有<span>{{$number}}</span>人参加</p>
</div>

<div class="wrap for-inner">
    <div class="hd"><i class="cicon icon-title-course"></i>课程介绍</div>
</div>

<div class="detail">
    @foreach($courses as $index => $value)
        <p class="course_title">课程{{$index + 1}}：{{ $value['cat_title'] ? $value['cat_title'] : $value['title'] }}</p>
        <p class="course_desc">{{ $value['cat_desc'] ? $value['cat_desc'] : $value['desc'] }}</p>
        <div class="voice-container">
            <img class="voice-play-button" id="play{{$index}}" data-id="{{$index}}" src="{{ config('course.static_url') }}/mobile/images/c_activity_play.png">
            <div style="line-height: 1rem">
                <span class="voice-title">{{$value['title']}}</span>
                <div class="voice-length">
                    <div class="voice-progress"></div>
                </div>
                <div class="voice-info-c">
                    <span class="voice-teacher">{{$value['teacher_name']}}-{{$value['teacher_position']}}</span>
                    <span class="voice-time voice-time-curr">0:00</span>
                    <span class="voice-time voice-time-total">--:--</span>
                </div>
            </div>
            <audio id="audio{{$index}}" class="audio" controls="controls">
                <source src="{{ $value['audio_src'] }}" type="audio/mpeg"> 您的浏览器不支持音频标签。
            </audio>
        </div>
    @endforeach
    <div style="line-height: 1.4em; text-align: center; margin-bottom: 20px; font-size: 34px">-课程讲师-</div>
    <div class="activity-teacher-container">
        <div style="width: 493px; height: 4px; margin-top: -3px; background: #fff; margin-left: 102px"></div>
        <div style="display: flex; flex-flow: row wrap">
            @foreach($teachers as $course)
                <div style="display: flex; flex-direction: column; margin-top: 77px; align-items: center; margin-left: 17px">
                    <img class="activity-teacher-avatar" src="{{$course['teacher_avatar']}}">
                    <span class="activity-teacher-name">{{$course['teacher_name']}}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="pb120 bottom-tips">
    <i class="icon icon-round"></i>“魔栗妈咪学院”版权归属景栗科技所有，相关课程内容由景栗科技提供。平台相关内容不作为医学诊断参考，如情况严重，建议及时就医。
</div>
@if($_GET['id'] != 39)
    <div id="vv" class="footer-btn">一键报名</div>
@endif
<script src="{{config('course.static_url')}}/js/jquery.min.js"></script>
<script src="//cdn.bootcss.com/lodash.js/4.6.1/lodash.min.js"></script>
@include('mobile.share', ['share' => $share])
<script>

    for (var i = 0; i < '{{ count($courses) }}'; i++) {
        eventTester("loadedmetadata", i);
    }

    function eventTester (e, index){
        var audio = $('#audio' + index)[0];
        var time = $(".voice-time-total")[index];
        var limit = audio.duration;
        audio.addEventListener(e, function(){
            if (e == 'play') {
                if (audio.currentTime < limit) {
                    setTimeout("checkTime(" + index + ")", 1000);
                } else {
                    resetTime(index)
                    audio.load();
                }
            } else if (e == 'loadedmetadata') {
                $(time).html(getDisplayTime(audio.duration));
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

    function resetTime(index) {
        $($(".voice-time-curr")[index]).html('00:00');
        $($('.voice-progress')[index]).css('width', 0);
        $($('.voice-play-button')[index]).attr('src', '{{ config('course.static_url') }}/mobile/images/c_activity_play.png')
    }

    function checkTime(index) {
        var audio = $('#audio' + index)[0];
        var limit = audio.duration;
        $($('.voice-progress')[index]).css('width', audio.currentTime / limit * 490);
        $($(".voice-time-curr")[index]).html(getDisplayTime(Math.ceil(audio.currentTime)));
        if (audio.paused) {
            return '';
        } else if (audio.currentTime < limit) {
            setTimeout("checkTime(" + index + ")", 1000);
        } else {
            audio.load();
            resetTime(index)
            return '';
        }
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
</script>
@include('public.statistics')
</body>
</html>
