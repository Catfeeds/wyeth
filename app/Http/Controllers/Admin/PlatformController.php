<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/10/9
 * Time: 14:07
 */

namespace App\Http\Controllers\Admin;

use App\Repositories\FindRepository;
use App\Services\Qnupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PlatformController extends BaseController {
    public function index (Request $request) {
        $data = $request->all();
        $user_info = Session::get('admin_info');
        $params = [];

        $findRepo = new FindRepository();
        if (array_key_exists('page', $data)) {
            $list  = $findRepo->getAuthorByPage($data['page'], 10);
        } else {
            $list  = $findRepo->getAuthorByPage(0, 10);
        }

        return view('admin.materiel.platform', ['list' => $list['data'], 'params' => $params, 'total' =>$list['total']])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function edit (Request $request) {
        $data = $request->all();

        if (!empty($_FILES['platform_logo']) && $_FILES['platform_logo']['size'] > 0) {
            $data['platform_logo'] = Qnupload::upload($_FILES['platform_logo']);
        } else {
            $data['platform_logo'] = '';
        }

        $findRepo = new FindRepository();
        if ($data['id']) {
            $res = $findRepo->updateAuthor($data['id'], $data['old_name'], $data['platform_name'], $data['platform_logo']);
            if ($res['ret'] == 1) {
                return view('admin.error', ['msg' => '修改成功，进入列表', 'url' => '/admin/platform']);
            } else {
                return view('admin.error', ['msg' => '修改失败，进入列表', 'url' => '/admin/platform']);
            }
        } else {
            $res = $findRepo->addAuthor($data['platform_name'], $data['platform_logo']);
            if ($res['ret'] == 1) {
                return view('admin.error', ['msg' => '添加成功，进入列表', 'url' => '/admin/platform']);
            } else {
                return view('admin.error', ['msg' => '添加失败，进入列表', 'url' => '/admin/platform']);
            }
        }
    }

    public function delete (Request $request) {
        $id = $request->input('id');

        $findRepo = new FindRepository();
        $res = $findRepo->deleteAuthor($id);

        if ($res['ret'] == 1) {
            return $this->ajaxMsg("删除成功", 1);
        } else {
            return $this->ajaxError('删除失败', 0);
        }
    }
}