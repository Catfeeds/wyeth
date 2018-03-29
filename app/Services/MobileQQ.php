<?php
namespace App\Services;

use App\Lib\Timer;
use GuzzleHttp\Client;
use App\Models\User;
use App\Models\Tplmsg;

class MobileQQ
{

    protected $base_uri = "http://wyeth.qq.nplusgroup.com/";

    protected $timeout = 2.0;

    protected $key = "dj3PVTErGxhBtuq7ZmsDgqwd";

    protected $iv = "01234567";

    protected $client = null;

    function __construct()
    {
        $this->client = new Client([
                'base_uri' => $this->base_uri,
                'timeout' => $this->timeout,
            ]
        );
    }

    //查询手Q用户会员信息
    public function searchMemberInfo($openid)
    {
        $timer = Timer::start();
        //3DES加密
        $nowtime = time();
        $nstr = $openid . '_' . $nowtime;
        $cryptnStr = encrypt($this->key, $this->iv, $nstr);

        $params = [
            'accessToken' => $cryptnStr,
        ];
        $ret = $this->client->request(
            'POST',
            'api/hwapi/getMemberInfoByOpenid.json',
            [
                'form_params' => [
                    'JsonParameter' => json_encode($params)
                ]
            ]
        );

        $body = $ret->getBody();
        $result = json_decode($body, true);

        //记录日志
        $log = BLogger::getLogger('api');
        $log->info(__FUNCTION__, ['timer' => $timer->mark(), 'query_params' => $params, 'result' => $result]);

        return $result;
    }

    //查询手Q用户会员信息
    public function getQQUserInfo($openid)
    {
        //3DES加密
        $nowtime = time();
        $nstr = $openid . '_' . $nowtime;
        $cryptnStr = encrypt($this->key, $this->iv, $nstr);

        $params = [
            'accessToken' => $cryptnStr,
        ];
        $ret = $this->client->request(
            'POST',
            'api/hwapi/getQQUserInfoByOpenid.json',
            [
                'form_params' => [
                    'JsonParameter' => json_encode($params)
                ]
            ]
        );

        $body = $ret->getBody();
        $result = json_decode($body, true);

        //记录日志
        $log = BLogger::getLogger('api');
        $log->info(__FUNCTION__, ['query_params' => $params, 'result' => $result]);

        return $result;
    }

    //将用户信息保存到手Q会员处
    public function signUser(User $user)
    {
        $timer = Timer::start();
        //3DES加密
        $nowtime = time();
        $openid = $user->openid;
        $nstr = $openid . '_' . $nowtime;
        $cryptnStr = encrypt($this->key, $this->iv, $nstr);


        $params = [
            'accessToken' => $cryptnStr,
            'name' => $user->realname,
            'phone' => $user->mobile,
            'babyBirthday' => date("Y-m-d", strtotime($user->baby_birthday)),
            'province' => $user->crm_province,
            'city' => $user->crm_city,
            'remark' => "",
        ];
        $ret = $this->client->request(
            'POST',
            'api/hwapi/saveMemberInfo.json',
            [
                'form_params' => [
                    'JsonParameter' => json_encode($params)
                ]
            ]
        );

        $body = $ret->getBody();
        $result = json_decode($body, true);

        //记录日志
        $log = BLogger::getLogger('api');
        $log->info(__FUNCTION__, ['timer' => $timer->mark(), 'query_params' => $params, 'result' => $result]);

        return $result;
    }

    /**
    * 发模板消息
        $params = [
            'source' => '微课堂',
            "accessToken" => "unFoYRND//36lgaJFQuHZqAZiePuhQUFoPxrHqSh9SeYobcYiOjJCIk9Ck+ORc0J",
            "templateid" => "52c0727fbd1873fa",
            "keynote3" => "点击详情马上进入",
            "first" => "测试内容",
            "url" => "http://wyeth.qq.nplusgroup.com/phone/wkt/share.htm",
            "keynote2" => "2016-03-09 20:00~20:45",
            "keynote1" => "如何提高母乳喂养成功率"
        ];
    )
    */
    public function sendTemplateMessage($openid, $data, $courseId)
    {
        $timer = Timer::start();
        if(!is_string($openid)){
            return;
        }
        //params
        $params = $this->processingParameter($openid, $data);

        //send
        $ret = $this->client->request(
            'POST',
            '/api/hwapi/sendTemplateMessage.json',
            [
                'form_params' => [
                    'JsonParameter' => json_encode($params)
                ]
            ]
        );

        $body = $ret->getBody();
        $result = json_decode($body, true);

        //blog
        $tplmsg = new Tplmsg();
        $tplmsg->openid = $openid;
        $tplmsg->cid = $courseId;
        $tplmsg->type = 2;
        $tplmsg->status = $result['status'] ? 1 : 0;

        $tplmsg->save();

        return $result;
    }

    private function accessToken($openid)
    {
        $nstr = $openid . '_' . time();

        return encrypt($this->key, $this->iv, $nstr);
    }

    private function processingParameter($openid, $message)
    {
        $cryptnStr = $this->accessToken($openid);

        $preparingData = [
            'accessToken' => $cryptnStr,
            'templateid' => '52c0727fbd1873fa', //temp
            //'first' => 'aa', //开头内容
            //'keynote1' => '', //标题
            //'keynote2' => '', //开课时间
            //'keynote3' => '', //开课地点
            //'url' => '', //jump link
            'source' => '微课堂', //来源
        ];

        return array_merge($preparingData, $message);
    }

    /**
    * 转向QQ授权页面
    */
    public static function redirectAuthorizeWebsite($request)
    {
        //处理完授权后转向的地址
        $realWebSite = $request->getUri();
        $realWebSite .= strstr($realWebSite, '?') === false ? '?' : '&';
        $realWebSite .= 'hw_c=qq_auth';
        $realWebSite = static::urlEncode($realWebSite);

        //处理授权地址后缀
        $processingAuthorizationUrlSuffix = '/auth/qq';
        //假装成是惠氏的网址，有IP和域名限制
        $url = "http://wyeth.qq.nplusgroup.com/oauthchatforwkt" . $processingAuthorizationUrlSuffix . '?realWebSite=' . $realWebSite;
        if ($request->dev) {
            $url = $url . "&hw_dev=". $request->dev;
        }

        //转到的QQ网站
        $QQAuthorizeUrl = "https://open.mp.qq.com/connect/oauth2/authorize?appid=200464349&redirect_uri=";
        $QQAuthorizeUrlSuffix = "&response_type=code&scope=snsapi_base&state=state1#qq_redirect";
        $redirectUrl = $QQAuthorizeUrl . rawurlencode($url) . $QQAuthorizeUrlSuffix;

        return $redirectUrl;
    }

    /**
    * 把 ? & 换成 wen hw
    */
    public static function urlEncode($url)
    {
        return base64_encode($url);
    }

    /**
    * 把问号
    */
    public static function urlDecode($url)
    {
        return base64_decode($url);
    }
}
