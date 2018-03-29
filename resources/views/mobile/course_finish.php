<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="<?=config('course.static_url');?>/mobile/css/swiper.min.css?v=<?=$resource_version;?>" />
    <link rel="stylesheet" href="<?=config('course.static_url');?>/mobile/css/finish_course.css?v=<?=$resource_version;?>" />
    <title><?php echo '妈妈微课堂精华回顾' ?></title>
</head>
<body>
    <div class="page">
        <div class="content" style="top:0;bottom:0;">
            <div class="course">
                <div class="class_title" id="class_title">
                    <div class="course_name" id="coourse_name"><?=$course->title;?></div>
                </div>
                <div class="courseware swiper-container" id="courseware">
                    <!--课件显示区域-->
                    <div class="swiper-wrapper">
                        <?php foreach($coursewares as $row):?>
                            <div class="swiper-slide" style="width:100%;"><img src="<?=$row['img'];?>" style="width:100%;"/></div>
                        <?php endforeach;?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

                <div class="lead" id="lead" heywow_lead style="overflow: hidden">
                    <?=$course_review->guide;?>
                </div>
                <!-- 这里是二维码显示 -->
                <?php if($qrcode):?>
                <div class="qrcode" id="qrcode" style="overflow: hidden">
                    <img src="<?=$qrcode->img;?>">
                </div>
                <?php endif;?>
            </div>
            <hr />
            <div class="dialogue" id="dialogue_1">
                <img id="teacher_voice" class="voice" src="<?=config('course.static_url');?>/mobile/img/live_in_class/answer.png">
                <!--音频文件链接-->
                <audio heywow_audio src="<?=$course_review->video;?>" id="cr_video" controls＝"control"></audio>
                <div id="circle_1" class="circle_1"></div>
                <div id="circle_2" class="circle_2"></div>
                <div id="circle_3" class="circle_3"></div>
                <div id="play_btn" class="play_btn"></div>
                <!--教师头像描述内容-->
                <img id="teacher_head" class="teacher_head" src="<?=$course->teacher_avatar;?>"/>
                <div id="teacher_name" class="teacher_name"><?=$course->teacher_name;?>&nbsp<?=$course->teacher_position;?></div>
                <div id="teacher_hospital" class="teacher_hospital"><?=$course->teacher_hospital;?></div>
            </div>
            <hr />
            <div class="course_content" id="coures_content" style="overflow: hidden">
                <?=$course_review->desc;?>
            </div>
            <div class="video ds-n" id="video">
                <video controls poster="<?=config('course.static_url');?>/mobile/img/video_first.jpg" style="width:100%;">
                    <source src="http://7xp1g4.com1.z0.glb.clouddn.com/course_ideo.mp4" type="video/mp4">
                </video>
            </div>
            <?php if(!empty($course_recommends)):?>
            <div class="commend" id="commend">
                热门课程推荐
            </div>
            <div class="lessonList" id="thelist">
                <?php foreach($course_recommends as $row):?>
                <div class="lessonLineTypeOne">
                    <div class="newsCover"><img src="<?=$row['img'];?>"alt="" class='newsCover' /></div>
                    <div class="newsContent">
                        <h4><a><?=$row['title'];?></a></h4>
                        <p><i class="dateIcon"></i><?=$row['start_day'];?>  <?=$row['start_time'];?>-<?=$row['end_time'];?></p>
                        <p class="doctor">
                            <span><?=$row['teacher_name'];?> <?=$row['teacher_position'];?></span>
                            <span><?=$row['teacher_hospital'];?></span>
                        </p>
                        <p><i class="likeIcon"></i><?=$row['hot'];?></p>
                        <a href="/mobile/<?=($row['status'] == 1) ? 'reg' : 'living';?>?cid=<?=$row['cid'];?>" class="functionBTN <?=($row['status'] == 1 && $row['is_signed'] == 1) ? 'regend' : (($row['status'] == 2) ? 'regplaying' : 'regstart');?>"></a>
                    </div>
                </div>
                <hr />
                <?php endforeach;?>
            </div>
        </div>
        <?php endif;?>
    </div>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/jquery-1.8.3.js?v=<?=$resource_version;?>"></script>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/swiper.jquery.min.js?v=<?=$resource_version;?>"></script>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/course_finish.js?v=<?=$resource_version;?>"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript"  src="<?=config('course.mz_url');?>"></script>
    <script src="<?=config('course.static_url');?>/mobile/js/record.js"></script>
    <script type="text/javascript">
        var page_name = '回顾课程页';
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
    <script>
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
            function GetRequest() { 
                var url = location.search; //获取url中"?"符后的字串 
                var theRequest = new Object(); 
                if (url.indexOf("?") != -1) { 
                var str = url.substr(1); 
                strs = str.split("&"); 
                for(var i = 0; i < strs.length; i ++) { 
                  theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]); 
                } 
                } 
                return theRequest; 
            } 
            var request = GetRequest();
            if (request.cid == '56') {
                $('#video').removeClass('ds-n');
            }

        });
        //自己的分享统计
        function myRecordShare () {
            var cid = '<?=$course->id?>';
            $.ajax({
                type: "POST",
                url: "/api/course/share",
                data: {
                    cid: cid,
                    type: 3
                },
                success: function(data){
                    console.log(data);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }
        

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

        var shareUrl = '<?=config('app.url');?>' + '/mobile/end?cid=<?=$course->id?>'+'&from_openid='+'<?=$openid;?>';
        var firend_title = '<?=$course_review['firend_title']?>';
        var firend_subtitle = '<?=$course_review['firend_subtitle']?>';
        var share_title = '<?=$course_review['share_title']?>';
        var share_picture = '<?=$course_review['share_picture']?>';
        wx.ready(function(){
            // 分享朋友圈的数据
            wx.onMenuShareTimeline({
                title: share_title, // 分享标题
                link: shareUrl, // 分享链接
                imgUrl: share_picture, // 分享图标
                success:function() {
                    _mz_wx_timeline();
                    if (token) {
                        myRecordShare ();
                    }
                }
            });

            // 分享给好友的数据
            wx.onMenuShareAppMessage({
                title: firend_title, // 分享标题
                desc: firend_subtitle, // 分享描述
                link:shareUrl, // 分享链接
                imgUrl: share_picture, // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success:function() {
                    _mz_wx_friend();
                    if (token) {
                        myRecordShare ();
                    }
                }
            });
        });
    </script>
 </body>
</html>
