<?php

namespace App\Services;

use App\CIData\Cidata;
use App\Helpers\CacheKey;
use App\Http\Requests\Request;
use App\Lib\Timer;
use App\Models\Tplmsg;
use App\Models\TplProjectPush;
use App\Models\User;
use Cache;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis as Redis;

class WxWyeth
{

    /**
     * Cache tags keys
     * Cache::tags($keys) 参数
     * @var array
     */
    private $cacheTagsKey = ['Services', 'WxWyeth'];

    //检查用户是否关注公众账号
    public function getSubscribeStatus($openid)
    {
        $key = "a725de38fvcaeaa21";
        $client = new Client([
                'base_uri' => 'http://wyeth.woaap.com/',
                'timeout' => 2.0,
            ]
        );
        $query_params = ['openid' => $openid];
        //如果失败再试一次
        try {
            $ret = $client->request('GET', 'api/mp/accept/' . $key, ['query' => $query_params]);
        } catch (\Exception $e) {
            \Log::warning(__FUNCTION__ . '第一次请求失败', $query_params);
            // 等0.5s
            usleep(500000);
            try {
                $ret = $client->request('GET', 'api/mp/accept/' . $key, ['query' => $query_params]);
            } catch (\Exception $e) {
                \Log::warning(__FUNCTION__ . '第二次请求失败', $query_params);
                return false;
            }
        }

        $body = $ret->getBody();
        $result = json_decode($body, true);

        //记录日志
        // $log = BLogger::getLogger('api');
        // $log->info(__FUNCTION__, ['query_params' => $query_params, 'result' => $result]);

        return (isset($result['result']) && $result['result']) ? 1 : 0;
    }

    /**
     * 发送模版消息
     * 5月16日更改
     * http://wyethapi.etocrm.com/wpi/wyeth_oapi/pushCustomMessage
     * 双方约定的凭证秘钥和 Un 当天日期(YYYY-MM-dd)以下 划线”_”进行拼接后,再拼接 activity_no 进行 md5 加密所 得的字符串。加密时所需秘钥 以邮件为准。
     * 活动编号（activity_no）为：minclassNews；
     * 双方约定的凭证密钥为：etocrm1291
     * md5('etocrm1291_' . date('YYYY-MM-dd') . 'minclassNews');
     *
     * @param $params array
     *  openid   用户openid
     *  title    消息简介
     *  content  消息内容
     *  odate    消息时间
     *  address  消息地址
     *  remark   备注信息
     *  url      跳转链接
     * @param $templateId int   消息类别，1开课提醒，2报名提醒，3回答提醒 4课程回顾 5预约提醒 6操作提醒
     * @param $needCheck bool 是否检查url重复
     * @param $try int 次数
     * @return int
     */
    public function pushpushCustomMessage($params, $templateId = 2, $needCheck = true, $try = 3)
    {
        $log = BLogger::getLogger('api');
        if (!in_array($templateId, [1, 2, 3, 4, 5, 6])) {
            return 0;
        }

        // 进行限速 按秒进行限速
        $rate = 60;
        // 是否获取令牌
        $isGetToken = false;
        $tryTimes = 0;
        do {
            // 只允许执行10次
            if ($tryTimes == 10) {
                // throw new \Exception('Get Token Reach Max Times');
                $log->info(__FUNCTION__, ['info' => 'GetTokenReachMaxTimes']);
            }
            $getTokenKey = 'services:WxWyeth:' . time();
            $timeCount = Redis::incr($getTokenKey);
            if ($timeCount <= $rate) {
                // 设置过期时间
                if (Redis::ttl($getTokenKey) < 0) {
                    Redis::expire($getTokenKey, 1800);
                }
                $isGetToken = true;
            } else {
                // 等0.5s
                usleep(500000);
            }
            $tryTimes++;
        } while (!$isGetToken);

        $timer = Timer::start();
        if (isset($params['title']) && isset($params['content']) && isset($params['odate']) && isset($params['address'])) {
            // 进行限速 按同时的量进行限速
            $rate = 1000;
            // 是否获取令牌
            $isGetToken = true; //设为true,去掉按量限速,防止堵塞。。。。
            $tryTimes = 0;
            $getTokenKey = 'services:WxWyeth:total';
            do {
                // 只允许执行10次
                if ($tryTimes == 10) {
                    $log->info(__FUNCTION__, ['info' => 'GetTotalTokenReachMaxTimes']);
                }
                $totalCount = Redis::incr($getTokenKey);
                if ($totalCount <= $rate) {
                    $isGetToken = true;
                } else {
                    Redis::decr($getTokenKey);
                    // 等0.5s
                    usleep(500000);
                }
                $tryTimes++;
            } while (!$isGetToken);

            $client = new Client([
                    'base_uri' => 'http://wyethapi.etocrm.com/',
                    'timeout' => 5.0,
                    // disable throwing exceptions on an HTTP protocol errors
                    'http_errors' => false
                ]
            );

            $queryParams = [
                'openid' => isset($params['openid']) ? $params['openid'] : '',
                'title' => isset($params['title']) ? $params['title'] : '',
                'content' => isset($params['content']) ? $params['content'] : '',
                'odate' => isset($params['odate']) ? $params['odate'] : '',
                'address' => isset($params['address']) ? $params['address'] : '',
                'remark' => isset($params['remark']) ? $params['remark'] : '感谢您对惠氏妈妈一直以来的支持',
                'title_color' => isset($params['title_color']) ? $params['title_color'] : '#0255fb',
                'content_color' => isset($params['content_color']) ? $params['content_color'] : '',
                'odate_color' => isset($params['odate_color']) ? $params['odate_color'] : '',
                'address_color' => isset($params['address_color']) ? $params['address_color'] : '',
                'remark_color' => isset($params['remark_color']) ? $params['remark_color'] : '#0255fb',
                'url' => isset($params['url']) ? $params['url'] : '',
                'template_id' => $templateId,
            ];

            // 重复发送检查
            $checkKey = md5(implode('-', ['pushpushCustomMessage', $params['openid'], md5($queryParams['url'])]));
            if ($needCheck) {
                // 改用redis进行判断, 并且只保留两天时间
                if (Cache::tags($this->cacheTagsKey)->has($checkKey)) {
                    //参数错误，记录日志
                    $log->info(__FUNCTION__, ['d' => $timer->mark()['d'], 'tplId' => $templateId, 'r' => 'Repeat Send']);
                    Redis::decr($getTokenKey);
                    return 0;
                }
                $log->info(__FUNCTION__, ['d' => $timer->mark()['d'], 'step' => 'checkKey']);
            }

            //重新计算
            $str = 'etocrm1291_' . date('Y-m-d') . 'minclassNews';
            $queryParams['accessToken'] = md5($str);
            //增加新参数
            $queryParams['activity_no'] = 'minclassNews';
            // 不管执行成功与否都执行减一操作
            try {
                $ret = $client->request('POST', 'wpi/wyeth_oapi/pushCustomMessageForNew', ['form_params' => $queryParams]);
                Redis::decr($getTokenKey);
            } catch (\Exception $e) {
                //调用接口失败
                Redis::decr($getTokenKey);

                $log->error(__FUNCTION__ . "调用发模板消息接口失败", [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ]);
                return 0;
            }
            // $log->info(__FUNCTION__, ['d' => $timer->mark()['d'], 'code' => $ret->getStatusCode()]);
            $statusCode = $ret->getStatusCode();
            $body = $ret->getBody();
            if ($statusCode != '200') {
                $log->info(__FUNCTION__, [
                    'timer' => $timer->mark(),
                    'query_params' => $params,
                    'templateId' => $templateId,
                    'result' => "Http Exception $statusCode",
                    'timeCount' => "$timeCount,$totalCount"
                ]);
                $resultCode = $statusCode;
            }
            $result = json_decode($body, true);
            if (!$result) {
                $ret = 0;
                $resultCode = '9001';
            } else {
                $resultCode = $result['code'];
                if ($result['code'] == '0001') {
                    $ret = 1;
                } else {
                    $ret = 0;
                }

            }
            if (!$ret) {
                $log->info(__FUNCTION__, ['Error' => $params['openid'], 'code' => $resultCode, 'msg' => isset($result['msg']) ? $result['msg'] : '']);
            }
            if ($resultCode == '0007') {
                //超速了,呵呵呵,等待5分钟
                $log->info(__FUNCTION__ . "等待5分钟 gg 剩余尝试次数$try");
                Email::SendEmail('发模板消息0007', "gg 剩余尝试次数$try", 'xujin@corp-ci.com');
                sleep(333);
                //0007错误之后再次调用
                if ($try > 0) {
                    $this->pushpushCustomMessage($params, $templateId, $needCheck, $try - 1);
                }
            }

            if (isset($params['pid'])) {
                //推送项目
                $tpl_project_push = new TplProjectPush();
                $tpl_project_push->pid = $params['pid'];
                $tpl_project_push->type = $templateId;
                $tpl_project_push->openid = $params['openid'];
                $tpl_project_push->status = $ret;
                $tpl_project_push->abtest = isset($params['abtest']) ? $params['abtest'] : '';
                $tpl_project_push->save();
            } else {
                // 记录发送日志到db中
                $tplmsg = new Tplmsg();
                $tplmsg->openid = $params['openid'];
                // $tplmsg->content = json_encode($query_params);
                if (isset($params['type'])) {
                    //params里有type的话,存一下数据库
                    $tplmsg->type = $params['type'];
                } else {
                    $tplmsg->type = $templateId;
                }

                // $tplmsg->check_key = '';
                $tplmsg->status = $ret;
                // $tplmsg->code = $resultCode;
                $tplmsg->cid = 0;
                if ($params['url']) {
                    $tmp = $params;
                    $url_query = parse_url($params['url'], PHP_URL_QUERY); //url中有可能带params key值
                    parse_str($url_query);
                    $tplmsg->cid = isset($cid) ? $cid : 0;
                    $params = $tmp;
                }
                $tplmsg->save();

                //cidata统计发模板消息
                if ($ret && env('APP_ENV', 'local') == 'production') {
                    Cidata::init(config('oneitfarm.appkey'));
                    $user_info = User::where('openid', $params['openid'])->first();
                    $event_params = [
                        'cid' => isset($cid) ? $cid : 0,
                        'wyeth_channel' => isset($_hw_c) ? $_hw_c : '',//2017-09-01
                        'template_id' => $templateId
                    ];
                    if (strpos($params['url'], 'wxtpl_tjhg') !== false) {
                        //统计回顾推荐的
                        $event_params['wxtpl_tjhg'] = 1;
                    }
                    $uid = $user_info ? $user_info->id : $params['openid'];
                    Cidata::sendEvent($uid, null, null, 'send_tplmsg', $event_params);
                }
            }


            // 写入缓存, 有效期是两天,并且推送成功
            if ($needCheck && $ret) {
                $expiresAt = Carbon::now()->addDays(2);
                Cache::tags($this->cacheTagsKey)->put($checkKey, '1', $expiresAt);
            }
            // 进行限速
            $endMark = $timer->mark();
            $log->info(__FUNCTION__, [
                'o' => $params['openid'],
                'd' => $endMark['d'],
                't' => $endMark['t'],
                'r' => $result,
                'timeCount' => "$timeCount,$totalCount",
                'step' => 'push end'
            ]);

            return $ret;
        }

        //参数错误，记录日志
        $log->info(__FUNCTION__, ['timer' => $timer->mark(), 'queryParams' => $params, 'templateId' => $templateId, 'result' => 0]);
        return 0;
    }

    //获取JsapiTicket,加缓存1h
    public function getJsapiTicket()
    {
        $jsapi_ticket = Cache::get(CacheKey::JSAPI_TICKET);
        if ($jsapi_ticket) {
            return $jsapi_ticket;
        }

        $timer = Timer::start();
        $key = "a725de38ccaeaa21";
        $client = new Client([
                'base_uri' => 'http://wyeth.woaap.com/',
                'timeout' => 2.0,
            ]
        );
        $ret = $client->request('GET', 'api/mp/accept/' . $key);
        $body = $ret->getBody();
        $result = json_decode($body, true);

        //记录日志
        $log = BLogger::getLogger('api');
        $log->info(__FUNCTION__, ['timer' => $timer->mark(), 'query_params' => $key, 'result' => $result]);

        $jsapi_ticket = $result['result'];
        if ($jsapi_ticket) {
            Cache::put(CacheKey::JSAPI_TICKET, $jsapi_ticket, 60);
            return $jsapi_ticket;
        } else {
            return false;
        }
    }

    public function getSignPackage($url = '')
    {
        $timer = Timer::start();
        $signPackage = [];

        if (!$url) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }

        //使用更换域名后的新版的js conf 2017-01-09
        return $this->getWechatJsConf($url);

        $timestamp = time();

        //妈妈微课堂
        $jsapiTicket = $this->getJsapiTicket();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);
        $appId = "wx7fd6725300a50716";

        $signPackage = array(
            "appId" => $appId,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => $signature,
        );

        return $signPackage;
    }

    //获取微信js配置
    public function getWechatJsConf($url)
    {
        $client = new Client([
            'base_uri' => 'http://wyethapi.etocrm.com',
            'timeout' => 2.0,
        ]);
        try {
            $ret = $client->request('GET', '/wpi/WyethMp/getWechatJsConf?url=' . urlencode($url));
        }catch (\Exception $exception) {
            return [];
        }
        $body = $ret->getBody();
        $result = json_decode($body, true);
        unset($result['jsApiList']);
        return $result;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function _encrypt($key, $iv, $input)
    {
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC); //3DES加密将MCRYPT_DES改为MCRYPT_3DES
        $input = $this->_pkcs5_pad($input, $size); //如果采用PaddingPKCS7，请更换成PaddingPKCS7方法。
        $key = str_pad($key, 24, '0'); //3DES加密将8改为24
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, ''); //3DES加密将MCRYPT_DES改为MCRYPT_3DES
        if ($iv == '') {
            $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }

        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data); //如需转换二进制可改成  bin2hex 转换
        return $data;
    }

    private function _pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * 自动下行数据的统计
     * @param $start
     * @param $end
     * @return mixed
     */
    public function getWxClassSendLog($start, $end)
    {
        return $this->callWyethApi('/wpi/wyeth_oapi/getWxClassSendLog', 'getWxClassSendLog', [
            'starttime' => $start,
            'endtime' => $end,
        ]);
    }

    /**
     * 微课堂会员卡获取用户开卡信息接口
     * @param $code
     * @param null $card_id
     * @return mixed
     */
    public function getCardUserInfoByCode($code, $card_id = null)
    {
        $params = ['code' => $code];
        if ($card_id) {
            $params['card_id'] = $card_id;
        }
        return $this->callWyethApi('/wpi/wyeth_oapi/getCardUserInfoByCode', 'WxClassCardMember', $params);
    }

    //获取开卡组件的参数
    public function getWxCardUrl($card_id, $outer_str)
    {
        $params = [
            'card_id' => $card_id,
            'outer_str' => $outer_str
        ];
        return $this->callWyethApi('/wpi/wyeth_oapi/getWxCardUrl', 'WxClassCardUrl', $params);
    }

    //获取跳转型开卡组件用户提交信息
    public function getWxCardMemberInfoByTicket($activate_ticket)
    {
        $params = [
            'activate_ticket' => $activate_ticket
        ];
        $res = $this->callWyethApi('/wpi/wyeth_oapi/getWxCardMemberInfoByTicket', 'getWxCardMemberInfoByTicket', $params);
        if (isset($res['msg'])) {
            return $res['msg'];
        }
        return null;
    }

    //激活会员卡
    public function activiteWxCard($code)
    {
        $params = [
            'membership_number' => $code,
            'code' => $code,
            'card_id' => config('oneitfarm.mini_card_id')
        ];
        $res = $this->callWyethApi('/wpi/wyeth_oapi/activiteWxCard', 'activiteWxCard', $params);
        if (isset($res['msg'])) {
            return $res['msg'];
        }
        return null;
    }

    /**
     * 调用齐数接口
     * @param $uri
     * @param $activity_no
     * @param $params
     * @return mixed
     */
    private function callWyethApi($uri, $activity_no, $params)
    {
        $client = new Client([
                'base_uri' => 'http://wyethapi.etocrm.com',
                'timeout' => 5.0,
                'http_errors' => false
            ]
        );

        $accessToken = md5('etocrm1291_' . date('Y-m-d') . $activity_no);

        $params['accessToken'] = $accessToken;
        $params['activity_no'] = $activity_no;

        $ret = $client->request('POST', $uri, ['form_params' => $params]);

        $body = $ret->getBody();
        $result = json_decode($body, true);
        return $result;

    }

}
