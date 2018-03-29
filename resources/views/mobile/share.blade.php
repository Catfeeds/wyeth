@if ($browser == 'WXBrowser')
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="{{config('course.static_url')}}/js/weixin1.js"></script>
<script>
var wxOptions = {
    reqUrl: document.URL,
    shareTimelineData: {
        title: '{{ isset($share) ? $share['desc'] : '魔栗妈咪学院' }}',
        link: '{{ isset($share) ? $share['link'] : 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}',
        imgUrl: '{{ isset($share) ? $share['imgUrl'] : config('course.static_url') . "/mobile/images/lessonlist_share.jpg"}}',
        trigger: function() {},
        success: function() {
            CIData.push(["trackEvent", 'wyeth', 'share', 'cid', 0]);
        },
        cancel: function() {},
        fail: function() {}
    },
    shareAppData: {
        title: '{{ isset($share) ? $share['title'] : '魔栗妈咪学院'}}',
        link: '{{ isset($share) ? $share['link'] : 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] }}',
        imgUrl: '{{ isset($share) ? $share['imgUrl'] : config('course.static_url') . "/mobile/images/lessonlist_share.jpg"}}',
        desc: '{{ isset($share) ? $share['desc'] : '上万新妈孕妈在【魔栗妈咪学院】报名上课啦！每天15分钟轻松学，你也快来吧！'}}',
        trigger: function() {},
        success: function() {
            CIData.push(["trackEvent", 'wyeth', 'share', 'cid', 0]);
        },
        cancel: function() {},
        fail: function() {}
    }
};
WeiXinSDK.init(wxOptions);
</script>

@elseif ($browser == 'SQ')
<script src="http://mp.gtimg.cn/open/js/openApi.js"></script>
<script src="{{config('course.static_url')}}/js/qqshare.js"></script>
<script type="text/javascript">
var sqOptions = {
    reqUrl: document.URL,
    shareQQData: {
        title: '{{$share['desc']}}',
        link: '{{$share['link']}}',
        imgUrl: '{{$share['imgUrl']}}',
        trigger: function() {},
        success: function() {},
        cancel: function() {},
        fail: function() {}
    }
};
QqshareSDK.init(sqOptions);
</script>
@endif
