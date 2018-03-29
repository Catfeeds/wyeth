<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$app_config['title']}}</title>
    <base href="//cdn-s1.oneitfarm.com/web{{$test}}/">
    <link rel="dns-prefetch" href="//wyeth-uploadsites.nibaguai.com">
    <link rel="dns-prefetch" href="//cdn-s1.oneitfarm.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no, email=no">
    {{--<script type="text/javascript" src="{{$su}}/mobile/v2/js/pxrem.js"></script>--}}
    <script type="text/javascript" src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>

    <style type="text/css">
        .home-container{
            display: flex;display: -webkit-flex;
            overflow-x: hidden;
            flex-direction: column;-webkit-flex-direction: column;
            justify-content: flex-start;
            -webkit-justify-content: flex-start;
            align-items: stretch;-webkit-align-items: stretch;
            background-color: #f4f4f4;
            font-family: BlinkMacSystemFont, 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        .static-container{
            background-color: #f4f4f4;
            -webkit-overflow-scrolling : touch;

        }
        .ohs-searchbar-tab{
            display: flex;display: -webkit-flex;
            position: absolute;
            top: 0px;
            left: 0px;
            right:0px;
            flex-direction: row;-webkit-flex-direction: row;
            align-items: center;-webkit-align-items: center;
            height: {{rpx750(78)}};
            padding-left: {{rpx750(16)}};
            padding-right: {{rpx750(16)}};
            background-color: #efeff4;
        }
        .ohs-searchbar-tab-inner{
            display: flex;display: -webkit-flex;
            flex: 1;-webkit-flex-flex: 1;-webkit-flex: 1;
            height: {{rpx750(56)}};
            background-color: white;
            flex-direction: row;-webkit-flex-direction: row;
            padding-left: {{rpx750(35)}};
            border-radius: {{rpx750(8)}};
            border-width: {{rpx750(1)}};
            border-style: solid;
            border-color: #ffca25;
            align-items: center;-webkit-align-items: center;
        }
        .ohs-searchbar-tab-icon{
            width: {{rpx750(30)}};
            height: {{rpx750(30)}};
        }
        .ohs-searchbar-tab-input{
            color: #999999;
            margin: {{rpx750(0)}};
            margin-left: {{rpx750(11)}};
            font-size: {{rpx750(28)}};
        }
        .slider-container{
            display: flex;display: -webkit-flex;
            position: relative;
            flex-direction: column;-webkit-flex-direction: column;
        }
        .slider-inner{
            display: flex;display: -webkit-flex;
        }
        .slider-item{
            position: absolute;
            top: 0px;
            left: 0px;
        }
        .slider-item-cover{
            width: {{rpx750(750)}};
            height: {{rpx750(272)}};
            background-size: cover;
        }
        .indicator-container{
            display: flex;display: -webkit-flex;
            position: absolute;
            bottom: 0px;
            width: {{rpx750(750)}};
            justify-content: center;
            -webkit-justify-content: center;
            align-items: center;-webkit-align-items: center;
        }
        .indicator-inner{
            align-items: center;-webkit-align-items: center;
            display: flex;display: -webkit-flex;
            flex-direction: row;-webkit-flex-direction: row;
            justify-content: space-between;
            -webkit-justify-content: space-between;
            align-items: center;-webkit-align-items: center;
            height: {{rpx750(31)}};
            width: {{rpx750(300)}};
        }
        .indicator-pot{
            width: {{rpx750(20)}};
            height: {{rpx750(24)}};
        }
        .tags-container{
            background-color: white;
            position: relative;
        }
        .tags-shadow{
            position: absolute;
            top:0px;
            width: {{rpx750(750)}};
            height: {{rpx750(20)}};
            background-image: linear-gradient(rgba(0, 0, 0, 0.0392157), rgba(255, 255, 255, 0));
        }
        .tags{
            display: flex;display: -webkit-flex;
            flex-direction: row;-webkit-flex-direction: row;
            align-items: center;-webkit-align-items: center;
        }
        .tag-item{
            flex: 1;-webkit-flex-flex: 1;-webkit-flex: 1;
            display: flex;display: -webkit-flex;
            align-items: center;-webkit-align-items: center;
            flex-direction: column;-webkit-flex-direction: column;
            justify-content: center;
            -webkit-justify-content: center;
        }
        .tag-icon1{
            width: {{rpx750(80)}};
            height: {{rpx750(80)}};
        }
        .tag-text1{
            font-size: {{rpx750(26)}};
            margin: 0px;
            margin-top: {{rpx750(13)}};
            padding: 0px;
            color: #464646;
        }
        .hot-cell{
            display: flex;display: -webkit-flex;
            height: {{rpx750(443)}};
            flex-direction: row;-webkit-flex-direction: row;
            align-items: stretch;-webkit-align-items: stretch;
            background-image: url("//wyeth-course.nibaguai.com/wyeth/image/bghuang.png");
            background-size: cover;
        }
        .teacher-activity{
            display: flex;display: -webkit-flex;
            width: {{rpx750(300)}};
            flex-direction: column;-webkit-flex-direction: column;
        }
        .teacher-container{
            display: flex;display: -webkit-flex;
            flex: 1;-webkit-flex-flex: 1;-webkit-flex: 1;
            border-width: 0px;
            border-style: solid;
            border-bottom-width: {{rpx750(1)}};
            border-color: #eeeeee;
        }
        .img-teacher-title{
            margin: 0px;
            width: {{rpx750(155)}};
            height: {{rpx750(32)}};
            margin-top: {{rpx750(18)}};
            margin-left: {{rpx750(18)}};
            position: absolute;
        }
        .img-teacher-cover{
            width: {{rpx750(300)}};
            height: {{rpx750(221)}};
        }
        .activity-container{
            display: flex;display: -webkit-flex;
            flex: 1;-webkit-flex-flex: 1;-webkit-flex: 1;
        }
        .theme-container{
            border-width: 0px;
            border-style: solid;
            border-left-width: {{rpx750(1)}};
            border-color: #eeeeee;
            flex: 1;-webkit-flex-flex: 1;-webkit-flex: 1;
            display: flex;display: -webkit-flex;
            flex-direction: column;-webkit-flex-direction: column;
        }
        .tag-icon2{
            width: {{rpx750(80)}};
            height: {{rpx750(80)}};
        }
        .tag-text2{
            font-size: {{rpx750(26)}};
            margin: 0px;
            margin-top: {{rpx750(18)}};
            padding: 0px;
            color: #464646;
        }
        .slider-item-cover2{
            width: {{rpx750(750)}};
            height: {{rpx750(200)}};
            background-size: cover;
        }
        .course-container{
            background-color: white;
            display: flex;display: -webkit-flex;
            align-items: stretch;-webkit-align-items: stretch;
            flex-direction: column;-webkit-flex-direction: column;
        }
        .header-panel{
            background-image: url("//wyeth-course.nibaguai.com/wyeth/image/header_bg.png");
            padding-left: {{rpx750(12)}};
            padding-right: {{rpx750(34)}};
            height: {{rpx750(86)}};
            display: flex;display: -webkit-flex;
            flex-direction: row;-webkit-flex-direction: row;
            justify-content: space-between;
            -webkit-justify-content: space-between;
            align-items: center;-webkit-align-items: center;
        }
        .header-title{
            display: flex;display: -webkit-flex;
            flex-direction: row;-webkit-flex-direction: row;
            align-items: center;-webkit-align-items: center;
        }
        .header-title-txt{
            color: #af730c;
            font-size: {{rpx750(29)}};
            margin: 0px;
            margin-left: {{rpx750(10)}};
        }
        .header-more{
            display: flex;display: -webkit-flex;
            flex-direction: row;-webkit-flex-direction: row;
            align-items: center;-webkit-align-items: center;
        }
        .header-more-txt{
            color: #666666;
            font-size: {{rpx750(25)}};
            margin: 0px;
            margin-right: {{rpx750(27)}};
        }
        .cells-container{
            display: flex;display: -webkit-flex;
            flex-direction: row;-webkit-flex-direction: row;
            align-items: stretch;-webkit-align-items: stretch;
            padding-top: {{rpx750(11)}};
            padding-bottom: {{rpx750(11)}};
            padding-left: {{rpx750(30)}};
            padding-right: {{rpx750(30)}};
            justify-content:space-between;
            -webkit-justify-content: space-between;
        }
        .cell-item{
            display: flex;display: -webkit-flex;
            overflow: hidden;
            flex-direction: column;-webkit-flex-direction: column;
            justify-content: space-between;
            -webkit-justify-content: space-between;
        }
        .course-div1{

        }
        .course-div2{

        }
        .course-cover{
            position: relative;
            height: {{rpx750(220)}};
            width: {{rpx750(220)}};
        }
        .course-cover-img{
            background-color: #e6e6e6;
            width: {{rpx750(218)}};
            border-width: {{rpx750(1)}};
            border-style: solid;
            border-color: #f7f4f2;
            height: {{rpx750(218)}};
        }
        .course-shadow{
            position: absolute;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: {{rpx750(100)}};
            background-image: linear-gradient(to top, rgba(3, 3, 3, 0.55), rgba(255, 255, 255, 0));
        }
        .course-hot{
            position: absolute;
            left: {{rpx750(11)}};
            bottom: {{rpx750(7)}};
            display: flex;display: -webkit-flex;
            align-items: center;-webkit-align-items: center;
            flex-direction: row;-webkit-flex-direction: row;
        }
        .course-video-tag{
            position: absolute;
            width: {{rpx750(34)}};
            height: {{rpx750(34)}};
            right: {{rpx750(9)}};
            top: {{rpx750(9)}};
        }
        .course-hot-img{
            width: {{rpx750(26)}};
            height: {{rpx750(26)}};
        }
        .course-hot-txt{
            color: white;
            font-size: {{rpx750(24)}};
            margin: 0px;
            text-shadow: #464646 {{rpx750(2)}} {{rpx750(2)}} {{rpx750(2)}};
            margin-left: {{rpx750(13)}};
        }
        .course-title{
            display: -webkit-box;
            -webkit-box-orient: vertical;

            color: #333333;
            margin: 0px;
            margin-top: {{rpx750(17)}};
            width: {{rpx750(220)}};
            font-size: {{rpx750(26)}};
            overflow: hidden; text-overflow: ellipsis; -webkit-line-clamp: 2;
        }
        .course-teacher{
            display: flex;display: -webkit-flex;
            flex-direction: row;-webkit-flex-direction: row;
            align-items: center;-webkit-align-items: center;
            margin-top: {{rpx750(18)}};
        }
        .teacher-name{
            display: -webkit-flex;
            -webkit-flex-orient: vertical;
            color: #666666;
            margin: 0px;
            max-width: {{rpx750(85)}};
            overflow: hidden;text-overflow: ellipsis; white-space: nowrap;
            font-size: {{rpx750(20)}};
        }
        .teacher-position{
            display: -webkit-flex;
            -webkit-flex-orient: vertical;
            max-width: {{rpx750(120)}};
            color: #999999;
            margin: 0px;
            font-size: {{rpx750(20)}};
            margin-left: {{rpx750(12)}};
            overflow: hidden;text-overflow: ellipsis; white-space: nowrap;
        }
        .course-tag-container{
            display: flex;display: -webkit-flex;
            margin-top: {{rpx750(10)}};
            width: {{rpx750(200)}};
            flex-direction: row;-webkit-flex-direction: row;
            align-items: center;-webkit-align-items: center;
        }
        .tag-img{
            width: {{rpx750(26)}};
            height: {{rpx750(28)}};
        }
        .tag-txt{
            color: #999999;
            display: flex;display: -webkit-flex;
            font-size: {{rpx750(20)}};
            padding-left: {{rpx750(14)}};
            padding-right: {{rpx750(14)}};
            height: {{rpx750(48)}};
            border-width: {{rpx750(1)}};
            border-color: #eeeeee;
            border-style: solid;
            border-radius: {{rpx750(4)}};
            background-color: #fafafd;
            align-items: center;-webkit-align-items: center;
            justify-content: center;
            -webkit-justify-content: center;
            margin: 0px;
            margin-left: {{rpx750(16)}};
            overflow: hidden; text-overflow: ellipsis; -webkit-line-clamp: 1;white-space: nowrap;
        }
        .more-course{
            height: {{rpx750(100)}};
            border-width: 0px;
            border-top-width: {{rpx750(2)}};
            border-style: solid;
            border-color: #eeeeee;
            background-color: white;
            display: flex;display: -webkit-flex;
            flex-direction: row;-webkit-flex-direction: row;
            justify-content: center;
            -webkit-justify-content: center;
            align-items: center;-webkit-align-items: center;
        }
        .more-text{
            padding: {{rpx750(15)}};
            padding-left: {{rpx750(30)}};
            padding-right: {{rpx750(30)}};
            margin: 0px;
            color: #90abda ;
            font-size: {{rpx750(26)}};
        }
        .declaration-txt{
            color: #666666;
            margin: 0px;
            padding: {{rpx750(30)}};
            font-size: {{rpx750(24)}};
        }
        .tabbar-shadow{
            position: fixed;
            bottom: {{rpx750(100)}};
            height: {{rpx750(20)}};
            left: 0px;
            right: 0px;
            background-image: linear-gradient(to top, rgba(0, 0, 0, 0.08), rgba(255, 255, 255, 0));
        }
        .tabbar-static{
            display: flex;display: -webkit-flex;
            flex-direction: row;-webkit-flex-direction: row;
            align-items: center;-webkit-align-items: center;
            justify-content: space-around;
            -webkit-justify-content: space-around;
            position: fixed;
            background-color: white;
            height: {{rpx750(100)}};
            bottom: 0px;
            left: 0px;
            right: 0px;

        }
        .tabbar-item-static{
            display: flex;display: -webkit-flex;
            background-color: #e6e6e6;
            bottom: 0px;
            width: {{rpx750(62)}};
            height: {{rpx750(62)}};
            left: 0px;
            right: 0px;
        }
        .tabbar-center-item{
            justify-content: center;
            -webkit-justify-content: center;
            width: {{rpx750(104)}};
            height: {{rpx750(104)}};
            border-radius: {{rpx750(65)}};
            border-width: {{rpx750(13)}};
            border-color: white;
            border-style: solid;
            background-color: #e6e6e6;
            position: absolute;
            bottom: 0px;
        }
        .toast-loading{
            background-color: rgba(0, 0, 0, 0.4);
            display: flex;display: -webkit-flex;
            position: fixed;
            left: 0px;
            top: 0px;
            right: 0px;
            bottom: 0px;
            align-items: center;-webkit-align-items: center;
            justify-content: center;
            -webkit-justify-content: center;
            z-index: 100;
        }
        .toast-loading-container{
            display: flex;display: -webkit-flex;
            top: {{rpx750(-100)}};
            justify-content: flex-start;
            -webkit-justify-content: flex-start;
            align-items: center;-webkit-align-items: center;
            width: {{rpx750(240)}};
            height: {{rpx750(240)}};
            background-image: linear-gradient(to bottom, RGBA(246,245,242,1), RGBA(226,224,218,1));
            flex-direction: column;-webkit-flex-direction: column;
            justify-content: space-around;
            -webkit-justify-content: space-around;
            border-radius: {{rpx750(10)}};
        }
        .toast-loading-img{
            width: {{rpx750(52)}};
            height: {{rpx750(52)}};
            bottom: {{rpx750(-30)}};
            position: relative;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .toast-loading-text{
            margin: 0px;
            color: #ad7a00;
            font-size: {{rpx750(26)}};
            align-content: center;-webkit-align-content:center;
        }


    </style>
    <script type="text/javascript">
        function rpx(x) {
            var len = x / 750 * window.innerWidth

            return len+'px'
        }

        function autoCss() {
            var st=document.querySelector('style')
            var text=st.innerText
            var p=/[0-9]\.?[0-9]*px/g
            st.innerText=text.replace(p,function (s) {
                s=s.slice(0,s.length-2)
                var x=parseFloat(s)
                return rpx(x)
            })
        }
//        autoCss()
    </script>
    <script type="text/javascript">
        var slider=function (el,options) {
            if(!el) return
            this.$el=el
            this.pageX=0
            this.pageY=0
            this.move=0
            this.minRange=80
            this.width = this.$el.clientWidth
            this.height = this.$el.clientHeight
            this.slides=el.children
            this.interval=options&&options.interval||3000
            this.currentIndex=options&&options.currentIndex||0
            this.duration=options&&options.duration||300

            this.autoPlay=options?(options.autoPlay==false?false:true):true
            this.oldid=0

            this.changing=false
            var self=this

            if(this.slides.length<2){
                return
            }

            this.changeCallback=function () {
                if(!this.changing){
                    var call=options.onChange
                    if (call){
                        call(self.currentIndex,self.oldid)
                    }
                }
            }
            this.translate=function (index, dist, speed,callback) {

                var slide =  this.slides[index];
                var style = slide && slide.style;

                if (!style) return;
                style.webkitTransitionDuration =
                    style.MozTransitionDuration =
                        style.msTransitionDuration =
                            style.OTransitionDuration =
                                style.transitionDuration = speed + 'ms';

                style.webkitTransform = 'translate(' + dist + 'px,0)' + 'translateZ(0)';
                style.msTransform =
                    style.MozTransform =
                        style.OTransform = 'translateX(' + dist + 'px)';



                if(callback){
                    var transitionEnd=(function(){
                        var transEndEventNames = {
                            WebkitTransition : 'webkitTransitionEnd',
                            MozTransition    : 'transitionend',
                            OTransition      : 'oTransitionEnd otransitionend',
                            transition       : 'transitionend'
                        }
                        for(var name in transEndEventNames){
                            if(typeof style[name] === "string"){
                                return transEndEventNames[name]
                            }
                        }
                    })();

                    var fn=function(callback){

                    }
                    fn(callback)

                    var f=function () {
                        callback()
                        slide.removeEventListener(transitionEnd, f,false)
                    }
                    slide.addEventListener(transitionEnd, f,false)
                }

            }
            this.circle=function (index) {
                return (self.slides.length+index%self.slides.length)%self.slides.length
            }
            this.reset=function () {
                var list=self.$el.children
                for(var i=0;i<list.length;i++){
                    var el=list[i]
                    self.translate(i,(i>self.currentIndex?self.width:(i<self.currentIndex?-self.width:0)),0)
                }
            }
            this.onTouchStart=function (event) {
                event.stopPropagation()
                var touches = event.touches[0]
                //记录落点
                self.pageX = touches.pageX
                self.pageY = touches.pageY
                self.stopPlay()

            }
            this.onTouchMove=function (event) {
                self.stopPlay()
                event.preventDefault()
                event.stopPropagation()
                var touches = event.touches[0]
                var X = touches.pageX - self.pageX
                var Y = touches.pageY - self.pageY
                var current = self.slides[self.currentIndex]
                self.move=X
                self.translate(self.currentIndex,X,0)
                if(self.move>0){
                    self.translate(self.circle(self.currentIndex-1),X-self.width,0)
                }else{
                    self.translate(self.circle(self.currentIndex+1),X+self.width,0)
                }
            }
            this.prev=function () {
                self.oldid=self.currentIndex
                var a=self.currentIndex-2
                self.translate(self.currentIndex,self.width,self.duration,function () {
//                    self.translate(self.circle(a),-self.width,0)
                })
                self.translate(self.circle(self.currentIndex-1),0,self.duration)
                self.translate(self.circle(self.currentIndex-2),-self.width,0)
                self.currentIndex=self.circle(self.currentIndex-1)
                self.changeCallback()
            }
            this.next=function (flag) {

                self.oldid=self.currentIndex
                var a=self.currentIndex+2

                self.translate(self.currentIndex,-self.width,self.duration,function () {
//                    if(flag==1){
//                        self.translate(self.circle(a),self.width,0)
//                    }
                })
                self.translate(self.circle(self.currentIndex+1),0,self.duration)
                self.translate(self.circle(a),self.width,0)
                self.currentIndex=self.circle(self.currentIndex+1)

                self.changeCallback()
            }
            this.onTouchEnd=function (event) {
                if(self.move==0){
                    return
                }else{
                    event.stopPropagation()
                }

                if(self.move>self.minRange){
                    self.prev()
                }else if(self.move<-self.minRange){
                    self.next()
                }else{
                    self.translate(self.currentIndex,0,self.duration)
                    if(self.move>0){
                        self.translate(self.circle(self.currentIndex-1),-self.width,self.duration)
                    }else{
                        self.translate(self.circle(self.currentIndex+1),self.width,self.duration)
                    }

                }
                self.move=0
                self.startPlay()
            }

            this.startPlay=function () {
                if(self.autoPlay){
                    self.timer=window.setInterval(function () {
                        self.next(1)
                    },self.interval);
                }

            }
            this.stopPlay=function () {
                if(self.autoPlay){
                    window.clearInterval(self.timer)
                }

            }
            this.$el.addEventListener('touchstart',this.onTouchStart)
            this.$el.addEventListener('touchmove',this.onTouchMove)
            this.$el.addEventListener('touchend',this.onTouchEnd)
            this.$el.addEventListener('touchcancel',this.onTouchEnd)
            self.reset()
            this.startPlay()
        }
        var indicator=function (el,options) {
            if(!el) return
            this.$el=el
            this.pot=el.children
            this.index=0
            if(options){
                if(options.index<el.children.length&&options.index>=0){
                    this.index= options.index
                }
            }

            this.default2selected=options&&options.toSelected
            this.selected2default=options&&options.toDefault

            this.update=function(newid,oldid){
                if(this.selected2default){
                    this.selected2default(this.pot[oldid])
                }
                if(this.default2selected){
                    this.default2selected(this.pot[newid])
                }
            }

            this.reset=function () {
                for(var i=0;i<el.children.length;i++){
                    if(this.index==i){
                        this.default2selected(this.pot[i])
                    }else{
                        this.selected2default(this.pot[i])
                    }

                }
            }
            this.reset()
        }

    </script>

    <script tag="1">
        window.wyeth_host = "{{config('app.url')}}"
        window.develop=0
        window.shareDebug=false
        console.log=function () {}
        window.CIData = [];
        window.CIData.push(["setAppKey", "{{ config('oneitfarm.appkey') }}"]);
        window.CIData.push(["setVersion", "v1.0.0"])


        @if(isset($uid))
            window.CIData.push(["setUserId", {{$uid}}]);
        @else
        @endif
        @if(isset($user_channel))
            window.CIData.push(["setChannel", "{{ $user_channel }}"]);
        @else
        @endif
        @if(isset($user_properties))
            var user_properties = <?php echo json_encode($user_properties, JSON_UNESCAPED_UNICODE); ?>;
            window.CIData.push(["setUserProperties", user_properties]);
        @else
        @endif

            window.app_config = <?php echo json_encode($app_config); ?>;


        @if(isset($hotClass))
            @foreach($hotClass as $item)
                CIData.push(['recActionExpose',{{$item['id']}}])
            @endforeach
        @endif
        @if(isset($newClass))
            @foreach($newClass as $item)
            CIData.push(['recActionExpose',{{$item['id']}}])
            @endforeach
        @endif

        @if(isset($recomClass))
            @foreach($recomClass as $item)
                CIData.push(['recActionExpose',{{$item['id']}}])
            @endforeach
        @endif

    </script>
    <script type="text/javascript">
        window.showLoading=function (isShow) {

            var el=document.querySelector('#loading-container')
            if(el&&isShow){
                el.style.display=''
            }else{
                el.innerHTML=''
            }
        }

        var onStageClick=function (index, id) {
            window.CIData.push(['trackEvent', 'wyeth', 'home_stage', 'stage',id]);


            if(window.vm){
                window.vm.$router.push({name: 'all', params: {stage:index,stage_id:id ,nokeep:true}})
            }else{
                if(!window.firstClick){
                    window.showLoading(true)
                    window.firstClick=function () {
                        window.vm.$router.push({name: 'all', params: {stage:index,stage_id:id,nokeep:true}})
                    }
                }
            }


        }

        var onItemClick=function(link, type, subject, flag){

            window.CIData.push(['trackEvent', 'wyeth', 'home_activity_'+flag, type, link])

            if (type == 0) {

                if(window.vm){
                    window.vm.$router.push('/courseSeries/' + link)
                }else {
                    if(!window.firstClick){
                        window.showLoading(true)
                        window.firstClick=function () {

                            window.vm.$router.push('/courseSeries/' + link)
                        }
                    }
                }
            } else {
                console.log('onActivityClick',link)

                window.location.href=link
            }

        }

        var onActivityClick=function (link,subject) {
            console.log('onActivityClick',link)
            window.CIData.push(['trackEvent', 'wyeth', 'home_activity' , 'title', subject])

            window.location.href=link
        }

        var onTagClick=function (name,id) {
            window.CIData.push(['trackEvent', 'wyeth', 'home_tag', 'tid', id])


            if(window.vm){
                console.log('-------------onTagClick')
                window.vm.$router.push({name: 'all', params: {tag:{name:name,id:id},nokeep:true}})
            }else{
                console.log('-------------onTagClick2')
                if(!window.firstClick){
                    window.showLoading(true)
                    window.firstClick=function () {
                        window.vm.$router.push({name: 'all', params: {tag:{name:name,id:id},nokeep:true}})
                    }
                }
            }
        }

        var onCourseClick=function (id , status,review_type,type) {

            switch (type){
                case 1:
                    window.CIData.push(['trackEvent', 'wyeth', 'home_course', 'hot',id])
                    break;
                case 2:
                    window.CIData.push(['trackEvent', 'wyeth', 'home_course', 'new',id])
                    break;
                case 3:
                    window.CIData.push(['trackEvent', 'wyeth', 'home_course', 'recom',id])
                    break;
                case 4:
                    window.CIData.push(['trackEvent', 'wyeth', 'home_course', 'noparam',id])
                    break;
            }


            if(window.vm){
                console.log('-------------onCourseClick')
                console.log('review_type',review_type)
                switch (review_type){
                    case 0:
                        window.vm.$router.push('/courseNew/'+id)
                        break;
                    case 1:
                        window.vm.$router.push('/courseAudio/'+id)
                        break;
                    case 2:
                        window.vm.$router.push('/courseVideo/'+id)
                        break;
                    default:
                        window.vm.$router.push('/courseNew/'+id)
                        break;
                }
            }else{
                if(!window.firstClick){
                    console.log('-------------onCourseClick')
                    window.showLoading(true)
                    window.firstClick=function () {
                        console.log('review_type',review_type)
                        switch (review_type){
                            case 0:
                                window.vm.$router.push('/courseNew/'+id)
                                break;
                            case 1:
                                window.vm.$router.push('/courseAudio/'+id)
                                break;
                            case 2:
                                window.vm.$router.push('/courseVideo/'+id)
                                break;
                            default:
                                window.vm.$router.push('/courseNew/'+id)
                                break;
                        }
                    }
                }
            }

        }

        var onMoreClick=function (k) {

            switch (k){
                case 1:
                    window.CIData.push(['trackEvent', 'wyeth', 'home_more', 'type', 'hot'])
                    break;
                case 2:
                    window.CIData.push(['trackEvent', 'wyeth', 'home_more', 'type', 'new'])
                    break;
                case 3:
                    window.CIData.push(['trackEvent', 'wyeth', 'home_more', 'type', 'recom'])
                    break;
                case 4:
                    window.CIData.push(['trackEvent', 'wyeth', 'home_more', 'type', 'noparam'])
                    break;
            }

            if(window.vm){
                switch (k){
                    case 1:
                        window.vm.$router.push({ name: 'all', params: { type: 2 ,nokeep:true} })
                        break;
                    case 2:
                        window.vm.$router.push({ name: 'all', params: { type: 0 ,nokeep:true} })
                        break;
                    case 3:
                        window.vm.$router.push({ name: 'all', params: { type: 1 ,nokeep:true} })
                        break;
                    case 4:
                        window.vm.$router.push({ name: 'all', params: { type: 0 ,nokeep:true} })
                        break;
                }
            }else{
                if(!window.firstClick){
                    window.showLoading(true)
                    window.firstClick=function () {
                        switch (k){
                            case 1:
                                window.vm.$router.push({ name: 'all', params: { type: 2 ,nokeep:true} })
                                break;
                            case 2:
                                window.vm.$router.push({ name: 'all', params: { type: 0 ,nokeep:true} })
                                break;
                            case 3:
                                window.vm.$router.push({ name: 'all', params: { type: 1 ,nokeep:true} })
                                break;
                            case 4:
                                window.vm.$router.push({ name: 'all', params: { type: 0 ,nokeep:true} })
                                break;
                        }
                    }
                }
            }
        }
    </script>

    @if(is_https())
        <script src="https://jic.talkingdata.com/app/h5/v1?appid=5BE2AF352D654B89BB24D32D8E34602B&vn=魔栗妈咪学院&vc=1.0.0"></script>
    @else
        <script src="http://sdk.talkingdata.com/app/h5/v1?appid=5BE2AF352D654B89BB24D32D8E34602B&vn=魔栗妈咪学院&vc=1.0.0"></script>
    @endif

</head>

<body style="padding: 0px;margin: 0px">

<div class="home-container">

    {{--<div id="static">--}}
    <div id="static" class="static-container" style="{{config('oneitfarm.home_style')}} position:absolute;top:0;left:0;bottom:{{rpx750(100)}};right:0;overflow-y:scroll;overflow-x:hidden;">
        <!--搜索条-->
        <div   class="ohs-searchbar-tab" >
            <div class="ohs-searchbar-tab-inner">
                <img class="ohs-searchbar-tab-icon" src="//wyeth-course.nibaguai.com/wyeth/image/search.png"></img>
                <p id="searchhint" class="ohs-searchbar-tab-input">搜索一下</p>
            </div>
        </div>

        <div style="position:absolute;top:{{rpx750(78)}};left:0;right:0;bottom:0px;overflow-y:scroll;overflow-x:hidden;">
            <!--轮播图-->
            <div class="slider-container" style="height: {{rpx750(272)}}">
                <div class="slider-inner">
                    @foreach ($flashPics1 as $item)
                        <div class="slider-item">
                            <img class="slider-item-cover" src="{{$item['img']}}" onclick="window.location.href='{{$item['link']}}'" />
                        </div>
                    @endforeach
                </div>

                <div class="indicator-container">
                    <div class="indicator-inner" style="width: {{rpx750(count($flashPics1)*20+(count($flashPics1)-1)*52)}}">
                        @foreach ($flashPics1 as $item)
                            <img class="indicator-pot" src="//wyeth-course.nibaguai.com/wyeth/image/pot_selected.png"/>
                        @endforeach
                    </div>
                </div>
            </div>
            <!--tag-->
            <div class="tags-container" style="height: {{rpx750(182)}};">

                <div class="tags" style="padding-top: {{rpx750(30)}};padding-bottom: {{rpx750(27)}}">
                    @for($i=0;$i<count($top_tags);$i++)
                        <div class="tag-item" onclick="onStageClick({{$i+1}},{{$top_tags[$i]['id']}})">
                            <img class="tag-icon1" src="{{$top_tags[$i]['img']}}">
                            <p class="tag-text1">{{$top_tags[$i]['name']}}</p>
                        </div>
                    @endfor
                </div>
                <div class="tags-shadow"></div>
            </div>
            <!--专家、活动、主题-->
            <div class="hot-cell" style="margin-top: {{rpx750(18)}}">
                <div class="teacher-activity">
                    @if(isset($cat_activity[0]))
                        <div class="teacher-container" onclick="onItemClick('{{$cat_activity[0]['link']}}', '{{$cat_activity[0]['type']}}', '{{$cat_activity[0]['subject']}}',1)">
                            <img class="img-teacher-title" src="{{$cat_activity[0]['header']}}">
                            <img class="img-teacher-cover" src="{{$cat_activity[0]["img"]}}">
                        </div>
                    @endif
                    @if(isset($cat_activity[1]))
                        <div class="activity-container" onclick="onItemClick('{{$cat_activity[1]['link']}}', '{{$cat_activity[1]['type']}}', '{{$cat_activity[1]['subject']}}',2)">
                            <img class="img-teacher-title" src="{{$cat_activity[1]['header']}}">
                            <img class="img-teacher-cover" src="{{$cat_activity[1]["img"]}}">
                        </div>
                    @endif
                </div>
                <div class="theme-container">
                    <img class="img-teacher-title" src="//wyeth-course.nibaguai.com/wyeth/image/remen.png">
                    <div class="tags" style="margin-top: {{rpx750(80)}}">
                        @foreach(array_slice($index_tags,0,3) as $tag)
                            <div class="tag-item" onclick="onTagClick('{{$tag['name']}}',{{$tag['id']}})">
                                <img class="tag-icon2" src="{{strcasecmp($tag['img'],'')==0?"//wyeth-course.nibaguai.com/wyeth/image/qimeng.png":$tag['img']}}">
                                <p class="tag-text2">{{$tag['name']}}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="tags" style="margin-top: {{rpx750(62)}}">
                        @foreach(array_slice($index_tags,3,3) as $tag)
                            <div class="tag-item" onclick="onTagClick('{{$tag['name']}}',{{$tag['id']}})">
                                <img class="tag-icon2" src="{{strcasecmp($tag['img'],'')==0?"//wyeth-course.nibaguai.com/wyeth/image/qimeng.png":$tag['img']}}">
                                <p class="tag-text2">{{$tag['name']}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!--最热课程-->
            <div class="course-container" style="margin-top: {{rpx750(18)}}">
                <div class="header-panel">
                    <div class="header-title">
                        <img style="width: {{rpx750(8)}};height: {{rpx750(38)}}" src="//wyeth-course.nibaguai.com/wyeth/image/line.png">
                        <p class="header-title-txt">最热课程</p>
                    </div>
                    <div class="header-more" onclick="onMoreClick(1)">
                        <p class="header-more-txt">更多</p>
                        <img style="width: {{rpx750(28)}};height: {{rpx750(28)}}" src="//wyeth-course.nibaguai.com/wyeth/image/more.png">
                    </div>
                </div>
                <div class="cells-container">
                    @foreach($hotClass as $item)
                        <div class="cell-item" onclick="onCourseClick({{$item['id']}},{{$item['status']}},{{$item['review_type']}},1)">
                            <div class="course-div1">
                                <div class="course-cover">
                                    <img class="course-cover-img" src="{{$item['img']}}">
                                    <div class="course-shadow"></div>
                                    <div class="course-hot">
                                        <img class="course-hot-img" src="//wyeth-course.nibaguai.com/wyeth/image/love.png"></img>
                                        <p class="course-hot-txt">{{$item['hot']>10000?round($item['hot']/10000,1).'万':$item['hot']}}</p>
                                    </div>
                                    <img class="course-video-tag" style="display: {{$item['review_type']==2?'':'none'}}"
                                         src="//wyeth-course.nibaguai.com/wyeth/image/video.png"/>
                                </div>
                                <p class="course-title">{{$item['title']}}</p>
                            </div>
                            <div class="course-div2">
                                <div class="course-teacher">
                                    <p class="teacher-name">{{$item['teacher_name']}}</p>
                                    <p class="teacher-position">{{$item['teacher_position']}}</p>
                                </div>
                                <div class="course-tag-container">
                                    <img class="tag-img" src="//wyeth-course.nibaguai.com/wyeth/image/tag.png">
                                    <p class="tag-txt">{{ isset($item['tag'])?$item['tag']:"" }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!--轮播图-->
            <div class="slider-container" style="height: {{rpx750(200)}};margin-top: {{rpx750(18)}}">
                <div class="slider-inner">
                    @foreach ($flashPics2 as $item)
                        <div class="slider-item">
                            <img class="slider-item-cover2" src="{{$item['img']}}" onclick="window.location.href='{{$item['link']}}'"/>
                        </div>
                    @endforeach
                </div>

                <div class="indicator-container">
                    <div class="indicator-inner" style="width: {{rpx750(count($flashPics1)*20+(count($flashPics1)-1)*52)}}">
                        {{--@foreach($flashPics2 as $item)--}}
                        {{--<img class="indicator-pot" src="//wyeth-course.nibaguai.com/wyeth/image/pot_selected.png"/>--}}
                        {{--@endforeach--}}
                    </div>
                </div>
            </div>
            <!--最新课程-->
            <div class="course-container">
                <div class="header-panel">
                    <div class="header-title">
                        <img style="width: {{rpx750(8)}};height: {{rpx750(38)}}" src="//wyeth-course.nibaguai.com/wyeth/image/line.png">
                        <p class="header-title-txt">最新课程</p>
                    </div>
                    <div class="header-more" onclick="onMoreClick(2)">
                        <p class="header-more-txt">更多</p>
                        <img style="width: {{rpx750(28)}};height: {{rpx750(28)}}" src="//wyeth-course.nibaguai.com/wyeth/image/more.png">
                    </div>
                </div>
                <div class="cells-container">
                    @foreach($newClass as $item)
                        <div class="cell-item" onclick="onCourseClick({{$item['id']}},{{$item['status']}},{{$item['review_type']}},2)">
                            <div class="course-div1">
                                <div class="course-cover">
                                    <img class="course-cover-img" src="{{$item['img']}}">
                                    <div class="course-shadow"></div>
                                    <div class="course-hot">
                                        <img class="course-hot-img" src="//wyeth-course.nibaguai.com/wyeth/image/love.png"></img>
                                        <p class="course-hot-txt">{{$item['hot']>10000?round($item['hot']/10000,1).'万':$item['hot']}}</p>
                                    </div>
                                    <img class="course-video-tag" style="display: {{$item['review_type']==2?'':'none'}}"
                                         src="//wyeth-course.nibaguai.com/wyeth/image/video.png"/>
                                </div>
                                <p class="course-title">{{$item['title']}}</p>
                            </div>
                            <div class="course-div2">
                                <div class="course-teacher">
                                    <p class="teacher-name">{{$item['teacher_name']}}</p>
                                    <p class="teacher-position">{{$item['teacher_position']}}</p>
                                </div>
                                <div class="course-tag-container">
                                    <img class="tag-img" src="//wyeth-course.nibaguai.com/wyeth/image/tag.png">
                                    <p class="tag-txt">{{ isset($item['tag'])?$item['tag']:"" }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!--推荐课程-->
            <div class="course-container"  style="margin-top: {{rpx750(18)}}">
                <div class="header-panel">
                    <div class="header-title">
                        <img style="width: {{rpx750(8)}};height: {{rpx750(38)}}" src="//wyeth-course.nibaguai.com/wyeth/image/line.png">
                        <p class="header-title-txt">推荐课程</p>
                    </div>
                    <div class="header-more" onclick="onMoreClick(3)">
                        <p class="header-more-txt">更多</p>
                        <img style="width: {{rpx750(28)}};height: {{rpx750(28)}}" src="//wyeth-course.nibaguai.com/wyeth/image/more.png">
                    </div>
                </div>
                <div class="cells-container" id="recomCourse">
                    @foreach($recomClass as $item)
                        <div class="cell-item" onclick="onCourseClick({{$item['id']}},{{$item['status']}},{{$item['review_type']}},3)">
                            <div class="course-div1">
                                <div class="course-cover">
                                    <img class="course-cover-img" src="{{$item['img']}}">
                                    <div class="course-shadow"></div>
                                    <div class="course-hot">
                                        <img class="course-hot-img" src="//wyeth-course.nibaguai.com/wyeth/image/love.png"></img>
                                        <p class="course-hot-txt">{{$item['hot']>10000?round($item['hot']/10000,1).'万':$item['hot']}}</p>
                                    </div>
                                    <img class="course-video-tag" style="display: {{$item['review_type']==2?'':'none'}}"
                                         src="//wyeth-course.nibaguai.com/wyeth/image/video.png"/>
                                </div>
                                <p class="course-title">{{$item['title']}}</p>
                            </div>
                            <div class="course-div2">
                                <div class="course-teacher">
                                    <p class="teacher-name">{{$item['teacher_name']}}</p>
                                    <p class="teacher-position">{{$item['teacher_position']}}</p>
                                </div>
                                <div class="course-tag-container">
                                    <img class="tag-img" src="//wyeth-course.nibaguai.com/wyeth/image/tag.png">
                                    <p class="tag-txt">{{ isset($item['tag'])?$item['tag']:""}}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!--更多课程-->
            <div class="more-course">
                <p class="more-text" onclick="onMoreClick(4)">更多课程</p>
            </div>
            <!--声明-->
            <p class="declaration-txt">{{$app_config['copyright']}}</p>
            <div style="height: {{rpx750(35)}}"></div>

            <div class="tabbar-shadow"></div>



            <div id="loading-container"  style="display: none">
                <div id="toast-loading" class="toast-loading">
                    <div class="toast-loading-container">
                        <img src="//wyeth-course.nibaguai.com/wyeth/image/toast_loading.gif" class="toast-loading-img" />
                        <p class="toast-loading-text">正在加载中..</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="tabbar2" class="tabbar-static">
        <div class="tabbar-item-static"></div>
        <div class="tabbar-item-static"></div>
        <div class="tabbar-item-static"></div>
        <div class="tabbar-item-static"></div>
        <div class="tabbar-item-static"></div>
        <div class="tabbar-center-item"></div>
    </div>

    <div id="root"></div>

</div>


<script type="text/javascript">
    function autoStyle() {
        var static=document.querySelector('#static')
        var staticText=static.outerHTML
        var p=/[0-9]\.?[0-9]*px/g
        static.outerHTML=staticText.replace(p,function (s) {
            s=s.slice(0,s.length-2)
            var x=parseFloat(s)
            return rpx(x)
        })
    }

//    autoStyle()
</script>


<script type="text/javascript">


    var png_selected= '//wyeth-course.nibaguai.com/wyeth/image/pot_selected.png'
    var png_default='//wyeth-course.nibaguai.com/wyeth/image/pot_default.png'
    var containers=document.getElementsByClassName('slider-container')
    for (var i=0 ;i<containers.length;i++){
        var s=containers[i].querySelector('.slider-inner')
        var mIndicator=containers[i].querySelector('.indicator-inner')
        var bind=function (s,mIndicator) {
            var indi=new indicator(mIndicator,{
                toSelected:function (el) {
                    if(el){
                        el.src=png_selected
                    }
                },
                toDefault:function (el) {
                    if(el){
                        el.src=png_default
                    }
                }
            })
            new slider(s,{
                duration:300,
                interval:3000,
                autoPlay:true,
                onChange:function (newid,oldid) {
                    indi.update(newid,oldid)
                }
            })
        }
        bind(s,mIndicator)
    }

    var host="{{config('app.url')}}"

    window.refreshRecom=function () {
        var xhr =new XMLHttpRequest();
        if(xhr){
            xhr.onreadystatechange=function () {
                if(xhr.readyState==4){
                    if(xhr.status==200){
//                        console.log('--xhr.response--',xhr.response)
                        var res=JSON.parse(xhr.response)

                        if(res.ret==1){
                            var data=res.data
                            var html=''
                            for (var i=0;i<data.length;i++){
                                var item=data[i]
                                var s=`<div class="cell-item" onclick="onCourseClick(${item['id']},${item['status']},${item['review_type']},3)">
                        <div class="course-div1">
                            <div class="course-cover">
                                <img class="course-cover-img" src="${item['img']}">
                                <div class="course-shadow"></div>
                                <div class="course-hot">
                                    <img class="course-hot-img" src="//wyeth-course.nibaguai.com/wyeth/image/love.png"></img>
                                    <p class="course-hot-txt">${item['hot']>10000?(item['hot']/10000).toFixed(1)+'万':item['hot']}</p>
                                </div>
                                 <img class="course-video-tag" style="display: ${item['review_type']==2?'':'none'}"
                                         src="//wyeth-course.nibaguai.com/wyeth/image/video.png"/>
                            </div>
                            <p class="course-title">${item['title']}</p>
                        </div>
                        <div class="course-div2">
                            <div class="course-teacher">
                                <p class="teacher-name">${item['teacher_name']}</p>
                                <p class="teacher-position">${item['teacher_position']}</p>
                            </div>
                            <div class="course-tag-container">
                                <img class="tag-img" src="//wyeth-course.nibaguai.com/wyeth/image/tag.png">
                                <p class="tag-txt">${item['tag']}</p>
                            </div>
                        </div>
                    </div>`
                                html=html+s
                            }
                            console.log('---- html ----',html)
                            document.querySelector('#recomCourse').innerHTML=html
                        }

                    }else{

                    }
                }
            };
            var url=host+'/wyeth/course/getRecomCourse'
            xhr.open("GET",url,true);
            xhr.send();
        }
    }

    var searchbarHint=document.querySelector('#searchhint')
    if(searchbarHint){
        searchbarHint.innerHTML='{{$app_config['search_placeholder']}}'
        var searchbar=document.querySelector('.ohs-searchbar-tab-inner')
        searchbar.addEventListener('click',function () {
            window.CIData.push(['trackEvent', 'wyeth', 'home_search', '','']);
            if(window.vm){
                window.vm.$router.push({name:'search',params:{hint:'{{$app_config['search_placeholder']}}',nokeep:true}})
            }
        })
    }

</script>


<script tag="1">
    function GetRequest() {
        var url = window.location.search
        var requestParams = {}
        if (url.indexOf('?') !== -1) {
            var str = url.substr(1)
            var strs = str.split('&')
            for (var i = 0; i < strs.length; i++) {
                requestParams[strs[i].split('=')[0]] = unescape(strs[i].split('=')[1])
            }
        }
        return requestParams
    }

    // 获取url中参数
    var requestParams = GetRequest()

    window.requestParams = requestParams || {}

    // wyeth_channel为url带参数_hw_c的值,来源渠道
    window.requestParams.wyeth_channel = requestParams._hw_c || ''

    window.wyeth_channel = requestParams._hw_c || ''

    window.requestParams.test = requestParams.test || ''
    window.onload = function () {
        //加载完毕，执行代码
        console.log('!!!!!!!!! window onload: ', Math.round(new Date().getTime() / 1000))
        var xhr = new XMLHttpRequest();
        xhr.open("get", "{{config('app.url') . "/wyeth/loadHome"}}", true);
        xhr.withCredentials = true;
        xhr.send(null);


//        if(navigator.userAgent.indexOf("JianKongBao") == -1 ){
//            var s = document.createElement("script");
//            s = document.createElement("script");
//            s.async = true;
//            s.type = "text/javascript";
//            s.src = "//oneitfarm.com/cidata/main.php/json/script";
//            document.getElementsByTagName("head")[0].appendChild(s);
//        }

        {{--var a = document.createElement("script");--}}
        {{--a.type = "text/javascript";--}}
        {{--a.src = "js/{{$config['manifestName']}}";--}}
        {{--document.getElementsByTagName("head")[0].appendChild(a);--}}

        {{--setTimeout(function () {--}}
            {{--var b = document.createElement("script");--}}
            {{--b.type = "text/javascript";--}}
            {{--b.src = "js/{{$config['vendorName']}}";--}}
            {{--document.getElementsByTagName("head")[0].appendChild(b);--}}
            {{--setTimeout(function () {--}}
                {{--var c = document.createElement("script");--}}
                {{--c.type = "text/javascript";--}}
                {{--c.src = "js/{{$config['appName']}}";--}}
                {{--document.getElementsByTagName("head")[0].appendChild(c);--}}

                {{----}}

            {{--},100)--}}
        {{--},100)--}}

        @if(isset($config['chunksName']))
                @foreach ($config['chunksName'] as $chunk)

            s = document.createElement("script");
        s.async = true;
        console.log('$chunk=','{{$chunk}}')
        s.type = "text/javascript";
        s.src = "js/"+'{{$chunk}}';
        document.getElementsByTagName("head")[0].appendChild(s);

        @endforeach

        @endif

    }
</script>


<script type="text/javascript">

</script>



<script tag="1" defer  type="text/javascript" src="js/{{$config['manifestName']}}"></script>
<script tag="1" defer  type="text/javascript" src="js/{{$config['vendorName']}}"></script>
<script tag="1" defer  type="text/javascript" src="js/{{$config['appName']}}"></script>

</body>
</html>
