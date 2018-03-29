define(function(require, exports,modules) {
    var App = {};
    var win = window,
        doc = document;

    function layout(){
        var winH = $(window).height();
        var h  = winH - 250;
        if(h<950){
         $('.pic-product').css({'-webkit-transform':'scale('+h/960+')','-webkit-transform-origin':'center top'});
        }
    }
    App.init = function(){
        addEvent();
        layout();
    }
    function addEvent(){
        $(document).on('touchmove',function(e){
            e.preventDefault();
        })
        var StartY = 0,
            EndY = 0;
        $('body').on({
            touchstart: function(e) {
               StartY=e.touches[0].clientY;
               e.preventDefault();
            },
            touchmove: function(e) {
                EndY=e.touches[0].clientY;
                e.preventDefault();
            },
            touchend: function(e) {
               if(EndY==0)return;
               console.log(StartY,EndY)
               if(StartY-EndY>30){
                 $('.page').css({'-webkit-transform':'translateY(-100%)'});
               }
               if(EndY-StartY>30){
                 $('.page').css({'-webkit-transform':'translateY(0)'});
               }
               EndY=0;
               e.preventDefault();
            }
        })
        $('#btnBuy').on('click touchend',function(e){
          e.preventDefault();
          var $this = $(this);
          if($this.hasClass('disabled'))return;
          location.href = 'order.html';
        })
    }
    
    ticker();
    var $time;
    function ticker(){
      var startTime = new Date('2016/5/5 15:30:00').getTime();//活动开始时间
      var endTime = new Date('2016/5/5 15:30:04').getTime();//活动结束时间
      var serverTime = new Date('2016/5/5 15:29:55').getTime();//服务器时间
      var localTime = new Date().getTime();//本地时间
      var t=1000;
      $time = $('.icon-top-bar');
      var $btnBuy = $('#btnBuy');
      var timer = setInterval(function(){
        t = new Date().getTime()-localTime;
        count();
      },1000)
      count();
      function count(){
        var stime = serverTime+t;
        if(stime<=startTime){
          //还未开始
          $btnBuy.addClass('disabled');
          countdown(startTime-stime,1);
        }
        if(stime>startTime && stime<=endTime){
          //进行中
          $btnBuy.removeClass('disabled');
          countdown(endTime-stime,2);
        }
        if(stime>endTime){
          //已结束
          clearInterval(timer);
          $btnBuy.addClass('disabled').html('<span class="cicon icon-end"></span>已秒光');
          $time.html('<p>秒杀已结束，下次要赶早哦</p>');
        }
      }
    }
    function countdown(t,type){
        var d = 24*60*60*1000,
            h = 60*60*1000,
            m = 60*1000,
            s = 1000;
        var D = parseInt(t/d),
            H = parseInt((t-D*d)/h),
            M = parseInt((t-D*d-H*h)/m),
            S = parseInt((t-D*d-H*h-M*m)/s);
            if(type==1){
              $time.html('距离开始还有 '+intDay(D)+" 天 "+intNumber(H)+" 小时 "+intNumber(M)+" 分 "+intNumber(S)+" 秒");
            }
            if(type==2){
              $time.html('距离结束还有 '+intDay(D)+" 天 "+intNumber(H)+" 小时 "+intNumber(M)+" 分 "+intNumber(S)+" 秒");
            }
    }
    function intDay(n){
      return n<10?'<span>'+n+"</span>":'<span>'+parseInt(n/10)+'</span>'+'<span>'+parseInt(n%10)+'</span>';
    }
    function intNumber(n){
      return n<10?'<span>0</span><span>'+n+"</span>":'<span>'+parseInt(n/10)+'</span>'+'<span>'+parseInt(n%10)+'</span>';
    }
    modules.exports = App;
});
