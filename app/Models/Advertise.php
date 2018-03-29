<?php

namespace App\Models;


use App\Helpers\WyethUtil;
use App\Services\Crm;
use Illuminate\Database\Eloquent\Model;

class Advertise extends Model
{
    protected $table = 'advertise';

    const ID_JINZHUANG = 4;
    const ID_QIFU = 10;
    const ID_GANHUO = 7;

    const T_WUZHU = 1;
    const T_QIFU = 2;
    const T_JINZHUANG = 3;

    const POSITION_INDEX_TOP = 1;
    const POSITION_INDEX_MID = 2;
    const POSITION_COURSE_MID = 3;
    const POSITION_COURSE_BOTTOM = 4;
    const POSITION_DISCOVERY = 5;
    const POSITION_MINE = 6;
    const POSITION_MQ_RULE = 7;
    const POSITION_MAY_LIKE = 8;
    const POSITION_INVITE_CARD = 9;
    const POSITION_DYNAMIC = 10;

    const POSITION_T = [
        self::POSITION_INDEX_TOP => '首页顶部',
        self:: POSITION_INDEX_MID => '首页中部',
        self:: POSITION_COURSE_MID => '课程中部',
        self:: POSITION_COURSE_BOTTOM => '课程底部',
        self:: POSITION_DISCOVERY => '发现页',
        self:: POSITION_MINE => '我的页',
        self:: POSITION_MQ_RULE => 'MQ规则页',
        self:: POSITION_MAY_LIKE => '分享成功页',
        self:: POSITION_INVITE_CARD => '邀请卡底部',
        self:: POSITION_DYNAMIC => '动态页'
    ];

    /**
     * @param int $position 广告展示位置
     * @param int $course_brand_id 课程所属品牌ID
     * @return array
     */
    public static function getAdvertise($position, $course_brand_id = 0) {
        $crm = new Crm();
        $bid = $crm->getMemberBrand();

        $version = 0;
        $v = CiAppConfig::where('module', 'de_index')->where('key', 'ci_advertise_version')->get()->pluck('data')->toArray();
        if (count($v) > 0) {
            $version = $v[0];
        }

        $ads = static::where('position', $position)->where('display', 1)->where('version', $version);
        if ($position == self::POSITION_COURSE_MID || $position == self::POSITION_COURSE_BOTTOM || $position == self::POSITION_MAY_LIKE || $position == self::POSITION_INVITE_CARD) {
            if ($course_brand_id == 4 || $course_brand_id == 5 || $course_brand_id == 8) {
                $ads = $ads->where('type', static::T_JINZHUANG)->orderBy('order')->get();
            } else if ($course_brand_id == 10 || $course_brand_id == 11 || $course_brand_id == 12) {
                $ads = $ads->where('type', static::T_QIFU)->orderBy('order')->get();
            } else {
                $ads = $ads->where('type', static::T_WUZHU)->orderBy('order')->get();
            }
        } else {
            if ($bid == self::ID_JINZHUANG) {
                $ads = $ads->where('type', static::T_JINZHUANG)->orderBy('order')->get();
            } else if ($bid == self::ID_QIFU) {
                $ads = $ads->where('type', static::T_QIFU)->orderBy('order')->get();
            } else {
                if ($position == self::POSITION_INDEX_TOP || $position == self::POSITION_INDEX_MID) {
                    if (date("w") % 2 == 0) {
                        $ads = $ads->where('type', static::T_WUZHU)->orderBy('order', 'desc')->get();
                    } else {
                        $ads = $ads->where('type', static::T_WUZHU)->orderBy('order', 'asc')->get();
                    }
                } else {
                    $ads = $ads->where('type', static::T_WUZHU)->orderBy('order')->get();
                }
            }
        }

        foreach ($ads as $ad) {
            $ad->link = WyethUtil::getParamsLink($ad->link, 'server_advertise', ['subject' => $ad->subject, 'position' => self::POSITION_T[$position], 'youzhu' => $bid ? 1 : 0, 'brand' => self::getBrandName($bid), 'order' => $ad->order]);
        }

        $r = $ads->toArray();
//        if (count($r) > 0 && ($position == self::POSITION_MINE || $position == self::POSITION_MQ_RULE)) {
//            $ret[] = $r[(date("w")) % count($r)];
//        } else {
        $ret = $r;
//        }

        $not_need = [];
        foreach ($ret as $item) {
//            if ($item['need_trans']) { // 需要位置内轮换
//                $need_int = count(array_search($item['need_trans'], array_column($ret, 'need_trans')));
//                if ($position == static::POSITION_INDEX_TOP) {
//                    if ($bid) { // 有主
//                        if ($item['need_trans'] % $need_int == floor(floor(time() / 86400 + 3) / 7) % $need_int) {
//                            $not_need[] = $item;
//                        }
//                    } else {
//                        if ($item['need_trans'] % $need_int == floor(floor(time() / 86400 + 1) / 7) % $need_int) {
//                            $not_need[] = $item;
//                        }
//                    }
//                } else {
//                    if ($item['need_trans'] % $need_int == date("w") % $need_int) {
//                        $not_need[] = $item;
//                    }
            if ($item['need_trans']) {
                if ($item['need_trans'] % 2 == date("w") % 2) {
                    $not_need[] = $item;
                }
            } else {
                $not_need[] = $item;
            }
        }

//        usort($need, "self::needTransSort");
//        for ($i = ((date("w") + 1) % 2) * (count($need) / 2); $i <  ((date("w") + 1) % 2 + 1) * (count($need) / 2); $i++) {
//            $not_need[] = $need[$i];
//        }
        return $not_need;
    }

    private static function getBrandName ($bid) {
        if (!$bid) {
            return '无主';
        } else {
            $brand = Brand::find($bid);
            return $brand->name;
        }
    }

    private static function needTransSort($a,$b)
    {
        if ($a['need_trans'] == $b['need_trans']) return 0;
        return ($a['need_trans'] < $b['need_trans']) ? -1 : 1;
    }

    private static function orderSort($a, $b) {
        if ($a['need_trans'] == $b['need_trans']) return 0;
        return ($a['need_trans'] < $b['need_trans']) ? -1 : 1;
    }
}