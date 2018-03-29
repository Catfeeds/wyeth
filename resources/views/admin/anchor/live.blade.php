<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="">

    <title>主持人后台</title>

    <!-- Bootstrap core CSS -->
    <link href="/admin_style/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/admin_style/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!--right slidebar-->
    <link href="/admin_style/flatlab/css/slidebars.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/admin_style/flatlab/css/style.css" rel="stylesheet">
    <link href="/admin_style/flatlab/css/style-responsive.css" rel="stylesheet" />
    <link href="/mobile/css/swiper.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/admin_style/flatlab/js/html5shiv.js"></script>
    <script src="/admin_style/flatlab/js/respond.min.js"></script>
    <![endif]-->

    <!-- workman -->
    <script type="text/javascript" src="/workman/web_socket.js"></script>
    <script type="text/javascript" src="/workman/json.js"></script>
    <style>html,body{height: 100%; padding: 0; margin: 0;}</style>
</head>
<body>

<div class="container" style="width:100%; height: 100%; padding-left: 5%;">

    <aside class="profile-nav alt green-border" style="position: fixed;top: 0;width: 90%;">
        <section class="panel">
            <div class="user-heading alt green-bg" style="padding: 8px; position: relative;">
                <p style="font-size: 16px;">
                     {{$course_info->title}}
                </p>
                <a href="#" style="margin-left: 0">
                    <img alt="" src="{{$course_info->teacher_avatar}}" style="width:70px; height: 70px;">
                </a>
                <p style="font-size: 16px; font-weight: bolder; margin: 14px 0 4px 0"> {{$course_info->teacher_name}}</p>
                <p style="font-size: 14px; margin-bottom: 4px; width: 50%;">
                    {{$course_info->teacher_hospital}}
                    {{$course_info->teacher_position}}
                </p>
                @if ($teacher_status)
                <p style="font-size: 12px;" id="teacher_status">当前在线</p>
                @else
                <p style="font-size: 12px; color: gray;" id="teacher_status">当前离线</p>
                @endif
                <div style="position: absolute; top:0; right:0; width:50%; padding: 8px;">
                    <p style="font-size: 16px; margin-bottom:0;">
                        课程设置<a href="/admin/anchor/index" style="float: right; font-size: 12px;">返回课程列表</a>
                    </p>
                    <div class="radios" style="overflow: hidden; font-size: 12px;">
                        <label class="label_radio" for="radio-01" style="width: 64px; float: left;">
                            <input name="course_status" id="radio-01" value="1" type="radio"@if ($course_info->status == 1) checked="checked"@endif> 报名中
                        </label>
                        <label class="label_radio" for="radio-02" style="width: 64px; float: left;">
                            <input name="course_status" id="radio-02" value="2" type="radio"@if ($course_info->status == 2) checked="checked"@endif> 进行中
                        </label>
                        <label class="label_radio" for="radio-03" style="width: 64px; float: left;">
                            <input name="course_status" id="radio-03" value="3" type="radio"@if ($course_info->status == 3) checked="checked"@endif> 已结束
                        </label>
                        <span style="cursor:pointer;" id="estimation">
                            <button class="btn btn-info" type="button">打分</button>
                        </span>
                        <span @if ($course_info->status != 2) style="display:none" @endif style="cursor:pointer; " id="startLivePlaying">
                            <button class="btn btn-info" type="button">开始直播播放</button>
                        </span>
                        @if ($type == 'recorded')
                            <span @if ($course_info->status != 2) style="display:none" @endif style="cursor:pointer; " id="startQuestionPlaying">
                                <button class="btn btn-info" type="button">开始问答环节</button>
                            </span>
                        @endif
                    </div>
                   <div>
                        <!--
                        <span style="margin-bottom: 10px;">发言概率: (万分之)</span>
                        <input type="text" size="5" maxlength="5" name="chance" id="chance"  value=" {{$course_info->speak_chance}}" style="color: gray; border-radius:2px; border: 0; height:22px;width: 60px;">
                        <input id="subChance" type="submit" class="btn btn-primary btn-xs" value="提交">
                        -->
                        提醒：
                        <div class="switch" id="mySwitch" data-on="primary" data-off="info" style="position: absolute;border-radius: 20px;">
                           <input type="checkbox" checked />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </aside>

    <section class="panel" style="position: absolute; top:138px; bottom: 98px; width: 90%;">
        <header class="panel-heading tab-bg-dark-navy-blue" style="background-color: #aec785;">
            <ul class="nav nav-tabs nav-justified ">
                <li class="active">
                    <a href="#dialog" data-toggle="tab" aria-expanded="true">
                        直播大厅
                    </a>
                </li>
                <li>
                    <a href="#chat_user" data-toggle="tab" aria-expanded="true">
                        用户讨论
                    </a>
                </li>
                <li class="">
                    <a href="#to_teacher_question" data-toggle="tab" aria-expanded="false" onclick="get_to_teacher_question()">
                        转发给老师的问题
                    </a>
                </li>
            </ul>
        </header>
        <div class="panel-body profile-activity">
            <div class="tab-content tasi-tab">
                <div class="tab-pane active" id="dialog" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; overflow: auto; padding: 15px; margin-top: 40px;">
                    <a id="get-history" style="cursor: pointer; display: block; text-align: center;">点击获取更多历史消息</a>
                </div>
                <div class="tab-pane" id="chat_user" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; overflow: auto; padding: 15px; margin-top: 40px;">
                    <chat-user></chat-user>
                </div>
                <div class="tab-pane" id="to_teacher_question" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; overflow: auto; padding: 15px; margin-top: 40px;">
                </div>
            </div>
        </div>
    </section>

    <section class="panel" style="padding:10px 0; position: fixed;bottom: 0;margin: 0;width: 70%; ">
        <div>
            <select class="form-control input-sm" id="client_list" style="width:20%; float:left; margin:0 0 5px 15px;">
                <option value="all">所有人</option>
            </select>

            <a class="btn btn-danger btn-xs" style="margin: 4px 0 0 10px" id="stop_scroll">停止滚动</a>
        </div>
        <div class="col-sm-12">
            <textarea maxlength="1000" class="form-control  t-text-area" rows="2" placeholder="消息内容" style="width: 84%; float: left;"></textarea>
            <button onclick="send_msg()" class="btn btn-info pull-right" type="button" style="width: 12%; float: right;">
                发送
            </button>
        </div>
    </section>

</div>
</body>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap-switch.js"></script>
<script src=" /mobile/js/lodash.min.js"></script>
<script type="text/javascript" src="/mobile/js/swiper.jquery.min.js"></script>

<script type="text/javascript">

    if (typeof console == "undefined") { this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "/workman/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;

    var USERTYPES = {user: 1, anchor: 2, teacher: 3 };
    var cid =  {{$course_info->id}};
    var user_type = 2;
    var teacher_author_id = {{$teacher_uid}};
    var author_id = {{$anchor_uid}};
    var select_client_id = 'all';
    var source_message_id = 0;
    var ws, client_list={}, timeid, reconnect=false;
    var is_scroll = true;
    var chat_domain = '{{config('course.chat_domain')}}';
    var chat_channel = '{{config('course.chat_channel')}}';
    var staticUrl = '';
    var token = '{{$token}}';
    $.ajaxSetup({
        beforeSend: function(xhr) {
            if (!token) {
                console.log('token empty before ajax send');
                return false;
            }
            xhr.setRequestHeader('Authorization', 'bearer ' + token);
        }
    });

    // ws 向服务器发送数据
    function wssend(data) {
        data = _.extend(data, {channel: chat_channel});
        if (data.type != 'pong') {
            data = _.extend(data, {token: token});
        }
        ws.send(JSON.stringify(data));
    }

    function init() {
        // 创建web socket
        ws = new WebSocket("ws://"+chat_domain+":7272");
        // 当socket连接打开时，输入用户名
        ws.onopen = function() {
            timeid && window.clearInterval(timeid);
            if(reconnect == false) {
                // 登录
                var login_data = {"type":"login","user_type":user_type, "author_id":author_id, "cid":cid};
                wssend(login_data);
                reconnect = true;
            } else {
                // 断线重连
                var re_login_data = {"type":"re_login","user_type":user_type, "author_id":author_id, "cid":cid};
                wssend(re_login_data);
            }
        };
        // 当有消息时根据消息类型显示不同信息
        ws.onmessage = function(e) {
            var data = JSON.parse(e.data);
            var eventName = 'ws.message.' + data.type;
            switch(data['type']){
                // 服务端ping客户端
                case 'ping':
                    wssend({"type":"pong"});
                    break;
                // 登录 更新用户列表
                case 'login':
                    data['content'] = data['name']+' 加入了聊天室';
                    // say(data);
                    if(data['client_list']) {
                        client_list = data['client_list'];
                    } else {
                        client_list[data['author_id']] = data['name'];
                    }
                    if(data['author_id'] != author_id) {
                        flush_client_list('login', data['author_id']);
                    }
                    // 讲师登录
                    if (data['user_type'] == USERTYPES.teacher) {
                        teacher_author_id = data['author_id'];
                        $("#teacher_status").html('当前在线').css({color:'white'});
                    }
                    console.log(data['name']+"登录成功");
                    break;
                // 断线重连，只更新用户列表
                case 're_login':
                    data['content'] = data['name']+' 重连成功';
                    //say(data);
                    console.log(data['name']+"重连成功");
                    break;
                // 发言
                case 'say':
                    if (data.message_type == 1 && data.content) {
                        say(data, 'new_message');
                        if (is_scroll) {
                            $('#dialog').scrollTop($('#dialog')[0].scrollHeight);
                        }
                    }

                    var eventName = 'ws.message.say.' + data.message_type;
                    if (data.message_type == 1)  {
                        data.message_type = 'question';
                        eventName = 'ws.message.say.question';
                    }
                    break;
                // 用户退出 更新用户列表
                case 'logout':
                    data['content'] = data['name']+' 退出了';
                    //say(data);
                    delete client_list[data['author_id']];
                    flush_client_list('logout', data['author_id']);
                    // 讲师退出
                    if (data['user_type'] == USERTYPES.teacher) {
                        teacher_author_id = data['author_id'];
                        $("#teacher_status").html('当前离线').css({color:'gray'});
                    }
                case 'delete':
                    $('.'+data['message_id']).remove();
            }
            $(document).trigger(eventName, data);
        };
        ws.onclose = function() {
            console.log("连接关闭，定时重连");
            // 定时重连
            window.clearInterval(timeid);
            timeid = window.setInterval(init, 3000);
        };
        ws.onerror = function() {
            console.log("出现错误");
        };
    }

    // 提交对话
    function send_msg() {
        if (!check_input_len()) return false;
        var input = $('.t-text-area');
        var to_client_id = $("#client_list option:selected").attr("value");
        var to_client_name = $("#client_list option:selected").html();
        var at = '';
        if (to_client_id!='all'){
            var at = '@'+to_client_name+' ';
        }
        var data = {
            type : "say",
            cid: cid,
            user_type: user_type,
            author_id: author_id,
            source_id: source_message_id,
            source_author_id:to_client_id=='all' ? 0 : to_client_id,
            message_type: "chatUser",
            content: at+input.val()
//            content: at+input.val().replace(/"/g, '\\"').replace(/\n/g,'\\n').replace(/\r/g, '\\r')
        };
        console.log(data);
        wssend(data);
        input.val("").focus();
        source_message_id = 0;
    }

    // 发言
    function say(data, type){
        var message_html = '';
        if (data['author_id'] == author_id) {
            message_html =
                '<div class="activity alt">'+
                '<span>'+
                '<img src="'+data['avatar']+'" style="-webkit-border-radius:50%; border-radius: 50%; width:45px;">'+
                '</span>'+
                '<div class="activity-desk">'+
                '<div class="panel" style="margin-bottom: 0px;">'+
                '<div class="panel-body" style="padding: 10px;">'+
                '<div class="arrow-alt"></div>'+
                '<div>'+
                '<a class="btn btn-white btn-xs" style="margin: 0 10px 0 0">'+
                '<i class=" fa fa-clock-o" style="margin-right: 2px;"></i>'+data['time']+'  '+data['name']+
                '</a>'+
                '</div>'+
                '<p>'+data['content']+'</p>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>';
        } else {
            if (data['message_id'] == undefined) {
                if (data['author_id'] == teacher_author_id) {
                    var to_teacher = '<a class="btn btn-warning btn-xs" style="margin: 0 10px 0 0">讲师</a>';
                } else {
                    var to_teacher = '';
                }
                message_html =
                    '<div class="activity" id="'+data['message_id']+'">'+
                    '<span>'+
                    '<img src="'+data['avatar']+'" style="-webkit-border-radius:50%; border-radius: 50%; width:45px;">'+
                    '</span>'+
                    '<div class="activity-desk">'+
                    '<div class="panel" style="margin-bottom: 0px;">'+
                    '<div class="panel-body" style="padding: 10px;">'+
                    '<div class="arrow"></div>'+
                    '<div>'+
                    '<a class="btn btn-white btn-xs" style="margin: 0 10px 0 0">'+
                    '<i class=" fa fa-clock-o" style="margin-right: 2px;"></i>'+data['time']+'  '+data['name']+
                    '</a>'+
                        to_teacher+
                    '</div>'+
                    '<p>'+data['content']+'</p>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+
                    '</div>';
            } else {
                // audio
                if (data['message_type'] == 2) {
                    data['content'] = '<audio controls="controls" style="margin-top:10px;">' +
                    '<source src="' + data['content']['url'] + '" type="audio/mpeg">' +
                    '您的浏览器不支持音频插件，你换chrome浏览器访问' +
                    '</audio></p>';
                }
                if (data['author_id'] == teacher_author_id) {
                    var to_teacher = '<a class="btn btn-warning btn-xs" style="margin: 0 10px 0 0">讲师</a>';
                    // 已转给讲师的话,回答后删除
                    if (data['source_id']) {
                        remove_to_teacher_message(data['source_id']);
                    }
                } else if(data['author_id'] == 0) {
                    var to_teacher = '<a class="btn btn-warning btn-xs" style="margin: 0 10px 0 0">主持人</a>';
                } else {
                    if (data['state'] == 0) {
                        var to_teacher = '<a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)" onclick="to_teacher(\'' + data['message_id'] + '\',\'' + data['author_id'] + '\', this)">转发给讲师</a>';
                    } else if (data['state'] == 1) {
                        var to_teacher = '<a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">已转发</a>';
                    } else if (data['state'] == 2) {
                        var to_teacher = '<a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">已回答</a>';
                    } else if (data['state'] == 3) {
                        var to_teacher = '<a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">已转发给第三方</a>';
                    } else if (data['state'] == 4) {
                        var to_teacher = '<a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">已被第三方回答</a>';
                    } else if (data['state'] == 5) {
                        var to_teacher = '<a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">已被第三方关闭</a>';
                    } else {
                        var to_teacher = '<a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)" onclick="to_teacher(\'' + data['message_id'] + '\',\'' + data['author_id'] + '\', this)">转发给讲师</a>';
                    }
                }
                message_html =
                    '<div class="activity" hw_message_id="' + data['message_id'] + '">' +
                    '<span>' +
                    '<img src="' + data['avatar'] + '" style="-webkit-border-radius:50%; border-radius: 50%; width:45px;">' +
                    '</span>' +
                    '<div class="activity-desk">' +
                    '<div class="panel" style="margin-bottom: 0px;">' +
                    '<div class="panel-body" style="padding: 10px;">' +
                    '<div class="arrow"></div>' +
                    '<div>' +
                    '<a class="btn btn-white btn-xs" style="margin: 0 10px 0 0">' +
                    '<i class=" fa fa-clock-o" style="margin-right: 2px;"></i>' + data['time'] + '  ' + data['name'] +
                    '</a>' +
                    to_teacher +
                    '<a class="btn btn-success btn-xs forwarding" style="margin: 0 10px 0 0" href="javascript:reply(\'' + data['author_id'] + '\',\'' + data['name'] + '\',\'' + data['message_id'] + '\')">回复Ta</a>' +
                    '<a class="btn btn-default btn-xs" style="margin: 0 10px 0 0" href="javascript:delete_msg(\'' + data['message_id'] + '\',\'' + data['author_id'] + '\')">不可见</a>' +
                    '</div>' +
                    '<p>' + data['content'] + '</p>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            }
        }
        if (type == 'history') {
            $("#get-history").after(message_html);
        } else {
            $("#dialog").append(message_html);
        }
    }


    // 转发给讲师的问题,删除
    function remove_to_teacher_message(message_id) {
        $('#to_teacher_question').find('[hw_message_id='+ message_id +']').remove();
    }

    function delete_msg(msg_id, user_id){
        $.post("/api/message/delete", { messageid: msg_id, userid: user_id },
            function(data){
                if (data['status'] == 1){
                    //alert('消息删除成功');
                } else {
                    alert('消息删除失败');
                }
                console.log(data);
            }
        );
    }
    function to_teacher(messageid, userid, t){
        if ($(t).attr('is_forward') == 1) return false;
        if (userid == 0) {
            alert('主持人的的消息不能转发给讲师');
            return false;
        }
        $.post("/api/message/submit", { cid: cid, messageid: messageid, userid: userid, lecturerid: teacher_author_id },
            function(data){
                if (data['status'] == 1){
                    $(t).html('已转发').attr('is_forward', 1);
                    alert('消息转发成功');
                } else {
                    alert('消息转发失败');
                }
            }
        );
    }

    var history_message_id = 0;
    function get_history(){
        $.post("/api/message/questions", { cid: cid, messageid: history_message_id },
            function(data){
                if (data['status'] == 1){
                    history_message_id = data['data']['messageid'];
                    if (data['data']['hasNextPage'] == 0 || history_message_id == 0) {
                        $('#get-history').off().html('');
                    }
                    data = data['data']['list'];
                    for(i in data){
                        var j = data.length -1 - i;
                        if (j < 0) continue;
                        data[j]['author_id'] = data[j]['user_id'];
                        say(data[j], 'history');
                    }
                } else {
                    alert('历史消息获取失败');
                }
            }
        );
    }

    function get_to_teacher_question(){
        $.post("/api/message/to_teacher_questions", { cid:cid, userid:teacher_author_id, type:'anchor'},
            function(data){
                if (data['status'] == 1){
                    data = data['data']['list'];
                    var html = '';
                    for(i in data){
                        var to_teacher = '';
                        /*if (data[i]['state'] == 1) {
                            var to_teacher = '';
                        } else if (data[i]['state'] == 2) {
                            var to_teacher = '<a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">已回答</a>';
                        }*/
                        html +=
                            '<div class="activity" hw_message_id="'+data[i]['message_id']+'">'+
                            '<span>'+
                            '<img src="'+data[i]['avatar']+'" style="-webkit-border-radius:50%; border-radius: 50%; width:45px;">'+
                            '</span>'+
                            '<div class="activity-desk">'+
                            '<div class="panel" style="margin-bottom: 0px;">'+
                            '<div class="panel-body" style="padding: 10px;">'+
                            '<div class="arrow"></div>'+
                            '<div>'+
                            '<a class="btn btn-white btn-xs" style="margin: 0 10px 0 0">'+
                            '<i class=" fa fa-clock-o" style="margin-right: 2px;"></i>'+data[i]['time']+'  '+data[i]['name']+
                            '</a>'+
                            to_teacher+
                            //'<a class="btn btn-success btn-xs forwarding" style="margin: 0 10px 0 0" href="javascript:reply(\''+data[i]['user_id']+'\',\''+data[i]['name']+'\',\''+data[i]['message_id']+'\')">回复Ta</a>'+
                            '<a class="btn btn-default btn-xs" style="margin: 0 10px 0 0" href="javascript:delete_msg(\''+data[i]['message_id']+'\',\''+data[i]['user_id']+'\')">不可见</a>'+
                            '</div>'+
                            '<p>'+data[i]['content']+'</p>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>';
                    }
                    $('#to_teacher_question').html(html);
                } else {
                    alert('转发给老师的问题获取失败');
                }
            }
        );
    }
    function reply(author_id, name, msg_id){
        var has_author_id = false;
        $("#client_list option").each(function(){
             if ($(this).val() == author_id) {
                 has_author_id = true;
             }
        });
        if (!has_author_id) {
            client_list[author_id] = name;
            flush_client_list('login', author_id);
        }
        $("#client_list").val(author_id);
        $('.t-text-area').focus();
        source_message_id = msg_id;
    }

    // 刷新用户列表框
    function flush_client_list(type, author_id) {
        var client_list_select = $("#client_list");
        var has_author_id = false;
        $("#client_list option").each(function(){
            if ($(this).val() == author_id) {
                has_author_id = true;
            }
        });
        if (!has_author_id) {
            client_list_select.append('<option value="'+author_id+'">'+client_list[author_id]+'</option>');
        }
    }
    $("#client_list").change(function(){
        select_client_id = $("#client_list option:selected").attr("value");
    });

    $("input[name='course_status']").click(function(){
        var status = $(this).val();
        $.post("/admin/anchor/changeCourseStatus", { cid:cid, status:status },
            function(data){
                if (data == 1){
                    alert('课程状态修改成功');
                    if (status == 2) {
                        $('#startPlaying').show();
                    } else {
                        $('#startPlaying').hide();
                    }
                } else {
                    alert('课程状态修改失败');
                }
            }
        );
    });
    $("input[name='speak_status']").click(function(){
        var checked = $(this).prop('checked');
        var status = checked ? 1 : 0;
        $.post("/admin/anchor/changeCourseSpeakStatus", { cid:cid, status:status },
            function(data){
                if (data == 1){
                    alert('禁言状态修改成功');
                } else {
                    alert('禁言状态修改失败');
                }
            }
        );
    });
    $("#subChance").on('click', function(){
        var chance = $("#chance").val();
        $.post("/api/message/send_chance",
            {cid: cid, chance: chance},
            function(data) {
                if (data.status == 1) {
                    alert('抢话筒概率修改成功');
                } else {
                    alert('抢话筒概率修改失败，请刷新后重试');
                }
            }
        );
    });
    function check_input_len(){
        var str = $('.t-text-area').val();
        if (getStrLength(str) > 60) {
            alert('输入的内容要为30个中文字符或60个英文字符');
            return false;
        }
        return true;
    }
    function getStrLength(str) {
        var cArr = str.match(/[^\x00-\xff]/ig);
        return str.length + (cArr == null ? 0 : cArr.length);
    }

    $(function(){
        $('#mySwitch').on('switch-change', function (e, data) {
            var status = data.value?1:0;
            $.post("/admin/anchor/changeReplyNotifyStatus", { cid:cid, status:status },
                function(data){console.log(data);
                    if (data == 1){
                        alert('设置回复提醒成功');
                    } else {
                        alert('设置回复提醒失败');
                    }
                }
            );

        });
        init();
        get_history();
        // 初始化 vue 写的app
        $(document).trigger('main.getToken.done');
    });

    $('#stop_scroll').click(function(){
        if (is_scroll) {
            is_scroll = false;
            $(this).html('开始滚动').removeClass('btn-danger').addClass('btn-info');
        } else {
            is_scroll = true;
            $(this).html('停止滚动').removeClass('btn-info').addClass('btn-danger');
        }
    });

    $('#get-history').click(function(){
        get_history();
    });

    //打分
    $('#estimation').click(function(){
        if(!window.confirm('确定要通知用户打分吗? 一节课只能打一次分')){
            return;
        }
        var me = $(this);
        $.ajax({
            type : 'post',
            data : {
                cid : cid
            },
            url : '/admin/anchor/noticeEstimationBoardcast',
            success : function(){
                me.html('已提醒');
                me.css('color', 'red');
                alert('已通知用户打分');
            },
            error : function(){
                window.console.log('通知打分失败');
            }
        });
    });

    //开始直播播放
    var type_live_played = false;
    $('#startLivePlaying').click(function(){
        if (type_live_played){
            return;
        }
        if(!window.confirm('确定通知用户开始播放吗？')){
            return;
        }
        type_live_played =true;
        var me = $(this);
        $.ajax({
            type: 'post',
            data: {
              cid: cid,
                playStatus: 'living'
            },
            url: '/admin/anchor/noticeCoverBoardcast',
            success: function() {
                me.html('已通知用户开始直播播放');
                me.css('color', 'red');
                alert('已通知用户开始直播播放');
            },
            error: function() {
                type_live_played = false;
                window.console.log('通知用户开始直播播放失败');
           }
        });
    });
    //开始直播播放
    var type_question_played = false;
    $('#startQuestionPlaying').click(function(){
        if (type_question_played){
            return;
        }
        if(!window.confirm('确定通知用户开始问答环节吗？')){
            return;
        }
        type_question_played =true;
        var me = $(this);
        $.ajax({
            type: 'post',
            data: {
                cid: cid,
                playStatus: 'qAndA'
            },
            url: '/admin/anchor/noticeCoverBoardcast',
            success: function() {
                me.html('已通知用户开始问答环节');
                me.css('color', 'red');
                alert('已通知用户开始问答环节');
            },
            error: function() {
                type_question_played = false;
                window.console.log('通知用户开始问答环节失败');
            }
        });
    });
</script>
<script src="/build/{{$assetConfig['admin-anchor-live']['js']}}"></script>

</html>
