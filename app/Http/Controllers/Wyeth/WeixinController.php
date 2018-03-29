<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2018/3/8
 * Time: 上午11:00
 */

namespace App\Http\Controllers\Wyeth;


use App\Services\Crm;
use App\Services\Weixin;
use App\Services\WxWyeth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeixinController extends WyethBaseController
{
    public function getWxCardMemberInfoByTicket(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->error->NO_USER;
        }
        $activate_ticket = $request->input('activate_ticket');
        $code = $request->input('code');
        if (!$activate_ticket) {
            return $this->returnError('activate_ticket 不存在');
        }
        if (!$code) {
            return $this->returnError('code 不存在');
        }

        $res = (new WxWyeth())->getWxCardMemberInfoByTicket($activate_ticket);
        \Log::info('getWxCardMemberInfoByTicket', ['params' => $request->all(), 'res' => $res]);

        if (!$res || !isset($res['info'])) {
            return $this->returnError('获取用户信息失败');
        }

        $name = '';
        $mobile = '';
        $info = $res['info'];
        if (isset($info['common_field_list'])) {
            foreach ($info['common_field_list'] as $item) {
                if (isset($item['name']) && $item['name'] == 'USER_FORM_INFO_FLAG_NAME') {
                    $name = $item['value'];
                }
                if (isset($item['name']) && $item['name'] == 'USER_FORM_INFO_FLAG_MOBILE') {
                    $mobile = $item['value'];
                }
            }
        }
        if (!$name || !$mobile) {
            return $this->returnError('获取姓名手机号失败');
        }

        $user->realname = $name;
        $user->mobile = $mobile;
        $user->crm_status = 2; //中间状态，还未填宝宝生日
        $user->remember_token = $code;
        $user->save();

        return $this->returnData([
            'name' => $name,
            'mobile' => $mobile
        ]);
    }

    public function getWxCardMemberInfo(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->error->NO_USER;
        }
        return $this->returnData([
            'name' => $user->realname,
            'mobile' => $user->mobile
        ]);
    }

    //注册crm会员
    public function registerCrm(Request $request)
    {
        $user = Auth::user();
        $baby_birthday = $request->input('baby_birthday');
        $child_number = $request->input('child_number');
        $recommend_code = $request->input('recommend_code', '');
        $comment = $request->input('comment', '');

        if (!$user) {
            return $this->error->NO_USER;
        }
        if (!strtotime($baby_birthday)) {
            return $this->returnError('宝宝生日不合法');
        }
        if ($child_number != 1 && $child_number != 2) {
            return $this->returnError('胎数不合法');
        }

        //激活会员卡
        $code = $user->remember_token;
        $res = (new WxWyeth())->activiteWxCard($code);
        \Log::info('activiteWxCard', ['params' => $request->all(), 'res' => $res]);
        if (!$res) {
            return $this->returnError('激活失败');
        }

        $res = (new Crm())->registerCrmMember($user->unionid, $user->mobile, $user->realname, $user->province, $user->city, $baby_birthday, $child_number, $recommend_code, $comment);

        \Log::info('registerCrm', $res);
        if (isset($res['flag']) && $res['flag']) {
            $user->baby_birthday = $baby_birthday;
            $user->crm_status = 1;
            $user->save();
            return $this->returnData();
        }

        return $this->returnError('注册失败');
    }
}