<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2018/2/26
 * Time: 上午10:19
 */

namespace App\Repositories;

use App\CIData\Cidata;
use App\CIService\CIDataRecommend;
use App\CIService\CMS;
use App\Helpers\CacheKey;
use App\Helpers\WyethError;
use App\Models\Advertise;
use App\Models\Course;
use App\Models\CourseCounter;
use App\Models\CourseReview;
use App\Models\CourseTag;
use App\Models\Materiel;
use App\Models\Task;
use App\Models\UserBuyCourses;
use App\Models\UserCourse;
use App\Models\CourseStat;
use App\Models\CourseCat;
use App\Models\Tag;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserTag;
use App\Models\UserEvent;
use App\Services\Crm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\UserEventService;
use App\Jobs\SendTemplateMessageBySignUp;
use GuzzleHttp\Exception\RequestException;

use App\Models\AppConfig;
use App\Models\CiAppConfig;
use App\Services\CourseService;
use App\Services\CounterService;
use App\Services\CourseReviewService;

use App\Repositories\UserRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Cache;
use Session;
use Log;

class MoLiDataRepository extends BaseRepository
{
    protected $cms;

    protected $jinzhuang = ['S-26', 'S26', 'S26-MMF', '金装妈妈', 'S-26妈妈'];

    protected $qifu = ['启赋', '启韵'];

    protected $jinzhuang_brand = [4, 5, 8];

    protected $qifu_brand = [10, 11, 12];

    protected $page_size = 5;

    protected $time;

    public function __construct()
    {
        $this->cms = new CMS();
        $this->time = '2017-08-25';
    }

    public function getMoLiData($uid, $platform){
        $data = [];
        //获取签到数据
        $tasks = Task::where('uid', $uid)
            ->where('type', Task::TYPE_SIGN)->get();
        foreach ($tasks as $task){
            $update_time = substr($task->created_at, 0, 10);
            $year = substr($update_time, 0, 4);
            $month = substr($update_time, 5, 2);
            $day = substr($update_time, 8, 2);
            $data['sign'][$year][$month][] = $day;
        }
        //我的足迹数据
        $type = ['course_trace'];
        $userEvents = UserEvent::where('uid', $uid)->whereIn('type', $type)->take($this->page_size)
            ->orderBy('updated_at', 'desc')->offset(0)->get()->toArray();
        if(is_array($userEvents) && (count($userEvents) > 0)){
            foreach ($userEvents as $userEvent){
                $cid = $userEvent['cid'];
                $course = Course::where('id', $cid)->first();
                if(!$course || !CourseService::filterCourse($course)){
                    continue;
                }
                $result = CourseService::getCourseInfoById($uid, $cid);
                $data['my_trace'][] = $result;
            }
        }
        //我的关注的数据
        $page = 1;
        $brand = (new Crm())->getMemberBrand();
        $page--;
        $offset = $page * $this->page_size;
        $userTags = UserTag::where('uid', $uid)->get()->toArray();
        $cids = [];
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
        if($platform != 0){
            if($brand == 4){
                $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)
                    ->whereNotIn('brand', $this->qifu_brand)->orderBy('created_at', 'desc')
                    ->offset($offset)->take($this->page_size)->get()->toArray();
            }elseif($brand == 10){
                $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)
                    ->whereNotIn('brand', $this->jinzhuang_brand)->orderBy('created_at', 'desc')
                    ->offset($offset)->take($this->page_size)->get()->toArray();
            }else{
                $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)
                    ->orderBy('created_at', 'desc')->offset($offset)->take($this->page_size)->get()->toArray();
            }
            foreach ($courses as $course){
                $ret = CourseService::getCourseInfoById($uid, $course['id']);
                $ret['type'] = 0;
                $data['my_attention'][] = $ret;
            }
            return $this->returnData($data);
        }
        $my_attention = Cache::get(CacheKey::CACHE_KEY_FIND . $uid . ($page - 1));
        if($my_attention){
            $data['my_attention'] = $my_attention;
            return $this->returnData($data);
        }
        if($brand == 4){
            $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)->whereNotIn('brand', $this->qifu_brand)->orderBy('created_at', 'desc')->get()->toArray();
            $materiels = Materiel::where('cms_id', '>', 0)->whereNotIn('brand', $this->qifu)->orderBy('id', 'desc')->get()->toArray();
        }elseif($brand == 10){
            $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)->whereNotIn('brand', $this->jinzhuang_brand)->orderBy('created_at', 'desc')->get()->toArray();
            $materiels = Materiel::where('cms_id', '>', 0)->whereNotIn('brand', $this->jinzhuang)->orderBy('id', 'desc')->get()->toArray();
        }else{
            $courses = Course::whereIn('id', $cids)->where('display_status', 1)->where('created_at', '>', $this->time)->orderBy('created_at', 'desc')->get()->toArray();
            $materiels = Materiel::where('cms_id', '>', 0)->orderBy('id', 'desc')->get()->toArray();
        }
        for($i = 0; $i < count($materiels); $i++){
            $materiels[$i]['start_day'] = $materiels[$i]['date'];
        }
        $articles = array_merge($courses, $materiels);
        array_multisort(array_column($articles, 'start_day'), SORT_DESC, $articles);
        $cms_ids_array = [];
        $index_array = [];
        for($i = $offset; $i < $offset + $this->page_size; $i++){
            if($i < count($articles) && array_key_exists('cms_id', $articles[$i])){
                $cms_ids_array[] = $articles[$i]['cms_id'];
                $index_array[] = $i;
            }
        }
        $cms_ids = json_encode($cms_ids_array);
        $ret = $this->cms->getArticleDetailByIds($cms_ids);
        if(array_key_exists('data', $ret)){
            $cms_data = $ret['data'];
        }else{
            $cms_data = [];
        }
        $j = 0;
        for($i = $offset; $i < $offset + $this->page_size; $i++){
            if(in_array($i, $index_array) && $j < count($cms_data)){
                if(array_key_exists('content', $cms_data[$j])){
                    $cms_data[$j]['content'] = '';
                }
                $cms_data[$j]['type'] = 1;
                $data['my_attention'][] = $cms_data[$j];
                $j++;
            }else{
                $ret = CourseService::getCourseInfoById($uid, $articles[$i]['id']);
                $ret['type'] = 0;
                $data['my_attention'][] = $ret;
            }
        }
        Cache::put(CacheKey::CACHE_KEY_FIND . $uid . $page, $data['my_attention'], 60);
        return $this->returnData($data);
    }
}