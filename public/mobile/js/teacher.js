"use strict";
var  ws, get_history, get_question, get_answered, get_mine;
var my_cid, my_uid, my_user_type;
var timer, timer2;
if (typeof console == "undefined") {
    this.console = {
        log: function (msg) {
        }
    };
}

var huishi = {
    SendMessage: {
        type: '',
        cid: '',
        source_id: 0,
        source_author_id: 0,
        author_id: '',
        name: '',
        avatar: '',
        user_type: '',
        message_type: '',
        content: '',
    },
    options: {
        status: 1,
    },
    record: {
        // 本地录音的时长
        local_id: '',
        // 上传到微信后id
        server_id: '',
        // 下载到本地服务器后地地址
        url: '',
        //录音开始时间
        start_time: '',
        //录音结束时间
        end_time: '',
        //录音时长
        time_count: '',
        //录音秒数
        seconds: 0,
        //秒针状态
        state: 0,
        //录音中状态 1:录音中 2:没有录音
        is_recording: 0,
        //设备类型 安卓 android / 苹果 IOS
        device: 'android',
    },
    ui: {
        question: $('[hw-question]'),
        answered: $('[hw-answered]'),
        mine: $('[hw-mine]'),
        all: $('[hw-all]'),
        comment_avatar: $("[hw_comment_avatar]"),
        comment_name: $("[hw_comment_name]"),
        comment_content: $("[hw_comment_content]"),
        button_start: $("[hw_comment_button_start]"),
        button_restart: $("[hw_comment_button_restart]"),
        button_send: $("[hw_comment_button_send]"),
        button_done: $("[hw_comment_button_done]"),
        comment_div_start_for_btn: $("[hw_comment_div_start_for_btn]"),
        comment_div_done_for_btn: $("[hw_comment_div_done_for_btn]"),
        comment_record_before: $("[hw_comment_record_before]"),
        comment_record_done: $("[hw_comment_record_done]"),
        comment_record_mic: $("[hw_comment_record_mic]"),
        //录完后的整个条
        comment_voice_div: $("[hw_comment_voice_div]"),
        comment_voice_avatar: $("[hw_comment_voice_avatar]"),
        // 录完后绿色条
        comment_voice: $("[hw_comment_voice]")
    },
    tpl: {
        bubble_l: $('#bubble_l').text(),
        bubble_r: $('#bubble_r').text(),
        question_bubble_l: $('#question_bubble_l').text(),
        question_bubble_r: $('#question_bubble_l').text(),
    },

    //tab
    _jqtab:function(tabtit,tab_conbox,shijian) {
        var self = this;
        $(tab_conbox).find("li").hide();
        $(tabtit).find("li").first().addClass("cur").show();
        $(tab_conbox).find("li").first().show();

        $(tabtit).find("li").bind(shijian,function(){
            $(this).addClass("cur").siblings("li").removeClass("cur");
            var activeindex = $(tabtit).find("li").index(this);
            switch(activeindex) {
                case 0:
                    if (!get_question) {
                        huishi._get_question(self.SendMessage.cid, self.SendMessage.author_id);
                    }
                    break;

                case 1:
                    if (!get_answered) {
                        huishi._get_answered(self.SendMessage.cid, self.SendMessage.author_id);
                    }
                    break;

                // case 2:
                //     if (!get_mine) {
                //         huishi._get_mine(self.SendMessage.cid, self.SendMessage.author_id);
                //     }
                //     break;

                // case 3:
                //     if (!get_history) {
                //         huishi._get_history(self.SendMessage.cid);
                //     }
                //     break;
            };
            $(tab_conbox).children().eq(activeindex).show().siblings().hide();
            return false;
        });
    },

    _add_temlplate: function(list, o_type) {
        var self = this;
        var template_data = {};
        var compiled;
        var html = '';
        $.each(list, function(k, v){
            template_data.message_id = v.message_id;
            template_data.author_id = v.user_id;
            if (v.user_type == 2) {
                template_data.name = v.name + '（主持人）';
            } else {
                template_data.name = v.name;
            }
            template_data.avatar = v.avatar;
            template_data.user_type = v.user_type;
            template_data.time = v.time.substr(11,8);
            template_data.message_type = v.message_type;
            if (v.message_type == 2 && typeof(v.content == 'object')) {
                template_data.url = v.content.url;
                if (v.source_author_name) {
                    template_data.content = "<span class=\"voice\">@" + v.source_author_name + "  " + v.content.time + "</span>";
                } else {
                    template_data.content = "<span class=\"voice\">" + v.content.time + "</span>";
                }
            } else {
                template_data.url = '';
                if (v.source_author_name) {
                    template_data.content = "@" + v.source_author_name + "  " + v.content;
                } else {
                    template_data.content = v.content;
                }
            }

            if (v.user_id == self.SendMessage.author_id) {
                if (!$.isEmptyObject(v.source_message)) {
                    var user_data = {};
                    user_data.message_id = v.source_message.message_id;
                    user_data.name = v.source_message.name + ' ' + v.source_message.remark;
                    user_data.avatar = v.source_message.avatar;
                    user_data.url = '';
                    user_data.content = v.source_message.content;
                    compiled = _.template(self.tpl.bubble_l);
                    html += compiled(user_data);

                }
                compiled = _.template(self.tpl.bubble_r);

            } else {
                if (o_type == 0) {
                    compiled = _.template(self.tpl.question_bubble_l);
                } else {
                    compiled = _.template(self.tpl.bubble_l);
                }
            }
            html += compiled(template_data);
        });
        switch(o_type) {
            case 0:
                self.ui.question.prepend(html);
                // self.ui.question.scrollTop(self.ui.question.height());
                break;
            case 1:
                self.ui.answered.prepend(html);
                // self.ui.answered.scrollTop(self.ui.answered.height());
                break;
            case 2:
                self.ui.mine.prepend(html);
                // self.ui.answered.scrollTop(self.ui.answered.height());
                break;
            case 3:
                self.ui.all.prepend(html);
                //self.ui.all.scrollTop(self.ui.all.height());
                break;

        }
    },

    _get_history: function(cid) {
        var self = this;
        $.ajax({
            type: "POST",
            url: "/api/message/history",
            data: {cid: cid, page: 1},
            dataType: "json",
            success: function(data){
                get_history = true;
                var list = data.data.list;
                self._add_temlplate(list, 3);
            }
        });
    },

    _get_question: function(cid, uid) {
        var self = this;
        $.ajax({
            type: "POST",
            url: "/api/message/question",
            data: {cid: cid, userid: uid, page: 1},
            dataType: "json",
            success: function(data){
                get_question = true;
                var list = data.data.list;
                self._add_temlplate(list, 0);
            }
        });
    },

    _get_answered: function(cid, uid) {
        var self = this;
        $.ajax({
            type: "POST",
            url: "/api/message/history",
            data: {
                cid: cid,
                message_type: 'answered',
                page: 1
            },
            dataType: "json",
            success: function(data){
                get_answered = true;
                var list = data.data.list;
                self._add_temlplate(list, 1);
            }
        });
    },

    _get_mine: function(cid, uid) {
        var self = this;
        $.ajax({
            type: "POST",
            url: "/api/message/history",
            data: {
                cid: cid,
                page: 1,
                message_type: 'teacher_voice',
            },
            dataType: "json",
            success: function(data){
                get_mine = true;
                var list = data.data.list;
                self._add_temlplate(list, 2);
            }
        });
    },

    // ws 向服务器发送数据
    wssend: function(data) {
        data = _.extend(data, {channel: chat_channel});
        if (data.type != 'pong') {
            data = _.extend(data, {token: token});
        }
        ws.send(JSON.stringify(data));
    },

    connect: function() {
        var self = this;
        // 创建websocket
        ws = new WebSocket("ws://"+document.domain+":7272");
        // 当socket连接打开时，输入用户名
        ws.onopen = function () {
            self.onopen();
        };
        // 当有消息时根据消息类型显示不同信息
        ws.onmessage = function (e) {
            self.onmessage(e);
        };
        ws.onclose = function() {
            console.log("连接关闭，定时重连");
            huishi.connect();
        };
        ws.onerror = function() {
            console.log("出现错误");
        };
    },

    onopen: function() {
        // 登录

        var login_data = {
            type: "login",
            cid: my_cid,
            user_type: my_user_type,
            author_id: my_uid
        };
        console.log(login_data);
        this.wssend(login_data);
    },

    onmessage: function(e) {
        var self = this;
        // console.log(e.data);
        var data = eval("("+e.data+")");
        switch(data.type){
            // 服务端ping客户端
            case 'ping':
                self.wssend({type : "pong"});
                break;;
            // 登录 更新用户列表
            case 'login':
                // data.message_id = 0;
                // data.url = '';
                // data.content = data.name + "加入了聊天室";
                // huishi.say(data);
                // console.log(data['name']+"登录成功");
                break;
            // 发言
            case 'say':
                if (data.message_type == 2) {
                    data.url = data.content.url;
                    if (data.source_author_name) {
                        data.content = "<span class=\"voice\">@" + data.source_author_name + "  " + data.content.time + "</span>";
                    } else {
                        data.content = "<span class=\"voice\">" + data.content.time + "</span>";
                    }

                } else {
                    data.url = '';
                    if (data.source_author_name) {
                        data.content = "@" + data.source_author_name + "  " + data.content;
                    }
                }
                huishi.say(data);
                // console.log(data);
                break;
            // 发言
            case 'submit':
                if (data.message_type == 2) {
                    data.url = data.content.url;
                    data.content = "<span class=\"voice\">" + data.content.time + "</span>";
                } else {
                    data.url = '';
                }
                huishi.say(data);
                // console.log(data);
                break;
            // 发言
            case 'delete':

                //huishi.say(data);
                // console.log(data);
                break;
            // 用户退出 更新用户列表
            case 'logout':
                // data.url = '';
                // data.content = data.name + "退出了聊天室";
                // huishi.say(data);
                break;
        }
    },

    say: function(data) {
        var compiled;
        var self = this;
        if (data.user_type == 2) {
            data.name = data.name + '（主持人）';
        }
        if (data.type == 'submit') {
            compiled = _.template(self.tpl.question_bubble_l);
            self.ui.question.append(compiled(data));
        } else {
            if (data.author_id == self.SendMessage.author_id) {
                var mine_html = '';
                // 判断是否有回复来源信息
                if (!$.isEmptyObject(data.source_message)) {
                    // 回复来源用户信息
                    var user_data = {};
                    user_data.name = data.source_message.name + ' ' + data.source_message.remark;
                    user_data.avatar = data.source_message.avatar;
                    user_data.content = data.source_message.content;
                    user_data.message_id = data.source_message.message_id;
                    user_data.time = data.source_message.time.substr(11,8);
                    user_data.url = '';
                    compiled = _.template(self.tpl.bubble_l);
                    mine_html = compiled(user_data);
                }
                compiled = _.template(self.tpl.bubble_r);
                if (mine_html && get_answered) {
                    // 向已回答里添加消息
                    mine_html += compiled(data);
                    self.ui.answered.append(mine_html);
                }
                // 向我的发言里添加信息
                // mine_html += compiled(data);
                // self.ui.mine.append(mine_html);
            }
        }

    },

    // 微信 sdk
    initWx: function() {
        var timestamp = parseInt(new Date().getTime() / 1000);
        var config = {};
        var app_id = 'wx1453c24798e5e42e';
        var req_url = encodeURIComponent(window.location.href);
        var jsApiList = ['stopRecord', 'onVoiceRecordEnd', 'playVoice', 'pauseVoice', 'stopVoice',
            'onVoicePlayEnd', 'uploadVoice', 'downloadVoice', 'translateVoice'];

        var self = this;

        $.ajax({
            type: "GET",
            async: false,
            cache: false,
            url: "http://v2.shheywow.com/api/v1/weixin/jsticket/get_config?appid=" + app_id + "&url=" + req_url + "&t=" + timestamp + "&jsapilist=" + jsApiList.toString(),
            dataType: "jsonp",
            jsonp: "callback",
            jsonpCallback: "getJsApiTicket",
            success: function(ret) {
                if (ret.errCode == 0) {
                    config = ret.data;
                }
                // config.debug = true;
                wx.config(config);
                wx.ready(function() {
                    self.on_wx_ready();
                });
            }
        })
    },

    on_wx_ready: function() {
        var self = this;
        // 录音自动结束
        wx.onVoiceRecordEnd({
            // 录音时间超过一分钟没有停止的时候会执行 complete 回调
            complete: function (res) {
                console.log('end');
                self.on_stop_record(res);
            }
        });

        wx.onVoicePlayEnd({
            success: function (res) {
                var localId = res.localId; // 返回音频的本地ID
                $('[hw_comment_voice]').removeClass('on');
            }
        });
    },

    // 录音结束时回调
    on_stop_record: function(res) {
        var self = this;
        //计算录音时长
        // self.record.end_time = new Date().getTime();
        // self.record.time_count = Math.round((self.record.end_time - self.record.start_time)/1000);
        // self.stop_timer();

        // this.ui.comment_record_before.hide();
        // this.ui.comment_record_done.show();
        // // 录音喇叭
        // this.ui.comment_record_mic.hide();
        // // 显示声音
        // this.ui.comment_voice_avatar.attr('src', self.SendMessage.avatar);
        // if (self.record.time_count > 60) {
        //     self.record.time_count = 60;
        // }
        // this.ui.comment_voice.find(".voice").text(self.record.time_count);
        // this.ui.comment_voice_div.show();
        // this.record.local_id = res.localId;

        clearInterval(timer2);
        var sendmessage = self.initSendMessage();
        self.initComment();
        self.sendWXvoice(res, sendmessage, 60);
    },

    start_timer: function() {
        var self = this;
        $('#mic_time').show();
        timer = setInterval(function() {
            self.record.seconds++;
            $('#mic_time').text(self.record.seconds);
        }, 1000);
    },

    stop_timer: function() {
        var self = this;
        clearInterval(timer);
        self.record.time_count = self.record.seconds;
        self.record.state = 0;
        self.record.seconds = 0;
        $('#mic_time').hide();
        $('#mic_time').text('0');
    },

    initComment: function() {
        var self = this;
        $('[hw_reply]').hide();
        $('[hw_comment]').hide();
        self.ui.comment_voice_div.hide();
        self.ui.comment_record_before.show();
        self.ui.comment_div_start_for_btn.show();
        self.ui.comment_div_done_for_btn.hide();
        self.ui.comment_record_done.hide();
        self.ui.comment_record_mic.hide();
        self.SendMessage.source_id = 0;
        self.SendMessage.source_author_id = 0;
        var popWrapH = $(".popWrap").height();
        $(".tabCon").css("padding-bottom", popWrapH);
        // self.record.seconds = 0;
        // $('#mic_time').hide();
        // $('#mic_time').text('0');
    },

    // 发送语音
    sendWXvoice: function(res, sendmessage, seconds) {
        // console.log(res);
        var self = this;
        wx.uploadVoice({
            localId: res.localId, // 需要上传的音频的本地ID，由stopRecord接口获得
            isShowProgressTips: 0, // 默认为1，显示进度提示
            success: function (upload_res) {
                var serverId = upload_res.serverId;
                var content = {serverid: serverId, time: seconds};
                sendmessage.content = JSON.stringify(content);
                // console.log(sendmessage);
                self.wssend(sendmessage);
            }
        });
    },

    initSendMessage: function() {
        var self = this;
        var sendmessage = {};
        sendmessage.cid = self.SendMessage.cid;
        sendmessage.source_id = self.SendMessage.source_id;
        sendmessage.source_author_id = self.SendMessage.source_author_id;
        sendmessage.author_id = self.SendMessage.author_id;
        sendmessage.name = self.SendMessage.name;
        sendmessage.avatar = self.SendMessage.avatar;
        sendmessage.user_type = self.SendMessage.user_type;
        sendmessage.type = 'say';
        sendmessage.message_type = 2;
        return sendmessage;
    },

    // 停止微信录音方法 type ='click_stop' 表示点击说完结束停止录音，type='reply'表示回复停止录音
    stopWXvoiceRecord: function(type, comment) {
        var self = this;
        self.record.is_recording = 0;
        clearInterval(timer2);
        var sendmessage = self.initSendMessage();
        var source_id = 0;
        if (type == 'reply') {
            source_id = comment.source_id;
        }
        var stop_flag = true;
        wx.stopRecord({
            success: function (res) {
                self.sendWXvoice(res, sendmessage, 10);
                if (self.record.device == 'IOS' && type == 'reply') {
                    self.initComment();
                    self.initReply(comment);
                    self.startWXvoiceRecord();
                    stop_flag = false;
                }
                // 如果是回答问题，则回答完修改问题标志为已回答
                if (sendmessage.source_id && sendmessage.source_id != source_id) {
                    var source_id = sendmessage.source_id;
                    $.ajax({
                        type: "POST",
                        url: "/api/message/update",
                        data: {messageid: source_id},
                        dataType: "json",
                        success: function(data){
                            var hw_message = "[hw-question] [hw_message_id='" + source_id + "']";
                            $(hw_message).remove();
                        }
                    });
                }
            }
        });
        if (self.record.device == 'IOS' && type == 'reply'){
            setTimeout(function() {
                if (stop_flag) {
                    self.startWXvoiceRecord();
                }
            },3000);
        }
        if (self.record.device == 'android' && type == 'reply') {
            self.initComment();
            self.initReply(comment);
            self.startWXvoiceRecord();
        }
        if (type == 'click_stop') {
           self.initComment();
        }
    },

    // 开始微信持续录音中方法
    startWXvoiceRecord: function() {
        var self = this;
        self.record.is_recording = 1;
        $('[hw_reply]').show();
        self.ui.comment_div_start_for_btn.hide();
        self.ui.comment_div_done_for_btn.show();
        self.ui.comment_record_mic.show();
        self.SendMessage.type = 'say';
        self.SendMessage.message_type = 2;
        wx.startRecord();
        clearInterval(timer2);
        timer2 = setInterval(function() {
            var stop_flag = true;
            wx.stopRecord({
                success: function (res) {
                    if (self.record.device == 'IOS') {
                        console.log('IOS');
                        stop_flag = false;
                        wx.startRecord();
                    }
                    self.sendWXvoice(res, self.SendMessage, 40);
                }
            });
            if (self.record.device == 'IOS') {
                setTimeout(function() {
                    if (stop_flag) {
                        wx.startRecord();
                    }
                }, 3000);
            }
            if (self.record.device == 'android') {
                wx.startRecord();
            }
        }, 40000);
    },

    // 回复初始化
    initReply: function(comment) {
        var self = this;
        self.SendMessage.source_id = comment.source_id;
        self.SendMessage.source_author_id = comment.source_author_id;
        self.ui.comment_avatar.attr('src', comment.avatar);
        self.ui.comment_name.text(comment.name);
        self.ui.comment_content.text(comment.content);
        $('[hw_comment]').show();
        $(".tabCon").css("padding-bottom",comment.popWrapH);
    },

    getDeviceType: function() {
        var self = this;
        var u = navigator.userAgent;
        var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
        // var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
        if (isAndroid) {
            self.record.device = 'android';
        } else {
            self.record.device = 'IOS';
        }
    },

    // 对象初始化
    init : function(options){
        var self = this;

        self.SendMessage.cid = options.cid;
        self.SendMessage.author_id = options.uid;
        self.SendMessage.name = options.name;
        self.SendMessage.avatar = options.avatar;
        self.SendMessage.user_type = options.user_type;
        self.options.status = options.status;
        my_uid = options.uid;
        my_cid = options.cid;
        my_user_type = options.user_type;
        get_history = false;
        get_question = false;
        get_answered = false;
        get_mine = false;

        var mobile   = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
        var touchstart = mobile ? "touchstart" : "mousedown";
        var touchend = mobile ? "touchend" : "mouseup";
        var touchmove = mobile ? "touchmove" : "mousemove";
        var tap = "click";
        var play = document.getElementById('audio');

        huishi.connect();

        huishi.getDeviceType();

        // wm sdk
        this.initWx();

        //tab
        huishi._jqtab(".tabNav",".tabCon",touchstart);
        huishi.initComment();
        huishi._get_question(self.SendMessage.cid, self.SendMessage.author_id);

        //pop 回复
        var popWrapH = $(".popWrap").height();
        $('.tabCon').on(touchstart, '.popOpen', function(e){
            // 打开底部回复
            var selfEl = this;
            var comment = {
                avatar: $(selfEl).data('avatar'),
                name: $(selfEl).data('name'),
                content: $(selfEl).parent().find('.bubble-main').text(),
                source_id: $(selfEl).data('messageid'),
                source_author_id: $(selfEl).data('uid'),
                popWrapH: popWrapH
            };
            // 设置发送消息的来源信息
            if (self.SendMessage.source_id == $(selfEl).data('messageid')) {
                return true;
            }
            if (self.record.is_recording) {
                self.stopWXvoiceRecord('reply', comment);
            } else {
                self.initReply(comment);
                // 开始录音
                self.startWXvoiceRecord();
            }
        });
        $('.popClose').bind(touchstart,function(e){
            huishi.initComment();
            play.pause();
            $('.bubble-cont').removeClass('on');
            wx.stopRecord({
                success: function (res) {
                }
            });
            wx.stopVoice({
                localId: self.record.local_id // 需要停止的音频的本地ID，由stopRecord接口获得
            });
            self.ui.comment_voice.removeClass('on');
            $(".tabCon").css("padding-bottom","0");
            self.SendMessage.source_id = 0;
            self.SendMessage.source_author_id = 0;
        });

        //听音频

        $('.tabCon').on(touchstart, '.bubble-cont', function(e){
            if (play.ended) {
                $(this).removeClass('on');
            }
            var state = $(this).hasClass('on')? true : false;
            if (state) {
                play.pause();
                $(this).removeClass('on');
            } else {
                var voiceUrl = $(this).data('url');
                play.src = voiceUrl;
                play.load();
                $(this).addClass('on');
                play.play();
            }
        });

        // 开始录音
        this.ui.button_start.on('click', function(e){
            self.startWXvoiceRecord();
        });

        // 结束录音
        this.ui.button_done.on('click', function(e) {
            var comment = {};
            self.stopWXvoiceRecord('click_stop', comment);
        });

        //听录音
        this.ui.comment_voice.on('click', function(e){
            var state = $(this).hasClass('on')? true: false;
            console.log(state);
            if (state) {
                $(this).removeClass('on');
                wx.stopVoice({
                    localId: self.record.local_id // 需要停止的音频的本地ID，由stopRecord接口获得
                });
            } else {
                $(this).addClass('on');
                wx.playVoice({
                    localId: self.record.local_id // 需要播放的音频的本地ID，由stopRecord接口获得
                });
            }
        });

        // 重新录音
        this.ui.button_restart.on('click', function(e) {
            if (self.SendMessage.source_id) {
                self.ui.comment_record_before.show();
                self.ui.comment_record_done.hide();

                self.ui.comment_div_done_for_btn.hide();
                self.ui.comment_div_start_for_btn.show();

                self.ui.comment_voice_div.hide();

                // 录音喇叭
                self.ui.comment_record_mic.hide();
            } else {
                huishi.initComment();
            }
        });

        // 发送
        this.ui.button_send.on('click', function(e) {
            var sendmessage = {};
            sendmessage.cid = self.SendMessage.cid;
            sendmessage.source_id = self.SendMessage.source_id;
            sendmessage.source_author_id = self.SendMessage.source_author_id;
            sendmessage.author_id = self.SendMessage.author_id;
            sendmessage.name = self.SendMessage.name;
            sendmessage.avatar = self.SendMessage.avatar;
            sendmessage.user_type = self.SendMessage.user_type;
            wx.uploadVoice({
                localId: self.record.local_id, // 需要上传的音频的本地ID，由stopRecord接口获得
                isShowProgressTips: 0, // 默认为1，显示进度提示
                success: function (res) {
                    var serverId = res.serverId; // 返回音频的服务器端ID
                    console.log(serverId);
                    if (sendmessage.source_id) {
                        var source_id = sendmessage.source_id;
                        $.ajax({
                            type: "POST",
                            url: "/api/message/update",
                            data: {messageid: source_id},
                            dataType: "json",
                            success: function(data){
                                console.log(data);
                                var template_data = {};
                                var hw_message = "[hw-question] [hw_message_id='" + source_id + "']";
                                // template_data.message_id = $(hw_message).find('div.replyTA').data('messageid');
                                // template_data.author_id = $(hw_message).find('div.replyTA').data('uid');
                                // template_data.name = $(hw_message).find('div.replyTA').data('name');
                                // template_data.avatar = $(hw_message).find('div.replyTA').data('avatar');
                                // template_data.user_type = $(hw_message).find('div.replyTA').data('usertype');
                                // template_data.content = $(hw_message).find('div.bubble-main').text();
                                // template_data.url = '';
                                $(hw_message).remove();
                                // var compiled = _.template(self.tpl.bubble_l);
                                // self.ui.answered.append(compiled(template_data));
                                alert('发送成功');
                            }
                        });
                    }
                    sendmessage.type = 'say';
                    var content = {serverid: serverId, time: self.record.time_count};
                    sendmessage.message_type = 2;
                    sendmessage.content = JSON.stringify(content);
                    console.log(sendmessage);
                    self.wssend(sendmessage);
                }
            });
            huishi.initComment();
        });

    }

};


