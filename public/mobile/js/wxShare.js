var wxShare = {

	initWx: function(sharemessage) {
        var self = this;
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: self.options.package.appId, // 必填，企业号的唯一标识，此处填写企业号corpid
            timestamp: self.options.package.timestamp, // 必填，生成签名的时间戳
            nonceStr: self.options.package.nonceStr,  // 必填，生成签名的随机串
            signature: self.options.package.signature, // 必填，签名，见附录1
            jsApiList: [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage'
            ]
        });

        var shareUrl = self.options.shareUrl;
        var shareTitle = self.options.shareTitle;
        var shareDesc = self.options.shareDesc;
        var imgUrl = self.options.imgUrl;

        wx.ready(function(){
            // 分享朋友圈的数据
            wx.onMenuShareTimeline({
                title: shareTitle // 分享标题
                link: _mz_wx_shareUrl(shareUrl), // 分享链接
                imgUrl: imgUrl,
                success:function() {
                    _mz_wx_timeline();
                    _t_view(self.t_ids.share_timeline, '直播-分享朋友圈')
                }
            });

            // 分享给好友的数据
            wx.onMenuShareAppMessage({
                title: '妈妈微课堂', // 分享标题
                desc: share_desc, // 分享描述
                link: _mz_wx_shareUrl(shareUrl), // 分享链接
                imgUrl: self.options.static_url + '/mobile/images/logo.jpg', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success:function() {
                    _mz_wx_friend();
                    _t_view(self.t_ids.share_friend, '直播-分享好友')
                }
            });
        });
    },

    getPackage: function () {
    	var self = this;
    	var package = {};

    	$.ajax({
            type: "GET",
            async: false,
            cache: false,
            url: ,
            dataType: "jsonp",
            jsonp: "callback",
            jsonpCallback: "getJsApiTicket",
            success: function(ret) {
            	package = ret;
            }
        })

    	return package;
    	
    },

	init: function (options) {
		var self = this;
		var package = self.getPackage();


	}
}