<?php
namespace App\Services;

use App;

class QcloudService
{
    /**
     * 获取推流地址
     * 如果不传key和过期时间，将返回不含防盗链的url
     * @param $bizId 您在腾讯云分配的bizid
     * @param $streamId 您用来区别不同推流地址的唯一id
     * @param null $key 安全密钥
     * @param null $time 过期时间 sample 2013-11-12 12：00：00
     * @return string url
     */
    function getPushUrl($bizId, $streamId, $key = null, $time = null){

        if($key && $time){
            $txTime = strtoupper(base_convert(strtotime($time),10,16));
            //txSecret = MD5( KEY + livecode + txTime )
            //livecode = bizid+"_"+stream_id  如 8888_test123456
            $livecode = $bizId."_".$streamId; //直播码
            $txSecret = md5($key.$livecode.$txTime);
            $ext_str = "?".http_build_query(array(
                    "bizid"=> $bizId,
                    "txSecret"=> $txSecret,
                    "txTime"=> $txTime
                ));
        }
        return "rtmp://".$bizId.".livepush.myqcloud.com/live/".$livecode.(isset($ext_str) ? $ext_str : "")."&record=mp4";
    }

    /**
     * 获取播放地址
     * @param $bizId 您在腾讯云分配到的bizid
     * @param $streamId 您用来区别不同推流地址的唯一id
     * @return array url
     */
    function getPlayUrl($bizId, $streamId){
        $livecode = $bizId."_".$streamId; //直播码
        return array(
            "rtmp://".$bizId.".liveplay.myqcloud.com/live/".$livecode,
            "http://".$bizId.".liveplay.myqcloud.com/live/".$livecode.".flv",
            "http://".$bizId.".liveplay.myqcloud.com/live/".$livecode.".m3u8"
        );
    }

    /**
     * @param string $action 接口名，详见腾讯云点播API
     * @param array $option 输入参数，详见腾讯云点播API
     * @return mixed 输出参数 详见腾讯云点播API
     */
    public function vodApi($action = '', $option = []) {
        $secretId= config('course.qcloud_secret_id');
        $secretKey = config('course.qcloud_secret_key');
        $domain = config('course.qcloud_domain_vod_api');
        $optionPublic = [
            'Action' => $action,
            'Region' => 'sh',
            'Timestamp' => time(),
            'Nonce' => rand(),
            'SecretId' => $secretId,
        ];
        $option = array_merge($option, $optionPublic);
        ksort($option);
        $parameterNoSignature = http_build_query($option);
        $srcStr = "GET$domain/v2/index.php?$parameterNoSignature";
        $signStr = base64_encode(hash_hmac('sha1', $srcStr, $secretKey, true));
        $option['Signature'] = $signStr;
        $parameterContainSignature = http_build_query($option);

        return json_decode(file_get_contents("https://$domain/v2/index.php?$parameterContainSignature"));
    }

    /**
     * @param $cid 课程id
     * @return mixed 视频播放信息列表，详见腾讯云点播API/视频点播相关接口/获取视频播放信息列表/输出参数
     */
    public function getVodList($cid){
        $action = 'DescribeVodPlayInfo';
        $enviroment = App::environment();
        $bizid = config('course.qcloud_bizid');
        $fileName = 'live'.$bizid.'_wyeth_'.$enviroment.'_'.$cid;
        $option = [
            'fileName' => $fileName,
        ];
        $vodList = $this->vodApi($action, $option);
        if ($vodList->code == 0) {
            $vodList->fileSet = array_reverse($vodList->fileSet);
        }
        return $vodList;
    }
}