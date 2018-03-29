<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/8/15
 * Time: 下午6:20
 */

namespace App\Repositories;


use App\Helpers\WyethError;

class BaseRepository
{
    /**
     * @var WyethError
     */
    protected $error;

    public function __construct()
    {
        $this->error = new WyethError();
    }

    //失败返回
    protected function returnError($msg = '', $code = -1){
        return [
            'ret' => -1,
            'code' => $code,
            'msg' => $msg
        ];
    }

    //成功返回数据
    protected function returnData($data = []){
        return [
            'ret' => 1,
            'data' => $data
        ];
    }
}