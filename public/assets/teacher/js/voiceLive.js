var VoiceLive = {
    player: null,
    // 开始录音时间
    publishStartTimestamp: 0,
    ui: {
        live: $('#live'),
        mediaInfo: $('#mediaInfo'),
        streamInfo: $('#streamInfo'),
        btnStart: $('[hw_btn_start]'),
        btnLiving: $('[hw_btn_living]'),
        btnStop: $('[hw_btn_end]'),
        videoDisplay: $('#videoDisplay'),
        totalFlow: $('#totalFlow'),
        avgBitrate: $('#avgBitrate'),
        maxBitrate: $('#maxBitrate')

    },
    videoCallBack: function(type, info) {
        var self = this;
        switch (type) {
            case RTMP_MEDIA_INFO:
                switch (info) {
                    case "Svr.Version.Success":
                        break;
                    case "NetConnection.Connect.Success":
                    case "ChangeInfo.NetConnection.Connect.Success":
                    case "new connect":
                        break;
                    case SCHEDULE_FINISH:
                        self.startPublish();
                        break;
                        // case RTMP_PEPFLASH:
                        //  alert("警告：\n\n系统检测到您正在使用 Pepper Flash Player，\n\n此版本的 Flash 并不完善，请尝试更换IE浏览器，\n\n或百度“如何禁用Pepper Flash”。");
                        //  closeConnect();
                        //  break;
                    default:
                        break;
                }
                break;
            case RTMP_MEDIA_ERROR:
                break;
            case MEDIA_DEVICE_INFO:
                switch (info) {
                    case "AVHardwareDisable":
                        alert("flash player 全局设置了禁用硬件设置，修改方法：\nC:\WINDOWS\system32\Macromed\Flash\mms.cfg\n文件，修改为 AVHardwareDisable=0");
                        break;
                        //需要添加其他摄像头麦克风禁用的消息
                    default:
                        break;
                }
                break;
            case RTMP_MEDIA_READY: //swf加载完成消息
                self.player.onSwfReady();
                self.onSwfReady();
                break;
            case RTMP_MEDIA_NETSTREAM_INFO:
                break;
            case RTMP_MEDIA_STATISTICS:
                var obj = JSON.parse(info);
                if (obj) {
                    if (obj.totalFlow >= 1048576) {
                        self.ui.totalFlow.val((obj.totalFlow / 1048576).toFixed(2) + "MB");
                    } else {
                        self.ui.totalFlow.val((obj.totalFlow / 1024).toFixed(2) + "KB");
                    }
                    self.ui.avgBitrate.val((obj.avgBitrate / 1000).toFixed(2) + "kb");
                    self.ui.maxBitrate.val((obj.maxBitrate / 1000).toFixed(2) + "kb");
                }
                break;
            default:
                break;
        }
        if (type != RTMP_MEDIA_NETSTREAM_INFO && type != RTMP_MEDIA_STATISTICS){
            var date = new Date();
            self.ui.mediaInfo.val(date.getHours()+":"+date.getMinutes()+":"+date.getSeconds()+"."+date.getMilliseconds()+' '+info+'\n'+self.ui.mediaInfo.val());
       }
    },

    onSwfReady: function() {

    },


    // 统计信息输出
    setPublishByteCount: function(){
        var tempTotalFlow = this.player.getPublishByteCount();
        var tempMaxBitrateFlow = this.player.getPublishMaxBitrate();
        var publishTimestamp = Date.parse(new Date());

        if (tempTotalFlow > 0) {
            if (tempTotalFlow >= 1048576) {
                this.ui.totalFlow.val((tempTotalFlow / 1048576).toFixed(2) + "MB");
            } else {
                this.ui.totalFlow.val((tempTotalFlow / 1024).toFixed(2) + "KB");
            }

            this.ui.avgBitrate.val(((tempTotalFlow)/(publishTimestamp - this.publishStartTimestamp)).toFixed(2) + "kb");
            this.ui.maxBitrate.val((tempMaxBitrateFlow/1024) .toFixed(2) + "kb");

            // tempTotalFlow 大于 308840 延时10s 发讲师开讲通知
            if (tempTotalFlow > 310000) {
                this.sendTeacherPublish();
            }
        }
    },

    // 发送讲师开始讲师
    sendTeacherPublish: function() {
        var date = new Date();
        if (date.getSeconds()%5 == 0) {
            // dom live 上trigger事件
            this.ui.live.trigger('teacherPublish', {cid: this.options.cid});
        }
    },

    // debug信息输出
    setPublishStreamInfo: function(){
        var publishStreamInfoStr = "当前帧率:  " + this.player.getCurrentFPS().toFixed(2) +"\n" +
                                        "音频码率:  " + this.player.getAudioBytesPerSecond().toFixed(2) + "(kbps)\n" +
                                        "视频码率:  " + this.player.getVideoBytesPerSecond().toFixed(2)  + "(kbps)\n" +
                                        "当前码率:  " + this.player.getCurrentBytesPerSecond().toFixed(2) + "(kbps)\n"+
                                        // "关键帧间隔:     "+  this.player.getKeyFrameInterval() +   "\n" +
                                        "发送字节数: " + this.player.getCurrentByteCount() + "(byte)\n"+
                                        "缓冲区时间:  " + this.player.getBufferLength() +"(s)\n"+
                                        "音频缓冲区时间:    " + this.player.getAudioBufferLength() + "(s)\n"+
                                        "视频缓冲区时间:    " + this.player.getVideoBufferLength() + "(s)\n"+
                                        "音频编码:  " + this.player.getAudioCodec() +"\n"+
                                        "视频编码:  " + this.player.getVideoCodec() +"\n"+
                                        "原始视频宽度: " + this.player.getVideoWidth().toString()+ "\n"+
                                        "原始视频高度: "+ this.player.getVideoHeight().toString()+ "\n"+
                                        "原始视频宽度:   " + this.player.getPublishVideoWidth()+ "\n"+
                                        "原始视频高度:   "+ this.player.getPublishVideoHeight()+ "\n"+
                                        "音频设备:  " + this.player.getMicName() + "\n"+
                                        "视频设备:  " + this.player.getCameraName() ;
        this.ui.streamInfo.val(publishStreamInfoStr);
    },

    startPublish: function() {
        var self = this;
        this.publishStartTimestamp = Date.parse(new Date());

        var width = 4;
        var height = 4;
        var micID = 0;
        var camID = 0;
        var audioCodec = 'Nellymoser';
        var videoCodec = 'h264';
        var audioKBitrate = 44;
        var audioSamplerate = 44100;
        var videoFPS = 1;
        var keyFrameInterval = 1;
        var videoKBitrate = 32;
        var videoQuality = 1;
        var volume = 100;
        var isUseCam = true;
        var isUseMic = true;
        var isHD = false;
        var isUDP = false;
        var isMute = false;

        this.player.startPublish(width,
            height,
            micID,
            camID,
            audioCodec,
            videoCodec,
            audioKBitrate,
            audioSamplerate,
            videoFPS,
            keyFrameInterval,
            videoKBitrate,
            videoQuality,
            volume,
            isUseCam,
            isUseMic,
            isHD,
            isUDP,
            isMute
        );

        if (this.player) {
            this.streamIntervalId = window.setInterval(function() {
                self.setPublishStreamInfo();
                self.setPublishByteCount();
            }, 1000);
        }
    },
    // 初始化发布流
    initConnect: function() {
        var rtmpLive = '';
        var rtmpArea = 'hangzhou';
        var schedulingPing = 1500;
        var limitCheckPing = 1000;
        var checkPingTimer = 1000;
        var userID = '10001';
        var isHD = false;
        var session = '123';
        var isUDP = false;
        var rtmpKey = '';
        this.player.initConnect(this.options.addr,
            rtmpLive,
            this.options.stream,
            rtmpArea,
            schedulingPing,
            limitCheckPing,
            checkPingTimer,
            userID,
            isHD,
            session,
            isUDP,
            rtmpKey
        );

        this.ui.btnStop.show();
        this.ui.btnLiving.show();
        this.ui.btnStart.hide();
    },

    // 停止发布
    stopPublish: function() {
        this.player.stopPublish();
        this.ui.btnStop.hide();
        this.ui.btnLiving.hide();
        this.ui.btnStart.show();
        this.streamIntervalId && window.clearInterval(this.streamIntervalId);
    },

    // 绑定事件
    initEvent: function() {
        var self = this;
        this.ui.btnStart.on('click', function() {
            if (!self.player) {
                return alert('程序没还没准备好,稍等一会.');
            }
            self.initConnect();
        });

        this.ui.btnStop.on('click', function() {
            if (!self.player) {
                return alert('程序没还没准备好,稍等一会.');
            }
            self.stopPublish();
        });
    },

    init: function(options) {
        var self = this;
        this.player = new Video("player1", this.ui.videoDisplay.width(), this.ui.videoDisplay.height() - 26, function(type, info) {
            self.videoCallBack(type, info)
        }, null);
        this.options = options;
        this.initEvent();
    }
};
$(function() {
    var options = {
        cid: cid,
        addr: hls_record_addr,
        stream: hls_record_stream
    };
    VoiceLive.init(options);
});
