<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>魔栗妈咪学院</title>
<meta name="description" content="fenlibao" />
<meta name="format-detection" content="telephone=no">
@include('public.head')
<link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/common.css">
<link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/yi.min_temp.css?v=<?=$resource_version;?>">
<link rel="stylesheet" href="{{ config('course.static_url') }}/mobile/v2/css/dropload.css">
<style>
#__bs_notify__{display: none!important;}
    #stage_tags a {  width: 121px;  }
    #stage_tags a span {  width: 112px;  }
</style>
<!--移动端版本兼容 -->
<script type="text/javascript" src="{{ config('course.static_url') }}/mobile/v2//js/pxrem.js"></script>
<!--移动端版本兼容 end -->

</head>
<body>
<style type="text/css">
.tag_show{
    display:none;
}
</style>
<!-- new hot review and tags -->
<div class="wrap for-search">
    <div class="hd">
        <div class="segmented-control">
            <div class="bg"></div>
            <a class="control-item @if ($type == 'new') active @endif" href="###" hw_type='new'><span>最新</span></a>
            <a class="control-item @if ($type == 'hot') active @endif" href="###"  hw_type='hot'><span>推荐</span></a>
            <a class="control-item @if ($type == 'review') active @endif" href="###"  hw_type='review'><span>只看回顾</span></a>
        </div>
    </div>
    <div class="bd">
        <div class="list-link" id="stage_tags">
          <div class="list-link-title">适合阶段</div>
          {{--<a href="###" @if ($stage == 0) class="active"  @endif hw_stage=''><span>全部</span></a>--}}
          {{--<a href="###" @if ($stage == 1) class="active"  @endif hw_stage='1'><span>孕早期</span></a>--}}
          {{--<a href="###" @if ($stage == 2) class="active"  @endif hw_stage='2'><span>孕中晚期</span></a>--}}
          {{--<a href="###" @if ($stage == 3) class="active"  @endif hw_stage='3'><span>新手妈咪</span></a>--}}
            <a href="###" @if ($stage == 0) class="active"  @endif hw_stage=''><span>全部</span></a>
            <a href="###" @if ($stage == 1) class="active"  @endif hw_stage='1'><span>孕期</span></a>
            <a href="###" @if ($stage == 2) class="active"  @endif hw_stage='2'><span>0-12月</span></a>
            <a href="###" @if ($stage == 3) class="active"  @endif hw_stage='3'><span>12-24月</span></a>
            <a href="###" @if ($stage == 4) class="active"  @endif hw_stage='4'><span>24+月</span></a>
        </div>
        <div class="list-link">
            <div class="list-link-title">热门标签</div>

            <div style="height:140px;">
                <div hw_tag_list class="tags @if ($type == 'review') tag_show @endif">
                    <a href="#" @if (!$tagId) class="active" @endif hw_tag='' ><span>不限</span></a>
                    @foreach ($tags as $tag)
                    <a href="#" @if ($tagId == $tag['id']) class="active" @endif hw_tag="{{$tag['id']}}"><span>{{$tag['name']}}</span></a>
                    @endforeach
                </div>
                <div hw_tag_list_review class="tagsReview @if ($type != 'review') tag_show @endif">
                    <a href="#" @if (!$tagId) class="active" @endif hw_tag='' ><span>不限</span></a>
                    @foreach ($tagsReview as $tag)
                    <a href="#" @if ($tagId == $tag['id']) class="active" @endif hw_tag="{{$tag['id']}}"><span>{{$tag['name']}}</span></a>
                    @endforeach
                </div>
            </div>

        </div>
  </div>
</div>

<!-- lists -->
<div id="courseArea">
    <ul class="table-view for-good-list" id="courseList">
        @foreach ($contents as $row)
            <li class="table-view-cell media" hw_item="{{$row['cid']}}">
                <a class="navigate-right" href="{{$row['url']}}">
                    <div class="pic-left">
                        <img class="media-object pull-left" src="{{$row['img']}}">
                    </div>
                    <div class="media-body">
                        <div class="media-right">
                            <i class="icon icon-status0{{$row['status']}}"></i>
                        </div>
                        <h3>{{$row['title']}}</h3>
                        <p class="item"><i class="icon icon-calendar"></i>{{$row['start_day']}} {{$row['start_time']}}</p>
                        <p class="item"><i class="icon icon-user"></i>{{$row['teacher_name']}} {{$row['teacher_hospital']}}</p>
                        <p class="item"><i class="icon icon-heart"></i>{{$row['hot']}}</p>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>
<div class="pb120 bottom-tips" hw_content>
  <i class="icon icon-round"></i>“魔栗妈咪学院”版权归属景栗科技所有，相关课程内容由景栗科技提供。平台相关内容不作76为医学诊断参考，如情况严重，建议及时就医。
</div>

<script src="{{config('course.static_url')}}/js/jquery.min.js"></script>
<!-- foot -->
@include('mobile.course.menu', ['current' => 'all'])

<script src="{{$su}}/js/lodash.min.js"></script>
@include('mobile.share')

<!-- sp -->

<script src="{{config('course.static_url')}}/mobile/v2/js/dropload.js"></script>
<script src="{{config('course.static_url')}}/mobile/v2/js/list.js?v=<?=$resource_version;?>"></script>
<script>
var tag = '{{$tagId}}';
var type = '{{$type}}';
var page = '{{$page}}';
var stage = '{{$stage}}';
var tagName = '{{$tagName}}';
$(function () {
    var options = {
        page: page,
        tag: tag,
        type: type,
        stage: stage,
        tagName: tagName
    };
    AppList.init(options);
});

$(function(){
    var bool=false;
    setTimeout(function(){
        bool=true;
    },1500);
    window.addEventListener("popstate", function(e) {
        if(bool)
        {
            history.go(-1);
        }
    }, false);
});
</script>
@include('public.statistics')
</body>
</html>
