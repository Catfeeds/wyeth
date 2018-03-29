<!-- sp -->

@if(isset($user_version) && $user_version != -1)
    <div style="width: 750px;height: 20px;position: fixed;bottom: 100px;background-image: linear-gradient(to top, rgba(0,0,0,0.08), rgba(255,255,255,0));"></div>
    <div style="width: 750px;height: 100px;background-color: white;position: fixed;bottom: 0px;flex-direction: row;align-items:stretch;display: flex">
        <div data="index" class="wyeth-tab-item" href="javascript:void(0)" style="display:flex;flex: 1;flex-direction: column;align-items: center;justify-content: center">
            <img src="<?=config('course.static_url');?>/mobile/images/bany/<?php if($current=='home'){echo 'home';}else{echo 'home2';}?>.png"
                 style="width: 44px;height: 44px"
                 alt=""/>
            <p style="display:flex;margin-top:5px;margin-bottom:0px;align-items: center;justify-content: center;font-size: 20px;
        color: <?php if($current=='home'){echo '#cd9e29';}else{echo '#666666';}?>">
                首页</p>
        </div>

        <div data="all" class="wyeth-tab-item" href="javascript:void(0)" style="display:flex;flex: 1;flex-direction: column;align-items: center;justify-content: center">
            <img src="<?=config('course.static_url');?>/mobile/images/bany/<?php if($current=='all'){echo 'all';}else{echo 'all2';}?>.png"
                 style="width: 44px;height: 44px"
                 alt=""/>
            <p style="display:flex;margin-top:5px;margin-bottom:0px;align-items: center;justify-content: center;font-size: 20px;
        color: <?php if($current=='all'){echo '#cd9e29';}else{echo '#666666';}?>">
                全部</p>
        </div>

        <div data="mine" class="wyeth-tab-item" href="javascript:void(0)" style="display:flex;flex: 1;flex-direction: column;align-items: center;justify-content: center">
            <img src="<?=config('course.static_url');   ?>/mobile/images/bany/<?php if($current=='mine'){echo 'mine';}else{echo 'mine2';}?>.png"
                 style="width: 44px;height: 44px"
                 alt=""/>
            <p style="display:flex;margin-top:5px;margin-bottom:0px;align-items: center;justify-content: center;font-size: 20px;
        color: <?php if($current=='mine'){echo '#cd9e29';}else{echo '#666666';}?>">
                我的</p>
        </div>
    </div>
    <script>
        $('.wyeth-tab-item').on("touchstart",function(){
            var url = $(this).attr('data');
            if(url=='index'){
                location.href = '/mobile/index/'
            }else{
                location.href = "/mobile/" + url;
            }
        })
    </script>
@else
    <nav class="bar bar-tab footer-nav">
        <a class="tab-item @if ($current == 'index') active @endif" href="javascript:void(0)" data="index">
            <span class="icon icon-nav1-1"></span>
            <span class="icon icon-nav1-2"></span>
            <span class="tab-label">精选</span>
        </a>
        <a class="tab-item @if ($current == 'all') active @endif" href="javascript:void(0)" data="all">
            <span class="icon icon-nav2-1"></span>
            <span class="icon icon-nav2-2"></span>
            <span class="tab-label">全部</span>
        </a>
        <a class="tab-item @if ($current == 'mine') active @endif" href="javascript:void(0)" data="mine">
            <span class="icon icon-nav3-1"></span>
            <span class="icon icon-nav3-2"></span>
            <span class="tab-label">我的</span>
        </a>
    </nav>
    <script>
        $('.footer-nav .tab-item').on("touchstart", function () {
            var url = $(this).attr('data');
            location.href = "/mobile/" + url;
        })
    </script>
@endif

