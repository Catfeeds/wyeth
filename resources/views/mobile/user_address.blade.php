<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    @include('public.head')
    <link rel="stylesheet"
          href="{{ config('course.static_url') }}/mobile/css/user_address/demo.css?v={{$resource_version}}"/>
    <link rel="stylesheet"
          href="{{ config('course.static_url') }}/mobile/css/user_address/ydui.css?v={{$resource_version}}"/>
    <script src="{{config('course.static_url')}}/mobile/js/user_address/ydui.flexible.js"></script>
    <title>中奖地址填写</title>
    <style>
        .divider {
            width: 100%;
            height: 1px;
            background: #eee;
        }

        .submit {
            /*position: fixed;*/
            bottom: 0.5rem;
            width: 90%;
            height: 1rem;
            margin: 0.2rem 0 0.2rem 5%;
            text-align: center;
            line-height: 1rem;
            font-size: 0.3rem;
            color: #fff;
            border-radius: 0.1rem;
            background: linear-gradient(to right, #E7C25F, #E4BE5B, #CEA03F, #C69735)
        }
        .notice {
            font-size: 0.25rem;
            padding: 0.1rem 0.3rem;
        }
    </style>
</head>
<body>

<section class="g-flexview" style="padding-top: 10px">
    <section class="g-scrollview">
        <div class="m-cell">
            <div class="cell-item">
                <div class="cell-left"> 收货人 ：</div>
                <div class="cell-right">
                    <input id="user_name" type="text" class="cell-input" placeholder="请填写收货人">
                </div>
            </div>
            <div class="divider"></div>
            <div class="cell-item">
                <div class="cell-left">联系电话：</div>
                <div class="cell-right">
                    <input id="user_phone" type="number" class="cell-input" placeholder="请填写收货人联系电话">
                </div>
            </div>
            <div class="divider"></div>
            <div class="cell-item">
                <div class="cell-left">所在地区：</div>
                <div class="cell-right cell-arrow">
                    <input type="text" class="cell-input" readonly id="user_city" placeholder="请选择收货地区">
                </div>
            </div>
            <div class="divider"></div>
            <div class="cell-item" style="align-items: flex-start; padding-top: 0.2rem">
                <div class="cell-left">详细地址：</div>
                <div class="cell-right">
                    <textarea id="user_address" class="cell-input" placeholder="请填写详细收货地址" style="height: 2rem; padding-top: 0.05rem"></textarea>
                </div>
            </div>
            <div class="divider"></div>
            <div>
                @if($access)
                <div class="submit" id="submit">提交并开始录音</div>
                @else
                <div class="submit">抱歉，您未中奖</div>
                @endif
                <div class="notice">1.中奖通知发出后，请在三天内填写地址和相关身份信息，逾期则视为自动放弃；</div>
                <div class="notice">2.请确保您填写的地址和身份信息真实有效，以免错失奖品；</div>
                <div class="notice">3.请在此页面跳转后录音并选择戒指尺寸；</div>
                <div class="notice">4.因需要一定时间制作，奖品将于20个工作日内发放到您手中，敬请谅解。</div>
            </div>
        </div>
    </section>
</section>

<script src="{{config('course.static_url')}}/mobile/js/user_address/jquery.min.js"></script>
<script src="{{config('course.static_url')}}/mobile/js/user_address/ydui.citys.js"></script>
<script src="{{config('course.static_url')}}/mobile/js/user_address/ydui.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        if ('{{$address}}'.length > 0) {
            $("#user_name").val('{{$address ? $address->name : ""}}');
            $("#user_phone").val('{{$address ? $address->phone : ""}}');
            $("#user_city").val('{{$address ? $address->city : ""}}');
            $("#user_address").val('{{$address ? $address->address : ""}}')
        }
    });
    /**
     * 默认调用
     */
    !function () {
        var $target = $('#user_city');

        $target.citySelect();

        $target.on('click', function (event) {
            event.stopPropagation();
            $target.citySelect('open');
        });

        $target.on('done.ydui.cityselect', function (ret) {
            $(this).val(ret.provance + ' ' + ret.city + ' ' + ret.area);
        });
    }();
    /**
     * 手机号正则验证
     */
    function isPoneAvailable(str) {
        var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
        return myreg.test(str);
    }
    $("#submit").click(function () {
        var name = $("#user_name").val();
        var phone = $("#user_phone").val();
        var city = $("#user_city").val();
        var address = $("#user_address").val();
        if (!name) {
            alert("请填写收货人");
            return
        }
        if (!phone || !isPoneAvailable(phone)) {
            alert("请填写正确手机格式");
            return
        }
        if (!city) {
            alert("请选择所在地区");
            return
        }
        if (!address) {
            alert("请填写详细地址");
            return
        }
        $.ajax({
            url: '/mobile/hd/save',
            type: 'POST',
            dataType: 'json',
            data: {
                name: name,
                phone: phone,
                city: city,
                address: address
            },
            success: function (result) {
                if (result['status'] == 200) {
                    window.location.assign('http://app.malianghang.com/product/soundwave-customize-ring?order_id=null&redirect_uri=m.malianghang.com&thirdparty_id=huishi')
                } else {
                    alert('fail');
                }
            },
            error: function (error) {
                alert('network error')
            }
        });
        console.log('submit')
    });
</script>
</body>