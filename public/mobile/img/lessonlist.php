<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width,user-scalable=no" name="viewport" />
        <link href="css/reset.css" rel="styleSheet" type="text/css" />
        <link href="css/lesson.css" rel="styleSheet" type="text/css" />
        <!--秒针统计-->
        <script type="text/javascript" src="http://js.miaozhen.com/wx.1.0.js"></script>
        <script type="text/javascript">
            _mwx=window._mwx||{};
            _mwx.siteId=8000330;
            _mwx.openId='<?=$openid;?>'; //OpenID为微信提供的用户唯一标识,需要开发者传入，如果没有OpenID，去掉该代码即可。
//            _mwx.debug=true;//代码调试阶段，加入此代码，正式上线之后去掉该代码
        </script>
        <!--秒针统计-->
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

            var shareUrl = '<?=config('app.url');?>' + '/mobile/index'+'?from_openid='+'<?=$openid;?>';
            wx.ready(function(){
                // 分享朋友圈的数据
                wx.onMenuShareTimeline({
                    title: '妈妈微课堂开课啦，听专家医生教我们最正确的育儿方式。', // 分享标题
                    link: _mz_wx_shareUrl(shareUrl), // 分享链接
                    imgUrl: '<?=config('app.url');?>/mobile/images/logo.jpg', // 分享图标
                    success:function() {
                        _mz_wx_timeline();
                    }
                });

                // 分享给好友的数据
                wx.onMenuShareAppMessage({
                    title: '妈妈微课堂', // 分享标题
                    desc: '妈妈微课堂开课啦，听专家医生教我们最正确的育儿方式。', // 分享描述
                    link: _mz_wx_shareUrl(shareUrl), // 分享链接
                    imgUrl: '<?=config('app.url');?>/mobile/images/logo.jpg', // 分享图标
                    type: '', // 分享类型,music、video或link，不填默认为link
                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                    success:function() {
                        _mz_wx_friend();
                    }
                });
            });
        </script>
        <title>妈妈微课堂</title>
</head>
<body>
<header class='lessonTop'>
    <img src="images/bany/top.png" alt=""/>
    <img src="images/bany/December.png" alt=""/>
    <div class="clear"></div>
</header>
<div class="lessonList" id="thelist">
    <?php if($data):?>
    <?php foreach($data as $row):?>
    <div class="lessonLineTypeOne">
        <a href="/mobile/<?=($row['status']==1)?'reg':'living';?>?cid=<?=$row['cid'];?>" class="newsCover">
            <img src="<?=$row['img'];?>" alt="" class='newsCover' />
        </a>
        <div class="newsContent">
            <h4><a href="/mobile/<?=($row['status']==1)?'reg':'living';?>?cid=<?=$row['cid'];?>"><?=$row['title'];?></a></h4>
            <p><i class="dateIcon"></i><?=$row['start_day'];?>  <?=$row['start_time'];?>-<?=$row['end_time'];?></p>
            <p class="doctor">
                <span><?=$row['teacher_name'];?> <?=$row['teacher_position'];?></span>
                <span><?=$row['teacher_hospital'];?></span>
            </p>
            <p><i class="likeIcon"></i><?=$row['hot'];?></p>
            <a href="/mobile/<?=($row['status']==1)?'reg':'living';?>?cid=<?=$row['cid'];?>" class="functionBTN <?=($row['status']==1&&$row['is_signed']==1)?'regend':(($row['status']==2)?'regplaying':'regstart');?>"></a>
        </div>
    </div>
    <?php endforeach;?>
    <?php endif;?>
<div class="lessonLineTypeOne">
    <div class="newsCover"><img src="./img/lesson/review1.png" alt="" class='newsCover' /></div>
    <div class="newsContent">
        <h4><a>宝宝湿疹的护理和治疗</a></h4>
        <p><i class="dateIcon"></i>2015-12-31 21:00-22:00</p>
        <p class="doctor">
            <span>周怡 皮肤科主治医生</span>
            <span>安徽医科大学第一附属医院</span>
        </p>
        <audio id="audio_1" src="http://7xk3aj.com1.z0.glb.clouddn.com/001.mp3" ></audio>
        <p><i class="likeIcon1"></i></p>
        <a class="functionBTN regreview" id="regreview_1"></a>
        <div class="voice" id="regreview_1">
            <div class="circle_1" id="regreview_1_circle_1"></div>
            <div class="circle_2" id="regreview_1_circle_2"></div>
            <div class="circle_3" id="regreview_1_circle_3"></div>
        </div>
    </div>
</div>
<div class="lessonLineTypeOne">
    <div class="newsCover"><img src="./img/lesson/review2.png"alt="" class='newsCover' /></div>
    <div class="newsContent">
        <h4><a> 如何合理搭配宝宝的膳食</a></h4>
        <p><i class="dateIcon"></i>2015-12-31 21:00-22:00</p>
        <p class="doctor">
            <span>朱国伟  副主任医师</span>
            <span>上海市徐汇区妇幼保健所</span>
        </p>    
        <audio id="audio_2" control="controls" src="http://7xk3aj.com1.z0.glb.clouddn.com/002.mp3" ></audio>
        <p><i class="likeIcon1"></i></p>
        <a class="functionBTN regreview" id="regreview_2"></a>
        <div class="voice" >
            <div class="circle_1" id="regreview_2_circle_1"></div>
            <div class="circle_2" id="regreview_2_circle_2"></div>
            <div class="circle_3" id="regreview_2_circle_3"></div>
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
            <a href="/mobile/index" class="lesson current"></a>
            <a href="javascript:void(0)" class='discovery'></a>
            <a href="/mobile/mine"  class="mine"></a>
</footer>
<script src="js/jquery-1.8.3.js"></script>
<script src="js/iscroll.js"></script>
<script src="js/bany.js"></script>
<script>

    var audio_1 = document.querySelector('#audio_1');
    var audio_2 = document.querySelector('#audio_2');
    var regreview_1 = document.querySelector('#regreview_1');
    var regreview_1_circle_1 = document.querySelector('#regreview_1_circle_1');
    var regreview_1_circle_2 = document.querySelector('#regreview_1_circle_2');
    var regreview_1_circle_3 = document.querySelector('#regreview_1_circle_3');
    var regreview_2_circle_1 = document.querySelector('#regreview_2_circle_1');
    var regreview_2_circle_2 = document.querySelector('#regreview_2_circle_2');
    var regreview_2_circle_3 = document.querySelector('#regreview_2_circle_3');
    var  state = 1;
    regreview_1.addEventListener('click', function () {
        if (state == 1){
            regreview_1_circle_1.classList.add('circle_1_animation');
            regreview_1_circle_2.classList.add('circle_2_animation');
            regreview_1_circle_3.classList.add('circle_3_animation');
            
            audio_1.play();  

            state = 2;
        }else{
            regreview_1_circle_1.classList.remove('circle_1_animation');
            regreview_1_circle_2.classList.remove('circle_2_animation');
            regreview_1_circle_3.classList.remove('circle_3_animation');
            regreview_2_circle_1.classList.remove('circle_1_animation');
            regreview_2_circle_2.classList.remove('circle_2_animation');
            regreview_2_circle_3.classList.remove('circle_3_animation');
            audio_1.pause();
            audio_2.pause();  
            state = 1;
        }
    });
    audio_1.addEventListener('ended', function () {
        regreview_1_circle_1.classList.remove('circle_1_animation');
        regreview_1_circle_2.classList.remove('circle_2_animation');
        regreview_1_circle_3.classList.remove('circle_3_animation'); 
    }, false);
    
    var regreview_2 = document.querySelector('#regreview_2');
    regreview_2.addEventListener('click', function () {
        if (state == 1){
            regreview_2_circle_1.classList.add('circle_1_animation');
            regreview_2_circle_2.classList.add('circle_2_animation');
            regreview_2_circle_3.classList.add('circle_3_animation');

            audio_2.play();  

            state = 2;
        }else{
            regreview_1_circle_1.classList.remove('circle_1_animation');
            regreview_1_circle_2.classList.remove('circle_2_animation');
            regreview_1_circle_3.classList.remove('circle_3_animation');
            regreview_2_circle_1.classList.remove('circle_1_animation');
            regreview_2_circle_2.classList.remove('circle_2_animation');
            regreview_2_circle_3.classList.remove('circle_3_animation');
            audio_1.pause();
            audio_2.pause();
            state = 1;
        }
    });
    audio_1.addEventListener('ended', function () {
        regreview_2_circle_1.classList.remove('circle_1_animation');
        regreview_2_circle_2.classList.remove('circle_2_animation');
        regreview_2_circle_3.classList.remove('circle_3_animation'); 
    }, false);
</script>
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
    </body>
</html>