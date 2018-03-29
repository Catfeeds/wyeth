<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="/mobile/css/style.css">
</head>
<body class="bg1" >
    <audio id="audio" src="" style="display:none" > </audio>
    <ul class="tabNav">
        <li><i class="tabIcon tabIcon-1">待回答</i></li>
        <li><i class="tabIcon tabIcon-2">已回答</i></li>
        <!--
        <li><i class="tabIcon tabIcon-3">我的发言</i></li>
        <li><i class="tabIcon tabIcon-4">全部</i></li>
        -->
    </ul>
    <ul class="tabCon">
        <li hw-question>
        </li>
        <li hw-answered></li>
        <li hw-mine></li>
        <li hw-all></li>
    </ul>
    <div class="popWrap popShow" hw_pop>
        <div hw_reply style="display:none;">
            <div class="popClose" style="display:none"></div>
            <div class="popMain">
                <div class="dialogBox">
                    <div class="bubble bubble_l" hw_comment>
                        <dl>
                          <dt><div class="bubble-user"><a href="###"><img src="" hw_comment_avatar></a></div></dt>
                          <dd>
                            <div class="bubble-box">
                              <h6 hw_comment_name></h6>
                              <div class="bubble-cont">
                                <div class="bubble-arrow"></div>
                                <div class="bubble-main" hw_comment_content></div>
                              </div>
                            </div>
                          </dd>
                        </dl>
                      </div>
                      <div class="bubble bubble_r" hw_comment_voice_div style="display: none">
                        <dl>
                          <dt>
                            <div class="bubble-user"><a href="###"><img src="" hw_comment_voice_avatar></a></div>
                          </dt>
                          <dd>
                            <div class="bubble-box">
                              <div class="bubble-cont" hw_comment_voice>
                                <div class="bubble-arrow"></div>
                                <div class="bubble-main"><span class="voice"></span></div>
                              </div>
                            </div>
                          </dd>
                        </dl>
                    </div>
                    <div class="mic_time" id="mic_time" style="display:none; text-align:center; line-height:1.0em; font-size:2.2em; padding:10px; display:none">0</div>
                    <div class="mic" hw_comment_record_mic><img src="/mobile/images/mic.png"></div>
                </div>
            </div>
        </div>
        <div class="popBtn" hw_comment_record_before>
            <ul class="helpR">
                <li hw_comment_div_start_for_btn><div class="btn btn2"><img src="/mobile/images/btn2.png"><span hw_comment_button_start>点击后，开始说话</span></div></li>
                <li hw_comment_div_done_for_btn style="display: none;"><div class="btn btn2"><img src="/mobile/images/btn2.png"><span hw_comment_button_done>说完，点我结束</span></div></li>
            </ul>
        </div>

        <div class="popBtn" style="display:none;" hw_comment_record_done>
            <ul class="twoBtn">
                <li><div class="btn btn1"><img src="/mobile/images/btn1.png"><span hw_comment_button_restart>重新说</span></div></li>
                <li><div class="btn btn1"><img src="/mobile/images/btn1.png"><span hw_comment_button_send>发送</span></div></li>
            </ul>
      </div>
    </div>

<script type="text/template" id="question_bubble_l">
<div class="dialogBox" hw_message_id="<%=message_id%>">
    <div class="bubble bubble_l">
        <dl>
            <dt>
                <div class="bubble-user"><a href="###"><img src="<%=avatar%>"></a></div>
            </dt>
            <dd>
                <div class="bubble-box">
                    <h6><%=name%></h6>
                    <div class="bubble-cont" data-url="<%=url%>">
                        <div class="bubble-arrow"></div>
                        <div class="bubble-main"><%=content%>
                        </div>
                    </div>
                </div>
            </dd>
        </dl>
        <div class="replyTA popOpen" data-messageid="<%=message_id%>" data-uid="<%=author_id%>" data-name="<%=name%>" data-avatar="<%=avatar%>" data-usertype="<%=user_type%>"></div>
    </div>
</div>
</script>
<script type="text/template" id="bubble_l">
<div class="dialogBox" hw_message_id="<%=message_id%>">
    <div class="bubble bubble_l">
        <dl>
            <dt>
                <div class="bubble-user"><a href="###"><img src="<%=avatar%>"></a></div>
            </dt>
            <dd>
                <div class="bubble-box">
                    <h6><%=name%></h6>
                    <div class="bubble-cont" data-url="<%=url%>">
                        <div class="bubble-arrow"></div>
                        <div class="bubble-main"><%=content%>
                        </div>
                    </div>
                </div>
            </dd>
        </dl>

    </div>
</div>
</script>
<script type="text/template" id="bubble_r">
<div class="dialogBox" hw_message_id="<%=message_id%>">
    <div class="bubble bubble_r">
        <dl>
            <dt>
                <div class="bubble-user"><a href="###"><img src="<%=avatar%>"></a></div>
            </dt>
            <dd>
                <div class="bubble-box">
                    <h6><%=name%></h6>
                    <div class="bubble-cont" data-url="<%=url%>">
                        <div class="bubble-arrow"></div>
                        <div class="bubble-main"><%=content%>
                        </div>
                    </div>
                </div>
            </dd>
            <dd>
            <div class="bubbler-time"><%=time%></div>
            </dd>
        </dl>
    </div>
</div>
</script>
<!-- JavaScript -->

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="<?=config('course.static_url');?>/mobile/js/jquery-1.8.3.js"></script>
<script src="<?=config('course.static_url');?>/mobile/js/lodash.min.js"></script>
<script src="<?=config('course.static_url');?>/mobile/js/teacher.js?id=48"></script>
<script >

    var cid = <?php echo $cid; ?>;
    var uid = <?php echo $uid; ?>;
    var name = "<?php echo $name; ?>";
    var avatar = "<?php echo $avatar; ?>";
    var user_type = <?php echo $user_type; ?>;
    var status = <?=$status;?>;
    var chat_channel = '<?php echo config('course.chat_channel'); ?>';
    var token;
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
                    name: name,
                    avatar: avatar,
                    user_type: user_type,
                    status: status,
                    chat_channel: chat_channel
                };
                huishi.init(options);
            }
        });
    });

</script>
</body>
</html>
