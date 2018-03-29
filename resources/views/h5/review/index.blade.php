<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>课程回顾</title>
    <meta name="description" content="fenlibao" />
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/common.css">
    <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/yi.min_temp.css?v=<?=$resource_version;?>">
    <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/dropload.css">
    <link rel="stylesheet" href="{{config('course.static_url')}}/js/swiper/swiper-3.3.0.min.css">
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
        #__bs_notify__{display: none!important;}
    </style>
    <!-- 移动端版本兼容 -->
    <script type="text/javascript" src="{{config('course.static_url')}}/mobile/v2/js/pxrem.js"></script>
    <!-- 移动端版本兼容 end -->
    <script>var _hmt = _hmt || [];</script>
</head>

<body>
    <div class="swiper-container for-home">
        <div class="swiper-wrapper" style="height:350px;">
            @foreach ($flashPics as $k=>$row)
                <div class="swiper-slide">
                    <a hw-statistics="alipay课程回顾首页-轮播{{$k+1}}" href="{{$row['link']}}">
                        <img src="{{$row['img']}}" style="width:100%;">
                    </a>
                </div>
            @endforeach
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>
    <!-- banner end -->

    <!-- tags -->
    <div class="wrap for-search">
        <div class="bd">
            <div class="list-link">
                <div class="list-link-title">适合阶段</div>
                <a href="###" @if ($stage == 0) class="active" @endif hw_stage="" hw-statistics="alipay课程回顾首页-适合阶段-全部"><span>全部</span></a>
                <a href="###" @if ($stage == 1) class="active" @endif hw_stage="1" hw-statistics="alipay课程回顾首页-适合阶段-孕早期"><span>孕早期</span></a>
                <a href="###" @if ($stage == 2) class="active" @endif hw_stage="2" hw-statistics="alipay课程回顾首页-适合阶段-孕中晚期"><span>孕中晚期</span></a>
                <a href="###" @if ($stage == 3) class="active" @endif hw_stage="3" hw-statistics="alipay课程回顾首页-适合阶段-新手妈咪"><span>新手妈咪</span></a>
            </div>
            <div class="list-link">
                <div class="list-link-title">热门标签</div>
                <div style="height:140px;">
                    <a href="#" @if (!$tagId) class="active" @endif hw_tag="" hw-statistics="alipay课程回顾首页-热门标签-不限"><span>不限</span></a>
                    <div class="tags">
                        @foreach ($tagsReview as $tag)
                            <a href="#" @if ($tagId == $tag['id']) class="active" @endif hw_tag="{{$tag['id']}}" hw-statistics="alipay课程回顾首页-热门标签-{{$tag['name']}}"><span>{{$tag['name']}}</span></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- lists -->
    <div id="courseArea">
        <ul class="table-view for-good-list" id="courseList">
            @foreach ($contents as $row)
                <li class="table-view-cell media" hw_item="{{$row['cid']}}">
                    <a class="navigate-right" href="/h5/review/{{$row['cid']}}" hw-statistics="alipay课程回顾首页-课程推荐-cid{{$row['cid']}}">
                        <div class="pic-left">
                            <img class="media-object pull-left" src="{{$row['img']}}">
                        </div>
                        <div class="media-body">
                            <div class="media-right">
                                <i class="icon icon-status0{{$row['status']}}"></i>
                            </div>
                            <h3>{{$row['title']}}</h3>
                            <p class="item"><i class="icon icon-calendar"></i>{{$row['start_day']}} {{$row['start_time']}}</p>
                            <p class="item"><i class="icon icon-user"></i>{{$row['teacher_name']}} {{$row['teacher_hospital']}}</p>
                            <p class="item"><i class="icon icon-heart"></i>{{$row['hot']}}</p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- 声明 -->
    <div class="pb120 bottom-tips" hw_content>
        <i class="icon icon-round"></i>妈妈微课堂由育儿24小时提供，惠氏妈妈俱乐部仅提供平台支持，如情况严重，建议及时就医。
    </div>

    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.js"></script>
    <script src="/mobile/review/js/review_dropload.js"></script>
    <script src="/mobile/review/js/review_list.js?v=<?=$resource_version;?>"></script>
    <script src="{{config('course.static_url')}}/js/lodash.js"></script>
    <script src="{{config('course.static_url')}}/js/swiper/swiper.3.2.0.jquery.min.js"></script>
    <script src="{{config('course.static_url')}}/js/jquery.storageapi.min.js"></script>
    <script>
        var tag = '{{$tagId}}';
        var page = '{{$page}}';
        var stage = '{{$stage}}';
        $(function () {
            var options = {
                page: page,
                tag: tag,
                stage: stage,
                type: 'review'
            };
            AppList.init(options);
        });
    </script>
    <script>
        $(function() {
            var swiperHome = new Swiper('.swiper-container.for-home', {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                autoplay: 3000,
                width: window.innerWidth>750?750:window.innerWidth,
                height: 100
            });
            var swiperAd = new Swiper('.swiper-container.for-ad', {
                autoplay: 3000,
                width: window.innerWidth>750?750:window.innerWidth,
                height: 100,
                effect: 'fade'
            });
            var guideKey = 'm:course:index:guide:show';
            if ($.localStorage.isEmpty(guideKey)) {
                $('#guide').show();
                $('body').css('overflow', 'hidden');
            }
            $('#guide-ok').on('click', function () {
                $('#guide').hide();
                $('body').css('overflow', '');
                $.localStorage.set(guideKey, true);
            });

        });

        //百度统计
        $('a[hw-statistics]').on('click', function() {
            var hwStatistics = $(this).attr('hw-statistics');
            _hmt.push(['_trackEvent', hwStatistics]);
        });
    </script>
    @include('h5.review.statistics')
</body>
</html>