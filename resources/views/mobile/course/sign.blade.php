<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>微课堂</title>
    <meta name="description" content="fenlibao" />
    <meta name="format-detection" content="telephone=no">
    @include('public.head')
    <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/common.css">
    <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/cat.css?v={{$resource_version}}">

    <style>
    #__bs_notify__{display: none!important;}
    </style>
    <!--移动端版本兼容 -->
    <script type="text/javascript" src="{{ config('course.static_url') }}/mobile/v2/js/pxrem.js"></script>
    <!-- 图片轮播css -->
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
    .btn_share{
        background:transparent url({{ config('course.static_url') }}/mobile/img/btn_share.png?sdfadsfasds) no-repeat 0 0;
        background-size:contain;
    }
    </style>
    <script>var _hmt = _hmt || []; var CIData = CIData || [];</script>
</head>
<body>
<div class="share-success">
    <div class="share-inner">
        <i class="cicon icon-share-success"></i>
        <div class="center">
            <div class="remind">
            开课前10分钟会通过微信消息提醒妈妈哦~
        </div>
            <a href="javascript:" class="btn-share">推荐给妈妈群里的闺蜜们</a>
        </div>
    </div>
</div>

<div class="wrap for-inner">
    <div class="hd center">我们猜你对以下内容感兴趣</div>
</div>

<ul class="table-view for-good-list">
    @foreach ($courses as $course)
  <li class="table-view-cell media">
        <a class="navigate-right" href="{{$course['url']}}">
        <div class="pic-left"><img class="media-object pull-left" src="{{ $course['img'] }}"></div>
        <div class="media-body">
        <div class="media-right">
            <i class="icon icon-status0{{$course['status']}}"></i>
        </div>
        <h3>{{ $course['title'] }}</h3>
        <p class="item"><i class="icon icon-calendar"></i>{{$course['start_day']}}  {{$course['start_time']}}</p>
        <p class="item"><i class="icon icon-user"></i>{{$course['teacher_name']}}  {{$course['teacher_hospital']}}</p>
        <p class="item"><i class="icon icon-heart"></i>{{ $course['hot'] }}</p>
        </div>
        </a>
    </li>
    @endforeach
</ul>

<div class="swiper-container for-ad">
    <div class="swiper-wrapper">
        @foreach ($ads as $ad)
        <div class="swiper-slide">
            <a onclick="_hmt.push([
                    '_trackEvent',
                    '{{$ad['subject']}}',
                    '{{$ad['subject']}}-<?php
                        if($user['subscribe_status']){ echo 'subscribe'; } else { echo 'no_subscribe'; } ?>-<?php if($user['crm_hasShop']){ echo 'hasShop'; }else{ echo 'no_hasShop'; } ?>'
                    ,
                    '{{$ad['subject']}}-{{$user['id']}}-{{date('Y-m-d H:i:s')}}',
                    '{{$userStatusBaiduStatistics}}'
                ]);
                    CIData.push(['trackEvent', 'wyeth', 'click_ad', 'subject', '{{$ad['subject']}}']);" href="{{$ad['link']}}">
            <img src="{{ $ad['img'] }}" style="width:100%;"></a>
        </div>
        @endforeach
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
</div>
<div class="modal transparent" id="pageShare">
    <div id="guideShare">
        <a href="javascript:void(0)" class="btn btn_share" id="btnShare"></a>
    </div>
</div>
<!-- share needed 2 js -->
<script src="{{ config('course.static_url') }}/mobile/js/lodash.min.js"></script>
<script src="{{config('course.static_url')}}/js/jquery.min.js"></script>
@include('mobile.share', ['share' => $share])
<!-- flash -->
<script src="{{ config('course.static_url') }}/mobile/js/libs/zepto/zepto.js"></script>
<script src="{{ config('course.static_url') }}/mobile/js/libs/swiper/swiper.3.2.0.jquery.min.js"></script>
<script>
    $(function(){
        setTimeout("StartAnimate()",500);
    })
    function StartAnimate(){
        $("#btnShare").addClass("btn_share_animate");
    }
var swiper = new Swiper('.swiper-container.for-ad', {
    autoplay: 3000,
    width: window.innerWidth>750?750:window.innerWidth,
    height: 100,
    effect: 'fade'
});
<!-- sp -->
var winH = $(window).height();
$('.share-success').height(winH-668-86);
$('.btn-share').on('touchend',function(e){
    $('#pageShare').addClass('active');
    $('#guideShare').addClass('course_head');
    e.preventDefault();
});

$('#pageShare').on('touchend',function(e){
    $('#pageShare').removeClass('active');
    $('#guideShare').removeClass('course_head');
    e.preventDefault();
})
    sessionStorage.setItem("flag", getQueryString("cid"));

    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return r[2]; return null;
    }
</script>
@include('public.statistics')
</body>
</html>
