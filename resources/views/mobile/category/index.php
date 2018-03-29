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
<body class="">
<header class='lessonTop'>
    <div class="scroll_img" id='scroll_img'>
        <div class=''>
            <?php if(!empty($banner)):?>
            <?php foreach ($banner as $k => $b):?>
            <a href='<?=$b->link;?>'><img src="<?=$b->img;?>" <?=$k==0?'class="current"':'';?> alt=""/></a>
            <?php endforeach;?>
            <?php endif;?>
        </div>
    </div>

    <div class="clear"></div>
</header>
<div class="searchBlock searchBlock1">
    <div class="searchHead">
        <a class="searchIcon" href="javascript:void(0);"></a>
        <div class="searchInput">
            <span class="icon"></span>
            <form method="POST" action="/mobile/category/search" id="form1">
                <input type="text" id="searchText" name="key" placeholder="搜一搜感兴趣的内容"/>
                <input type="hidden" name="tid" id="tid"/>
                <input type="hidden" name="caid" id="tid"/>
            </form>
        </div>
        <a href="javascript:void(0);" class='searchBtn form1'>搜索</a>
    </div>
    <div class="searchTips">
        <?php if(!empty($tags)):?>
        <?php foreach ($tags as $tag):?>
        <a class="searchTipsLink" href="javascript:void(0);" tid="<?=$tag->tid;?>"><?=$tag->name;?></a>
        <?php endforeach;?>
        <?php endif;?>
    </div>
    <div class="clear"></div>
</div>
<div class="searchBlock searchBlock2" style="display: none;">
    <div class="searchHead">
        <span class="searchIcon back" ></span>
        <div class="searchInput">
            <span class="icon"></span>
            <form method="POST" action="/mobile/category/search" id="form2">
                <input type="text" name="key" id="searchText2" value="" placeholder="搜一搜感兴趣的内容"/>
                <input type="hidden" name="tid" id="tid"/>
                <input type="hidden" name="caid" id="tid"/>
            </form>
        </div>
        <a href="javascript:void(0);" class='searchBtn form2'>搜索</a>
    </div>
    <div class="clear"></div>
</div>
<?php if(!empty($recommend_courses)):?>
<div class="subtitles">
<!--    <a href="#">更多&gt;&gt; </a>-->
</div>
<div class="lessonList" id="thelist">
    <?php foreach($recommend_courses as $row):?>
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
    <div class="clear"></div>
</div>
<?php endif;?>
<?php if(!empty($cate_list)):?>
<div class="categoryList" style="display: none;">
    <div class="leftpart">
        <?php foreach($cate_list as $c):?>
        <a href="javascript:void(0);" class="current" caid="<?=$c['caid'];?>"><?=$c['name'];?></a>
        <?php endforeach;?>
    </div>

    <div class="rightpart">
        <?php foreach($cate_list as $row):?>
        <div class="rightpartMain">
            <?php if(!empty($row['child'])):?>
            <?php foreach($row['child'] as $ch):?>
            <h4 caid="<?=$ch['caid'];?>"><?=$ch['name'];?></h4>
                <?php if(!empty($ch['child'])):?>
                <?php foreach($ch['child'] as $c):?>
                <a href="javascript:void(0);" caid="<?=$c['caid'];?>"><?=$c['name'];?></a>
                <?php endforeach;?>
                <?php endif;?>
            <?php endforeach;?>
            <?php endif;?>
        </div>
        <?php endforeach;?>
    </div>
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
<script src="<?=config('course.static_url');?>/mobile/js/hhSwipe.js?v=<?=$resource_version;?>"></script>
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

        $('.searchIcon').on('click',function(){
            $('.searchBlock1').hide();
            $('.searchBlock2').show();
            $('.categoryList').show();
            $('body').addClass('searching');
        })
        $('.searchIcon.back').on('click',function(){
            $('.searchBlock1').show();
            $('.searchBlock2').hide();
            $('.categoryList').hide();
            $('body').removeClass('searching');
        })
        $('.leftpart a').on('click',function(){
            var index = $(this).index();
            $('.rightpartMain').hide().eq(index).show();
            $('.leftpart .current').removeClass('current');
            $(this).addClass('current');
        })
        var slider = Swipe(document.getElementById('scroll_img'), {
            auto: 3000,
            continuous: true,
            callback: function(pos) {
            }
        });

        //搜索课程
        $(".searchTipsLink").on('click',function(){
            var tid = $(this).attr('tid');
            var key = $(this).html();
            $("#searchText").val(key);
            $("#tid").val(tid);
        });
        $(".searchBtn.form1").on('click',function(){
            var searchKey = $("#searchText").val();
            if(searchKey!=''){
                $("#form1").submit();
            }
        });

        $(".rightpartMain a").on('click',function(){
            var caid = $(this).attr('caid');
            var key = $(this).html();console.log(caid+key);
            $("#searchText2").val(key);
            $("#caid").val(caid);
        });
        $(".searchBtn.form2").on('click',function(){
            var searchKey = $("#searchText2").val();
            if(searchKey!=''){
                $("#form2").submit();
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