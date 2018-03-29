<aside>
    <div id="sidebar" class="nav-collapse " tabindex="5000" style="overflow: hidden; outline: none;">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <?php foreach ($menu as $v) {
                if (isset($v['subMenu'])) {
                    echo '<li class="sub-menu dcjq-parent-li">
                            <a href="javascript:;" class="dcjq-parent '.$v['active'].'">
                                <i class="fa '.$v['class'].'"></i>
                                <span>'.$v['name'].'</span>
                                <span class="dcjq-icon"></span>
                            </a>
                            <ul class="sub">
                    ';
                    foreach ($v['subMenu'] as $vv) {
                        echo '<li class="'.$vv['active'].'"><a href="'.$vv['href'].'">'.$vv['name'].'</a></li>';
                    }
                    echo    '</ul>
                        </li>';
                } else {
                    echo '<li>
                            <a class="'.$v['active'].'" href="'.$v['href'].'">
                            <i class="fa '.$v['class'].'"></i>
                            <span>'.$v['name'].'</span>
                            </a>
                        </li>';
                }
            } ?>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>