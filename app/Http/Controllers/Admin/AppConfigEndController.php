<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use App\Models\Brand;
use App\Services\Qnupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AppConfigEndController extends Controller
{
    public function index(Request $request)
    {
        $user_info = Session::get('admin_info');
        $data = AppConfig::where('module', 'end')->get()->toArray();
        $setting = [];
        foreach ($data as $v) {
            $setting[$v['key']][] = $v;
        }
        if ($setting) {
            foreach ($this->config as $k => $v) {
                if (array_key_exists($k, $setting)) {
                    if ($v['input'] == 'subtable') {
                        foreach ($setting[$k] as $vv) {
                            $this->config[$k]['default'][$vv['id']] = $vv['data'];
                            if (array_key_exists('displayorder', $v['fields'])) {
                                $this->config[$k]['default'][$vv['id']]['displayorder'] = $vv['displayorder'];
                            }
                            if (array_key_exists('attr', $v['fields'])) {
                                $this->config[$k]['default'][$vv['id']]['attr'] = isset($vv['data']['attr']) ? $vv['data']['attr'] : $v['fields']['attr']['default'];
                            }
                        }
                    } else {
                        $this->config[$k]['default'] = $setting[$k][0]['data'];
                    }
                }
            }
        }
        return view('admin.module.config_end', ['fields' => $this->config, 'page'=>'end'])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => $request->admin->menu])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function store(Request $request)
    {
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
            $config = AppConfig::where(['module' => 'index', 'key' => $k])->first();
            if ($config) {
                $config->data = $data;
                $config->save();
            } else {
                $config = new AppConfig();
                $config->module = 'index';
                $config->key = $k;
                $config->data = $data;
                $config->save();
            }
            if ($k == 'tags' || $k == 'courses1' || $k == 'courses2') {
                AppConfig::$k(true);
            }
        }
        return view('admin.error', ['msg' => '已更新', 'url' => "/admin/app_config/end"]);
    }

    public function subtableUpdate(Request $request, $field)
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
            $config->module = 'end';
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
        if ($field == 'carousels1' || $field == 'carousels2') {
            $userType = $request->input('attr');
            AppConfig::carousels1($userType, true);
        }

        return view('admin.error', ['msg' => '已更新', 'url' => "/admin/app_config/end"]);
    }

    public function subtableEdit(Request $request, $field, $id)
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
                    $fields[$k]['default'] = $config->data[$k];
                }
            }
        }

        $user_info = Session::get('admin_info');
        return view('admin.module.subtable_end_edit', ['fields' => $fields, 'id' => $id, 'field' => $field])
            ->nest('header', 'admin.common.header', ['user_info' => $user_info])
            ->nest('sidebar', 'admin.common.sidebar', ['menu' => $request->admin->menu])
            ->nest('footer', 'admin.common.footer', []);
    }

    public function subtableDestroy(Request $request, $field, $id)
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

            return view('admin.error', ['msg' => '已删除', 'url' => "/admin/app_config/end"]);
        }
        return view('admin.error', ['msg' => '删除失败']);
    }

    protected $config = [
        'carousels_end1' => [
            'input' => 'subtable',
            'label' => '回顾页广告位1',
            'desc' => '',
            'default' => '',
            'fields' => [
                'subject' => [
                    'input' => 'text',
                    'label' => '标题',
                    'desc' => '',
                    'default' => '',
                ],
                'link' => [
                    'input' => 'text',
                    'label' => '链接',
                    'desc' => '',
                    'default' => '',
                ],
                'img' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => '',
                ],
                'displayorder' => [
                    'input' => 'text',
                    'label' => '序号',
                    'desc' => '',
                    'default' => '',
                ],
            ],
        ],
        'carousels_end2' => [
            'input' => 'subtable',
            'label' => '回顾页广告位2',
            'desc' => '',
            'default' => '',
            'fields' => [
                'subject' => [
                    'input' => 'text',
                    'label' => '标题',
                    'desc' => '',
                    'default' => '',
                ],
                'link' => [
                    'input' => 'text',
                    'label' => '链接',
                    'desc' => '',
                    'default' => '',
                ],
                'img' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => '',
                ],
                'displayorder' => [
                    'input' => 'text',
                    'label' => '序号',
                    'desc' => '',
                    'default' => '',
                ],
            ],
        ],
        'advertise_breast' => [
            'input' => 'subtable',
            'label' => '母乳活动广告',
            'desc' => '',
            'default' => '',
            'fields' => [
                'subject' => [
                    'input' => 'text',
                    'label' => '标题',
                    'desc' => '',
                    'default' => '',
                ],
                'link' => [
                    'input' => 'text',
                    'label' => '链接',
                    'desc' => '',
                    'default' => '',
                ],
                'img' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => '',
                ],
                'displayorder' => [
                    'input' => 'text',
                    'label' => '序号',
                    'desc' => '',
                    'default' => '',
                ],
            ],
        ],
        'ci_detail_advertise_1' => [
            'input' => 'subtable',
            'label' => '新版详情页广告1',
            'desc' => '',
            'default' => '',
            'fields' => [
                'subject' => [
                    'input' => 'text',
                    'label' => '标题',
                    'desc' => '',
                    'default' => '',
                ],
                'link' => [
                    'input' => 'text',
                    'label' => '链接',
                    'desc' => '',
                    'default' => '',
                ],
                'img' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => '',
                ],
                'displayorder' => [
                    'input' => 'text',
                    'label' => '序号',
                    'desc' => '',
                    'default' => '',
                ],
                'attr' => [
                    'input' => 'select',
                    'label' => '是否孕期',
                    'desc' => '',
                    'default' => 0,
                    'values' => Brand::brand_arr
                ]
            ],
        ],
        'ci_detail_advertise_2' => [
            'input' => 'subtable',
            'label' => '新版详情页广告2',
            'desc' => '',
            'default' => '',
            'fields' => [
                'subject' => [
                    'input' => 'text',
                    'label' => '标题',
                    'desc' => '',
                    'default' => '',
                ],
                'link' => [
                    'input' => 'text',
                    'label' => '链接',
                    'desc' => '',
                    'default' => '',
                ],
                'img' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => '',
                ],
                'displayorder' => [
                    'input' => 'text',
                    'label' => '序号',
                    'desc' => '',
                    'default' => '',
                ],
                'attr' => [
                    'input' => 'select',
                    'label' => '是否孕期',
                    'desc' => '',
                    'default' => 0,
                    'values' => Brand::brand_arr
                ]
            ],
        ],
    ];
}
