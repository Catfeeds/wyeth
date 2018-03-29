<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/8/31
 * Time: 13:55
 */

namespace App\Http\Controllers\Admin;


use App\Models\Advertise;
use App\Models\Brand;
use App\Services\Qnupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdvertiseController extends BaseController
{
    public function index (Request $request) {
        $data = $request->all();
        $user_info = Session::get('admin_info');
        $params = [];
        $params['brand_id'] = $request->input('brand_id');
        $params['position'] = $request->input('position');
        $params['type'] = $request->input('type');

        $md = Advertise::where('id', '>' , 0);

        // !empty($params['brand_id']) && $md->where("brand_id", "=", $params['brand_id']);
        !empty($params['position']) && $md->where("position", "=", $params['position']);
        !empty($params['type']) && $md->where("type", "=", $params['type']);

        if (!isset($data['version'])) {
            $data['version'] = 0;
        }

        $per_page = 10;
        $list = $md->where('version', $data['version'])->orderBy('display', 'desc')->paginate($per_page);

        foreach ($list as $item) {
            $brand = Brand::find($item->brand_id);
            if ($brand) {
                $item->brand = $brand->name;
            } else {
                $item->brand = '无';
            }
        }

        $brands = Brand::where('id', '>', 0)->get();

        return view('admin.advertise.index', ['list' => $list, 'params' => $params, 'brands' => $brands, 'version' => $data['version']])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function edit (Request $request) {
        $data = $request->all();

        if (!empty($_FILES['img']) && $_FILES['img']['size'] > 0) {
            $data['img'] = Qnupload::upload($_FILES['img']);
        }
        if (isset($data['img']) && empty($data['img'])) {
            unset($data['img']);
        }
        if ($data['id']) {
            $id = DB::table('advertise')->where('id', $data['id'])->update($data);
            if ($id > 0) {
                return view('admin.error', ['msg' => '修改成功，进入列表', 'url' => '/admin/advertise?type=' . $data['type'] . '&position=' . $data['position'] . '&version=' . $data['version']]);
            } else {
                return view('admin.error', ['msg' => '修改失败，进入列表', 'url' => '/admin/advertise?type=' . $data['type'] . '&position=' . $data['position'] . '&version=' . $data['version']]);
            }
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $id = DB::table('advertise')->insertGetId($data);
            if ($id > 0) {
                return view('admin.error', ['msg' => '添加成功，进入列表', 'url' => '/admin/advertise?type=' . $data['type'] . '&position=' . $data['position'] . '&version=' . $data['version']]);
            } else {
                return view('admin.error', ['msg' => '添加失败，进入列表', 'url' => '/admin/advertise?type=' . $data['type'] . '&position=' . $data['position'] . '&version=' . $data['version']]);
            }
        }
    }

    public function delete (Request $request) {
        $id = $request->input('id');

        $advertise = DB::table('advertise')->where('id', $id);
        if (!$advertise) {
            return $this->ajaxError('广告不存在');
        }

        $advertise->delete();

        return $this->ajaxMsg("删除成功", 1);
    }
}