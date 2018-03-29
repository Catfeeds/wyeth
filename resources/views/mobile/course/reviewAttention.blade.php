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
    <title><?php echo '惠氏微课堂' ?></title>
    <!--秒针统计-->
    <script type="text/javascript"  src="<?=config('course.mz_url');?>"></script>
    <script type="text/javascript">
        _mwx=window._mwx||{};
        _mwx.siteId=8000330;
        _mwx.openId='<?=Session::get('openid');?>'; //OpenID为微信提供的用户唯一标识,需要开发者传入，如果没有OpenID，去掉该代码即可。
        //            _mwx.debug=true;//代码调试阶段，加入此代码，正式上线之后去掉该代码
    </script>
    <!--秒针统计-->
</head>
<body>
    <!--
    <div class="loading">
        <div class="wrapper">
        <div class="circle">
            <div class="box"></div>
        </div>
        <div class="spinner">
            <div class="rect"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
        <div class="text">加载中</div>
        </div>
    </div>
     -->

    <div class="page">
        <div class="content" style="top:0;bottom:0;overflow:hidden;background:#fedc62;">
            <div style="position:relative;">
                <?php if($open_type==1):?>
                    <img alt="关注我们" src="<?=config('course.static_url');?>/mobile/img/bg_attention.png" style="width:100%;">
                    <a style="position:absolute;top:48.5%;width:46%;left:26%; border:solid 5px #ff8f50;" onclick="_mz_wx_custom(57,'识别二维码');return false;">
                        <img id="qrCode" src="<?=config('course.static_url');?>/mobile/img/bg_code.png" style="width:100%;display:block;"/>
                    </a>
                <?php else:?>
                    <img alt="关注我们" src="<?=config('course.static_url');?>/mobile/img/sq_ba_attention_1.jpg" style="width:100%;">
                    <a style="position:absolute;top:48.5%;/* bottom:10%; */width:46%;left:26%; border:solid 5px #ff8f50;" href="http://file.api.b.qq.com/bl/cgi-bin/bar/extra/jump2pa?uin=1930085473&account_flag=16849209&jumptype=1&card_type=public_account">
                        <img id="qrCode" src="<?=config('course.static_url');?>/mobile/img/sq_attention.jpg" style="width:100%;display:block;"/>
                    </a>
                <?php endif;?>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/jquery-1.8.3.js?v=<?=$resource_version;?>"></script>
    <script type="text/javascript" src="<?=config('course.static_url');?>/mobile/js/page.js?v=<?=$resource_version;?>"></script>
@include('public.statistics')
</body>
</html>
