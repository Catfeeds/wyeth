<?php
/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/7/20
 * Time: 下午4:18
 */


namespace App\Http\Controllers\Wyeth;

use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends WyethBaseController{

    protected $taskRepository;

    public function __construct()
    {
        parent::__construct();
        $this->taskRepository = new TaskRepository();
    }

    public function getMq(Request $request){
        $type = $request->input('type');
        return $this->taskRepository->getMq($this->uid, $type);
    }
    
    public function getTask(Request $request){
        return $this->taskRepository->getTask($this->uid);
    }
}