/**
 * 通讯的 communication
 * 浮层，layer
 * 献花 flowers
 * 播放 movie
 * 在线人数 updateOnlineNumber
 * 倒计时 countdown
 */
var userStatusBaiduStatistics = $('#userStatusBaiduStatistics').val();
var manager = {
    _configWX: function () {
        //基本数据
        var self = this;
        var timestamp = parseInt(new Date().getTime() / 1000);
        var config = {};
        var app_id = 'wx1453c24798e5e42e';
        var req_url = encodeURIComponent(window.location.href);
        var jsApiList = ['checkJsApi', 'onMenuShareTimeline', 'onMenuShareAppMessage'];

        $.ajax({
            type: "GET",
            async: false,
            cache: false,
            url: "http://v2.shheywow.com/api/v1/weixin/jsticket/get_config?appid=" + app_id + "&url=" + req_url + "&t=" + timestamp + "&jsapilist=" + jsApiList.toString(),
            dataType: "jsonp",
            jsonp: "callback",
            jsonpCallback: "getJsApiTicket",
            success: function (ret) {
                var config;
                if (ret.errCode == 0) {
                    config = ret.data;
                }
                config.debug = false;
                wx.config(config);
                var shareUrl = self.options.share.app_url + '/mobile/living?cid=' + self.options.course.id + '&from_openid=' + self.options.user.openid;
                var share_title = self.options.share.living_share_title;
                var share_desc = self.options.share.living_firend_subtitle;
                var firend_title = self.options.share.living_firend_title;
                var img_url = self.options.share.living_share_picture;
                wx.error(function (res) {
                    window.console.log(res);
                });

                //微信分享成功后记录到自已的数据库
                wx.ready(function () {
                    // 分享朋友圈的数据
                    wx.onMenuShareTimeline({
                        title: share_title, // 分享标题
                        link: shareUrl, // 分享链接
                        imgUrl: img_url, // 分享图标
                        success: function () {
                            submitLog();
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });

                    // 分享给好友的数据
                    wx.onMenuShareAppMessage({
                        title: firend_title, // 分享标题
                        desc: share_desc, // 分享描述
                        link: shareUrl, // 分享链接
                        imgUrl: img_url, // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () {
                            submitLog();
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });

                    //获取用户网络类型
                    wx.getNetworkType({
                        success: function (res) {
                            var networkType = res.networkType; // 返回网络类型2g，3g，4g，wifi
                            sendNetWorkType(networkType);
                        }
                    });
                });
            }
        });
        function sendNetWorkType(type){
            $.ajax({
                type: "POST",
                url: "/mobile/living/setNetWorkType",
                data: {
                    type: type,
                    cid : cid
                },
                success: function (data) {
                    console.log(data);
                }
            });
        }
        //提交动作
        function submitLog() {
            $.ajax({
                type: "POST",
                url: "/api/course/share",
                data: {
                    cid: manager.options.course.id,
                    type: 2
                },
                success: function (data) {
                    console.log(data);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }, //function over
    _init: function (options) {
        this.options = options;
        this.options.channel = (options.channel == undefined) ? '' : options.channel;
        this.options.imgSrc = {
            'share': 'boardcast\/img\/showShare.png',
            'showPresentedFail': 'boardcast/img/showPresentedFail2.png'
        };
        this.countdown.start(this.options.course.countdownSeconds);
        this.movie.playStatus = options.playStatus;
        this.movie.attach();
        this._initEventBind();
        this._initWebSocket();
        this._configWX();
        this.estimation.init();

    },
    // 课后评价打分
    estimation: {
        init: function () {
            var self = this;
            $(document).on("touchend", ".score a", function (e) {
                if (self.flag) {
                    var _ind = $(this).index();
                    $(".score").css("margin-top", "24px");
                    $(".score a").removeClass("select").slice(0, _ind + 1).addClass("select");
                    var _text = $(this).attr("data-text");
                    $("h4").hide();
                    $("#scoretext").text(_text).show();
                    $(".hs_finish").hide();
                    $(".hs_form").show();
                    self.mark = $(this).attr('data-hw-value');
                }
                e.preventDefault();
            });

            $(".hs_btn").on("touchend", function (e) {
                _hmt.push(['_trackEvent', '课后评分广告','弹出', self.cid, userStatusBaiduStatistics]);
                $(".hs_form").hide();
                $(".hs_finish").show();
                self.flag = false;
                //submit data
                var content = $('#estimation .hs_text').val();
                var mark = self.mark;
                $.ajax({
                    type: 'post',
                    data: {
                        content: content,
                        mark: mark,
                        cid: manager.options.course.id
                    },
                    url: '/mobile/living/processEstimation',
                    success: function (response) {
                        if (response.status == 0) {
                            $('.hs_finish #userscore').html(content);
                        } else {
                            console.log(response.error_msg);
                        }
                    }
                });

                //submit data over
                e.preventDefault();
            });
            //
        },
        flag: true,
        mark: undefined,
        display: function () {
            $("#estimation").fadeIn();
        }
    },
    //
    _initWebSocket: function () {
        //init websocket
        this.ws = new WebSocket("ws://" + this.options.video.chat_domain + ":7272");
        var self = this;
        this.ws.onopen = function () {
            self.ws.send(JSON.stringify({
                type: 'login',
                cid: manager.options.course.id,
                user_type: manager.options.user.user_type,
                author_id: manager.options.user.id,
                channel: manager.options.video.chat_channel,
                token: manager.options.user.token
            }));
        };
        this.ws.onmessage = function (e) {
            self.handleMessage(e);
        };
        this.ws.onclose = function () {
            console.log('on close');
        };
        this.ws.onerror = function () {
            console.log('warning: communication error');
            // 5s后重连
            setTimeout(function () {
                self._initWebSocket();
            }, 5000);
        };
    },
    //
    _initEventBind: function () {
        var self = this;
        //share button
        $('.button-share').click(function () {
            self.layer.show(manager.options.imgSrc.share);
        });

        //底部刷新
        $('.tip-refresh').on('click', function () {
            window.location.reload();
        });

        //init flash
        var slider = Swipe(document.getElementById('scroll-img'), {
            width: 640,
            auto: 5000,
            continuous: true
        });

        //讨论区事件绑定
        $('.page-message .close').on('click', function () {
            $('.page-message').addClass('page-message-disappear');
            setTimeout(function () {
                $('.page-message').addClass('f-hide');
                $('.page-message').removeClass('page-message-appear');
                $('.page-message').removeClass('page-message-disappear');
            }, 800);
        });
        // 留言按钮
        $('.button-area .button-message').on('click', function () {
            $('.page-message').removeClass('f-hide');
            $('.page-message').addClass('page-message-appear');
            //car2._messageScroll.refresh();
        });
        // 提问按钮
        $('.page-message .button-bottom').on('click', function () {
            $('.page-message .pop').removeClass('f-hide');
        });
        $('.page-message .pop .cancel').on('click', function () {
            $('.page-message .pop').addClass('f-hide');
        });
        //send
        $('.page-message .pop .send').on('click', function () {
            var sendStr = $('#saytext').val();
            self.discuss.send(sendStr);
            $('.page-message .pop').addClass('f-hide');
            $('.page-message .tip-success').removeClass('f-hide');
            setTimeout(function () {
                $('.page-message .tip-success').addClass('f-hide');
            }, 1000);
        });
    },
    /**
     * class flowers
     * 方法 presended  | 用户点花时，花变胖1秒，然后向服务器发送数据
     */
    flowers: {
        elements: {
            number: $('.lecturer .flower-number'),
            tip: $('.lecturer .flower-tip'),
            flower: (function () {
                var flower = $('.lecturer .flower');
                flower.on('click', function () {
                    manager.flowers.presented();
                });
                return flower;
            })()
        },
        number: 0,
        action: {
            fail: function () {
                manager.layer.show(manager.options.imgSrc.showPresentedFail);
            },
            success: function () {
                manager.flowers.elements.flower.addClass('flower-animation');
                manager.flowers.elements.tip.html('已献花');
                setTimeout(function () {
                    manager.flowers.elements.flower.removeClass('flower-animation');
                }, 1000);
            },
            increaseFlowerNumber: function () {
                var flowerNumbers = ++manager.options.course.presentedFlowersNumbers;
                manager.flowers.elements.number.html(flowerNumbers);
            }
        },
        deliverData: function () {
            var self = this;
            $.post('/mobile/living/presentFlower', {cid: manager.options.course.id}, function (data) {
                if (data.status == 0) {
                    self.action.success();
                } else if (data.status == 2) {
                    //console.log(data.error_msg);
                    manager.flowers.action.fail();
                } else {
                    console.log('other:' + data.error_msg);
                }
            });
        },
        presented: function () {
            if (this.number < 6) {
                this.number++;
                this.deliverData();
            } else {
                this.action.fail();
            }
        }
    },
    //
    layer: {
        element: (function () {
            var img = document.createElement('img');
            img.style.width = "100%";
            img.style.zIndex = '11';
            img.style.position = 'fixed';
            img.style.top = '0px';
            img.style.bottom = '0px';
            img.style.left = '0px';
            img.style.right = '0px';
            img.style.display = 'none';
            img.onclick = function () {
                img.style.display = 'none';
                div.style.display = 'none';
            };
            var div = window.document.createElement('div');
            div.style.zIndex = '10';
            div.style.position = 'fixed';
            div.style.top = '0px';
            div.style.bottom = '0px';
            div.style.left = '0px';
            div.style.right = '0px';
            div.style.backgroundColor = 'black';
            div.style.opacity = '0.8';
            div.style.display = 'none';
            div.onclick = function () {
                img.style.display = 'none';
                div.style.display = 'none';
            };

            this.cloud = window.document.body.appendChild(div);
            this.cloud = window.document.body.appendChild(img);
            var self = this;
            return {'div': div, 'img': img};
        })(),
        show: function (src) {
            this.element.img.src = src;
            this.element.div.style.display = 'block';
            this.element.img.style.display = 'block';
        }
    },
    // communication
    handleMessage: function (e) {
        var data = JSON.parse(e.data);
        var eventName = 'ws.message.' + data.type;
        switch (data.type) {
            case 'ping':
                this.sendSocketData({type: "pong"});
                break;
            // 登录 更新用户列表
            case 'login':
                this.updateOnlineNumber(data.online_num);
                break;
            // 发言
            case 'say':
                var eventName = 'ws.message.say.' + data.message_type;
                if (data.message_type == 1) {
                    data.message_type = 'question';
                    eventName = 'ws.message.say.question';
                } else if (data.message_type == 'presentFlower') {
                    this.flowers.action.increaseFlowerNumber();
                } else if (data.message_type == 'answered') {
                    if (data.author_id == manager.options.user.id) {
                        var face = $('#face');
                        face.css('display', 'block');
                        var fun = function () {
                            face.css('display', 'none');
                        };
                        window.setTimeout(fun, 1500);
                    }
                } else if (data.message_type == 'estimation') {
                    // 课后评价
                    manager.estimation.display();
                    manager.ws.close();
                }
                break;
            //主持人通知用户开始播放
            case 'play':
                // 如果触发隐藏的测试按钮，则用户界面不受主持人后台通知开始播放和开始问答环节的影响
                if (manager.movie.isLiveTest == true) {
                    break;
                }
                if (manager.countdown.countdownSeconds > 0 && manager.movie.ui.playReady == false) {
                    manager.movie.playStatus = data.playStatus;
                    manager.movie.ui.play.click();
                } else if (manager.countdown.countdownSeconds > 0 && manager.movie.ui.playReady){
                    if (manager.movie.playStatus == 'living' && data.playStatus == 'qAndA') {
                        if (manager.movie.ui.play.hasClass('button-pause')) {
                            manager.movie.ui.play.click();
                            manager.movie.playStatus = data.playStatus;
                            manager.movie.ui.play.click();
                        } else {
                            manager.movie.playStatus = data.playStatus;
                        }
                    } else if (manager.movie.playStatus == 'qAndA' && data.playStatus == 'living'){
                        if (manager.movie.ui.play.hasClass('button-pause')) {
                            manager.movie.ui.play.click();
                            manager.movie.playStatus = data.playStatus;
                            manager.movie.ui.play.click();
                        } else {
                            manager.movie.playStatus = data.playStatus;
                        }
                    }
                }
                break;
            // 用户退出 更新用户列表
            case 'logout':
                // this.updateOnlineNumber(data.online_num);
                break;
            case 'finishLiving':
                this.countdown.stop();
                break;
            default:
                console.log(e);
                break;
        }
        //如果是献花消息 提前结束function,不在讨论区生成献花消息
        if (data.type == 'say' && data.message_type == 'presentFlower') {
            return;
        }
        $(document).trigger(eventName, data);
    },
    sendSocketData: function (obj) {
        obj = _.extend(obj, {channel: this.options.video.chat_channel});
        if (obj.type != 'pong') {
            obj = _.extend(obj, {token: this.options.user.token});
        }
        this.ws.send(JSON.stringify(obj));
    },
    // 在线人数
    updateOnlineNumber: function (onlineNumber) {
        if (this.onlineNumberDisplayControl == undefined) {
            this.onlineNumberDisplayControl = $('.online-number');
        }
        this.onlineNumberDisplayControl.html(onlineNumber);
    },
    //倒计时
    countdown: {
        interval: undefined,
        countdownSeconds: 0,
        ui: {
            //time
            hours: $('.top-state .minute-decade'),
            hour: $('.top-state .minute-units'),
            minutes: $('.top-state .second-decade'),
            minute: $('.top-state .second-units'),
            //mark
            title: $('.state-text')
        },
        start: function (countdownSeconds) {
            this.countdownSeconds = countdownSeconds;
            var self = this;
            //foreach
            this.interval = window.setInterval(function () {
                if (self.countdownSeconds > 0) {
                    self.ui.title.html('直播中').attr('liveStatus', "true");
                }
                self.countdownSeconds++;
                //显示
                var timeArr = self.computer(self.countdownSeconds);
                self.show(timeArr);
                //
            }, 1000);
        },
        show: function (timeArr) {
            //attach value
            this.ui.hours.html(timeArr[0].substr(0, 1));
            this.ui.hour.html(timeArr[1]);
            this.ui.minutes.html(timeArr[2]);
            this.ui.minute.html(timeArr[3]);
        },
        stop: function () {
            if (this.interval != undefined) {
                //stop interval
                window.clearInterval(this.interval);
                //title
                this.ui.title = '直播结束';
                //to zero
                this.countdownSeconds = 0;
                this.show(this.computer(0));
            }
        },
        computer: function (kk) {
            var value = kk > 0 ? kk : -kk;
            var theTime = parseInt(value);// 秒
            var theTime1 = 0;// 分
            var theTime2 = 0;// 小时
            if (theTime > 60) {
                theTime1 = parseInt(theTime / 60);
                theTime = parseInt(theTime % 60);
                if (theTime1 > 60) {
                    theTime2 = parseInt(theTime1 / 60);
                    theTime1 = parseInt(theTime1 % 60);
                }
            }
            var result = '';
            if (theTime > 9) {
                result = ',' + parseInt((theTime - theTime % 10) / 10) + ',' + parseInt(theTime % 10) + result;
            } else {
                result = ',0,' + parseInt(theTime) + result;
            }
            if (theTime2 > 0) {
                theTime1 = theTime2 * 60 + theTime1;
            }
            if (theTime1 > 9) {
                result = parseInt((theTime1 - theTime1 % 10) / 10) + ',' + parseInt(theTime1 % 10) + result;
            } else {
                result = '0,' + parseInt(theTime1) + result;
            }
            var timeArr = new Array();
            timeArr = result.split(',');

            return timeArr;
        }
    },
    //直播
    movie: {
        ui: {
            play: $('.button-area .button-play'),
            cover: $('.cover'),
            coverAd: $('#cover-ad'),
            tipTimer: $('#cover-ad #tip-timer'),
            tipText: $('#cover-ad #tip-text'),
            tipGif: $('#cover-ad .tip-gif'),
            tipPlay: $('#cover-ad #tip-play'),
            liveTest: $('.live-test'),
            qaTest: $('.qa-test'),
            video: $('#cover-ad').attr('cover-type') =='2' ? document.getElementById("video") : false,
            tipBlank: $('#cover-ad #tip-blank'),
            playReady: false,
            switch: $('#switch'),
            playUrlType: 'aodianyun'
        },
        isLiveTest: false,
        hideButton: '',
        showFlash: true,
        playStatus: 'living',
        attach: function () {
            var self = this;
            var gifSrc = manager.options.attr.gifSrc + '?' + Math.random();
            this.ui.tipGif.attr('src', gifSrc);
            this.ui.play.on('click', function () {
                var isStart = manager.countdown.countdownSeconds > 0;
                if (self.showFlash) {
                    if (isStart) {
                        self.showFlash = false;
                        self.ui.playReady = true;
                        self.ui.tipText.html('音频努力加载中...');
                        self.ui.tipTimer.html('关闭按钮5秒');
                        self.ui.tipPlay.html('点此开始播放');
                    }
                    self.ui.cover.removeClass('f-hide');
                    self.ui.coverAd.removeClass('f-hide');
                    _hmt.push(['_trackEvent', '直播广告','弹出', self.cid, userStatusBaiduStatistics]);
                    var adCountdownTimer = 5,
                        liveStatus = $('#liveStatus').attr('liveStatus');
                        setIntervalAdTimer = setInterval(function () {
                            if (--adCountdownTimer >= 0) {
                                if (liveStatus == 'false') {
                                    self.ui.tipTimer.html('跳过 ' + adCountdownTimer);
                                } else {
                                    self.ui.tipTimer.html('还剩' + adCountdownTimer + '秒');
                                }
                            } else {
                                clearInterval(setIntervalAdTimer);
                                self.ui.tipTimer.html('跳过 5');
                                if (isStart || self.ui.coverAd.attr('cover-type') =='2') {
                                    self.ui.tipText.addClass('f-hide');
                                    self.ui.tipTimer.addClass('f-hide');
                                    self.ui.tipPlay.removeClass('f-hide');
                                    self.ui.tipBlank.removeClass('f-hide');
                                    if (self.ui.video != false ) {
                                        self.ui.video.addEventListener('ended', function(){
                                            var liveStatus = $('#liveStatus').attr('liveStatus');
                                            self.ui.video.currentTime = 0;
                                            self.ui.video.pause();
                                            if (liveStatus == 'false') {
                                                self.ui.tipText.removeClass('f-hide');
                                                self.ui.tipTimer.removeClass('f-hide');
                                                self.ui.tipPlay.addClass('f-hide');
                                                self.ui.tipBlank.addClass('f-hide');
                                                self.ui.cover.addClass('f-hide');
                                                self.ui.coverAd.addClass('f-hide');
                                            } else {
                                                var isStart = manager.countdown.countdownSeconds > 0;
                                                self.ui.cover.addClass('f-hide');
                                                self.ui.coverAd.addClass('f-hide');
                                                if (isStart) {
                                                    self.play();
                                                }
                                            }
                                        });
                                    }
                                } else {
                                    self.ui.cover.addClass('f-hide');
                                    self.ui.coverAd.addClass('f-hide');
                                }
                            }
                        }, 1000);
                } else {
                    if (isStart || self.isLiveTest) {
                        if (!self.ui.play.hasClass('button-pause')) {
                            self.play();
                        } else {
                            self.stop();
                        }
                    }
                }
            });
            this.ui.switch.one('click', function(){
                manager.movie.ui.playUrlType = 'qcloud';
                if (manager.movie.showFlash == false) {
                    swal({
                        title: "<h1>请稍等</h1>",
                        text: "<h3 style='margin-bottom:-8%;font-size: 30px;'>讲课音频正在切换中...</h3>",
                        timer : 3000,
                        html: true,
                        showConfirmButton: false
                    });
                    manager.movie.ui.play.trigger('click');
                    manager.movie.ui.play.trigger('click');
                }
            });
            //
            this.ui.tipPlay.on('click', function () {
                var liveStatus = $('#liveStatus').attr('liveStatus');
                if (self.ui.coverAd.attr('cover-type') =='2') {
                    var video = document.getElementById('video');
                    video.currentTime = 0;
                    video.pause();
                }
                if (liveStatus == 'false') {
                    self.ui.tipText.removeClass('f-hide');
                    self.ui.tipTimer.removeClass('f-hide');
                    self.ui.tipPlay.addClass('f-hide');
                    self.ui.tipBlank.addClass('f-hide');
                    self.ui.cover.addClass('f-hide');
                    self.ui.coverAd.addClass('f-hide');
                } else {
                    var isStart = manager.countdown.countdownSeconds > 0;
                    self.ui.cover.addClass('f-hide');
                    self.ui.coverAd.addClass('f-hide');
                    if (isStart) {
                        self.play();
                    }
                }
            });
            //直播测试隐藏按钮绑定事件
            // 左边隐藏按钮 直播环节
            this.ui.liveTest.on('click', function () {
                if (!self.ui.play.hasClass('button-pause')) {
                    self.isLiveTest = true;
                    self.hideButton = 'living';
                    self.play();
                } else {
                    self.stop();
                    self.isLiveTest = true;
                    self.hideButton = 'living';
                }
                self.showFlash = false;
            });
            // 右边隐藏按钮 问答环节
            this.ui.qaTest.on('click', function () {
                if (!self.ui.play.hasClass('button-pause')) {
                    self.isLiveTest = true;
                    self.hideButton = 'qAndA';
                    self.play();
                } else {
                    self.stop();
                    self.isLiveTest = true;
                    self.hideButton = 'qAndA';
                }
                self.showFlash = false;
            });
        },
        // 播放函数
        play: function () {
            var date = new Date();
            if (manager.movie.ui.playUrlType == 'aodianyun') {
                src = manager.options.video.hlsUrl + '?' + date.getTime();
            } else if (manager.movie.ui.playUrlType == 'qcloud') {
                src = qcloudUrl;
            }
            buzz.sounds = [];
            this.sound = new buzz.sound(src);
            if (type == 'live') {
                this.sound.play();                var now = parseInt(new Date().getTime() / 1000);

            } else if (type == 'recorded') {
                var now = parseInt(new Date().getTime() / 1000);
                var second = now - startTime;
                // 直播间问答环节测试
                if (this.isLiveTest == true && this.hideButton == 'qAndA') {
                    this.sound.play();
                    // 录播环节测试
                } else if (this.isLiveTest == true && this.hideButton == 'living' ) {
                    myAudio.play();
                    // 录播环节
                } else if (this.playStatus == 'living' && second <= myAudio.duration) {
                    myAudio.currentTime = second;
                    myAudio.play();
                    myAudio.onended = function() {
                        manager.movie.sound.play();
                    };
                    // 直播间问答环节
                } else if (this.playStatus == 'qAndA' || second > myAudio.duration ) {
                    this.sound.play();
                }
            }
            this.ui.play.addClass('button-pause');
            $('.tip-refresh').removeClass('f-hide');
        },
        //
        stop: function () {
            if (type == 'live') {
                if (this.sound !== undefined) {
                    this.sound.stop();
                    // 调为静音, stop不好使
                    this.sound.mute();
                }
            } else if (type == 'recorded') {
                if (this.sound !== undefined) {
                    this.sound.stop();
                    // 调为静音, stop不好使
                    this.sound.mute();
                }
                myAudio.pause();
            }
            this.ui.play.removeClass('button-pause');
            $('.tip-refresh').addClass('f-hide');
        }
    }
};