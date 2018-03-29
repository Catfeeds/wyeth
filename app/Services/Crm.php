<?php
namespace App\Services;

use App\Helpers\CacheKey;
use App\Helpers\SessionKey;
use App\Lib\Timer;
use App\Models\Brand;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Crm
{

    protected $base_uri = "http://crmapi.etocrm.com:9999/";

    protected $new_uri = "http://58.32.203.234:8001/";

    protected $timeout = 10.0;

    protected $token = "wyethapiservice2015";

    protected $new_token = "d7w6yekdldo0j2l1";

    protected $client = null;

    protected $new_client = null;

    function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->base_uri,
            'timeout' => $this->timeout,
        ]);
        $this->new_client = new Client([
            'base_uri' => $this->new_uri,
            'timeout' => $this->timeout,
        ]);
    }

    //查询用户是否crm系统用户
    public function isCrmMember($openid)
    {
        $timer = Timer::start();
        $ts = time();
        $params = [
            'token' => $this->token,
            'ts' => $ts,
            'sig' => md5($this->token . $ts),
            'wxopenid' => $openid
        ];
        $ret = $this->client->request('POST', 'CRMServiceForHeywow.asmx/Search_IsMember',
            [
                'form_params' => [
                    'JsonParameter' => json_encode($params)
                ]
            ]
        );

        $body = $ret->getBody();
        $arr = simplexml_load_string($body);
        $result = json_decode($arr[0], true);

        //记录日志
        $log = BLogger::getLogger('api');
        $log->info(__FUNCTION__, ['timer' => $timer->mark(), 'query_params' => $params, 'result' => $result]);
        
        return isset($result['Member']) && $result['Member']  ? 1 : 0;
    }

    //注册Crm系统用户
    public function signUser($params)
    {
        $timer = Timer::start();
        $ts = time();
        $params['token'] = $this->token;
        $params['ts'] = $ts;
        $params['sig'] = md5($this->token . $ts);
        $params['Regtime'] = date("Y-m-d H:i:s");
        $params['Remark'] = "";

        $ret = $this->client->request('POST', 'CRMServiceForHeywow.asmx/Insert_Heywow_Data',
            [
                'form_params' => [
                    'JsonParameter' => json_encode($params)
                ]
            ]
        );

        $body = $ret->getBody();
        $arr = simplexml_load_string($body);
        $result = json_decode($arr[0], true);

        //记录日志
        $log = BLogger::getLogger('api');
        $log->info(__FUNCTION__, ['timer' => $timer->mark(), 'query_params' => $params, 'result' => $result]);

        return $result;
    }

    //查询crm用户详情
    public function searchMemberInfo($openid)
    {
        $ts = time();
        $params = [
            'token' => $this->token,
            'ts' => $ts,
            'sig' => md5($this->token . $ts),
            'wxopenid' => $openid
        ];

        return $this->clientPost('CRMServiceForHeywow.asmx/Search_MemberInfo', ['JsonParameter' => json_encode($params)]);
    }

    /**
     * 获取用户品牌信息
     * @param $openid string 传openid根据openid获取用户,否则用户Auth
     * @return int|mixed
     */
    public function getMemberBrand ($openid = null) {

        //测试用户品牌专用
        if (Session::has(SessionKey::USER_BRAND)) {
            if ($brand = $this->getUserBrand(Session::get(SessionKey::USER_BRAND))){
                return $brand;
            }
        }

        if ($openid){
            $user = User::where('openid', $openid)->first();
            if (!$user){
                return 0;
            }
        }else{
            $user = Auth::user();
        }

        if (!$user){
            return 0;
        }

        $brand = Cache::get(CacheKey::CACHE_KEY_USER_BRAND . $user->id);
        if ($brand) {
            return $brand;
        }

        $result = $this->getMemberStatus($user['unionid']);


        if ($result && isset($result['flag']) && $result['flag']){
            //返回成功
            if (isset($result['Status1'])){
                $brand = self::getUserBrand($result['Status1']);
                //更新用户品牌及是否有主
                $user->brand = $brand;
                if (isset($result['YouZhu'])){
                    $user->youzhu = $result['YouZhu'];
                }
                $user->save();
            }else{
                $brand = 0;
            }
            Cache::put(CacheKey::CACHE_KEY_USER_BRAND . $user->id, $brand, 1440);  // 缓存一天

        } else {
            $brand = 0;
        }
        return $brand;
    }

    //获取用户crm信息
    public function getMemberStatus($unionid)
    {
        $params = [
            'accesstoken' => $this->getAccessToken(),
            'unionid' => $unionid
        ];
        $params = [
            'JsonParameter' => json_encode($params)
        ];

        return $this->clientPost('CRMServiceForMember.asmx/GetMemberStatus', $params, true);
    }

    public function getAccessToken($token = null) {
        if (!$token){
            $token = $this->new_token;
        }

        $cache_key = CacheKey::CACHE_KEY_TOKEN.$token;
        $accessToken = Cache::get($cache_key);
        if ($accessToken) {
            return $accessToken;
        }

        $ts = time();
        $params = [
            'token' => $token,
            'ts' => $ts,
            'sig' => md5($token . $ts)
        ];
        $ret = $this->client->request('POST', 'GetAccessToken.ashx', [
            'form_params' => [
                'JsonParameter' => json_encode($params)
            ]
        ]);

        $result = json_decode($ret->getBody(), true);
        if ($result['Flag']) {
            $accessToken = $result['AccessToken'];

            Cache::put($cache_key, $accessToken, 110);
            return $accessToken;
        } else {
            return '';
        }
    }

    /**
     * 根据CRM获取到的用户类型映射到品牌
     * @param $userType
     * @return int
     */
    private function getUserBrand($userType) {
        switch ($userType) {
            case '天赋1':
            case '天赋2':
            case '好孕启':
            case '启赋':
            case 'qifu':
                $brand = Brand::where('name', '启赋')->first();
                return $brand->id;
            case '智学1':
            case '智学2':
            case '好孕金':
            case '金装':
            case 'jinzhuang':
                $brand = Brand::where('name', 'S-26')->first();
                return $brand->id;
            case 'Club':
                $brand = Brand::where('name', 'club')->first();
                return $brand->id;
            case 'SMA':
                $brand = Brand::where('name', 'SMA')->first();
                return $brand->id;
            case '好孕':
            default:
                return 0;
        }
    }

    /**
     * 根据unionid查询是否为会员
     * @param $unionid
     * @return mixed 0：非会员；1：非冻结的老会员；-1：冻结的老会员
     */
    public function searchMemberByUnionid($unionid){
        $token = 'bSZm2acK1yGpoKS';
        $params = [
            'accesstoken' => $this->getAccessToken($token),
            'unionid' => $unionid
        ];
        $ret = $this->new_client->request('POST', 'CRMServiceForXCX.asmx/SearchIsMemberByUnionID', [
            'form_params' => [
                'JsonParameter' => json_encode($params)
            ]
        ]);

        $body = $ret->getBody();
        $arr = simplexml_load_string($body);
        $result = json_decode($arr[0], true);

        return isset($result['member']) ?  $result['member'] : 0;
    }

    /**
     * @param $unionid
     * @param $mobiletel
     * @param $mamaname
     * @param $province
     * @param $city
     * @param $bbirthday
     * @param null $childnumber
     * @param string $recommendcode 邀请码
     * @param string $comment 备注
     * @param int $channeltype 注册渠道 微信公众号28 小程序141 默认小程序
     * @param null $regtime 注册时间
     * @return mixed
     */
    public function registerCrmMember($unionid, $mobiletel, $mamaname, $province, $city, $bbirthday, $childnumber = null, $recommendcode = '', $comment = '', $channeltype = 141, $regtime = null){
        $token = 'bSZm2acK1yGpoKS';
        $params = [
            'accesstoken' => $this->getAccessToken($token),
            'unionid' => $unionid,
            'mobiletel' => $mobiletel,
            'mamaname' => $mamaname,
            'province' => $province,
            'city' => $city,
            'bbirthday' => $bbirthday,
            'channeltype' => $channeltype,
            'recommendcode' => $recommendcode,
            'comment' => $comment
        ];
        if (isset($regtime)){
            $params['regtime'] = $regtime;
        }else{
            $params['regtime'] = date('Y-m-d H:i:s');
        }
        if (isset($childnumber)){
            $params['childnumber'] = $childnumber;
        }

        $ret = $this->new_client->request('POST', 'CRMServiceForXCX.asmx/Regist', [
            'form_params' => [
                'JsonParameter' => json_encode($params)
            ]
        ]);

        $body = $ret->getBody();
        $arr = simplexml_load_string($body);
        $result = json_decode($arr[0], true);
        return $result;
    }

    private function clientPost($uri, $params = [], $is_new_client = false, $is_xml = true)
    {
        $client = $is_new_client ? $this->new_client : $this->client;

        //失败重试一次，再失败发送预警邮件
        try{
            $ret = $client->request('POST', $uri, ['form_params' => $params]);
        }catch (RequestException $exception){
            try{
                $ret = $client->request('POST', $uri, ['form_params' => $params]);
            }catch (RequestException $exception){
                Email::SendEmail('RequestException', "POST\n$uri\n".json_encode($params), Email::EMAIL_BOX_PHP);
                return false;
            }
        }

        $body = $ret->getBody();
        if ($is_xml) {
            $arr = simplexml_load_string($body);
            $result = json_decode($arr[0], true);
        }else {
            $result = json_decode($body, true);
        }

        return $result;
    }

}
