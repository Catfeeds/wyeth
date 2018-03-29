<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width,user-scalable=no" name="viewport" />
        @include('public.head')
        <link href="./css/reset.css" rel="styleSheet" type="text/css" />
        <link href="./css/lesson.css" rel="styleSheet" type="text/css" />
        <title>妈妈微课堂</title>
</head>
<body>
<header class='lessonTop'>
    <img src="./img/lesson/class_review.png" alt=""/>
    <img src="./images/bany/December.png" alt=""/>
    <div class="clear"></div>
</header>

<div class="lessonList" id="thelist">

<div class="lessonLineTypeOne">
    <div class="newsCover">
        <!--课程图片-->
        <img src="./img/lesson/review_8.jpg"alt="" />
        <div class="play_btn" id="play_btn_1">
            <!--音频-->
            <audio class="ds-n" id="audio_1" src="http://7xk3aj.com1.z0.glb.clouddn.com/5_wethy_review.mp3" ></audio>
        </div>
    </div>
    <div class="newsContent">
        <h4><a>如何合理搭配宝宝的膳食</a></h4>
        <p><i class="dateIcon"></i>2015-11-19 21:00-22:00</p>
        <p class="doctor">
            <span>朱国伟 副主任医师</span>
            <span>上海市徐汇区妇幼保健所</span>
        </p>
        <p><i class="likeIcon1"></i></p>
        <!--跳转链接-->
        <a class="functionBTN regreview" id="regreview_1" href=""></a>
    </div>
</div>
<div class="lessonLineTypeOne">
    <div class="newsCover">
        <img src="./img/lesson/review_6.jpg"alt="" />
        <div class="play_btn" id="play_btn_2">
            <audio id="audio_1" src="http://7xk3aj.com1.z0.glb.clouddn.com/8_wethy_review.mp3"></audio>
        </div>
    </div>
    <div class="newsContent">
        <h4><a>教你轻松应对疱疹性咽峡炎</a></h4>
        <p><i class="dateIcon"></i>2015-11-8 20:00-21:00</p>
        <p class="doctor">
            <span>洪亮 主任医师</span>
            <span>北京同仁医院</span>
        </p>
        <p><i class="likeIcon1"></i></p>
        <a class="functionBTN regreview" id="regreview_2"></a>
    </div>
</div>
<div class="lessonLineTypeOne">
    <div class="newsCover">
        <img src="./img/lesson/review_5.jpg"alt="" class='newsCover' />
        <div class="play_btn" id="play_btn_3">
            <audio id="audio_1" src="http://7xk3aj.com1.z0.glb.clouddn.com/6_wethy_review.mp3"></audio>
        </div>
    </div>
    <div class="newsContent">
        <h4><a> 产后不敢“招惹”的妇科疾病</a></h4>
        <p><i class="dateIcon1"></i>2015-11-1 20:00-21:00</p>
        <p class="doctor">
            <span>王蕾 妇产科主治医师</span>
            <span>北京美中宜和妇儿医院</span>
        </p>
        <p><i class="likeIcon1"></i></p>
        <a class="functionBTN regreview" id="regreview_3"></a>
    </div>
</div>
    <div class="clear"></div>
</div>

<div class="lastLesson" >
    <div class="item">“魔栗妈咪学院”版权归属景栗科技所有，相关课程内容由景栗科技提供。平台相关内容不作为医学诊断参考，如情况严重，建议及时就医。
    </div>
</div>
<div class="footerLine"></div>
<footer class='fixedMenu'>
</footer>
<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="js/end_lessonlist.js"></script>>
@include('public.statistics')
</body>
</html>