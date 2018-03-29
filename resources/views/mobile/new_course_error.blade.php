<!DOCTYPE html>
<html lang="en" id="html">
<head>
    <meta charset="UTF-8">
    <title>妈妈微课堂</title>
    <meta charset="UTF-8">
    <meta content="telephone=no" name="format-detection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
    @include('public.head')
    <link rel="stylesheet" type="text/css" href="/mobile/review/css/reset.css?v=122">
    <script>
        function adapt(designWidth, rem2px) {
            var d = window.document.createElement('body');
            d.style.width = '1rem';
            d.style.display = "none";
            var head = window.document.getElementsByTagName('head')[0];
            head.appendChild(d);
            var defaultFontSize = parseFloat(window.getComputedStyle(d, null).getPropertyValue('width'));
            d.remove();
            document.documentElement.style.fontSize = window.innerWidth / designWidth * rem2px / defaultFontSize * 100 + '%';
            var st = document.createElement('style');
            var portrait = "@media screen and (min-width: " + window.innerWidth + "px) {html{font-size:" + ((window.innerWidth / (designWidth / rem2px) / defaultFontSize) * 100) + "%;}}";
            var landscape = "@media screen and (min-width: " + window.innerHeight + "px) {html{font-size:" + ((window.innerHeight / (designWidth / rem2px) / defaultFontSize) * 100) + "%;}}"
            st.innerHTML = portrait + landscape;
            head.appendChild(st);
            return defaultFontSize
        }
        ;
        var defaultFontSize = adapt(750, 100);
    </script>
</head>
<body>
<div id="box">
    <div class="title">
        <a href=""><img src="{{ config('course.static_url') }}/mobile/review/img/title_a.png"/></a>
    </div>
    <div id="box_content">
        <ul>
            @foreach($list as $key => $item)
                <li class="href" location="{{ $item['url'] }}">
                    <div class="left_portrait">
                        <img src="{{ $item['teacher_avatar'] && strlen($item['teacher_avatar']) > 0 ? $item['teacher_avatar'].'?imageView2/1/w/55/h/55/q/99':'' }}">
                    </div>
                    <div class="right_test">
                        <h4>{{$item['title']}}</h4>
                        <div>
                            <p><img src="{{ config('course.static_url') }}/mobile/review/img/sprite_t.png"></p>
                            <span>{{$item['start_day']}}  {{$item['start_time']}}</span><br>
                        </div>
                        <div>
                            <p><img src="{{ config('course.static_url') }}/mobile/review/img/sprite_n.png"></p>
                            <span class="teacher-info">{{$item['teacher_name']}}  {{$item['teacher_hospital']}}werewrewrewrwerwerwwe</span><br>
                        </div>
                        <div>
                            <p><img src="{{ config('course.static_url') }}/mobile/review/img/sprite_b.png"></p>
                            <span>{{$item['hot']}}</span><br>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="box_btn">
        <a onclick="_hmt.push(['_trackEvent', 'Error页', '点击更多']);" href="/mobile/index"><img
                    src="{{ config('course.static_url') }}/mobile/review/img/more.png"></a>
    </div>
</div>
<script src="<?=config('course.static_url');?>/mobile/js/jquery-1.8.3.js"></script>

<script>
    $(function () {
        _hmt.push(['_trackPageview', '用户进入回顾报错页', '用户进入回顾报错页']);
        $('.href').on("click", function () {
            var url = $(this).attr('location');
            location.href = url;
        })
    })
</script>
@include('public.statistics')
</body>
</html>