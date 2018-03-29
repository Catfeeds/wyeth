<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseStat extends BaseModel
{
    protected $table = 'course_stat';
    protected $fillable = ['cid', 'uid'];

    const VISITORS_CACHE_TIME = 7200;

    /**
    * 课程总访问次数
    */
    public static function visitors($cid)
    {
        $visitors = static::visitorsName($cid);
//        $value = Cache::remember($visitors, static::VISITORS_CACHE_TIME, function() use($uid, $cid) {
//            return static::where('cid', '=', $cid)->count();
//        };
    }

    public static function upgradeVisitors($cid)
    {
        $visitors = static::visitors($cid);
        $visitorsName = static::visitorsName($cid);
        Cache::put($visitorsName, ++$visitors, static::VISITORS_CACHE_TIME);

    }

    private static function visitorsName($cid)
    {
        return 'visitors_' . $cid;
    }

    public static function boardcastVisitLogs($uid, $cid, $channel)
    {
        $courseStat = static::firstOrCreate(['uid' => $uid, 'cid' => $cid]);
        if ($courseStat) {
            if (empty($courseStat->channel)) {
                $courseStat->channel = $channel;
            }

            $courseStat->in_class_times = $courseStat->in_class_times + 1;
            $courseStat->save();
        }
    }
}
