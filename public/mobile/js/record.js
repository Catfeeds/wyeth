var Record =  {

    // 初始化秒针
    initMz: function () {
        var self = this;
        _mwx = window._mwx||{};
        _mwx.siteId = this.options.mz.site_id;
        _mwx.openId = this.options.mz.openid;
        _mwx.debug = true;
    },

    // 初始化 dataeye
    initDc: function () {
        (function(b,f){
            var a=document.createElement("script");a.async=true;a.charset="UTF-8";a.src=f;var d=document.querySelector("script");d.parentNode.insertBefore(a,d);var e=[];var c=function(h){if(typeof DCAgent==="undefined"){e.push(arguments)}else{var g=DCAgent[h];if(!g){return console.log("DCAgent."+h+" is undefined")}if(typeof g==="function"){return g.apply(DCAgent,[].slice.call(arguments,1))}else{return g}}};c.loadTime=Date.now();c.cache=e;window[b]=c;window["DCAgentObject"]=b
        })("dc", this.options.static_url + "/js/dcagent.v2.min.js");

        dc('init', {appId: this.options.dc.appid, channel: this.options.channel});
        dc('login', this.options.uid);
    },

    // 页面加载
    page: function(event_name, extra, page) {
        extra = $.extend(extra, {channel: this.options.channel});
        _mz_wx_view(page, extra);
        dc('onEvent', event_name, extra);
    },

    // 事件
    event: function(event_name, extra, eventid) {
        if (event) {
            _mz_wx_custom(eventid);
        }
        extra = $.extend(extra, {channel: this.options.channel});
        dc('onEvent', event_name, extra);
    },

    // 朋友圈
    timeline: function(event_name) {
        _mz_wx_timeline();
        dc('onEvent', 'timeline_' + event_name, extra);
    },

    // 好友
    friend: function(event_name) {
        _mz_wx_friend();
        dc('onEvent', 'friend_' + event_name, extra);
    },

    init: function (options) {
        this.options = options;
        this.initMz();
        this.initDc();
    }
};