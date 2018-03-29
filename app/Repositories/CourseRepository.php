<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/7
 * Time: 下午3:44
 */

namespace App\Repositories;

use App\CIData\Cidata;
use App\CIService\CIDataRecommend;
use App\Helpers\CacheKey;
use App\Helpers\WyethError;
use App\Models\Advertise;
use App\Models\Course;
use App\Models\CourseCounter;
use App\Models\CourseReview;
use App\Models\CourseTag;
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

class CourseRepository extends BaseRepository
{
    use DispatchesJobs;

    const JIN_ZHUANG = 4;

    const QI_FU = 10;

    protected $courseNum = 3;

    protected $img_suffix = '?imageView2/1/w/220/h/220';

    //无主品牌需求数组
    protected $brandArray = [[0, 7], [4, 5, 8], [10, 11, 12]];

    //金装品牌需求数组
    protected $JZBrandArray = [[4, 5, 8], [0, 7], [0, 7]];

    //启赋品牌需求数组
    protected $QFBrandArray = [[10, 11, 12], [0, 7], [0, 7]];

    //金装课brand数组
    protected $JIN_ZHUANG_ARRAY = [4, 5, 8];

    //启赋课brand数组
    protected $QI_FU_ARRAY = [10, 11, 12];

    protected $noDoubleArray = [];

    protected $cat_course_num = 10;

    //新版获取推荐课的方法
    public function getCourseRecommend($uid){
        $userTags = UserTag::where('uid', $uid)->whereIn('type', [0, 1])->get()->toArray();
        if(count($userTags) > 0){
            $ciDataREcommend = new CIDataRecommend();
            $response = $ciDataREcommend->recomend($uid);
            $data = [];
            if(is_array($response) && array_key_exists('data', $response) && array_key_exists('items', $response['data'])){
                $items = $response['data']['items'];
                if(is_array($items)){
                    //惠氏过滤
                    if (config('oneitfarm.is_wyeth')){
                        $data = $this->chooseCourseFromArray($uid, $items);
                    }else{
                        foreach ($items as $item){
                            $course = Course::where('id', $item)->where('display_status', 1)->first();

                            if(CourseService::filterCourse($course)){
                                $data[] = CourseService::getCourseInfoById($uid, $course->id);
                            }
                        }
                    }

                    if(count($data) < $this->courseNum){
                        $data = array_merge($data, $this->getFirstRecomCourse($uid, $this->courseNum - count($data)));
                    }
                }
            }else{
                $data = $this->getFirstRecomCourse($uid, $this->courseNum);
            }
        }else{
            $data = $this->getFirstRecomCourse($uid, $this->courseNum);
        }
        return $this->returnData($data);
    }

    //依据品牌需求选择课程
    public function chooseCourseFromArray($uid, $ids){
        $bArray = $this->getBrandArray();
        $result = [];
        foreach ($bArray as $value){
            $courses = Course::whereIn('id', $ids)->whereNotIn('id', $this->noDoubleArray)->where('display_status', 1)->whereIn('brand', $value)->get();
            $hasCourse = false;
            foreach ($courses as $course){
                if(CourseService::filterCourse($course)){
                    $this->noDoubleArray[] = $course->id;
                    $result[] = CourseService::getCourseInfoById($uid, $course->id);
                    $hasCourse = true;
                    break;
                }
            }
            if(!$hasCourse){
                $courses = Course::whereNotIn('id', $this->noDoubleArray)->where('display_status', 1)->whereIn('brand', $value)->orderBy('id', 'desc')->get();
                foreach ($courses as $course){
                    if(CourseService::filterCourse($course)){
                        $this->noDoubleArray[] = $course->id;
                        $result[] = CourseService::getCourseInfoById($uid, $course->id);
                        break;
                    }
                }
            }
        }
        return $result;
    }

    public function getBrandArray(){
        $userBrand = (new Crm())->getMemberBrand();
        if($userBrand == 0){
            return $this->brandArray;
        }elseif($userBrand == 4){
            return $this->JZBrandArray;
        }else{
            return $this->QFBrandArray;
        }
    }

    //旧版获取推荐课的方法
    public function getOldCourseRecommend($uid){
        $result = [];
        $bArray = $this->getBrandArray();
        $ids = CiAppConfig::ci_recommend((new UserRepository())->getUserType(Auth::id()), true);
        if(is_array($ids) && count($ids) > 0){
            foreach ($ids as $id){
                $result[] = CourseService::getCourseInfoById($uid, $id);
            }
        }else{
            for($i = 0; $i < $this->courseNum; $i++){
                $course = Course::whereIn('brand', $bArray[$i])->whereNotIn('id', $this->noDoubleArray)->orderBy('id', 'desc')->first();                            $this->noDoubleArray[] = $course->id;
                $this->noDoubleArray[] = $course->id;
                $result[] = CourseService::getCourseInfoById($uid, $course->id);
            }
        }
        return $result;
    }

    //依据剩余数量获取首页推荐课
    public function getFirstRecomCourse($uid, $num){
        $userTags = UserTag::where('uid', $uid)->whereIn('type', [0, 1])->get()->toArray();
        $result = [];
        $courseIds = [];
        $bArray = $this->getBrandArray();
        $search_num = min(count($bArray), $num);
        if(count($userTags) > 0){
            foreach ($userTags as $userTag){
                $courseTag = CourseTag::where('tid', $userTag['tid'])->orderBy('cid', 'desc')->first();
                if($courseTag){
                    if(!in_array($courseTag['cid'], $courseIds)){
                        $courseIds[] = $courseTag['cid'];
                    }
                }
            }
            for($i = 0; $i < $search_num; $i++){
                $course = Course::whereIn('id', $courseIds)->whereNotIn('id', $this->noDoubleArray)->whereIn('brand', $bArray[$i])->orderBy('id', 'desc')->first();
                if($course != NULL && CourseService::filterCourse($course)){
                    $result[] = CourseService::getCourseInfoById($uid, $course->id);
                }else{
                    //如果没取到就去运营设置的课程id去找
                    $ids = CiAppConfig::ci_recommend((new UserRepository())->getUserType(Auth::id()), true);
                    if(is_array($ids) && count($ids) > 0){
                        $course = Course::whereIn('id', $ids)->whereNotIn('id', $this->noDoubleArray)->whereIn('brand', $bArray[$i])->first();
                        if($course != NULL){
                            $this->noDoubleArray[] = $course->id;
                            $result[] = CourseService::getCourseInfoById($uid, $course->id);
                        }else{
                            //如果还没找到,那就取符合该品牌的课程的id最大的一节课
                            $course = $this->getMaxIdBrandCourse($uid, $bArray[$i]);
                            if($course){
                                $result[] = $course;
                            }
                        }
                    }else{
                        $course = $this->getMaxIdBrandCourse($uid, $bArray[$i]);
                        if($course){
                            $result[] = $course;
                        }
                    }
                }
            }
        }else{
            for($i = 0; $i < $search_num; $i++){
                $ids = CiAppConfig::ci_recommend((new UserRepository())->getUserType(Auth::id()), true);
                if(is_array($ids) && count($ids) > 0){
                    $course = Course::whereIn('id', $ids)->whereNotIn('id', $this->noDoubleArray)->whereIn('brand', $bArray[$i])->first();
                    if($course != NULL){
                        $this->noDoubleArray[] = $course->id;
                        $result[] = CourseService::getCourseInfoById($uid, $course->id);
                    }else{
                        //如果还没找到,那就取符合该品牌的课程的id最大的一节课
                        $course = $this->getMaxIdBrandCourse($uid, $bArray[$i]);
                        if($course){
                            $result[] = $course;
                        }
                    }
                }else{
                    $course = $this->getMaxIdBrandCourse($uid, $bArray[$i]);
                    if($course){
                        $result[] = $course;
                    }
                }
            }
        }
        return $result;
    }

    public function getMaxIdBrandCourse($uid, $brandArray){
        $courses = Course::whereIn('brand', $brandArray)->whereNotIn('id', $this->noDoubleArray)->orderBy('id', 'desc')->get();
        foreach ($courses as $course){
            if(CourseService::filterCourse($course)){
                $this->noDoubleArray[] = $course->id;
                return CourseService::getCourseInfoById($uid, $course->id);
            }
        }
        return null;
    }

    //获取首页最热课程
    public  function getHotCourse($uid){
        $brand = (new Crm())->getMemberBrand();
        if($brand == 4){
            $ids = CiAppConfig::ci_jinzhuang_hot_course(true);
        }elseif($brand == 10){
            $ids = CiAppConfig::ci_qifu_hot_course(true);
        }else{
            $ids = CiAppConfig::ci_hot_course(true);
        }
        $data = [];
        $from = [];
        $extra = 0;
        foreach ($ids as $id) {
            $course = Course::where('id', $id)->first();
            if($course == NULL){
                $extra++;
                continue;
            }
            //避免从后台获取的课程id中有重复来源的课程
            if($course->brand == 4 || $course->brand == 5 || $course->brand == 8){
                if(!in_array(4, $from)){
                    $from[] = 4;
                }
            }elseif($course->brand == 10 || $course->brand == 11 || $course->brand == 12){
                if(!in_array(10, $from)){
                    $from[] = 10;
                }
            }else{
                if(!in_array(7, $from)){
                    $from[] = 7;
                }
            }
            $data[] = CourseService::getCourseInfoById($uid, $id);
            //添加到去重数组
            $this->noDoubleArray[] = $id;
        }
        $num = $this->courseNum - count($ids) + $extra;
        if($num > 0){
            $courseData = $this->getCourseByType($uid, $num, 'hot', $this->noDoubleArray, $from);
            $data = array_merge($data, $courseData);
        }
        return $this->returnData($data);
    }

    //获取首页最新课程
    public function getNewCourse($uid){
        $brand = (new Crm())->getMemberBrand();
        if($brand == 4){
            $ids = CiAppConfig::ci_jinzhuang_new_course(true);
        }elseif($brand == 10){
            $ids = CiAppConfig::ci_qifu_new_course(true);
        }else{
            $ids = CiAppConfig::ci_new_course(true);
        }
        $data = [];
        $from = [];
        $extra = 0;
        foreach ($ids as $id) {
            $course = Course::where('id', $id)->first();
            if($course == NULL){
                $extra++;
                continue;
            }
            if($course->brand == 4 || $course->brand == 5 || $course->brand == 8){
                if(!in_array(4, $from)){
                    $from[] = 4;
                }
            }elseif($course->brand == 10 || $course->brand == 11 || $course->brand == 12){
                if(!in_array(10, $from)){
                    $from[] = 10;
                }
            }else{
                if(!in_array(7, $from)){
                    $from[] = 7;
                }
            }
            $data[] = CourseService::getCourseInfoById($uid, $id);
            //添加到去重数组
            $this->noDoubleArray[] = $id;
        }
        $num = $this->courseNum - count($ids) + $extra;
        if($num > 0){
            $courseData = $this->getCourseByType($uid, $num, 'new', $this->noDoubleArray, $from);
            $data = array_merge($data, $courseData);
        }
        return $this->returnData($data);
    }

    //根据最热或最新获取课程
    public function getCourseByType($uid, $num, $type, $ids, $from){
        $brands = $this->deleteDoubleElement($from);
        $data = [];
        for($i = 0; $i < $num; $i++){
            if(count($brands) > $i) {
                $course = $this->getCourseFromBrand($brands[$i], $type);
            }elseif($i == 0){
                $course = $this->getCourseFromBrand(4, $type);
            }else{
                $course = $this->getCourseFromBrand(7, $type);
            }
            if(!is_array($course) || count($course) == 0){
                continue;
            }
            $cid = $course['id'];
            $this->noDoubleArray[] = $cid;
            $data[] = CourseService::getCourseInfoById($uid, $cid);;
        }
        return $data;
    }

    //根据不同的品牌获取课程
    public function getCourseFromBrand($brand, $type){
        if($type == 'hot'){
            if($brand == 4){
                $courses = Course::whereIn('brand', [4, 5, 8])->whereNotIn('id', $this->noDoubleArray)->get()->toArray();
            }elseif($brand == 10){
                $courses = Course::whereIn('brand', [10, 11, 12])->whereNotIn('id', $this->noDoubleArray)->get()->toArray();
            }else{
                $courses = Course::whereIn('brand', [0, 7])->whereNotIn('id', $this->noDoubleArray)->get()->toArray();
            }
            $courseIds = [];
            foreach ($courses as $course){
                $courseIds[] = $course['id'];
            }
            $courseCounters = CourseCounter::whereIn('item_id', $courseIds)->where('item_type', 'course')->orderBy('course_reg', 'desc')->limit(10)->get()->toArray();
            foreach ($courseCounters as $courseCounter){
                $course = Course::where('id', $courseCounter['item_id'])->first();
                if(($course != null) && CourseService::filterCourse($course)){
                    $this->noDoubleArray[] = $course->id;
                    return $course;
                }
            }
            return NULL;
        }else{
            if($brand == 4){
                $courses = Course::whereIn('brand', [4, 5, 8])->whereNotIn('id', $this->noDoubleArray)->orderBy('start_day', 'desc')->limit(20)->get()->toArray();
            }elseif($brand == 10){
                $courses = Course::whereIn('brand', [10, 11, 12])->whereNotIn('id', $this->noDoubleArray)->orderBy('start_day', 'desc')->limit(20)->get()->toArray();
            }else{
                $courses = Course::whereIn('brand', [0, 7])->whereNotIn('id', $this->noDoubleArray)->orderBy('start_day', 'desc')->limit(40)->get()->toArray();
            }
            foreach ($courses as $course){
                if(CourseService::filterCourse($course)){
                    $this->noDoubleArray[] = $course['id'];
                    return $course;
                }
            }
            return NULL;
        }
    }

    //过滤重复品牌,得到没有的品牌的数组
    public function deleteDoubleElement($array){
        $userBrand = (new Crm())->getMemberBrand();
        if($userBrand == 0){
            $origin = [4, 7, 10];
        }elseif($userBrand == 4){
            $origin = [4, 7, 7];
        }else{
            $origin = [10, 7, 7];
        }
        $result = [];
        foreach ($origin as $o){
            if(!in_array($o, $array)){
                $result[] = $o;
            }
        }
        return $result;
    }

    //报名课程
    public function signCourse($cid){
        //课程信息
        $uid = Auth::id();

        $result = [];
        if (empty($cid) || empty($uid)) {
            $result['ret'] = -1;
            $result['msg'] = '请求参数错误';
            return response()->json($result);
        }

        //课程信息
        $course = Course::where('id', $cid)->first();
        if (empty($course)) {
            $result['ret'] = -1;
            $result['msg'] = '课程不存在';
            return response()->json($result);
        }

        //用户信息
        $user = User::where('id', $uid)->first();
        if (!$user) {
            $result['ret'] = -1;
            $result['msg'] = '用户不存在';
            return response()->json($result);
        }

        //检查用户报名数量
        $usercouses = CourseService::reg($cid);
//        if ($usercouses >= $course->sign_limit) {
//            $result['ret'] = -1;
//            $result['msg'] = '报名人数已满';
//            return response()->json($result);
//        }

        //用户是crm用户
        $crm_status = $user->crm_status;
        if (!$crm_status) {
            $result['ret'] = -1;
            $result['msg'] = '您不是crm用户';
            return response()->json($result);
        }
        //记录用户报名信息
        $userCourse = UserCourse::where(['cid' => $cid, 'uid' => $uid])->first();
        if (empty($userCourse)) {
            $userCourse = new UserCourse();
            $userCourse->cid = $cid;
            $userCourse->uid = $uid;
            $userCourse->channel = Session::get('channel');
            $userCourse->save();

            //记录用户统计日志
            $courseStat = CourseStat::where(['uid' => $uid, 'cid' => $cid])->first();
            if ($courseStat) {
                $courseStat->sign_time = date("Y-m-d H:i:s");
                $courseStat->save();
            }

            //CIData统计报名
            Cidata::init(config('oneitfarm.appkey'));
            Cidata::sendEvent($uid, $user->channel, null, 'sign', ['cid' => $cid, 'wyeth_channel' => Session::get('channel')]);

            $is_subscribed = $user->subscribe_status;
            if ($is_subscribed && $user->type == User::OPENID_TYPE_WX) {
                // 推荐课程
                $params = CourseService::recommendCourseIdGet($user, $course);
                Log::info('crmSign', [
                    'uid' => $user->id,
                    'sid' => $params?$params['sign_up_course']->id : 0,
                    'rid' => $params?$params['recommend_course']->id : 0
                ]);
                if ($params) {
                    $job = (new SendTemplateMessageBySignUp($params));
                    $this->dispatch($job);
                }
            }
        }

        //判断课程是否开始
        $status = $course->status;
        if ($status == 2) {
            //课程开始了
            $result['data']['url'] = config('app.url') . '/mobile/living?cid=' . $cid;
        } else {
            //$this->result['data']['url'] = config('app.url') . '/mobile/course_ok?cid=' . $cid . '&uid=' . $uid;
            $result['data']['url'] = config('app.url') . '/mobile/sign?cid=' .$cid;
        }
        $result['ret'] = 1;
        return response()->json($result);
    }

    //获取某课程是否报名
    public function isSign($cid){
        $uid = Auth::id();
        $userCourse = UserCourse::where(['cid' => $cid, 'uid' => $uid])->first();
        if($userCourse){
            return 1;
        }else{
            return 0;
        }
    }

    //获取某节课的赞数
    public function getLikeNum($cid){
        return $this->returnData(UserEventService::countReviewLikeAllGet($cid));
    }

    //给某节课点赞
    public function giveAReviewLike($cid){
        $user = Auth::user();
        $course = Course::find($cid);
        if (!$course) {
            return $this->error->ACTION_FAILED;
        }
        $res = UserEventService::giveAReviewLike($user, $course);
        if ($res) {
            return $this->returnData();
        } else {
            return $this->error->ACTION_FAILED;
        }
    }

    //取消某节课的赞
    public function cancelAReviewLike($cid)
    {
        $user = Auth::user();
        $course = Course::find($cid);
        if (!$course) {
            return $this->error->ACTION_FAILED;
        }
        $res = UserEventService::cancelAReviewLike($user, $course);
        if ($res) {
            return $this->returnData();
        } else {
            return $this->error->ACTION_FAILED;
        }
    }

    //用户是否给某节课点过赞
    public function isLikeAReview($cid){
        $uid = Auth::id();
        return UserEventService::isLikeAReview($uid, $cid);
    }

    //获取某节课的收藏数
    public function getSaveNum($cid){
        return $this->returnData(UserEventService::reviewSaveNum($cid));
    }

    //收藏某节课
    public function saveAReview($cid){
        $user = Auth::user();
        $course = Course::find($cid);
        if (!$course) {
            return $this->error->ACTION_FAILED;
        }
        $res = UserEventService::saveAReview($user, $course);
        if ($res) {
            return $this->returnData();
        } else {
            return $this->error->ACTION_FAILED;
        }
    }

    //取消收藏某节课
    public function cancelAReviewSave($cid)
    {
        $user = Auth::user();
        $course = Course::find($cid);
        if (!$course) {
            return $this->error->ACTION_FAILED;
        }
        $res = UserEventService::cancelAReviewSave($user, $course);
        if ($res) {
            return $this->returnData();
        } else {
            return $this->error->ACTION_FAILED;
        }
    }

    //用户是否收藏某节课
    public function isSaveAReview($cid){
        $uid = Auth::id();
        return UserEventService::isSaveAReview($uid, $cid);
    }

    //获取某套课的赞数
    public function getCatLikeNum($cid){
        return $this->returnData(UserEventService::countCatLikeAllGet($cid));
    }

    //给某套课点赞
    public function giveACatLike($cid){
        $user = Auth::user();
        $course = Course::where('cid', $cid)->first();
        if (!$course) {
            return $this->error->ACTION_FAILED;
        }
        $res = UserEventService::giveACatLike($user, $cid);
        if ($res) {
            return $this->returnData();
        } else {
            return $this->error->ACTION_FAILED;
        }
    }

    //取消某套课的赞
    public function cancelACatLike($cid)
    {
        $user = Auth::user();
        $course = Course::where('cid', $cid)->first();
        if (!$course) {
            return $this->error->ACTION_FAILED;
        }
        $res = UserEventService::cancelACatLike($user, $cid);
        if ($res) {
            return $this->returnData();
        } else {
            return $this->error->ACTION_FAILED;
        }
    }

    //用户是否给某套课点过赞
    public function isLikeACat($cid){
        $uid = Auth::id();
        return UserEventService::isLikeACat($uid, $cid);
    }

    //获取某套课的收藏数
    public function getCatSaveNum($cid){
        return $this->returnData(UserEventService::catSaveNum($cid));
    }

    //收藏某套课
    public function saveACat($cid){
        $user = Auth::user();
        $course = Course::where('cid', $cid)->first();
        if (!$course) {
            return $this->error->ACTION_FAILED;
        }
        $res = UserEventService::saveACat($user, $cid);
        if ($res) {
            return $this->returnData();
        } else {
            return $this->error->ACTION_FAILED;
        }
    }

    //取消收藏某套课
    public function cancelACatSave($cid)
    {
        $user = Auth::user();
        $course = Course::where('cid', $cid)->first();
        if (!$course) {
            return $this->error->ACTION_FAILED;
        }
        $res = UserEventService::cancelACatSave($user, $cid);
        if ($res) {
            return $this->returnData();
        } else {
            return $this->error->ACTION_FAILED;
        }
    }

    //用户是否收藏某套课
    public function isSaveACat($cid){
        $uid = Auth::id();
        return UserEventService::isSaveACat($uid, $cid);
    }

    //获取某专家的课程
    public function getProCourse($uid, $pro_id, $page, $page_size){
        $teacher = Teacher::where('id', $pro_id)->first();
        if($teacher){
            $teacher_name = $teacher->name;
            $page--;
            $offset = $page * $page_size;
            $courses = Course::where('teacher_name', $teacher_name)->take($page_size)->offset($offset)->get()->toArray();
            $data = [];
            if(is_array($courses) && (count($courses) > 0)){
                foreach ($courses as $course){
                    $cid = $course['id'];
                    $c = CourseService::getCourseInfoById($uid, $cid);
                    $like_sum = UserEventService::reviewLikesNum(Course::find($cid));
                    $c['like_sum'] = $like_sum;
                    $data[] = $c;
                }
            }
            return $this->returnData($data);
        }else{
            return (new WyethError())->TEACHER_NOT_EXIST;
        }
    }

    //获取课程详情
    public function getDetail($user, $cid){
        $course = Course::where('id', $cid)->first();
        if(!$course){
            return (new WyethError())->NO_COURSE;
        }
        //设置足迹
        UserCourseTraceRepository::setCourseTrace($user, $cid);
        //完成任务
        $mq = (new TaskRepository())->scan($user->id, $cid);
        //CourseStat统计
        // 记录用户统计日志
        $courseStat = CourseStat::firstOrCreate(['uid' => $user->id, 'cid' => $cid]);
        if ($courseStat->in_review_time == '0000-00-00 00:00:00') {
            $courseStat->in_review_time = date("Y-m-d H:i:s");
            $courseStat->save();
            CourseReviewService::countAllIncrement($cid);
        }

        //UserEvent统计
        $userEvent = new UserEvent();
        $userEvent->uid = $user->id;
        $userEvent->cid = $cid;
        $userEvent->user_type = $user->type;
        $userEvent->type = 'review_in';
        $data = json_encode(["updated_at" => time(), 'channel' => Session::get('channel')]);
        $userEvent->data = $data;
        $userEvent->save();

        $data = [];
        if(!($course == null) && $course->id){
            $id = $course->id;
            //推荐课程
            $recomClass = $this->getDetailRecommendCourse($user->id, $id);
            $review = CourseReview::where('cid', $id)->first();
            $c_t = CourseTag::where('cid', $id)->get()->toArray();
            $courseCounter = CourseCounter::where('item_id', $id)->where('item_type', 'course')->first();
            $teacher_id = 0;
            $teacher_name = $course->teacher_name;
            $teacher_position = $course->teacher_position;
            $teacher_hospital = $course->teacher_hospital;
            $teacher_desc = $course->teacher_desc;
            $teacher_avatar = $course->teacher_avatar;
            $concern = 0;
            $tags = array();
            $userCourseCids = UserCourse::forceIndex('user_course_uid_index')
                ->where('uid', $user->id)->where('cid', $id)->lists('cid')->toArray();
            $isSigned = 0;
            if (!empty($userCourseCids) && in_array($course->id, $userCourseCids)) {
                $isSigned = 1;
            }
            if(is_array($c_t) && (count($c_t) > 0)){
                for($i = 0; $i < count($c_t); $i++){
                    $tid = $c_t[$i]['tid'];
                    $type = $c_t[$i]['type'];
                    if($type == 2){
                        $tag = Tag::where('id', $tid)->first();
                        $concern = TeacherRepository::isConcerned($user->id, $tid);
                        $tag_name = $tag->name;
                        $teacher = Teacher::where('name', $tag_name)->first();
                        if($teacher){
                            //如果teacher表里有这个teacher,那么teacher的信息按照teacher表为准
                            $teacher_id = $teacher->id;
                            $teacher_name = $teacher->name;
                            $teacher_position = $teacher->position;
                            $teacher_hospital = $teacher->hospital;
                            $teacher_desc = $teacher->desc;
                            $teacher_avatar = $teacher->avatar;
                        }
                    }
                    $tag = Tag::where('id', $tid)->where('type', 3)->first();
                    if($tag){
                        $tags[] = $tag;
                    }
                }
            }
            $buy_count = UserBuyCourses::where([
                'type' => 2,
                'cid' => $id,
                'trade_status' => 1
            ])->count();

            //获取头图
            $temp = $course->banners ? explode(',', $course->banners) : [];
            $banners = [];
            if (count($temp) > 0) {
                $banners = [
                    array('img' => $temp[0]),
                    array('img' => $temp[1])
                ];
            }

            //获取相关套课数据
            $catCourses = [];
            if($course->cid != 0) {
                $cat_courses = Course::where('id', '<>', $cid)->where('cid', $course->cid)->where('display_status', 1)
                    ->take($this->cat_course_num)->orderBy('id', 'desc')->get();
                foreach ($cat_courses as $cat_course) {
                    $catCourses[] = CourseService::getCourseInfoById($user->id, $cat_course->id);
                }
            }

            $classInfo = [
                'id' => $id,
                'cid' => $course->cid,
                'title' => $course->title,
                'img' => $course->img,
                'hot' => CourseReviewService::countAllGet($id),
                'brand' => $course->brand,
                'course_desc' => $course->desc,
                'status' => ($course->status == 1 && $isSigned) ? 4 : $course->status,
                'start_day' => $course->start_day,
                'start_time' => $course->start_time,
                'tags' => $tags,
                'likeNum' => $this->getLikeNum($id)['data'],
                'isLike' => $this->isLikeAReview($id),
                'saveNum' => $this->getSaveNum($id)['data'],
                'isSave' => $this->isSaveAReview($id),
                'regNum' => $courseCounter ? $courseCounter->course_reg : 0,
                'isSign' => $this->isSign($id),
                'price' => $course->price,
                'buyCount' => $buy_count,
                'banners' => $banners
            ];
            $userInfo = [
                'concern' => $concern,
                'teacher_id' => $teacher_id,
                'teacher_name' => $teacher_name,
                'teacher_position' => $teacher_position,
                'teacher_hospital' => $teacher_hospital,
                'teacher_desc' => $teacher_desc,
                'teacher_avatar' => $teacher_avatar
            ];
            $flashPic = CiAppConfig::ci_course_flash_pic(true);

            // 课程购买状态
            $cat_id = $course->cid;
            $cat_price = 0;
            if ($cat_id) {
                $cat = CourseCat::where('id', $cat_id)->first();
                $cat_price = $cat->price;
            }
            if ($cat_id && $cat_price) { // 属于套课
                $buy_history = UserBuyCourses::where('cid', $cat_id)->where('type', 1)->where('trade_status', 1)->first();
                if ($buy_history) {
                    $readable = UserBuyCourses::STATUS_CAN_READ;
                } else {
                    $readable = UserBuyCourses::STATUS_BUY_CAT;
                }
            } else { // 不属于套课
                $cat_price = $course->price;
                if ($cat_price) { // 付费课程
                    $buy_history = UserBuyCourses::where('cid', $id)->where('type', 2)->where('trade_status', 1)->first();
                    if ($buy_history) {
                        $readable = UserBuyCourses::STATUS_CAN_READ;
                    } else {
                        $readable = UserBuyCourses::STATUS_BUY_COURSE;
                    }
                } else { // 非付费课程
                    $readable = UserBuyCourses::STATUS_CAN_READ;
                }
            }
            $classInfo['purchased'] = $readable;    // 1：可阅读，2：购买套课，3：购买单课
            $classInfo['price'] = $cat_price;

            if(count($review) == 0){
                return $this->returnData([
                    'userInfo' => $userInfo,
                    'classInfo' => $classInfo,
                    'recomClass' => $recomClass,
                    'flashPic' => $flashPic,
                    'catCourses' => $catCourses
                ]);
            }
            //处理章节化数据
            $section = NULL;
            $sec_ret = [];
            $src = '';
            $guide = '';
            $desc = '';
            $audio_duration = $review->audio_duration;
            if(!($review == null) && $review->id){
                $section = $review->section;
                $src = $review->review_type == 1 ? $review->audio : $review->video;
                $guide = $review->guide;
                $desc = $review->desc;
                $aid = $review->id;
                if(is_array($section) && (count($section) > 0)) {
                    //预处理时间
                    $sec_time = [];
                    for($i = 0; $i < count($section); $i++){
                        $sec_time[$i] = $section[$i]['second'];
                    }
                    for ($i = 0; $i < count($section); $i++) {
                        if(array_key_exists('section', $section[$i])){  //应对新格式
                            $sec_ret[$i]['title'] = $section[$i]['point'];
                            $sec_ret[$i]['start'] = $section[$i]['second'];
                            $sec_ret[$i]['cid'] = $course->id;
                            $sec_ret[$i]['index'] = $i + 1;
                            if(($i + 1) < count($section)){
                                $sec_ret[$i]['end'] = $section[$i+1]['second'];
                                $sec_ret[$i]['duration'] = $sec_ret[$i]['end'] - $sec_ret[$i]['start'];
                            }else{
                                $sec_ret[$i]['end'] = $audio_duration;
                                $sec_ret[$i]['duration'] = $audio_duration - $sec_ret[$i]['start'];
                            }
                            $sec = $section[$i]['section'];
                            if(is_array($sec) && (count($sec) > 0)){
                                for($j = 0; $j < count($sec); $j++){
                                    $sec[$j]['src'] = $src;
                                    $sec[$j]['id'] = $aid;
                                    $sec[$j]['title'] = $sec[$j]['point'];
                                    $sec[$j]['start'] = $sec[$j]['second'];
                                    $sec[$j]['index'] = $j + 1;
                                    if(($j + 1) < count($sec)){
                                        $sec[$j]['end'] = $sec[$j + 1]['second'];
                                        $sec[$j]['duration'] = $sec[$j]['end'] - $sec[$j]['start'];
                                    }elseif(($i + 1) < count($section)){
                                        $sec[$j]['end'] = $sec_time[$i + 1];
                                        $sec[$j]['duration'] = $sec_time[$i + 1] - $sec[$j]['start'];
                                    }else{
                                        $sec[$j]['end'] = $audio_duration;
                                        $sec[$j]['duration'] = $audio_duration - $sec[$j]['start'];
                                    }
                                    unset($sec[$j]['second']);
                                    unset($sec[$j]['point']);
                                    $sec_ret[$i]['section'][] = $sec[$j];
                                }
                            }
                            unset($section[$i]['section']);
                            $section[$i]['section'] = $sec;
                            unset($section[$i]['second']);
                            unset($section[$i]['point']);
                        }else{  //兼容之前的格式
                            $sec_ret = [];
                        }
                    }
                }
            }
            $classlist = [
                    'chapter' => $sec_ret,
                    'id' => $id,
                    'src' => $src,
                    'title' => $course->title,
                    'audio_duration' => $audio_duration
                ];
            $extra = [];
            $review_type = $review->review_type;
            $classInfo['review_type'] = $review_type;
            if($review_type == 1 || $review_type == 2){  //音频
                //广告
                if($course->ad_img && $course->ad_link){
                    $carouselsEnd1 = [
                        [
                            'img' => $course->ad_img,
                            'link' => $course->ad_link,
                        ]
                    ];
                }else{
                    $carouselsEnd1 = Advertise::getAdvertise(Advertise::POSITION_COURSE_MID, $course->brand);
                }
                $carouselsEnd2 = Advertise::getAdvertise(Advertise::POSITION_COURSE_BOTTOM, $course->brand);

                $classInfo['desc'] = $review->desc;
                $extra = [
                    'broadcast1' => $carouselsEnd1,
                    'broadcast2' => $carouselsEnd2
                ];
            }
            $classInfo['desc'] = str_replace('img.xiumi.us', 'wyeth-xiumi.nibaguai.com', $desc);
            $classInfo['desc'] = str_replace('statics.xiumi.us', 'wyeth-xiumi.nibaguai.com', $desc);
            $classInfo['guide'] = str_replace('img.xiumi.us', 'wyeth-xiumi.nibaguai.com', $guide);
            $classInfo['guide'] = str_replace('statics.xiumi.us', 'wyeth-xiumi.nibaguai.com', $guide);

            $data = [
                'userInfo' => $userInfo,
                'classInfo' => $classInfo,
                'classlist' => $classlist,
                'recomClass' => $recomClass,
                'flashPic' => $flashPic,
                'mq' => $mq,
                'catCourses' => $catCourses
            ];
            $data = array_merge($data, $extra);
        }
        return $this->returnData($data);
    }

    public function getDetailCatCourse($uid, $cid, $page){
        $course = Course::where('id', $cid)->first();
        if(!$course){
            return $this->returnError('课程不存在');
        }
        if($course->cid == 0){
            return $this->returnData([]);
        }
        $cat_courses = Course::where('id', '<>', $cid)->where('cid', $course->cid)->where('display_status', 1)
            ->take($this->cat_course_num)->offset(($page - 1) * $this->cat_course_num)->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($cat_courses as $cat_course){
            $data[] = CourseService::getCourseInfoById($uid, $cat_course->id);
        }
        return $this->returnData($data);
    }

    //新版获取课程详情页推荐课程的方法
    public function getDetailRecommendCourse($uid, $cid){
        $course_num = 3;
        $course_tags = CourseTag::where('cid', $cid)->where('type', 0)->get()->toArray();
        if(count($course_tags) > 0){
            $ori_course_ids = [];
            foreach ($course_tags as $course_tag){
                $tag_id = $course_tag['tid'];
                $new_course_tags = CourseTag::where('tid', $tag_id)->where('cid', '<>', $cid)->get()->toArray();
                foreach ($new_course_tags as $new_course_tag){
                    $course = Course::where('id', $new_course_tag['cid'])->first();
                    if(!in_array($new_course_tag['cid'], $ori_course_ids) && CourseService::filterCourse($course)){
                        $ori_course_ids[] = $new_course_tag['cid'];
                    }
                }
                if(count($ori_course_ids) > 3){
                    $ori_course_ids = array_slice($ori_course_ids, 0, 10, true);
                    break;
                }
            }
            $coursesRecommend1 = [];
            $hot = [];
            foreach ($ori_course_ids as  $ori_course_id){
                $sign_num = CounterService::courseRegAllGet($ori_course_id);
                if(count($hot) < $course_num){
                    $hot[$sign_num] = $ori_course_id;
                }
                foreach ($hot as $k => $v){
                    if($sign_num > $k && count($hot) > $course_num){
                        unset($hot[$k]);
                        $hot[$sign_num] = $ori_course_id;
                    }
                }
            }
            foreach ($hot as $k){
                $coursesRecommend1[] = CourseService::getCourseInfoById($uid, $k);
            }
            return $coursesRecommend1;
        }else{
            $coursesRecommend1 = $this->coursesRecommend($cid);
            return $coursesRecommend1;
        }
    }

    //详情页获取推荐课程调用的方法
    public function coursesRecommend($cid, $notInCid = [])
    {
        $user = Auth::user();
        $userType = $user->type;
        $uid = Auth::id();
        //推荐课程 规则：同阶段  第一条 报名中  第二条 报名中  第三条 回顾
        //同阶段
        $coursesReviewSameStageNum = 3;
        $coursesReviewSameStage = $this->endCoursesRecommended($uid, $userType, 'review', $coursesReviewSameStageNum, $cid, true, $notInCid);
        $coursesRecommendSameStage = $coursesReviewSameStage;

        //如果同阶段够三条记录，那么取同阶段课程，否则用不同阶段的课程按照规则补齐三条
        if (count($coursesRecommendSameStage) == 3) {
            $coursesRecommend = $coursesRecommendSameStage;
        } else {
            foreach ($coursesRecommendSameStage as $v) {
                $notInCid[] = $v['cid'];
            }
            $coursesReviewDifferentStageNum = 3;
            $coursesReviewDifferentStage = $this->endCoursesRecommended($uid, $userType, 'review', $coursesReviewDifferentStageNum, $cid, false, $notInCid);
            $coursesRecommendDifferentStage = $coursesReviewDifferentStage;
            $coursesRecommend = array_merge($coursesRecommendSameStage, $coursesRecommendDifferentStage);
            $coursesRecommend = array_slice($coursesRecommend, 0, 3);
        }

        //如果课程url为空，则不跳转
        foreach ($coursesRecommend as $k => $v) {
            if ($v['url'] == '') {
                $coursesRecommend[$k]['url'] = 'javascript:void(0);';
            }
        }

        return $coursesRecommend;
    }

    //详情页获取推荐课程调用的方法
    public function endCoursesRecommended($uid, $userType, $type, $number, $cid, $isStage = true, $notInCid = [])
    {
        $page = 1;
        $page--;
        $offset = $page * $number;

        $query = Course::where('course.display_status', 1);
        $query->whereIn('course.user_type', [0, $userType]);
        $query->take($number);
        $query->offset($offset);
        if ($notInCid) {
            $query->whereNotIn('course.id',$notInCid);
        }
        if ($cid) {
            $query->where('course.id', '<>', $cid);
        }
        //通过课程类型查找课程  review 回顾  unsigned 报名中
        switch ($type) {
            case 'review':
                $query->where('course.status', '=', 3);
                $query->rightJoin('course_review as r', DB::raw('r.cid'), '=', 'course.id');
                $query->whereRaw('r.status=' . CourseReview::STATUS_YES);
                $query->orderBy('course.start_day', 'desc');
                $query->orderBy('course.start_time', 'desc');
                break;
            case 'unsigned':
                $userCourseCids = UserCourse::where('uid', $uid)->lists('cid')->toArray();
                $query->whereNotIn('course.id',$userCourseCids);
                $query->where('course.status', '=', 1);
                $query->orderBy('course.start_day', 'asc');
                $query->orderBy('course.start_time', 'asc');
                break;
        }

        /**
         * 通过用户阶段查找课程
         * stage 0获取不到宝宝生日 1孕早期  2孕晚期  3新手妈咪
         * 孕早期 0-3个月
         * 孕晚期 4-10个月
         */
        $user = User::find($uid);
        $babyBirthday = $user->baby_birthday;
        if($babyBirthday) {
            $babyBirthday = strtotime($babyBirthday);
        }
        $now = time();
        if (!$babyBirthday) {
            $stage = 0;
        } else if ($babyBirthday <= $now) {
            $stage = 3;
        } else if (($babyBirthday - $now) > 60*60*24*30*7) {
            $stage = 1;
        } else {
            $stage = 2;
        }
        if ($isStage == true && $stage > 0) {
            if($stage == 1){
                //早期
                $query->where('course.stage_from','<', 203);
                $query->where('course.stage_from','>', 100);
            }else if($stage == 2){
                //中晚期
                $query->where(function($query) {
                    $query->whereBetween('course.stage_to', [203, 210]);
                    $query->orWhere(function ($query) {
                        $query->whereBetween('course.stage_from', [203, 210]);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('course.stage_to', '<', 203);
                        $query->where('course.stage_from', '>', 210);
                    });
                });
            }else if($stage == 3){
                //宝宝
                $query->where('course.stage_to', '>=', 300);
                $query->where('course.stage_from', '>=', 100);
            }
        }
        $courses = $query->select('course.*')->get();
        $data = $this->attachedToDynamicData($courses, $uid);
        return $data['data'];
    }

    //获取全部页面的数据
    public function getAllPageData($type, $time, $tag, $page = 1, $page_size = 6){
        $user = Auth::user();
        $userType = $user->type;
        $uid = Auth::id();

        $page--;
        $offset = $page * $page_size;

        $query = Course::where('course.display_status', 1);
        $query->whereIn('course.user_type', [0, $userType]);
        $query->rightJoin('course_review as r', DB::raw('r.cid'), '=', 'course.id');
        $query->whereRaw('r.status=' . CourseReview::STATUS_YES);
//        $query->take($page_size);
//        $query->offset($offset);
        switch($type){
            case 0:     //最新
//                $query->orderBy(DB::raw('field(status,' . Course::COURSE_STATUS_ORDER . ')'));
                //$query->orderBy('number', 'desc');
                $query->orderBy('course.start_day', 'desc');
                $query->orderBy('course.start_time', 'desc');
                break;
            case 1:     //推荐
                if($tag != 0){
                    //如果选了标签则直接跳过
                    break;
                }
                $data = $this->getAllPageCourseRecommend($uid, $page, $page_size);
                return [
                    'ret' => 1,
                    'data' => $data
                ];
                break;
            case 2:     //热门
                $query->rightJoin('course_detail as d', DB::raw('d.cid'), '=', 'course.id')->orderBy('d.now_h5', 'desc');
                break;
            case 3:
                return $this->getTypeAllCourse($uid, $page, $time, $tag);
                break;
        }
        $tagId = $tag;
        if($tagId != 0){
            $courseTags = CourseTag::where('tid', $tagId)->get();
            $tagIds = [];
            foreach ($courseTags as $courseTag) {
                $tagIds[] = $courseTag['cid'];
            }
            $query->whereIn('course.id', $tagIds);
        }
        switch ($time){
            case 0:
                break;
            case 1:
                $query->where('course.stage_from','<=', 210);
                $query->where('course.stage_from','>=', 100);
                break;
            case 2:
                $query->whereBetween('course.stage_to', [300, 301]);
                $query->orWhere(function ($query) {
                    $query->whereBetween('course.stage_from', [300, 301]);
                });
                $query->orWhere(function ($query) {
                    $query->where('course.stage_to', '<', 301);
                    $query->where('course.stage_from', '>', 300);
                });
                break;
            case 3:
                $query->whereBetween('course.stage_to', [301, 302]);
                $query->orWhere(function ($query) {
                    $query->whereBetween('course.stage_from', [301, 302]);
                });
                $query->orWhere(function ($query) {
                    $query->where('course.stage_to', '<', 302);
                    $query->where('course.stage_from', '>', 301);
                });
                break;
            case 4:
                $query->where('course.stage_to', '>=', 303);
                $query->where('course.stage_from', '>=', 100);
                break;
        }
        $courses = $query->whereNotIn('course.brand', $this->getNoBrandArray())->select('course.*')->get();
        $data = $this->filterCourses($courses, $uid, $type, $time, $tag, $page, $page_size);
        return $data;
    }

    public function getTypeAllCourse($uid, $page, $time, $tag){
        //page已减过1
        $query_jinzhuang = Course::whereIn('brand', $this->JIN_ZHUANG_ARRAY)->where('display_status', 1);
        $query_qifu = Course::whereIn('brand', $this->QI_FU_ARRAY)->where('display_status', 1);
        $query_ganhuo = Course::whereNotIn('brand', array_merge($this->JIN_ZHUANG_ARRAY, $this->QI_FU_ARRAY))->where('display_status', 1);
        $tagId = $tag;
        if($tagId != 0){
            $courseTags = CourseTag::where('tid', $tagId)->get();
            $tagIds = [];
            foreach ($courseTags as $courseTag) {
                $tagIds[] = $courseTag['cid'];
            }
            $query_jinzhuang->whereIn('course.id', $tagIds);
            $query_qifu->whereIn('course.id', $tagIds);
            $query_ganhuo->whereIn('course.id', $tagIds);
        }
        switch ($time){
            case 0:
                break;
            case 1:
                $query_jinzhuang->where('course.stage_from','<=', 210)->where('course.stage_from','>=', 100);
                $query_qifu->where('course.stage_from','<=', 210)->where('course.stage_from','>=', 100);
                $query_ganhuo->where('course.stage_from','<=', 210)->where('course.stage_from','>=', 100);
                break;
            case 2:
                $query_jinzhuang->whereIn('brand', $this->JIN_ZHUANG_ARRAY)->whereBetween('course.stage_to', [300, 301]);
                $query_jinzhuang->orWhere(function ($query) {
                    $query->whereIn('brand', $this->JIN_ZHUANG_ARRAY)->whereBetween('course.stage_from', [300, 301]);
                });
                $query_jinzhuang->orWhere(function ($query) {
                    $query->whereIn('brand', $this->JIN_ZHUANG_ARRAY)->where('course.stage_to', '<', 301);
                    $query->whereIn('brand', $this->JIN_ZHUANG_ARRAY)->where('course.stage_from', '>', 300);
                });
                $query_qifu->whereIn('brand', $this->QI_FU_ARRAY)->whereBetween('course.stage_to', [300, 301]);
                $query_qifu->orWhere(function ($query) {
                    $query->whereIn('brand', $this->QI_FU_ARRAY)->whereBetween('course.stage_from', [300, 301]);
                });
                $query_qifu->orWhere(function ($query) {
                    $query->whereIn('brand', $this->QI_FU_ARRAY)->where('course.stage_to', '<', 301);
                    $query->whereIn('brand', $this->QI_FU_ARRAY)->where('course.stage_from', '>', 300);
                });
                $query_ganhuo->whereNotIn('brand', array_merge($this->JIN_ZHUANG_ARRAY, $this->QI_FU_ARRAY))->whereBetween('course.stage_to', [300, 301]);
                $query_ganhuo->orWhere(function ($query) {
                    $query->whereNotIn('brand', array_merge($this->JIN_ZHUANG_ARRAY, $this->QI_FU_ARRAY))->whereBetween('course.stage_from', [300, 301]);
                });
                $query_ganhuo->orWhere(function ($query) {
                    $query->whereNotIn('brand', array_merge($this->JIN_ZHUANG_ARRAY, $this->QI_FU_ARRAY))->where('course.stage_to', '<', 301);
                    $query->whereNotIn('brand', array_merge($this->JIN_ZHUANG_ARRAY, $this->QI_FU_ARRAY))->where('course.stage_from', '>', 300);
                });
                break;
            case 3:
                $query_jinzhuang->whereIn('brand', $this->JIN_ZHUANG_ARRAY)->whereBetween('course.stage_to', [301, 302]);
                $query_jinzhuang->orWhere(function ($query) {
                    $query->whereIn('brand', $this->JIN_ZHUANG_ARRAY)->whereBetween('course.stage_from', [301, 302]);
                });
                $query_jinzhuang->orWhere(function ($query) {
                    $query->whereIn('brand', $this->JIN_ZHUANG_ARRAY)->where('course.stage_to', '<', 302);
                    $query->whereIn('brand', $this->JIN_ZHUANG_ARRAY)->where('course.stage_from', '>', 301);
                });
                $query_qifu->whereIn('brand', $this->QI_FU_ARRAY)->whereBetween('course.stage_to', [301, 302]);
                $query_qifu->orWhere(function ($query) {
                    $query->whereIn('brand', $this->QI_FU_ARRAY)->whereBetween('course.stage_from', [301, 302]);
                });
                $query_qifu->orWhere(function ($query) {
                    $query->whereIn('brand', $this->QI_FU_ARRAY)->where('course.stage_to', '<', 302);
                    $query->whereIn('brand', $this->QI_FU_ARRAY)->where('course.stage_from', '>', 301);
                });
                $query_ganhuo->whereNotIn('brand', array_merge($this->JIN_ZHUANG_ARRAY, $this->QI_FU_ARRAY))->whereBetween('course.stage_to', [301, 302]);
                $query_ganhuo->orWhere(function ($query) {
                    $query->whereNotIn('brand', array_merge($this->JIN_ZHUANG_ARRAY, $this->QI_FU_ARRAY))->whereBetween('course.stage_from', [301, 302]);
                });
                $query_ganhuo->orWhere(function ($query) {
                    $query->whereNotIn('brand', array_merge($this->JIN_ZHUANG_ARRAY, $this->QI_FU_ARRAY))->where('course.stage_to', '<', 302);
                    $query->whereNotIn('brand', array_merge($this->JIN_ZHUANG_ARRAY, $this->QI_FU_ARRAY))->where('course.stage_from', '>', 301);
                });
                break;
            case 4:
                $query_jinzhuang->where('course.stage_to', '>=', 303)->where('course.stage_from', '>=', 100);
                $query_qifu->where('course.stage_to', '>=', 303)->where('course.stage_from', '>=', 100);
                $query_ganhuo->where('course.stage_to', '>=', 303)->where('course.stage_from', '>=', 100);
                break;
        }
        $brand = (new Crm())->getMemberBrand();
        if($brand == 4){        //金装
            $count_jinzhuang = $query_jinzhuang->count();
            $jinzhuang_array = $query_jinzhuang->whereIn('brand', $this->JIN_ZHUANG_ARRAY)->offset($page * 2)->take(2)->get()->toArray();
            $count = count($jinzhuang_array);
            if($count == 1){
                $ganhuo_array = $query_ganhuo->offset($page * 3)->take(4)->get()->toArray();
            }elseif($count == 2){
                $ganhuo_array = $query_ganhuo->offset($page * 3)->take(3)->get()->toArray();
            }else{              //  count = 0
                $length = $count_jinzhuang % 2 == 1 ? ($count_jinzhuang - 1) / 2 : $count_jinzhuang / 2;
                $ganhuo_array = $query_ganhuo->offset($length * 3 + 4 + ($page - 1 - $length) * 5)->take(5)->get()->toArray();
            }
            $data = array_merge($jinzhuang_array, $ganhuo_array);
        }elseif($brand == 10){  //启赋
            $count_qifu = $query_qifu->count();
            $qifu_array = $query_qifu->offset($page * 2)->take(2)->get()->toArray();
            $count = count($qifu_array);
            if($count == 1){
                $ganhuo_array = $query_ganhuo->offset($page * 3)->take(4)->get()->toArray();
            }elseif($count == 2){
                $ganhuo_array = $query_ganhuo->offset($page * 3)->take(3)->get()->toArray();
            }else{              //  count = 0
                $length = $count_qifu % 2 == 1 ? ($count_qifu - 1) / 2 : $count_qifu / 2;
                $ganhuo_array = $query_ganhuo->offset($length * 3 + 4 + ($page - 1 - $length) * 5)->take(5)->get()->toArray();
            }
            $data = array_merge($qifu_array, $ganhuo_array);
        }else{                  //无主
            $jinzhuang = $query_jinzhuang->offset($page)->first();
            $qifu = $query_qifu->offset($page)->first();
            $count_jinzhuang = $query_jinzhuang->count();
            $count_qifu = $query_qifu->count();
            $brand_array = [$jinzhuang, $qifu];
            $length_max = $count_jinzhuang > $count_qifu ? $count_jinzhuang : $count_qifu;
            $length_min = $count_jinzhuang > $count_qifu ? $count_qifu : $count_jinzhuang;
            if($page < $length_min){
                $ganhuo_array = $query_ganhuo->offset($page * 3)->take(3)->get()->toArray();
            }elseif($length_min - 1 < $page && $page < $length_max){
                $ganhuo_array = $query_ganhuo->offset($length_min * 3 + 4 * ($page - $length_min))->take(4)->get()->toArray();
            }else{
                $ganhuo_array = $query_ganhuo->offset($length_min * 3 + 4 * ($length_max - $length_min) + 5 * ($page - $length_max))->take(5)->get()->toArray();
            }
            $data = array_merge($brand_array, $ganhuo_array);
        }
        $ret = [];
        foreach ($data as $item){
            if(count($item) > 0){
                $ret[] = CourseService::getCourseInfoById($uid, $item['id']);
            }
        }
        return $this->returnData($ret);
    }


    public function getAllPageCourseRecommend($uid, $page, $page_size){
        //page已减过1
        $lastPageSize = $page * $page_size;
        $data = [];
        try{
            $response = (new CIDataRecommend())->recomend($uid);
        }catch (RequestException $e){
            return [];
        }
        if(is_array($response) && array_key_exists('data', $response) && array_key_exists('items', $response['data'])){
            $items = $response['data']['items'];
            $totalData = [];
            $no_brand_array = $this->getNoBrandArray();
            if(is_array($items)){
                foreach ($items as $item){
                    $course = Course::where('id', $item)->whereNotIn('brand', $no_brand_array)->where('display_status', 1)->first();
                    if($course && CourseService::filterCourse($course)){
                        $totalData[] = CourseService::getCourseInfoById($uid, $item);
                    }
                }
                for($i = 0; $i < $page_size; $i++){
                    if(($i + $lastPageSize) < count($totalData)){
                        $data[] = $totalData[$i + $lastPageSize];
                    }
                }
                return $data;
            }
        }else{
            return $data;
        }
    }

    //根据course数组获取数据
    public function attachedToDynamicData($courses, $uid)
    {
        $d = [];
        if (count($courses) == 0) {
            return [
                'ret' => 1,
                'data' => []
            ];
        }

        //课程信息
        foreach ($courses as $row) {
            if(CourseService::filterCourse($row)){
                $result = CourseService::getCourseInfoById($uid, $row->id);
                $d[] = $result;
            }
        }
        $data = [
            'ret' => 1,
            'data' => $d
        ];
        return $data;
    }

    //根据页数和每页大小返回过滤后的课程数据
    public function filterCourses($courses, $uid, $type, $time, $tag, $page = 1, $page_size = 6){
        $lastPageSize = $page * $page_size;
        $d = [];
        if (count($courses) == 0) {
            return [
                'ret' => 1,
                'data' => []
            ];
        }
        $totalData = Cache::tags(CacheKey::ALL_PAGE_DATA . $uid)->get(CacheKey::ALL_PAGE_DATA . $uid . $type . $time . $tag);
        if(!$totalData){
            //课程信息
            foreach ($courses as $row) {
                if(CourseService::filterCourse($row)){
                    $totalData[] = $row->id;
                }
            }
            Cache::tags(CacheKey::ALL_PAGE_DATA . $uid)->put(CacheKey::ALL_PAGE_DATA . $uid . $type . $time . $tag, $totalData, 5);
        }

        for($i = 0; $i < $page_size; $i++){
            if(($i + $lastPageSize) < count($totalData)){
                $d[] = CourseService::getCourseInfoById($uid, $totalData[$i + $lastPageSize]);
            }
        }
        $data = [
            'ret' => 1,
            'data' => $d
        ];
        return $data;
    }

    //获取报名人数
    public static function reg($cid)
    {
        return CounterService::courseRegAllGet($cid);
    }

    //获取没有标签的课程
    public function getNoTagCourse(){
        $sql = "select * from course where display_status = 1";
        $courses = DB::connection('mysql_read')->select($sql);
        $data = [];
        foreach ($courses as $course){
            $courseTag = DB::connection('mysql_read')->select("select * from course_tags where cid = " . $course->id . " and type = 0");
            if(count($courseTag) < 1){
                $data[] = [
                    'id' => $course->id,
                    'title' => $course->title
                ];
            }
        }
        return $this->returnData($data);
    }

    //分享课程
    public function shareCourse($uid, $cid){
        (new TaskRepository())->share($uid, $cid);
        return $this->returnData([]);
    }

    //获取套课数据
    public function cat($cid, $uid, $page, $page_size)
    {
        //处理当前用户id, 合并到课程信息
        $temp_userCourses = userCourse::where('uid', $uid)->get();
        $userCourses = [];
        foreach ($temp_userCourses as $temp_userCourse) {
            $userCourses[$temp_userCourse['cid']] = $temp_userCourse['uid'];
        }

        //计算当前报名人数
        $number = CounterService::courseCatRegAllGet($cid) + 2937;

        //取得分类数据信息
        $courseCat = CourseCat::find($cid);
        if(!$courseCat){
            return (new WyethError())->NO_COURSE;
        }
        $courseCat->likeNum = $this->getCatLikeNum($cid)['data'];
        $courseCat->saveNum = $this->getCatSaveNum($cid)['data'];
        $courseCat->isLike = $this->isLikeACat($cid);
        $courseCat->isSave = $this->isSaveACat($cid);
        $courseCat->number = $number;
        //获取购买信息
        $user_buy_course = UserBuyCourses::where([
            'uid' => $uid,
            'type' => 1,
            'cid' => $cid,
            'trade_status' => 1
        ])->first();
        $buy_count = UserBuyCourses::where([
            'type' => 1,
            'cid' => $cid,
            'trade_status' => 1
        ])->count();
        $courseCat->purchased = ($user_buy_course || $courseCat->price == 0)? UserBuyCourses::STATUS_CAN_READ : UserBuyCourses::STATUS_BUY_CAT;
        $courseCat->buyCount = $buy_count;

        //取得当前分类下的课程信息
        if($cid == 39){
            $query = Course::where('course.is_competitive', 1);
        }else{
            $query = Course::where('course.cid', $cid);
        }
        $user = Auth::user();
        $user_type = $user->type;
        $query->where('course.display_status', 1);
        $query->whereIn('course.user_type', [0, $user_type]);
        $query->leftJoin('course_review as r', DB::raw('r.cid'), '=', 'course.id');
        $query->where('r.status', 1);
        $query->orderBy(DB::raw('field(course.status,' . Course::COURSE_STATUS_ORDER . ')'));
        $query->orderBy('course.cat_order');
        $query->orderBy('course.start_day');
        $query->orderBy('course.start_time');
        if($courseCat->show_type == 2 && $page && $page_size){
            $offset = ($page - 1) * $page_size;
            $query->take($page_size);
            $query->offset($offset);
        }
        $courses = $query->get();
        $courseArray = [];
        $is_subscribed = true;
        $teacher_info = [];
        foreach ($courses as $key => $course){
            $cid = $course['cid'];
            $courseInfo = CourseService::getCourseInfoById($uid, $cid);
            $tag = Tag::where('name', $courseInfo['teacher_name'])->first();
            $tid = $tag->id;
            if(count($teacher_info) == 0){
                $teacher_info['teacher_id'] = $courseInfo['teacher_id'];
                $teacher_info['teacher_name'] = $courseInfo['teacher_name'];
                $teacher_info['teacher_hospital'] = $courseInfo['teacher_hospital'];
                $teacher_info['teacher_position'] = $courseInfo['teacher_position'];
                $teacher_info['teacher_avatar'] = $courseInfo['teacher_avatar'];
                $teacher_info['teacher_desc'] = $courseInfo['teacher_desc'];
                $teacher_info['concern'] =  TeacherRepository::isConcerned($uid, $tid) == 1 ? true : false;
            }
            if (isset($userCourses[$cid])) {
                $courseInfo['sign'] = true;
            }else{
                $courseInfo['sign'] = false;
                if($is_subscribed){
                    $is_subscribed = false;
                }
            }
            $courseArray[] = $courseInfo;
        }

        //用户信息，用于查看用户是否分享或者关注，
        $userModel = new User();
        $tempUserInfo = $userModel->getUserInfo($user);

        $userInfo = [
            'is_crmmember' => $tempUserInfo->crm_status,
            'is_subscribed' => $is_subscribed,
            'id' => $tempUserInfo->id,
        ];

        //分享参数
        $share = [
            'title' => '魔栗妈咪学院',
            'link' => '',
            'imgUrl' => $courseCat->img,
            'desc' => '我已经报名了【' . $courseCat->name . '】每天15分钟，实用育儿知识轻松学，你也快来吧！',
        ];

        return $this->returnData([
            'courseCat' => $courseCat,
            'courses' => $courseArray,
            'teacher_info' => $teacher_info,
            'share' => $share,
            'userInfo' => $userInfo]);
    }

    public function signCat($cid, $user){
        $uid = $user->id;
        if($cid == 39){
            $courses = Course::where('is_competitive', 1)->get()->toArray();
        }else{
            $courses = Course::where('cid', $cid)->get()->toArray();
        }
        foreach ($courses as $course){
            //记录用户报名信息
            $userCourse = UserCourse::where(['cid' => $course['id'], 'uid' => $uid])->first();
            if (empty($userCourse)) {
                $userCourse = new UserCourse();
                $userCourse->cid = $course['id'];
                $userCourse->uid = $uid;
                $userCourse->channel = Session::get('channel');
                $userCourse->save();

                //记录用户统计日志
                $courseStat = CourseStat::where(['uid' => $uid, 'cid' => $course['id']])->first();
                if ($courseStat) {
                    $courseStat->sign_time = date("Y-m-d H:i:s");
                    $courseStat->save();
                }

                //CIData统计报名
                Cidata::init(config('oneitfarm.appkey'));
                Cidata::sendEvent($uid, $user->channel, null, 'sign', ['cid' => $course['id'], 'wyeth_channel' => Session::get('channel')]);

                $is_subscribed = $user->subscribe_status;
                if ($is_subscribed && $user->type == User::OPENID_TYPE_WX) {
                    // 推荐课程
                    $c = Course::where('id', $course['id'])->first();
                    $params = CourseService::recommendCourseIdGet($user, $c);
                    Log::info('crmSign', [
                        'uid' => $user->id,
                        'sid' => $params?$params['sign_up_course']->id : 0,
                        'rid' => $params?$params['recommend_course']->id : 0
                    ]);
                    if ($params) {
                        $job = (new SendTemplateMessageBySignUp($params));
                        $this->dispatch($job);
                    }
                }
            }
        }
        return $this->returnData([]);
    }

    public function getNoBrandArray(){
        $brand = (new Crm())->getMemberBrand();
        $no_brand_array = [];
        if($brand == self::QI_FU){
            $no_brand_array = $this->JIN_ZHUANG_ARRAY;
        }elseif($brand == self::JIN_ZHUANG){
            $no_brand_array = $this->QI_FU_ARRAY;
        }
        return $no_brand_array;
    }
}