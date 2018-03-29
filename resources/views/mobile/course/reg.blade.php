<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    @include('public.head')
    <link rel="stylesheet" href="{{$su}}/mobile/css/sign.css?v={{$rv}}" />
    <title>妈妈微课堂</title>
    <script type="text/javascript" src="{{$su}}/mobile/js/jquery-1.8.3.js?v={{$rv}}"></script>
    <script type="text/javascript"  src="<?=config('course.mz_url');?>"></script>
    <script src="{{$su}}/mobile/js/record.js"></script>
    <script type="text/javascript">
        var page_name = '报名首页';
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
<?php
$mz_confg = config('miaozhen.course');
$cid = $course['id'];
$config = isset($mz_confg[$cid]) ?: array();
?>
<script>
    _mz_wx_view(<?=isset($config['page'][0]) ?: 0;?>, '详情页');
</script>
    <a href="<?php echo $refer; ?>" onclick="_mz_wx_custom(<?=isset($config['event'][5]) ?: 0;?>); setTimeout(function(){window.open('<?=$refer;?>','_self');},500); return false;"  id="course_back_btn"><img src="img/course_back_btn.png"></a>
    <div class="page">
        <div class="content" id="wrapper">
            <div id="scroller">
            <div class="course">
                <div class="course_head">
                    <img src="<?php echo $course['img']; ?>" id="courseCover" style="width:100%;" />
                    <a href="javascript:void(0)" class="btn btn_share" id="btnShare">
                    </a>
                </div>
                <div class="course_body">
                    <div class="course_info">
                        <h4 class="course_title" id="courseTitle"></h4>
                        <div class="course_time">开课时间: <span id="courseTime"><?php echo $course['start_day'] . "&nbsp;&nbsp;" . substr($course['start_time'], 0, 5) . '-' . substr($course['end_time'], 0, 5); ?></span></div>
                        <div class="course_baby">适龄阶段: <span id="courseFor"><?php echo $course['stage_from'].' - '.$course['stage_to']; ?></span></div>
                        <img class="bg_course_title" src="{{$su}}/mobile/img/bg_course_title.png"/>
                    </div>

                    <div class="course_content" id="courseContent">
                        <?=nl2br($course['desc']);?>
                    </div>

                </div>
            </div>

            <div class="teacher">
                <div class="teacher_body">
                    <div class="teacher_info">
                        <img class="bg_teacher_title" src="{{$su}}/mobile/img/bg_teacher_title.png"/>
                        <div>
                            <div class="teacher_left">
                                <img class="teacher_pic" id="teacherCover" src="<?=$course['teacher_avatar'];?>" alt="">
                            </div>
                            <div class="teacher_right" style="padding-left:1em;">
                                <h4 class="teacher_name" id="teacherName" style="padding-top:0.5em;"><?=$course['teacher_name'];?></h4>
                                <div class="teacher_hospital" id="teacherHospital"><?=$course['teacher_hospital'];?></div>
                                <div class="teacher_title" id="teacherTitle"><?=$course['teacher_position'];?></div>
                            </div>
                            <div class="teacher_content" id="teacherContent">
                                <?=nl2br($course['teacher_desc']);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 这里是二维码显示  -->
            <?php if ($userinfo->display && $userinfo->imgtype == 'qr'): ?>
            <div class="coursetip">
                <div class="coursetip_body">
                    <div style="border-bottom: 1px solid #dcdcdc; width: 92%; margin:0em 4%;"></div>
                    <div class="coursetip_content">
                        <img src="<?=$userinfo->imgurl;?>">
                    </div>
                </div>
            </div>
            <?php endif;?>

            <?php if ($userinfo->display && $userinfo->imgtype == 'ad'): ?>
            <div class="coursetip">
                <div class="coursetip_body">
                    <div style="border-bottom: 1px solid #dcdcdc; width: 92%; margin:0em 4%;"></div>
                    <div class="coursetip_content">
                        <img src="<?=$userinfo->imgurl;?>" style="width: 100%; margin: 0;">
                    </div>
                </div>
            </div>
            <?php endif;?>

            <div class="coursetip">
                <div class="coursetip_body">
                    <div style="border-bottom: 1px solid #dcdcdc; width: 92%; margin:0em 4%;"></div>
                    <div class="coursetip_content" id="courseTip">
                        <div class="item">“魔栗妈咪学院”版权归属景栗科技所有，相关课程内容由景栗科技提供。平台相关内容不作为医学诊断参考，如情况严重，建议及时就医。
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="footer_tianchong"><!--填充漂浮footer--></div>
        <div class="footer">
            <div class="enroll_info">
                <div class="surplus font-bold">剩余 <span id="courseSurplus"><?=($course['left_sign_num'] > 0) ? $course['left_sign_num'] : 0;?></span> 个名额</div>
                <div class="enrolled">已经有 <span id="courseLimit"><?php echo $course['sign_num']; ?></span> 位妈妈报名</div>
            </div>
            <?php $btn_class = $user['is_signed'] != 0 ? 'btn_enroll_disable' : ($course['left_sign_num'] > 0 ? '' : 'btn_enroll_limit'); ?>
            <a class="btn btn_enroll <?=$btn_class;?>" id="btnEnroll"></a>
        </div>
    </div>

    <div class="unvisible">
    </div>

    <div class="share_dialog">
        <div class="overlay"></div>
        <div class="box">
            <a class="close" id="btnClose"></a>
            <img src="{{$su}}/mobile/img/bg_share_dialog.png" style="width:100%;" />
        </div>
    </div>

<script src="{{$su}}/mobile/js/page.js?v={{$rv}}"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="{{$su}}/js/lodash.js"></script>
<script src="{{config('course.static_url')}}/js/weixin1.js?v={{$rv}}"></script>
<script type="text/javascript">
    $(window).load(function (e) {
        console.log(sessionStorage.getItem("flag"));
        if (sessionStorage.getItem("flag") == '{{$course['id']}}') {
            $("#btnEnroll").addClass("btn_enroll_disable disable");
            $("#btnEnroll").removeClass("btn_enroll_limit");
            sessionStorage.clear();
        }
    })

</script>
<script type="text/javascript">
    var appUrl = '{{config('app.url')}}';
    var cid = '{{$course['id']}}';
    var fromOpenid = '{{$openid}}';
    var shareTitle =  '{{$course['share_title']}}';
    var sharePicture =  '{{$course['share_picture']}}';
    var shareFriendTitle =  '{{$course['firend_title']}}';
    var shareFriendSubtitle =  '{{$course['firend_subtitle']}}';

    var token;
    $.getJSON('/token', function (data) {
        if ('token' in data) {
            token = data.token;
        }
    });
    $(document).ready(function(){
        $.ajaxSetup({
            beforeSend: function(xhr) {
                if (!token) {
                    console.log('token empty before ajax send');
                    return false;
                }
                xhr.setRequestHeader('Authorization', 'bearer ' + token);
            }
        });

    });

    var is_crmmember = "<?=$user['is_crmmember'];?>";
    var is_subscribed = "<?=$user['is_subscribed'];?>";
    var is_signed = "<?=$user['is_signed'];?>";

    //分享次数自己统计
    function myRecordShare () {
            var cid = '<?php echo $cid; ?>';
            CIData.push(["trackEvent", 'wyeth', 'share', 'cid', cid]);
            $.ajax({
                type: "POST",
                url: "/api/course/share",
                data: { cid: cid, type: 1}
            });
        }

    $(function(){
        $.ajaxSetup({
            beforeSend: function(xhr) {
                if (!token) {
                    console.log('token empty before ajax send');
                    return false;
                }
                xhr.setRequestHeader('Authorization', 'bearer ' + token);
            }
        });

        $("#btnShare").click(function(){
            $(".share_dialog").show();
        });
        $("#btnClose").click(function(){
            $(".share_dialog").hide();
        });

        setTimeout("StartAnimate()",500);
        if(is_signed == 0){
            $("#btnEnroll").on("click", function(){
                if($(this).hasClass("btn_enroll_limit")){
                   window.location.href = "/mobile/index";
                   return;
                }

                if($(this).hasClass("disable")){
                    return;
                }
                $(this).addClass("disable");
                //未分享
                //测试未分享
                <?php if ($user['is_shared'] == 0) {?>
                   $("div.share_dialog").show();
                   $("#btnClose").on("click", function(){
                       //秒针测试代码，
                       _mz_wx_custom(<?=isset($config['event'][3]) ?: 0;?>,'我要分享');

                       JumpUrl();
                   });
                <?php } else {?>
                    //秒针测试代码，
                    _mz_wx_custom(<?=isset($config['event'][4]) ?: 0;?>,'我要报名');

                    JumpUrl();
                <?php }
?>
                $(this).removeClass("disable");
            });
        }
    });

    /*
     * 是否关注 -> 不是 -> 引导关注
     *            是--> 继续
     * crm -->    是 --> 直接提示报名成功
     *        --> 不是 跳转进入报名页
     */

   function JumpUrl(){
       if(is_subscribed == 1 && is_crmmember == 1){
           $.ajax({
               type:"get",
               url: "/api/course/crmSign?cid=<?php echo $course['id']; ?>&uid=<?php echo $user['uid']; ?>",
               data:{},
               dataType:"json",
               success:function(response) {
                   if(response.status == 1){
                       window.location.href = response.data.url;
                   }else{
                       ShowAlert(response.error_msg);
                   }
                   return false;
               },
               error:function() {
                   ShowAlert("提交报名信息出错！");
               }
           });
       }

       if( is_subscribed == 0) {
           window.location.href = "/mobile/attention?cid=<?php echo $course['id']; ?>&uid=<?php echo $user['uid']; ?>";
           return;
       } else {
           if(is_crmmember == 0) {
               window.location.href = "/mobile/card?cid=<?php echo $course['id']; ?>&uid=<?php echo $user['uid']; ?>";
               return;
           }
       }
   }

   function StartAnimate(){
       $("#btnShare").addClass("btn_share_animate");
   }

    var shareUrl = appUrl + '/mobile/reg?cid=' + cid + '&from_openid=' + fromOpenid;
    var wxOptions = {
        debug: false,
        reqUrl: document.URL,
        shareTimelineData: {
            title: shareTitle,
            link: _mz_wx_shareUrl(shareUrl),
            imgUrl: sharePicture,
            success: function() {
                //Record.timeline(page_name,'');
                _mz_wx_timeline();
                if (token) {
                    myRecordShare ();
                }
            }
        },
        shareAppData: {
            title: shareFriendTitle,
            link: _mz_wx_shareUrl(shareUrl),
            imgUrl: sharePicture,
            desc: shareFriendSubtitle,
            success: function() {
                //Record.friend(page_name,'');
                _mz_wx_friend();
                if (token) {
                    myRecordShare ();
                }
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
        'title':'<?=$course['firend_title'];?>',
        'link': 'http://wyeth.qq.nplusgroup.com/phone/wkt/d-reg-<?=$course['id'];?>.htm',
        'imgUrl':"<?=$course['share_picture'];?>",
        'desc':'<?=$course['firend_subtitle'];?>'
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
<script>
    CIData.push(['trackEvent', 'page', 'page_reg']);
</script>
</body>
</html>
