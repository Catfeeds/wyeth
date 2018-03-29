<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/7
 * Time: 下午3:15
 */

namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseListen;
use App\Models\CourseReview;
use App\Models\CourseStat;
use App\Models\User;
use App\Models\UserFriend;
use App\Models\UserFriendLog;
use App\Models\UserMq;
use App\Services\MqService;
use Illuminate\Support\Facades\Auth;

class CourseListenRepository extends BaseRepository
{
    protected $date = '2017-08-31';

    public function add($cid){
        $course = Course::find($cid);
        if (!$course) {
            return $this->error->NO_COURSE;
        }
        $uid = Auth::id();

        //取出最后一条记录,小于1分钟失败,避免误判小于30s
        $last_one = CourseListen::where('uid', $uid)
            ->where('cid', $cid)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($last_one && time() - strtotime($last_one->created_at) < 30){
//            return $this->returnError('听课间隔小于1分钟');
            //不报错
            return $this->returnData(['msg' => '听课间隔小于1分钟']);
        }

        //唤醒老用户加积分的过程
        //获取UserFriendLog表中唤醒该用户的记录
        $user_friend_logs = UserFriendLog::where('to_uid', $uid)->where('created_at', '>', $this->date)->get()->toArray();
        //判断该cid是否是曾经分享的课程cid
        $is_share = false;
        foreach ($user_friend_logs as $user_friend_log){
            if($user_friend_log['cid'] == $cid){
                $is_share = true;
            }
        }
        //查询course_listen表中是否有今天插入的记录
        $monday = date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
        $record = CourseListen::where('uid', $uid)->where('cid', $cid)->where('created_at', '>', $monday)->get()->toArray();
        if(count($record) == 0 && $is_share){
            //每个用户只加一次,即便该用户点了多个由他分享的链接
            $no_double_array = [];
            //如果没有则加积分
            foreach ($user_friend_logs as $user_friend_log){
                if($user_friend_log['from_uid'] != $uid && !in_array($user_friend_log['from_uid'], $no_double_array)){
                    MqService::increase($user_friend_log['from_uid'], MqService::ADD_TYPE_ACTIVE_OLD);
                    $no_double_array[] = $user_friend_log['from_uid'];
                }
            }
        }

        //上一次的听课时间+1,连续两分钟内+1
        $listen_time = 0;
        if ($last_one && time() - strtotime($last_one->created_at) < 120){
            $listen_time = $last_one->listen_time;
        }
        $listen_time ++;

        $course_listen = new CourseListen();
        $course_listen->uid = $uid;
        $course_listen->cid = $cid;
        $course_listen->listen_time = $listen_time;
        $course_listen->save();


        //总听课分钟
        $total = CourseListen::where('uid', $uid)
            ->where('cid', $cid)
            ->count();
        $course_review = CourseReview::where('cid', $cid)->first();
        $audio_duration = 0;
        if ($course_review){
            $audio_duration = ceil($course_review->audio_duration / 60);
        }
        $total = $total < $audio_duration ? $total : $audio_duration;


        $course_stat = CourseStat::firstOrCreate(['uid' => $uid, 'cid' => $cid]);
        //连续听课7分钟 或者听满整节课 增加一次mq(每节课只加一次)
        if ($course_stat->reward == 0 && ($listen_time >= 7 || $total >= $audio_duration)){
            $course_stat->reward = 1;
            $course_stat->save();
            //TODO 增加MQ (听课时长超过7分钟加一次)
            MqService::increase($uid, MqService::ADD_TYPE_LISTEN_LONG);
        }


        //记录总听课时长到course_stat
        if ($course_stat->listen < $total){
            $gap = $total - $course_stat->listen;

            $course_stat->listen = $total;
            $course_stat->save();

            //TODO 增加MQ (听课时长-常规)
            MqService::increase($uid, MqService::ADD_TYPE_LISTEN_REGULAR, $gap);
        }

        return $this->returnData([
            'id' => $course_listen->id,
            'reward' => $course_stat->reward,
            'listen_time' => $listen_time
        ]);
    }

    /**
     * 返回听课时长(分钟)
     * @param $uid
     * @return mixed
     */
    public function getAllListen($uid){
        $sum = CourseStat::where('uid', $uid)
            ->sum('listen');
        return $sum;
    }

}