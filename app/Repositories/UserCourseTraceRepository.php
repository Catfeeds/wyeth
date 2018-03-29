<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/26
 * Time: 上午11:27
 */

namespace App\Repositories;

use App\CIData\Cidata;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\CourseTag;
use App\Models\UserCourse;
use App\Models\CourseStat;
use App\Models\Tag;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\UserEventService;
use App\Jobs\SendTemplateMessageBySignUp;

use App\Models\AppConfig;
use App\Services\CourseService;
use App\Services\CounterService;

use Session;
use Log;

class UserCourseTraceRepository extends BaseRepository
{

    public function getTraceCourse($uid, $page = 1, $page_size = 6){
        $page--;
        $offset = $page * $page_size;
        $type = ['course_trace'];
        $userEvents = UserEvent::where('uid', $uid)->whereIn('type', $type)->take($page_size)
            ->orderBy('updated_at', 'desc')->offset($offset)->get()->toArray();
        $data = [];
        if(is_array($userEvents) && (count($userEvents) > 0)){
            foreach ($userEvents as $userEvent){
                $cid = $userEvent['cid'];
                $course = Course::where('id', $cid)->first();
                if(!$course || !CourseService::filterCourse($course)){
                    continue;
                }
                $result = CourseService::getCourseInfoById($uid, $cid);
                $data[] = $result;
            }
        }
        return $this->returnData($data);
    }

    public static function setCourseTrace($user, $cid){
        $type = 'course_trace';
        $userEvent = UserEvent::where('uid', $user->id)->where('cid', $cid)->where('type', $type)->first();
        if (!$userEvent) {
            $userEventNew = new UserEvent();
            $userEventNew->uid = $user->id;
            $userEventNew->user_type = $user->type;
            $userEventNew->cid = $cid;
            $userEventNew->type = $type;
            $userEventNew->save();
        }else {
            $userEvent->delete();
            $userEventNew = new UserEvent();
            $userEventNew->uid = $user->id;
            $userEventNew->user_type = $user->type;
            $userEventNew->cid = $cid;
            $userEventNew->type = $type;
            $userEventNew->save();
        }
        return 1;
    }

    public function getTraceCourseByDate($uid, $date){
        $type = ['course_trace'];
        $userEvents = UserEvent::where('uid', $uid)->whereIn('type', $type)->where('created_at', '>', $date . ' 00:00:00')
            ->where('created_at', '<', $date . ' 23:59:59')->orderBy('created_at', 'desc')->get()->toArray();
        $data = [];
        if(is_array($userEvents) && (count($userEvents) > 0)){
            foreach ($userEvents as $userEvent){
                $cid = $userEvent['cid'];
                $course = Course::where('id', $cid)->first();
                if(!$course || !CourseService::filterCourse($course)){
                    continue;
                }
                $result = CourseService::getCourseInfoById($uid, $cid);
                $data[] = $result;
            }
        }
        return $this->returnData($data);
    }
}