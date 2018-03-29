<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width,user-scalable=no" name="viewport" />
        <meta name="format-detection" content="telephone=no" />
        @include('public.head')
        <style type="text/css">
            body, div, span,object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, address, cite, code, del, dfn, em, img, ins, kbd, q, samp, small, strong, sub, sup, var, b, i, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td {
                    border: 0 none;
                    font-size: 100%;
                    margin: 0;
                    padding: 0;
            }
            body{background: #fff0bb;}
            .page{width:100%; height:100%;}
            .page img{width:100%; height:100%;}
            .page a{width:79.066667%; height:8.699254%; top:80%; left:10%; position:absolute; background: url({{config('course.static_url')}}/mobile/img/no_review_btn.png) no-repeat; background-size:100% auto; -webkit-tap-highlight-color:rgba(0,0,0,0);}
            .page .p1{color:#fff; font-size:1.4em; font-weight:bold; position:absolute; top:25%; left:8%;}
            .page .p2{color:#fff; font-size:1.4em; font-weight:bold; position:absolute; top:31%; left:8%;}
            @media screen and (max-width:320px){
                .page a{background-size: 100% 100%;}
                .page .p1{top:7em; font-size:1.2em;}
                .page .p2{top:8.5em; font-size:1.2em;}
            }
        </style>
        <title>妈妈微课堂</title>
    </head>
    <body>
        @include('public.statistics')
        <div class="page">
            <img src="{{config('course.static_url')}}/mobile/img/no_review_bg.png">
            <p class="p1">亲爱的妈妈:</p>
            <p class="p2">{{ $message }}</p>
            <a onclick="_hmt.push(['_trackEvent', 'Error页', '点击更多']);" href="/mobile/index"></a>
        </div>
        <script>_hmt.push(['_trackPageview', '/mobile/error']);</script>
    </body>
</html>
