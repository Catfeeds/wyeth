<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Redirect, Input;
use App\Services\Qnupload;

class CityController extends Controller
{

    function index(Request $request)
    {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = $request->all();
        $list = DB::table('area_city')->paginate($per_page);
        return view('admin.city.index', ['list' => $list, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    function add(Request $request, $id)
    {
        $data = $request->all();
        if ($request->method() == 'POST') {
            if ($id) {
                if (!empty($_FILES['img']) && $_FILES['img']['size'] > 0) {
                    $data['img'] = Qnupload::upload($_FILES['img']);
                }
                if (isset($data['img']) && empty($data['img'])) {
                    unset($data['img']);
                }

                DB::table('area_city')->where('id', $id)->update($data);
                return view('admin.error', ['msg' => '已更新', 'url' => '/admin/city']);
            } else {
                if (!empty($_FILES['img']) && $_FILES['img']['size'] > 0) {
                    $data['img'] = Qnupload::upload($_FILES['img']);
                }
                if (isset($data['img']) && empty($data['img'])) {
                    unset($data['img']);
                }

                $data['created_at'] = date('Y-m-d H:i:s');
                $id = DB::table('area_city')->insertGetId($data);

                if ($id > 0) {
                    return view('admin.error', ['msg' => '添加成功，进入列表', 'url' => '/admin/city']);
                } else {
                    return view('admin.error', ['msg' => '添加失败，请重试']);
                }
            }
        }

        $info = DB::table('area_city')->where('id', $id)->first();
        return view('admin.city.add', ['info' => $info, 'id' => $id])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    function delete($id)
    {
        $id = intval($id);
        DB::table('area_city')->where("id", $id)->delete();
        return view('admin.error', ['msg' => '已删除', 'url' => '']);
    }
}
