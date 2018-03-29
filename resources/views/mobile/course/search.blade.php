<!DOCTYPE html>
<html lang="en" id="html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"/>
    <title>搜索</title>
    <link rel="stylesheet" type="text/css" href="{{config('course.static_url')}}/mobile/css/search/reset.css">
    <link rel="stylesheet" type="text/css" href="{{config('course.static_url')}}/mobile/css/search/search.css">
</head>
<body>
<div id="searchBox" style="min-height:48px;">
    <div style="top: 0.258rem;">
        <a href="javascript:;"><img src="{{config('course.static_url')}}/mobile/img/review/searchIcon.png"></a>
        <input type="text" name="keyword" value="{{$keyword ? $keyword : ''}}" placeholder="找找你感兴趣的内容 如:腹泻"
               hw-data="腹泻"/>
    </div>
    <ul>
        <!-- 后退 -->
        <li class="href" location="/mobile/index"></li>
        <!-- 搜索 -->
        <li class="do-search"></li>
    </ul>
</div>
<div id="content">
    @if ($count > 0)
        <div class="result">
            <ul>
                @foreach($list as $key => $item)
                    <li hw-data="{{$item['id']}}" hw-type="1" hw-status="{{$item['status']}}">
                        <div class="result_left">
                            <img src="{{strlen($item['img']) > 0 ? $item['img'].'?imageView2/1/w/116/h/99/q/99' : '/mobile/img/review/tr.png'}}">
                        </div>
                        <div class="result_right">
                            <p>{{$item['title']}}<br><span>{{$item['teacher_name']}}<i>{{$item['teacher_hospital']}}</i></span>
                            </p>
                            <div class="result_right_botton">
                                <a href="javascript:;" class="fl"><img src="/mobile/img/review/x.png"></a>
                                <i>{{$item['hot']}}</i>
                                @if ($item['status'] == 1)
                                    <a href="javascript:;" class="fr" hw-data="{{$item['status']}}"><img
                                                src="{{config('course.static_url')}}/mobile/img/review/enrolled.png"></a>
                                @elseif($item['status'] == 2)
                                    <a href="javascript:;" class="fr" hw-data="{{$item['status']}}"><img
                                                src="{{config('course.static_url')}}/mobile/img/review/broadcast.png"></a>
                                @else
                                    <a href="javascript:;" class="fr" hw-data="{{$item['status']}}"><img
                                                src="{{config('course.static_url')}}/mobile/img/review/review.png"></a>
                                @endif

                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="gray_bg"></div>
    @else

        <div class="notFound">
            <img src="{{config('course.static_url')}}/mobile/img/review/notFound.png">
        </div>
    @endif

    <div class="recommend">
        <p><img src="{{config('course.static_url')}}/mobile/img/review/recommend.png"></p>
        <div class="result">
            <ul>
                @foreach($recomm as $item)
                    <li hw-data="{{$item['id']}}" hw-type="2" hw-status="{{$item['status']}}">
                        <div class="result_left">
                            <img src="{{strlen($item['img']) > 0 ? $item['img'].'?imageView2/1/w/116/h/99/q/99' : '/mobile/img/review/tr.png'}}">
                        </div>
                        <div class="result_right">
                            <p>{{$item['title']}}<br><span>{{$item['teacher_name']}}<i>{{$item['teacher_hospital']}}</i></span>
                            </p>
                            <div class="result_right_botton">
                                <a href="" class="fl"><img
                                            src="{{config('course.static_url')}}/mobile/img/review/x.png"></a>
                                <i>{{$item['hot']}}</i>
                                @if ($item['status'] == 1)
                                    <a href="javascript:;" class="fr" hw-data="{{$item['status']}}"><img
                                                src="{{config('course.static_url')}}/mobile/img/review/enrolled.png"></a>
                                @elseif($item['status'] == 2)
                                    <a href="javascript:;" class="fr" hw-data="{{$item['status']}}"><img
                                                src="{{config('course.static_url')}}/mobile/img/review/broadcast.png"></a>
                                @else
                                    <a href="javascript:;" class="fr" hw-data="{{$item['status']}}"><img
                                                src="{{config('course.static_url')}}/mobile/img/review/review.png"></a>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="viewAll">
        <a href="/mobile/all"><img src="/mobile/img/review/viewAll.png"></a>
    </div>
</div>
</body>
<script type="text/javascript" src="{{config('course.static_url')}}/mobile/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">
    var html = document.getElementById('html');
    html.style.fontSize = window.innerWidth / 10 + "px";

    $(function () {
        $('.result ul li').click(function () {
            var click_type = $(this).attr('hw-type');
            var click_id = $(this).attr('hw-data');
            var status = $(this).attr('hw-status');
            var sid = "{{$sid}}";
            //去更新
            goChangeRecordInfo(sid, status, click_id, click_type);
        });
        $('.do-search').click(function () {
            var keyword = $('input[name=keyword]').val();
            if (keyword.length == 0) {
                keyword = $('input[name=keyword]').attr('hw-data');
            }
            keyword = encodeURIComponent(keyword);

            location.href = '/mobile/search?keyword=' + keyword;
        });
        $('.href').on("click", function () {
            var url = $(this).attr('location');
            location.href = url;
        })
    });

    function goChangeRecordInfo(sid, status, click_id, click_type) {
        if (status == 1) {
            var url = '/mobile/reg?cid=' + click_id;
        } else if (status == 2) {
            //alert(click_id);
             if(click_id==381){
                 var url = 'http://mudu.tv/?c=activity&a=live&id=39250';
             }
             else
             {
                var url = '/mobile/living?cid=' + click_id;
            }
            //var url = '/mobile/living?cid=' + click_id;
        } else {
            var url = '/mobile/end?cid=' + click_id;
        }
        if (sid.length == 0) {
            location.href = url;
            return false;
        }
        $.ajax({
            url: '/mobile/search/update',
            type: 'POST',
            dataType: 'json',
            data: {
                sid: sid,
                click_id: click_id,
                click_type: click_type
            },
            success: function (result) {
                //alert(url);
                location.href = url;
            }
        })
    }
</script>
</html>