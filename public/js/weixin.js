var WeiXinSDK = {
    initConfig: function() {
        var self = this;
        var timestamp = parseInt(new Date().getTime() / 1000);
        $.ajax({
            type: "GET",
            async: false,
            cache: false,
            url: '/api/wechat/package?url=' + encodeURIComponent(this.options.reqUrl) + "&t=" + timestamp,
            dataType: "json",
            success: function(ret) {
                if (ret.status) {
                    var config = _.extend({
                        debug: self.options.debug,
                        jsApiList: self.options.jsApiList
                    }, ret.package);
                    wx.config(config);
                    wx.ready(function() {
                        wx.onMenuShareTimeline(self.options.shareTimelineData);
                        wx.onMenuShareAppMessage(self.options.shareAppData);
                        wx.onMenuShareQQ(self.options.shareQQData);
                        wx.onMenuShareWeibo(self.options.onMenuShareWeibo);
                        wx.hideMenuItems({
                            menuList: self.options.hideMenuList
                        })
                    });
                }
            }
        })
    },
    init: function(options) {
        var defaultOptions = {
            // 当前页的地址
            reqUrl: '',
            debug: false,
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