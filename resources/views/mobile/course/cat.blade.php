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
<div class="wrap course-content">
    <p>
    {{$courseCat['description']}}
    </p>
    <p class="number">已有<span>{{$number}}</span>人参加</p>
</div>

<div class="wrap for-inner">
    <div class="hd"><i class="cicon icon-title-course"></i>课程介绍</div>
</div>

<ul class="table-view for-good-list for-course">
    @foreach ($courses as $key => $course)
    <li class="table-view-cell media
    @if ($course['status'] == 3)
        review
    @elseif (isset($course['sign']))
        actived
    @else
        active
    @endif
    " hw_id="{{$course['id']}}">
    <div class="course">
        {{--<div class="selectAndImg"><div class="left-icon"></div></div>--}}

        <div class="gold_medal">第<span>{{++$key}}</span>节</div>

        @if ($course['status'] == 3)
        <div class="down_details" onclick="location.href='end?cid={{ $course['id'] }}'" style="margin-left: 30px">
        @else
        <div class="down_details" style="margin-left: 30px">
        @endif
            <div class="down_details_root" hw-data="0">详情<i class="cicon icon-down"></i></div>

            <div class="img" style="position:relative; overflow:hidden;">
                @if ($course['status'] == 3)
                {{--<a href="end?cid={{ $course['id'] }}">--}}
                <img width="100%" class="media-object pull-left" src="{{$course['img']}}">
                {{--<div class="review-layer">查看回顾</div>--}}
                {{--</a>--}}
                @else
                <img width="100%" class="media-object pull-left" src="{{$course['img']}}">
                @endif
            </div>

            <div class="main_body">
                <div class="main_body_">
                    <div class="course_title">{{$course['title']}}</div>
                    <div class="time">
                        <i style="margin-right:15px;" class="icon icon-calendar"></i>
                        {{$course['start_day']}}  {{$course['start_time']}}</div>
                    <div class="teacher">
                        <i style="margin-right:15px;" class="icon icon-user"></i>{{$course['teacher_name']}}  {{$course['teacher_hospital']}}
                    </div>
                    <div>
                        <div class="hot"><i style="margin-right:15px;" class="icon icon-heart"></i>{{$course['hot']}}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="details">
            <div class="dtitle">课程介绍</div>
            <div>{{$course['desc']}}</div>
        </div>
    </div>
    </li>
    @endforeach
</ul>
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
$(function(){
    if (GetQueryString('id') == 39) {
        $('.footer-btn').hide();
    }
    /*
    $('.for-course li').on('click',function(e){
        var $this = $(this);
        //如果已经报名了，那么就啥也不做 icon-check
        if(!$this.hasClass('actived')){
            $this.toggleClass('active');
        }
    });
    */
    $('.selectAndImg').on('click',function(e){
        //
        var activeElement = $(this).parent().parent('li');

        if(!activeElement.hasClass('actived') && !activeElement.hasClass('review')){
            activeElement.toggleClass('active');
        }
        //
    })

    $('.down_details_root').on('touchend',function(e){
       var data = $(this).attr('hw-data');
       if(data == 0){
           $(this).parent(".down_details").next(".details").show();
           $(this).html('收起<i class="cicon icon-down" data="0"></i>');
           $(this).attr('hw-data',1);
       }else{
           $(this).parent(".down_details").next(".details").hide();
           $(this).html('详情<i class="cicon icon-down" data="1"></i>');
           $(this).attr('hw-data',0);
       }

    });

    $('#vv').click(function(){
        //
        var arr = Array();
        var kk = $('.active');
        if(kk.size()>0){
            //
            $('.active').each(function(i){
                var t = $(this).attr('hw_id');
                arr.push(t);
            });

            //如果没有关注
            if(!{{$userInfo['is_subscribed']}}){
                window.location.href = '/mobile/attention';
                return;
            }
            //如果没有报名
            if(!{{$userInfo['is_crmmember']}}){
                var ids = arr.join('.');
                window.location.href = '/mobile/card?cid=' + ids + '&uid=' + {{$userInfo['id']}};
                return;
            }

            //取得现在用户的openid,然后发送到appp/http/api/service/userControll/signCourse
            $.ajax({
                url: '/mobile/sign',
                type: 'post',
                data: {course_ids: arr},
                dataType: 'json',
                success: function(json){
                    //课程报名成功，转到成功页面
                    if (json.mark) {
                        window.location.href = '/mobile/sign';
                    }else{
                        alert('报名失败');
                    }
                }
            });
            //
        }else{
            // table-view-cell media active 已选中
            // table-view-cell media         未选中
            // table-view-cell media actived  已报过
            // table-view-cell media review   已结束
            if($('.media.review, .media.actived').size() == $('.media').size()){
                window.location.href = '/mobile/all';
            }else{
                alert('您还未没有选择课程');
            }
        }
        //
    });

    //
    if($('.active').size() <= 0){
        $('.footer-btn').html('报名更多课程');
    }

    function GetQueryString(name)
    {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    }
    //
});
</script>
@include('public.statistics')
</body>
</html>
