<?php

/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/8/8
 * Time: 上午11:18
 */

//统一记录session的key值

namespace App\Helpers;

class SessionKey
{
    /*
     * 新版用户token
     */
    const USER_TOKEN = 'user_token';

    //新用户
    const NEW_USER = 'new_user';

    /**
     * 灰度测试用户版本 1新版本 -1老版本
     */
    const USER_VERSION = 'user_version';

    /**
     * crm注册回调
     */
    const REGISTER_CRM_REDIRECT = 'register_crm_redirect';

    /**
     * 中台account的token
     */
    const CI_ACCOUNT_TOKEN = 'ci_account_token';

    /**
     * 失败计数
     */
    const ERROR_COUNT = 'error_count';

    /**
     * 用户品牌
     */
    const USER_BRAND = 'user_brand';

    /**
     * 微信临时二维码的参数
     */
    const QRCODE_PARAMS = 'qrcode_params';

    /**
     * 活动aid
     */
    const ACTIVITY_AID = 'activity_aid';
}