<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/8/17
 * Time: 10:02
 */

namespace App\Http\Controllers\Admin;

use App\Models\AppConfig;
use App\Models\User;
use App\Services\WxWyeth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AutoPushController extends BaseController
{

    public function index(Request $request)
    {
        $user_info = Session::get('admin_info');
        $config = AppConfig::where('module', AppConfig::MODULE_AUTIO_PUSH)
            ->where('key', AppConfig::KEY_FULI_TPL)
            ->first();

        return view('admin.module.auto_push', ['data' => $config == null ? [] : $config->data])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => $request->admin->menu])
            ->nest('footer', 'admin.common.footer', []);
    }

    //保存
    public function save(Request $request)
    {
        $data = $request->all();

        $auto_push = AppConfig::where('module', AppConfig::MODULE_AUTIO_PUSH)
            ->where('key', AppConfig::KEY_FULI_TPL)
            ->first();

        if ($auto_push) {
            $auto_push->data = $data;
            $auto_push->save();
        } else {
            $auto_push = new AppConfig();
            $auto_push->module = AppConfig::MODULE_AUTIO_PUSH;
            $auto_push->key = AppConfig::KEY_FULI_TPL;
            $auto_push->data = $data;
            $auto_push->save();
        }
        return $this->ajaxMsg('保存成功');
    }

    //预览
    public function preview_tplmsg(Request $request)
    {
        //预约标题
        //预约人
        //预约项目
        //预约时间
        //预约备注
        $params = [
            'title' => $request->input('notify_title'),
            'content' => $request->input('notify_content'),
            'odate' => $request->input('notify_odate'),
            'address' => $request->input('notify_address'),
            'remark' => $request->input('notify_remark', ''),
            'url' => $request->input('notify_url', ''),
            'openid' => $request->input('notify_openid'),
        ];
        $template_id = 5;

        if (!$params['address']){
            $params['address'] = date('Y-m-d');
        }
        if (!$params['content']){
            $user = User::where('openid', $params['openid'])->first();
            $params['content'] = $user->nickname;
        }


        $wxWyeth = new WxWyeth();
        return $wxWyeth->pushpushCustomMessage($params, $template_id, false);
    }

}