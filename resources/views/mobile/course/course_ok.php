<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @include('public.head')
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="{{$su}}/mobile/css/sign.css?v={{$rv}}" />
    <title><?php echo '妈妈微课堂' ?></title>
    <style type="text/css">
        body{ background: #ebebeb;}
    </style>
    <!--秒针统计-->
    <script type="text/javascript" src="{{$su}}/mobile/js/jquery-1.8.3.js?v={{$rv}}"></script>
    <script type="text/javascript"  src="<?=config('course.mz_url');?>"></script>
    <script type="text/javascript" src="{{$su}}/mobile/js/record.js"></script>
    <script type="text/javascript">
        var page_name = '老会员报名成功页';
        var page = '<?=$page;?>';
        Record.init({
            static_url: '{{$su}}',
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
</head>
<body>
    <div class="page">
        <div class="content" style="top:0;bottom:0;">
            <div class="talk_list">
                <div class="talk_item" id="talk_item_1">
                    <div class="media">
                        <a href="javascript:void(0)"><img class="anchor_img" src="{{$su}}/mobile/img/image5.jpg" style="" /></a>
                    </div>
                    <div class="talk_main">
                        <div class="body">
                           <div class="angle angle_left">

                           </div>
                           <div class="msg msg_text" id="msg">
                               我是您的私人孕育顾问Miss惠！<br>
                               欢迎报名妈妈微课堂<?php echo date('m月d日', strtotime($course->start_day)); ?>的《<?php echo $course->title; ?>》。<br>
                               <!-- 你可以通过点击“<a href="/mobile/mine" onclick="_mz_wx_custom(54,'老会员我的'); setTimeout(function(){window.open('/mobile/mine','_self');},500); return false;">我的</a>”，查看全部已报名的课程信息。<br>
                               记得要准时来听课哦～ <br>
                               <br>
                               现在，你还可以回到“<a href="/mobile/index" onclick="_mz_wx_custom(55,'老会员摇一摇'); setTimeout(function(){window.open('/mobile/index','_self');},500); return false;">课程列表</a>”，回顾我们过往的课程精彩内容哦！
                                -->
                           </div>
                        </div>
                    </div>
                </div>
                <!--
                <div class="talk_item" id="talk_item_2">
                    <div class="media">
                        <a href="javascript:void(0)"><img class="anchor_img" src="{{$su}}/mobile/img/image5.jpg" style="" /></a>
                    </div>
                    <div class="talk_main">
                        <div class="body">
                           <div class="angle angle_left">

                           </div>
                           <div class="msg msg_text">
                                现在，你还可以回到“<a href="/mobile/index" onclick="_mz_wx_custom(55,'老会员摇一摇'); setTimeout(function(){window.open('/mobile/index','_self');},500); return false;">课程列表</a>”，回顾我们过往的课程精彩内容哦！

                           </div>
                        </div>
                    </div>
                </div>
                -->
                <?php if ($user_type == 1 && $user->display): ?>
                <div class="talk_item" id="talk_item_3">
                    <div class="media">
                        <a href="javascript:void(0)"><img class="anchor_img" src="{{$su}}/mobile/img/image5.jpg" style="" /></a>
                    </div>
                    <div class="talk_main">
                        <div class="body">
                           <div class="angle angle_left">

                           </div>
                           <div class="msg msg_text">
                               <img src="<?=$user->imgurl;?>" style="width:200px">
                           </div>
                        </div>
                    </div>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>

    <div class="unvisible">
        <input id="hidCourseId" type="hidden" value='' />
    </div>

    <script type="text/javascript" src="{{$su}}/mobile/js/page.js?v={{$rv}}"></script>
    <script type="text/javascript">
       $(function(){
           $('.talk_item').show();
//           setTimeout("$('#talk_item_3').show()",500);
//           setTimeout("$('#talk_item_4').show()",2000);
       });
    </script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="{{$su}}/js/lodash.js"></script>
    <script src="{{config('course.static_url')}}/js/weixin1.js"></script>
    <script type="text/javascript">
        var appUrl = '{{config('app.url')}}';
        var cid = '{{$course['id']}}';
        var fromOpenid = '{{$openid}}';
        var shareTitle =  '{{$course['share_title']}}';
        var sharePicture =  '{{$course['share_picture']}}';
        var shareFriendTitle =  '{{$course['firend_title']}}';
        var shareFriendSubtitle =  '{{$course['firend_subtitle']}}';

        var shareUrl = appUrl + '/mobile/reg?cid=' + cid + '&from_openid=' + fromOpenid;
        var wxOptions = {
            debug: false,
            reqUrl: document.URL,
            shareTimelineData: {
                title: shareTitle,
                link: _mz_wx_shareUrl(shareUrl),
                imgUrl: sharePicture,
                success: function() {
                    Record.timeline(page_name);
                }
            },
            shareAppData: {
                title: shareFriendTitle,
                link: _mz_wx_shareUrl(shareUrl),
                imgUrl: sharePicture,
                desc: shareFriendSubtitle,
                success: function() {
                    Record.friend(page_name);
                }
            }
        };
        WeiXinSDK.init(wxOptions);
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
                };
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
            'link':shareUrl,
            'imgUrl':"{{$su}}/mobile/images/logo.jpg",
            'desc':'我发现了一堂好课，在成为好妈妈的路上又近了一步，一起加入好妈妈的行列吧！'
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
