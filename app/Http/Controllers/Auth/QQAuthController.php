<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\MobileQQ;
use App\Models\User;
use JWTFactory;
use JWTAuth;
use App\Models\UserRelation;
use Illuminate\Support\Facades\Session;

class QQAuthController extends Controller
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
        "status": "true",
        "msg": "ok",
        "data": {
            "subscribe": 0,
            "openid": "9B3B58B108ED9C44A77060672955EB04"
            //
            "sex": 1,
​            "remark": "",
​            "nickname": "cai feng",
​            "province": "江苏",
​            "groupid": 102,
​            "language": "zh_CN",
​            "headimgurl":  "http://q2.qlogo.cn/g?b=qq&k=Zib7JwJZk8IJMSna0wEgOaQ&s=40&t=1408793760",
​            "subscribe_time": 1455689851,
​            "country": "中国",
​            "city": "无锡"
        }
     */
    public function process(Request $request)
    {
        //以后加一个判断是不是从QQ过来的联接
        if(!$request->request->has('code')){
            throw new \InvalidArgumentException('code not exist');
        }
        if(!$request->request->has('realWebSite')){
            throw new \InvalidArgumentException('realWebSite not exist');
        }
        $code = $request->request->get('code');

        $baseUri = "http://wyeth.qq.nplusgroup.com/";
        $uri = "api/toauth/getUserByOauthCode.json";

        $client = new Client([
            'base_uri' => $baseUri,
            'timeout' => 2.0,
        ]);
        $response = $client->request('GET', $uri, ['query' => ['code' => $code]]);
        $responseContents = $response->getBody()->getContents();
        $returnArr = json_decode($responseContents, true);

        //新建或者更新user
        $user = User::firstOrNew(['openid' => $returnArr['data']['openid'], 'type' => User::OPENID_TYPE_SQ]);

        if(!$returnArr['data']['openid']){
            $user->city = $returnArr['data']['city'];
            $user->province = $returnArr['data']['province'];
            $user->nickname = $returnArr['data']['nickname'];
            $user->sex = $returnArr['data']['sex'];
            //$user->language =
            //$user->avatar = $returnArr['data']['headimgurl'];
            //$user->subscribe_time
            //$user->groupid
            //$user->remark
            $user->subscribe_status = 1;
        }else{
            $user->subscribe_status = 0;
        }

        /*
        //不实时读取惠氏CRM信息，惠氏有可能有其它渠道拿到CRM信息
        $mobileQQ = new MobileQQ();
        $arrCrm = $mobileQQ->searchMemberInfo($returnArr['data']['openid']);

        if($arrCrm['status'] && $arrCrm['data']){
            $data['name'] = $arrCrm['data']['name'];
            phone;
            baby_briday
            create_time
        }
        */
        $user->save();

        //create or update user_relation
        UserRelation::firstOrCreate(['openid' => $returnArr['data']['openid'], 'type' => User::OPENID_TYPE_SQ, 'uid' => $user->id]);

        //session
        Session::put('openid', $returnArr['data']['openid']);

        Session::put('openid_type', User::OPENID_TYPE_SQ);
        // 使用jwt生成token

        $payload = JWTFactory::make(['user_type' => 'user', 'uid' => $user->id, 'nickname' => $user->nickname]);
        Session::put('token', JWTAuth::encode($payload));

        $realWebSite = $request->request->get('realWebSite');
        $realWebSite = MobileQQ::urlDecode($realWebSite);

        return redirect($realWebSite);
    }
}
