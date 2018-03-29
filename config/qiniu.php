<?php
/**
 * 七牛上传配置
 */
//return [
//    'accessKey'=>'yTvkBQ0puBm2biSoDzPVzbqW-4te1V9sRCiXB6Mi',
//    'secretKey'=>'GlewQtY73jtL85Rf8zZjmYQ1Bm6U9BNX_8ZkJhv8',
//    'bucket'=>'uploadsites',//上传空间名称
//    'domain'=>'http://wyethup.img.apicase.io', //空间域名
//    'prefix' => env('QINIU_PREFIX', 'wyethcourse/'),  // 空间路径前缀
//];

return [
    'accessKey'=>'2y9GnYh6aBPTS3bfTnAWELrEnjh87_W7azNJk6-p',
    'secretKey'=>'aX0oNPlE_R_pf1_osjx6NC9eLxrswA_A1Mi8-rSf',
    'bucket'=>'uploadsites',//上传空间名称
    'domain'=>env('QINIIU_UPLOAD_URL'), //空间域名
    'prefix' => env('QINIU_PREFIX', 'wyethcourse/'),  // 空间路径前缀
];