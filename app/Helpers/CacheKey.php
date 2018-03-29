<?php

/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/8/8
 * Time: 上午11:18
 */

//统一记录cache的key值

namespace App\Helpers;

class CacheKey
{
    const HOME_TAGS = 'home_tags';
    const SEARCH_TAGS = 'search_tags';
    const SEARCH_RESULT = 'search';
    const COURSE_HOT = 'courseHot';
    const COURSE_NEW = 'courseNew';
    const CLASS_ACTIVITY = 'class_activity';
    const HOMEPAGE_DATA = 'homepage_data';
    const PLAYLIST_DATA = 'playlist_data';
    const ALL_PAGE_DATA = 'all_page_data';
    const H5_COUNT_USERS = 'h5_count_users';  // h5的累计用户数
    const H5_COUNT_DEVICES = 'h5_count_devices';  // h5的累计设备数
    const S26_CARD_USERS = 's26_card_channel';  // 学霸卡统计
    const XUE_BA_CARD_USERS = 'wyeth_breast_activity_count';  // 母乳卡统计
    const HD_CARD_USERS = 'hd_card_users'; // 活动卡页面统计
    const QUERY_SPRING = 'query_spring';  // 三月孕育指南统计

    const CACHE_KEY_TOKEN = 'crm_access_token';
    const CACHE_KEY_USER_BRAND = 'crm_user_brand';

    //全部页面的金装、启赋和干货
    const CACHE_KEY_ALL_JINZHUANG = 'all_page_jinzhuang';
    const CACHE_KEY_ALL_QIFU = 'all_page_qifu';
    const CACHE_KEY_ALL_GANHUO = 'all_page_ganhuo';

    //发现页面的金装、启赋和干货
    const CACHE_KEY_FIND_JINZHUANG = 'find_page_jinzhuang';
    const CACHE_KEY_FIND_QIFU = 'find_page_qifu';
    const CACHE_KEY_FIND_GANHUO = 'find_page_ganhuo';
    const CACHE_KEY_FIND = 'find_page';

    //CMS的金装、启赋和干货缓存key
    const CACHE_KEY_CMS_JINZHUANG = 'cms_page_jinzhuang';
    const CACHE_KEY_CMS_QIFU = 'cms_page_qifu';
    const CACHE_KEY_CMS_GANHUO = 'cms_page_ganhuo';

    /**
     * WoaapService中的
     */
    const WOAPP_ACKEY = 'woapp_ackey';

    /**
     * WxWyeth中的缓存jsapi_ticket
     */
    const JSAPI_TICKET = 'jsapi_ticket';

    const ACTIVITY_SHARE_RECORD = 'activity_share_log';

}