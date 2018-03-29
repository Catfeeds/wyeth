<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/24
 * Time: 下午5:19
 */

namespace App\Services;


//临时参数二维码
use App\Helpers\WyethError;
use App\Models\WoaapQrcode;

class WoaapQrcodeService
{
    protected $error;

    public function __construct()
    {
        $this->error = new WyethError();
    }

    /**
     * 生成二维码
     * @param $params array|string 参数的json字符串
     * @param string $source 来源,为以后使用,目前只有hd
     * @return array
     */
    public function addQrcode($params, $source = 'ci_hd'){
        //把参数按字母顺序排序
        if (!is_array($params)){
            $params = json_decode($params, true);
            if (!$params){
                return $this->error->returnError('json格式不合法');
            }
        }
        ksort($params);
        $params = json_encode($params);

        $qrcode = WoaapQrcode::where('params', $params)->first();
        if ($qrcode){
            //参数已存在
            if ($qrcode->expire > time()){
                //未过期
                return $this->returnData($qrcode->ticket, $qrcode->scene_str);
            }else{
                //过期重新生成
                $woaap = new WoaapService();
                $res = $woaap->qrcodeCreate($qrcode->scene_str);

                if (isset($res['ticket'])){
                    $qrcode->ticket = $res['ticket'];
                    $qrcode->expire = time() + $res['expire_seconds'];
                    $qrcode->save();
                    return $this->returnData($qrcode->ticket, $qrcode->scene_str);
                }else{
                    return $this->error->returnError($res['errmsg'], $res['errcode']);
                }
            }
        }else{
            $scene_str = $this->getSceneStr();
            $woaap = new WoaapService();
            $res = $woaap->qrcodeCreate($scene_str);

            if (isset($res['ticket'])){
                $qrcode = new WoaapQrcode();
                $qrcode->source = $source;
                $qrcode->params = $params;
                $qrcode->scene_str = $scene_str;
                $qrcode->ticket = $res['ticket'];
                $qrcode->expire = time() + $res['expire_seconds'];
                $qrcode->save();
                return $this->returnData($qrcode->ticket, $qrcode->scene_str);
            }else{
                return $this->error->returnError($res['errmsg'], $res['errcode']);
            }
        }
    }

    //根据scene_str获取参数
    public function getParamsBySceneStr($scene_str){
        $qrcode = WoaapQrcode::where('scene_str', $scene_str)->first();
        if (!$qrcode){
            return false;
        }

        $params = $qrcode->params;
        return json_decode($params, true);
    }

    private function returnData($ticket, $scene_str){
        return [
            'ret' => 1,
            'scene_str' => $scene_str,
            'ticket' => $ticket,
            'url' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket)
        ];
    }

    //获取场景id
    private function getSceneStr(){
        $num = WoaapQrcode::count();

        //避免与永久二维码重复,从100w开始
        $num += 1000000;

        //测试使用前1w的id
        return $num + 1 + (config('app.debug') ? 0 : 10000);
    }
}