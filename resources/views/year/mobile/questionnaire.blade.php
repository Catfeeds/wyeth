<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />
    <meta name="format-detection" content="telephone=no" />
    <title>妈妈微课堂调查问卷</title>
    <link rel="stylesheet" href="{{ $static_url }}/mobile/css/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="{{ $static_url }}/mobile/css/my_css.css" />
</head>
<body>
<form id="form1" class="validate" method="post" action="">
    <input type="hidden" name="openid" value="{{$openid}}"/>
    <div id="toptitle">
        <h1 class="htitle" >弹幕有礼砸中你！</h1>
    </div>
    <div id="divContent">
        <div id="divQuestion">
            <fieldset class="fieldset" style="" id="fieldset1">
                <div class="field ui-field-contain" id="div10" topic="10" data-role="fieldcontain" type="9">
                    <div class="field-label">
                        请填写你的详细信息
                    </div>
                    <table cellspacing="0" class="matrix-rating">
                        <tbody>
                        <tr rv="1">
                            <th style="text-align:left;">姓名：</th>
                            <td style="text-align:left;">
                                <div class="ui-input-text">
                                    <input type="text" id="name" name="name" />
                                </div></td>
                        </tr>
                        <tr rv="1_1" style="height:1em;"></tr>
                        <tr rv="2">
                            <th style="text-align:left;">手机：</th>
                            <td style="text-align:left;">
                                <div class="ui-input-text">
                                    <input type="text" id="phone" name="phone" verify="手机" />
                                </div></td>
                        </tr>
                        <tr rv="1_1" style="height:1em;"></tr>
                        <tr rv="3">
                            <th style="text-align:left;">地址：</th>
                            <td style="text-align:left;">
                                <div class="ui-input-text">
                                    <input type="text" id="address" name="address" />
                                </div></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="errorMessage"></div>
                </div>
            </fieldset>
        </div>
        <div class="footer">
            <div class="ValError">
            </div>
            <div id="divSubmit">
                <a id="ctlNext" href="javascript:;" class="button blue"> 提交</a>
            </div>
        </div>
    </div>
</form>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="{{ $static_url }}/mobile/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="{{ $static_url }}/mobile/js/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="{{ $static_url }}/mobile/js/record.js"></script>
<script type="text/javascript"  src="<?=config('course.mz_url');?>"></script>
<script>
    var json_config = {!! json_encode($package) !!};
    $(document).ready(function(){
        "use strict";
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要
            appId: json_config.appId, // 必填，企业号的唯一标识，此处填写企业号corpid
            timestamp: json_config.timestamp, // 必填，生成签名的时间戳
            nonceStr: json_config.nonceStr,  // 必填，生成签名的随机串
            signature: json_config.signature, // 必填，签名，见附录1
            jsApiList: [
                'checkJsApi',
                'hideOptionMenu',
            ]
        });
        wx.ready(function(){
            wx.hideOptionMenu();
        });

        var mark = false;
        $('#divSubmit').on('click', function() {
            if (mark) {
                return false;
            }
            var postData = $("#form1").serialize();
            $.ajax({
                type: "POST",
                url: "/year/questionnaire/save",
                data: postData,
                success: function (data) {
                    if (data.error) {
                        alert(data.message);
                    } else {
                        mark = true;
                        alert('提交成功！');
                    }
                }
            });
        });
    });
</script>
</body>
</html>