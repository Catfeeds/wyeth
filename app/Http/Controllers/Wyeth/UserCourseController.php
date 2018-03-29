<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/8/19
 * Time: 下午4:39
 */
namespace App\Http\Controllers\Wyeth;

use Illuminate\Http\Request;
use App\Repositories\UserCourseRepository;
use Auth;
use App\Services\MqService;


class UserCourseController extends WyethBaseController{
    protected $userCourseRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userCourseRepository = new UserCourseRepository();
    }

    public function getUserDynamic(Request $request){
        $uid = Auth::id();
        $page = $request->input('page');
        $page_size = $request->input('page_size');
        $data = $this->userCourseRepository->getUserDynamic($uid, $page, $page_size);
        return $data;
    }
}