<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/20
 * Time: 下午5:50
 */

namespace App\Http\Controllers\Wyeth;

use App\Repositories\UserTagRepository;
use Illuminate\Http\Request;
use App\Repositories\CourseRepository;
use App\Repositories\TagRepository;
use App\Repositories\AppConfigRepository;
use App\Repositories\TeacherRepository;
use App\Models\CourseTag;
use Auth;

class TeacherController extends WyethBaseController{
    protected $courseRepository;
    protected $tagRepository;
    protected $appConfigRepository;
    protected $userTagRepository;
    protected $teacherRepository;

    public function __construct()
    {
        parent::__construct();
        $this->courseRepository = new CourseRepository();
        $this->tagRepository = new TagRepository();
        $this->appConfigRepository = new AppConfigRepository();
        $this->userTagRepository = new UserTagRepository();
        $this->teacherRepository = new TeacherRepository();
    }

    public function observePro(Request $request){
        $pro_id = $request->input('pro_id');
        $is_cancel = $request->input('is_cancel');
        $data = $this->teacherRepository->observePro($pro_id, $is_cancel);
        return $data;
    }

    public function getProInfo(Request $request){
        $pro_id = $request->input('pro_id');
        $data = $this->teacherRepository->getProInfo($pro_id);
        return $data;
    }

    public function getTeacherCourse(Request $request){
        $uid = Auth::id();
        $tid = $request->input('tid');
        $page = $request->input('page');
        $page_size = $request->input('page_size');
        $data = $this->teacherRepository->getTeacherCourse($uid, $tid, $page, $page_size);
        return $this->returnData($data);
    }

}