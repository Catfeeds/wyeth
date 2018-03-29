<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="">

    <title>讲师后台</title>

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

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="/admin_style/flatlab/js/html5shiv.js"></script>
    <script src="/admin_style/flatlab/js/respond.min.js"></script>
    <![endif]-->

    <!-- workman -->
    <script type="text/javascript" src="/workman/swfobject.js"></script>
    <script type="text/javascript" src="/workman/web_socket.js"></script>
    <script type="text/javascript" src="/workman/json.js"></script>
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
                        直播统计<a href="/teacher/index" style="float: right; font-size: 12px;">返回课程列表</a>
                    </p>
                    <table class="table" style="margin-bottom: 0; font-size: 12px;display:none">
                        <thead>
                        <tr>
                            <th style="padding: 2px;">累计在线人数</th>
                            <th style="padding: 2px;">累计消息数</th>
                            <th style="padding: 2px;">累计可发言人数</th>
                            <th style="padding: 2px;">累计发言人数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="padding: 2px;">20000</td>
                            <td style="padding: 2px;">8838</td>
                            <td style="padding: 2px;">5988</td>
                            <td style="padding: 2px;">12888</td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table" style="margin-bottom: 0; font-size: 12px;">
                        <thead>
                        <tr>
                            <th style="padding: 2px;">当前在线人数</th>
                            <th style="padding: 2px;">有效问题数</th>
                            <th style="padding: 2px;">课程医师答题数</th>
                            <th style="padding: 2px;">－－－－－－</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="padding: 2px;">234444</td>
                            <td style="padding: 2px;">234</td>
                            <td style="padding: 2px;">45</td>
                            <td style="padding: 2px;"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </aside>

    <section class="panel" style="position: absolute; top:138px; bottom: 98px; width: 70%;">
        <header class="panel-heading tab-bg-dark-navy-blue" style="background-color: #aec785;">
            <ul class="nav nav-tabs nav-justified ">
                <li class="active">
                    <a href="#dialog" data-toggle="tab" aria-expanded="true">
                        直播大厅
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
            <textarea class="form-control  t-text-area" rows="2" placeholder="消息内容" style="width: 84%; float: left;"></textarea>
            <button onclick="send_msg()" class="btn btn-info pull-right" type="button" style="width: 12%; float: right;">
                发送
            </button>
        </div>
    </section>

    <div style="width:12%; position: fixed; top:0; right: 1.5%; text-align: center;display:none">
        <p>扫二维码进入讲课页</p>
        <img src="http://static01.fn-mart.com/product/images/index/APP.png"/>
    </div>
</div>

</body>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script src="<?=config('course.static_url');?>/mobile/js/lodash.min.js"></script>
<script src="/admin_style/flatlab/js/jquery.nicescroll.js"></script>

<script type="text/javascript">
    $("html").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', spacebarenabled:false,  cursorborder: '', zindex: '1000'});

    if (typeof console == "undefined") { this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "/workman/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var cid = <?=$course_info->id?>;
    var user_type = 3;
    var author_id = <?=$course_info->teacher_uid?>;
    var anchor_id = <?=$course_info->anchor_uid?>;
    var select_client_id = 'all';
    var source_message_id = 0;
    var ws, client_list={}, timeid, reconnect=false;
    var is_scroll = true;
    var chat_channel = '<?php echo config('course.chat_channel'); ?>';
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
        ws = new WebSocket("ws://"+document.domain+":7272");
        // 当socket连接打开时，输入用户名
        ws.onopen = function() {
            timeid && window.clearInterval(timeid);
            if(reconnect == false) {
                // 登录
                var login_data = {"type":"login","user_type":user_type, "author_id":author_id, "cid":cid};
                console.log("web socket握手成功，发送登录数据:"+login_data);
                wssend(login_data);
                reconnect = true;
            } else {
                // 断线重连
                var re_login_data = {"type":"re_login","user_type":user_type, "author_id":author_id, "cid":cid};
                console.log("web socket握手成功，发送重连数据:"+re_login_data);
                wssend(re_login_data);
            }
        };
        // 当有消息时根据消息类型显示不同信息
        ws.onmessage = function(e) {
            var data = JSON.parse(e.data);
            switch(data['type']){
                // 服务端ping客户端
                case 'ping':
                    wssend({"type":"pong"});
                    break;
                // 登录 更新用户列表
                case 'login':
                    data['content'] = data['name']+' 加入了聊天室';
                    //say(data);
                    if(data['client_list']) {
                        client_list = data['client_list'];
                    } else {
                        client_list[data['author_id']] = data['name'];
                    }
                    flush_client_list();
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
                    say(data, 'new_message');
                    if (is_scroll) {
                        $('#dialog').scrollTop($('#dialog')[0].scrollHeight);
                    }
                    break;
                // 用户退出 更新用户列表
                case 'logout':
                    data['content'] = data['name']+' 退出了';
                    //say(data);
                    delete client_list[data['author_id']];
                    flush_client_list();
                case 'delete':
                    $('#'+data['message_id']).remove();
            }
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
            message_type: "1",
            content: at+input.val().replace(/"/g, '\\"').replace(/\n/g,'\\n').replace(/\r/g, '\\r')
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
            if (data['message_type'] == 2){
                data['content'] = '<audio controls="controls" style="margin-top:10px;">'+
                '<source src="'+data['content']['url']+'" type="audio/mpeg">'+
                '您的浏览器不支持音频插件，你换chrome浏览器访问'+
                '</audio></p>';
            }
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
            if (data['author_id'] == anchor_id) {
                var anchor_tag = '<a class="btn btn-warning btn-xs" style="margin: 0 10px 0 0">主持人</a>';
            } else {
                var anchor_tag = '';
            }
            if (data['message_id'] == undefined) {
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
                        anchor_tag+
                    '</div>'+
                    '<p>'+data['content']+'</p>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+
                    '</div>';
            } else {
                if (data['message_type'] == 2){
                    data['content'] = '<audio controls="controls" style="margin-top:10px;">'+
                        '<source src="'+data['content']['url']+'" type="audio/mpeg">'+
                        '您的浏览器不支持音频插件，你换chrome浏览器访问'+
                        '</audio></p>';
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
                        anchor_tag+
                    '<a class="btn btn-success btn-xs" style="margin: 0 10px 0 0" href="javascript:reply(\''+data['author_id']+'\',\''+data['name']+'\',\''+data['message_id']+'\')">回复Ta</a>'+
                    //'<a class="btn btn-default btn-xs" style="margin: 0 10px 0 0" href="javascript:delete_msg(\''+data['message_id']+'\',\''+data['author_id']+'\')">不可见</a>'+
                    '</div>'+
                    '<p>'+data['content']+'</p>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+
                    '</div>';
            }
        }
        if (type == 'history') {
            $("#get-history").after(message_html);
        } else {
            $("#dialog").append(message_html);
        }
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

    var history_message_id = 0;
    function get_history(){
        $.post("/api/message/history", { cid:cid, messageid:history_message_id },
            function(data){
                if (data['status'] == 1){
                    if (data['data']['hasNextPage'] == 0) {
                        $('#get-history').off().html('');
                    }
                    history_message_id = data['data']['messageid'];
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
        $.post("/api/message/question", { cid:cid, userid:author_id },
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
                            '<div class="activity '+data[i]['message_id']+'">'+
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
            flush_client_list();
        }
        $("#client_list").val(author_id);
        $('.t-text-area').focus();
        source_message_id = msg_id;
    }
    // 刷新用户列表框
    function flush_client_list(){
        var client_list_select = $("#client_list");
        client_list_select.empty();
        client_list_select.append('<option value="all">所有人</option>');
        for(var p in client_list){
            client_list_select.append('<option value="'+p+'">'+client_list[p]+'</option>');
        }
        $("#client_list").val(select_client_id);
    }
    $("#client_list").change(function(){
        select_client_id = $("#client_list option:selected").attr("value");
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
    var token;
    $(function() {
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
                init();
                get_history();
            }
        });
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
</script>

</html>