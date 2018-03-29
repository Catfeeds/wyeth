<?php

namespace App\Services;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

abstract class Qnupload
{
    /**
     * 上传到七牛
     * @param $file php $_FILES
     * @param null $key 文件名
     * @param string $pathPre 路径 前后都不带 /
     * @return string
     * @throws \Exception
     */
    public static function upload($file, $key = null, $pathPre = 'default')
    {
        if (empty($file) || $file['size'] <= 0) {
            return '';
        }

        $accessKey = config('qiniu.accessKey');
        $secretKey = config('qiniu.secretKey');
        $domain = config('qiniu.domain');
        $auth = new Auth($accessKey, $secretKey);
        $bucket = config('qiniu.bucket'); //上传空间名称
        $prefix = config('qiniu.prefix') . $pathPre . '/';

        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager();

        //上传文件到七牛
        $filePath = $file['tmp_name'];
        $extension = \File::extension($file['name']);
        if ($key == null) {
            $key = $prefix . md5($filePath . time());
            if ($extension) {
                $key = $key . ".$extension";
            }
        } else {
            $key = $prefix . $key;
        }
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            //TODO使用模板提示
            exit('上传至七牛失败，返回信息：' . $err);
        } else {
            return config("qiniu.domain") . '/' . $ret['key'];
        }

    }

    public static function uploadTmp ($filePath, $pathPre = 'default', $index) {
        $accessKey = config('qiniu.accessKey');
        $secretKey = config('qiniu.secretKey');
        $domain = config('qiniu.domain');
        $auth = new Auth($accessKey, $secretKey);
        $bucket = config('qiniu.bucket'); //上传空间名称
        $prefix = config('qiniu.prefix') . $pathPre . '/';

        $token = $auth->uploadToken($bucket);
        $uploadMgr = new UploadManager();

        $key = $prefix . md5($filePath . time()) . $index . ".jpg";

        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            //TODO使用模板提示
            exit('上传至七牛失败，返回信息：' . $err);
        } else {
            return config("qiniu.domain") . '/' . $ret['key'];
        }
    }

    public static function getUpToken() {
        $accessKey = config('qiniu.accessKey');
        $secretKey = config('qiniu.secretKey');
        $auth = new Auth($accessKey, $secretKey);
        $bucket = config('qiniu.bucket'); //上传空间名称
        $token = $auth->uploadToken($bucket);
        return $token;
    }

}
