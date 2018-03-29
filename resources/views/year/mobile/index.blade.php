<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta content="width=device-width,user-scalable=no" name="viewport" />
	<link href="{{config('course.static_url')}}/year/css/reset.css" rel="styleSheet" type="text/css" />
	<link href="{{config('course.static_url')}}/year/css/main.css" rel="styleSheet" type="text/css" />
	<link href="{{config('course.static_url')}}/year/css/swiper.min.css" rel="styleSheet" type="text/css" />
	<title>惠氏妈妈俱乐部</title>
</head>
<body>
<div class="content">
	<div class="header"> 
		<img class='img1' src="{{config('course.static_url')}}/year/images/logo_txt_v1.png" alt=""/>
		<img class='img2' src="{{config('course.static_url')}}/year/images/Title.png" alt=""/>
	</div>
	<div class="livingBox">
		<img src="{{config('course.static_url')}}/year/images/onlineMain_v1.png" alt=""/>
		<div class="livingBoxList">
			<div id="barrage"></div>
		</div>

	</div>
	<div class="typingArea">
		<div class="typingTitle">
			<img src="{{config('course.static_url')}}/year/images/subtitle.png" alt=""/>
		</div>
		<div class="typingTitle2 cance2" style="display: none;">
			<img src="{{config('course.static_url')}}/year/images/subtitle2.png" alt="" />
			<div type="text" disabled="disabled"><p>偷偷告诉你</p>
			活跃的宝宝有机会赢得智能手环哦</div>
		</div>
		<div class="typingZone" id="typingZone">
			<a href="javascript:void(0);">{{$one}}<span></span></a>
			<a href="javascript:void(0);">{{$two}}<span></span></a>
			<a href="javascript:void(0);">{{$three}}<span></span></a>
			<a href="javascript:void(0);">{{$four}}<span></span></a>
		</div>
		<div class="typingInput">
			<input type="text" placeholder="我要写句独一无二的弹幕" id="typingInput" class="typingInputer"/>
		</div>
	</div>
	<a href="javascript:void(0);" class="sendMessage" id="sendMessage">
		<img src="{{config('course.static_url')}}/year/images/sendBTN.png" alt=""/>
		<div class="rocket ">
			<img src="{{config('course.static_url')}}/year/images/rocket.png" alt=""/>
		</div>
	</a>
	<a href="javascript:void(0);" style="display: none;" id="sendMessage2" class="sendMessage2 cance2">
		<img class="after_rocket2" src="{{config('course.static_url')}}/year/images/sendBTN.png" alt=""/>
		<div class="rocket2 ">
			<img src="{{config('course.static_url')}}/year/images/rocket.png" alt=""/>
		</div>
	</a>
	<p class="reward_tips">中奖的同学将于会后收到俱乐部消息哟~</p>
</div>

<script src="{{config('course.static_url')}}/year/js/main.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="{{config('course.static_url')}}/year/js/yearliving.js?v={{$resource_version}}"></script>
<script type="text/javascript">
var uid = {{$user->id}};

$(document).ready(function(){
	YearLiving.initWX({
		debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
		appId: '{{$package['appId']}}', // 必填，企业号的唯一标识，此处填写企业号corpid
		timestamp: {{$package['timestamp']}}, // 必填，生成签名的时间戳
		nonceStr: '{{$package['nonceStr']}}', // 必填，生成签名的随机串
		signature: '{{$package['signature']}}',// 必填，签名，见附录1
		jsApiList: [
			'hideOptionMenu'
		]
	});
	$.getJSON('/token', function (data) {
		if ('token' in data) {
			token = data.token;
			$.ajaxSetup({
				beforeSend: function(xhr) {
					if (!token) {
						console.log('token empty before ajax send');
						return false;
					}
					xhr.setRequestHeader('Authorization', 'bearer ' + token);
				}
			});
			var options = {
				uid: uid
			};
			YearLiving.init(options);
		}
	});
});
</script>
</body>
</html>
