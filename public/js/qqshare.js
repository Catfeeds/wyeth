var QqshareSDK = {
    initConfig: function() {
        var self = this;
        var timestamp = parseInt(new Date().getTime() / 1000);
        $.ajax({
            type: "GET",
            url: 'http://wyeth.qq.nplusgroup.com/api/toauth/index.json',
            data: 'url=' + encodeURIComponent(location.href.split('#')[0]) + '&callback=?',
            dataType: "jsonp",
            jsonp: "callback",
            success: function(json) {
                var mqqConfig = {
                    debug: false,
                    appId: json.appId,
                    timestamp: json.timestamp,
                    nonceStr: json.nonceStr,
                    signature: json.signature,
                    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareQzone', 'closeWindow', 'hideOptionMenu', 'showOptionMenu', 'hideMenuItems', 'showMenuItems', 'hideAllNonBaseMenuItem']
                };
                mqq.config(mqqConfig);
                mqq.ready(function() {
                    mqq.onMenuShareTimeline(self.options.shareTimelineData);
                    mqq.onMenuShareAppMessage(self.options.shareAppData);
                    mqq.onMenuShareQQ(self.options.shareQQData);
                    mqq.onMenuShareWeibo(self.options.onMenuShareWeibo);
                    mqq.hideMenuItems({
                        menuList: self.options.hideMenuList
                    })
                });
            }
        });
    },
    init: function(options) {
        var defaultOptions = {
            // 当前页的地址
            reqUrl: '',
            debug: true,
            jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo'],
            shareTimelineData: {
                title: '',
                link: '',
                imgUrl: '',
                trigger: function() {},
                success: function() {},
                cancel: function() {},
                fail: function() {}
            },
            shareAppData: {
                title: '',
                desc: '',
                imgUrl: '',
                trigger: function() {},
                success: function() {},
                cancel: function() {},
                fail: function() {}
            },
            shareQQData: {
                title: '',
                desc: '',
                imgUrl: '',
                trigger: function() {},
                success: function() {},
                cancel: function() {},
                fail: function() {}
            },
            shareWeiboData: {
                title: '',
                desc: '',
                imgUrl: '',
                trigger: function() {},
                success: function() {},
                cancel: function() {},
                fail: function() {}
            },
            hideMenuList: []
        };
        this.options = _.extend(defaultOptions, options);
        this.initConfig();
    }
};