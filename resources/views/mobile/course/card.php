<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    @include('public.head')
    <link rel="stylesheet" href="<?=config('course.static_url');?>/mobile/css/sign.css?v=<?=$resource_version;?>" />
    <title><?php echo '妈妈微课堂' ?></title>
        <style type="text/css">
            body{ background:#e5e5e5; }
        </style>
    <link rel="stylesheet" href="<?=config('course.static_url');?>/mobile/js/datepicker/mobiscroll.css?v=<?=$resource_version;?>" />
    <script type="text/javascript"  src="<?=config('course.mz_url');?>"></script>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/jquery-1.8.3.js?v=<?=$resource_version;?>"></script>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/record.js"></script>
    <script type="text/javascript">
        var page_name = '填卡页';
        var page = '<?=$page;?>';
        Record.init({
            static_url: '<?=config('course.static_url');?>',
            mz: {
                site_id: '<?=config('record.mz_siteid');?>',
                openid: '<?=$openid;?>'
            },
            dc: {
                appid: '<?=config('record.dc_appid');?>'
            },
            channel: '<?=$channel;?>',
            uid: '<?=$uid;?>'
        });
        Record.page(page_name, {}, page);
    </script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js?v=<?=$resource_version;?>"></script>
    <script>
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: '<?=$package['appId'];?>', // 必填，企业号的唯一标识，此处填写企业号corpid
            timestamp: <?=$package['timestamp'];?>, // 必填，生成签名的时间戳
            nonceStr: '<?=$package['nonceStr'];?>', // 必填，生成签名的随机串
            signature: '<?=$package['signature'];?>',// 必填，签名，见附录1
            jsApiList: [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage'
            ]
        });

        var shareUrl = '<?=config('app.url');?>' + '/mobile/reg?cid=' + '<?=$course->id;?>'+'&from_openid='+'<?=$openid;?>';
        wx.ready(function(){
            // 分享朋友圈的数据
            wx.onMenuShareTimeline({
                title: '我发现了一堂好课，在成为好妈妈的路上又近了一步，一起加入好妈妈的行列吧！', // 分享标题
                link: _mz_wx_shareUrl(shareUrl), // 分享链接
                imgUrl: '<?=config('course.static_url');?>/mobile/images/logo.jpg', // 分享图标
                success:function() {
                    Record.timeline(page_name);
                }
            });

            // 分享给好友的数据
            wx.onMenuShareAppMessage({
                title: '妈妈微课堂', // 分享标题
                desc: '我发现了一堂好课，在成为好妈妈的路上又近了一步，一起加入好妈妈的行列吧！', // 分享描述
                link: _mz_wx_shareUrl(shareUrl), // 分享链接
                imgUrl: '<?=config('course.static_url');?>/mobile/images/logo.jpg', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success:function() {
                    Record.friend(page_name);
                }
            });
        });
    </script>
    <script src="http://mp.gtimg.cn/open/js/openApi.js"></script>
    <script type="text/javascript">
        $.ajax({
            type: "get",
            url: 'http://wyeth.qq.nplusgroup.com/api/toauth/index.json',
            data:'url='+encodeURIComponent(location.href.split('#')[0])+'&callback=?',
            dataType: "jsonp",
            jsonp: "callback",
            success: function(json){
                var mqqConfig = {
                    debug:false,
                    appId: json.appId,
                    timestamp: json.timestamp,
                    nonceStr: json.nonceStr,
                    signature: json.signature,
                    jsApiList: [
                        'onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareQzone','closeWindow'
                        ,'hideOptionMenu','showOptionMenu','hideMenuItems','showMenuItems','hideAllNonBaseMenuItem'
                    ]
                }
                mqq.config(mqqConfig);
            },
            error:function (){
                alert("fail");
            }
        });

        window.isready = false;
        mqq.ready(function(){
            window.isready = true;
            mqq.hideAllNonBaseMenuItem();
            mqq.showMenuItems({
                menuList: ['menuItem:share:qq'] // 要显示的菜单项，所有menu项见附录3
            });
            share({});
        });

        window.shareparam = {
            'title':'妈妈微课堂',
            'link':shareUrl,
            'imgUrl':"<?=config('course.static_url');?>/mobile/images/logo.jpg",
            'desc':'我发现了一堂好课，在成为好妈妈的路上又近了一步，一起加入好妈妈的行列吧！'
        };


        function extend(destination, source) {
            for (var property in source)
                destination[property] = source[property];
            return destination;
        }

        function share(params){
            if(params){
                window.shareparam = extend(window.shareparam,params);
            }
            //分享到qq好友
            mqq.onMenuShareQQ({
                title: window.shareparam.title, // 分享标题
                link: window.shareparam.link, // 分享链接
                imgUrl: window.shareparam.imgUrl, // 分享图标
                desc: window.shareparam.desc, // 分享描述
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数

                }
            });
        }
    </script>

</head>
<body>
    <div class="page" id="wrapper">
        <div class="content" style="position:static;" id="content">
            <div class="talk_list">
                <div class="talk_item">
                    <div class="media">
                        <a href="javascript:void(0)"><img id="pic" src="<?=config('course.static_url');?>/mobile/img/image5.jpg" style="" /></a>
                    </div>
                    <div class="talk_main" style="padding-right:0;">
                        <div class="body">
                           <div class="angle angle_left"></div>
                           <div class="msg msg_text" id="msg">
                                <p>我是您的私人孕育顾问Miss惠！<br />
                                   欢迎报名妈妈微课堂<?php echo date('m月d日',strtotime($course->start_day)); ?>的《<?php echo $course->title; ?>》。<br />
                                <span style="color:#ff894f;">请完善您的听课证。</span>
                               </p>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <img alt="" id="bg" src="<?=config('course.static_url');?>/mobile/img/bg_course_card.png" style="width:100%;">
            <img id="avatar" src="<?php echo $user->avatar;?>" style="width:19%;border-radius:40px;border:solid 3px #fff;position:absolute;top:30px;left:6%;" />
                <div class="form">
                    <div class="line">
                        <div class="label"><div class="text">姓<span class="word2"></span>名</div></div>
                        <div class="input"><input type="text" placeholder="请输入您的姓名" class="txt" id="txtName" /></div>
                    </div>
                    <div class="line">
                        <div class="label"><div class="text">所在城市</div></div>
                        <div class="input"><input type="text" placeholder="请输入您所在的城市" value="" data-value="" class="txt city" id="txtAddress" /></div>
                    </div>
                    <div class="line">
                        <div class="label"><div class="text" style="top:16%;">预产期或<br />宝宝生日</div></div>
                        <div class="input"><input type="text" placeholder="请输入预产期或宝宝生日" style="text-align:left;" class="txt date" id="txtDate" /></div>
                    </div>
                    <div class="line">
                        <div class="label"><div class="text">手机号码</div></div>
                        <div class="input"><input type="tel" placeholder="请输入您的手机号码" class="txt" id="txtPhone" /></div>
                    </div>
                    <div class="line">
                        <div class="label"><div class="text">验<span class="word1"></span>证<span class="word1"></span>码</div></div>
                        <div class="input">
                            <input type="tel" placeholder="验证码" class="txt code" id="txtCode" />
                            <a class="btn btn_code" id="btnSendCode" style="">
                              <div class="text" style="top:16%;">发送验证码</div>
                            </a>
                        </div>
                    </div>
                    <div class="line" style="margin-top:2%;">
                        <a class="btn btn_submit" style="" id="btnSubmit">
                            <img src="<?=config('course.static_url');?>/mobile/img/btn_submit_card.png" style="width:40%;" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="unvisible">
		<input type="hidden" id="hdAddress" value="" />
    </div>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/citypicker/city.js?v=<?=$resource_version;?>"></script>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/citypicker/mobicity.js?v=<?=$resource_version;?>"></script>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/datepicker/mobiscroll.js?v=<?=$resource_version;?>"></script>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/iscroll.js?v=<?=$resource_version;?>"></script>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/page.js?v=<?=$resource_version;?>"></script>
    <script type="text/javascript">
       var winHeight, sendTime = 60, sendInterval = 60, sendTimer, citySelect, dateSelect, loadInfo = false, loadBg = false;
       var token;
        $.getJSON('/token', function (data) {
            if ('token' in data) {
                token = data.token;
            }
        });

        $(function() {
            $.ajaxSetup({
                beforeSend: function(xhr) {
                    if (!token) {
                        console.log('token empty before ajax send');
                        return false;
                    }
                    xhr.setRequestHeader('Authorization', 'bearer ' + token);
                }
            });
            PageInit();
        });

        function PageInit(){
   	        $("#txtCode").css("left", $("#txtPhone").position().left);
   	        $("#wrapper").height($(window).height());

           new iScroll("wrapper", { hScroll: false, vScrollbar: false, hScrollbar: false, Scrollbar: false, mouseWheel:true });

      	    citySelect = $("#txtAddress").citypicker({
      	    	headerText:false,
    			theme: 'android-ics',
    			mode: 'scroller',
    			valueFormat: '{p} {c} {a}',
    			lang: 'zh',
    			rows: 3,
    			display: 'bottom',
    			data:allCity
          	});
    	   dateSelect = $("#txtDate").mobiscroll({
    		    preset: 'date',
 				theme: 'android-ics light',
 				display: 'modal',
 				mode: 'scroller',
 				dateFormat: 'yyyy-mm-dd',
 				lang: 'zh',
 				rows: 3,
 				showNow: false,
 				startYear: new Date().getFullYear() - 11,
 				endYear: new Date().getFullYear() + 5
           });

     	   $("#btnSendCode").on("click", function(){
      		   if ($(this).hasClass("disable")) {
                   return;
               }
               if(sendTime == sendInterval){
                   var phone = $.trim($("#txtPhone").val());
                   if(phone == ""){
               	       ShowAlert("手机号码不能为空！");
               	       return;
                   }
                   var reg = /^(1[3|4|5|7|8]\d{9})$/;
                   if (!reg.test(phone)) {
                	   ShowAlert("手机号码格式不正确！");
                       return;
                   }
                   $(this).addClass("disable");
                   $.get("/api/course/code?mobile=" + phone, function(response){
                	    if(console.log)
                      	    console.log(response);
               	        if(response.status == 1){
                  	         $("#btnSendCode .text").text(sendTime + "秒重新发送");
                  	         sendTime--;
                	         sendTimer = setInterval(function(){
                	    	     $("#btnSendCode .text").text(sendTime + "秒重新发送");
                	    	     sendTime--;
                	    	     if(sendTime < 0){
                    	    		 $("#btnSendCode .text").text("发送验证码");
                    	    		 sendTime = sendInterval;
                                     clearInterval(sendTimer);
                                 }
                	    	 }, 1000);
                     	}
                   	    else{
                     		ShowAlert(response.error_msg || "发送验证码失败！");
                        }
                  	    $("#btnSendCode").removeClass("disable");
                   });
               }
           });

           $("#btnSubmit").on("click", function(){
               //秒针检测
               _mz_wx_custom(51,'新会员报名');

        	   if ($(this).hasClass("disable")) {
                   return;
               }
               var name = $.trim($("#txtName").val());
               if (name == "") {
                   ShowAlert("姓名不能为空！");
                   return;
               }
               var address = $.trim($("#txtAddress").val());
               if (address == "") {
            	   ShowAlert("所在城市不能为空！");
                   return;
               }
               var date = $.trim($("#txtDate").val());
               if (date == "") {
            	   ShowAlert("预产期或宝宝生日不能为空！");
                   return;
               }

               var phone = $.trim($("#txtPhone").val());
               if (phone == "") {
            	   ShowAlert("电话号码不能为空！");
                   return;
               }
               var mobile = /^(1[3|4|5|7|8]\d{9})|(0\d{2,3}[-\s]?\d{7,8})$/;
               if (!mobile.test(phone)) {
            	   ShowAlert("手机号码格式不正确！");
                   return;
               }
               var code = $.trim($("#txtCode").val());
               if (code == "") {
                   ShowAlert("验证码不能为空！");
                   return;
               }
               if (code.length != 4) {
                   ShowAlert("验证码不正确！");
                   return;
               }

               $(this).addClass("disable");
               var tmp = address.split(" ");
               //realname姓名、sex性别、mobile手机号、code验证码、birthday生日、province省、city市、 county县
               //api/proxy.php?url=
               $.post("/api/course/sign", {
                    //cid:<?php echo $course['id']; ?>, uid:<?php echo $user['id']; ?>, realname:name, birthday:date,
                    cid: '<?php echo $courseIdStr; ?>',
                    uid:<?php echo $user['id']; ?>,
                    realname:name,
                    birthday:date,
                    province:tmp[0] || '',
                    city:tmp[1] || '',
                    county:tmp[2]||'',
                    phone:phone,
                    code:code
                    },
                    function(response){
                        $(this).removeClass("disable");
                        if(response.status == 1){
                        //window.location = '/mobile/sign_ok?cid=<?php echo $course['id']; ?>&uid=<?php echo $user['id']; ?>';
                        window.location = '/mobile/sign';
                 	}
               	    else{
                 		ShowAlert(response.error_msg || "提交保存信息失败！");
                    }
               });

           });
       }
    </script>
@include('public.statistics')
</body>
</html>
