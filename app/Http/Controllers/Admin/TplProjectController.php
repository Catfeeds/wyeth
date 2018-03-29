<?php
/**
 * Created by PhpStorm.
 * User: xujin
 * Date: 2017/8/10
 * Time: 下午9:50
 */

namespace App\Http\Controllers\Admin;


use App\Jobs\CreateTplProjectPushByOpenid;
use App\Models\TplProject;
use App\Services\WxWyeth;
use Illuminate\Http\Request;

//推送项目
class TplProjectController extends BaseController
{
    public function index(){

    }

    public function store(Request $request){
        $id = $request->input('id');
        if ($id){
            $tpl_project = TplProject::find($id);
            if (!$tpl_project){
                return $this->ajaxError('推送项目不存在');
            }
        }else{
            $tpl_project = new TplProject();
        }

        $tpl_project->title = $request->input('title', '');
        $tpl_project->notify_title = $request->input('notify_title', '');
        $tpl_project->notify_content = $request->input('notify_content', '');
        $tpl_project->notify_remark = $request->input('notify_remark', '');
        $tpl_project->notify_odate = $request->input('notify_odate', '');
        $tpl_project->notify_address = $request->input('notify_address', '');
        $tpl_project->notify_template_id = $request->input('notify_template_id', 4);
        $tpl_project->notify_url = $request->input('notify_url', '');
        $tpl_project->remark = $request->input('remark', '');

        $tpl_project->save();
        return $tpl_project->toJson();
    }

    //指定openid推送
    public function tpl_project_push(Request $request){
        $pid = $request->input('pid');
        $openids = $request->input('openids');
        $abtest = $request->input('abtest', '');

        $tpl_project = TplProject::find($pid);
        if (!$tpl_project){
            return $this->ajaxError('推送项目不存在');
        }

        // 指定openid
        if (!$openids) {
            return $this->ajaxError('发送失败，没有指定openid');
        }
        $openid_list = explode("\n", $openids);
        if (count($openid_list) > 3000) {
            return $this->ajaxError('openid个数不能超过3000条');
        }
        $openidArr = [];
        foreach ($openid_list as $k => $v) {
            $v = trim($v);
            if ($v && strlen($v) > 10 && strlen($v) < 60) {
                $openidArr[] = $v;
            }
        }
        $openidsChunks = array_chunk($openidArr, 500);
        if ($openidsChunks) {
            foreach ($openidsChunks as $openidArr) {
                $this->dispatch(new CreateTplProjectPushByOpenid($tpl_project->id, $openidArr, $abtest));
            }
        }
        return $this->ajaxMsg('发送成功');
    }

    //预览
    public function preview(Request $request){
        $pid = $request->input('pid');
        $tpl_project = TplProject::find($pid);
        if (!$tpl_project){
            return $this->ajaxError('推送项目不存在');
        }
        $openid = $request->input('openid');
        if (!$openid){
            return $this->ajaxError('no openid');
        }
        $params = [
            'pid' => $tpl_project->id,
            'title' => $tpl_project->notify_title,
            'content' => $tpl_project->notify_content,
            'odate' => $tpl_project->notify_odate,
            'address' => $tpl_project->notify_address,
            'remark' => "\n" . $tpl_project->notify_remark,
            'url' => $tpl_project->notify_url,
            'openid' => $openid,
            'abtest' => $request->input('abtest', '')
        ];
        $wx_wyeth = new WxWyeth();
        return $wx_wyeth->pushpushCustomMessage($params, $tpl_project->notify_template_id, false);
    }
}