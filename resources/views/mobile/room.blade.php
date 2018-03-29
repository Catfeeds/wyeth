<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    @include('public.head')
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/css/class_live_in.css?v=201604082243{{$resource_version}}" />
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/css/swiper.min.css?v={{$resource_version}}" />
    <title>妈妈微课堂</title>
</head>
<body id="body">
    <div class="page" id="wrapper">
      <div class="content">
        <div class="title">
			<img src="{{config('course.static_url')}}/mobile/img/live_in_class/title_v1.png"/>
			<div class="now_people" id="now_people"></div>
			<div class="dan_btn" id="dan_btn"></div>
            <div class="dan_btn_line ds-n" id="dan_btn_line"></div>
        </div>
        <div class="barrage" id="barrage"></div>
        <div class="courseware swiper-container" id="courseware">
          	<div class="window swiper-wrapper" id="show_window">
	            <?php foreach ($coursewares as $v) {echo '<img class="swiper-slide" src="' . $v['img'] . '"/>';}?>
          	</div>
            <div class="swiper_pre" id="swiper_pre"></div>
            <div class="swiper_next" id="swiper_next"></div>
        </div>
        <div class="answer">
          	<div class="dialogue" id="dialogue_1">
	            <div id="teacher_voice" class="voice" >
                    <div class="to-left"></div>
                    <div id="teacher_voice_tips" style="padding-top: 10px;padding-left: 30px;font-size: 13px;">点击开始听课</div>
                </div>
                <div id="circle_1" class="circle_1 "></div>
                <div id="circle_2" class="circle_2 "></div>
                <div id="circle_3" class="circle_3 "></div>
	            <img id="teacher_head" class="teacher_head" src="<?php echo $avatar; ?>" />
	            <div id="teacher_name" class="teacher_name"><?php echo $name; ?></div>
	           	<div id="teacher_state" class="teacher_state">老师准备中...</div>
                <div id="teacher_question"></div>
          	</div>
            <hr />
        </div>
        <div class="bottom">
            <!--抢话筒区域-->
            <div class="microphone" id="microphone_area">
                <img src="{{config('course.static_url')}}/mobile/img/live_in_class/microphone_bg.png" />
                <!-- 主持人区域 -->
                <div class="anchor_area_show" id="anchor_area_show">
                        <div class="scroller">
                            <img id="teacher_head" style="width: 16.466667%; border-radius:50px; left:2.5%;" src="http://7xk3aj.com1.z0.glb.clouddn.com/FlN9bfkk4Nnsmu3azPLYJeEkFtI4">
                            <span class="anchor_name">Miss惠(主持人)</span>
                            <div class="anchor_message_show" id="anchor_message_show">欢迎麻麻粑粑们来参加妈妈微课堂^_^</div>
                            <div class="to-left"></div>
                        </div>
                    <div class="allAnthor_say_btn" id="allAnthor_say_btn"></div>
                    <hr />
                </div>

                <!-- 抢话筒区域 -->
                <div class="grab_microphone_area" id="grab_microphone_area">
                    <div class="grab_microphone_button" id="grab_microphone_button"></div>
                </div>
                <!-- 已回答区域 -->
                <div class="answered_users_area" id="answered_users_area">

                </div>
            </div>
            <!--抢话筒区域end-->
			<div class="b_chat_area ds-n">
				<div class="b_chat_title"></div>
			</div>
          	<div class="user_answer ds-n" id="user_answer">
            	<div class="swiper_area" id="swiper_area">

            	</div>
          	</div>
          <!-- <div class="b_reply_wrap">
            <p class="b_at_tips"></p>
            <a href="javascript:;" class="b_help_btn"></a>
            <div class="b_reply">
                <a href="javascript:;" class="b_submit_btn" id="send_msg"></a>
                <div class="b_reply_ipt">
                    <pre id="content" class="b_textarea" contenteditable="true"></pre>
                </div>
            </div>
          </div> -->
        </div>
        <div class="tc ds-n" id="grab_send_msg_area" hw_dialog>
            <div class="close_btn" hw_dialog_close_button></div>
            <div class="textarea" contenteditable="true" id="grab_send_content"></div>
            <div class="send_btn" id="grab_send_button"></div>
            <label class="notify">
                <input type="checkbox" id="grab_receive_more" checked="checked"/>我愿意接受其他专家课后答复未解决的问题
            </label>
        </div>
        <div class="tc_failed ds-n" id="tc_failed">

        </div>
        <div class="tc_share ds-n" id="tc_share">

        </div>
      </div>
    </div>
    <div class="all_tc ds-n" id="page_tc_voice">
        <div class="all_voice_return ds-n" id="all_voice_return">
            <img src="{{config('course.static_url')}}/mobile/img/course_back_btn01.png" >
        </div>
        <div class="more_answer" id="more_answer">
            <div class="answer_container" id="answer_container">
            </div>
        </div>
    </div>
    <div class="page_tc_anthor ds-n" id="page_tc_anthor">
        <div class="tc_anthor_return ds-n" id="tc_anthor_return">
            <img src="{{config('course.static_url')}}/mobile/img/course_back_btn01.png" >
        </div>
        <div class="anchor_area" id="anchor_area">
            <ul class="anchor_area_sroll" id="anchor_area_sroll">
            </ul>
        </div>
    </div>
    <div class="friends_show_tc ds-n" id="friends_show_tc" heywowo_friends_show_tc>
        <div class="btn_share ds-n" id="btn_share"></div>
        <div class="show_area" id="show_area" >
            <img class="show_title" src="{{config('course.static_url')}}/mobile/img/live_in_class/friends_show_title.png">
            <ul class="head_imgs_area" id="head_imgs_area">
            </ul>
            <div class="goto_class_btn" id="goto_class_btn"></div>
            <div class="show_my_friends" id="show_my_friends"></div>
        </div>
    </div>
    <div class="tips ds-n" id="tips">发言成功</div>
    <div class="tips_answer ds-n" id="tips_answer"></div>


    <script type="text/template" id="temp_dialogue_stu">
	    <div class="dialogue_stu">
	    	<img class="stu_head" style="border-radius:50px; " src="<%=avatar%>" />
	    	<div class="stu_name"><%=name%></div>
	    	<div class="textarea"><%=content%><em class="to-left"></em></div>
    	</div>
    </script>
    <script type="text/template" id="temp_dialogue_teacher">
    	<div class="dialogue_teacher">
        	<img class="teacher_head" style="border-radius:50px; " src="<%=avatar%>" />
        	<div hw_voice data-url="<%=url%>"  data_num="<%=num%>" class="voice">
              <div hw_voice_animation id="circle_1" class="circle_1 "></div>
              <div hw_voice_animation id="circle_2" class="circle_2 "></div>
              <div hw_voice_animation id="circle_3" class="circle_3 "></div><div class="voice_time"><%=voice_time%></div></div>
            <div class="not_play"></div>
        	<div class="time"><%=time%></div>
      	</div>
    </script>
    <script type="text/template" id="temp_dialogue_teacher_play">
        <div class="dialogue_teacher">
            <img class="teacher_head" style="border-radius:50px; " src="<%=avatar%>" />
            <div hw_voice data-url="<%=url%>"  data_num="<%=num%>" class="voice">
              <div hw_voice_animation id="circle_1" class="circle_1 "></div>
              <div hw_voice_animation id="circle_2" class="circle_2 "></div>
              <div hw_voice_animation id="circle_3" class="circle_3 "></div><div class="voice_time"><%=voice_time%></div></div>
            <div class="time"><%=time%></div>
        </div>
    </script>
    <script type="text/template" id="temp_dialogue_other">
    	<li class="dialogue_other">
	    	<img class="other_head" style="border-radius:50px; " src="<%=avatar%>" />
			<div class="other_name"><%=name%></div>
			<div class="textarea"><%=content%><em class="to-left"></em></div>
		</li>
    </script>
    <script type="text/template" id="temp_dialogue_mine">
    	<li class="dialogue_mine">
	    	<img class="mine_head" style="border-radius:50px; " src="<%=avatar%>" />
			<div class="mine_name"><%=name%></div>
			<div class="textarea"><%=content%><em class="to-right"></em></div>
		</li>
    </script>
    <script type="text/template" id="temp_dialogue_anthor">
    	<li class="dialogue_other">
	    	<img class="other_head" style="border-radius:50px; " src="<%=avatar%>" />
			<div class="other_name">Miss惠</div>
			<div class="textarea"><%=content%><em class="to-left"></em></div>
		</li>
    </script>
    <!--  头像列表 -->
    <script type="text/template" id="avatars">
        <div class="des">已有<%=answered_num%>位同学提问被老师回答，还剩<%=answered_left%>个名额</div>
        <div class="stus" id="stus"><%=avatars%></div>
    </script>
    <!--  主持人发言 -->
    <script type="text/template" id="anchor_say">
        <li class="scroller">
            <img id="teacher_head" style="width: 16.466667%; border-radius:50px; left:2.5%; " src="http://7xk3aj.com1.z0.glb.clouddn.com/FlN9bfkk4Nnsmu3azPLYJeEkFtI4">
            <span class="anchor_name">Miss惠(主持人)</span>
            <div class="anchor_message" id="anchor_message"><div class="to-left"></div><%=content%></div>
        </li>
    </script>
    <!--friends infos-->
    <script type="text/template" id="show_friends_info">
        <li><img src="<%=avatar%>"/><div><%=name%></div></li>
    </script>

    @if (isset($_GET['debug']) and $_GET['debug'] == 'true')
    <script src="http://jsconsole.com/js/remote.js?BB8D7C69-63C0-4B0D-A80C-BE866F9D986D"></script>
    @endif

    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/iscroll.js"></script>
    <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/swiper.jquery.min.js"></script>
    <script src="{{config('course.static_url')}}/mobile/js/lodash.min.js"></script>
    <script src="{{config('course.static_url')}}/mobile/js/jquery.danmu.min.js"></script>

    <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/record.js"></script>
    <script type="text/javascript">
        var page_name = '直播页';
        Record.init({
            static_url: '{{config('course.static_url')}}',
            mz: {
                site_id: '{{config('record.mz_siteid')}}',
                openid: '{{$openid}}'
            },
            dc: {
                appid: '{{config('record.dc_appid')}}'
            },
            channel: '{{$channel}}',
            uid: '{{$uid}}'
        });
        Record.page(page_name, {}, 6);
    </script>
    <script src="{{config('course.static_url')}}/js/buzz.min.js"></script>
    <script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/userliving.js?v={{$resource_version}}"></script>
    <script >
        var token;
		var cid = <?php echo $cid; ?>;
		var uid = <?php echo $user->id; ?>;
		var user_type = <?php echo $user_type; ?>;
        var living_firend_title = "{{$course->living_firend_title}}";
        var living_firend_subtitle = "{{$course->living_firend_subtitle}}";
        var living_share_title = "{{$course->living_share_title}}";
        var living_share_picture = "{{$course->living_share_picture}}";
		$(document).ready(function(){
            "use strict";
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
                    var options = {
                        cid: cid,
                        uid: uid,
                        user_type: user_type,
                        replyNotifyStatus: '{{$reply_notify_word}}',
                        package: <?php echo json_encode($package); ?>,
                        openid: '<?php echo $openid; ?>',
                        app_url: '<?php echo config('app.url'); ?>',
                        static_url: '<?php echo config('course.static_url'); ?>',
                        start_day: '<?php echo $start_day; ?>',
                        title: '<?php echo $title; ?>',
                        living_firend_title: living_firend_title,
                        living_firend_subtitle: living_firend_subtitle,
                        living_share_title: living_share_title,
                        living_share_picture: living_share_picture,
                        chat_channel:  '<?php echo config('course.chat_channel'); ?>',
                        hls_url: '<?php echo $hls_url; ?>',
                        isPublish: '<?php echo $isPublish; ?>'
                    };
                    UserLiving.init(options);
                    UserLiving.doMessageTeacherPublish();
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
            'title':'{{$course['firend_title']}}',
            'link': 'http://wyeth.qq.nplusgroup.com/phone/wkt/d-living-'+ cid +'.htm',
            'imgUrl':"{{$course['share_picture']}}",
            'desc':'{{$course['firend_subtitle']}}'
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
@include('mobile.share', ['share' => $share])
@include('public.statistics')
</body>
</html>