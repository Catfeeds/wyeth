<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/20
 * Time: 下午5:50
 */


namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseReview;
use App\Models\CourseTag;
use App\Models\UserCourse;
use App\Models\UserTag;
use App\Models\Teacher;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\AppConfig;
use App\Services\CourseService;
use App\Services\CounterService;

class TeacherRepository{
    public function observePro($pro_id, $is_cancel){
        $teacher = Teacher::where('id', $pro_id)->first();
        if($teacher == NULL){
            return [
                'ret' => -1,
                'msg' => '该讲师不存在'
            ];
        }
        $uid = Auth::id();
        $u_t = UserTag::where('uid', $uid)->where('tid', $teacher->tid)->get()->toArray();
        if($is_cancel == 1){
            UserTag::where('uid', $uid)->where('tid', $teacher->tid)->where('type', 2)->delete();
        }else{
            if(count($u_t) > 0){

            }else{
                $user_tag = new UserTag();
                $user_tag->uid = $uid;
                $user_tag->tid = $teacher->tid;
                $user_tag->type = 2;
                $user_tag->save();
            }
        }
        return [
            'ret' => 1,
        ];
    }

    public function getProInfo($pro_id){
        $uid = Auth::id();
        $teacher = Teacher::where('id', $pro_id)->first();
        if(count($teacher) <= 0){
            return [
                'ret' => -1,
                'msg' => '该讲师不存在'
            ];
        }else{
            $user_tag = UserTag::where('uid', $uid)->where('tid', $teacher->tid)->get()->toArray();
            $subscirbe = false;
            if(count($user_tag) > 0){
                $subscirbe = true;
            }
            $img = 'http://wyethup.img.apicase.io/wyethcourse/app/config/c60b58d923fde06e0f0387fe5e596774.jpg';
            return [
                'ret' => 1,
                'data' => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'position' => $teacher->position,
                    'hospital' => $teacher->hospital,
                    'desc' => $teacher->desc,
                    'avatar' => $teacher->avatar,
                    'subscribe' => $subscirbe,
                    'img' => $img
                ]
            ];
        }
    }

    public function getTeacherCourse($uid, $tid, $page, $page_size){
        $page = $page ? $page : 1;
        $page_size = $page_size ? $page_size : 10;
        $start = ($page - 1) * $page_size;
        $end = $start + $page_size;
        $courseTags = CourseTag::where('tid', $tid)->get()->toArray();
        $data = [];
        for($i = $start; $i < $end; $i++){
            if($i >= count($courseTags)){
                break;
            }
            $result = Course::where('id', $courseTags[$i]['cid'])->first();
            if($result && CourseService::filterCourse($result)){
                $data[] = CourseService::getCourseInfoById($uid, $courseTags[$i]['cid']);
            }
        }
        return $data;
    }

    public static function isConcerned($uid, $tid){
        //此处$tid为tag的id
        $userTag = UserTag::where('uid', $uid)->where('tid', $tid)->get()->toArray();
        $concern = (count($userTag) > 0) ? 1 : 0;
        return $concern;
    }

}