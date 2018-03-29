<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserCourse extends BaseModel
{
    protected $table = 'user_course';

    protected $fillable = ['cid', 'uid'];

    //查询用户上课信息
    static function getListenTime($uid)
    {
        $rank = 1;
        $model = new UserFriend();
        $friends = $model->where(
            function ($query) use ($uid) {
                $query->where('from_uid', $uid)
                    ->orwhere('to_uid', $uid);
            })
            ->select('from_uid', 'to_uid')
            ->get()
            ->toArray();

        $uids = [];
        if ($friends) {
            foreach ($friends as $k) {
                $uids[] = $k['from_uid'];
                $uids[] = $k['to_uid'];
            }

            //查询好友报名次数
            $listen_nums = UserCourse::whereIn('uid', array_unique($uids))
                ->select(DB::raw('uid,count(*) as nums'))
                ->groupBy('uid')
                ->get()
                ->toArray();
            if ($listen_nums) {
                foreach ($listen_nums as $row) {
                    $id = $row['uid'];
                    $temp_arr[$id][] = $row['nums'];
                }

                //查询好友听课时长
                $listen_times = UserCourse::whereIn('uid', array_unique($uids))
                    ->select(DB::raw('uid,SUM(listen_time) as times'))
                    ->groupBy('uid')
                    ->get()
                    ->toArray();
                if ($listen_times) {
                    foreach ($listen_times as $row) {
                        $id = $row['uid'];
                        $listen_num = isset($temp_arr[$uid]) ? $temp_arr[$uid][0] : 0;
                        $temp[$id][] = $row['times'] + $listen_num*200;
                    }

                    arsort($temp);
                    $i=1;
                    foreach ($temp as $k => $t) {
                        if ($k == $uid) {
                            $rank = $i;
                            break;
                        }
                        $i++;
                    }
                }
            }

        }

        return $rank;
    }

    //检查用户是否可以报名某堂课
    function checkUserSignStatus($cid, $uid)
    {
        $result = false;
        $course = Course::where('id', $cid)->first();
        if ($course && in_array($course->status, [Course::COURE_REG_STATUS, Course::COURSE_LIVING_STATUS])) {
            $usercourse = UserCourse::where(['cid' => $cid, 'uid' => $uid])->first();
            $result = empty($usercourse);
        }

        return $result;
    }

}
