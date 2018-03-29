function PageInit(){
  var debug = true;
  var log = function(){
        if(!debug) return;
        console.log.apply(console,arguments);
    }
  
  //初始化课件轮播区域
  $("#wrapper").height($(window).height());
  var mySwiper = new Swiper('.swiper-container', {
    autoplay: 5000,//可选选项，自动滑动
    loop: true
  });
  
  //初始化用户互动发言区滑动
  var user_answer = new iScroll("user_answer", { 
          hScroll: false,
          hScrollbar: false,
          vScrollbar: false,
          momentum: false,
          checkDOMChanges: true
      });
  //
  $(document).ready(function () {
    var swiper_answer; //问答区滑动组件
    
    //显示全部音频文件
    function all_voice () {
      $(".b_chat_area").hide();
      $(".b_reply_wrap").css("bottom", "-54px");
      $(".all_tc").removeClass("ds-n");
      $(".user_answer").addClass("ds-n");
      $(".all_tc").animate({top:"59%"}, function () {
        swiper_answer = new iScroll("more_answer", { 
          hScroll: false,
          hScrollbar: false,
          vScrollbar: false,
          momentum: false,
          checkDOMChanges: true
        });
        $(".allvioce_btn").unbind();
      });

    }
    
    //显示全部音频文件
    $(".allvioce_btn").on("click",function () {
      all_voice();
    });
    
    //收起全部音频文件
    $(".hidden_btn").on("click", function () {
      $(".all_tc").animate({top:"101%"}, function () {  
        $(".all_tc").addClass("ds-n");
        $(".user_answer").removeClass("ds-n"); 
        $(".b_chat_area").show(500);
      });
      $(".b_reply_wrap").css("bottom", "0");
      swiper_answer.destroy();
      $(".allvioce_btn").on("click",function () {
        all_voice();
      });
    });
  });
}
