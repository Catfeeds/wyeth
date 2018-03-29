<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/10/31
 * Time: 16:31
 */

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BrandController extends BaseController {

    public function index (Request $request) {
        $user_info = Session::get('admin_info');

        $md = DB::table('brand');

        $list = $md->paginate(10);

        return view('admin.brand.index', ['list' => $list])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer');
    }

    public function edit (Request $request) {
        $data = $request->all();

        if ($data['id']) {
            $brand = Brand::find($data['id']);
            $brand->name = $data['name'];
            $brand->save();
            return view('admin.error', ['msg' => '修改成功,进入列表', 'url' => '/admin/brand']);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            DB::table('brand')->insertGetId($data);
            return view('admin.error', ['msg' => '添加成功,进入列表', 'url' => '/admin/brand']);
        }
    }
}