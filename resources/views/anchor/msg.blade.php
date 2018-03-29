<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="">

    <title>主持人后台-聊天记录管理</title>

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
                     {{$courseInfo->title}}
                </p>
                <a href="#" style="margin-left: 0">
                    <img alt="" src="{{$courseInfo->teacher_avatar}}" style="width:70px; height: 70px;">
                </a>
                <p style="font-size: 16px; font-weight: bolder; margin: 14px 0 4px 0"> {{$courseInfo->teacher_name}}</p>
                <p style="font-size: 14px; margin-bottom: 4px;">
                    {{$courseInfo->teacher_hospital}}
                    {{$courseInfo->teacher_position}}
                </p>
                <div style="position: absolute; top:0; right:0; width:50%; padding: 8px;">
                    <p style="font-size: 16px; margin-bottom:0;">
                        课程设置<a href="/anchor/index" style="float: right; font-size: 12px;">返回课程列表</a>
                    </p>
                    <div style="margin: 14px 0 4px 0;">
                        <input id="notifyNew" type="button" class="btn btn-primary btn-xs" value="提醒医生回答问题" cid="{{$courseInfo->id}}">
                    </div>
                </div>
            </div>
        </section>
    </aside>

    <section class="panel" style="position: absolute; top:138px; bottom: 0px; margin-bottom: 8px; width: 70%;">
        <header class="panel-heading tab-bg-dark-navy-blue" style="background-color: #aec785;">
            <ul class="nav nav-tabs nav-justified ">
                <li class="active">
                    <a href="#dialog" data-toggle="tab" aria-expanded="true">
                        聊天记录
                    </a>
                </li>
            </ul>
        </header>
        <div class="panel-body profile-activity">
            <div class="tab-content tasi-tab">
                <div class="tab-pane active" id="dialog" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; overflow: auto; padding: 15px; margin-top: 40px;">
                @foreach ($userList as $user)
                <div style="position: relative; overflow: hidden; border:1px solid #aec785; padding: 10px 10px 0px 10px; margin-bottom: 10px; border-radius: 4px;">
                    @foreach ($user['msgList'] as $msg)
                    <div class="activity">
                        <span>
                            <img src="{{$user['avatar']}}" style="-webkit-border-radius:50%; border-radius: 50%; width:45px;">
                        </span>
                        <div class="activity-desk">
                            <div class="panel" style="margin-bottom: 0px;">
                                <div class="panel-body" style="padding: 10px;">
                                    <div class="arrow"></div>
                                    <div>
                                        <a class="btn btn-white btn-xs" style="margin: 0 10px 0 0">
                                        <i class=" fa fa-clock-o" style="margin-right: 2px;"></i>{{$msg['created_at']}}  {{$user['nickname']}}</a>
                                        <div id="state{{$msg['id']}}" style="display: inline-block">
                                        @if ($msg['state'] == 0 && $user['hasUnSendMsg'] == 1)
                                            <input name="msg_id[]" value="{{$msg['id']}}" type="checkbox">
                                        @elseif ($msg['state'] == \App\Models\Message::HAS_SEND)
                                            <a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">
                                                已转发
                                            </a>
                                            @if ($user['hasUnSendMsg'] == 1)
                                            <input name="msg_id[]" value="{{$msg['id']}}" type="checkbox">
                                            @endif
                                        @elseif ($msg['state'] == \App\Models\Message::ANSWERED)
                                            <a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">
                                                已回答
                                            </a>
                                        @elseif ($msg['state'] == \App\Models\Message::HAS_SEND_XJT)
                                            <a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">
                                                已转发给第三方
                                            </a>
                                        @elseif ($msg['state'] == \App\Models\Message::ANSWERED_BY_XJT)
                                            <a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">
                                                已被第三方回答
                                            </a>
                                        @elseif ($msg['state'] == \App\Models\Message::CLOSED_BY_XJT)
                                            <a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">
                                                已被第三方关闭
                                            </a>
                                        @else
                                        @endif
                                        </div>
                                    </div>
                                    <p>{{$msg['content']}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if ($user['hasUnSendMsg'] == 1)
                    <button type="submit" class="btn btn-info" style="position: absolute; right:10px; bottom: 10px;">发送给易健通</button>
                    @endif
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
</body>

<!-- js placed at the end of the document so the pages load faster -->
<script src="/admin_style/flatlab/js/jquery.js"></script>
<script src="/admin_style/flatlab/js/bootstrap.min.js"></script>
<script src="/admin_style/flatlab/js/bootstrap-switch.js"></script>
<script src=" {{config('course.static_url')}}/mobile/js/lodash.min.js"></script>

<script>
$(function($) {
    $('button').click(function(){
        var that = $(this);
        if ($(this).attr('is_send') > 0) {
            clearTimeout($(this).attr('is_send'));
            $(this).html('发送给易健通').attr('is_send', 0);
            return ;
        }
        var msgIds = [];
        $(this).parent().find("input[type='checkbox']").each(function(){
            if ($(this).prop('checked') == true) {
                msgIds.push($(this).val());
            }
        });
        if (msgIds.length > 3) {
            alert('一个用户最多只能选择3条消息!');
            return ;
        } else if (msgIds.length == 0) {
            alert('您需要勾选消息!');
            return ;
        }
        var sendMsg = function(){
            var cid = $('#cid').val();
            $.post("/anchor/sendMsg", {msgIds: msgIds},
            function (data) {
                if (data == 1) {
                    var html = '<a class="btn btn-info btn-xs" style="margin: 0 10px 0 0" href="javascript:void(0)">已转发给第三方</a>';
                    var i;
                    for(i in msgIds) {
                        $('#state'+msgIds[i]).html(html);
                    }
                    that.parent().find('input').remove();
                    that.remove();
                } else {
                    alert('发送消息给易健通失败！');
                }
            }, "json");
        };
        $(this).html('提交中，点击取消').attr('is_send', setTimeout(sendMsg, 10000));
    });
    $('#notifyNew').click(function(){
            $.ajax({
                url: 'http://fywd.haoyisheng.com/api/wechat/push',
                type: 'GET',
                dataType: 'json',
                data: {},
                success : function(data){
                    if (data.code == 0) {
                        alert('推送给医生成功');
                    } else {
                        alert('推送给医生失败');
                    }
                }
            })
    });
});
</script>

</html>