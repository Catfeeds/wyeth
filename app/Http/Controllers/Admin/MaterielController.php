<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/8/29
 * Time: 9:56
 */

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Materiel;
use App\Repositories\FindRepository;
use App\Services\Qnupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

require app_path().'/Helpers/simple_html_dom.php';

class MaterielController extends BaseController {

    public function index (Request $request) {
        $data = $request->all();
        $user_info = Session::get('admin_info');
        $params = [];
        $params['key_word'] = $request->input('key_word');
        $params['platform'] = $request->input('platform');

        $md = DB::table('materiel');

        if ($user_info->user_type == Admin::IDT_MATERIEL) {
            $md->where('platform_name', '=', $user_info->user_platform);
        }

        $platform = (new FindRepository())->getAuthorByPage(0, 100);

        $brands = Brand::all();

        !empty($params['key_word']) && $md->where('name', 'like', "%" . $params['key_word'] . "%")
            ->orWhere('key_word', 'like', "%" . $params['key_word'] . "%")
            ->orWhere('brand', 'like', "%" . $params['key_word'] . "%");

        !empty($params['platform']) && $md->where('platform_name', $params['platform']);

        $per_page = 10;
        $list = $md->orderBy('id', 'desc')->paginate($per_page);

        return view('admin.materiel.index', ['list' => $list, 'params' => $params, 'platform' => $platform['data'], 'user_type' => $user_info->user_type, 'user_info' => $user_info, 'brands' => $brands])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function edit (Request $request, $id) {
        $data = $request->all();
        $user_info = Session::get('admin_info');

        if ($request->method() == 'POST') {
            if (!empty($_FILES['banner']) && $_FILES['banner']['size'] > 0) {
                $data['banner'] = Qnupload::upload($_FILES['banner']);
            }
            if (isset($data['banner']) && empty($data['banner'])) {
                unset($data['banner']);
            }

            $author = (new FindRepository())->getAuthorById($request->input('platform_name'));
            $findRepo = new FindRepository();
            if ($id) {
                $materiel = Materiel::find($id);
                if ($materiel->cms_id) {
                    $findRepo->updateArticle($materiel->cms_id, $data['link'], $data['name'], array_key_exists('banner', $data) ? $data['banner'] : '', $author['data']['author_name'], $author['data']['author_avatar']);
                } else {
                    $cms_ret = $findRepo->upload($data['link'], $data['name'], array_key_exists('banner', $data) ? $data['banner'] : '', $author['data']['author_name'], $author['data']['author_avatar']);
                    if (!$cms_ret['error']) {
                        $materiel->cms_id = $cms_ret['data']['data'];
                    }
                }

                $keywords = explode(',', $data['key_word']);
                foreach ($keywords as $index => $k) {
                    if (!intval($k)) {
                        $key = DB::table('keyword')->where('name', $k)->first();
                        if ($key) {
                            $keywords[$index] = $key->id;
                        } else {
                            $id = (new KeywordController())->add($k);
                            $keywords[$index] = $id;
                        }
                    }
                }
                $materiel->key_word = implode(',', $keywords);
                array_key_exists('name', $data) ? $materiel->name = $data['name'] : null;
                array_key_exists('link', $data) ? $materiel->link = $data['link'] : null;
                array_key_exists('brand', $data) ? $materiel->brand = $data['brand'] : null;
                array_key_exists('platform_name', $data) ? $materiel->platform_name = $data['platform_name'] : null;
                array_key_exists('date', $data) ? $materiel->date = $data['date'] : null;
                array_key_exists('banner', $data) ? $materiel->banner = $data['banner'] : null;

//                $ret = DB::table('materiel')->where('id', $id)->update($data);
                $ret = $materiel->save();
                if ($ret > 0) {
                    return $this->ajaxMsg($materiel->cms_id, 1);
//                    return view('admin.error', ['msg' => '修改成功，进入列表', 'url' => '/admin/materiel']);
                } else {
                    return $this->ajaxMsg("修改失败", 0);
//                    return view('admin.error', ['msg' => '修改失败，进入列表', 'url' => '/admin/materiel']);
                }
            } else {
                $cms_ret = $findRepo->upload($data['link'], $data['name'], array_key_exists('banner', $data) ? $data['banner'] : '', $author['data']['author_name'], $author['data']['author_avatar']);
                $data['cms_id'] = 0;
                if (!$cms_ret['error']) {
                    $data['cms_id'] = $cms_ret['data']['data'];
                }

                $keywords = explode(',', $data['key_word']);
                foreach ($keywords as $index => $k) {
                    if (!intval($k)) {
                        $key = DB::table('keyword')->where('name', $k)->first();
                        if ($key) {
                            $keywords[$index] = $key->id;
                        } else {
                            $id = (new KeywordController())->add($k);
                            $keywords[$index] = $id;
                        }
                    }
                }
                $data['key_word'] = implode(',', $keywords);

                $data['created_at'] = date('Y-m-d H:i:s');
                $ret = DB::table('materiel')->insertGetId($data);

                if ($ret > 0) {
                    return $this->ajaxMsg($data['cms_id'], 1);
                } else {
                    return $this->ajaxMsg("修改失败", 0);
                }
            }
        }

        $platform = (new FindRepository())->getAuthorByPage(0, 100);

        $brands = Brand::all();

        $materiel = Materiel::find($id);

        $ret_keys = [];
        if ($materiel) {
            $keywords = explode(',', $materiel->key_word);
        } else {
            $keywords = [];
        }

        $all_key = DB::table('keyword')->get();
        foreach ($keywords as $k) {
            foreach ($all_key as $item) {
                if ($item->id == $k) {
                    $ret_keys[] = $item;
                    break;
                }
            }
        }

        $returnData = [
            'user_info' => $user_info,
            'platform' => $platform['data'],
            'brands' => $brands,
            'keywords' => $ret_keys,
            'materiel' => $materiel,
            'id' => $id
        ];

        return view('admin.materiel.edit', $returnData)
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function delete (Request $request) {
        $id = $request->input('id');

        $materiel = DB::table('materiel')->where('id', $id)->first();
        if (!$materiel) {
            return $this->ajaxError('图文不存在');
        }
        if ($materiel->cms_id) {
            (new FindRepository())->delete($materiel->cms_id);
        }

        DB::table('materiel')->where('id', $id)->delete();

        return $this->ajaxMsg("删除成功", 1);
    }

    public function downloadHtml (Request $request, $id) {
        $materiel = DB::table('materiel')->where('id', $id)->first();
        $html = file_get_html($materiel->link);
        $str = $html->save();
        $str = str_replace('data-src', 'src', $str);

        $htmlDir = "/tmp/materiel_html/";
        if (!is_dir($htmlDir)) {
            mkdir($htmlDir, 0777, true);
        }
        $this->delDirFiles($htmlDir);
        file_put_contents($htmlDir . "materiel" . $id . ".html", $str);
        return response()->download($htmlDir . "materiel" . $id . ".html");
    }

    function delDirFiles($dirName) {
        if(file_exists($dirName) && $handle=opendir($dirName)){
            while(false!==($item = readdir($handle))){
                if($item!= "." && $item != ".."){
                    if(file_exists($dirName.'/'.$item) && is_dir($dirName.'/'.$item)){
                        $this->delDirFiles($dirName.'/'.$item);
                    }else{
                        if(unlink($dirName.'/'.$item)){
                            return true;
                        }
                    }
                }
            }
            closedir( $handle);
        }
    }
}