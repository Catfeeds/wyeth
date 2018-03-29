<?php

namespace App\Http\Controllers\Wyeth;

use App\Helpers\WyethError;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class WyethBaseController
 * @package App\Http\Controllers\Wyeth
 */
class WyethBaseController extends Controller
{
    /**
     * @var WyethError
     */
    protected $error;

    protected $user;
    protected $uid;

    public function __construct()
    {
        $this->error = new WyethError();
        $this->user = Auth::user();
        $this->uid = Auth::id();
    }

    //失败返回
    protected function returnError($msg = '', $code = -1){
        return response()->json([
            'ret' => -1,
            'code' => $code,
            'msg' => $msg
        ]);
    }

    //成功返回数据
    protected function returnData($data = []){
        return response()->json([
            'ret' => 1,
            'data' => $data
        ]);
    }

    //成功返回数组
    protected  function returnArr($arr){
        $arr['ret'] = 1;
        return response()->json($arr);
    }

    protected function returnMessage($message){
        if($message == ''){
            $arr['ret'] = 1;
            return response()->json($arr);
        }else{
            $arr['ret'] = -1;
            $arr['msg'] = $message;
            return response()->json($arr);
        }
    }
}
