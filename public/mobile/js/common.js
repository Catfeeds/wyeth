var MZ = (function(obj){
	
	return obj;
})(MZ || {})
var ApiPrefix = 'http://172.17.1.112:8811/weixin';
;MZ.browser = {
	sticky: function(){
			var a,b="-webkit-sticky",
			c=document.createElement("i");
			return c.style.position=b,a=c.style.position,c=null,a===b
		}(),
	isIos: navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? !0 : !1,
	isAndroid: -1 < navigator.userAgent.indexOf("Android"),
	isMobile: /AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))
}

;MZ.utils = {

	getQueryString : function(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return r[2]; return null;
    },
    leaveTime: function(startTime,endTime){
    	if(startTime>=endTime){
    		return 0;
    	}else{
    		var t =  endTime - startTime;
    		var h = 60*60*1000,
    			m = 60*1000,
    			s = 1000;
    		var H = parseInt(t/h),
    			M = parseInt((t-H*h)/m),
    			S = parseInt((t-H*h-M*m)/s);
    		return intNumber(H)+":"+intNumber(M)+":"+intNumber(S);
    	}
    	function intNumber(n){
	    	return n>9? n:'0'+n;
	    }
    },
    
}
;MZ.mask = {

	show : function($mask,type) {
		var $maskInner = $mask.find('.mask-inner'),
			$maskBg = $mask.find('.mask-bg');
		$mask.css({'height':window.innerHeight, 'display':'block'}).addClass('fadeIn');
		switch(type){
			case 'slideDown':
				$maskInner.addClass('slideDown');
				setTimeout(function(){
					$maskInner.addClass('in');
				},20)

		}
    },
    hide: function($mask,type) {
    	var $maskInner = $mask.find('.mask-inner'),
			$maskBg = $mask.find('.mask-bg');
		$mask.removeClass('fadeIn');
		$maskInner.removeClass('slideDown').removeClass('in');
		$mask.hide();
    }
    
}
;(function(obj){
    var body = document.body;
    obj.dropdown = {
        init: function(){
            this.addEvent();
        },
        addEvent: function(){
           $(document).delegate('.for-slide .item-title','click',function(){
             var $this = $(this)
             $this.toggleClass('active');
             $this.next().toggle();
           })
        }
    }     
    obj.radio = {
        init: function(){
            this.addEvent();
        },
        addEvent: function(){
            $(document).delegate('.radio-list li','click',function(){
                var $this = $(this);
                $this.toggleClass('active').siblings('li').removeClass('active');
            })
        }
    }  
    obj.toggle = {
        init: function(){
            this.addEvent();
        },
        addEvent: function(){
            $(document).delegate('.toggle','click',function(){
                var $this = $(this);
                $this.toggleClass('active');
            })
        }
    }
    obj.confirm = function(config){
        var content = config.content ? config.content : '',
            title = config.title ? config.title : '确认提示',
            cancleFunc = config.cancle,
            callback = config.callback;
        var timestamp = new Date().getTime();
        var str = '<div class="weui_dialog_confirm" id="dialog'+timestamp+'">'+
                   ' <div class="weui_mask"></div>'+
                   ' <div class="weui_dialog">'+
                   '     <div class="weui_dialog_hd"><strong class="weui_dialog_title">'+title+'</strong></div>'+
                   '     <div class="weui_dialog_bd">'+content+'</div>'+
                   '     <div class="weui_dialog_ft">'+
                   '         <a href="javascript:;" class="weui_btn_dialog default jsCancle">取消</a>'+
                   '         <a href="javascript:;" class="weui_btn_dialog primary jsOk">确定</a>'+
                   '     </div>'+
                   ' </div>'+
                '</div>';
        $('body').append(str);
        var $dialog = $('#dialog'+timestamp);
        $dialog.find('.jsCancle').on('click',function(){
            $dialog.remove();
            cancleFunc && cancleFunc();
        })
        $dialog.find('.jsOk').on('click',function(e){
            callback && callback(e);
            $dialog.remove();
        })
        this.hide = function(){
            $dialog.remove();
        }
        return 'dialog'+timestamp;
    }
    obj.alert = function(config){
        var content = config.content ? config.content : '',
            title = config.title ? config.title : '系统消息',
            cancleFunc = config.cancle,
            callback = config.callback;
        var timestamp = new Date().getTime();
        var str = '<div class="weui_dialog_confirm" id="alert'+timestamp+'">'+
                   ' <div class="weui_mask"></div>'+
                   ' <div class="weui_dialog">'+
                   '     <div class="weui_dialog_hd"><strong class="weui_dialog_title">'+title+'</strong></div>'+
                   '     <div class="weui_dialog_bd">'+content+'</div>'+
                   '     <div class="weui_dialog_ft">'+
                   '         <a href="javascript:;" class="weui_btn_dialog primary jsOk">确定</a>'+
                   '     </div>'+
                   ' </div>'+
                '</div>';
        $('body').append(str);
        var $dialog = $('#alert'+timestamp);
        $dialog.find('.jsOk').on('click',function(e){
            callback && callback(e);
            $dialog.remove();
        })
        return 'alert'+timestamp;
    }
    obj.showPrize = function(config){
      var id = config.id,
          number = config.number,
          name = config.name;
      var str = '<div class="modal transparent active" id="pageLottery" style="display: none;">'+
                '<div class="lottery-dialog">'+
                 '   <div class="inner">'+
                 '       <div class="pic"><img src="../src/images/goods/01.png"></div>'+
                 '       <p>期号：'+number+'</p>'+
                 '       <h3>'+name+'</h3>'+
                 '       <div class="btn-area">'+
                 '           <a href="/views/user/address.html?id='+id+'" class="btn btn-red">选择收货地址</a>'+
                 '       </div>'+
                 '   </div>'+
                '</div>'+
                '<a href="javascript:" class="btn-close"><i class="icon icon-close-wechat"></i></a>'+
            '</div>'
        var $pageLottery = $(str);
        $('body').append($pageLottery);
        $pageLottery.fadeIn();
        setTimeout(function(){
            $pageLottery.addClass('animatein');
        },500)
        $pageLottery.find('.btn-close').on('click',function(){
          $pageLottery.removeClass('active');
        })
    }
    obj.showShareResult = function(config){
      var title = config.title,
          desc = config.desc;
      var str = '<div class="modal transparent active" id="pageShareResult" style="display: none;">'+
                '<div class="lottery-dialog">'+
                 '   <div class="inner">'+
                 '       <div class="pic"><img src="../src/images/goods/01.png"></div>'+
                 '       <p>'+title+'</p>'+
                 '       <h3>'+desc+'</h3>'+
                 '       <div class="btn-area">'+
                 '           <a href="/views/index.html" class="btn btn-red">马上购物</a>'+
                 '       </div>'+
                 '   </div>'+
                '</div>'+
                '<a href="javascript:" class="btn-close"><i class="icon icon-close-wechat"></i></a>'+
            '</div>'
        var $pageLottery = $(str);
        $('body').append($pageLottery);
        $pageLottery.fadeIn();
        setTimeout(function(){
            $pageLottery.addClass('animatein');
        },500)
        $pageLottery.find('.btn-close').on('click',function(){
          $pageLottery.removeClass('active');
        })
    }
    return obj;
})(MZ || {});

//:active效果
document.body.addEventListener('touchstart',function(){},false);
MZ.dropdown.init();
MZ.radio.init();
MZ.toggle.init();
;MZ.wechat = {
	/**
	 * 初始化微信分享配置
	 */
	 /*MZ.wechat.init({
	 	title: 'title',//不可为空
	 	desc: 'desc',//不可为空
	 	link: 'link',//不可为空
	 	imgUrl: 'imgUrl',//不可为空
	 	trigger: function(){},//可为空
	 	success: function(){},//可为空
	 	cancel: function(){},//可为空
	 	fail: function(){}//可为空
	 })*/
	init: function(config){
		_czc = _czc || {},
		_hmt = _hmt || {};
		if(!config){
			alert('config undefined');
		}
		wx.ready(function () {
            //分享给朋友
            wx.onMenuShareAppMessage({
              title: config.title,
              desc: config.desc,
              link: ''+config.link+'',
              imgUrl: ''+config.imgUrl+'',
              trigger: function (res) {
              	_czc.push(["_trackEvent", "点击弹出分享给朋友", "click", 'startup', 1]);
			    _hmt.push(['_trackEvent', "点击弹出分享给朋友", "click", 'startup', 1]);
              	config.trigger && config.trigger();
              },
              success: function (res) {
              	_czc.push(["_trackEvent", "分享给朋友", "click", 'startup', 1]);
			    _hmt.push(['_trackEvent', "分享给朋友", "click", 'startup', 1]);
			    config.success && config.success();
              },
              cancel: function (res) {
              	_czc.push(["_trackEvent", "取消分享给朋友", "click", 'startup', 1]);
			    _hmt.push(['_trackEvent', "取消分享给朋友", "click", 'startup', 1]);
              	config.cancel && config.cancel();
              },
              fail: function (res) {
              	_czc.push(["_trackEvent", "分享到朋友失败", "click", 'startup', 1]);
			    _hmt.push(['_trackEvent', "分享到朋友失败", "click", 'startup', 1]);
              	config.fail && config.fail();
              }
            });
            //分享到朋友圈
            wx.onMenuShareTimeline({
              title: config.title,
              desc: config.desc,
              link: ''+config.link+'',
              imgUrl: ''+config.imgUrl+'',
              trigger: function (res) {
              	_czc.push(["_trackEvent", "点击弹出分享到朋友圈", "click", 'startup', 1]);
			    _hmt.push(['_trackEvent', "点击弹出分享到朋友圈", "click", 'startup', 1]);
              	config.trigger && config.trigger();
              },
              success: function (res) {
              	_czc.push(["_trackEvent", "成功分享到朋友圈", "click", 'startup', 1]);
			    _hmt.push(['_trackEvent', "成功分享到朋友圈", "click", 'startup', 1]);
			    config.success && config.success();
              },
              cancel: function (res) {
              	_czc.push(["_trackEvent", "取消分享到朋友圈", "click", 'startup', 1]);
			    _hmt.push(['_trackEvent', "取消分享到朋友圈", "click", 'startup', 1]);
              	config.cancel && config.cancel();
              },
              fail: function (res) {
              	_czc.push(["_trackEvent", "分享到朋友圈失败", "click", 'startup', 1]);
			    _hmt.push(['_trackEvent', "分享到朋友圈失败", "click", 'startup', 1]);
              	config.fail && config.fail();
              }
            });
        })
        wx.error(function (res) {
          alert(res.errMsg);
        });
	}

}
;(function (root, factory) {
  if (typeof define === 'function' && define.amd) {
    define(function() {
      return factory(root);
    });
  } else if (typeof exports === 'object') {
    module.exports = factory;
  } else {
    root.echo = factory(root);
  }
})(this, function (root) {

  'use strict';

  var echo = {};

  var callback = function () {};

  var offset, poll, delay, useDebounce, unload;

  var isHidden = function (element) {
    return (element.offsetParent === null);
  };
  
  var inView = function (element, view) {
    if (isHidden(element)) {
      return false;
    }

    var box = element.getBoundingClientRect();
    return (box.right >= view.l && box.bottom >= view.t && box.left <= view.r && box.top <= view.b);
  };

  var debounceOrThrottle = function () {
    if(!useDebounce && !!poll) {
      return;
    }
    clearTimeout(poll);
    poll = setTimeout(function(){
      echo.render();
      poll = null;
    }, delay);
  };

  echo.init = function (opts) {
    opts = opts || {};
    var offsetAll = opts.offset || 0;
    var offsetVertical = opts.offsetVertical || offsetAll;
    var offsetHorizontal = opts.offsetHorizontal || offsetAll;
    var optionToInt = function (opt, fallback) {
      return parseInt(opt || fallback, 10);
    };
    offset = {
      t: optionToInt(opts.offsetTop, offsetVertical),
      b: optionToInt(opts.offsetBottom, offsetVertical),
      l: optionToInt(opts.offsetLeft, offsetHorizontal),
      r: optionToInt(opts.offsetRight, offsetHorizontal)
    };
    delay = optionToInt(opts.throttle, 250);
    useDebounce = opts.debounce !== false;
    unload = !!opts.unload;
    callback = opts.callback || callback;
    echo.render();
    if (document.addEventListener) {
      root.addEventListener('scroll', debounceOrThrottle, false);
      root.addEventListener('load', debounceOrThrottle, false);
    } else {
      root.attachEvent('onscroll', debounceOrThrottle);
      root.attachEvent('onload', debounceOrThrottle);
    }
  };

  echo.render = function () {
    var nodes = document.querySelectorAll('img[data-echo], [data-echo-background]');
    var length = nodes.length;
    var src, elem;
    var view = {
      l: 0 - offset.l,
      t: 0 - offset.t,
      b: (root.innerHeight || document.documentElement.clientHeight) + offset.b,
      r: (root.innerWidth || document.documentElement.clientWidth) + offset.r
    };
    for (var i = 0; i < length; i++) {
      elem = nodes[i];
      if (inView(elem, view)) {

        if (unload) {
          elem.setAttribute('data-echo-placeholder', elem.src);
        }

        if (elem.getAttribute('data-echo-background') !== null) {
          elem.style.backgroundImage = "url(" + elem.getAttribute('data-echo-background') + ")";
        }
        else {
          elem.src = elem.getAttribute('data-echo');
        }

        if (!unload) {
          elem.removeAttribute('data-echo');
          elem.removeAttribute('data-echo-background');
        }

        callback(elem, 'load');
      }
      else if (unload && !!(src = elem.getAttribute('data-echo-placeholder'))) {

        if (elem.getAttribute('data-echo-background') !== null) {
          elem.style.backgroundImage = "url(" + src + ")";
        }
        else {
          elem.src = src;
        }

        elem.removeAttribute('data-echo-placeholder');
        callback(elem, 'unload');
      }
    }
    if (!length) {
      echo.detach();
    }
  };

  echo.detach = function () {
    if (document.removeEventListener) {
      root.removeEventListener('scroll', debounceOrThrottle);
    } else {
      root.detachEvent('onscroll', debounceOrThrottle);
    }
    clearTimeout(poll);
  };

  return echo;

});