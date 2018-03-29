<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/14
 * Time: 上午10:17
 */

namespace App\Http\Controllers\Wyeth;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;

class UserController extends WyethBaseController{
    protected $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    //获取登录信息
    public function getLoginInfo(Request $request){
        $data = $this->userRepository->getLoginInfo($request->input('platform', 'mp'));
        return $this->returnData($data);
    }

    public function getUserInfo(){
        $data = $this->userRepository->getUserInfo();
        return $this->returnData($data);
    }

    public function sign(Request $request){ //签到
        $data = $this->userRepository->userSign();
        return $data;
    }

    public function getMq(Request $request){
        $type = $request->input('type');
        if($type == NULL){
            return $this->error->INVALID_PARAM;
        }
        return $this->returnData((new TaskRepository())->getMq($this->uid, $type));
    }

    public function getTask(Request $request){
        return (new TaskRepository())->getTask($this->uid);
    }

    //设置孕期提醒的宝宝生日
    public function setPregdate(Request $request){
        return $this->userRepository->setPregdate($request->input('pregdate'));
    }
    
}