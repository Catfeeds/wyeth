<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width,user-scalable=no" name="viewport" />
    <link href="<?=config('course.static_url');?>/mobile/css/reset.css?v=<?=$resource_version;?>" rel="styleSheet" type="text/css" />
    <link href="<?=config('course.static_url');?>/mobile/css/category.css?v=<?=$resource_version;?>" rel="styleSheet" type="text/css" />
    <script>
        var STATIC_URL = '<?=config('course.static_url');?>';
    </script>
    <title>妈妈微课堂</title>
</head>
<body>
<div class="searchBlock">
    <div class="searchHead">
        <span class="searchIcon empty" ></span>
        <div class="searchInput">
            <span class="icon"></span>
            <form method="POST" action="/mobile/category/search" id="form">
                <input type="text" id="searchText" name="key" value="<?=$params['key'];?>" placeholder="<?=$params['key'];?>"/>
                <input type="hidden" name="tid" id="tid"/>
                <input type="hidden" name="caid" id="tid"/>
            </form>
            <div class="searchResoures"><?=$params['key'];?><a href="javascript:void(0);"></a></div>
        </div>
        <a href="javascript:void(0);" class='searchBtn'>搜索</a>
    </div>
    <div class="clear"></div>
</div>
<div class="category_result">搜索结果</div>
<div class="lessonList" <?=empty($courses)?'style="display: none;"':'';?> id="thelist">
    <?php if(!empty($courses)):?>
    <?php foreach($courses as $row):?>
        <div class="lessonLineTypeOne">
            <a href="/mobile/<?=($row['status'] == 1) ? 'reg' : 'living';?>?cid=<?=$row['cid'];?>" class="newsCover">
                <img src="<?=$row['img'];?>" alt="" class='newsCover' />
            </a>
            <div class="newsContent">
                <h4><a href="/mobile/living?cid=30"><?=$row['title'];?></a></h4>
                <p><i class="dateIcon"></i><?=$row['start_day'];?>  <?=$row['start_time'];?>-<?=$row['end_time'];?></p>
                <p class="doctor">
                    <span><?=$row['teacher_name'];?> <?=$row['teacher_position'];?></span>
                    <span><?=$row['teacher_hospital'];?></span>
                </p>
                <p><i class="likeIcon"></i><?=$row['hot'];?></p>
                <a href="/mobile/<?=($row['status'] == 1) ? 'reg' : 'living';?>?cid=<?=$row['cid'];?>" class="functionBTN <?=($row['status'] == 1 && $row['is_signed'] == 1) ? 'regend' : (($row['status'] == 2) ? 'regplaying' : 'regstart');?>"></a>
            </div>
        </div>
    <?php endforeach;?>
    <?php endif;?>
    <div class="clear"></div>
</div>
<?php if(empty($courses)):?>
<div class="nomore" style="">
    <p>亲，没有搜索到相关结果。</p>
</div>
<?php endif;?>
<div class="footerLine"></div>
<footer class='fixedMenu'>
    <a href="/mobile/index" class="lesson "></a>
    <a href="/mobile/category" class='discovery current'></a>
    <a href="/mobile/mine" class="mine"></a>
</footer>
<script src="<?=config('course.static_url');?>/mobile/js/jquery-1.8.3.js?v=<?=$resource_version;?>"></script>
<script src="<?=config('course.static_url');?>/mobile/js/iscroll.js?v=<?=$resource_version;?>"></script>
<script src="<?=config('course.static_url');?>/mobile/js/bany.js?v=<?=$resource_version;?>"></script>
<script>
    var token;
    $(function() {
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
            }
        });

        $('#searchText').on('focus', function() {
            $('.searchResoures').hide();
        }).on('blur', function() {
            $('.searchResoures').show();
        })
        $('.searchResoures a').on('click', function() {
            $('.searchResoures').remove();
            $('#searchText').val('');
            //todo 当删除搜索项时 跳转或者重新搜索
        })

        //搜索课程
        $(".searchBtn").on('click',function(){
            var searchKey = $("#searchText").val();
            if(searchKey!=''){
                $("#form").submit();
            }
        });


    });

</script>
<script type="text/tpl">
    <!--^lessonMonth-->
    <div class="lessonMonth lessonMonth{#month}">
        <div class="date date1"></div>
        <div class="number">
            <div class="numerBack">
                {#date}
            </div>
        </div>
    </div>
    <!--lessonMonth$-->
    <!--^newsBlock-->
    <div class="newsBlock">
        <a href="/mobile/{#course|star}?cid={#course.cid}">
            <div class="newsHead">
                <img src="{#course.img}" alt="" class='newsCover' />
                <p class='newsCoverText'>{#course.title}</p>
                <span class="star {#course|star}"></span>
            </div>
        </a>
        <div class="newsContent">
            <p class='newsBorder'>{#teacher.name} <span class="yellow">|</span> {#teacher.hospital}{#teacher.position}</p>
            <p><span class="dateIcon"></span>开课时间：{#course.start_day}  {#course.start_time}-{#course.end_time}</p>
            <p><span class="babyIcon"></span>适龄阶段：{#course.stage}</p>
        </div>
    </div>
    <!--newsBlock$-->
</script>
</body>
</html>