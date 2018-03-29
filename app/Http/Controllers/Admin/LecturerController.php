<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Course;
use App\Models\CourseTag;
use App\Models\Lecturer;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\Qnupload;

use Excel;

class LecturerController extends BaseController {

    public function getSearch(Request $request) {
        $q = $request->input('q');
        if ($q == '' || $q == ' ') {
            $result['items'] = Lecturer::get();
        } else {
            $q = trim($q);
            $result = [
                'items' => [],
                'pagination' => ['more' => false]
            ];
            if (strlen($q) >= 1) {
                $result['items'] = Lecturer::where('name', 'like', "%$q%")->take(10)->get();
            }
        }
        return  response()->json($result);
    }

    public function index(Request $request) {
        $user_info = Session::get('admin_info');
        $per_page = 10;
        $params = [];
        $params['id'] = $request->input('id');
        $params['name'] = $request->input('name');
        $params['area'] = $request->input('area');

        if ($params['id']) {
            $md = Lecturer::where('id', '=', $params['id']);
        } else {
            $md = Lecturer::where('id', '>', 0);
        }

        !empty($params['area']) && $md->where("hospital", "like", "%" . $params['area'] . "%");
        !empty($params['name']) && $md->where("name", "=", $params['name']);

        $list = $md->orderBy('id', 'desc')->paginate($per_page);

        foreach ($list as $item) {
            $course_num = DB::table('course_tags')->where('tid', $item->tid)->count();
            $item->course_num = $course_num;
        }

        return view('admin.lecturer.index', ['list' => $list, 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function add(Request $request, $id) {
//        dd($id);
        $data = $request->all();
        if ($request->method() == 'POST') {
            if ($id) {
                $teacher = Lecturer::where('id', $id)->first();
                if ($teacher['name'] != $data['name']) {
                    $tag = Tag::where('name', $data['name'])->first();
                    if ($tag) {
                        return view('admin.error', ['msg' => '教师姓名不能重复，请重新填写']);
                    }
                }

                if (!empty($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
                    $data['avatar'] = Qnupload::upload($_FILES['avatar']);
                }
                if (!isset($data['avatar']) && empty($data['avatar'])) {
                    $data['avatar'] = $data['hideAvatar'];
                }
                unset($data['hideAvatar']);

                $update_arr = array('name' => $data['name'], 'type' => 2);
                if (array_key_exists('avatar', $data)) {
                    $update_arr['img'] = $data['avatar'];
                }

                $course_tags = CourseTag::where('tid', $teacher['tid'])->get();
                foreach ($course_tags as $c) {
                    $course = Course::find($c->cid);
                    if ($course) {
                        $course->teacher_name = $data['name'];
                        $course->teacher_avatar = $data['avatar'];
                        $course->teacher_hospital = $data['hospital'];
                        $course->teacher_position = $data['position'];
                        $course->teacher_desc = $data['desc'];
                        $course->save();
                    }
                }

                DB::table('tags')->where('id', $teacher['tid'])->update($update_arr);

                DB::table('teacher')->where('id', $id)->update($data);
                return view('admin.error', ['msg' => '已更新', 'url' => '/admin/lecturer']);
            } else {
                $tag = Tag::where('name', $data['name'])->first();
                if ($tag) {
                    return view('admin.error', ['msg' => '教师姓名不能重复，请重新填写']);
                }

                if (!empty($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
                    $data['avatar'] = Qnupload::upload($_FILES['avatar']);
                }
                if (isset($data['avatar']) && empty($data['avatar'])) {
                    unset($data['avatar']);
                }
                unset($data['hideAvatar']);
//                dd($data);

                if (isset($data['avatar'])) {
                    $insert_arr = array('name' => $data['name'], 'type' => 2, 'img' => $data['avatar'], 'created_at' => date('Y-m-d H:i:s'));
                } else {
                    $insert_arr = array('name' => $data['name'], 'type' => 2, 'created_at' => date('Y-m-d H:i:s'));
                }

                $tid = DB::table('tags')->insertGetId($insert_arr);

                $data['created_at'] = date('Y-m-d H:i:s');
                $data['tid'] = $tid;
                $id = DB::table('teacher')->insertGetId($data);

                if ($id > 0) {
                    return view('admin.error', ['msg' => '添加成功，进入列表', 'url' => '/admin/lecturer']);
                } else {
                    return view('admin.error', ['msg' => '添加失败，请重试']);
                }
            }
        }

        $info = DB::table('teacher')->where('id', $id)->first();
        return view('admin.lecturer.add', ['info' => $info, 'id' => $id])
            ->nest('header', 'admin.common.header', ['user_info' => Session::get('admin_info')])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function export() {
        $list = DB::table('teacher')->get();

        foreach ($list as $value) {
            $course_num = DB::table('course_tags')->where('tid', $value->tid)->count();
            $export[] = array(
                'ID' => $value->id,
                '姓名' => $value->name,
                '所属医院' => $value->hospital,
                '讲师职位' => $value->position,
                '讲师描述' => $value->desc,
                '关联课程数' => $course_num
            );
        }

        Excel::create('讲师列表',function($excel) use ($export){
            $excel->sheet('lecturer', function($sheet) use ($export){
                $sheet->rows($export);
            });
        })->export('xls');
    }
}