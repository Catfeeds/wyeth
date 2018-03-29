<?php
namespace App\Services;

use Cache;

class CounterService
{
    /**
     * 课程报名计数器
     */
    const TYPE_COURSE_REG = 'courseReg';

    /**
     * 套课报名计数器
     */
    const TYPE_COURSE_CAT_REG = 'courseCatReg';

    /**
     * 课程回顾计数器
     */
    const TYPE_COURSE_REVIEW = 'courseReview';

    /**
     * @param $type 计数器类型
     * @param $params 可以为字符串，也可以为数组
     * @param int $num 增加数目，不填默认加1
     * @return bool
     */
    public static function increment($type, $params, $num = 1)
    {
        $key = self::getKey($params);
        if (!$key) {
            return false;
        }
        $num = intval($num);
        if ($num <= 0) {
            return false;
        }
        Cache::tags(['services', 'counter', $type])->increment($key, $num);
        return true;
    }

    /**
     * @param $type 计数器类型
     * @param $params 可以为字符串，也可以为数组
     * @param int $num 减少数目，不填默认减1
     * @return bool
     */
    public static function decrement($type, $params, $num = 1)
    {
        $key = self::getKey($params);
        if (!$key) {
            return false;
        }
        $num = intval($num);
        if ($num <= 0) {
            return false;
        }
        Cache::tags(['services', 'counter', $type])->decrement($key, $num);
        return true;
    }

    /**
     * 获取数
     *
     * @param $type 见类常量
     * @param $params   参数
     * @return bool|integer
     */
    public static function get($type, $params)
    {
        $key = self::getKey($params);
        if (!$key) {
            return false;
        }
        $count = Cache::tags(['services', 'counter', $type])->get($key);
        if (!$count) {
            $count = 0;
        }
        return $count;
    }

    /**
     * 初始化或重置
     *
     * @param $type 见类常量
     * @param array|string $params 参数
     * @param integer $num 数量
     * @return bool|void
     */
    public static function put($type, $params, $num = 0)
    {
        $key = self::getKey($params);
        if (!$key) {
            return false;
        }
        $num = intval($num);
        return Cache::tags(['services', 'counter', $type])->forever($key, $num);
    }

    /**
     * 获取redis key
     *
     * @param $params
     * @return bool|string
     */
    private static function getKey($params)
    {
        $key = '';
        if (is_array($params)) {
            $key = implode(':', $params);
        } else if (is_string($params)) {
            $key = $params;
        } else {
            return false;
        }
        return md5($key);
    }

    public static function courseRegAllGet($cid)
    {
        return self::get(self::TYPE_COURSE_REG, ['all', $cid]);
    }

    public static function courseRegAllIncrement($cid, $num = 1)
    {
        return self::increment(self::TYPE_COURSE_REG, ['all', $cid], $num);
    }

    public static function courseRegDayGet($cid, $day)
    {
        return self::get(self::TYPE_COURSE_REG, ['day', $cid, $day]);
    }

    public static function courseRegDayIncrement($cid, $day, $num = 1)
    {
        return self::increment(self::TYPE_COURSE_REG, ['day', $cid, $day], $num);
    }

    /**
     * 套课报名获取
     * @param $cid
     * @return bool|int
     */
    public static function courseCatRegAllGet($cid)
    {
        return self::get(self::TYPE_COURSE_CAT_REG, ['all', $cid]);
    }

    /**
     * 套课报名加一
     * @param $cid
     * @param int $num
     * @return bool
     */
    public static function courseCatRegAllIncrement($cid, $num = 1)
    {
        return self::increment(self::TYPE_COURSE_CAT_REG, ['all', $cid], $num);
    }

}
