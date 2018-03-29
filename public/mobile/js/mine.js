jQuery(document).ready(function($) {
var curPage = 1;
var perPage = 5;

    function jumpToPath(path) {
        window.location.href = location.protocol + '//' + location.host + path
    }

    function page(){
    $('#thelist').dropload({
        scrollArea : window,
        loadDownFn : function(me){
            var self = this;
            $.ajax({
                type: 'GET',
                url: '/mobile/getMinePage',
                data:{
                    curPage :curPage,
                    perPage : perPage
                },
                dataType: 'json',
                success: function(data){
                    data = data.data;
                    if(data.hasNextPage == 0){
                        var html = '';
                        $.each(data.list, function(index, mineCourse) {
                            if(mineCourse.img.length > 0){
                                var imgStr = 'src="'+mineCourse.img+'"';
                            }else{
                                var imgStr = ' ';
                            }
                            if(mineCourse.status == 1){
                                //报名中 reg
                                var linksControl = '/mobile/reg';
                                var btnType = 'regend';

                            } else if (mineCourse.status == 2){
                                //直播中
                                //var linksControl = '/mobile/living';
                                //var btnType = 'regplaying';
                                if(mineCourse.cid==381)
                                {
                                   var linksControl = 'http://mudu.tv/?c=activity&a=live&id=39250';
                                   var btnType = 'regplaying';
                                }
                                else
                                {
                                    var linksControl = '/mobile/living';
                                    var btnType = 'regplaying';
                                }


                            } else if (mineCourse.status == 3) {
                                //已结束
                                var linksControl = '/mobile/end';
                                var btnType = 'regreview';
                            }
                            /*
                            else{
                                                                if(mineCourse.cid==381)
                                {
                                   var linksControl = 'http://www.baidu.com';
                                   var btnType = 'regend';
                                }
                                else
                                {
                                    var linksControl = '/mobile/reg';
                                    var btnType = 'regend';
                                }
                            }
                            */
                            html += ('<div class="lessonLineTypeOne" lid="'+mineCourse.id+'" onclick="jumpToPath('+mineCourse.cid+');console.log('+mineCourse.cid+')">'+
                            '<div class="newsCover" status="'+mineCourse.status+'">'+
                            '<img '+imgStr+' alt="" class="newsCover" />'+
                            '</div>'+
                            '<div class="newsContent">'+
                            '<h4>'+mineCourse.title+'</h4>'+
                            '<p><i class="dateIcon"></i>'+mineCourse.start_day+'  '+mineCourse.start_time+'-'+mineCourse.end_time+'</p>'+
                            '<p class="doctor">'+
                            '<span>'+mineCourse.teacher_name+' '+mineCourse.teacher_position+'</span>'+
                            '<span>'+mineCourse.teacher_hospital+'</span>'+
                            '</p>'+
                            '<p><i class="likeIcon"></i>'+mineCourse.hot+'</p>'+
                            '<div class="functionBTN '+btnType+'"></div>'+
                            '</div>'+
                            '</div>'+
                            '<script>' +
                            'function jumpToPath (cid) { window.location.href = window.location.protocol + "//" + window.location.host + "/mobile/reg?cid=" + cid;  }' +
                            '</script>');
                            

                            /**当前微直播的课程样式**/
                            // html += ('<div class="lessonLineTypeOne" lid="'+mineCourse.id+'">'+
                            // '<a href="'+linksControl+'?cid='+mineCourse.cid+'" class="newsCover" status="'+mineCourse.status+'">'+
                            // '<img '+imgStr+' alt="" class="newsCover" />'+
                            // '</a>'+
                            // '<div class="newsContent">'+
                            // '<h4><a href="'+linksControl+'?cid='+mineCourse.cid+'">'+mineCourse.title+'</a></h4>'+
                            // '<p><i class="dateIcon"></i>'+mineCourse.start_day+'  '+mineCourse.start_time+'-'+mineCourse.end_time+'</p>'+
                            // '<p class="doctor">'+
                            // '<span>'+mineCourse.teacher_name+' '+mineCourse.teacher_position+'</span>'+
                            // '<span>'+mineCourse.teacher_hospital+'</span>'+
                            // '</p>'+
                            // '<p><i class="likeIcon"></i>'+mineCourse.hot+'</p>'+
                            // '<a href="'+linksControl+'?cid='+mineCourse.cid+'" class="functionBTN '+btnType+'"></a>'+
                            // '</div>'+
                            // '</div>');

                        });
                        $('#courseClear').before(html);
                        curPage++;
                        me.resetload();
                    }else{
                        $('.dropload-down').hide();
                    }
                    //me.resetload();
                },
                error: function(xhr, type){
                    //alert('Ajax error!');
                    // 即使加载出错，也得重置
                    me.resetload();
                }
            });
        }
    });
}

function getLessonTime(){
    var that = this;
    $.ajax({
        type: 'GET',
        url: '/api/user/course',
        data: {
            uid: UID
        },
        dataType: 'json',
        success: function (data) {
            var data = data.data;
            if(data.listen_num != 0){
                var n = data.listen_num+'';
                var s = n.split('');
                var times = '';
                for(var i = 0,max=s.length;i<max;i++){

                    times += '<img src="'+STATIC_URL+'/mobile/images/bany/'+s[i]+'.png" alt=""/>'
                }
                times += '<img src="'+STATIC_URL+'/mobile/images/bany/ci.png" alt=""/>'
                $('.cishu').html(times);
            }

            // if(data.rank != 0){
            //     var n = data.rank+'';
            //     var s = n.split('');
            //     var times = '';
            //     times += '<img src="'+STATIC_URL+'/mobile/images/bany/di.png" alt=""/>'
            //     for(var i = 0,max=s.length;i<max;i++){
            //         times += '<img src="'+STATIC_URL+'/mobile/images/bany/'+s[i]+'.png" alt=""/>'
            //     }
            //     times += '<img src="'+STATIC_URL+'/mobile/images/bany/ming.png" alt=""/>'
            //     $('.paiming').html(times);
            // }
            if(data.listen_time != 0){
                var hours = parseInt(data.listen_time/60/60)+'';
                var mins = parseInt((data.listen_time-hours*60*60)/60)+'';
                var s = hours.split('');
                var ss = mins.split('');
                var times = '';
                for(var i = 0,max=s.length;i<max;i++){
                    times += '<img src="'+STATIC_URL+'/mobile/images/bany/'+s[i]+'.png" alt=""/>'
                }
                times += '<img src="'+STATIC_URL+'/mobile/images/bany/shi.png" alt=""/>'
                for(var i = 0,max=ss.length;i<max;i++){
                    times += '<img src="'+STATIC_URL+'/mobile/images/bany/'+ss[i]+'.png" alt=""/>'
                }
                times += '<img src="'+STATIC_URL+'/mobile/images/bany/fen.png" alt=""/>'
                $('.shichang').html(times);
            }
        }
    })
    $.ajax({
        type: 'GET',
        url: '/wyeth/user/getUserInfo',
        dataType: 'json',
        success: function (data) {
            var result = data.data;
            var n = result.mq + '';
            var s = n.split('');
            var mqs = '';
            for (var i = 0, max = s.length; i < max; i++) {
                mqs += '<img src="'+STATIC_URL+'/mobile/images/bany/'+s[i]+'.png" alt=""/>'
            }
            $('.paiming').html(mqs);
        }
    })
}
function getToken() {
    $.getJSON('/token', function (data) {
        if ('token' in data) {
            token = data.token;
            $.ajaxSetup({
                beforeSend: function (xhr) {
                    if (!token) {
                        console.log('token empty before ajax send');
                        return false;
                    }
                    xhr.setRequestHeader('Authorization', 'bearer ' + token);
                }
            });
            getLessonTime();
        }
    });
}

    page();
    getToken();
})
