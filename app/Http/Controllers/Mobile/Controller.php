<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * 报错页面, 必须return
     * @param  string $message 报错语
     * @return View
     */
    protected function showError($message)
    {
        return view('mobile.message_error', ['message' => $message]);
    }

    /**
     * 新版回顾保存页面
     * @param $type 1 回顾不存在   2 课件不存在
     * @param $data 推荐的三堂课程
     * @return View
     */
    protected function reviewError($type, $data)
    {
        if ($type == 1) {
            return view('mobile.review_error', $data);
        } elseif ($type == 2) {
            return view('mobile.review_error', $data);
        }

    }
    /**
     * show ajaxErro
     * @param  [type]  $msg    [description]
     * @param  integer $status [description]
     * @return [type]          [description]
     */
    protected function showAjaxError($msg, $status = 1)
    {
        $result = [
            'status' => $status,
            'error_msg' => $msg,
            'data' => [],
        ];
        return response()->json($result);
    }

    /**
     * show ajax
     * @param  [type]  $data   [description]
     * @param  integer $status [description]
     * @return [type]          [description]
     */
    protected function showAjax($data, $status = 0)
    {
        $result = [
            'status' => $status,
            'error_msg' => '',
            'data' => $data,
        ];
        return response()->json($result);
    }

}
