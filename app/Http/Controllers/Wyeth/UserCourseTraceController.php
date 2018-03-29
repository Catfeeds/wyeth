<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/26
 * Time: 下午1:42
 */

namespace App\Http\Controllers\Wyeth;

use Illuminate\Http\Request;
use App\Repositories\AppConfigRepository;
use App\Repositories\CourseRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserCourseTraceRepository;


use Illuminate\Support\Facades\Session; // cat
use Auth;
use View;

class UserCourseTraceController extends WyethBaseController{
    protected $courseRepository;
    protected $tagRepository;
    protected $appConfigRepository;
    protected $userCourseTraceRepository;

    public function __construct()
    {
        parent::__construct();
        $this->courseRepository = new CourseRepository();
        $this->tagRepository = new TagRepository();
        $this->appConfigRepository = new AppConfigRepository();
        $this->userCourseTraceRepository = new UserCourseTraceRepository();
    }

    public function getTraceCourse(Request $request){
        $uid = Auth::id();
        $page = $request->input('page');
        $page_size = $request->input('page_size');
        if(!$page || !$page_size){
            return $this->returnError('参数不正确');
        }
        $data = $this->userCourseTraceRepository->getTraceCourse($uid, $page, $page_size);
        return $data;
    }

    public function setTraceCourse(Request $request){
        $user = Auth::user();
        $cid = $request->input('cid');
        $data = UserCourseTraceRepository::setCourseTrace($user, $cid);
        return $this->returnData($data);
    }

    public function getTraceCourseByDate(Request $request){
        $date = $request->input('date');
        $uid = Auth::id();
        $data = $this->userCourseTraceRepository->getTraceCourseByDate($uid, $date);
        return $data;
    }
}