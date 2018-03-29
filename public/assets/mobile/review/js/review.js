window.doc = window.document;
$doc = $(doc);

function stopPropagation(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    } else if (e.cancelBubble) {
        e.cancelBubble = true;
    }
};

//视频
var myVideo = document.getElementById("video");
var playStatus = 0;
//播放视频
$doc.on('click', '.btn-v-play', function() {
    myVideo.play();
    $('.cover').addClass('hide');
    $('.video-wrap').find('.cont').removeClass('hide');
    $('.video-wrap .inner').css({
        'position': 'fixed',
        'top': $('.header').height()
    });
    $('.header .inner').css('position', 'fixed');
});

//图片轮播
var swiper = new Swiper('.img-wrap .swiper-container', {
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
    pagination: '.swiper-pagination',
    paginationClickable: true,
    // Disable preloading of all images
    preloadImages: false,
    // Enable lazy loading
    lazyLoading: true,
    autoplay: 5000,
    loop: true
});

//tabs
$doc.on('click', '.nav-tabs a', function() {
    var $pane = $($(this).attr('href'));
    $(this).closest('li').addClass('active').siblings().removeClass('active');
    $($(this).attr('href')).addClass('active in').siblings().removeClass('active in');
    stopPropagation(window.event || arguments.callee.caller.arguments[0]);
    return false;
});

//点赞
$doc.on('click', '.btn-thumb', function() {
    var num;
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        num = parseInt($(this).find('.thumb-num').text());
        $(this).find('.thumb-num').text(num - 1);
        $.ajax({
            type: 'POST',
            url: '/mobile/cancelAReviewLike',
            data: {
                cid: cid,
            },
            success: function(data){

            },
            error: function(data){

            }
        });
    } else {
        $(this).addClass('active');
        num = parseInt($(this).find('.thumb-num').text());
        $(this).find('.thumb-num').text(num + 1);
        $.ajax({
            type: 'POST',
            url: '/mobile/giveAReviewLike',
            data: {
                cid: cid
            },
            success: function(data){

            },
            error: function(data){

            }
        });
    }
});

//分享
$doc.on('click', '.btn-share', function() {
    $('.share-wrap').show();
});
//关闭分享
$doc.on('touchstart', '.share-wrap', function() {
    $('.share-wrap').hide();
});

//底部悬浮
var scrollTopValue = 0, // 上次滚动条到顶部的距离
    scrollInterval = null; // 定时器

function scrollTest() {
    // 判断此刻到顶部的距离是否和1秒前的距离相等
    if ($doc.scrollTop() == scrollTopValue) {
        $('.footer').show();
        clearInterval(scrollInterval);
        scrollInterval = null;
    }
}

$doc.scroll(function() {
    // $('.footer').hide();
    // 未发起时，启动定时器，1秒1执行
    scrollInterval == null ? scrollInterval = setInterval("scrollTest()", 500) : '';
    scrollTopValue = $doc.scrollTop();
});

//音频
var myAudio = document.getElementById("audio");

//底部播放音频
$doc.on('click', '.btn-video', function() {
    if ($('.btn-video').hasClass('play')) {
        $(this).removeClass('play').addClass('pause');
        playStatus = 'play';
        myAudio.play();
        playStatus = 1;
        CIData.push(['actionTimeStart', 'play', {cid: cid, channel: wyeth_channel}]);
        CIData.push(['trackEvent', 'wyeth', 'click_play', 'cid', cid]);
        mediaAction(reviewType, 'review_audio_begin');
    } else {
        $(this).removeClass('pause').addClass('play');
        playStatus = 0;
        myAudio.pause();
        CIData.push(['actionTimeEnd', 'play']);
        CIData.push(['trackEvent', 'wyeth', 'click_pause', 'cid', cid]);
        mediaAction(reviewType, 'review_audio_pause');
    }
});

//监听音频播放结束
if (myAudio){
    myAudio.addEventListener('ended',function () {
        $('.btn-video').removeClass('pause').addClass('play');
        playStatus = 0;
        myAudio.pause();
        CIData.push(['actionTimeEnd', 'play']);
        CIData.push(['trackEvent', 'wyeth', 'click_pause', 'cid', cid]);
        mediaAction(reviewType, 'review_audio_pause');
    });
}

// 章节要点播放按钮
$doc.on('click', '#gist_list a[second]', function() {
    var second = this.getAttribute("second");
    if (reviewType == 1) {              //音频课程
        myAudio.currentTime = second;
        $('.btn-video').removeClass('play').addClass('pause');
        playStatus = 1;
        myAudio.play();
        mediaAction(reviewType, 'review_audio_begin');
    } else if (reviewType == 2) {      //视频课程
        myVideo.currentTime = second;
        if (myVideo.paused) {
            myVideo.play();
        }
        if (!$('.cover').hasClass('hide')) {
            $('.cover').addClass('hide');
            $('.video-wrap').find('.cont').removeClass('hide');
            $('.video-wrap .inner').css({
                'position': 'fixed',
                'top': $('.header').height()
            });
            $('.header .inner').css('position', 'fixed');
        }
    }
});
// 监听视频播放事件
myVideo.addEventListener('play',function(){
    playStatus = 1;
    CIData.push(['actionTimeStart', 'play', {cid: cid, channel: wyeth_channel}]);
    CIData.push(['trackEvent', 'wyeth', 'click_play', 'cid', cid]);
    mediaAction(reviewType, 'review_video_begin');
});
myVideo.addEventListener('pause',function(){
    playStatus = 0;
    CIData.push(['actionTimeEnd', 'play']);
    CIData.push(['trackEvent', 'wyeth', 'click_pause', 'cid', cid]);
    mediaAction(reviewType, 'review_video_pause');
});
myVideo.addEventListener('ended',function(){
    playStatus = 0;
    CIData.push(['actionTimeEnd', 'play']);
    CIData.push(['trackEvent', 'wyeth', 'click_pause', 'cid', cid]);
    mediaAction(reviewType, 'review_video_pause');
});

// 媒体播放或停止事件
function mediaAction(reviewType, type) {
    $.post( "/mobile/reviewRecord", {id: reviewInId, cid: cid, review_type: reviewType, type: type}, function (data) {
        if (data.status == 1) {
            alert(data.error_msg);
        }
    });
}
//进入页面时长统计
function timing() {
    $.post( "/mobile/reviewTimeRecord", {id: reviewInId, cid: cid, review_type: reviewType, playStatus: playStatus, type: 'review_in'}, function (data) {
        if (data.status == 1) {
            alert(data.error_msg);
        }
    });
}
setInterval("timing()", 30000);
