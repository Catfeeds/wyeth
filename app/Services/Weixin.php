<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2018/3/8
 * Time: 下午3:25
 */

namespace App\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Weixin
{
    private $appid;
    private $secret;
    private $card_id;

    private $client;


    //card_id 胡萝卜测试
    //pSO9Mv9bpKN18Gu4CSBWqwSRT7Bs

    public function __construct()
    {
        $this->appid = 'wx3f506de465393a43';
        $this->secret = 'a3259381b87485cd44e61a37a25fa495';
        $this->card_id = env('TEST_CARD_ID', 'pSO9Mv0jC3MvoXKqYmyhNcV5RE0E');

        $this->client = new Client([
            'base_uri' => 'https://api.weixin.qq.com',
            'timeout' => 5.0,
        ]);
    }

    //创建会员卡
    public function createCard()
    {
        $uri = '/card/create?access_token=' . $this->getAccessToken();
        $params = [
            'card' => [
                'card_type' => 'MEMBER_CARD',
                'member_card' => [
                    'base_info' => [
                        'logo_url' => 'http://wx.qlogo.cn/mmopen/XE5dmcXZupiaDmUmsI1COw389LjzDiaCFZf4vbveGHY5ibqdJVOfqRpChBVFia6bMVpTVCqZ9VRv1SCbJjSMwoLERhqZCjd7wuBl/64',
                        'code_type' => 'CODE_TYPE_TEXT',
                        'brand_name' => '胡萝卜',
                        'title' => '胡萝卜会员卡',
                        'color' => 'Color080',
                        'notice' => 'notice',
                        'description' => 'description',
                        'sku' => [
                            'quantity' => 100000000
                        ],
                        'date_info' => [
                            'type' => 'DATE_TYPE_PERMANENT'
                        ],
                    ],
                    'prerogative' => '打9.8折',
                    'wx_activate' => true,
                    'wx_activate_after_submit' => true,
                    'wx_activate_after_submit_url' => 'http://mama-weiketang-wyeth.woaap.com',
                    'activate_app_brand_user_name' => 'gh_f367b1371825@app', //小程序user_name
                    'activate_app_brand_pass' => 'mix/home/index', //小程序path API/cardPage
                    'supply_bonus' => false,
                    'supply_balance' => false,
                ]
            ]
        ];
        return $this->requestPost($uri, $params);
    }

    public function updateCard()
    {
        $uri = '/card/update?access_token=' . $this->getAccessToken();
        $params = [
            'card_id' => $this->card_id,
            'member_card' => [
                'base_info' => [
                    'logo_url' => 'http://wx.qlogo.cn/mmopen/XE5dmcXZupiaDmUmsI1COw389LjzDiaCFZf4vbveGHY5ibqdJVOfqRpChBVFia6bMVpTVCqZ9VRv1SCbJjSMwoLERhqZCjd7wuBl/64',
                    'code_type' => 'CODE_TYPE_TEXT',
                    'title' => '胡萝卜会员卡',
                    'color' => 'Color080',
                    'notice' => 'notice',
                    'description' => 'description',
                ],
                'prerogative' => '打9.8折',
                'wx_activate' => true,
                'wx_activate_after_submit' => true,
                'wx_activate_after_submit_url' => 'http://mama-weiketang-wyeth.woaap.com',
                'activate_app_brand_user_name' => 'gh_f367b1371825@app', //小程序user_name
                'activate_app_brand_pass' => 'pregnotice/base/home/index', //小程序path API/cardPage
                'supply_bonus' => false,
                'supply_balance' => false,
            ]
        ];
        return $this->requestPost($uri, $params);
    }

    public function getCard()
    {
        $uri = '/card/get?access_token=' . $this->getAccessToken();
        $params = [
            'card_id' => $this->card_id
        ];
        return $this->requestPost($uri, $params);
    }

    //获取开卡参数
    public function cardGetUrl()
    {
        $uri = '/card/membercard/activate/geturl?access_token=' . $this->getAccessToken();
        $params = [
            'card_id' => $this->card_id,
            'outer_str' => 'test'
        ];
        $res = $this->requestPost($uri, $params);
        return urldecode($res['url']);
    }

    public function deleteCard($card_id)
    {
        $uri = '/card/delete?access_token=' . $this->getAccessToken();
        $params = [
            'card_id' => $card_id
        ];
        return $this->requestPost($uri, $params);
    }

    public function getUserInfo($code)
    {
        $uri = '/card/membercard/userinfo/get?access_token=' . $this->getAccessToken();
        $params = [
            'card_id' => $this->card_id,
            'code' => $code
        ];
        return $this->requestPost($uri, $params);
    }

    //获取用户开卡时提交的信息（跳转型开卡组件）
    public function getActivateTempinfo($activate_ticket)
    {
        $uri = '/card/membercard/activatetempinfo/get?access_token=' . $this->getAccessToken();
        $params = [
            'activate_ticket' => $activate_ticket
        ];
        return $this->requestPost($uri, $params);
    }

    //激活用户领取的会员卡（跳转型开卡组件）
    public function activeMember($code)
    {
        $uri = '/card/membercard/activate?access_token=' . $this->getAccessToken();
        $params = [
            'membership_number' => $code,
            'code' => $code,
            'card_id' => $this->card_id,
        ];
        return $this->requestPost($uri, $params);
    }

    public function testWhiteList()
    {
        $uri = '/card/testwhitelist/set?access_token=' . $this->getAccessToken();
        $params = [
            'username' => [
                'luanheart',
                'LYHB612'
            ]
        ];
        return $this->requestPost($uri, $params);
    }

    //设置开卡字段
    public function cardSetParams()
    {
        $uri = '/card/membercard/activateuserform/set?access_token=' . $this->getAccessToken();
        $params = [
            'card_id' => $this->card_id,
            'required_form' => [
                'can_modify' => false,
                'common_field_id_list' => [
                    'USER_FORM_INFO_FLAG_NAME',
                    'USER_FORM_INFO_FLAG_MOBILE',
                ]
            ],
            'optional_form' => [
                'can_modify' => false,
                'custom_field_list' => [

                ],
                'common_field_id_list' => [
//                    'USER_FORM_INFO_FLAG_NAME'
                ]
            ]
        ];
        return $this->requestPost($uri, $params);
    }

    //addCard 所需参数
    public function getCardList()
    {
        $api_ticket = $this->getCardApiTicket();
        $card_id = $this->card_id;
        $code = '';
        $openid = '';
        $timestamp = time() . '';
        $nonce_str = time() . '';
        $arr = [$api_ticket, $card_id, $code, $openid, $timestamp, $nonce_str];
        sort($arr);
        $signature = '';
        foreach ($arr as $item) {
            $signature .= $item;
        }
        $signature = sha1($signature);

        $outer_str = 'wyeth_mini';

        $ext = [
            'code' => $code,
            'openid' => $openid,
            'timestamp' => $timestamp,
            'nonce_str' => $nonce_str,
            'outer_str' => $outer_str,
            'signature' => $signature
        ];
        return [
            'cardId' => $card_id,
            'cardExt' => json_encode($ext)
        ];
    }

    //code 解码
    public function cardCodeDecrypt($encrypt_code)
    {
        $uri = '/card/code/decrypt?access_token=' . $this->getAccessToken();
        $params = [
            'encrypt_code' => $encrypt_code
        ];
        $res = $this->requestPost($uri, $params);
        if (isset($res['code'])) {
            return $res['code'];
        }
        return '';
    }

    public function getCardUser($code)
    {
        $uri = '/card/code/get?access_token=' . $this->getAccessToken();
        $params = [
            'card_id' => $this->card_id,
            'code' => $code
        ];
        return $this->requestPost($uri, $params);
    }

    public function getCardApiTicket()
    {
        $cache_key = 'weixin_card_ticket';
        $ticket = Cache::get($cache_key);
        if ($ticket) {
            return $ticket;
        }

        $uri = '/cgi-bin/ticket/getticket';
        $res = $this->requestGet($uri, [
            'access_token' => $this->getAccessToken(),
            'type' => 'wx_card'
        ]);
        $ticket = $res['ticket'];
        $expires_in = $res['expires_in'];

        Cache::put($cache_key, $ticket, intval($expires_in / 60) - 10);
        return $ticket;
    }

    public function getAccessToken()
    {
        $cache_key = 'weixin_access_token';
        $access_token = Cache::get($cache_key);
        if ($access_token) {
            return $access_token;
        }

        $uri = '/cgi-bin/token';
        $params = [
            'grant_type' => 'client_credential',
            'appid' => $this->appid,
            'secret' => $this->secret
        ];
        $res = $this->requestGet($uri, $params);
        $access_token = $res['access_token'];
        $expires_in = $res['expires_in'];

        Cache::put($cache_key, $access_token, intval($expires_in / 60) - 10);
        return $access_token;
    }

    public function requestGet($uri, $params = [])
    {
        $res = $this->client->request('GET', $uri, ['query' => $params]);
        $body = $res->getBody();
        $result = json_decode($body, true);
        return $result;
    }

    public function requestPost($uri, $params)
    {
        $res = $this->client->request('POST', $uri, ['body' => json_encode($params, JSON_UNESCAPED_UNICODE)]);
        $body = $res->getBody();
        $result = json_decode($body, true);
        return $result;
    }
}