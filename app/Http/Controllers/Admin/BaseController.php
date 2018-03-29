<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * BaseController
 * Class BaseController
 * @package App\Http\Controllers\Admin
 */
class BaseController extends Controller
{

    /**
     * 输出错误
     * @param $msg  错误
     * @return mixed
     */
    protected function error($msg)
    {
        return view('admin.error', ['msg' => $msg]);
    }

    /**
     * ajax error
     * @param $msg
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function ajaxError($msg, $code = 0)
    {
        $result = ['status' => $code, 'msg' => $msg];
        return response()->json($result);
    }

    /**
     * ajax msg
     * @param $msg
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function ajaxMsg($msg, $code = 1)
    {
        $result = ['status' => $code, 'msg' => $msg];
        return response()->json($result);
    }
}
