<?php
namespace App\Services;


class CourseReviewService
{
    /**
     * 课程回顾数获取
     * @param $cid
     * @return bool|int
     */
    public static function countAllGet($cid)
    {
        return CounterService::get(CounterService::TYPE_COURSE_REVIEW, ['all', $cid]);
    }

    /**
     * 课程回顾数加一
     * @param $cid
     * @param int $num
     * @return bool
     */
    public static function countAllIncrement($cid, $num = 1)
    {
        return CounterService::increment(CounterService::TYPE_COURSE_REVIEW, ['all', $cid], $num);
    }
}
