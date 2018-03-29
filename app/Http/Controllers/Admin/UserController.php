<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Qnupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Qrcode;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = $request->all();
        $query = DB::table('user');
        if (!empty($params['nickname'])) {
            $query->where("nickname", "like", "%" . $params['nickname'] . "%");
        }
        $list = $query->orderBy('id', 'desc')->simplePaginate($per_page);
        return view('admin.user.index', ['list' => $list, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function edit(Request $request, $id)
    {
        $user_info = Session::get('admin_info');
        $id = intval($id);
        $info = DB::table('user')->where("id", $id)->first();
        if (empty($info)) {
            return view('admin.error', ['msg' => '数据不存在']);
        }
        $data = $request->all();
        if (!empty($data)) {
            if ($_FILES['avatar']['size'] > 0) {
                $data['avatar'] = Qnupload::upload($_FILES['avatar']);
            }
            if (isset($data['avatar']) && empty($data['avatar'])) {
                unset($data['avatar']);
            }

            DB::table('user')->where('id', $id)->update($data);
            return view('admin.error', ['msg' => '已更新', 'url' => '/admin/user/index']);
        }
        return view('admin.user.edit', ['info' => $info, 'id' => $id])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function delete($id)
    {
        $id = intval($id);
        DB::table('user')->where("id", $id)->delete();
        return view('admin.error', ['msg' => '已删除', 'url' => '']);
    }

    public function delete_all(Request $request)
    {
        $params = $request->all();
        $id_arr = $params['id'];
        if (empty($id_arr)) {
            return view('admin.error', ['msg' => '请选择要删除的对象']);
        }
        DB::table('user')->whereIn('id', $id_arr)->delete();
        return view('admin.error', ['msg' => '已删除', 'url' => '']);
    }

    public function qrcode(Request $request)
    {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = $request->all();
        $list = DB::table('user_qrcode')->paginate($per_page);
        return view('admin.user.qrcode', ['list' => $list, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function qrcode_add(Request $request, $id)
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

                DB::table('user_qrcode')->where('id', $id)->update($data);
                return view('admin.error', ['msg' => '已更新', 'url' => '/admin/user/qrcode']);
            } else {
                if (!empty($_FILES['img']) && $_FILES['img']['size'] > 0) {
                    $data['img'] = Qnupload::upload($_FILES['img']);
                }
                if (isset($data['img']) && empty($data['img'])) {
                    unset($data['img']);
                }

                $data['created_at'] = date('Y-m-d H:i:s');
                $id = DB::table('user_qrcode')->insertGetId($data);

                if ($id > 0) {
                    return view('admin.error', ['msg' => '添加成功，进入列表', 'url' => '/admin/user/qrcode']);
                } else {
                    return view('admin.error', ['msg' => '添加失败，请重试']);
                }
            }
        }

        $info = DB::table('user_qrcode')->where('id', $id)->first();
        $user_info = Session::get('admin_info');
        return view('admin.user.qrcode_add', ['info' => $info, 'id' => $id])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function qrcode_delete($id)
    {
        $id = intval($id);
        DB::table('user_qrcode')->where("id", $id)->delete();
        return view('admin.error', ['msg' => '已删除', 'url' => '']);
    }

    public function user_in_qrcode(Request $request)
    {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = $request->all();
        $list = Qrcode::paginate($per_page);

        // echo "string";
        // exit();
        return view('admin.user.user_in_qrcode', ['list' => $list, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function userInQrcodeAdd ()
    {
        $data = [
          'action' => 'add',
        ];
        
        $user_info = Session::get('admin_info');
        return view('admin.user.user_in_qrcode_add', $data)
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function userInQrcodeEdit($id)
    {
        if (!$id) {
            return view('admin.error', ['msg' => '对不起，没有数据', 'url' => '/admin/user/user_in_qrcode']);
        }
        $info = Qrcode::find($id);
        if (!$info) {
            return view('admin.error', ['msg' => '对不起，没有数据', 'url' => '/admin/user/user_in_qrcode']);
        }
        if (!$info->display_channel) {
            $info->display_channel = [];
        }

        $data = [
            'info' => $info,
            'id' => $id,
            'action' => 'edit',
        ];
        
        $user_info = Session::get('admin_info');
        return view('admin.user.user_in_qrcode_add',$data)
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function userInQrcodeDelete($id)
    {
        if (!$id) {
            return view('admin.error', ['msg' => '删除失败', 'url' => '/admin/user/user_in_qrcode']);
        }
        $id = intval($id);

        $qrcode = Qrcode::find($id);
        if (!$qrcode) {
            return view('admin.error', ['msg' => '删除失败', 'url' => '/admin/user/user_in_qrcode']);
        }

        $rel = $qrcode->delete();
        if ($rel) {
            return view('admin.error', ['msg' => '删除成功', 'url' => '/admin/user/user_in_qrcode']);
        } else {
            return view('admin.error', ['msg' => '删除失败', 'url' => '/admin/user/user_in_qrcode']);
            return view('admin.error', ['msg' => '删除失败', 'url' => '/admin/user/user_in_qrcode']);
        }
    }

    public function userInQrcodeSave(Request $request)
    {
        $action = $request->input('action');
        if (!$action) {
            return view('admin.error', ['msg' => '保存失败！', 'url' => '/admin/user/user_in_qrcode']);
        }

        if ($action == 'edit') {
            $id = intval($request->input('id'));
            if (!$id) {
                return view('admin.error', ['msg' => '保存失败！', 'url' => '/admin/user/user_in_qrcode']);
            }
        }

        if (!empty($_FILES['imgurl']) && $_FILES['imgurl']['size'] > 0) {
            $imgurl = Qnupload::upload($_FILES['imgurl']);
            if (!$imgurl) {
                return view('admin.error', ['msg' => '保存失败！', 'url' => '/admin/user/user_in_qrcode']);
            }
        } else {
            $imgurl = false;
        }

        $name = $request->input('name');
        if (!$name) {
            $name = '';
        }
        $link = $request->input('link');
        if (!$link) {
            $link = '';
        }

        $stage = $request->input('stage');
        if (!$stage) {
            return view('admin.error', ['msg' => '保存失败', 'url' => '/admin/user/user_in_qrcode']);
        }

        $display_channel = $request->input('display_channel');
        if (!$display_channel) {
            return view('admin.error', ['msg' => '保存失败：没有选择展示渠道', 'url' => '/admin/user/user_in_qrcode']);
        }

        if ($action == 'add') {
            if (!$imgurl) {
                return view('admin.error', ['msg' => '保存失败：没有选择图片！', 'url' => '/admin/user/user_in_qrcode']);
            }

            $qrcode = new Qrcode;
            $qrcode->imgurl = $imgurl;
            $qrcode->name = $name;
            $qrcode->stage = $stage;
            $qrcode->display_channel = $display_channel;
            $res = $qrcode->save();
            if ($res) {
                return view('admin.error', ['msg' => '保存成功！', 'url' => '/admin/user/user_in_qrcode']);
            } else {
                return view('admin.error', ['msg' => '保存失败！', 'url' => '/admin/user/user_in_qrcode']);
            }

        }

        if ($action == 'edit') {
            $qrcode = Qrcode::find($id);
            if ($qrcode) {
                if ($imgurl) {
                    $qrcode->imgurl = $imgurl;
                }
                $qrcode->name = $name;
                $qrcode->link = $link;
                $qrcode->stage = $stage;
                $qrcode->display_channel = $display_channel;
                $res = $qrcode->save();
                if ($res) {
                    return view('admin.error', ['msg' => '保存成功！', 'url' => '/admin/user/user_in_qrcode']);
                } else {
                    return view('admin.error', ['msg' => '保存失败！', 'url' => '/admin/user/user_in_qrcode']);
                }
            } else {
                return view('admin.error', ['msg' => '保存失败！', 'url' => '/admin/user/user_in_qrcode']);
            }
        }
    }

}
