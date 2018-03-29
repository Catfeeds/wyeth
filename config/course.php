<?php

$test_openids = [
    'owtN6jtt3OXlW_U8By9hwnGqTQ5w', //戴杰
    'owtN6jixHcoptlE1x8VzTA1iovxs', //阮涛
    'owtN6jhI6xiP86geJRfVd_YFipTA', //刘宁
    'owtN6jupa12XF6MQizhSXn0evjq8', //乔巴
    'owtN6jieq018PJk98LiCe2ewwzhQ', //刘佳佳
    'owtN6jgpE3kRvT-zMpbpCLvoIIFA', //胡雯
    'owtN6jsH2B4gmGKsyR5GvrjTrZwo', //陈晓妍
];

$signature_keys = [
    'heywow2015',
    'heywow2016',//大神活动
    'heywow201602',//新妈训练营
    'heywow201603',//新活动
    'hongbao2016',//红包
    'heywow201604', //智惠新妈训练营
    'yjt201603', //易健通问题回答
];

/*
 * $QRcode array
 * 功能：课程详情页+课程回顾页，替换指定课程的微信群二维码
 * 键：string 课程cid
 * 值：string 二维码图片url
 */
$QRcode = [
    // for dev
    '60' => 'http://7xp1g4.com1.z0.glb.clouddn.com/course/qr/qrcode-yummy.jpg',
    // for online
    '133' => 'http://7xp1g4.com1.z0.glb.clouddn.com/course/qr/qrcode-yummy.jpg',
];

return [
    'QRcode' => $QRcode,
    'test_openids' => $test_openids,
    'test_course_ids' => [
        40,
    ],
    'static_url' => env('STATIC_URL', 'http://7xp1g4.com1.z0.glb.clouddn.com'),
    // 秒针统计js
    'mz_url' => env('MZ_URL', 'http://js.miaozhen.com/wx.1.0.js'),
    'sign_course_ids' => [36, 37, 39],//猴年活动批量报名课程
    'hounian_end_day' => '2016-01-01',//猴年活动结束时间
    'signature_keys'=>$signature_keys,
    'chat_domain' => env('CHAT_DOMAIN', '112.124.123.97'),
    'chat_channel' => env('CHAT_CHANNEL', 'CHAT_DEFAULT'),
    'chat_register_address' => env('CHAT_REGISTER_ADDRESS', '127.0.0.1'),

    'reply_notify_word' => '讲师正在赶来的途中',
    'baidu_tongji_key' => env('RECORD_BAIDU_KEY', '44bb97d1b947ae49384ed0efd437874b'),   // 百度统计key
    // 奥点云配置
    'aodianyun_app_id' => env('AODIANYUN_APP_ID', 'jimmy_test1'),
    'aodianyun_access_id' => env('AODIANYUN_ACCESS_ID', '741665782372'),
    'aodianyun_access_key' => env('AODIANYUN_ACCESS_KEY', 'InDQ5783888AGcStF7XY3E9f8ypVIIfw'),
    'aodianyun_addr' => env('AODIANYUN_ADDR', 'rtmp://11230.lsspublish.aodianyun.com/jimmy_test1'),
    'aodianyun_stream_pre' => env('AODIANYUN_STREAM_PRE', 'stream_wyeth_'),
    'aodianyun_play_url' => env('AODIANYUN_PLAY_URL', 'http://11230.hlsplay.aodianyun.com/jimmy_test1'),

    // 腾讯云配置
    'qcloud_bizid' => env('QCLOUD_BIZID', ''),
    'qcloud_key' => env('QCLOUD_KEY', ''),
    'qcloud_secret_id' => env('QCLOUD_SECRET_ID', ''),
    'qcloud_secret_key' => env('QCLOUD_SECRET_KEY', ''),
    'qcloud_domain_vod_api' => env('QCLOUD_DOMAIN_VOD_API', 'vod.api.qcloud.com'),

    //crm注册地址
    'register_crm' => 'http://wyeth.woaap.com/memberCard/userCenter/user_center?type=2&channelType=28',
];