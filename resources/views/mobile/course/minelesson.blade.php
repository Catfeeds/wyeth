<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width,user-scalable=no" name="viewport" />
    @include('public.head')
    <link href="<?=config('course.static_url');?>/mobile/css/reset.css?v=<?=$resource_version;?>" rel="styleSheet" type="text/css" />
    <link href="<?=config('course.static_url');?>/mobile/css/lesson.css?v=<?=$resource_version;?>" rel="styleSheet" type="text/css" />
    <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/dropload.css">
    <title>魔栗妈咪学院</title>
    <script>
        var UID = <?=$user->id;?>;
        var STATIC_URL = '<?=config('course.static_url');?>';
    </script>
</head>
<body>
<div class="lessonList">
    <div class="lessonListInner" id="lessonScroller">
        <div class="lessonScroller common-list-scroller" >
            <header class='lessonUserTop'>
                <img src="<?=config('course.static_url');?>/mobile/images/bany/mytop.png" alt=""/>
                <img src="<?=!empty($user['avatar']) ? $user['avatar'] : 'http://7xk3aj.com1.z0.glb.clouddn.com/pic.jpg';?>" alt="" class="userPhoto"/>
                <p class="userName"><?=$user['nickname'];?></p>
                <div class="clear"></div>
            </header>
            <header class='lessonTop myLessonTop'>
                <img src="<?=config('course.static_url');?>/mobile/images/bany/mineTop_new.png" alt=""/>
                <div class="mineTimes mineTop">
                    <div class="keepcenter cishu">
                        <img src="<?=config('course.static_url');?>/mobile/images/bany/0.png" alt=""/>
                        <img src="<?=config('course.static_url');?>/mobile/images/bany/ci.png" alt=""/>
                    </div>
                </div>
                <div class="mineTime mineTop">
                    <div class="keepcenter shichang">
                        <img src="<?=config('course.static_url');?>/mobile/images/bany/0.png" alt=""/>
                        <img src="<?=config('course.static_url');?>/mobile/images/bany/shi.png" alt=""/>
                        <img src="<?=config('course.static_url');?>/mobile/images/bany/0.png" alt=""/>
                        <img src="<?=config('course.static_url');?>/mobile/images/bany/fen.png" alt=""/>
                    </div>
                </div>
                <div class="mineRank mineTop">
                    <div class="keepcenter paiming">
                        <img src="<?=config('course.static_url');?>/mobile/images/bany/0.png" alt=""/>
                    </div>
                </div>
                <div class="clear"></div>
            </header>
            <div class="lesson_list" id="thelist">
                <div class="clear" id="courseClear"></div>
            </div>
            <div id="pullUp" class="common-list-pull">
                <span class="pullUpIcon"></span><span class="pullUpLabel">上拉获取更多...</span>
            </div>
            <div class="nomore" style="display:none">
                <p>亲，你还没报名任何课程哦</p>
                <a href="/mobile/index"><img src="<?=config('course.static_url');?>/mobile/images/bany/rightway.png" alt=""/></a>
            </div>
            <div class="clear"></div>
        </div>
    </div>

</div>
<div class="footerLine"></div>

<!-- menu -->
<style type="text/css">
.bar {
    overflow:hidden;
    position: fixed;
    right: 0;
    left: 0;
    bottom:0px;
    z-index: 10;
    color:#fe6c01;
    background-image:url(<?=config('course.static_url');?>/mobile/v2/img/mine.png);
    background-size:100% 100%;
    background-repeat:no-repeat;
    height:8vh;
}
.bar a{
    display:block;
    float:left;
    width:33.333%;
    height:100%;
    text-align:center;
    font-size:12px;
    overflow:hidden;
    color:#ffac28;
}
.active{
    color:white;
}
</style>

@if(isset($user_version) && $user_version != -1)
    <div style="width: 100vw;height: 2.666vw;position: fixed;bottom: 13.33vw;background-image: linear-gradient(to top, rgba(0,0,0,0.08), rgba(255,255,255,0));"></div>
    <div style="width: 100vw;height: 13.33vw;background-color: white;position: fixed;bottom: 0px;flex-direction: row;align-items:stretch;display: flex">
        <div data="index" class="wyeth-tab-item" href="javascript:void(0)" style="display:flex;flex: 1;flex-direction: column;align-items: center;justify-content: center">
            <img src="<?=config('course.static_url');?>/mobile/images/bany/home2.png"
                 style="width: 5.86vw;height: 5.86vw"
                 alt=""/>
            <p style="display:flex;margin-top:0.6vw;margin-bottom:0px;align-items: center;justify-content: center;font-size: 2.666vw;color: #666666">
                首页</p>
        </div>

        <div data="all" class="wyeth-tab-item" href="javascript:void(0)" style="display:flex;flex: 1;flex-direction: column;align-items: center;justify-content: center">
            <img src="<?=config('course.static_url');?>/mobile/images/bany/all2.png"
                 style="width: 5.86vw;height: 5.86vw"
                 alt=""/>
            <p style="display:flex;margin-top:0.6vw;margin-bottom:0px;align-items: center;justify-content: center;font-size: 2.666vw;color: #666666">
                全部</p>
        </div>

        <div data="mine" class="wyeth-tab-item" href="javascript:void(0)" style="display:flex;flex: 1;flex-direction: column;align-items: center;justify-content: center">
            <img src="<?=config('course.static_url');?>/mobile/images/bany/mine.png"
                 style="width: 5.86vw;height: 5.86vw"
                 alt=""/>
            <p style="display:flex;margin-top:0.6vw;margin-bottom:0px;align-items: center;justify-content: center;font-size: 2.666vw;color: #cd9e29">
                我的</p>
        </div>
    </div>
@else
    <div class="bar bar-tab footer-nav">
        <a class="tab-item" href="javascript:void(0)" data="index">
            <div class="icon icon-nav1"></div>
        </a>
        <a class="tab-item" href="javascript:void(0)" data="all">
            <div class="icon icon-nav1"></div>
        </a>
        <a class="tab-item active" href="javascript:void(0)" data="mine">
            <div class="icon icon-nav1"></div>
        </a>
    </div>
@endif

<script src="<?=config('course.static_url');?>/mobile/js/jquery-1.8.3.js"></script>
<script src="{{config('course.static_url')}}/mobile/v2/js/dropload.js"></script>

<script type="text/tpl">
<!--^lessonLine-->
    <a href="{#nextLink}" class="lessonLineTypeOne" lid="{#cid}">
        <div class="newsCover">
            <img src="{#img}" alt="" class='newsCover' />
        </div>
        <div class="newsContent">
            <h4>{#title}</h4>
            <p><i class="dateIcon"></i>{#start_day}  {#start_time}-{#end_time}</p>
            <p class="doctor">
                <span>{#teacher_name} {#teacher_position}</span>
                <span>{#teacher_hospital}</span>
            </p>
            <p><i class="likeIcon"></i>{#hot}</p>
            <div class="functionBTN {#btn}"></div>
        </div>
    </a>
    {{--<div class="lessonLineTypeOne" lid="{#cid}">--}}
        {{--<a href="{#nextLink}" class="newsCover">--}}
            {{--<img src="{#img}" alt="" class='newsCover' />--}}
        {{--</a>--}}
        {{--<div class="newsContent">--}}
            {{--<h4><a href="{#nextLink}">{#title}</a></h4>--}}
            {{--<p><i class="dateIcon"></i>{#start_day}  {#start_time}-{#end_time}</p>--}}
            {{--<p class="doctor">--}}
                {{--<span>{#teacher_name} {#teacher_position}</span>--}}
                {{--<span>{#teacher_hospital}</span>--}}
            {{--</p>--}}
            {{--<p><i class="likeIcon"></i>{#hot}</p>--}}
            {{--<a href="{#nextLink}" class="functionBTN {#btn}"></a>--}}
        {{--</div>--}}
    {{--</div>--}}
<!--lessonLine$-->
<!--^back-->
     <div class="myLessonLine" lid="{#course.cid}">
        <img src="{#course.img}" alt="" class="myLessonCover"/>
        <div class="myLessonInfo">
            <a href="#" class="myLessonTitle">{#course.title}</a>
            <p class="myLessonTime"><span class="dateIcon"></span> 开课时间:{#course.start_day}  {#course.start_time}-{#course.end_time}</p>
        </div>
        <span class="baoming baoming{#course|star}"></span>
        <div class="clear"></div>
    </div>
<!--back$-->
</script>
<!--统计-->
<script type="text/javascript"  src="<?=config('course.mz_url');?>"></script>
<script src="<?=config('course.static_url');?>/mobile/js/record.js"></script>
<script type="text/javascript">
    var page_name = '我的课程页';
    var page = '<?=$page;?>';
    Record.init({
        static_url: '<?=config('course.static_url');?>',
        mz: {
            site_id: '<?=config('record.mz_siteid');?>',
            openid: '<?=$openid;?>'
        },
        dc: {
            appid: '<?=config('record.dc_appid');?>'
        },
        channel: '<?=$channel;?>',
        uid: '<?=$uid;?>'
    });
    Record.page(page_name, {}, page);
</script>


<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="{{$su}}/js/lodash.js"></script>
<script src="{{config('course.static_url')}}/js/weixin1.js?v={{$rv}}"></script>
<script type="text/javascript">
    var appUrl = '{{config('app.url')}}';
    var fromOpenid = '{{$openid}}';
    var shareTitle =  '这就是好妈妈和普通妈妈的区别，课程表满满的！我的[妈妈微课堂]课程表，专业医生讲解1对1答疑，超实用方便！';
    var sharePicture =  STATIC_URL + '/mobile/images/mine_share.jpg';
    var shareFriendTitle =  '妈妈微课堂';
    var shareFriendSubtitle =  '这就是好妈妈和普通妈妈的区别，课程表满满的！我的[妈妈微课堂]课程表，专业医生讲解1对1答疑，超实用方便！';

    var shareUrl = appUrl + '/mobile/index?from_openid=' + fromOpenid;
    var wxOptions = {
        debug: false,
        reqUrl: document.URL,
        shareTimelineData: {
            title: shareTitle,
            link: _mz_wx_shareUrl(shareUrl),
            imgUrl: sharePicture,
            success: function() {
                CIData.push(["trackEvent", 'wyeth', 'share', 'cid', 0]);
                Record.timeline(page_name);
            }
        },
        shareAppData: {
            title: shareFriendTitle,
            link: _mz_wx_shareUrl(shareUrl),
            imgUrl: sharePicture,
            desc: shareFriendSubtitle,
            success: function() {
                CIData.push(["trackEvent", 'wyeth', 'share', 'cid', 0]);
                //Record.friend(page_name,'');
                _mz_wx_friend();
                if (token) {
                    Record.friend(page_name);
                }
            }
        }
    };
    WeiXinSDK.init(wxOptions);
</script>
<script src="{{config('course.static_url')}}/mobile/js/mine.js?v=<?=$resource_version;?>"></script>
<script src="http://mp.gtimg.cn/open/js/openApi.js"></script>
<script type="text/javascript">
    $(function(){
        $('.footer-nav .tab-item').on("touchstart", function(){
            $('.tab-item').removeClass('active');
            var url = $(this).attr('data');
            $(this).addClass('active');
            location.href = "/mobile/"+url;
        })
    })
    $(function(){
        $('.wyeth-tab-item').on("touchstart", function(){
            $('.tab-item').removeClass('active');
            var url = $(this).attr('data');
            $(this).addClass('active');
            if(url=='index'){
                location.href = '/mobile/index/'
            }else{
                location.href = "/mobile/" + url;
            }
        })
    })
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
        'title':'妈妈微课堂',
        'link': 'http://wyeth.qq.nplusgroup.com/phone/wkt/page-mine.htm',
        'imgUrl':"<?=config('course.static_url');?>/mobile/images/mine_share.jpg",
        'desc':'这就是好妈妈和普通妈妈的区别，课程表满满的！我的[妈妈微课堂]课程表，专业医生讲解1对1答疑，超实用方便！'
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
