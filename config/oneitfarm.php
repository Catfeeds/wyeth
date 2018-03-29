<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/6/30
 * Time: 下午3:51
 */

//中台配置文件

return [
    'domain' => env('ONEITFARM_DOMAIN', 'https://oneitfarm.com'),
    'appkey' => env('ONEITFARM_APPKEY', 'jtcj5komwfxrv2qzbts4rlgu7ownzesy'),
    'appsecret' => env('ONEITFARM_APPSECRET', 'i0jpbku13rnfcbkyxc2wmrf4sql9gepy'),
    //是否为惠氏专用
    'is_wyeth' => env('ONEITFARM_IS_WYETH', 1),
    //web_test
    'web_test' => env('ONEITFARM_WEB_TEST', 4),
    //web的config路径
    'web_config_path' => env('ONEITFARM_WEB_CONFIG_PATH', ''),
    //抽奖活动的aid
    'draw_aid' => env('ONEITFARM_DRAW_AID', 406),
    //无主无品牌的抽奖aid
    'draw_aid_no' => env('ONEITFARM_DRAW_AID_NO', 434),
    //首页static的style，用来是否隐藏首页
    'home_style' => env('ONEITFARM_HOME_STYLE', ''),

    //小程序appid和secret
    'mini_appid' => env('ONEITFARM_MINI_APPID'),
    'mini_secret' => env('ONEITFARM_MINI_SECRET'),
    //小程序微信会员卡card_id
    'mini_card_id' => env('ONEITFARM_MINI_CARD_ID', 'pwtN6jtF_VhvHjZtahCKsbz_dDZI')
];