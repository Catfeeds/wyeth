<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/13
 * Time: 下午3:54
 */

namespace App\Http\Controllers\Wyeth;


use App\Repositories\ErrorLogRepository;
use Illuminate\Http\Request;

class ErrorLogController extends WyethBaseController
{
    protected $errorLogRepository;

    public function __construct()
    {
        parent::__construct();
        $this->errorLogRepository = new ErrorLogRepository();
    }

    public function add(Request $request){
        return $this->errorLogRepository->add($this->uid, $request->all());
    }

    public function getList(Request $request){
        return $this->errorLogRepository->getList($request->input('page'),$request->input('page_size'),$request->input('uid'),$request->input('start'),$request->input('end'));
    }
}