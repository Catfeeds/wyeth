<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <title>微课堂</title>
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
</head>

<body @if ($course_review->review_type == '2') class="no-foot" @endif>
<input type="hidden" id="notCid" value="">
<!-- 课程标题 -->
<div class="header">
    <div class="inner">
        <p class="ellipsis">{{$course->title}}</p>
    </div>
</div>

<!-- 课程视频 -->
@if ($course_review->review_type == '2')
<div class="video-wrap">
    <div class="inner">
        <div class="cover" style="background-image: url({{$course_review->video_cover}});">
            <a class="icon icon-start btn-v-play" href="javascript:void(0);"></a>
        </div>
        <div class="cont hide">
            <video id="video" class="video" controls webkit-playsinline>
                <!--<source src="video.ogg" type="video/ogg">-->
                <source src="{{$course_review->video}}" type="video/mp4"> 您的浏览器不支持视频标签。
            </video>
        </div>
    </div>
</div>
@endif

<!-- 课件轮播图 -->
@if ($course_review->review_type == '1')
<div class="img-wrap">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            @foreach ($coursewares as $row)
            <div class="swiper-slide">
                <img data-src="{{$row['img']}}" class="swiper-lazy">
                <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
            </div>
            @endforeach
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination swiper-pagination-white"></div>
        <!-- Navigation -->
        <div class="swiper-button-next swiper-button-white"></div>
        <div class="swiper-button-prev swiper-button-white"></div>
    </div>
</div>
@endif

<!-- 已有xxx个麻麻学过 点赞 分享 -->
<div class="tool-wrap clearfix">
    <div class="tool-l pull-left">已有{{$mothersNum}}个麻麻学过</div>
    <div class="tool-r pull-right">
        <ul>
            <li>
                <a @if($isLike) class="btn-thumb active" @else class="btn-thumb" @endif  href="javascript:void(0);">
                    <p><i class="icon icon-thumb"></i></p>
                    <p class="thumb-num">{{$reviewLikesNum}}</p>
                </a>
            </li>
            <li>
                <a class="btn-share" href="javascript:void(0);">
                    <p><i class="icon icon-share"></i></p>
                    <p>分享</p>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- 老师介绍 章节要点 精彩问答 -->
<div class="details-wrap mar-t-gap">
    <ul class="nav-tabs clearfix">
        <li>
            <a href="#teacher"><i class="icon icon-lecturer"></i>老师介绍</a>
        </li>
        <li class="active">
            <a href="#gist"><i class="icon icon-gist"></i>章节要点</a>
        </li>
        <li>
            <a href="#ask"><i class="icon icon-ask"></i>精彩问答</a>
        </li>
    </ul>
    <div class="tab-content">
        <!-- 老师介绍  -->
        <div class="tab-pane fade clearfix" id="teacher">
            <div class="lecturer">
                <div class="info clearfix">
                    <div class="face pull-left" style="background-image:url({{$course->teacher_avatar}});"></div>
                    <div>
                        <div class="name">{{$course->teacher_name}}</div>
                        <div>{{$course->teacher_hospital}}</div>
                        <div>{{$course->teacher_position}}</div>
                    </div>
                </div>
                <div class="intro">{{$course->teacher_desc}}</div>
            </div>
        </div>
        <!-- 章节要点 -->
        <div class="tab-pane fade clearfix active in" id="gist">
            <div class="gist">
                <div class="cont">{{$course->desc}}</div>
                <ul id="gist_list">
                    @if ($course_review->section)
                    @foreach ($course_review->section as $k=>$v)
                    <li class="clearfix">
                        <span class="tit ellipsis pull-left">Part {{$k + 1}}: {{$v['point']}}</span>
                        @if ($course_review->review_type == '2')
                        <a second="{{$v['second']}}" class="btn-node pull-right" href="javascript:void(0);"><i class="icon icon-nodeplay"></i></a>
                        @endif
                    </li>
                    @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <!-- 精彩问答 -->
        <div class="tab-pane fade clearfix" id="ask">
            <div class="ask">
                @if ($qAndA)
                @foreach ($qAndA as $k=>$v )
                <div @if ($k>4) class="hide" @endif  hw-qa-item>
                    <div class="ask-item ask-left clearfix">
                        @if ($v['q_avatar'])
                        <div class="face" style="background-image:url({{$v['q_avatar']}});"></div>
                        @else
                        <div class="face" style="background-image:url({{config('course.static_url') }}/assets/mobile/review//images/q_head.jpg?v={{$resource_version}});"></div>
                        @endif
                        <div class="bubble">{{$v['question']}}</div>
                    </div>
                    <div class="ask-item ask-right clearfix">
                        <div class="face" style="background-image:url({{config('course.static_url') }}/assets/mobile/review//images/p-and-a.jpg?v={{$resource_version}});"></div>
                        <div class="bubble">{{$v['answer']}}</div>
                    </div>
                </div>
                @endforeach
                @endif
                <a class="btn-askmore" id="open" href="javascript:void(0);">点击展开更多</a>
            </div>
        </div>
    </div>
</div>

<!-- 课程导读 -->
<div class="guide-wrap mar-t-gap">
    @if ($course_review->guide_title)
    <div class="sign-title">
        <div class="tit">{{$course_review->guide_title}}</div>
    </div>
    @endif
    <div class="cont">
        {!!$course_review->guide!!}
    </div>
</div>

<!-- 广告位1 -->
@if ($carouselsEnd1)
<a class="banner mar-t-gap" href="{{$carouselsEnd1['link']}}">
    <img src="{{$carouselsEnd1['img']}}">
</a>
@endif

<!-- 课程推荐 -->
@if ($coursesRecommend)
<div class="list-wrap mar-t-gap">
    <div class="sign-title clearfix">
        <div class="tit pull-left">爱学习的麻麻都在看</div>
        <div class="pull-right">
            <a id="coursesRecommend-change" class="btn-change" href="javascript:void(0);"><i class="icon icon-change"></i>换一换</a>
        </div>
    </div>
    <ul>
        @foreach ($coursesRecommend as $v)
        <li class="coursesRecommend{{$v['group']}} <?php if($v['group']!=1) {echo 'hide';} else {echo 'show';} ?>">
            <div class="item">
                <div class="cont clearfix">
                    <div class="img pull-left">
                        <div class="img-inner" style="background-image:url({{$v['img']}});"></div>
                    </div>
                    <div class="detail">
                        <div class="tit">{{$v['title']}}</div>
                        <div class="time"><i class="icon icon-time"></i>{{$v['start_day']}}&nbsp{{$v['start_time']}}</div>
                        <div class="info"><i class="icon icon-staff"></i><span class="name">{{$v['teacher_name']}}</span>{{$v['teacher_position']}}</div>
                        <div class="tag">
                            @if (isset($v['tags'][0]))
                            <em class="bg-yellow">{{$v['tags'][0]}}</em>
                            @endif
                            @if (isset($v['tags'][1]))
                            <em class="bg-orange">{{$v['tags'][1]}}</em>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="cont-b clearfix">
                    <div class="pull-left"><i class="icon icon-medal"></i>已有<span class="c-orange">{{$v['signNum']}}</span>个麻麻报名学习</div>
                    <div class="pull-right">
                        <a href="{{$v['url']}}">我也要学</a>
                    </div>
                </div>
            </div>
        </li>
        @endforeach
        <li>
            <a class="btn-listmore" href="/mobile/all">查看更多课程</a>
        </li>
    </ul>
</div>
@endif
<!-- 广告位2 -->
@if ($carouselsEnd2)
<a class="banner mar-t-gap" href="{{$carouselsEnd2['link']}}">
    <img src="{{$carouselsEnd2['img']}}">
</a>
@endif

<!-- 音频 -->
@if ($course_review->review_type == '1')
<div class="footer clearfix" style="background-color: #f8e6a9">
    <div class="face pull-left" style="background-image:url({{$course->teacher_avatar}});"></div>
    <div class="intro pull-left">
        <div class="tit ellipsis" style="color: #774321">{{$course->title}}</div>
        <div class="info">
            <span class="name">{{$course->teacher_name}}</span>
            <span>{{$course->teacher_hospital}}</span>
        </div>
    </div>
    <audio id="audio" class="audio hide" controls="controls">
        <!--<source src="music.ogg" type="audio/ogg">-->
        <source src="{{$course_review->audio}}" type="audio/mpeg"> 您的浏览器不支持音频标签。
    </audio>
    <a class="btn-video pull-right icon icon-state play" href="javascript:void(0);"></a>
</div>
@endif
<div class="share-wrap hide"></div>
<!-- JavaScript -->
<script src="{{config('course.static_url')}}/js/zepto.min.js"></script>
<script src="{{config('course.static_url')}}/mobile/js/swiper.jquery.min.js"></script>
<script src="{{config('course.static_url')}}/assets/mobile/review/js/review.js"></script>
<script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/course_end.js?v={{$resource_version}}"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    var cid = '{{$course->id}}';
    var coursesRecommend1 = $('.coursesRecommend1'),
        coursesRecommend2 = $('.coursesRecommend2'),
        coursesRecommend3 = $('.coursesRecommend3'),
        coursesRecommendChange = $('#coursesRecommend-change');

    coursesRecommendChange.on('click', function() {
        if (coursesRecommend1.hasClass('show')) {
            if (coursesRecommend2.length > 0) {
                coursesRecommend1.removeClass('show');
                coursesRecommend1.addClass('hide');
                coursesRecommend2.removeClass('hide');
                coursesRecommend2.addClass('show');
            }
        } else if (coursesRecommend2.hasClass('show')) {
            if (coursesRecommend3.length > 0) {
                coursesRecommend2.removeClass('show');
                coursesRecommend2.addClass('hide');
                coursesRecommend3.removeClass('hide');
                coursesRecommend3.addClass('show');
            } else {
                coursesRecommend2.removeClass('show');
                coursesRecommend2.addClass('hide');
                coursesRecommend1.removeClass('hide');
                coursesRecommend1.addClass('show');
            }
        } else if (coursesRecommend3.hasClass('show')) {
            coursesRecommend3.removeClass('show');
            coursesRecommend3.addClass('hide');
            coursesRecommend1.removeClass('hide');
            coursesRecommend1.addClass('show');
        }
    });

    $(function() {
        if ($('#ask [hw-qa-item].hide').length < 1) {
            $('#open').hide();
        }
        $('#open').on("click", function() {
            $('#ask [hw-qa-item].hide').slice(0, 5).removeClass('hide');
            if ($('#ask [hw-qa-item].hide').length < 1) {
                $('#open').hide();
            }
        });
    });

    var token;
    $.getJSON('/token', function (data) {
        if ('token' in data) {
            token = data.token;
        }
        var options = {course_id: {{$course->id}}, is_subscribed: {{$is_subscribed}}};
        Course_End.init(options);
    });
    $(document).ready(function(){
        $(document).on('ajaxBeforeSend', function(e, xhr, options){
            if (!token) {
                console.log('token empty before ajax send');
                return false;
            }
            xhr.setRequestHeader('Authorization', 'bearer ' + token);
        });
    });
    //自己的分享统计
    function myRecordShare () {
        $.ajax({
            type: "POST",
            url: "/api/course/share",
            data: {
                cid: cid,
                type: 3
            },
            success: function(data){
                console.log(data);
            },
            error: function(data){
                console.log(data);
            }
        });
    }

    var shareUrl = '{{config('app.url')}}' + '/mobile/end?cid={{$course->id}}'+'&from_openid='+'{{$openid}}';
    var firend_title = '{{$course_review['firend_title']}}';
    var firend_subtitle = '{{$course_review['firend_subtitle']}}';
    var share_title = '{{$course_review['share_title']}}';
    var share_picture = '{{$course_review['share_picture']}}';
    wx.ready(function(){
        // 分享朋友圈的数据
        wx.onMenuShareTimeline({
            title: share_title, // 分享标题
            link: shareUrl, // 分享链接
            imgUrl: share_picture, // 分享图标
            success:function() {
                if (token) {
                    myRecordShare();
                }
            }
        });

        // 分享给好友的数据
        wx.onMenuShareAppMessage({
            title: firend_title, // 分享标题
            desc: firend_subtitle, // 分享描述
            link:shareUrl, // 分享链接
            imgUrl: share_picture, // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success:function() {
                if (token) {
                    myRecordShare();
                }
            }
        });
    });
</script>
<script src="http://mp.gtimg.cn/open/js/openApi.js"></script>
<script type="text/javascript">
    $.ajax({
        type: "get",
        url: 'http://wyeth.qq.nplusgroup.com/api/toauth/index.json',
        data:'url='+encodeURIComponent(location.href.split('#')[0])+'&callback=?',
        dataType: "jsonp",
        jsonp: "callback",
        success: function(json){
            var mqqConfig = {
                debug:false,
                appId: json.appId,
                timestamp: json.timestamp,
                nonceStr: json.nonceStr,
                signature: json.signature,
                jsApiList: [
                    'onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareQzone','closeWindow'
                    ,'hideOptionMenu','showOptionMenu','hideMenuItems','showMenuItems','hideAllNonBaseMenuItem'
                ]
            }
            mqq.config(mqqConfig);
        },
        error:function (){
            alert("fail");
        }
    });

    window.isready = false;
    mqq.ready(function(){
        window.isready = true;
        mqq.hideAllNonBaseMenuItem();
        mqq.showMenuItems({
            menuList: ['menuItem:share:qq'] // 要显示的菜单项，所有menu项见附录3
        });
        share({});
    });

    window.shareparam = {
        'title':'{{$course->firend_title}}',
        'link': 'http://wyeth.qq.nplusgroup.com/phone/wkt/detail-{{$course->id}}.htm',
        'imgUrl':"{{$course->share_picture}}",
        'desc':'{{$course->firend_subtitle}}'
    };

    function extend(destination, source) {
        for (var property in source)
            destination[property] = source[property];
        return destination;
    }

    function share(params){
        if(params){
            window.shareparam = extend(window.shareparam,params);
        }
        //分享到qq好友
        mqq.onMenuShareQQ({
            title: window.shareparam.title, // 分享标题
            link: window.shareparam.link, // 分享链接
            imgUrl: window.shareparam.imgUrl, // 分享图标
            desc: window.shareparam.desc, // 分享描述
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数

            }
        });
    }
</script>
@include('public.statistics')
</body>

</html>
