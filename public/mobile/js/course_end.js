var Course_End = {
    initSwiper: function() {
        if ($('.swiper-slide').length != 1) {
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
        } else {
            setTimeout(function () {
                var mySwiper = new Swiper('.swiper-container', {
                    loop: true,
                    pagination: '.swiper-pagination',
                    onInit: function(swiper){
                        $('.swiper-pagination').css('text-align', 'right');
                    }
                 });
            }, 1000);
        }
    },

    initPlay: function() {
        var self = this;
        var state = 1;
        $('.play_btn').on('click', function () {
            if(state == 1) {
                document.getElementById('cr_audio').play();
                $("#circle_1").addClass("voice_1");
                $("#circle_2").addClass("voice_2");
                $("#circle_3").addClass("voice_3");
                state = 0
                // 统计
                _hmt.push(['_trackEvent', 'end_voice', 'play', self.course_id]);
            } else {
                document.getElementById('cr_audio').pause();
                state = 1;
                $("#circle_1").removeClass("voice_1");
                $("#circle_2").removeClass("voice_2");
                $("#circle_3").removeClass("voice_3");
            }
            document.getElementById('cr_audio').addEventListener('ended', function () {
                $("#circle_1").removeClass("voice_1");
                $("#circle_2").removeClass("voice_2");
                $("#circle_3").removeClass("voice_3");
            }, false);
        });
    },

    initReview: function() {
        var cid = this.options['course_id'];
        var is_subscribed = this.options['is_subscribed'];
        $('#grab_close_btn').click(function(){
            $('#grab_send_msg_area').hide();
        });
        $('#i_want_ask').click(function(){
            if(!is_subscribed){
                window.location.href = '/mobile/review/attention';
                return;
            }
            $('#grab_send_msg_area').show();
        });
        $('#review_ad_btn').click(function(){
            if(!is_subscribed){
                window.location.href = '/mobile/review/attention';
                return;
            }
            $('#grab_send_msg_area').show();
        });
        $('#grab_send_button').click(function () {
            var question = $('#grab_send_content').text();
            var allow_answer = $('#grab_receive_more').prop('checked');
            if (question.replace(/(^s*)|(s*$)/g, "").length == 0)
            {
                $('#tips').html('问题描述不能为空').show().delay(2000).fadeOut(100);
                return false;
            }
            if (!allow_answer)
            {
                $('#tips').html('您需要同意医生为您解答问题').show().delay(2000).fadeOut(100);
                return false;
            }
            $.ajax({
                type: "POST",
                url: "/mobile/review/add",
                data: {question:question, cid:cid},
                success: function(msg){
                    $('#grab_send_content').text('');
                    if (msg > 0) {
                        $('#grab_send_msg_area').hide();
                        $('#tips').html('发送成功').show().delay(2000).fadeOut(100);
                    } else {
                        $('#grab_send_msg_area').hide();
                        $('#tips').html('发送失败').show().delay(2000).fadeOut(100);
                    }
                }
            });
        });
    },

    init: function(options) {
        this.options = options;
        this.initSwiper();
        this.initPlay();
        this.initReview();
    }
};