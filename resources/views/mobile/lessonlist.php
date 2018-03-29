<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width,user-scalable=no" name="viewport" />
        <meta name="format-detection" content="telephone=no" />
        <link href="<?=config('course.static_url');?>/mobile/css/reset.css?v=<?=$resource_version;?>" rel="styleSheet" type="text/css" />
        <link href="<?=config('course.static_url');?>/mobile/css/lesson.css?v=<?=$resource_version;?>" rel="styleSheet" type="text/css" />
        <script>
            var STATIC_URL = '<?=config('course.static_url');?>';
        </script>
        <title>妈妈微课堂</title>
    </head>
<body>
<!--秒针分页监控-->
<header class='lessonTop'>
    <img src="<?=config('course.static_url');?>/mobile/images/bany/top.png" alt=""/>
    <?php
        $first_month_str = $data[0]['start_day'];
        $first_month = substr($first_month_str, 5, 2);
        $pre_month = $first_month;
    ?>
    <img src="<?=config('course.static_url');?>/mobile/images/bany/<?=$first_month;?>month.png" alt=""/>
    <div class="clear"></div>
</header>

<?php
$mz_confg = config('miaozhen.course');
?>
<audio id="audio" src="" > </audio>
<div class="ad_tc" id="ad_tc" hw_ad_tc style="display: none;">
    <img class="ad_img" id="ad_img" src="<?=config('course.static_url');?>/mobile/img/guide_clause.png" />
    <img class="ad_img_fixed" id="ad_img_fixed" src="<?=config('course.static_url');?>/mobile/img/guide_relief.png" />
    <div class="ad_click_btn" id="ad_click_btn"></div>
</div>
<div class="lessonList" id="thelist" >
    <?php $my_counter = 0;?>
    <?php if ($data): ?>
    <?php foreach ($data as $row): ?>
    <?php
    $cid = $row['cid'];
    $config = isset($mz_confg[$cid]) ? : array();
    if($row['status'] == 1){
        $status_str = ($row['is_signed'] == 1)?"regend":"regstart";
        $status = "reg";
    }else if($row['status'] == 2){
        $status_str = "regplaying";
        $status = "living";
    }else{
        $status_str = "regreview";
        $status = "end";
    }
    $my_counter++;

    if($cid != 40){
        $date_str = $row['start_day'];
        $month = substr($date_str, 5, 2);
    ?>
    <?php if ($month != $pre_month){ ?>
    <img class="month_title" src="<?=config('course.static_url');?>/mobile/images/bany/<?=$month;?>month.png" alt=""/>
    <?php }
        $pre_month = $month;
    ?>
    <div class="lessonLineTypeOne">
        <a href="/mobile/<?=$status;?>?cid=<?=$row['cid'];?>" hw_t="<?=isset($config['event'][0])?:0;?>" class="newsCover">
            <img src="<?=$row['img'];?>" alt="" class='newsCover' />
        </a>
        <div class="newsContent">
            <h4><a href="/mobile/<?=$status;?>?cid=<?=$row['cid'];?>" hw_t="<?=isset($config['event'][1])?:0;?>"><?=$row['title'];?></a></h4>
            <p><i class="dateIcon"></i><?=$row['start_day'];?>  <?=$row['start_time'];?>-<?=$row['end_time'];?></p>
            <p class="doctor">
                <span><?=$row['teacher_name'];?> <?=$row['teacher_position'];?></span>
                <span><?=$row['teacher_hospital'];?></span>
            </p>
            <p><i class="likeIcon"></i><?=$row['hot'];?></p>
            <a href="/mobile/<?=$status;?>?cid=<?=$row['cid'];?>" class="functionBTN <?=$status_str;?>" hw_t="<?=isset($config['event'][2])?:0;?>"></a>
        </div>
    </div>
    <?php }?>
    <?php endforeach;?>
    <?php endif;?>
<div class="lessonLineTypeOne">
    <div class="newsCover"><img src="<?=config('course.static_url');?>/mobile/img/8.jpg" alt="" class='newsCover' /></div>
    <div class="newsContent">
        <h4><a>如何合理搭配宝宝的膳食</a></h4>
        <p><i class="dateIcon"></i>2015-11-19 21:00-22:00</p>
        <p class="doctor">
            <span>朱国伟 副主任医师</span>
            <span>上海市徐汇区妇幼保健所</span>
        </p>
        <p><i class="likeIcon1"></i></p>
        <a class="functionBTN regreview" hw_voice_play_btn data-src="http://7xk3aj.com1.z0.glb.clouddn.com/8_wethy_review.mp3"></a>
        <div hw_voice_animation class="voice ds-n" >
            <div class="circle_1 circle_1_animation"></div>
            <div class="circle_2 circle_2_animation"></div>
            <div class="circle_3 circle_3_animation"></div>
            <div class="circle_4 circle_4_animation"></div>
        </div>
    </div>
</div>

<div class="lessonLineTypeOne">
    <div class="newsCover"><img src="<?=config('course.static_url');?>/mobile/img/7.jpg" alt="" class='newsCover' /></div>
    <div class="newsContent">
        <h4><a>宝宝乖乖吃饭的小秘诀</a></h4>
        <p><i class="dateIcon"></i>2015-11-15 20:00-21:00</p>
        <p class="doctor">
            <span>马晓晖 主任医师</span>
            <span>大连儿童医院</span>
        </p>
        <p><i class="likeIcon1"></i></p>
        <a class="functionBTN regreview" hw_voice_play_btn data-src="http://7xk3aj.com1.z0.glb.clouddn.com/review_7.mp3"></a>
        <div hw_voice_animation class="voice ds-n" >
            <div class="circle_1 circle_1_animation"></div>
            <div class="circle_2 circle_2_animation"></div>
            <div class="circle_3 circle_3_animation"></div>
            <div class="circle_4 circle_4_animation"></div>
        </div>
    </div>
</div>

<div class="lessonLineTypeOne">
    <div class="newsCover"><img src="<?=config('course.static_url');?>/mobile/img/6.jpg" alt="" class='newsCover' /></div>
    <div class="newsContent">
        <h4><a>教你轻松应对疱疹性咽峡炎</a></h4>
        <p><i class="dateIcon"></i>2015-11-8 20:00-21:00</p>
        <p class="doctor">
            <span>洪亮 主任医师</span>
            <span>北京同仁医院</span>
        </p>
        <p><i class="likeIcon1"></i></p>
        <a class="functionBTN regreview" hw_voice_play_btn data-src="http://7xk3aj.com1.z0.glb.clouddn.com/6_wethy_review.mp3"></a>
        <div hw_voice_animation class="voice ds-n" >
            <div class="circle_1 circle_1_animation"></div>
            <div class="circle_2 circle_2_animation"></div>
            <div class="circle_3 circle_3_animation"></div>
            <div class="circle_4 circle_4_animation"></div>
        </div>
    </div>
</div>

<div class="lessonLineTypeOne">
    <div class="newsCover"><img src="<?=config('course.static_url');?>/mobile/img/5.jpg" alt="" class='newsCover' /></div>
    <div class="newsContent">
        <h4><a> 产后不敢“招惹”的妇科疾病</a></h4>
        <p><i class="dateIcon"></i>2015-11-1 20:00-21:00</p>
        <p class="doctor">
            <span>王蕾 妇产科主治医师</span>
            <span>北京美中宜和妇儿医院</span>
        </p>
        <p><i class="likeIcon1"></i></p>
        <a class="functionBTN regreview" hw_voice_play_btn data-src="http://7xk3aj.com1.z0.glb.clouddn.com/5_wethy_review.mp3"></a>
        <div hw_voice_animation class="voice ds-n" >
            <div class="circle_1 circle_1_animation"></div>
            <div class="circle_2 circle_2_animation"></div>
            <div class="circle_3 circle_3_animation"></div>
            <div class="circle_4 circle_4_animation"></div>
        </div>
    </div>
</div>
<img class="month_title" src="<?=config('course.static_url');?>/mobile/images/bany/oct.png" alt=""/>
<div class="lessonLineTypeOne">
    <div class="newsCover"><img src="<?=config('course.static_url');?>/mobile/img/4.jpg" alt="" class='newsCover' /></div>
    <div class="newsContent">
        <h4><a>说不清道不明，宝宝的“肚肚痛”</a></h4>
        <p><i class="dateIcon"></i>2015-10-25 20:00-21:00</p>
        <p class="doctor">
            <span>侯尚文 主治医师</span>
            <span>北京和睦家医院</span>
        </p>
        <p><i class="likeIcon1"></i></p>
        <a class="functionBTN regreview" hw_voice_play_btn data-src="http://7xk3aj.com1.z0.glb.clouddn.com/review_4.mp3"></a>
        <div hw_voice_animation class="voice ds-n" >
            <div class="circle_1 circle_1_animation"></div>
            <div class="circle_2 circle_2_animation"></div>
            <div class="circle_3 circle_3_animation"></div>
            <div class="circle_4 circle_4_animation"></div>
        </div>
    </div>
</div>

<div class="lessonLineTypeOne">
    <div class="newsCover"><img src="<?=config('course.static_url');?>/mobile/img/3.jpg" alt="" class='newsCover' /></div>
    <div class="newsContent">
        <h4><a> 关于“咳嗽”的小故事</a></h4>
        <p><i class="dateIcon"></i>2015-10-18 22:00-21:00</p>
        <p class="doctor">
            <span>张寒冰 儿童保健主治医师</span>
            <span>北京和睦家医院</span>
        </p>
        <p><i class="likeIcon1"></i></p>
        <a class="functionBTN regreview" hw_voice_play_btn data-src="http://7xk3aj.com1.z0.glb.clouddn.com/review_3.mp3"></a>
        <div hw_voice_animation class="voice ds-n" >
            <div class="circle_1 circle_1_animation"></div>
            <div class="circle_2 circle_2_animation"></div>
            <div class="circle_3 circle_3_animation"></div>
            <div class="circle_4 circle_4_animation"></div>
        </div>
    </div>
</div>
    <div class="clear"></div>
</div>
<!--<div class="lookout">-->
<!--    <img src="images/bany/bottom_01.png" alt=""/>-->
<!--    <div class="qr">-->
<!--        <img src="images/bany/qr.png" alt=""/>-->
<!--    </div>-->
<!--    <img src="images/bany/bottom_02.png" alt=""/>-->
<!--    <div class="clear"></div>    -->
<!--</div>-->
<div class="lastLesson" >
    <div class="item">“魔栗妈咪学院”版权归属景栗科技所有，相关课程内容由景栗科技提供。平台相关内容不作为医学诊断参考，如情况严重，建议及时就医。
    </div>
</div>
<div class="footerLine"></div>
<footer class='fixedMenu'>
    <a href="/mobile/index" hw_t="49" class="lesson current"></a>
    <a href="javascript:void(0)" class='discovery'></a>
    <a href="/mobile/mine" hw_t="50" class="mine"></a>
</footer>

<script type="text/tpl">
    <!--^lessonMonth-->
    <div class="lessonMonth lessonMonth{#month}">
        <div class="date date1"></div>
        <div class="number">
            <div class="numerBack">
                {#date}
            </div>
        </div>
    </div>
    <!--lessonMonth$-->
    <!--^newsBlock-->
    <div class="newsBlock">
        <a href="/mobile/{#course|star}?cid={#course.cid}">
        <div class="newsHead">
            <img src="{#course.img}" alt="" class='newsCover' />
            <p class='newsCoverText'>{#course.title}</p>
            <span class="star {#course|star}"></span>
        </div>
        </a>
        <div class="newsContent">
            <p class='newsBorder'>{#teacher.name} <span class="yellow">|</span> {#teacher.hospital}{#teacher.position}</p>
            <p><span class="dateIcon"></span>开课时间：{#course.start_day}  {#course.start_time}-{#course.end_time}</p>
            <p><span class="babyIcon"></span>适龄阶段：{#course.stage}</p>
        </div>
    </div>
    <!--newsBlock$-->
</script>

<script src="<?=config('course.static_url');?>/mobile/js/jquery-1.8.3.js"></script>
<script src="<?=config('course.static_url');?>/mobile/js/iscroll.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<!--秒针统计-->
<script type="text/javascript"  src="<?=config('course.mz_url');?>"></script>
<script src="<?=config('course.static_url');?>/mobile/js/record.js"></script>

<!--秒针统计-->

<script>
    var page_name = '首页';
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

    $(function() {
        // a 连接的统计
        $('body').on('click', '[hw_t]', function(e) {
            $currentTarget = $(e.currentTarget);
            var id = $currentTarget.attr('hw_t');
            _mz_wx_custom(id);
            setTimeout(function() {
                // 注意 dc 统计不了这里的数据
                window.location = $currentTarget.attr('href');
            }, 500);
            return false;
        });
    });

    // 微信分享配置
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

    var shareUrl = '<?=config('app.url');?>' + '/mobile/index'+'?from_openid='+'<?=$openid;?>';
    wx.ready(function(){
        // 分享朋友圈的数据
        wx.onMenuShareTimeline({
            title: '有了这些护理知识，宝宝再也不用担心我手忙脚乱了～在线妈妈微课堂，专业医生讲解1对1答疑，超实用方便！收藏！', // 分享标题
            link: _mz_wx_shareUrl(shareUrl), // 分享链接
            imgUrl: '<?=config('course.static_url');?>/mobile/images/lessonlist_share.jpg', // 分享图标
            success:function() {
                Record.timeline(page_name);
            }
        });

        // 分享给好友的数据
        wx.onMenuShareAppMessage({
            title: '妈妈微课堂', // 分享标题
            desc: '有了这些护理知识，宝宝再也不用担心我手忙脚乱了～在线妈妈微课堂，专业医生讲解1对1答疑，超实用方便！收藏！', // 分享描述
            link: _mz_wx_shareUrl(shareUrl), // 分享链接
            imgUrl: '<?=config('course.static_url');?>/mobile/images/lessonlist_share.jpg', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success:function() {
                Record.friend(page_name);
            }
        });
    });

    var token;

    $(function() {
        function getGuidePage () {
            /*
            //获取当前时间 “年月日”
            var myDate = new Date();
            var nowTime = myDate.getFullYear() + "-" + (myDate.getMonth() + 1) + "-" + myDate.getDate();
            //localStorage中添加进入首页状态，为当前年月日，控制弹出层每日显示一次

            if (!localStorage.hw_lessonlist_state || localStorage.hw_lessonlist_state != nowTime ) {
                setTimeout(function () {
                    $('#ad_tc').hide();
                    localStorage.hw_lessonlist_state = nowTime;
                    console.log(localStorage.hw_lessonlist_state);
                }, 3000);
            } else {
                $('#ad_tc').hide();
                console.log(localStorage.hw_lessonlist_state);
            }
            */
            $('#ad_click_btn').on('click', function () {
                $('#ad_tc').hide();
            });
            if (localStorage.hw_lessonlist_state) {
                $('#ad_tc').hide();
            } else {
                $('#ad_tc').show();
                $('#thelist').hide();
                localStorage.setItem('hw_lessonlist_state', '1');
                setTimeout(function () {
                    $('#ad_tc').hide();
                    $('#thelist').show();
                }, 3000);
            }
        }
        getGuidePage ();
        $.getJSON('/token', function (data) {
            if ('token' in data) {
                token = data.token;
                $.ajaxSetup({
                    beforeSend: function(xhr) {
                        if (!token) {
                            console.log('token empty before ajax send');
                            return false;
                        }
                        xhr.setRequestHeader('Authorization', 'bearer ' + token);
                    }
                });
            }
        });
        var play = $('#audio')[0];

        $('[hw_voice_play_btn]').on('click', function() {
            Record.event(page_name + 'voice_play');
            if($(this).data('src') != play.src) {
                $('[hw_voice_animation]').addClass('ds-n');
                $(this).next().removeClass('ds-n');
                play.src = $(this).data('src');
                $(play).data('play', '1');
                play.load();
                play.play();
            } else {
                if($(play).data('play') == 1) {
                    $(this).next().addClass('ds-n');
                    $(play).data('play', '0');
                    play.pause();
                } else {
                    $(this).next().removeClass('ds-n');
                    $(play).data('play', '1');
                    play.play();
                }
            }
        });

        $(play).on('ended', function () {
            $('[hw_voice_animation]').addClass('ds-n');
            $(play).data('play', '0');
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
        'link': 'http://wyeth.qq.nplusgroup.com/phone/wkt/page-index.htm',
        'imgUrl':"<?=config('course.static_url');?>/mobile/images/lessonlist_share.jpg",
        'desc':'有了这些护理知识，宝宝再也不用担心我手忙脚乱了～在线妈妈微课堂，专业医生讲解1对1答疑，超实用方便！收藏！'
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