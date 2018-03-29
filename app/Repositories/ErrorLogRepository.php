<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/9/20
 * Time: 上午10:05
 */

namespace App\Repositories;

//记录客户端的error
use App\Models\ErrorLog;
use App\Services\Email;

class ErrorLogRepository extends BaseRepository
{
    public function add($uid, $params){
        $error_log = new ErrorLog();
        $error_log->uid = $uid;
        $error_log->url = issetKey($params, 'url');
        $error_log->msg = issetKey($params, 'msg');
        $error_log->ua = issetKey($params, 'ua');
        $error_log->stack = issetKey($params, 'stack');
        $error_log->save();

        //发邮件
        $content = "uid: {$error_log->uid} <br>
                    url: {$error_log->url} <br>
                    ua: {$error_log->ua} <br>
                    msg: {$error_log->msg} <br>
                    stack: {$error_log->stack} <br>";
        Email::SendEmail('weex error', $content, Email::EMAIL_BOX_WEEX);

        return $this->returnData();
    }
    
    public function getList($page = 1, $page_size = 10, $uid = null, $start = null, $end = null){
        $page = $page ?: 1;
        $page_size = $page_size ?: 10;
        
        $error_log = ErrorLog::skip(($page - 1) * $page_size)->take($page_size);
        if ($uid){
            $error_log->where('uid', $uid);
        }
        if ($start){
            $error_log->where('created_at', '>', $start);
        }
        if ($end){
            $error_log->where('created_at', '<', $end);
        }
        $result = $error_log->orderBy('id', 'desc')->get()->toArray();
        $data = $this->returnData($result);
        $data['total'] = $error_log->count();
        return $data;
    }
}