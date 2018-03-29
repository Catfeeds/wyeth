<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin;
use App\Models\CourseTag;
use App\Models\Tag;
use App\Models\UserCourse;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\Qnupload;

use Excel;

class TagController extends BaseController
{
    public function getSearch(Request $request)
    {
//        $new_ids = Tag::getNewTag();

        $q = $request->input('q');
        if ($q == ' ' || $q == '') {
            $result['items'] = Tag::where('type', 0)->get();
        } else {
            $q = trim($q);
            $result = [
                'items' => [],
                'pagination' => ['more' => false]
            ];
            if (strlen($q) >= 1) {
                $result['items'] = Tag::where('name', 'like', "%$q%")->where('type', 0)->get();
            }
        }
//        foreach ($result['items'] as $item) {
//            $item['name'] .= '（新）';
//        }
        return  response()->json($result);
    }

    public function index(Request $request) {
        $data = $request->all();
        $user_info = Session::get('admin_info');
        $params = [];
        $params['name'] = $request->input('name');

        $md = Tag::where('id', '>', 0);

        !empty($params['name']) && $md->where("name", "=", $params['name']);

        if (!isset($data['type'])) {
            $data['type'] = 0;
        }

        $per_page = 10;
        $list = $md->where('type', $data['type'])->paginate($per_page);

        foreach ($list as $item) {
            $course_num = DB::table('course_tags')->where('tid', $item->id)->count();
            $item->course_num = $course_num;
        }

        return view('admin.tags.index', ['list' => $list, 'type' => $data['type'], 'params' => $params])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function add(Request $request) {
        $data = $request->all();

        $tag = DB::table('tags')->where('name', $data['name'])->first();
        if ($tag) {
            return $this->ajaxError('标签名已存在，不能重复', 0);
        }

        if (!empty($_FILES['img']) && $_FILES['img']['size'] > 0) {
            $data['img'] = Qnupload::upload($_FILES['img']);
        }
        if (isset($data['img']) && empty($data['img'])) {
            unset($data['img']);
        }

        if (!empty($_FILES['interest_img']) && $_FILES['interest_img']['size'] > 0) {
            $data['interest_img'] = Qnupload::upload($_FILES['interest_img']);
        }
        if (isset($data['interest_img']) && empty($data['interest_img'])) {
            unset($data['interest_img']);
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $id = DB::table('tags')->insertGetId($data);

        if ($id > 0) {
            TagService::updateTag();
            return view('admin.error', ['msg' => '添加成功，进入列表', 'url' => '/admin/tags']);
//            return $this->ajaxMsg('添加成功', 1);
        } else {
            return view('admin.error', ['msg' => '添加失败，进入列表', 'url' => '/admin/tags']);
//            return $this->ajaxError('添加失败', 0);
        }
    }

    public function edit(Request $request) {
        $data = $request->all();

        $tag = DB::table('tags')->where('name', $data['name'])->first();
        if ($tag && (int)$data['id'] != $tag->id) {
            return $this->ajaxError('标签名已存在，不能重复', 0);
        }

        if (!empty($_FILES['img']) && $_FILES['img']['size'] > 0) {
            $data['img'] = Qnupload::upload($_FILES['img']);
        }
        if (isset($data['img']) && empty($data['img'])) {
            unset($data['img']);
        }

        if (!empty($_FILES['interest_img']) && $_FILES['interest_img']['size'] > 0) {
            $data['interest_img'] = Qnupload::upload($_FILES['interest_img']);
        }
        if (isset($data['interest_img']) && empty($data['interest_img'])) {
            unset($data['interest_img']);
        }

        $id = DB::table('tags')->where('id', $data['id'])->update($data);

        if ($id > 0) {
            return view('admin.error', ['msg' => '修改成功，进入列表', 'url' => '/admin/tags']);
//            return $this->ajaxMsg('更新成功', 1);
        } else {
            return view('admin.error', ['msg' => '修改失败，进入列表', 'url' => '/admin/tags']);
//            return $this->ajaxError('添加失败', 0);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        $tag = Tag::find($id);
        if (!$tag) {
            return $this->ajaxError('标签不存在');
        }

        DB::table('tags')->where("id", $id)->delete();
        DB::table('course_tags')->where("tid", $id)->delete();

        TagService::updateTag();
        return $this->ajaxMsg("删除成功", 1);
    }

    public function exportCourseTags () {

        $list = DB::table('course')->get();

        foreach ($list as $value) {
            $a = [];
            $ids = [];
            $tags = DB::table('course_tags')->where('cid', $value->id)->get();
            foreach ($tags as $tag) {
                $t = Tag::where('id', $tag->tid)->first();
                $a[] = $t->name;
                $ids[] = $t->id;
            }
            $s1 = implode(", ", $a);
            $s2 = implode(", ", $ids);
            $export[] = array(
                'ID' => $value->id,
                '名称' => $value->title,
                '标签' => $s1,
                '标签ID组' => $s2
            );
        }

        Excel::create('课程tag表', function ($excel) use ($export) {
            $excel->sheet('course', function($sheet) use ($export) {
                $sheet->rows($export);
            });
        })->export('xls');
    }
}
