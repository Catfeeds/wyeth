<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="">

    <title>讲师 - {{$course_info->title}}</title>

    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />
    <link href="/mobile/css/swiper.min.css" rel="stylesheet">
    <link href="/mobile/css/teacher_live.css" rel="stylesheet">
    <script type="text/javascript" src="/workman/swfobject.js"></script>
    <script type="text/javascript" src="/workman/web_socket.js"></script>
    <script type="text/javascript" src="/workman/json.js"></script>

    <script type="text/javascript" src="/assets/teacher/js/vvMedia.js"></script>

    <style>html,body{height: 100%; padding: 0; margin: 0;}</style>
</head>
<body>

<div class="container" style="width:100%; height: 100%; padding:0 15%;">

    <aside class="profile-nav alt green-border" style="position: fixed;top: 0;width: 70%;">
        <section class="panel">
            <div class="user-heading alt green-bg" style="padding: 8px; position: relative;">
                <p style="font-size: 16px;">
                    <?=$course_info->title?>
                </p>
                <a href="#" style="margin-left: 0">
                    <img alt="" src="<?=$course_info->teacher_avatar?>" style="width:70px; height: 70px;">
                </a>
                <p style="font-size: 16px; font-weight: bolder; margin: 14px 0 4px 0"><?=$course_info->teacher_name?></p>
                <p style="font-size: 14px; margin-bottom: 4px;">
                    <?=$course_info->teacher_hospital?>
                    <?=$course_info->teacher_position?>
                </p>
                <div style="position: absolute; top:0; right:0; width:50%; padding: 8px;">
                    <p style="font-size: 16px;">
                        <a href="/teacher/index" style="float: right; font-size: 12px;">返回课程列表</a>
                    </p>
                    <p style="margin-top:45px;">
                        <button type="button" class="btn btn-info" hw_btn_start>开始直播</button>
                        <button type="button" class="btn btn-success" hw_btn_living disabled style="display: none;">直播中</button>
                        <button type="button" class="btn btn-danger" hw_btn_end style="display: none;">结束直播</button>
                    </p>
            </div>
        </section>
    </aside>

    <section class="panel" style="position: absolute; top:138px; bottom: 10px; width: 70%;">
        <header class="panel-heading tab-bg-dark-navy-blue" style="background-color: #aec785;">
            <ul class="nav nav-tabs nav-justified " hw_nav>
                <li class="active">
                    <a href="##" data-toggle="tab" aria-expanded="true" data-show-tab="live">直播大厅</a>
                </li>
                <li class="">
                    <a href="##" data-toggle="tab" aria-expanded="false" data-show-tab="ppt">课件幻灯片</a>
                </li>
                <li class="">
                    <a href="##" data-toggle="tab" aria-expanded="false" data-show-tab="to_teacher_question">答疑</a>
                    <!-- onclick="get_to_teacher_question() -->
                </li>
            </ul>
        </header>
        <div class="panel-body profile-activity">

                <div id="live" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; overflow: auto; padding: 15px; margin-top: 40px;">
                    <div id="videoDisplay" style="width: 100%; height: 100%;">
                        <div>
                            <label>累计流量：</label><input id="totalFlow" style="width: 95px" type="text" value="0.00MB" disabled />
                            <label>平均码率：</label><input id="avgBitrate" style="width: 95px" type="text" value="0.00kb" disabled />
                            <label>峰值码率：</label><input id="maxBitrate" style="width: 95px" type="text" value="0.00kb" disabled />
                        </div>
                        <div id="player1">
                            <p>
                                To view this page ensure that Adobe Flash Player version
                                11.1.0 or greater is installed.
                            </p>
                        </div>
                    </div>
                </div>
                <div id="ppt" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; overflow: auto; padding: 15px; margin-top: 40px; z-index: -1;">
                    <div class="swiper-container gallery-top">
                        <div class="swiper-wrapper">
                            @foreach ($ware as $v)
                            <div class="swiper-slide" style="background-image:url({{$v['img']}})"></div>
                            @endforeach
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next swiper-button-black"></div>
                        <div class="swiper-button-prev swiper-button-black"></div>
                    </div>
                    <div class="swiper-container gallery-thumbs">
                        <div class="swiper-wrapper">
                            @foreach ($ware as $v)
                            <div class="swiper-slide" style="background-image:url({{$v['img']}})"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div id="to_teacher_question" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; overflow: auto; padding: 15px; margin-top: 40px; z-index: -1;">
                </div>

        </div>
    </section>

    <section class="panel" style="padding:10px 0; position: fixed;bottom: 0;margin: 0;width: 70%; ">
        @if ($debug)
        <div>
            <textarea rows="1" cols="20" style="width: 375px; height: 230px; background-color:#EEE;float:left" id="mediaInfo" readonly="readonly"></textarea>
            <textarea rows="1" cols="20" style="width: 252px; height: 230px; background-color:#EEE" id="streamInfo" readonly="readonly"></textarea>
        </div>
        @endif
    </section>
</div>

<script type="text/template" id="tpl_message">
    <div class="activity <%=message_id%>">
        <span><img src="<%=avatar%>" style="-webkit-border-radius:50%; border-radius: 50%; width:45px;"></span>
        <div class="activity-desk">
            <div class="panel" style="margin-bottom: 0px;">
                <div class="panel-body" style="padding: 10px;">
                    <div class="arrow"></div>
                    <div>
                        <a class="btn btn-white btn-xs" style="margin: 0 10px 0 0"><i class=" fa fa-clock-o" style="margin-right: 2px;"></i><%=time%>  <%=name%></a>
                        <%=state_html%>
                    </div>
                    <p><%=content%></p>
                </div>
            </div>
        </div>
    </div>
</script>

</body>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script src="{{config('course.static_url')}}/mobile/js/lodash.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js"></script>
<script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/swiper.jquery.min.js"></script>

<script type="text/javascript">
var cid = {{$course_info->id}};
var user_type = 3;
var author_id = {{$course_info->teacher_uid}};
var anchor_id = {{$course_info->anchor_uid}};
var select_client_id = 'all';
var source_message_id = 0;
var ws, client_list={}, timeid, reconnect=false;
var is_scroll = true;
var chat_channel = '{{config('course.chat_channel')}}';
var hls_record_addr = '{{$hls_record_addr}}';
var hls_record_stream = '{{$hls_record_stream}}';

var galleryTop = new Swiper('.gallery-top', {
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        spaceBetween: 10
});
var galleryThumbs = new Swiper('.gallery-thumbs', {
    spaceBetween: 10,
    centeredSlides: true,
    slidesPerView: 'auto',
    touchRatio: 0.2,
    slideToClickedSlide: true
});
galleryTop.params.control = galleryThumbs;
galleryThumbs.params.control = galleryTop;
</script>

<script src="/assets/teacher/js/voiceLive.js"></script>
<script src="/assets/teacher/js/live.js"></script>

</html>