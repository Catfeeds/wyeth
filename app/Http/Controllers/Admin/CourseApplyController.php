<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseApply;
use Endroid\QrCode\QrCode;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Input;
use Redirect;

class CourseApplyController extends Controller
{
    public function index(Request $request)
    {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = $request->all();
        $list = CourseApply::where('account_id', $user_info->id)->paginate($per_page);
        return view('admin.course_apply.index', ['list' => $list, 'params'=>$params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function add(Request $request)
    {
        $user_info = Session::get('admin_info');
        $data = $request->all();

        if (!empty($data['title'])) {

            $data['stage'] = $this->do_stage($data['stage']);
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['account_id'] = $user_info->id;
            $data['area'] = $user_info->area;
            $id = DB::table('course_apply')->insertGetId($data);

            if ($id > 0) {
                return view('admin.error', ['msg' => '添加成功，进入课程申请列表', 'url' => '/admin/course_apply']);
            } else {
                return view('admin.error', ['msg' => '添加失败，请重试']);
            }
        }
        return view('admin.course_apply.add', [])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function edit(Request $request, $id)
    {
        $user_info = Session::get('admin_info');
        $id = intval($id);
        empty($id) && Redirect('/admin/course_apply/add');
        $info = DB::table('course_apply')->where("id", $id)->where("account_id", $user_info->id)->first();
        if (empty($info)) {
            return view('admin.error', ['msg' => '数据不存在']);
        }

        $data = $request->all();
        if (!empty($data['title'])) {
            $data['status'] = isset($data['status']) && $info->status == 2 ? 0 : $info->status;
            $data['stage'] = $this->do_stage($data['stage']);

            DB::table('course_apply')->where('id', $id)->update($data);
            return view('admin.error', ['msg' => '已更新', 'url' => '/admin/course_apply']);
        }
        $info->stage = $this->redo_stage($info->stage);
        return view('admin.course_apply.edit', ['info' => $info, 'id' => $id])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function delete($id)
    {
        $id = intval($id);
        DB::table('course_apply')->where("id", $id)->delete();
        return view('admin.error', ['msg' => '已删除', 'url' => '/admin/course_apply']);
    }

    //传入数组，返回字符串
    public function do_stage($stage_arr)
    {
        $string = $stage_arr[1][1];
        if ($stage_arr[1][1] == '孕中') {
            $string .= ' ' . $stage_arr[1][2];
        } elseif ($stage_arr[1][1] == '宝宝') {
            $string .= ' ' . $stage_arr[1][3];
        }

        $string .= ' - ' . $stage_arr[2][1];
        if ($stage_arr[2][1] == '孕中') {
            $string .= ' ' . $stage_arr[2][2];
        } elseif ($stage_arr[2][1] == '宝宝') {
            $string .= ' ' . $stage_arr[2][3];
        }
        return $string;
    }

    //传入字符串，返回数组
    public function redo_stage($string)
    {
        $re = [];
        $tmp_1 = explode('-', $string);
        if (isset($tmp_1[0])) {
            $tmp_1[0] = trim($tmp_1[0]);
            $re[1] = explode(' ', $tmp_1[0]);
            !isset($re[1][1]) && $re[1][1] = '';
        } else {
            $re[1] = array(0 => '', 1 => '');
        }
        if (isset($tmp_1[1])) {
            $tmp_1[1] = trim($tmp_1[1]);
            $re[2] = explode(' ', $tmp_1[1]);
            !isset($re[2][1]) && $re[2][1] = '';
        } else {
            $re[2] = array(0 => '', 1 => '');
        }
        return $re;
    }
}