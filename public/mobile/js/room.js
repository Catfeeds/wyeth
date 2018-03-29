$(function(){
    var debug = true;
    var log = function(){
        if(!debug) return;
        console.log.apply(console,arguments);
    }
    
    var getSearch = function(url){
        url = decodeURIComponent(url || location.href);
        var searchStr = url.replace(/(.*)?\?/,'');
        var obj = {};
        var arr = searchStr.split('&') , x , k ,v;
        for(var i=0;i<arr.length;i++){
            x = arr[i].split('=');
            k = x[0] , v = x[1] || '';
            if(obj[k]){
                obj[k] = [obj[k]];
                obj[k].push(v);
            }else{
                obj[k] = v;
            }
        }
        return obj;
    }
    var itpl = (function(){
        var tpl = {};
        tpl.temples = {};
        tpl.doneFn = {};
        var config = tpl.config = {
            lt : '<%',
            rt : '%>'
        }
        var ss = document.getElementsByTagName('script') , len = ss.length;
        for(var i=len-1;i>=0;i--){
            if(ss[i].getAttribute('type') == 'text/itpl'){
                var id = ss[i].getAttribute('id');
                tpl.temples[id] = ss[i].innerHTML;
                tpl.doneFn[id] = takeTempFn(tpl.temples[id]);
                ss[i].parentNode.removeChild(ss[i]);
            }
        }

        function takeTempFn(tplstr){
            var html = [];
            html.push('var html = [];');
            tplstr = tplstr.replace(/\r|\n/g,'');
            function subSplit(tpl,html){
                var idxl = tpl.indexOf(config.lt);
                if(idxl == -1){
                    html.push('html.push(\''+tpl.substring(0,tpl.length)+'\');');
                    return;
                }
                var _lp = tpl.substring(0,idxl);
                html.push('html.push(\''+_lp+'\');');
                tpl = tpl.substring(idxl+config.lt.length,tpl.length);
                var idxr = tpl.indexOf(config.rt);
                if(tpl.substr(0,1) == '='){
                    html.push('html.push('+tpl.substring(1,idxr)+');')
                }else if(tpl.substr(0,1) == '!'){
                    html.push('html.push(\''+tpl.substring(1,idxr)+'\');')
                }else{
                    html.push(tpl.substr(0,idxr));
                }
                subSplit(tpl.substring(idxr+config.rt.length,tpl.length),html);
            }
            subSplit(tplstr,html);
            html.push('return html.join(\'\');');
            try{
                var fn = new Function('data',html.join('\n'));
                return fn;
            }catch(e){
                throw e;
            }
        }
        tpl.done = function(tplId,obj){
            return tpl.doneFn[tplId](obj);
        }
        return tpl;
    })();
    
    function getWidth(str){
        var div = document.createElement('div');
        div.className = 'at_user_help';
        div.innerHTML = str;
        $('body').append(div);
        var width = div.clientWidth;
        div.parentNode.removeChild(div);
        return width;
    }
    
    var socketURL = '112.124.123.97';
    var atUser , atMsgId;
    $('.b_reply_chat').live('click',function(){
        atUser = $(this).attr('aid');
        atMsgId = $(this).attr('mid');
        atUserName = $(this).attr('uname');
        var width = getWidth('@'+atUserName);
        $('#content').html('<input type="text" style="width:'+width+'px" readonly="readonly" class="at_somebody" value="@'+atUserName+'"/>');
    });
    var socket = {
        connected : false,
        connect : function(cid){
            var that = this;
            var ws = new WebSocket("ws://" + socketURL + ":7272");
            that.ws = ws;
            ws.onopen = function(){
                that.connected = true;
                log('websocket open success , 准备进入房间，房间号：' + cid);
                that.loginRoom(cid);
            }
            ws.onmessage = function(e){
                var data = JSON.parse(e.data);
                // console.log(data)
                log('=============================')
                log('接受到消息：')
                log(data)
                log('=============================')
                if(data.type == 'login'){
                    that.cid = cid;
                    log('房间进入成功，返回内容：' + JSON.stringify(data));
                    that.loginSuccess && that.loginSuccess(data);
                    return;
                }
                if(data.type == 'ping'){
                    that.ws.send(JSON.stringify({"type":"pong"}));
                    return;
                }
                if(data.type == 'say' && data.message_type == '2'){
                    if(typeof data.content === 'string'){
                        try{
                            data.content = JSON.parse(data.content);
                        }catch(e){
                            log(e)
                        }
                    }
                }
                that.onGetMsg(data);
            }
            ws.onclose = function(){
                that.cid = undefined;
                that.connected = false;
            }
            ws.onerror = function(){
                socket.onError && socket.onError();
            }
        },
        loginRoom : function(cid){
            var that = this;
            var data = {
                type : 'login',
                cid : cid,
                author_id : user.uid,
                // name : user.name,
                // avatar : user.avatar,
                user_type : user.userType
            }
            log('进入房间消息体：' + JSON.stringify(data));
            that.ws.send(JSON.stringify(data));
        },
        leaveRoom : function(){
            this.ws.close();
        },
        onGetMsg : function(data){
            if(data.author_id == user.uid){
                data.isMine = true;
            }
            if(data.message_type == '2'){
                player.push(data.content);
            }else{
                var html = itpl.doneFn.msgTpl(data);
                $('#msg_list').append(html);
                $('#msg_list').scrollTop(9999);
            }
        },
        sendMsg : function(msgType,content){
            if(!user.can_say){
                
                return;
            }
            var that = this;
            content = msgType == 2 ? JSON.stringify(content) : $('#content').html();
            var data = {
                type : 'say',
                cid : that.cid , //课程ID
                author_id : user.uid,//发言用户ID
                name : user.name,//发言用户名称
                avatar : user.avatar,//发言用户头像
                user_type : user.userType,//发言用户类别 值用1、2、3表示
                message_type : msgType ? msgType : 1,//消息类型 值用 1、2表示 （文本、语音）
                content : content
            }
            if(atUser !== undefined){
                data.source_id = atMsgId;        //回复来源消息ID（指的是回复消息的后台返回数据中的message_id）
                data.source_author_id = atUser; //消息来源作者ID（指的是回复消息的后台返回数据中的author_id）
            }
            log('发送消息：' + JSON.stringify(data));
            that.ws.send(JSON.stringify(data));
            $('#content').html('');
            atUser = ''; atMsgId = '';
        }
    }
    var search = getSearch();
    var uid = search.uid , cid = search.cid;
    var user = {
        uid : uid,
        can_say : 1,
        // name : '测试名称',
        // avatar : 'http://gravatar.oschina.net/avatar/bbe658d09154b41b496444943936f3d2?s=40&d=mm',
        userType : 1,
        entryRoom : function(cid){
            socket.connect(cid);
        }
    }
    var timer;
    socket.onError = function(){
        if(timer){
            clearInterval(timer);
        }
        timer = setInterval(function(){
            user.entryRoom(cid);
        },3000);
    }
    
    user.entryRoom(cid);
    
    $('#send_msg').click(function(){
        socket.sendMsg();
    });
    
    var playing;
    $('.b_audio_dom').live('click',function(){
        var url = $(this).attr('aurl');
        var player = document.getElementById('b_audio');
        if(player){
            if(playing == this){
                if(player.paused){
                    player.play();
                    $(this).find('.b_audio_icon').addClass('b_playing');
                }else{
                    player.pause();
                    $(this).find('.b_audio_icon').removeClass('b_playing');
                }
            }else{
                player.src = url;
                $('.b_playing').removeClass('b_playing');
                $(this).find('.b_audio_icon').addClass('b_playing');
            }
        }else{
            var playerCon = document.createElement('div');
            playerCon.id = 'b_audio_player';
            playerCon.style.position = 'absolute';
            playerCon.style.top = '-1000px';
            playerCon.style.left = '-1000px';
            player = document.createElement('audio');
            player.id = 'b_audio';
            playerCon.appendChild(player);
            player.setAttribute('autoplay','autoplay');
            $('body').append(playerCon);
            $(this).find('.b_audio_icon').addClass('b_playing');
            player.src = url;
        }
        player.onend = function(){
            playing = undefined;
            $('.b_playing').removeClass('b_playing');
        }
        player.onerror = function(){
            alert('音频播放失败');
        }
        playing = this;
    })
    
    var focus = false;
    $('#content').focus(function(){
        setTimeout(function(){
            var st = $(document).scrollTop();
            var newH = window.innerHeight;
            $('.b_reply_wrap').css({
                position:'absolute',
                top : newH + st - $('.b_reply_wrap').height()
            })
        },100);
    })
    $('#content').blur(function(){
        $('.b_reply_wrap').css({
            position:'fixed',
            top : 'auto',
            bottom : 0
        });
    });
    function toggleFixed(){
        var st = $(document).scrollTop();
        var h = $('.b_fixed_hide').height();
        if(st >= h){
            $('body').addClass('b_fixed');
        }else{
            $('body').removeClass('b_fixed');
        }
    }
    setInterval(function(){
        toggleFixed();
    },100);
    document.ontouchmove = toggleFixed;
    function getHistoryMsg(){
        $.ajax({
            url : '/api/message/history',
            type : 'post',
            data : {
                uid : uid,
                cid : cid
            },
            success : function(data){
                var list = data.data.list , len = list.length;
                var html = '';
                player.init(list,data.data.time);
                for(var i=0;i<list.length;i++){
                    if(list[i].message_type == '1'){
                        html += itpl.doneFn.msgTpl(list[i]);
                    }
                }
                $('#msg_list').append(html);
            }
        })
    }
    function getClassInfo(){
        $.ajax({
            url : '/api/course/info',
            type : 'get',
            data : {
                uid : uid,
                cid : cid
            },
            success : function(data){
                var course = data.data.course || {};
                var teacher = data.data.teacher || {};
                $('.b_class_bg').css('background-image','url(' + course.img + ')')
                $('.b_class_bg').css('background-size','100%');
                $('.b_class_name').html(course.title);
                $('.b_doc_avatar img').attr('src',teacher.avatar);
                $('.b_name p').html(teacher.name);
                $('.b_flower_num').html(teacher.zan_num);
                $('.b_hos_name').html(teacher.hospital);
                $('.b_job').html(teacher.position);
                $('.b_answer_btn').click(function(){
                    var width = getWidth('@'+teacher.name);
                    $('#content').html('<input type="text" style="width:'+width+'px" readonly="readonly" class="at_somebody" value="@'+teacher.name+'"/>');
                    atUser = teacher.teacher_id;
                });
            }
        })
    }
    getHistoryMsg();
    getClassInfo();
    window.socket = socket;
    
    String.prototype.toDate = function(format){
        if(this.toString()==='') return '';
        var str;
        if(this.indexOf('.') != -1){
            str = this.substring(0,this.indexOf('.'));
        }else{
            str = this.toString();
        }
        var reg = /-/g;
        str = str.replace(reg,'/');
        if(str.indexOf('T')){
            str = str.replace('T',' ');
        }
        if(format){
            return new Date(str).format(format);
        }else{
            return new Date(str);
        }
    }
    var player = (function(){
        var list = [];
        return {
            play : function(){
                var that = this;
                var player = document.getElementById('_audio_player');
                if(!player){
                    var playerCon = document.createElement('div');
                    playerCon.style.position = 'absolute';
                    playerCon.style.top = '-1000px';
                    playerCon.style.left = '-1000px';
                    player = document.createElement('audio');
                    player.setAttribute('autoplay','autoplay');
                    // player.setAttribute('-webkit-playsinline','true');
                    player.id = '_audio_player';
                    playerCon.appendChild(player);
                    document.body.appendChild(playerCon);
                }
                if(player.ended || player.paused || player.src == ''){
                    var current = list.shift();
                    if(current){
                        player.src = current.url;
                        player.onloadedmetadata = function(){
                            player.currentTime = current.from;
                            $('.b_sound_icon').addClass('b_playing3');
                        }
                        player.onerror = function(){
                            alert('音频加载失败')
                            $('.b_sound_icon').removeClass('b_playing3');
                        }
                        player.onended = function(){
                            that.play();
                            $('.b_sound_icon').removeClass('b_playing3');
                        }
                    }else{
                        player.pause();
                    }
                }
            },
            push : function(msgData,from){
                list.push({
                    url : msgData.url ,
                    time : msgData.time,
                    from : from || 0
                });
                if(this.inited){
                    this.play();
                }
            },
            init : function(msgList,time){
                var that = this;
                // time = '2012-01-01 00:03:00'
                var serverTime = time.toDate().getTime();
                var current;
                // msgList.push({
                    // message_type : 2,
                    // content : '{"url":"/1.mp3","time":192}',
                    // time : '2012-01-01 00:00:00'
                // })
                // msgList.push({
                    // message_type : 2,
                    // content : '{"url":"/2.mp3","time":30}',
                    // time : '2012-01-01 00:00:15'
                // })
                for(var i=0;i<msgList.length;i++){
                    if(msgList[i].message_type == '2'){
                        var msgData = msgList[i].content;
                        if(msgData === null) continue;
                        var msgTime = msgList[i].time.toDate().getTime();
                        if(current){
                            that.push(msgData);
                        }else{
                            if(serverTime - msgTime < msgData.time*1000){
                                current = i;
                                that.push(msgData,parseFloat((serverTime - msgTime)/1000));
                            }
                        }
                    }
                }
                this.play();
                that.inited = true;
            }
        }
    })();
})