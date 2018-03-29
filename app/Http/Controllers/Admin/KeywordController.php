<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/10/18
 * Time: 14:13
 */

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeywordController extends BaseController {
    public function getSearch (Request $request) {
        $q = $request->input('q');
        $result = [
            'items' => [],
            'pagination' => ['more' => false]
        ];
        $result['items'] = DB::table('keyword')->where('name', 'like', "%$q%")->get();

        return response()->json($result);
    }

    public function add ($name) {
        $data = [
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $id = DB::table('keyword')->insertGetId($data);

        return strval($id);
    }
}