<?php
namespace App\Services;

use App\Helpers\WyethError;
use App\Models\FailedInterface;
use App\Models\UserMq;
use Auth;
use GuzzleHttp\Client;
use App\Models\User;
use GuzzleHttp\Exception\RequestException;
use Log;

/**
 * Created by PhpStorm.
 * User: xuzx
 * Date: 2017/8/18
 * Time: 下午7:47
 */
class MqService{
    const BASE_URL = 'http://58.32.203.234:8001/CRMServiceForMQ.asmx/';

    const SUFFIX_GETMQ = 'GetMQ';

    const SUFFIX_ADDMQ = 'AddMQ';

    const SUFFIX_REDUCEMQ = 'ReduceMQ';

    const SUFFIX_GETMQRECORD = 'GetMQRecord';

    const TIME_OUT = 2.0;

    //注册
    const ADD_TYPE_REG = 1;

    //签到
    const ADD_TYPE_SIGN = 2;

    //邀请新用户
    const ADD_TYPE_INVITE_NEW = 3;

    //唤醒老用户
    const ADD_TYPE_ACTIVE_OLD = 4;

    //进入课程
    const ADD_TYPE_ENTER_COURSE = 5;

    //听课-常规
    const ADD_TYPE_LISTEN_REGULAR = 6;

    //听课-超过7分钟
    const ADD_TYPE_LISTEN_LONG = 7;

    //提问
    const ADD_TYPE_ASK = 8;

    //转发课程
    const ADD_TYPE_TRANSMIT = 9;

    //评论图文
    const ADD_TYPE_COMMENT = 10;

    //收藏图文
    const ADD_TYPE_SAVE = 11;

    //转发图文
    const ADD_TYPE_TRANSMIT_CMS = 12;

    //现金购买
    const ADD_TYPE_CASH = 13;

    //补偿mq
    const ADD_TYPE_COMPENSATE = 14;

    //积分直接换
    const CONSUME_TYPE_DIRECT = 1000;

    //积分折价换
    const CONSUME_TYPE_DISCOUNT = 1001;

    //中奖码兑换
    const CONSUME_TYPE_CODE = 1002;

    //翻牌隐藏福利
    const CONSUME_TYPE_HIDDEN = 1003;

    //翻牌机会多一次
    const CONSUME_TYPE_ONE_MORE = 1004;

    //付费课程
    const CONSUME_TYPE_PAID_COURSE = 1005;

    //专家问答
    const CONSUME_TYPE_PRO_QA = 1006;

    //兑换抽奖次数
    const CONSUME_TYPE_DRAW = 1007;

    //每周积分上限
    const WEEK_MQ_LIMIT = 800;

    public static function getTypeArray(){
        return $type_table = [
            self::ADD_TYPE_REG              => [50, '注册'],
            self::ADD_TYPE_SIGN             => [20, '签到'],
            self::ADD_TYPE_INVITE_NEW       => [50, '邀请新用户'],
            self::ADD_TYPE_ACTIVE_OLD       => [10, '唤醒老用户'],
            self::ADD_TYPE_ENTER_COURSE     => [3, '进入课程'],
            self::ADD_TYPE_LISTEN_REGULAR   => [1, '听课-常规'],
            self::ADD_TYPE_LISTEN_LONG      => [50, '听课-超过7分钟'],
            self::ADD_TYPE_ASK              => [3, '提问'],
            self::ADD_TYPE_TRANSMIT         => [5, '转发课程'],
            self::ADD_TYPE_COMMENT          => [3, '评论图文'],
            self::ADD_TYPE_SAVE             => [3, '收藏图文'],
            self::ADD_TYPE_TRANSMIT_CMS     => [5, '转发图文'],
            self::ADD_TYPE_CASH             => [50, '现金购买'],
            self::ADD_TYPE_COMPENSATE       => [50, '补偿MQ'],
            self::CONSUME_TYPE_DIRECT       => [50000, '积分直接换'],
            self::CONSUME_TYPE_DISCOUNT     => [25000,'积分折价换'],
            self::CONSUME_TYPE_CODE         => [50, '中奖码兑换'],
            self::CONSUME_TYPE_HIDDEN       => [50, '翻牌隐藏福利'],
            self::CONSUME_TYPE_ONE_MORE     => [50, '翻牌机会多一次'],
            self::CONSUME_TYPE_PAID_COURSE  => [500, '付费课程'],
            self::CONSUME_TYPE_PRO_QA       => [400, '专家问答'],
            self::CONSUME_TYPE_DRAW         => [50, '兑换抽奖次数']
        ];
    }

    //获取每周有积分上限的类型数组
    public static function getLimitTypeArray(){
        return $type_table = [
            self::ADD_TYPE_REG,
            self::ADD_TYPE_SIGN,
            self::ADD_TYPE_INVITE_NEW,
            self::ADD_TYPE_ACTIVE_OLD,
            self::ADD_TYPE_ENTER_COURSE,
            self::ADD_TYPE_LISTEN_REGULAR,
            self::ADD_TYPE_LISTEN_LONG,
            self::ADD_TYPE_ASK,
            self::ADD_TYPE_TRANSMIT,
            self::ADD_TYPE_COMMENT,
            self::ADD_TYPE_SAVE,
            self::ADD_TYPE_TRANSMIT_CMS
        ];
    }

    public static function sendRequest($uri, $params){
        $client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout' => self::TIME_OUT,
        ]);
        try{
            $ret = $client->request('POST', $uri, ['form_params' => [
                    'JsonParameter' => json_encode($params)
            ]]);
        }catch (RequestException $exception){
            //重试一次
            try{
                $ret = $client->request('POST', $uri, ['form_params' => [
                    'JsonParameter' => json_encode($params)
                ]]);
            }catch (RequestException $exception){
                $fail = new FailedInterface();
                $fail->type = 'mq';
                $fail->uri = $uri;
                $fail->method = 'post';
                $fail->params = json_encode($params);
                $fail->save();
                \Log::error('调用mq接口失败'.$uri, $params);
                Email::SendEmail('调用mq接口失败'.$uri, json_encode($params), Email::EMAIL_BOX_PHP);
                return ['flag' => false];
            }
        }

        $body = $ret->getBody();
        $arr = simplexml_load_string($body);
        $result = json_decode($arr[0], true);
        return $result;
    }

    public static function getUserMq($uid){
        $user = User::where('id', $uid)->first();
        if(!config('oneitfarm.is_wyeth') || env('APP_ENV') == 'local'){
            return $user->mq;
        }
        //调用crm那边的增加积分的接口
        $accesstoken = (new Crm())->getAccessToken();
        $params = [
            'accesstoken' => $accesstoken,
            'unionid' => $user->unionid
        ];
        $result = self::sendRequest(self::SUFFIX_GETMQ, $params);
        if ($result['flag']) {
            return $result['mq'];
        }else{
            Log::info('accesstoken:' . $accesstoken . ', unionid:' . $user->unionid . ', message:' . $result['message']);
            return $user ? $user->mq : 0;
        }
    }

    public static function getConsumeList($uid, $page = 1, $page_size = 6){
        $user = User::where('id', $uid)->first();
        if(!config('oneitfarm.is_wyeth') || env('APP_ENV') == 'local'){
            $page--;
            $offset = $page * $page_size;
            $user_mq = UserMq::where('uid', $uid)->take($page_size)->offset($offset)->orderBy('created_at', 'desc')->orderBy('id', 'desc')->get()->toArray();
            $data = [];
            if(is_array($user_mq) && (count($user_mq) > 0)){
                foreach ($user_mq as $um){
                    $weekarray = array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
                    $day = $weekarray[date("w", strtotime($um['created_at']))];
                    $info = [
                        'uid' => $um['uid'],
                        'event' => $um['event'],
                        'mq' => $um['mq'],
                        'balance' => $um['balance'],
                        'created_at' => $um['created_at'],
                        'day' => $day
                    ];
                    $data['list'][] = $info;
                }
            }
            $data['mq'] = $user->mq;
            return $data;
        }
        $data = [];
        //调用crm那边的增加积分的接口
        $accesstoken = (new Crm())->getAccessToken();
        $params = [
            'accesstoken' => $accesstoken,
            'unionid' => $user->unionid,
            'pageindex' => (int)$page,
            'pagesize' => (int)$page_size
        ];
        $result = self::sendRequest(self::SUFFIX_GETMQRECORD, $params);
        if ($result['flag']) {
            $user_mq = $result['MQRecordList'];
            foreach ($user_mq as $um){
                $weekarray = array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
                $day = $weekarray[date("w", strtotime($um['createtime']))];
                $info = [
                    'type' => $um['type'],
                    'event' => $um['desc'],
                    'mq' => $um['mq'],
                    'balance' => $um['balance'],
                    'created_at' => $um['createtime'],
                    'day' => $day
                ];
                $data['list'][] = $info;
            }
            $data['mq'] = self::getUserMq($uid);
            return $data;
        }else{
            Log::info('accesstoken:' . $accesstoken . ', unionid:' . $user->unionid . ', page:' . $page . ', page_size:' . $page_size . ', message:' . $result['message']);
            return [
                'list' => [],
                'mq' => self::getUserMq($uid)
            ];
        }
    }

    public static function increase($uid, $type, $num = 0){
        $user = User::where('id', $uid)->first();
        //判断一周以来的mq积分是否超过800
        $limit_array = self::getLimitTypeArray();
        $monday = date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
        $week_mq = UserMq::where('uid', $uid)->whereIn('type', $limit_array)->where('created_at', '>', $monday)->sum('mq');
        if(!$num){
            $num = self::getTypeArray()[$type][0];
        }
        //现金购买和补偿mq从每周限制取消掉
        if(!in_array($type, [self::ADD_TYPE_CASH, self::ADD_TYPE_COMPENSATE])){
            if($week_mq >= self::WEEK_MQ_LIMIT){
                $num = 0;
            }elseif($num > (self::WEEK_MQ_LIMIT - $week_mq)){
                $num = self::WEEK_MQ_LIMIT - $week_mq;
            }
        }

        //调用crm那边的增加积分的接口
        if (config('oneitfarm.is_wyeth') && env('APP_ENV') == 'production') {
            if(!$user || !$user->unionid || $user->unionid == ''){
                return (new WyethError())->NO_UNIONID;
            }
            $accesstoken = (new Crm())->getAccessToken();
            $params = [
                'accesstoken' => $accesstoken,
                'unionid' => $user->unionid,
                'openid' => $user->openid,
                'mq' => $num,
                'channel' => 1,
                'type' => $type,
                'desc' => $num == 0? self::getTypeArray()[$type][1] . ' (已超过每周上限800MQ)' : self::getTypeArray()[$type][1]
            ];
            $result = self::sendRequest(self::SUFFIX_ADDMQ, $params);
            if ($result['flag']) {
                $user = User::where('id', $uid)->first();
                $mq = $user->mq;
                $mq = $mq + $num;
                User::where('id', $uid)->update([
                    'mq' => $mq
                ]);
                $user_mq = new UserMq();
                $user_mq->uid = $uid;
                $user_mq->type = $type;
                $user_mq->event = $num == 0? self::getTypeArray()[$type][1] . ' (已超过每周上限800MQ)' : self::getTypeArray()[$type][1];
                $user_mq->mq = $num;
                $user_mq->balance = $mq;
                $user_mq->save();

                return [
                    'ret' => 1,
                    'mq' => $num,
                    'msg' => '增加成功'
                ];
            }else{
                Log::info('Error accesstoken:' . $accesstoken . ', unionid:' . $user->unionid . ', openid:' . $user->openid . ', mq:'
                 . ', type:' . $type . ', desc' . self::getTypeArray()[$type][1]);
                return (new WyethError())->returnError('未知错误');
            }
        }else{
            $mq = $user->mq;
            $mq = $mq + $num;
            User::where('id', $uid)->update([
                'mq' => $mq
            ]);
            $user_mq = new UserMq();
            $user_mq->uid = $uid;
            $user_mq->type = $type;
            $user_mq->event = self::getTypeArray()[$type][1];
            $user_mq->mq = $num;
            $user_mq->balance = $mq;
            $user_mq->save();

            return [
                'ret' => 1,
                'mq' => $num,
                'msg' => '增加成功'
            ];
        }
    }

    public static function decrease($uid, $type, $num = 0){
        $user = User::where('id', $uid)->first();
        if(!$num){
            $num = self::getTypeArray()[$type][0];
        }
        //调用crm那边的减少积分的接口
        if (config('oneitfarm.is_wyeth') && env('APP_ENV') == 'production') {
            if(!$user || !$user->unionid || $user->unionid == ''){
                return (new WyethError())->NO_UNIONID;
            }
            $params = [
                'accesstoken' => (new Crm())->getAccessToken(),
                'unionid' => $user->unionid,
                'openid' => $user->openid,
                'mq' => $num,
                'channel' => 1,
                'type' => $type,
                'desc' => self::getTypeArray()[$type][1]
            ];
            $result = self::sendRequest(self::SUFFIX_REDUCEMQ, $params);
            if ($result['flag']) {
                $user = User::where('id', $uid)->first();
                $mq = $user->mq;
                $mq = $mq - $num;

                User::where('id', $uid)->update([
                    'mq' => $mq
                ]);
                $user_mq = new UserMq();
                $user_mq->uid = $uid;
                $user_mq->type = $type;
                $user_mq->event = self::getTypeArray()[$type][1];
                $user_mq->balance = $mq;
                $user_mq->mq = 0 - $num;
                $user_mq->save();
                return [
                    'ret' => 1,
                    'mq' => $num,
                    'msg' => '消费成功'
                ];
            }else{
                if(strpos('Insufficient', $result['message'])){
                    return (new WyethError())->NO_SUFFICIENT_FUNDS;
                }
                return (new WyethError())->returnError($result['message']);
            }
        }else{
            $mq = $user->mq;
            $mq = $mq - $num;
            if($mq < 0){
                return (new WyethError())->NO_SUFFICIENT_FUNDS;
            }

            User::where('id', $uid)->update([
                'mq' => $mq
            ]);
            $userMq = new UserMq();
            $userMq->uid = $uid;
            $userMq->type = $type;
            $userMq->event = self::getTypeArray()[$type][1];
            $userMq->balance = $mq;
            $userMq->mq = 0 - $num;
            $userMq->save();
            return [
                'ret' => 1,
                'mq' => $num,
                'msg' => '消费成功'
            ];
        }
    }


}