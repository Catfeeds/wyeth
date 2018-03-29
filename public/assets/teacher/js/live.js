$("html").niceScroll({
    styler: "fb",
    cursorcolor: "#e8403f",
    cursorwidth: '6',
    cursorborderradius: '10px',
    background: '#404040',
    spacebarenabled: false,
    cursorborder: '',
    zindex: '1000'
});
if (typeof console == "undefined") {
    this.console = {
        log: function(msg) {}
    };
}
WEB_SOCKET_SWF_LOCATION = "/workman/WebSocketMain.swf";
WEB_SOCKET_DEBUG = true;
// ws 向服务器发送数据
function wssend(data) {
    data = _.extend(data, {
        channel: chat_channel
    });
    if (data.type != 'pong') {
        data = _.extend(data, {
            token: token
        });
    }
    ws.send(JSON.stringify(data));
}

function init() {
    // 创建web socket
    ws = new WebSocket("ws://" + chat_domain + ":7272");
    // 当socket连接打开时，输入用户名
    ws.onopen = function() {
        timeid && window.clearInterval(timeid);
        if (reconnect == false) {
            // 登录
            var login_data = {
                "type": "login",
                "user_type": user_type,
                "author_id": author_id,
                "cid": cid
            };
            wssend(login_data);
            reconnect = true;
        } else {
            // 断线重连
            var re_login_data = {
                "type": "re_login",
                "user_type": user_type,
                "author_id": author_id,
                "cid": cid
            };
            wssend(re_login_data);
        }
    };
    // 当有消息时根据消息类型显示不同信息
    ws.onmessage = function(e) {
        var data = JSON.parse(e.data);
        switch (data['type']) {
            // 服务端ping客户端
            case 'ping':
                wssend({
                    "type": "pong"
                });
                break;
            // 登录 更新用户列表
            case 'login':
                data['content'] = data['name'] + ' 加入了聊天室';
                //say(data);
                if (data['client_list']) {
                    client_list = data['client_list'];
                } else {
                    client_list[data['author_id']] = data['name'];
                }
                flush_client_list();
                console.log(data['name'] + "登录成功");
                break;
            // 断线重连，只更新用户列表
            case 're_login':
                data['content'] = data['name'] + ' 重连成功';
                //say(data);
                console.log(data['name'] + "重连成功");
                break;
            // 发言
            case 'say':
                // say(data, 'new_message');
                break;
                // 用户退出 更新用户列表
            // 转发给讲师
            case 'submit':
                on_message_submit(data);
                break;
            case 'logout':
                data['content'] = data['name'] + ' 退出了';
                //say(data);
                delete client_list[data['author_id']];
                flush_client_list();
                break;
            case 'delete':
                $('#' + data['message_id']).remove();
                break;
            case 'play':
                if (data['playStatus'] == 'qAndA') {
                    alert('现在开始问答环节');
                }
                break;
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
    if (to_client_id != 'all') {
        var at = '@' + to_client_name + ' ';
    }
    var data = {
        type: "say",
        cid: cid,
        user_type: user_type,
        author_id: author_id,
        source_id: source_message_id,
        source_author_id: to_client_id == 'all' ? 0 : to_client_id,
        message_type: "1",
        content: at + input.val().replace(/"/g, '\\"').replace(/\n/g, '\\n').replace(/\r/g, '\\r')
    };
    wssend(data);
    input.val("").focus();
    source_message_id = 0;
}

function delete_msg(msg_id, user_id) {
    $.post("/api/message/delete", {
        messageid: msg_id,
        userid: user_id
    }, function(data) {
        if (data['status'] == 1) {
            //alert('消息删除成功');
        } else {
            alert('消息删除失败');
        }
    });
}
var history_message_id = 0;

function get_history() {
    $.post("/api/message/history", {
        cid: cid,
        messageid: history_message_id
    }, function(data) {
        if (data['status'] == 1) {
            if (data['data']['hasNextPage'] == 0) {
                $('#get-history').off().html('');
            }
            history_message_id = data['data']['messageid'];
            data = data['data']['list'];
            for (i in data) {
                var j = data.length - 1 - i;
                if (j < 0) continue;
                data[j]['author_id'] = data[j]['user_id'];
                say(data[j], 'history');
            }
        } else {
            alert('历史消息获取失败');
        }
    });
}

// 来转给讲师消息时的回调
function on_message_submit(data) {
    var html = process_to_teacher_question(data);
    $('#to_teacher_question').append(html);
}

// 获取转给讲师的问题
function get_to_teacher_question() {
    $.post("/api/message/to_teacher_questions", {
        cid: cid
    }, function(data) {
        if (data['status'] == 1) {
            data = data['data']['list'];
            var html = '';
            _.each(data, function(item) {
                html += process_to_teacher_question(item);
            });
            $('#to_teacher_question').html(html);
        } else {
            alert('转发给老师的问题获取失败');
        }
    });
}

function process_to_teacher_question(item) {
    var data = _.clone(item);
    data.state_html = get_state_html(item);
    var tpl = $('#tpl_message').html();
    var compiled = _.template(tpl);
    return compiled(data);
}

// 获取状态的html
function get_state_html(item) {
    var html = '';
    if (!item.state || item.state == 1) {
        // 待回答
        html = '<a hw_id="'+item.message_id+'" hw_state="1" class="btn btn-success btn-xs" style="margin: 0 10px 0 0" href="javascript:">回复Ta</a>';
    } else if(item.state == 2) {
        // 已回答
        html = '<a hw_id="'+item.message_id+'" hw_state="2" class="btn btn-default btn-xs" style="margin: 0 10px 0 0" href="javascript:">已回答</a>';
    }
    return html;
}

// 回答问题
function do_message_answer(message_id) {
    $.ajax({
        type: "POST",
        url: "/api/message/update",
        data: {messageid: message_id},
        dataType: "json",
        success: function(data){
            console.log(data);
        }
    });
}

// 刷新用户列表框
function flush_client_list() {
    var client_list_select = $("#client_list");
    client_list_select.empty();
    client_list_select.append('<option value="all">所有人</option>');
    for (var p in client_list) {
        client_list_select.append('<option value="' + p + '">' + client_list[p] + '</option>');
    }
    $("#client_list").val(select_client_id);
}
$("#client_list").change(function() {
    select_client_id = $("#client_list option:selected").attr("value");
});

function check_input_len() {
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

// 事件绑定
function initEvent() {
    $('#stop_scroll').click(function() {
        if (is_scroll) {
            is_scroll = false;
            $(this).html('开始滚动').removeClass('btn-danger').addClass('btn-info');
        } else {
            is_scroll = true;
            $(this).html('停止滚动').removeClass('btn-info').addClass('btn-danger');
        }
    });
    $('#get-history').click(function() {
        get_history();
    });


    $('[hw_nav] a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $currentTarget = $(e.currentTarget);
        var showTab = $currentTarget.data('show-tab');
        if (showTab == 'live') {
            $('#to_teacher_question').css("z-index", -1);
            $('#ppt').css("z-index", -1);
            $('#live').css("z-index", 0);
        } else if (showTab == 'ppt') {
            $('#to_teacher_question').css("z-index", -1);
            $('#live').css("z-index", -1);
            $('#ppt').css("z-index", 0);
        } else {
            $('#live').css("z-index", -1);
            $('#ppt').css("z-index", -1);
            $('#to_teacher_question').css("z-index", 0);
        }
    });

    // 回复按钮
    $('#to_teacher_question').on('click', '[hw_id]', function(e) {
        $currentTarget = $(e.currentTarget);
        if ($currentTarget.attr('hw_state') == '2') {
            return alert('已回复');
        }
        // 回复中
        if ($currentTarget.attr('hw_state') == '99') {
            return '';
        }
        var message_id = $currentTarget.attr('hw_id');
        $('#to_teacher_question a[hw_state=99]').attr('hw_state', '2').removeClass('btn-danger').addClass('btn-default');
        $currentTarget.attr('hw_state', '99').removeClass('btn-success').addClass('btn-danger');

        do_message_answer(message_id);
    });

    // 每隔5s收到讲师直播时下发状态消息
    $('#live').on('teacherPublish', function(e, options) {
        var data = {
            type: "say",
            message_type: "teacherPublish",
            cid: options.cid,
            author_id: author_id
        };
        wssend(data);
    });
}
