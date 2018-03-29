<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/8/17
 * Time: 10:02
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use App\Services\Qnupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class ActivityColumnController extends Controller {
    public function index (Request $request) {
        $user_info = Session::get('admin_info');
        $data = AppConfig::where(['module' => 'activity', 'key' => 'column'])->first();

        return view('admin.module.activity_column', ['data' => $data == null ? [] : $data->data])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => $request->admin->menu])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function store (Request $request) {
        $data = $request->all();

        if (!empty($_FILES['img']) && $_FILES['img']['size'] > 0) {
            $data['img'] = Qnupload::upload($_FILES['img']);
        }
        if (!isset($data['img']) && empty($data['img'])) {
            $data['img'] = $data['hideImg'];
//            unset($data['img']);
        }

        if (!empty($_FILES['qrcode']) && $_FILES['qrcode']['size'] > 0) {
            $data['qrcode'] = Qnupload::upload($_FILES['qrcode']);
        }
        if (!isset($data['qrcode']) && empty($data['qrcode'])) {
            $data['qrcode'] = $data['hideQrcode'];
//            unset($data['img']);
        }

        $activity_config = AppConfig::where(['module' => 'activity', 'key' => 'column'])->first();

        if ($activity_config) {
            $activity_config->data = $data;
            $activity_config->save();
        } else {
            $activity_config = new AppConfig();
            $activity_config->module = 'activity';
            $activity_config->key = 'column';
            $activity_config->data = $data;
            $activity_config->save();
        }
        return view('admin.error', ['msg' => '已更新', 'url' => "/admin/app_config/activity_column"]);
    }
}