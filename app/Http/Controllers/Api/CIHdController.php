<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/7/21
 * Time: 下午2:49
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Wyeth\WyethBaseController;
use App\Models\Activity;
use App\Models\User;
use App\Services\BLogger;
use App\Services\Crm;
use App\Services\WoaapQrcodeService;
use App\Services\WxWyeth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//给中台活动提供的接口

class CIHdController extends WyethBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('token', ['except' => ['isCrmMember', 'getMaterialByScene']]);
    }

    //根据aid,hd生成带参数的二维码
    public function qrcodeCreate(Request $request){
        $aid = $request->input('aid');
        $hd = $request->input('hd');
        if (!$aid || !$hd){
            return $this->returnError('aid or hd invalid');
        }
        $params = ['aid' => $aid, 'hd' => $hd];

        $qrcode_service = new WoaapQrcodeService();
        $res = $qrcode_service->addQrcode(json_encode($params));

        $log = BLogger::getLogger('ci');
        $log->info(__FUNCTION__, ['params'=>$params, 'res' => $res]);

        return response()->json($res);
    }

    //给活动的接口,可以看付费课程
    public function listenCourse(Request $request){
        $aid = $request->input('aid');
        $account_id = $request->input('account_id');
        if (!$aid || !$account_id){
            return $this->returnError('no aid or account_id');
        }

        $user = User::where('account_id', $account_id)->first();
        if (!$user){
            return $this->error->NO_USER;
        }

        $data['ret'] = 1;
        $res = DB::table('user_identify')
            ->where('uid', $user->id)
            ->first();
        if ($res){
            $data['msg'] = '已领取付费课程';
        }else{
            DB::table('user_identify')
                ->insert([
                    'uid' => $user->id,
                    'aid' => $aid,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            $data['msg'] = '领取成功';
        }
        return response()->json($data);
    }
    
    //给九月活动的发模板消息接口
    public function pushCustomMessage(Request $request){
        //预约标题
        //预约人
        //预约项目
        //预约时间
        //预约备注
        $params = [
            'title' => $request->input('title'),
            'content' => $request->input('content', ''),
            'odate' => $request->input('odate'),
            'address' => $request->input('address', ''),
            'remark' => $request->input('remark'),
            'url' => $request->input('url', ''),
            'openid' => trim($request->input('openid')),
        ];

        if (!$params['title'] || !$params['odate'] || !$params['remark'] || !$params['openid']){
            return $this->returnError('参数不合法');
        }

        $template_id = 5;

        if (!$params['address']){
            $params['address'] = date('Y-m-d');
        }


        if (!$params['content']){
            $user = User::where('openid', $params['openid'])->first();
            if (!$user){
                return $this->error->NO_USER;
            }
            $params['content'] = $user->nickname;
        }

        $wxWyeth = new WxWyeth();
        $res = $wxWyeth->pushpushCustomMessage($params, $template_id, false);
        if ($res){
            return $this->returnData();
        }else{
            return $this->returnError('发送失败');
        }
    }

    //孕期提醒问答模板消息 发操作提醒
    public function pregnoticeSendTpl(Request $request){
        $openid = $request->input('openid');
        $user = User::where('openid', $openid)->first();
        if (!$user){
            return $this->error->NO_USER;
        }
        $params = [
            'title' => "亲爱的麻麻：\n恭喜你！您在我们轻问诊中的问题被选为“最佳问题”！只需在公众号↓↓↓回复文字“是的”即可免费获得专属于VIP的1v1专家咨询一次！ ",
            'content' => '轻问诊-最佳问题',
            'odate' => date('Y-m-d'),
            'address' => '',
            'remark' => "\n回复文字“是的”即可~",
            'url' => '',
            'openid' => trim($request->input('openid')),
        ];

        $wxWyeth = new WxWyeth();
        $res = $wxWyeth->pushpushCustomMessage($params, 6, false);
        if ($res){
            return $this->returnData();
        }else{
            return $this->returnError('发送失败');
        }
    }

    //发送操作提醒模板消息
    public function sendNoticeTpl(Request $request)
    {
        $openid = $request->input('openid');
        $user = User::where('openid', $openid)->first();
        if (!$user){
            return $this->error->NO_USER;
        }
        $params = [
            'title' => $request->input('title', '提醒标题'),
            'content' => $request->input('content', '提醒内容'),
            'odate' => date('Y-m-d'),
            'address' => '',
            'remark' => $request->input('remark', ''),
            'url' => $request->input('url', ''),
            'openid' => trim($request->input('openid')),
        ];

        $wxWyeth = new WxWyeth();
        $res = $wxWyeth->pushpushCustomMessage($params, 6, false);
        if ($res){
            return $this->returnData();
        }else{
            return $this->returnError('发送失败');
        }
    }

    //查询是否为crm会员
    public function isCrmMember(Request $request)
    {
        $openid = $request->input('openid');
        $res = (new Crm())->isCrmMember($openid);
        return [
            'Flag' => true,
            'Member' => $res
        ];
    }

    //根据场景值id获取下行图文素材
    public function getMaterialByScene(Request $request)
    {
        $scene_str = $request->input('scene_str');
        $qrcode = new WoaapQrcodeService();
        $params = $qrcode->getParamsBySceneStr($scene_str);
        if (!$params || !isset($params['aid'])){
            return $this->returnError('场景值不存在');
        }
        $aid = $params['aid'];
        $activity = Activity::find($aid);
        if (!$activity){
            return $this->returnError('活动不存在');
        }
        $setting = json_decode($activity->setting, true);
        $data = [
            'title' => isset($setting['xxtw_title']) ? $setting['xxtw_title'] : '赢好礼过好年丨春节别太high，孕妈请悠着点吃',
            'abstract' => isset($setting['xxtw_abstract']) ? $setting['xxtw_abstract'] : '年夜饭很丰盛，哪些能吃哪些不能吃？这里有一份孕妈春节饮食攻略，赶紧点击学起来~',
            'img' => isset($setting['xxtw_img']) ? $setting['xxtw_img'] : 'http://mama-weiketang-wyeth.woaap.com/qiniu/wyethcourse/app/config/1f5c5a2c0fc3da99e8b3d011d568ed3f.jpg',
            'url' => config('app.url') . '/mobile/hd?scene_str=' . $scene_str
        ];
        return $this->returnData($data);
    }
}