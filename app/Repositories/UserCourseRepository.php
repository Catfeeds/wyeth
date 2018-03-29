<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/8/19
 * Time: 下午4:39
 */

namespace App\Repositories;

use App\CIData\Cidata;
use App\CIService\CIDataRecommend;
use App\Models\Course;
use App\Models\CourseCounter;
use App\Models\CourseReview;
use App\Models\CourseTag;
use App\Models\UserCourse;
use App\Models\CourseStat;
use App\Models\Tag;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserTag;
use App\Services\Crm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\UserEventService;
use App\Jobs\SendTemplateMessageBySignUp;

use App\Models\AppConfig;
use App\Models\CiAppConfig;
use App\Services\CourseService;
use App\Services\CounterService;

use App\Repositories\UserRepository;

use Session;
use Log;

class UserCourseRepository extends BaseRepository{
    protected $time;

    protected $jinzhuang_brand = [4, 5, 8];

    protected $qifu_brand = [10, 11, 12];

    public function __construct()
    {
        $this->time = '2016-08-25';
    }

    public function getUserDynamic($uid, $page, $page_size){
        $page--;
        $brand = (new Crm())->getMemberBrand();
        $offset = $page * $page_size;
        $userTags = UserTag::where('uid', $uid)->get()->toArray();
        $cids = [];
        $data = [];
        if(count($userTags) > 0){
            foreach ($userTags as $userTag){
                $tag = Tag::where('id', $userTag['tid'])->first();
                if($tag){
                    $courseTags = CourseTag::where('tid', $userTag['tid'])->get()->toArray();
                    if(count($courseTags) > 0){
                        foreach ($courseTags as $courseTag){
                            if(!in_array($courseTag['cid'], $cids)){
                                $cids[] = $courseTag['cid'];
                            }
                        }
                    }
                }
            }
            if(count($cids) > 0){
                if($brand == 4){
                    $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)
                        ->whereNotIn('brand', $this->qifu_brand)->orderBy('created_at', 'desc')
                        ->offset($offset)->take($page_size)->get()->toArray();
                }elseif($brand == 10){
                    $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)
                        ->whereNotIn('brand', $this->jinzhuang_brand)->orderBy('created_at', 'desc')
                        ->offset($offset)->take($page_size)->get()->toArray();
                }else{
                    $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)
                        ->orderBy('created_at', 'desc')->offset($offset)->take($page_size)->get()->toArray();
                }
                foreach ($courses as $course){
                    if(CourseService::filterCourse($course)){
                        $data[] = CourseService::getCourseInfoById($uid, $course['id']);
                    }
                }
            }
        }
        return $this->returnData($data);
    }
}