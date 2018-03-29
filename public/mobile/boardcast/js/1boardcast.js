/**
 * 总经理手下有这么几个干活的工人：
 * 管通讯的 communication
 * 管浮层的，layer
 * 管献花的 flowers
 * 管放电影的 movie
 * 管统计在线人数的 updateOnlineNumber
 * 管倒计时的 countdown
 */
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

                //test
                /*
                 console.log('tupian  :  ' + shareUrl);
                 console.log('tupian  :  ' + share_title);
                 console.log('tupian  :  ' + share_desc);
                 console.log('tupian  :  ' + firend_title);
                 console.log('tupian  :  ' + img_url);
                 window.console.log(wx);
                 */
                wx.error(function (res) {
                    // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
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
                    //
                });
                //
            }
        }); // ajax over

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
            'showPresentedFail': 'boardcast/img/showPresentedFail.png',
        }
        this.countdown.start(this.options.course.countdownSeconds);

        this.movie.attach();
        this._initEventBind();
        this._initWebSocket();
        this._configWX();
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
                token: manager.options.user.token,
            }));
        };
        this.ws.onmessage = function (e) {
            self.handleMessage(e);
        };
        this.ws.onclose = function () {
            self._initWebSocket();
        };
        this.ws.onerror = function () {
            window.console.log('warning: communication error');
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

        /*
         $('.p-ct').height($(window).height());
         $('.m-page').height($(window).height());
         $('.translate-back').height($(window).height());
         */

        /*
         $('.translate-back').removeClass('f-hide');
         $('.page-index').removeClass('f-hide');
         $(function(){
         $('.u-pageLoading').hide();
         });
         */
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
        // at 按钮
        $('.page-message .button-at').on('click', function () {
            $('.page-message .pop-at-box').removeClass('f-hide');
            $('.page-message .arrow-up-at').removeClass('f-hide');
            $('.page-message .pop-face-box').addClass('f-hide');
            $('.page-message .arrow-up-face').addClass('f-hide');
        });
        // face 按钮
        $('.page-message .button-face').on('click', function () {
            $('.page-message .pop-face-box').removeClass('f-hide');
            $('.page-message .arrow-up-face').removeClass('f-hide');
            $('.page-message .pop-at-box').addClass('f-hide');
            $('.page-message .arrow-up-at').addClass('f-hide');
            if ($('.page-message .button-face').hasClass('keyboard')) {
                $('.page-message .button-face').removeClass('keyboard');
                $('.page-message .pop-face-box').addClass('f-hide');
                $('.page-message .arrow-up-face').addClass('f-hide');
            } else {
                $('.page-message .button-face').addClass('keyboard');
            }
        });
        // emotion
        /*
         $('.button-face').qqFace({
         id : 'facebox',
         assign: 'saytext',
         path: 'boardcast/img/emoticon/' //表情存放的路径
         });
         */
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
            })(),
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
                    /*
                     var nextNum = parseInt($('.lecturer .flower-number').data('number')) + 1;
                     $('.lecturer .flower-number').html('+' + nextNum);
                     $('.lecturer .flower-number').data('number', nextNum);
                     */
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
                this.fail();
            }
        },
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
            }

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
            }

            this.cloud = window.document.body.appendChild(div);
            this.cloud = window.document.body.appendChild(img);
            var self = this;
            /*
             $(div).click(function(){
             self.div.style.display = 'block';
             self.img.style.display = 'block';
             });
             */
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
                }
                break;
            // 用户退出 更新用户列表
            case 'logout':
                this.updateOnlineNumber(data.online_num);
                break;
            case 'finishLiving':
                this.countdown.stop();
                break;
            default:
                window.console.log('---------------------');
                window.console.log(e);
                break;
        }
        $(document).trigger(eventName, data);
    },
    //讨论区的相关功能
    /*
     discuss : {
     //处理讨论区的广播 ： 收到的消息(献花消息|用户留言)附加到讨论区后面
     receive : function(data){
     //
     window.console.log(data);
     },
     send : function(str){
     this.sendSocketData({
     //讨论区传什么样的数据呢
     //
     });
     }
     },
     */
    //
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
            title: $('.state-text'),
        },
        start: function (countdownSeconds) {
            this.countdownSeconds = countdownSeconds;
            var self = this;
            //foreach
            this.interval = window.setInterval(function () {
                if (self.countdownSeconds > 0) {
                    self.ui.title.html('直播中');
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
            //
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
            coverAd: $('.cover-ad'),
            tipTimer: $('.cover-ad .tip-timer'),
            tipText: $('.cover-ad .tip-text'),
            tipGif: $('.cover-ad .tip-gif'),
        },
        showFlash: true,
        attach: function () {
            var self = this;
            var gifSrc = manager.options.attr.gifSrc + '?' + Math.random();
            this.ui.tipGif.attr('src', gifSrc);
            this.ui.play.on('click', function () {
                var isStart = manager.countdown.countdownSeconds > 0;
                if (self.showFlash) {
                    if (isStart) {
                        self.showFlash = false;
                        self.ui.tipText.html('音频努力加载中...');
                        // 这里仅创建
                        self.createSound(manager.options.video.hlsUrl);
                    }

                    self.ui.cover.removeClass('f-hide');
                    self.ui.coverAd.removeClass('f-hide');

                    var adCountdownTimer = 5,
                        setIntervalAdTimer = setInterval(function () {
                            if (--adCountdownTimer >= 0) {
                                self.ui.tipTimer.html('还剩' + adCountdownTimer + '秒');
                            } else {
                                if (isStart) {
                                    // 这里不再创建
                                    self.play(false);
                                }
                                clearInterval(setIntervalAdTimer);
                                self.ui.cover.addClass('f-hide');
                                self.ui.coverAd.addClass('f-hide');
                                self.ui.tipTimer.html('还剩5秒');
                            }
                        }, 1000);
                } else {
                    if (isStart) {
                        if (!self.ui.play.hasClass('button-pause')) {
                            self.play(true);
                        } else {
                            self.stop();
                        }
                    }
                }
            });
            //
        },
        // 播放函数
        play: function (create) {
            if (create) {
                this.createSound(manager.options.video.hlsUrl);
            }
            this.sound.unmute();
            this.ui.play.addClass('button-pause');
            $('.tip-refresh').removeClass('f-hide');
        },
        //
        stop: function () {
            if (this.sound !== undefined) {
                this.sound.stop();
                // 调为静音, stop不好使
                this.sound.mute();
            }
            this.ui.play.removeClass('button-pause');
            $('.tip-refresh').addClass('f-hide');
        },
        // 创建音频 注意这里创建完后就静音,然后播放.
        createSound: function (src) {
            var date = new Date();
            src = src + '?' + date.getTime();
            buzz.sounds = [];
            this.sound = new buzz.sound(src);
            // 静音
            this.sound.mute();
            this.sound.play();
        }

    }
};
