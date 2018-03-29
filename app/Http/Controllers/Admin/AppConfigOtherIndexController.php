<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use App\Models\Brand;
use App\Models\CiAppConfig;
use App\Services\Qnupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AppConfigOtherIndexController extends Controller {

    protected $module = AppConfig::MODULE_OTHER_INDEX;

    public function index (Request $request ) {

        $user_info = Session::get('admin_info');
        $data = AppConfig::where('module', $this->module)->get()->toArray();
        $setting = [];
        foreach ($data as $v) {
            $setting[$v['key']][] = $v;
        }
        if (!$setting) {
            return view('admin.module.config_other_index', ['fields' => $this->config, 'page'=>$this->module])
                ->nest('header', 'admin.common.header', ['user_info' => $user_info])
                ->nest('sidebar', 'admin.common.sidebar', ['menu' => $request->admin->menu])
                ->nest('footer', 'admin.common.footer', []);
        }

        foreach ($this->config as $k => $v) {
            if (!array_key_exists($k, $setting)) {
                continue;
            }
            if ($v['input'] != 'subtable') {
                $this->config[$k]['default'] = $setting[$k][0]['data'];
                continue;
            }
            foreach ($setting[$k] as $vv) {
                foreach ($this->config[$k]['fields'] as $kkk => $vvv) {
                    if (array_key_exists($kkk, $vv['data'])) {
                        continue;
                    }
                    $vv['data'][$kkk] = $this->config[$k]['fields'][$kkk]['default'];
                }
                $this->config[$k]['default'][$vv['id']] = $vv['data'];
                if (array_key_exists('displayorder', $v['fields'])) {
                    $this->config[$k]['default'][$vv['id']]['displayorder'] = $vv['displayorder'];
                }
                if (array_key_exists('attr', $v['fields'])) {
                    $this->config[$k]['default'][$vv['id']]['attr'] = isset($vv['data']['attr']) ? $vv['data']['attr'] : $v['fields']['attr']['default'];
                }
            }
        }
        return view("admin.module.config_{$this->module}", ['fields' => $this->config, 'page'=>$this->module])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => $request->admin->menu])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function store (Request $request) {
        foreach ($this->config as $k => $v) {
            $data = $request->input($k);
            if ($v['input'] == 'subtable') {
                continue;
            } else if ($v['input'] == 'image') {
                if ($_FILES[$k]['size'] > 0) {
                    $data = Qnupload::upload($_FILES[$k], '', 'app/config');
                } else {
                    $data = $request->input($k, '');
                }
            }

            //当更新或添加时同时更新缓存，1，标签，2，3推荐课程
            $config = AppConfig::where(['module' => $this->module, 'key' => $k])->first();
            if ($config) {
                $config->data = $data;
                $config->save();
            } else {
                $config = new AppConfig();
                $config->module = $this->module;
                $config->key = $k;
                $config->data = $data;
                $config->save();
            }
//            if ($k == 'tags' || $k == 'courses1' || $k == 'courses2') {
//                AppConfig::$k(true);
//            }
        }
        return view('admin.error', ['msg' => '已更新', 'url' => "/admin/app_config/{$this->module}"]);
    }

    public function subtable(Request $request, $field)
    {
        if (!$field) {
            return view('admin.error', ['msg' => '参数错误']);
        }
        $carousels = AppConfig::where(['module' => $this->module, 'key' => $field])->get()->toArray();
        $user_info = Session::get('admin_info');
        return view('admin.module.subtable', ['carousels' => $carousels])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => Session::get('menu')])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function subtable_edit(Request $request, $field, $id)
    {
        if (!$field) {
            return view('admin.error', ['msg' => '参数错误']);
        }
        if (!array_key_exists($field, $this->config) || !array_key_exists('fields', $this->config[$field])) {
            return view('admin.error', ['msg' => '参数错误']);
        }
        $fields = $this->config[$field]['fields'];

        if ($id) {
            $config = AppConfig::where('id', $id)->first();
            foreach ($fields as $k => $v) {
                if ($k == 'displayorder') {
                    $fields[$k]['default'] = $config->displayorder;
                } elseif ($k == 'attr') {
                    $fields[$k]['default'] = isset($config->data[$k]) ? $config->data[$k] : 'normal';
                } else {
                    if (array_key_exists($k, $config->data)) {
                        $fields[$k]['default'] = $config->data[$k];
                    }
                }
            }
        }
        $user_info = Session::get('admin_info');
        $domain = config('qiniu.domain');
        return view('admin.module.subtable_other_edit', ['fields' => $fields, 'id' => $id, 'field' => $field, 'domain' => $domain])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => $request->admin->menu])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function subtable_update(Request $request, $field)
    {
        if (!$field) {
            return view('admin.error', ['msg' => '参数错误']);
        }
        if (!array_key_exists($field, $this->config) || !array_key_exists('fields', $this->config[$field])) {
            return view('admin.error', ['msg' => '参数错误']);
        }
        $id = $request->input('id', 0);
        $fields = $this->config[$field]['fields'];
        if ($id) {
            $config = AppConfig::where('id', $id)->first();
            if ($config->key != $field) {
                return view('admin.error', ['msg' => '参数错误']);
            }
        } else {
            $config = new AppConfig();
            $config->module = $this->module;
            $config->key = $field;
        }

        $data = [];
        foreach ($fields as $k => $v) {
            if ($k == 'displayorder') {
                $config->displayorder = $request->input($k);
            } else {
                if ($v['input'] == 'image') {
                    if ($_FILES[$k]['size'] > 0) {
                        $img = Qnupload::upload($_FILES[$k], '', 'app/config');
                    } else {
                        $img = $request->input($k, '');
                    }
                    $data[$k] = $img;
                } else {
                    $data[$k] = $request->input($k);
                }
            }
        }
        $config->data = $data;
        $config->save();

        //添加完后更新缓存
//        if ($field == 'carousels1' || $field == 'carousels2') {
//            $userType = $request->input('attr');
//            AppConfig::carousels1($userType, true);
//        }

        return view('admin.error', ['msg' => '已更新', 'url' => "/admin/app_config/{$this->module}"]);
    }

    public function subtable_destroy(Request $request, $field, $id)
    {
        if (!$field || !$id) {
            return view('admin.error', ['msg' => '参数错误']);
        }
        $config = AppConfig::where('id', $id)->first();
        if ($config) {
            $config->delete();

            //update cache carousels2
            if ($field == 'carousels1' || $field == 'carousels2') {
                $userType = $config['data']['attr'];
                AppConfig::carousels1($userType);
            }

            return view('admin.error', ['msg' => '已删除', 'url' => "/admin/app_config/{$this->module}"]);
        }
        return view('admin.error', ['msg' => '删除失败']);
    }

    protected $config = [

        AppConfig::KEY_OTHER_TITLE => [
            'input' => 'text',
            'label' => 'h5标题',
            'desc' => '',
            'default' => '',
        ],
        AppConfig::KEY_OTHER_SEARCH_PLACEHOLDER => [
            'input' => 'text',
            'label' => '搜索框默认值',
            'desc' => '多个的话以空格分隔,随机取一个',
            'default' => '',
        ],
        AppConfig::KEY_OTHER_COPYRIGHT => [
            'input' => 'textarea',
            'label' => '版权信息',
            'desc' => '',
            'default' => '',
        ],
        AppConfig::KEY_OTHER_DRAW_BG => [
            'input' => 'image',
            'label' => '抽奖活动背景图片',
            'desc' => '',
            'default' => '',
        ],
        AppConfig::KEY_OTHER_CRM_TIP => [
            'input' => 'text',
            'label' => 'crm未注册提示',
            'desc' => '',
            'default' => '',
        ],
        AppConfig::KEY_OTHER_CRM_REGISTER => [
            'input' => 'link',
            'label' => 'crm注册跳转',
            'desc' => '',
            'default' => '',
        ],
        AppConfig::KEY_OTHER_FULI_TEMPLATE => [
            'input' => 'subtable',
            'label' => '福利模板消息',
            'desc' => '',
            'default' => '',
            'fields' => [
                'content' => [
                    'input' => 'textarea',
                    'label' => '内容',
                    'desc' => '',
                    'default' => ''
                ],
                'item' => [
                    'input' => 'text',
                    'label' => '项目',
                    'desc' => '',
                    'default' => ''
                ],
                'remark' => [
                    'input' => 'text',
                    'label' => '备注',
                    'desc' => '',
                    'default' => ''
                ],
                'time' => [
                    'input' => 'select',
                    'label' => '时间',
                    'desc' => '',
                    'default' => '',
                    'values' => [
                        1 => '周一',
                        3 => '周三',
                        5 => '周五'
                    ]
                ]
            ]
        ]
    ];
}