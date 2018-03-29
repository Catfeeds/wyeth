<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>魔栗妈咪学院</title>
    <meta name="description" content="fenlibao"/>
    <meta name="format-detection" content="telephone=no">
    @include('public.head')
    {{--<link rel="stylesheet" href="{{$su}}/mobile/v2/css/common.css">--}}
    {{--<link rel="stylesheet" href="{{$su}}/mobile/css/search/search.css">--}}
    {{--<link rel="stylesheet" href="{{$su}}/mobile/v2/css/yi.min_temp.min.css?v={{$rv}}">--}}
    <link rel="stylesheet" href="{{$su}}/mobile/v2/css/index.min.css?v={{$rv}}">
    <style>
        #__bs_notify__ {
            display: none !important;
        }
    </style>
    <!--移动端版本兼容 -->
    <script type="text/javascript" src="{{$su}}/mobile/v2/js/pxrem.min.js"></script>
    <!--移动端版本兼容 end -->

    {{--<link rel="stylesheet" href="{{$su}}/js/swiper/swiper-3.3.0.min.css">--}}
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
    </style>
    <style>
        .big_div {
            position: fixed;
            display: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            text-align: center;
            z-index: 9999;
        }

        .mask {
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: black;
            -moz-opacity: 0.7;
            opacity: .70;
            filter: alpha(opacity=70);
            z-index: 999;
        }

        .Advertisement {
            position: relative;
            z-index: 9999;
            top: 1.8rem;
        }

        .Advertisement img {
            width: 7.5rem;
            height: 9.8rem;
        }

        .quit {
            position: relative;
            z-index: 9999;
            top: 3rem;
            width: 80px;
            margin: 0 auto;
        }

        .quit img {
            width: 80px;
            height: 80px;
        }

        /*=====
        =======searchStyle===
        ====*/
        #searcher-box {
            display: none;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background: #fff;
            z-index: 99;
        }

        @-webkit-keyframes fadeInRight {
            from {
                opacity: 0;
                -webkit-transform: translatex(+10px);
                -moz-transform: translatex(+10px);
                -o-transform: translatex(+10px);
                transform: translatex(+10px);
            }
            to {
                opacity: 1;
                -webkit-transform: translatex(0);
                -moz-transform: translatex(0);
                -o-transform: translatex(0);
                transform: translatex(0);
            }
        }

        @-moz-keyframes fadeInRight {
            from {
                opacity: 0;
                -webkit-transform: translatex(+10px);
                -moz-transform: translatex(+10px);
                -o-transform: translatex(+10px);
                transform: translatex(+10px);
            }
            to {
                opacity: 1;
                -webkit-transform: translatex(0);
                -moz-transform: translatex(0);
                -o-transform: translatex(0);
                transform: translatex(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                -webkit-transform: translatex(+100px);
                -moz-transform: translatex(+100px);
                -o-transform: translatex(+100px);
                transform: translatex(+100px);
            }
            to {
                opacity: 1;
                -webkit-transform: translatex(0);
                -moz-transform: translatex(0);
                -o-transform: translatex(0);
                transform: translatex(0);
            }
        }

        .in-left {
            -webkit-animation-name: fadeInRight;
            -moz-animation-name: fadeInRight;
            -o-animation-name: fadeInRight;
            animation-name: fadeInRight;
            -webkit-animation-fill-mode: both;
            -moz-animation-fill-mode: both;
            -o-animation-fill-mode: both;
            animation-fill-mode: both;
            -webkit-animation-duration: .5s;
            -moz-animation-duration: .5s;
            -o-animation-duration: .5s;
            animation-duration: .5s;
            -webkit-animation-delay: .5s;
            -moz-animation-delay: .5s;
            -o-animation-duration: .5s;
            animation-delay: .5s;
        }
        .yellow_bg{
            width: 100%;
            height: 7.3%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 80;
            background: #fedb63;
        }
        .seach_i{
            width:89.07%;
            height: 5.4vh;
            background: #fff;
            opacity:.9;
            position: absolute;
            top: 12.255%;
            left: 5.33%;
            z-index: 80;
            border-radius: 0.42rem;
            text-align: center;
        }
        .seach_i img{
            width:3.89%;
            height:46.43%;
            position: absolute;
            top: 1.35vh;
            left: 17.47%;
        }
        .seach_i span{
            font-size: 0.32rem;
            color: #bab8b7;
            line-height: 5.4vh;
        }
        #DoNone{
            margin-top:7.25vh;
        }

    </style>
</head>
<body>
<div class="yellow_bg">
    <div class="seach_i"  id="searchHref">
        <img src="{{$su}}/mobile/v2/img/index_seach.png" alt="">
        {{--<input type="text" value="" placeholder="找找你感兴趣的内容，如宝宝腹泻"/>--}}
        <span>找找你感兴趣的内容，如宝宝腹泻</span>
    </div>
</div>
<div id="DoNone">
    <div class="big_div" style="display:none;">
        <div class="mask"></div>
        <div class="Advertisement">
            @if ($advertis)
                <a href="{{$advertis[0]['link']}}">
                    <img src="{{$advertis[0]['img']}}" alt="{{$advertis[0]['subject']}}"
                         title="{{$advertis[0]['subject']}}">
                </a>
            @endif
        </div>
        <div class="quit" id="popAdQuit">
            <img src="{{$su}}/mobile/images/quit.png">
        </div>
    </div>
    <div class="swiper-container for-home">
        <div class="swiper-wrapper" style="height:350px;">
            @foreach ($flashPics as $row)
                <div class="swiper-slide">
                    <a onclick="_hmt.push([
                            '_trackEvent',
                            '{{$row['subject']}}',
                            '{{$row['subject']}}-<?php
                    if ($user['subscribe_status']) {
                        echo 'subscribe';
                    } else {
                        echo 'no_subscribe';
                    } ?>-<?php if ($user['crm_hasShop']) {
                        echo 'hasShop';
                    } else {
                        echo 'no_hasShop';
                    } ?>'
                            ,
                            '{{$row['subject']}}-{{$user['id']}}-{{date('Y-m-d H:i:s')}}'
                            ]);
                            CIData.push(['trackEvent', 'wyeth', 'click_ad', 'subject', '{{$row['subject']}}']);" href="{{$row['link']}}">
                        <img class="swiper-lazy" data-src="{{$row['img']}}" style="width:100%;">
                    </a>
                </div>
            @endforeach
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>


    <!-- banner end -->
    <div class="wrap">
        <ul class="list-icon">
            @foreach ($navigations as $row)
                <li><a onclick="CIData.push(['trackEvent', 'wyeth', 'click_cat', 'subject', '{{$row['subject']}}']);"
                       href="{{$row['link']}}"><img class="lazy" data-original="{{$row['img']}}"></a></li>
            @endforeach
        </ul>
    </div>


    <div class="wrap hot">
        <div class="hd"><i class="icon icon-title"></i>热门主题推荐</div>
        <div class="bd">
            <div class="list-link">
                @foreach ($tags as $row)
                    <a href="/mobile/all?type=hot&tag={{$row['name']}}">{{$row['name']}}</a>
                @endforeach
            </div>
        </div>
    </div>

    <ul class="table-view for-good-list">
        @foreach ($courseRecommend1 as $row)
            <li class="table-view-cell media">
                <a class="navigate-right" href="{{$row['url']}}">
                    <div class="pic-left"><img class="media-object pull-left lazy" data-original="{{$row['img']}}"></div>
                    <div class="media-body">
                        <div class="media-right">
                            @if($row['is_signed'] && $row['status'] !=3)
                                <i class="icon icon-status04"></i>
                            @else
                                <i class="icon icon-status0{{$row['status']}}"></i>
                            @endif
                        </div>
                        <h3>{{$row['title']}}</h3>
                        <p class="item"><i class="icon icon-calendar"></i>{{$row['start_day']}} {{$row['start_time']}}
                        </p>
                        <p class="item"><i
                                    class="icon icon-user"></i>{{$row['teacher_name']}} {{$row['teacher_hospital']}}</p>
                        <p class="item"><i class="icon icon-heart"></i>{{$row['hot']}}</p>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
    <div class="swiper-container for-ad">
        <div class="swiper-wrapper">
            @foreach ($flashPics2 as $row)
                <div class="swiper-slide">
                    <a onclick="_hmt.push([
                            '_trackEvent',
                            '{{$row['subject']}}',
                            '{{$row['subject']}}-<?php
                    if ($user['subscribe_status']) {
                        echo 'subscribe';
                    } else {
                        echo 'no_subscribe';
                    } ?>-<?php if ($user['crm_hasShop']) {
                        echo 'hasShop';
                    } else {
                        echo 'no_hasShop';
                    } ?>'
                            ,
                            '{{$row['subject']}}-{{$user['id']}}-{{date('Y-m-d H:i:s')}}'
                            ]);
                            CIData.push(['trackEvent', 'wyeth', 'click_ad', 'subject', '{{$row['subject']}}']);" href="{{$row['link']}}">
                        <img class="swiper-lazy" data-src="{{$row['img']}}" style="width:100%;"></a>
                </div>
            @endforeach
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>
    <ul class="table-view for-good-list">
        @foreach ($courseRecommend2 as $row)
            <li class="table-view-cell media">
                <a class="navigate-right" href="{{$row['url']}}">
                    <div class="pic-left"><img class="media-object pull-left lazy" data-original="{{$row['img']}}"></div>
                    <div class="media-body">
                        <div class="media-right">
                            @if($row['is_signed'] && $row['status'] !=3)
                                <i class="icon icon-status04"></i>
                            @else
                                <i class="icon icon-status0{{$row['status']}}"></i>
                            @endif
                        </div>
                        <h3>{{$row['title']}}</h3>
                        <p class="item"><i class="icon icon-calendar"></i>{{$row['start_day']}} {{$row['start_time']}}
                        </p>
                        <p class="item"><i
                                    class="icon icon-user"></i>{{$row['teacher_name']}} {{$row['teacher_hospital']}}</p>
                        <p class="item"><i class="icon icon-heart"></i>{{$row['hot']}}</p>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
    <div class="bottom-tips">
        <i class="icon icon-round"></i>“魔栗妈咪学院”版权归属景栗科技所有，相关课程内容由景栗科技提供。平台相关内容不作为医学诊断参考，如情况严重，建议及时就医。
    </div>
    <div style="margin-bottom:106px;"><img src="{{$su}}/mobile/v2/img/home_guide.png" width="100%"/></div>

    <div id="guide"
         style="display:none; position: fixed;top: 0px;z-index: 170;background: wheat;width: 100%;height: 100%;padding: 0px;margin: 0px;left: 0px;">
        <div style="position: relative;">
            <img class="lazy" data-original="{{$su}}/mobile/v2/img/guide_bg.jpg">
            <div id="guide-ok" style="position: absolute;
        left: 0;
        right: 0;
        bottom: 8.5%;">
                <img class="lazy" data-original="{{$su}}/mobile/v2/img/guide_btn.png">
                <a href="javascript:void(0);" style="position: absolute;
        left: 0;
        right: 0;
        top:0;
        bottom: 0;
        display: block;
        width: 80%;
        margin: auto;"></a>
            </div>
        </div>
    </div>
</div>
<div id="searcher-box">
    <div id="searchBox">
        <div>
            <a href=""><img src="/mobile/img/searchIcon.png"></a>
            <input type="text" name="keyword" value="" placeholder="找找你感兴趣的内容，如宝宝腹泻" hw-data="腹泻" maxlength="40" style="margin-top:1.65%;height: 40px;
    line-height: normal;
    line-height: 34px\9;"/>
        </div>
        <ul>
            <!-- 后退 -->
            <li class="close-box"></li>
            <!-- 搜索 -->
            <li class="to-search"></li>
        </ul>
    </div>
    <div id="searchCon">
        <div class="searchContentIcon">
            <img src="/mobile/img/searchCon.png"/>
        </div>
        <div class="keyword">
            <ul>
                @foreach ($hotKeyword as $item)
                    <li class="href"
                        location="/mobile/search?keyword={{$item}}">{{mb_substr($item, 0 ,4 , 'utf-8')}}</li>
                @endforeach
            </ul>
        </div>

    </div>
</div>
<script src="{{$su}}/js/jquery.min.js"></script>
<!-- footer start -->
@include('mobile.course.menu', ['current' => 'index'])

<script src="{{$su}}/js/lodash.min.js"></script>
@include('mobile.share', ['share' => $share])
{{--<script src="{{$su}}/js/swiper/swiper.3.2.0.jquery.min.js"></script>--}}
{{--<script src="{{$su}}/js/jquery.storageapi.min.js"></script>--}}
{{--<script src="{{$su}}/mobile/js/jquery.lazyload.min.js"></script>--}}
<script src="{{$su}}/mobile/v2/js/index.min.js"></script>

<script>
    $(function(){
        var xhr = new XMLHttpRequest();
        xhr.open("get", '{{config('app.url') . "/wyeth/loadHome"}}', true);
        xhr.withCredentials = true;
        xhr.send(null);
    });
    $("#searchHref").click(function () {
        $('input[name=keyword]').val('');
        /*$("#searcher-box").fadeIn('100',function(){
         $("#DoNone").fadeOut('500');
         });*/
        $("#searcher-box").show();
        $("#DoNone").hide();
    })
    var showPopAd = '{{$showPopAd}}';
    $(function () {

        $('img.lazy').lazyload({
//            effect : "fadeIn",
            threshold: 200
        });

        $('#searcher-box').css('min-height', windowsHei);
        var encOpenid = "{{$encOpenid}}";
        jiuQianStatis(encOpenid);
        var swiperHome = new Swiper('.swiper-container.for-home', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            autoplay: 3000,
            width: window.innerWidth > 750 ? 750 : window.innerWidth,
            height: 100,
            lazyLoading : true,
            lazyLoadingInPrevNext : true,
        });
        var swiperAd = new Swiper('.swiper-container.for-ad', {
            autoplay: 3000,
            width: window.innerWidth > 750 ? 750 : window.innerWidth,
            height: 100,
            effect: 'fade',
            lazyLoading : true,
            lazyLoadingInPrevNext : true,
        });
//    var guideKey = 'm:course:index:guide:show';
//    if ($.localStorage.isEmpty(guideKey)) {
//        $('#guide').show();
//        $('body').css('overflow', 'hidden');
//    }
//    $('#guide-ok').on('click', function () {
//        $('#guide').hide();
//        $('body').css('overflow', '');
//        $.localStorage.set(guideKey, true);
//    });
        if (showPopAd == '1') {
            initPopAd();
        }

        //搜索弹出层
        $('.href').on("click", function () {
            var url = $(this).attr('location');
            location.href = url;
        })
        var windowsHei = $('#searcher-box').height();
        $('.to-search').on("click touchend", function () {
            var keyword = $('input[name=keyword]').val();
            if (keyword.length == 0) {
                keyword = '腹泻';
            }

            location.href = "/mobile/search?keyword=" + keyword;
        });
        $('.close-box').on("click", function () {
            $('#searcher-box').hide();
            $("#DoNone").show();
        })
    });

    /**
     * 弹层广告初始化
     */
    function initPopAd() {
        $('#popAdQuit').click(function () {
            popAdShow(false);
        });
        var today = new Date();
        today.setHours(0);
        today.setMinutes(0);
        today.setSeconds(0);
        today.setMilliseconds(0);
        // 今天0点
        var todayParse = Date.parse(new Date(today)) / 1000;

        var localStorage = $.initNamespaceStorage('m:course:index').localStorage;
        if (localStorage.isSet('popAdTime')) {
            var time = localStorage.get('popAdTime');
            if (time < todayParse) {
                localStorage.set('popAdTime', Date.parse(new Date()) / 1000);
                popAdShow(true);
            }
        } else {
            localStorage.set('popAdTime', Date.parse(new Date()) / 1000);
            popAdShow(true);
        }

        function popAdShow(show) {
            if (show) {
                _hmt.push(['_trackEvent', '每天首次进入首页弹窗广告', '弹出', '首页']);
                $('.big_div').show();
            } else {
                $('.big_div').hide();
            }
        }
    }
    function jiuQianStatis(userId) {
        var theUrl = "http://" + userId + ".wy01.meritg.cn/index.html?t=" + new Date().getTime();
        var xmlHttp = null;
        xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", theUrl, false);
        xmlHttp.send(null);
        return xmlHttp.responseText;
    }
</script>
<!-- baidu statistics -->
@include('public.statistics')
<script>
    CIData.push(['trackEvent', 'page', 'page_index']);
</script>

</body>
</html>
