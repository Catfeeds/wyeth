<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/7
 * Time: 上午9:52
 */

namespace App\Http\Controllers\Wyeth;

use App\CIData\Cidata;
use App\Helpers\SessionKey;
use App\Models\CourseReview;
use App\Repositories\CourseListenRepository;
use Illuminate\Http\Request;
use App\Repositories\AppConfigRepository;
use App\Repositories\CourseRepository;
use App\Repositories\CourseReviewRepository;
use App\Repositories\TagRepository;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // cat
use View;

class CourseController extends WyethBaseController{

    protected $courseRepository;
    protected $tagRepository;
    protected $appConfigRepository;
    protected $courseReviewRepository;

    public function __construct()
    {
        parent::__construct();
        $this->courseRepository = new CourseRepository();
        $this->tagRepository = new TagRepository();
        $this->appConfigRepository = new AppConfigRepository();
        $this->courseReviewRepository = new CourseReviewRepository();
    }


    public function getHotCourse(){
        $uid = Auth::id();
        $data = $this->courseRepository->getHotCourse($uid);
        return $data;
    }

    public function getCourseRecommend(Request $request){
        $uid = Auth::id();
//        $data = $this->courseRepository->getOldCourseRecommend($uid);
        $data = $this->courseRepository->getCourseRecommend($uid);
        return $data;
    }

    public function getNewCourse(){
        $uid = Auth::id();
        $data = $this->courseRepository->getNewCourse($uid);
        return $data;
    }



    public function getDetail(Request $request){
        $user = Auth::user();
        $cid = $request->input('course_id');
        return $this->courseRepository->getDetail($user, $cid);
    }

    public function getDetailCatCourse(Request $request){
        $uid = Auth::id();
        $cid = $request->input('course_id');
        $page = $request->input('page');
        return $this->courseRepository->getDetailCatCourse($uid, $cid, $page);
    }

    public function getAd(Request $request){
        $site = $request->input('site');
        if($site == 'homePlayback'){
            $flashPics1 = $this->appConfigRepository->getHomePlayback1();
            return $this->returnData($flashPics1);
        }elseif($site == 'homeBottom'){
            $flashPics2 = $this->appConfigRepository->getHomePlayback2();
            return $this->returnData($flashPics2);
        }
    }

    public function signCourse(Request $request){
        $course_id = $request->input('cid');
        $data = $this->courseRepository->signCourse($course_id);
        return $data;
    }

    public function like(Request $request){
        $cid = $request->input('cid');
        $is_cancel = $request->input('is_cancel');
        if($is_cancel == 1){
            $data = $this->courseRepository->cancelAReviewLike($cid);
        }else{
            $data = $this->courseRepository->giveAReviewLike($cid);
        }
        return $data;
    }

    public function cancelLike(Request $request){
        $cid = $request->input('cid');
        $data = $this->courseRepository->cancelAReviewLike($cid);
        return $data;
    }

    public function getLikeNum(Request $request){
        $cid = $request->input('cid');
        $data = $this->courseRepository->getLikeNum($cid);
        return $data;
    }

    public function save(Request $request){
        $cid = $request->input('cid');
        $is_cancel = $request->input('is_cancel');
        if($is_cancel == 1){
            $data = $this->courseRepository->cancelAReviewSave($cid);
        }else{
            $data = $this->courseRepository->saveAReview($cid);
        }
        return $data;
    }

    public function cancelSave(Request $request){
        $cid = $request->input('cid');
        $data = $this->courseRepository->cancelAReviewSave($cid);
        return $data;
    }

    public function getSaveNum(Request $request){
        $cid = $request->input('cid');
        $data = $this->courseRepository->getSaveNum($cid);
        return $data;
    }

    public function likeCat(Request $request){
        $cid = $request->input('cid');
        $is_cancel = $request->input('is_cancel');
        if($is_cancel == 1){
            $data = $this->courseRepository->cancelACatLike($cid);
        }else{
            $data = $this->courseRepository->giveACatLike($cid);
        }
        return $data;
    }

    public function getCatLikeNum(Request $request){
        $cid = $request->input('cid');
        $data = $this->courseRepository->getCatLikeNum($cid);
        return $data;
    }

    public function saveCat(Request $request){
        $cid = $request->input('cid');
        $is_cancel = $request->input('is_cancel');
        if($is_cancel == 1){
            $data = $this->courseRepository->cancelACatSave($cid);
        }else{
            $data = $this->courseRepository->saveACat($cid);
        }
        return $data;
    }

    public function getCatSaveNum(Request $request){
        $cid = $request->input('cid');
        $data = $this->courseRepository->getCatSaveNum($cid);
        return $data;
    }

    public function likeOrSaveCourse(Request $request){
        $cid = $request->input('cid');
        $is_cancel = $request->input('is_cancel');
        $is_like = $request->input('is_like');
        $type = $request->input('type');
        if($type == 1){
            if($is_like == 1){
                return $is_cancel? $this->courseRepository->cancelAReviewLike($cid) : $this->courseRepository->giveAReviewLike($cid);
            }else{
                return $is_cancel? $this->courseRepository->cancelAReviewSave($cid) : $this->courseRepository->saveAReview($cid);
            }
        }elseif($type == 2){
            if($is_like == 1){
                return $is_cancel? $this->courseRepository->cancelACatLike($cid) : $this->courseRepository->giveACatLike($cid);
            }else{
                return $is_cancel? $this->courseRepository->cancelACatSave($cid) : $this->courseRepository->saveACat($cid);
            }
        }
    }

    public function getProCourse(Request $request){
        $uid = Auth::id();
        $pro_id = $request->input('pro_id');
        $page = $request->input('page');
        $page_size = $request->input('page_size');
        $data = $this->courseRepository->getProCourse($uid, $pro_id, $page, $page_size);
        return $data;
    }

    public function getNoTagCourse(){
        $data = $this->courseRepository->getNoTagCourse();
        return $data;
    }

    public function shareCourse(Request $request){
        $uid = $this->uid;
        $cid = $request->input('cid');
        if($cid == NULL){
            return $this->error->INVALID_PARAM;
        }
        $data = $this->courseRepository->shareCourse($uid, $cid);
        return $data;
    }

    public function cat(Request $request){
        $cid = $request->input('cid');
        $page = $request->input('page');
        $page_size = $request->input('page_size');
        $uid = Auth::id();
        $data = $this->courseRepository->cat($cid, $uid, $page, $page_size);
        return $data;
    }

    public function signCat(Request $request){
        $cid = $request->input('cid');
        $user = Auth::user();
        $data = $this->courseRepository->signCat($cid, $user);
        return $data;
    }

    function returnSuccess($data, $success){
        if($success && $data){
            return response()->json([
                'ret' => 1,
                'data' => $data,
            ]);
        }else{
            return response()->json([
                'ret' => 0,
                'msg' => 'error',
            ]);
        }
    }

    //统计首页加载时间,end
    public function loadHome(Request $request){
        $user = Auth::user();
        $version = Session::get(SessionKey::USER_VERSION);
        //统计首页加载时间,打点
        Cidata::init(config('oneitfarm.appkey'));
        $event_params = [
            'action' => 'end',
            'version' => $version,
        ];
        Cidata::sendEvent($user->id, $user->channel, null, 'load_home', $event_params);
        return 1;
    }
    
    //用户每分钟听课记录
    public function courseListenAdd(Request $request){
        $course_listen = new CourseListenRepository();
        return $course_listen->add($request->input('cid'));
    }
}