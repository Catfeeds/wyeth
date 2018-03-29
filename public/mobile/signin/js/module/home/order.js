define(function(require, exports,modules) {
    var App = {};
    var win = window,
        doc = document;
    App.init = function(){
        addEvent();

    }

    function addEvent(){
        //增加数量
       $(document).delegate('.btnPlus','touchend',function(e){
          var $this = $(this),
              $parent = $this.parents('.for-item'),
              $input = $parent.find('input'),
              $status = $parent.find('.status'),
              $price = $('#price');
          var surplusNumber = parseInt($parent.attr('data-surplusNumber'));
          var number = $input.val();
          if(number>=surplusNumber){
            $status.html('最多可购买'+surplusNumber+'份');
            $this.addClass('gray');
          }else{
            $input.val(++number);
            $this.removeClass('gray');
          }
          $('#total').html("￥"+$price.attr('data-price')*number);
          $('#totalLast').html("￥"+($price.attr('data-price')*number-$('#use').html()));
          if(number>1){
            $('.btnMinus').removeClass('gray');
          }
          e.preventDefault();
       })
       //减少数量
       $(document).delegate('.btnMinus','touchend',function(e){
          var $this = $(this),
              $parent = $this.parents('.for-item'),
              $input = $parent.find('input'),
              $status = $parent.find('.status'),
              $price = $('#price');
          var number = $input.val();
          var surplusNumber = parseInt($parent.attr('data-surplusNumber'));
          if(number>1){
            $input.val(--number);
            if(number<=surplusNumber){
              $status.html('');
              $this.removeClass('gray');
            }
            if(number==1){
              $this.addClass('gray');
            }
          }
          if(number<surplusNumber){
            $('.btnPlus').removeClass('gray');
          }
          $('#total').html("￥"+$price.attr('data-price')*number);
          $('#totalLast').html("￥"+($price.attr('data-price')*number-$('#use').html()));
          e.preventDefault();
       })
       $(document).delegate('.number','blur',function(){
          var $this = $(this),
              $parent = $this.parents('.for-item'),
              $status = $parent.find('.status'),
              $price = $('#price')
          var value = $this.val();
          var surplusNumber = parseInt($parent.attr('data-surplusNumber'));
          if(!/^[1-9]\d*$/g.test(value)){
            $this.val(1);
            $('#total').html("￥"+$price.attr('data-price')*number);
            $('#totalLast').html("￥"+($price.attr('data-price')*number-$('#use').html()));
            return;
          }
          if(value>surplusNumber){
            $this.val(surplusNumber);
            $('#total').html("￥"+$price.html()*number);
            $('#totalLast').html("￥"+($price.attr('data-price')*number-$('#use').html()));
            $status.html('最多可购买'+surplusNumber+'份');
            return;
          }
          if(value<1){
            $this.val(1);
            $('#total').html("￥"+$price.attr('data-price')*number);
            $('#totalLast').html("￥"+($price.attr('data-price')*number-$('#use').html()));
            return;
          }

       })
       //勾选接受规则
       $('#acceptRule').on('click',function(e){
          e.preventDefault();
          $(this).toggleClass('active');
          $('#btnSubmit').toggleClass('disabled')
       })
       //提交订单
       $('#btnSubmit').on('click',function(e){
          e.preventDefault();
          var $this = $(this);
          if($this.hasClass('disabled'))return;
          if($this.text()=='提交订单'){
            var load = new MZ.loading({content:'请求中...'});
            setTimeout(function(){
              load.hide();
              $('.for-time').show();
              $('#btnSubmit').html('立即支付');
              ticker();
            },1500);
          }else{
            MZ.alert({content:'支付成功',callback:function(){
              alert('确定');
            }});
            clearInterval(timer);
          }
       })
    }

    var $time;
    var timer ;
    function ticker(){
      $time = $('.time');
      var totalTime = 15*60*1000;
      timer = setInterval(function(){
        totalTime -= 1000;
        if(totalTime==0){
          //15分钟倒计时结束
          $('#btnSubmit').addClass('disabled').html('支付失败');
          clearInterval(timer);
        }
        countdown(totalTime);
      },1000)
      
    }
    function countdown(t){
        var d = 24*60*60*1000,
            h = 60*60*1000,
            m = 60*1000,
            s = 1000;
        var D = parseInt(t/d),
            H = parseInt((t-D*d)/h),
            M = parseInt((t-D*d-H*h)/m),
            S = parseInt((t-D*d-H*h-M*m)/s);
            $time.html(intNumber(M)+' <span class="empty">:</span> '+intNumber(S));
    }
 
    function intNumber(n){
      return n<10?'<span>0</span><span>'+n+"</span>":'<span>'+parseInt(n/10)+'</span>'+'<span>'+parseInt(n%10)+'</span>';
    }
    modules.exports = App;
});
