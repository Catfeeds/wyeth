<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>测试jssdk跨域分享</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    @include('public.head')
    <script src="http://opponplusgroup.oss-cn-hangzhou.aliyuncs.com/oppoms/js/jquery-1.11.3.min.js"></script>
    <script src="http://mp.gtimg.cn/open/js/openApi.js"></script>
</head>
<body>
<div class="pages">
    测试jssdk跨域分享,前提是调用如下JS代码的域名必须加入安全域名.
</div>
<script type="text/javascript">
    var url = 'http://'+location.host+location.pathname;
    //var baseurl = 'http://wyeth.qq.nplusgroup.com/phone/wkt/index.htm';
    //var baseurl = 'http://'+location.host+location.pathname;

    $.ajax({
        type: "get",
        url: 'http://wyeth.qq.nplusgroup.com/api/toauth/index.json',
        data:'url='+url+'&callback=?',
        dataType: "jsonp",
        jsonp: "callback",
        success: function(json){
            var mqqConfig = {
                debug:true,
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
            menuList: ['menuItem:share:qq','menuItem:share:QZone','menuItem:share:appMessage'] // 要显示的菜单项，所有menu项见附录3
        });
        share({});
    });

    window.shareparam = {
        'title':'测试jssdk跨域分享',
        'link':url,
        'imgUrl':'http://opponplusgroup.oss-cn-hangzhou.aliyuncs.com/oppoms/images/share.jpg',
        'desc':'测试jssdk跨域分享,前提是调用如下JS代码的域名必须加入安全域名.'
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
                _hmt.push(['_trackEvent', 'share', 'timeline']);
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数

            }
        });

        mqq.onMenuShareQzone({
            title: window.shareparam.title, // 分享标题
            link: window.shareparam.link, // 分享链接
            imgUrl: window.shareparam.imgUrl, // 分享图标
            desc: window.shareparam.desc, // 分享描述
            success: function () {
                _hmt.push(['_trackEvent', 'share', 'timeline']);
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数

            }
        });

        mqq.onMenuShareTimeline({
            title: window.shareparam.title, // 分享标题
            link: window.shareparam.link, // 分享链接
            imgUrl: window.shareparam.imgUrl, // 分享图标
            desc: window.shareparam.desc, // 分享描述
            success: function () {
                _hmt.push(['_trackEvent', 'share', 'timeline']);
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数

            }
        });

        mqq.onMenuShareAppMessage({
            title: window.shareparam.title, // 分享标题
            link: window.shareparam.link, // 分享链接
            imgUrl: window.shareparam.imgUrl, // 分享图标
            desc: window.shareparam.desc, // 分享描述
            success: function () {
                _hmt.push(['_trackEvent', 'share', 'timeline']);
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
