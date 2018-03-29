<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="css/swiper.min.css" />
    <link rel="stylesheet" href="css/class.css" />
    <title><?php echo 'classing' ?></title>

</head>
<body>
    <div class="page">
        <div class="content" style="top:0;bottom:0;">
            <div class="course">
                <div class="courseware swiper-container" id="courseware">
                    <!--课件显示区域-->
                    <div class="swiper-wrapper">
                        <img class="swiper-slide" src="./img/live_in_class/turn_pic_1.png"/>
                        <img class="swiper-slide" src="./img/live_in_class/turn_pic_2.png"/>
                        <img class="swiper-slide" src="./img/live_in_class/turn_pic_3.png"/>
                        <img class="swiper-slide" src="./img/live_in_class/turn_pic_4.png"/>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <div class="seach_area" id="seach_area">
                <div class="seach_class_btn" id="seach_class_btn"><img src='./img/class_btn.png'/></div>
                <div class="seach_input" id="seach_input">
                    <img src='./img/class_seach.png'/>
                    <input placeholder='搜一搜感兴趣的内容' />
                </div>
                <div class="hot_seach" id="hot_seach">
                    <ul>
                        <li class="hot_element" id="hot_element_1">宝宝湿疹宝宝湿疹</li>
                        <li class="hot_element" id="hot_element_1">宝宝湿疹</li>
                        <li class="hot_element" id="hot_element_1">宝宝湿疹</li>
                        <li class="hot_element" id="hot_element_1">宝宝湿疹</li>
                        <li class="hot_element" id="hot_element_1">宝宝湿疹</li>
                    </ul>
                </div>
            </div>
            <div class="class_show_more" id="class_show_more">
                <img src="./img/class_show_more.png" />
            </div>
            <div class="lessonList" id="thelist">
                <div class="lessonLineTypeOne">
                    <div class="newsCover"><img src="./img/9.jpg"alt="" class='newsCover' /></div>
                    <div class="newsContent">
                        <h4><a>宝宝湿疹不要怕</a></h4>
                        <p><i class="dateIcon"></i>2015-11-29 20:30-21:30</p>
                        <p class="doctor">
                            <span>周怡  皮肤科主治医师</span>
                            <span>安徽医科大学第一附属医院</span>
                        </p>
                        <audio id="audio_1" src="http://7xk3aj.com1.z0.glb.clouddn.com/review_9.mp3" ></audio>
                        <p><i class="likeIcon1"></i></p>
                        <a class="functionBTN regreview" id="regreview_1"></a>
                        <div class="voice ds-n" >
                            <div class="circle_1 circle_1_animation" id="regreview_1_circle_1"></div>
                            <div class="circle_2 circle_2_animation" id="regreview_1_circle_2"></div>
                            <div class="circle_3 circle_3_animation" id="regreview_1_circle_3"></div>
                            <div class="circle_4 circle_4_animation" id="regreview_1_circle_4"></div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="lessonLineTypeOne">
                    <div class="newsCover"><img src="./img/9.jpg"alt="" class='newsCover' /></div>
                    <div class="newsContent">
                        <h4><a>宝宝湿疹不要怕</a></h4>
                        <p><i class="dateIcon"></i>2015-11-29 20:30-21:30</p>
                        <p class="doctor">
                            <span>周怡  皮肤科主治医师</span>
                            <span>安徽医科大学第一附属医院</span>
                        </p>
                        <audio id="audio_1" src="http://7xk3aj.com1.z0.glb.clouddn.com/review_9.mp3" ></audio>
                        <p><i class="likeIcon1"></i></p>
                        <a class="functionBTN regreview" id="regreview_1"></a>
                        <div class="voice ds-n" >
                            <div class="circle_1 circle_1_animation" id="regreview_1_circle_1"></div>
                            <div class="circle_2 circle_2_animation" id="regreview_1_circle_2"></div>
                            <div class="circle_3 circle_3_animation" id="regreview_1_circle_3"></div>
                            <div class="circle_4 circle_4_animation" id="regreview_1_circle_4"></div>
                        </div>
                    </div>
                </div>
                <hr />
            </div>
        </div>
        <div class="class_tc ds-n" id="class_tc">
            <div class="input_area" id="input_area">
                <img src="./img/class_seach.png"/>
                <input placeholder="请输入想要搜索的内容" />
            </div>
            <div class="class_content" id="class_content">
                <div class="">
                    
                </div>
                <div>
                    
                </div>
            </div>
        </div>
        <footer class='fixedMenu'>
            <a href="/mobile/index" onclick="_mz_wx_custom(49); setTimeout(function(){window.open('/mobile/index','_self');},500); return false;" class="lesson"></a>
            <a href="/mobile/class" onclick="setTimeout(function(){window.open('/mobile/class','_self');},500); return false;" class='discovery current'></a>
            <a href="/mobile/mine" onclick="_mz_wx_custom(50); setTimeout(function(){window.open('/mobile/mine','_self');},500); return false;" class="mine"></a>
        </footer>
    </div>
    <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="js/swiper.jquery.min.js"></script>
    <script type="text/javascript" src="js/course_finish.js"></script>
    <script type="text/javascript" src="js/jquery.jpanelmenu.js"></script>
 </body>
</html>
