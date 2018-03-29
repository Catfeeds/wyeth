<?php
namespace App\Services;

use GuzzleHttp\Client;

class Sms
{

    protected $base_uri = "http://www.wemediacn.net/";

    protected $timeout = 2.0;

    protected $token = "7100203030727691";

    protected $client = null;

    function __construct()
    {
        $this->client = new Client([
                'base_uri' => $this->base_uri,
                'timeout' => $this->timeout,
            ]
        );
    }

    //发送验证码
    public function sendCode($mobile, $code)
    {
        $params = [
            'mobile' => $mobile,
            'FormatID' => 8, //中文格式
            'Content' => '验证码：' . $code,
            'ScheduleDate' => date("2010-1-1"), //计划发送时间,以前日期如2010-1-1,立即发送
            'TokenID' => $this->token
        ];

        $ret = $this->client->request('POST', 'webservice/smsservice.asmx/SendSMS',['form_params' => $params]);

        $body = $ret->getBody();
        $ret = simplexml_load_string($body);
        $sms_info = $ret[0];

        $result = [
            'status' => (stripos($sms_info, 'OK') !== false) ? 1 : 0,
            'error_msg' => $sms_info
        ];

        //记录日志
        $log = BLogger::getLogger('api');
        $log->info(__FUNCTION__, ['query_params' => $params, 'result' => $result]);

        return $result;
    }


}