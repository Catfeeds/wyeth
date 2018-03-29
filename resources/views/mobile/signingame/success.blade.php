<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>妈妈微课堂</title>
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache" content="no-cache">
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/signin/css/common.css?v={{$resource_version}}">
    <link rel="stylesheet" href="{{config('course.static_url')}}/mobile/signin/css/yi.min.css?v={{$resource_version}}">
    <style>
        #__bs_notify__ {
            display: none !important;
        }
    </style>
    <!--移动端版本兼容 -->
    <script type="text/javascript" src="{{config('course.static_url')}}/mobile/signin/js/pxrem.js"></script>
    <!--移动端版本兼容 end -->

</head>
<body>
<div class="info-title">
    <div class="cicon icon-info-title"></div>
</div>
<div class="center for-success">
    <div class="cicon icon-ok"></div>
    <h2>您的信息已提交成功</h2>
    <a class="cicon icon-btn-normal" href="/mobile/index">我知道</a>
</div>
<script src="{{config('course.static_url')}}/mobile/signin/js/libs/zepto/zepto.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/libs/seajs/3.0.0/sea.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/config.js"></script>
<script src="{{config('course.static_url')}}/mobile/signin/js/common.js"></script>
@include('mobile.signingame.footer')
</body>
</html>
