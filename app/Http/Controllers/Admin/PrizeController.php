<?php
/**
 * Created by PhpStorm.
 * User: Zzhk
 * Date: 2017/10/31
 * Time: 16:06
 */

namespace App\Http\Controllers\Admin;

use App\Services\Qnupload;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PrizeController extends BaseController {

    protected $timeout = 2;
    protected $dev_domain = 'http://idg-zhuyixuan.tunnel.nibaguai.com';
    protected $pro_domain = 'http://oneitfarm.com';

    public function index (Request $request) {
        $data = $request->all();

        $domain = env('APP_ENV', 'local') == 'production' ? $this->pro_domain : $this->dev_domain;
        $act_id = env('APP_ENV', 'local') == 'production' ? $request->input('aid', 406) : 795;

        $q_params = [];
        $q_params['title'] = $request->input('title');
        $q_params['status'] = $request->input('status');
        $q_params['type'] = $request->input('type');
        $q_params['aid'] = $request->input('aid', 406);

        $page = array_key_exists('page', $data) ? $data['page'] : 1;
        $user_info = Session::get('admin_info');

        $params = [
            'act_id' => $act_id,
            'page' => 1,
            'limit' => 1000
        ];
        $url = '/hd/main.php/hsback/getPrizeList.json';

        $ret = $this->request($domain, $url, $params);

        if ($ret->getStatusCode() != 200) {
            return view('admin.error', ['msg' => '请求中台失败，回到主页', 'url' => '/admin/index']);
        }

        $result = json_decode($ret->getBody(), true);

        $params_n = [
            'act_id' => $act_id,
            'is_text' => 1
        ];
        $url_n = '/hd/main.php/hsback/getNoticeText.json';
        $ret_n = $this->request($domain, $url_n, $params_n);
        if ($ret->getStatusCode() != 200) {
            return view('admin.error', ['msg' => '请求中台失败，回到主页', 'url' => '/admin/index']);
        }
        $result_n = json_decode($ret_n->getBody(), true);
        $content = '';
        if ($result_n['ret'] == 1) {
            $content = $result_n['text'];
        }

        if ($result['ret'] == 1) {
            $list = $result['list'];

            $id = [];
            foreach ($list as $index => $item) {
                if (array_key_exists('id', $item)) {
                    $id[$index] = $item['id'];
                } else {
                    $id[$index] = 0;
                }
            }
            array_multisort($id, SORT_DESC, $list);

            $ret_list_1 = [];
            if ($q_params['status']) {
                foreach ($list as $index => $item) {
                    if ($q_params['status'] == 1 && array_key_exists('id', $item)) {
                        $ret_list_1[] = $item;
                    } else if ($q_params['status'] == 2 && array_key_exists('send_num', $item) && !array_key_exists('id', $item)) {
                        $ret_list_1[] = $item;
                    } else if ($q_params['status'] == 3 && !array_key_exists('id', $item)) {
                        $ret_list_1[] = $item;
                    }
                }
            } else {
                $ret_list_1 = $list;
            }

            $ret_list_2 = [];
            if ($q_params['title']) {
                foreach ($ret_list_1 as $item) {
                    if ($item['title'] == $q_params['title']) {
                        $ret_list_2[] = $item;
                    }
                }
            } else {
                $ret_list_2 = $ret_list_1;
            }

            $ret_list_3 = [];
            if ($q_params['type']) {
                foreach ($ret_list_2 as $item) {
                    if ($item['type'] == $q_params['type']) {
                        $ret_list_3[] = $item;
                    }
                }
            } else {
                $ret_list_3 = $ret_list_2;
            }


            $ret_list = array_slice($ret_list_3, 10 * ($page - 1), 10);

            return view('admin.prize.index', ['list' => $ret_list, 'total' => count($ret_list_3), 'page' => $page, 'total_list' => $ret_list_3, 'q_params' => $q_params, 'content' => $content])
                ->nest('header', 'admin.common.header', ['user_info' => $user_info])
                ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
                ->nest('footer', 'admin.common.footer', []);
        } else {
            return view('admin.error', ['msg' => '请求中台失败，回到主页', 'url' => '/admin/index?aid=' . $act_id]);
        }
    }

    public function edit(Request $request) {
        $data = $request->all();

        $domain = env('APP_ENV', 'local') == 'production' ? $this->pro_domain : $this->dev_domain;
        $act_id = env('APP_ENV', 'local') == 'production' ? $request->input('aid', 406) : 795;

        $id = $data['id'];

        if (!empty($_FILES['pic']) && $_FILES['pic']['size'] > 0) {
            $pic = Qnupload::upload($_FILES['pic']);
        } else {
            $pic = $data['imgHidden'];
        }
        if ($id) {
            $params = [
                'id' => $data['id'],
                'aid' => $act_id,
                'item_id' => $data['item_id'],
                'left_num' => $data['left_num'] == $data['before_left'] ? 'not_change' : $data['left_num'],
                'odds' => intval(floatval($data['odds']) * 100),
                'pic' => $pic,
                'title' => $data['title'],
                'starttime' => strtotime($data['starttime']),
                'endtime' => strtotime($data['endtime']),
                'type' => $data['type'],
            ];
            if ($data['type'] == 'object') {
                $params['need_remark'] = array_key_exists('need_remark', $data) ? 1 : 0;
            } else {
                $params['jump_url'] = $data['jump_url'];
            }
            $url = '/hd/main.php/hsback/editOnlinePrize.json';
        } else {
            $form_params = [];
            $form_params['title'] = $data['title'];
            $form_params['type'] = $data['type'];
            $form_params['starttime'] = strtotime($data['starttime']);
            $form_params['endtime'] = strtotime($data['endtime']);
            if ($pic) {
                $form_params['pic'] = $pic;
            }
            if ($data['type'] == 'object') {
                $form_params['need_remark'] =  array_key_exists('need_remark', $data) ? 1 : 0;
            } else {
                $form_params['jump_url'] = $data['jump_url'];
            }

            $params = [
                'aid' => $act_id,
                'item_id' => $data['item_id'] ? $data['item_id'] : 'new_item',
                'data' => $form_params
            ];
            $url = '/hd/main.php/hsback/editItem.json';
        }

        $ret = $this->request($domain, $url, $params);

        if ($ret->getStatusCode() != 200) {
            return view('admin.error', ['msg' => '请求中台失败，回到列表', 'url' => '/admin/prize']);
        }

        $result = json_decode($ret->getBody(), true);

        if ($result['ret'] == 1) {
            return view('admin.error', ['msg' => '请求成功，回到列表', 'url' => '/admin/prize']);
        } else {
            return view('admin.error', ['msg' => '请求中台失败，回到列表', 'url' => '/admin/prize']);
        }
    }

    public function delete (Request $request) {
        $data = $request->all();

        $domain = env('APP_ENV', 'local') == 'production' ? $this->pro_domain : $this->dev_domain;
        $act_id = env('APP_ENV', 'local') == 'production' ? $request->input('aid', 406) : 795;

        $params = [
            'act_id' => $act_id,
            'item_id' => $data['item_id']
        ];

        $url = '/hd/main.php/hsback/deleteItem.json';

        $ret = $this->request($domain, $url, $params);

        if ($ret->getStatusCode() != 200) {
            return $this->ajaxError("删除失败", 0);
        }

        $result = json_decode($ret->getBody(), true);

        if ($result['ret'] == 1) {
            return $this->ajaxMsg("删除成功", 1);
        } else {
            return $this->ajaxError("删除失败", 0);
        }
    }

    public function change (Request $request) {
        $data = $request->all();

        $domain = env('APP_ENV', 'local') == 'production' ? $this->pro_domain : $this->dev_domain;
        $act_id = env('APP_ENV', 'local') == 'production' ? $request->input('aid', 406) : 795;

        $params = [
            'act_id' => $act_id,
            'id' => $data['id'],
            'item_id' => $data['item_id'],
            'left_num' => $data['left_num'],
            'odds' => intval(floatval($data['odds']) * 100)
        ];
        $url ='/hd/main.php/hsback/changePrize.json';

        $ret = $this->request($domain, $url, $params);

        if ($ret->getStatusCode() != 200) {
            return view('admin.error', ['msg' => '请求中台失败，回到列表', 'url' => '/admin/prize']);
        }

        $result = json_decode($ret->getBody(), true);

        if ($result['ret'] == 1) {
            return view('admin.error', ['msg' => '请求成功，回到列表', 'url' => '/admin/prize']);
        } else {
            return view('admin.error', ['msg' => '请求中台失败，回到列表', 'url' => '/admin/prize']);
        }
    }

    public function notice_edit (Request $request) {
        $data = $request->all();

        $domain = env('APP_ENV', 'local') == 'production' ? $this->pro_domain : $this->dev_domain;
        $act_id = env('APP_ENV', 'local') == 'production' ? $request->input('aid', 406) : 795;

        $params = [
            'act_id' => $act_id,
            'is_text' => 1,
            'content' => $data['content']
        ];

        $url = '/hd/main.php/hsback/setNoticeText.json';

        $ret = $this->request($domain, $url, $params);

        if ($ret->getStatusCode() != 200) {
            return view('admin.error', ['msg' => '请求中台失败，回到列表', 'url' => '/admin/prize']);
        }

        $result = json_decode($ret->getBody(), true);

        if ($result['ret'] == 1) {
            return view('admin.error', ['msg' => '请求成功，回到列表', 'url' => '/admin/prize']);
        } else {
            return view('admin.error', ['msg' => '请求中台失败，回到列表', 'url' => '/admin/prize']);
        }
    }

    private function request($domain, $url, $params) {
        $client = new Client([
                'base_uri' => $domain,
                'timeout' => $this->timeout,
            ]
        );

        $ret = $client->request('POST', $url, [
            'form_params' => $params
        ]);

        return $ret;
    }
}