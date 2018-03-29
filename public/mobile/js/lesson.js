$(function(){
    var debug = true;
    var log = function(){
        if(!debug) return;
        console.log.apply(console,arguments);
    }
    util.tpl.format.star = function(a){
        var obj = arguments[arguments.length-1];
        return obj.course.lesson_type;
//        var i = new Date(obj.course.start_day+' '+obj.course.start_time);
//        var e = new Date(obj.course.start_day+' '+obj.course.end_time);
//        var t = new Date();
//        if(t<i){
//            return 'reg'
//        }else if(i<t && t<e){
//            return 'living'
//        }else{
//            return 'end'
//        }
    }
    util.tpl.format.timeLess = function(a){
        return a.substr(0, a.length-3)
    }
    var page = {
        isinit:false,
        index:1,
        pageSize : 20,
        data:{},
        init:function(opt){
            var that = this;
            if($('.lessonTop').height()!= 0){
                $('.lessonList').css('top',0);
                $('.keepcenter').css('paddingTop','3em')
                that.pagenations();
//                that.devOpen();
                that.globalBind();
                $('#pullUp').hide();
            }else{
                setTimeout(function(){
                    page.init();
                },500)
            }
        },
        globalBind:function(){
            var that = this;
            var myScroll,
                pullDownEl, pullDownOffset,
                pullUpEl, pullUpOffset,
                ajaxing = false,
                _page = this;

            pullDownEl = document.getElementById('pullDown');
            pullDownOffset = pullDownEl.offsetHeight;
            pullUpEl = document.getElementById('pullUp');
            pullUpOffset = pullUpEl.offsetHeight;

            function pullDownAction () {
                _page.index = 1;
                _page.pagenations();
                setTimeout(function () {
                    ajaxing = false;
                    myScroll.refresh();
                    pullDownEl.style.visibility = 'hidden';
                }, 500);
            }

            function pullUpAction () {
                if(page.more == false){
                    return false;
                }
                _page.index += 1;
                _page.pagenations(_page.index);
                setTimeout(function () {

                    ajaxing = false;
                    myScroll.refresh();
                    pullUpEl.style.visibility = 'hidden';
                }, 500);
            }

            setTimeout(function(){
                myScroll = that.scoller = new iScroll('lessonScroller', {
                    mouseWheel: true,
                    click: true,
                    useTransition: false,
                    hScrollbar: false,
                    vScrollbar: false,
                    hideScrollbar: true,
                    topOffset: pullDownOffset,
                    onRefresh: function () {
                        if (pullDownEl.className.match('loading')) {
                            pullDownEl.className = '';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉更新数据...';
                        } else if (pullUpEl.className.match('loading')) {
                            pullUpEl.className = '';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉获取更多...';
                        }
                    },
                    onScrollMove: function () {
                        if(ajaxing == true){
                            return false;
                        }
                        if(this.y > this.absStartY){
                            pullDownEl.style.visibility = 'visible';
                            pullUpEl.style.visibility = 'hidden';
                        }else{
                            pullUpEl.style.visibility = 'visible';
                            pullDownEl.style.visibility = 'hidden';
                        }
                        if (this.y > 5 && !pullDownEl.className.match('flip')) {
                            pullDownEl.className = 'flip';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = '松开确认更新...';
                            this.minScrollY = 0;
                        } else if (this.y < 5 && pullDownEl.className.match('flip')) {
                            pullDownEl.className = '';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉更新数据...';
                            this.minScrollY = -pullDownOffset;
                        } else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
                            pullUpEl.className = 'flip';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '松开确认更新...';
                            this.maxScrollY = this.maxScrollY;
                        } else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
                            pullUpEl.className = '';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉获取更多...';
                            this.maxScrollY = pullUpOffset;
                        }
                        $('body').focus();
                    },
                    onScrollEnd: function () {
                        if (pullDownEl.className.match('flip')) {
                            pullDownEl.className = 'loading';
                            pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';
                            ajaxing = true;
                            pullDownAction();
                        } else if (pullUpEl.className.match('flip')) {
                            pullUpEl.className = 'loading';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
                            ajaxing = true;
                            pullUpAction();
                        }
                    }
                })
            },300);
        },
        pagenations:function(){
            var that = this;
            if(that.index == 1 || that.index == 0){
                $('#thelist').html('');
            }
            var data = {
                p: that.index
            }
            ajax('/api/course',data,function(data) {
                    var date = '';
                    var data = data.data;
                    if(that.index == 1) {
                        $("#thelist").empty();
                    }
                    if (data.list.length > 0) {
                        var list = data.list;
                        var datalist = [];
                        for(var i=0;i<list.length;i++){
                            var temp;
                            if(date != list[i].course.start_day){
                                var s = list[i].course.start_day.split('-');
                                if(s[2].length == 1){
                                    s[2] = '0'+s[2];
                                }
                                temp = util.tpl.apply('lessonMonth',{
                                    month:s[1],
                                    date:'<img src='+STATIC_URL+'/mobile/img/lesson/'+s[2].substr(0,1)+'.png" alt=""/><img src="'+STATIC_URL+'/mobile/img/lesson/'+s[2].substr(1,2)+'.png" alt=""/>'
                                });
                                date = list[i].course.start_day;
                                datalist.push(temp);
                            }
                            temp = util.tpl.apply('newsBlock',list[i]);
                            datalist.push(temp);
                        }
                        $('#thelist').append(datalist.join(' '));
                        if(data.hasNextPage==1){
                            $('#pullUp').show();
                            page.more = true;
                        }else{
                            $('#pullUp').hide();
                            page.more = false;
                        }
                        that.scoller && that.scoller.refresh();
                    }
                },function(){

                },'GET'
            )
        },
        devOpen:function(){
            var that = this;
            var data = {
                "status": 1,
                "error_msg": "",
                "data": {
                    "hasNextPage": 1,
                    "list": [
                        {
                            "course": {
                                "cid":1,
                                "title": "关于“咳嗽”的故事0",
                                "start_day": "2015-11-14",
                                "start_time": "20:40:00",
                                "end_time": "21:00:00",
                                "stage": "3岁以上",
                                "img": "http://7u2omr.com1.z0.glb.clouddn.com/o_1a3sp6vif12he1i91jr11qemdo17.jpg"
                            },
                            "teacher": {
                                "name": "侯尚文0",
                                "hospital": "北京和睦家医院0",
                                "position": "主治医师"
                            }
                        },
                        {
                            "course": {
                                "cid":1,
                                "title": "关于“咳嗽”的故事1",
                                "start_day": "2015-11-14",
                                "start_time": "20:40:00",
                                "end_time": "21:00:00",
                                "stage": "3岁以上",
                                "img": "http://7u2omr.com1.z0.glb.clouddn.com/o_1a3sp6vif12he1i91jr11qemdo17.jpg"
                            },
                            "teacher": {
                                "name": "侯尚文1",
                                "hospital": "北京和睦家医院1",
                                "position": "主治医师"
                            }
                        },
                        {
                            "course": {
                                "cid":1,
                                "title": "关于“咳嗽”的故事2",
                                "start_day": "2015-11-13",
                                "start_time": "20:40:00",
                                "end_time": "21:00:00",
                                "stage": "3岁以上",
                                "img": "http://7u2omr.com1.z0.glb.clouddn.com/o_1a3sp6vif12he1i91jr11qemdo17.jpg"
                            },
                            "teacher": {
                                "name": "侯尚文2",
                                "hospital": "北京和睦家医院2",
                                "position": "主治医师"
                            }
                        }
                    ]
                }
            }
            var date = '';
            var data = data.data;
            if(that.index == 1) {
                $("#thelist").empty();
            }
            if (data.list.length > 0) {
                var list = data.list;
                var datalist = [];
                for(var i=0;i<list.length;i++){
                    var temp;
                    if(date != list[i].course.start_day){
                        var s = list[i].course.start_day.split('-');
                        if(s[2].length == 1){
                            s[2] = '0'+s[2];
                        }
                        temp = util.tpl.apply('lessonMonth',{
                            month:s[1],
                            date:'<img src='+STATIC_URL+'"/mobile/img/lesson/'+s[2].substr(0,1)+'.png" alt=""/><img src="'+STATIC_URL+'img/lesson/'+s[2].substr(1,2)+'.png" alt=""/>'
                        });
                        date = list[i].course.start_day;
                        datalist.push(temp);
                    }
                    temp = util.tpl.apply('newsBlock',list[i]);
                    datalist.push(temp);
                }
                $('#thelist').append(datalist.join(' '));
                if(data.hasNextPage==1){
                    $('#pullUp').show();
                    page.more = true;
                }else{
                    $('#pullUp').hide();
                    page.more = false;
                }
                that.scoller && that.scoller.refresh();
            }
        }
    }

    page.init();


})