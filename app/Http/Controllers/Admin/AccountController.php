<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Repositories\FindRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Redirect, Input;

class AccountController extends Controller
{

    function index(Request $request)
    {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = $request->all();
        $list = Admin::paginate($per_page);
        return view('admin.account.index', ['list' => $list, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    function add(Request $request, $id)
    {
        $data = $request->all();
        if ($request->method() == 'POST') {
            if ($id) {
                if (!empty($data['cids'])) {
                    $data['cids'] = json_encode($data['cids']);
                } else {
                    $data['cids'] = '[]';
                }

                DB::table('admin')->where('id', $id)->update($data);
                return view('admin.error', ['msg' => '已更新', 'url' => '/admin/account']);
            } else {
                if (!empty($data['cids'])) {
                    $data['cids'] = json_encode($data['cids']);
                }
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['password'] = md5($data['password']);
                $id = DB::table('admin')->insertGetId($data);

                if ($id > 0) {
                    return view('admin.error', ['msg' => '添加成功，进入列表', 'url' => '/admin/account']);
                } else {
                    return view('admin.error', ['msg' => '添加失败，请重试']);
                }
            }
        }
        $courses = Course::where('display_status', 1)->get()->toArray();

        $info = Admin::where('id', $id)->first();

        $platform = (new FindRepository())->getAuthorByPage(0, 100);

        return view('admin.account.add', ['info' => $info, 'id' => $id, 'courses' => $courses, 'platform' => $platform['data']])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    function delete($id)
    {
        $id = intval($id);
        DB::table('admin')->where("id", $id)->delete();
        return view('admin.error', ['msg' => '已删除', 'url' => '/admin/account']);
    }
}
