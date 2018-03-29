<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />
    <meta name="format-detection" content="telephone=no" />
    @include('public.head')
    <title>妈妈微课堂调查问卷</title>
    <link rel="stylesheet" href="{{ $static_url }}/mobile/css/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="{{ $static_url }}/mobile/css/my_css.css" />
</head>
<body>
<form id="form1" class="validate" method="post" action="">
    <input type="hidden" name="cid" value="{{$cid}}"/>
    <input type="hidden" name="openid" value="{{$openid}}"/>
    <div id="toptitle">
        <h1 class="htitle" >妈妈微课堂调查问卷</h1>
    </div>
    <div id="divContent">
        <div id="divQuestion">
            <fieldset class="fieldset" style="" id="fieldset1">
                <div class="field ui-field-contain" id="div1" topic="1" data-role="fieldcontain" type="3">
                    <div class="field-label">
                        1. 对于音频听课的形式，是否接受？
                    </div>
                    <div class="ui-controlgroup">
                        <div class="ui-radio">
                            <span class="jqradiowrapper"> <input type="radio" value="yes" id="q1_1" name="q1" style="display:none;" /> </span>
                            <label for="q1_1">yes</label>
                        </div>
                        <div class="ui-radio">
                            <span class="jqradiowrapper"> <input type="radio" value="no" id="q1_2" name="q1" style="display:none;" /> </span>
                            <label for="q1_2">no</label>
                        </div>
                    </div>
                    <div class="field-label">
                        2. 画面翻动是否跟上语音表达？
                    </div>
                    <div class="ui-controlgroup">
                        <div class="ui-radio">
                            <span class="jqradiowrapper"> <input type="radio" value="yes" id="q2_1" name="q2" style="display:none;" /> </span>
                            <label for="q2_1">yes</label>
                        </div>
                        <div class="ui-radio">
                            <span class="jqradiowrapper"> <input type="radio" value="no" id="q2_2" name="q2" style="display:none;" /> </span>
                            <label for="q2_2">no</label>
                        </div>
                        <div class="ui-radio">
                            <span class="jqradiowrapper"> <input type="radio" value="没有留意" id="q2_3" name="q2" style="display:none;" /> </span>
                            <label for="q2_3">没有留意</label>
                        </div>
                    </div>
                    <div class="field-label">
                        3.  文字及图表是否清晰？
                    </div>
                    <div class="ui-controlgroup">
                        <div class="ui-radio">
                            <span class="jqradiowrapper"> <input type="radio" value="yes" id="q3_1" name="q3" style="display:none;" /> </span>
                            <label for="q3_1">yes</label>
                        </div>
                        <div class="ui-radio">
                            <span class="jqradiowrapper"> <input type="radio" value="no" id="q3_2" name="q3" style="display:none;" /> </span>
                            <label for="q3_2">no</label>
                        </div>
                        <div class="ui-radio">
                            <span class="jqradiowrapper"> <input type="radio" value="没有留意" id="q3_3" name="q3" style="display:none;" /> </span>
                            <label for="q3_3">没有留意</label>
                        </div>
                    </div>
                    <div class="field-label">
                        4. 是否希望收到课程内容教材？
                    </div>
                    <div class="ui-controlgroup">
                        <div class="ui-radio">
                            <span class="jqradiowrapper"> <input type="radio" value="yes" id="q4_1" name="q4" style="display:none;" /> </span>
                            <label for="q4_1">yes</label>
                        </div>
                        <div class="ui-radio">
                            <span class="jqradiowrapper"> <input type="radio" value="no" id="q4_2" name="q4" style="display:none;" /> </span>
                            <label for="q4_2">no</label>
                        </div>
                    </div>
                    <div class="errorMessage"></div>
                </div>
                <div class="field ui-field-contain" id="div2" topic="2" data-role="fieldcontain" type="3">
                    <div class="field-label">
                        5. 对于1/21母乳喂养课程内容的评分（单选）
                    </div>
                    <div class="ui-controlgroup">
                        <div class="ui-radio">
                            <span class="jqradiowrapper"><input type="radio" value="5分（实用、详尽、对我很有帮助）" id="q5_1" name="q5" style="display:none;" /></span>
                            <label for="q5_1">a) 5分（实用、详尽、对我很有帮助）</label>
                        </div>
                        <div class="ui-radio">
                            <span class="jqradiowrapper"><input type="radio" value="4分（有借鉴价值，还希望听到此类课程）" id="q5_2" name="q5" style="display:none;" /></span>
                            <label for="q5_2">b) 4分（有借鉴价值，还希望听到此类课程）</label>
                        </div>
                        <div class="ui-radio">
                            <span class="jqradiowrapper"><input type="radio" value="3分（以前有听过类似的内容）" id="q5_3" name="q5" style="display:none;" /></span>
                            <label for="q5_3">c) 3分（以前有听过类似的内容）</label>
                        </div>
                        <div class="ui-radio">
                            <span class="jqradiowrapper"><input type="radio" value="2分（对我帮助不大）" id="q5_4" name="q5" style="display:none;" /></span>
                            <label for="q5_4">d) 2分（对我帮助不大）</label>
                        </div>
                        <div class="ui-radio">
                            <span class="jqradiowrapper"><input type="radio" value="1分（内容不准确）" id="q5_5" name="q5" style="display:none;" /></span>
                            <label for="q5_5">e) 1分（内容不准确）</label>
                        </div>
                    </div>
                    <div class="errorMessage"></div>
                </div>
                <div class="field ui-field-contain" id="div6" topic="6" data-role="fieldcontain" type="9">
                    <div class="field-label">
                        6. 您还希望了解哪些母乳喂养的相关知识？
                    </div>
                    <table cellspacing="0" class="matrix-rating">
                        <tbody>
                        <tr rv="1">
                            <th style="text-align:left;"></th>
                            <td style="text-align:left;">
                                <div class="ui-input-text">
                                    <textarea id="q6" name="q6" rows="5"></textarea>
                                </div></td>
                        </tr>

                        </tbody>
                    </table>
                    <div class="errorMessage"></div>
                </div>
                <div class="field ui-field-contain" id="div7" topic="7" data-role="fieldcontain" type="9">
                    <div class="field-label">
                        7. 您希望参加哪位老师的讲座？请填写您心仪的讲师姓名
                    </div>
                    <table cellspacing="0" class="matrix-rating">
                        <tbody>
                        <tr rv="1">
                            <th style="text-align:left;"></th>
                            <td style="text-align:left;">
                                <div class="ui-input-text">
                                    <textarea id="q7" name="q7" rows="5"></textarea>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="errorMessage"></div>
                </div>
                <div class="field ui-field-contain" id="div7" topic="7" data-role="fieldcontain" type="9">
                    <div class="field-label">
                        8. 对于课程形式，您的建议：
                    </div>
                    <table cellspacing="0" class="matrix-rating">
                        <tbody>
                        <tr rv="1">
                            <th style="text-align:left;"></th>
                            <td style="text-align:left;">
                                <div class="ui-input-text">
                                    <textarea id="q8" name="q8" rows="5"></textarea>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="field ui-field-contain" id="div7" topic="7" data-role="fieldcontain" type="9">
                    <div class="field-label">
                        9. 请完善您的信息以便后期回访
                    </div>
                    <table cellspacing="0" class="matrix-rating">
                        <tbody>
                        <tr rv="1">
                            <th style="text-align:left;">姓名:</th>
                            <td style="text-align:left;">
                                <div class="ui-input-text">
                                    <input type="text" id="q9_name" name="q9_name" />
                                </div>
                            </td>
                        </tr>
                        <tr rv="1_1" style="height:1em;"></tr>
                        <tr rv="2">
                            <th style="text-align:left;">电话: </th>
                            <td style="text-align:left;">
                                <div class="ui-input-text">
                                    <input type="text" id="q9_phone" name="q9_phone"  />
                                </div></td>
                        </tr>
                        <tr rv="1_1" style="height:1em;"></tr>
                        <tr rv="3">
                            <th style="text-align:left;">地址: </th>
                            <td style="text-align:left;">
                                <div class="ui-input-text">
                                    <input type="text" id="q9_address" name="q9_address" />
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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
    var page_name = '问卷调查页';
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
    var json_config = <?php echo json_encode($package) ?>;
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
                window.location = '{{config('app.url') . '/mobile/index'}}';
                return false;
            }
            var postData = $("#form1").serialize();
            $.ajax({
                type: "POST",
                url: "/mobile/questionnaire/save",
                data: postData,
                success: function (data) {
                    if (data.error) {
                        alert('您还题没有答完哦~');
                    } else {
                        mark = true;
                        alert(data.message);
                    }
                }
            });
        });
    });
</script>
@include('public.statistics')
</body>
</html>