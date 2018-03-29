<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>妈咪早安</title>
    <meta name="description" content="fenlibao"/>
    <meta name="format-detection" content="telephone=no">
    @include('public.head')
    {{--<link rel="stylesheet" href="{{$su}}/mobile/v2/css/common.css">--}}
    {{--<link rel="stylesheet" href="{{$su}}/mobile/css/search/search.css">--}}
    {{--<link rel="stylesheet" href="{{$su}}/mobile/v2/css/yi.min_temp.min.css?v={{$rv}}">--}}
    <link rel="stylesheet" href="{{$su}}/mobile/v2/css/index.min.css?v={{$rv}}">
    <style>
        .main-container1 {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: url('{{$su}}/mobile/v2/images/morning_bg1.png');
            height: 1156px
        }

        .main-container2 {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: url('{{$su}}/mobile/v2/images/morning_bg2.png');
            height: 934px;
        }

        .date-bg {
            background: url('{{$su}}/mobile/v2/images/morning_fur.png');
            background-size: cover;
            display: inline-block;
            color: #fff;
            position: absolute;
            top: 0;
            padding-left: 5px;
        }

        .date-bg-l {
            width: 85px;
            height: 45px;
            line-height: 45px;
            font-size: 25px;
        }

        .date-bg-s {
            width: 73px;
            height: 39px;
            line-height: 39px;
            margin-left: 12px;
            margin-top: 11px;
            font-size: 22px;
        }

        .voice-container {
            width: 452px;
            height: 90px;
            margin-top: 70px;
            display: flex;
            align-items: center;
            flex-direction: row;
            background: url('{{$su}}/mobile/v2/images/morning_voice.png');
            background-size: cover;
            padding: 0 21px 0 37px;
            justify-content: space-between;
            font-size: 28px;
            color: #fff;
        }

        .course-introduce {
            color: #9B9B9B;
            font-size: 24px;
            margin-top: 17px;
        }

        .breast-container {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            padding-left: 24px;
            padding-right: 36px;
        }

        .breast-group-img {
            object-fit: cover;
            width: 218px;
            height: 218px;
            margin-left: 12px;
            margin-top: 11px;
            box-shadow: 3px 3px 5px #f7d77f;
        }
    </style>
    <!--移动端版本兼容 -->
    <script type="text/javascript" src="{{$su}}/mobile/v2/js/pxrem.min.js"></script>
    <!--移动端版本兼容 end -->
    <script> var CIData = CIData || [];</script>
</head>
<body>
<div class="main-container1">
    <div style="width: 320px; height: 320px; margin-top: 356px; position: relative">
        <img src="">
        <div class="date-bg date-bg-l">
            <span>1/29</span>
        </div>
    </div>
    <div class="voice-container">
        <span>图标</span>
        <span>60''</span>
    </div>
    <span class="course-introduce">母乳喂养还能减肥？来听李</span>
</div>

<div class="main-container2">
    <div id="mainContainer" class="breast-container">
        @foreach($morningData as $index => $item)
            <a href="index?defaultPath=/courseAudio/{{$item['cid']}}" style="position: relative"
               onclick="CIData.push(['trackEvent', 'wyeth', 'click_good_morning_activity', 'index', {{$index}}]);">
                <img class="breast-group-img" src="{{$item['img']}}">
                <div class="date-bg date-bg-s" style="@if($item['status'] == '缺勤') background: url('{{$su}}/mobile/v2/images/morning_lost.png'); @elseif($item['status'] == '打卡') background: url('{{$su}}/mobile/v2/images/morning_ing.png'); @else background: url('{{$su}}/mobile/v2/images/morning_fur.png'); @endif">
                    {{$item['status']}}
                </div>
            </a>
        @endforeach
    </div>
    <img onclick="window.location.href = '/mobile/index?defaultPath=/all'"
         style="width: 348px; height: auto; margin-top: 73px;"
         src="{{$su}}/mobile/v2/images/morning_reg_more.png">
</div>

<script src="{{$su}}/js/jquery.min.js"></script>
<script src="{{$su}}/js/lodash.min.js"></script>
<script src="{{$su}}/mobile/v2/js/index.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="{{config('course.static_url')}}/js/weixin1.js?v={{$rv}}"></script>

<script>
    var fromOpenid = '{{$openid}}';
    var appUrl = '{{config('app.url')}}';

    var shareUrl = appUrl + '/mobile/S26Card?&from_openid=' + fromOpenid;
    var shareTitle = '魔栗早安';
    var sharePicture = '';

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
            success: function () {
                if (token) {
//                    myRecordShare();
                }
            }
        },
        shareAppData: {
            title: shareTitle,
            link: shareUrl,
            imgUrl: sharePicture,
            desc: shareTitle,
            success: function () {
                if (token) {
//                    myRecordShare();
                }
            }
        }
    };
    WeiXinSDK.init(wxOptions);

    //根据QueryString参数名称获取值

    function getQueryStringByName(name) {
        var result = location.search.match(new RegExp("[\?\&]" + name + "=([^\&]+)", "i"));
        if (result == null || result.length < 1) {
            return "";
        }
        return result[1];
    }
</script>

<!-- baidu statistics -->
@include('public.statistics')
<script>
    CIData.push(['trackEvent', 'wyeth', 'good_morning_channel', 'wyeth_channel', getQueryStringByName('_hw_c')]);
</script>

</body>
</html>