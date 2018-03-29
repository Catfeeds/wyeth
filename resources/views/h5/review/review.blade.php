<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta name="content-type" content="text/html;charset=utf-8">
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/css/swiper.min.css?v={{$resource_version}}" />
        <link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/css/finish_course.css?v={{$resource_version}}" />
        <title>妈妈微课堂精华回顾</title>
    </head>

    <body>
        <div class="page">
            <div class="content" style="top:0;bottom:0;">
                <!-- 课件 -->
                <div class="course">
                    <div class="class_title" id="class_title">
                        <div class="course_name" id="coourse_name">{{$course->title}}</div>
                    </div>
                    <div class="courseware swiper-container" id="courseware">
                        <!--课件显示区域-->
                        <div class="swiper-wrapper">
                            @foreach ($coursewares as $row)
                                <div class="swiper-slide" style="width:100%;"><img src="{{$row['img']}}" style="width:100%;"/></div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>

                    <div class="lead" id="lead" heywow_lead style="overflow: hidden">
                        {!!$course_review->guide!!}
                    </div>
                </div>
                <hr />

                <!-- 音频 -->
                <div class="dialogue" id="dialogue_1">
                    <img id="teacher_voice" class="voice" src="{{config('course.static_url')}}/mobile/img/live_in_class/answer.png">
                    <!--音频文件链接-->
                    <audio heywow_audio src="{{$course_review->audio}}" id="cr_audio" controls＝"control"></audio>
                    <div id="circle_1" class="circle_1"></div>
                    <div id="circle_2" class="circle_2"></div>
                    <div id="circle_3" class="circle_3"></div>
                    <div id="play_btn" class="play_btn" hw-statistics="alipay课程回顾页-音频"></div>
                    <div id="i_want_ask" class="i_want_ask ds-n"></div>
                    <!--教师头像描述内容-->
                    <img id="teacher_head" class="teacher_head" src="{{$course->teacher_avatar}}"/>
                    <div id="teacher_name" class="teacher_name">{{$course->teacher_name}}&nbsp{{$course->teacher_position}}</div>
                    <div id="teacher_hospital" class="teacher_hospital">{{$course->teacher_hospital}}</div>
                    @if ($course->id == 72)
                        <div class="video_prompt">点击下方视频观看更多精彩内容</div>
                    @endif
                </div>
                <hr />

                <!-- 视频位置1 -->
                @if ($course_review->video_display == '1' && $course_review->video_position == '1')
                    <div>
                        <div class="video" id="video">
                            <video controls poster="{{$course_review->video_cover}}" style="width:100%;">
                                <source src="{{$course_review->video}}" type="video/mp4">
                            </video>
                        </div>
                    </div>
                    <hr />
                @endif

                <!-- 课程问答内容回顾 -->
                <div class="course_content" id="coures_content" style="overflow: hidden">
                    {!!$course_review->desc!!}
                </div>

                <!-- 视频位置2 -->
                @if ($course_review->video_display == '1' && $course_review->video_position == '2')
                    <div>
                        <div class="video" id="video">
                            <video controls poster="{{$course_review->video_cover}}" style="width:100%;">
                                <source src="{{$course_review->video}}" type="video/mp4">
                            </video>
                        </div>
                    </div>
                    <hr />
                @endif

                <!-- 热门课程推荐 -->
                <div class="commend" id="commend">
                    热门课程推荐
                </div>
                <div class="lessonList" id="thelist">
                    @foreach ($course_recommends as $row)
                        <div class="lessonLineTypeOne">
                            <div class="newsCover"><img src="{{$row['img']}}"alt="" class='newsCover' /></div>
                            <div class="newsContent">
                                <h4><a>{{$row['title']}}</a></h4>
                                <p><i class="dateIcon"></i>{{$row['start_day']}}  {{$row['start_time']}}-{{$row['end_time']}}</p>
                                <p class="doctor">
                                    <span>{{$row['teacher_name']}} {{$row['teacher_position']}}</span>
                                    <span>{{$row['teacher_hospital']}}</span>
                                </p>
                                <p><i class="likeIcon"></i>{{$row['hot']}}</p>
                                <a href="/h5/review/{{$row['cid']}}" class="functionBTN regreview" hw-statistics="alipay课程回顾页-课程推荐-cid{{$row['cid']}}"></a>
                            </div>
                        </div>
                        <hr />
                    @endforeach
                </div>
            </div>
        </div>
        <script src="http://mp.gtimg.cn/open/js/openApi.js"></script>
        <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/course_end.js?v={{$resource_version}}"></script>
        <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/jquery-1.8.3.js?v={{$resource_version}}"></script>
        <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/swiper.jquery.min.js?v={{$resource_version}}"></script>
        <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/course_end.js?v={{$resource_version}}"></script>
        <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script>
            var options = {course_id: {{$course->id}}, is_subscribed: false};
            Course_End.init(options);

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
            //自己的分享统计
            function myRecordShare () {
                var cid = '{{$course->id}}';
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
                appId: '{{$package['appId']}}', // 必填，企业号的唯一标识，此处填写企业号corpid
                timestamp: {{$package['timestamp']}}, // 必填，生成签名的时间戳
                nonceStr: '{{$package['nonceStr']}}', // 必填，生成签名的随机串
                signature: '{{$package['signature']}}',// 必填，签名，见附录1
                jsApiList: [
                    'checkJsApi',
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage'
                ]
            });

            var shareUrl = '{{config('app.url')}}' + '/mobile/end?cid={{$course->id}}';
            var firend_title = '{{$course_review['firend_title']}}';
            var firend_subtitle = '{{$course_review['firend_subtitle']}}';
            var share_title = '{{$course_review['share_title']}}';
            var share_picture = '{{$course_review['share_picture']}}';
            wx.ready(function(){
                // 分享朋友圈的数据
                wx.onMenuShareTimeline({
                    title: share_title, // 分享标题
                    link: shareUrl, // 分享链接
                    imgUrl: share_picture, // 分享图标
                    success:function() {
                        if (token) {
                            myRecordShare();
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
                        if (token) {
                            myRecordShare();
                        }
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
                'title':'{{$course->firend_title}}',
                'link': 'http://wyeth.qq.nplusgroup.com/phone/wkt/detail-{{$course->id}}.htm',
                'imgUrl':"{{$course->share_picture}}",
                'desc':'{{$course->firend_subtitle}}'
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

            //百度统计
            $('a[hw-statistics], #play_btn').on('click', function() {
                var hwStatistics = $(this).attr('hw-statistics');
                _hmt.push(['_trackEvent', hwStatistics]);
            });
        </script>
        @include('h5.review.statistics')
    </body>
</html>