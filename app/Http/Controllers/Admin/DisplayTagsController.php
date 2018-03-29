<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/9/18
 * Time: 11:17
 */

namespace App\Http\Controllers\Admin;

use App\Models\DisplayTags;
use App\Models\Tag;
use App\Services\Qnupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DisplayTagsController extends BaseController {

    public function getSearch (Request $request) {
        $q = $request->input('q');
        $q = trim($q);
        $result = [
            'items' => [],
            'pagination' => ['more' => false]
        ];
        $result['items'] = Tag::where('type', Tag::TAG_DISPLAY)->where('name', 'like', "%$q%")->get();
        return  response()->json($result);
    }

    public function index (Request $request) {
        $user_info = Session::get('admin_info');
        $params = [];
        $params['name'] = $request->input('name');
        $md = Tag::where('id', '>', 0)->where('type', Tag::TAG_DISPLAY);

        !empty($params['name']) && $md->where("name", "like", "%" . $params['name'] . "%");

        $per_page = 10;
        $list = $md->paginate($per_page);

        return view('admin.tags.display_tags', ['list' => $list, 'params' => $params])
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
        $data['type'] = 3;
        if ($data['id']) {
            $id = DB::table('tags')->where('id', $data['id'])->update($data);
            if ($id > 0) {
                return view('admin.error', ['msg' => '修改成功，进入列表', 'url' => '/admin/display_tags']);
            } else {
                return view('admin.error', ['msg' => '修改失败，进入列表', 'url' => '/admin/display_tags']);
            }
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $id = DB::table('tags')->insertGetId($data);
            if ($id > 0) {
                return view('admin.error', ['msg' => '添加成功，进入列表', 'url' => '/admin/display_tags']);
            } else {
                return view('admin.error', ['msg' => '添加失败，进入列表', 'url' => '/admin/display_tags']);
            }
        }
    }

    public function delete (Request $request) {
        $id = $request->input('id');

        $tag = DB::table('tags')->where('id', $id);
        if (!$tag) {
            return $this->ajaxError('标签不存在');
        }

        $tag->delete();

        return $this->ajaxMsg("删除成功", 1);
    }
}