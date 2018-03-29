$(document).ready(function () {
    setTimeout(function () {
        var mySwiper = new Swiper('.swiper-container', {
            autoplay: 5000,//可选选项，自动滑动
            loop: true,
            pagination: '.swiper-pagination',
            onInit: function(swiper){
                $('.swiper-pagination').css('text-align', 'right');
            }   
         });
    }, 1000);
    var state = 1;

    $('.play_btn').on('click', function () {

        if(state == 1){
            document.getElementById('cr_video').play();
            $("#circle_1").addClass("voice_1");
            $("#circle_2").addClass("voice_2");
            $("#circle_3").addClass("voice_3");
            state = 0
        }else{
            document.getElementById('cr_video').pause();
            state = 1;
            $("#circle_1").removeClass("voice_1");
            $("#circle_2").removeClass("voice_2");
            $("#circle_3").removeClass("voice_3");
        }
        document.getElementById('cr_video').addEventListener('ended', function () {
            $("#circle_1").removeClass("voice_1");
            $("#circle_2").removeClass("voice_2");
            $("#circle_3").removeClass("voice_3");
        }, false);
    });

})