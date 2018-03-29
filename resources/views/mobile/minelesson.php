<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width,user-scalable=no" name="viewport" />
    <link href="<?=config('course.static_url');?>/mobile/css/reset.css?v=<?=$resource_version;?>" rel="styleSheet" type="text/css" />
    <link href="<?=config('course.static_url');?>/mobile/css/lesson.css?v=<?=$resource_version;?>" rel="styleSheet" type="text/css" />
    <title>妈妈微课堂</title>
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
                <img src="<?=config('course.static_url');?>/mobile/images/bany/mineTop.png" alt=""/>
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
                        <img src="<?=config('course.static_url');?>/mobile/images/bany/di.png" alt=""/>
                        <img src="<?=config('course.static_url');?>/mobile/images/bany/0.png" alt=""/>
                        <img src="<?=config('course.static_url');?>/mobile/images/bany/ming.png" alt=""/>
                    </div>
                </div>
                <div class="clear"></div>
            </header>
            <div class="lesson_list" id="thelist">
                <div class="clear"></div>
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
<footer class='fixedMenu'>
    <a href="/mobile/index" hw_t="49" class="lesson"></a>
    <a href="javascript:void(0)" class='discovery'></a>
    <a href="/mobile/mine" hw_t="49" class="mine current"></a>
</footer>
<script src="<?=config('course.static_url');?>/mobile/js/jquery-1.8.3.js"></script>
<script src="<?=config('course.static_url');?>/mobile/js/iscroll.js"></script>
<script src="<?=config('course.static_url');?>/mobile/js/bany.js"></script>
<script src="<?=config('course.static_url');?>/mobile/js/mine.js?v=<?=$resource_version;?>"></script>
<script type="text/tpl">
<!--^lessonLine-->
    <a href="{#nextLink}" class="lessonLineTypeOne" lid="{#cid}">
        <div class="newsCover">
            <img src="{#img}" alt="" class='newsCover' />
        </div>
        <div class="newsContent">
            <h4><div>{#title}</div></h4>
            <p><i class="dateIcon"></i>{#start_day}  {#start_time}-{#end_time}</p>
            <p class="doctor">
                <span>{#teacher_name} {#teacher_position}</span>
                <span>{#teacher_hospital}</span>
            </p>
            <p><i class="likeIcon"></i>{#hot}</p>
            <div class="functionBTN {#btn}"></div>
        </div>
    </a>
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
<script>
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?=$package['appId'];?>', // 必填，企业号的唯一标识，此处填写企业号corpid
        timestamp: <?=$package['timestamp'];?>, // 必填，生成签名的时间戳
        nonceStr: '<?=$package['nonceStr'];?>', // 必填，生成签名的随机串
        signature: '<?=$package['signature'];?>',// 必填，签名，见附录1
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage'
        ]
    });

    var shareUrl = '<?=config('app.url');?>' + '/mobile/index?from_openid='+'<?=$openid;?>';
    wx.ready(function(){
        // 分享朋友圈的数据
        wx.onMenuShareTimeline({
            title: '这就是好妈妈和普通妈妈的区别，课程表满满的！我的[妈妈微课堂]课程表，专业医生讲解1对1答疑，超实用方便！', // 分享标题
            link: _mz_wx_shareUrl(shareUrl), // 分享链接
            imgUrl: '<?=config('course.static_url');?>/mobile/images/mine_share.jpg', // 分享图标
            success:function() {
                Record.timeline(page_name);
            }
        });

        // 分享给好友的数据
        wx.onMenuShareAppMessage({
            title: '妈妈微课堂', // 分享标题
            desc: '这就是好妈妈和普通妈妈的区别，课程表满满的！我的[妈妈微课堂]课程表，专业医生讲解1对1答疑，超实用方便！', // 分享描述
            link: _mz_wx_shareUrl(shareUrl), // 分享链接
            imgUrl: '<?=config('course.static_url');?>/mobile/images/mine_share.jpg', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success:function() {
                Record.friend(page_name);
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

</body>
</html>