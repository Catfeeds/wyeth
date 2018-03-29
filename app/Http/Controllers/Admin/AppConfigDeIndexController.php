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

class AppConfigDeIndexController extends Controller {

    public function index (Request $request ) {

        $user_info = Session::get('admin_info');
        $data = AppConfig::where('module', 'de_index')->get()->toArray();
        $setting = [];
        foreach ($data as $v) {
            $setting[$v['key']][] = $v;
        }
        if (!$setting) {
            return view('admin.module.config_de_index', ['fields' => $this->config, 'page'=>'de_index'])
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
        return view('admin.module.config_de_index', ['fields' => $this->config, 'page'=>'de_index'])
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
            $config = AppConfig::where(['module' => 'de_index', 'key' => $k])->first();
            if ($config) {
                $config->data = $data;
                $config->save();
            } else {
                $config = new AppConfig();
                $config->module = 'de_index';
                $config->key = $k;
                $config->data = $data;
                $config->save();
            }
//            if ($k == 'tags' || $k == 'courses1' || $k == 'courses2') {
//                AppConfig::$k(true);
//            }
        }
        return view('admin.error', ['msg' => '已更新', 'url' => "/admin/app_config/de_index"]);
    }

    public function subtable(Request $request, $field)
    {
        if (!$field) {
            return view('admin.error', ['msg' => '参数错误']);
        }
        $carousels = AppConfig::where(['module' => 'de_index', 'key' => $field])->get()->toArray();
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
        return view('admin.module.subtable_de_edit', ['module' => 'de_index', 'fields' => $fields, 'id' => $id, 'field' => $field, 'domain' => $domain])
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
            $config->module = 'de_index';
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

        return view('admin.error', ['msg' => '已更新', 'url' => "/admin/app_config/de_index"]);
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

            return view('admin.error', ['msg' => '已删除', 'url' => "/admin/app_config/de_index"]);
        }
        return view('admin.error', ['msg' => '删除失败']);
    }

    protected $config = [
//        'carousels_temp' => [
//            'input' => 'subtable',
//            'label' => '首页顶部轮播图',
//            'desc' => '',
//            'default' => '',
//            'fields' => [
//                'subject' => [
//                    'input' => 'text',
//                    'label' => '标题',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'link' => [
//                    'input' => 'text',
//                    'label' => '链接',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'img' => [
//                    'input' => 'image',
//                    'label' => '图片',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'displayorder' => [
//                    'input' => 'text',
//                    'label' => '序号',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'attr' => [
//                    'input' => 'select',
//                    'label' => '是否孕期',
//                    'desc' => '',
//                    'default' => 0,
//                    'values' => Brand::brand_arr
//                ]
//                'attr' => [
//                    'input' => 'select',
//                    'label' => '属性',
//                    'desc' => '',
//                    'default' => User::USERTYPE_NN,
//                    'values' => [
//                        User::USERTYPE_NN => '没有关注，没有hasShop',
//                        User::USERTYPE_SN => '有关注，没有hasShop',
//                        User::USERTYPE_NH => '没有关注，有hasShop',
//                        User::USERTYPE_SH => '有关注，有hasShop',
//                    ],
//                ],
//            ],
//        ],
//        'ci_carousels1' => [
//            'input' => 'subtable',
//            'label' => '轮播图1',
//            'desc' => '',
//            'default' => '',
//            'fields' => [
//                'subject' => [
//                    'input' => 'text',
//                    'label' => '标题',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'link' => [
//                    'input' => 'text',
//                    'label' => '链接',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'img' => [
//                    'input' => 'image',
//                    'label' => '图片',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'displayorder' => [
//                    'input' => 'text',
//                    'label' => '序号',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'brand' => [
//                    'input' => 'select',
//                    'label' => '有主无主',
//                    'desc' => '',
//                    'default' => 0,
//                    'values' => Brand::brand_arr
//                ]
////                'attr' => [
////                    'input' => 'select',
////                    'label' => '属性',
////                    'desc' => '',
////                    'default' => User::USERTYPE_NN,
////                    'values' => Brand::brand_arr,
////                ],
//            ],
//        ],
//        'ci_carousels2' => [
//            'input' => 'subtable',
//            'label' => '首页底部通栏',
//            'desc' => '',
//            'default' => '',
//            'fields' => [
//                'subject' => [
//                    'input' => 'text',
//                    'label' => '标题',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'link' => [
//                    'input' => 'text',
//                    'label' => '链接',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'img' => [
//                    'input' => 'image',
//                    'label' => '图片',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'displayorder' => [
//                    'input' => 'text',
//                    'label' => '序号',
//                    'desc' => '',
//                    'default' => '',
//                ],
//                'attr' => [
//                    'input' => 'select',
//                    'label' => '是否孕期',
//                    'desc' => '',
//                    'default' => 0,
//                    'values' => Brand::brand_arr
//                ]
//                'attr' => [
//                    'input' => 'select',
//                    'label' => '属性',
//                    'desc' => '',
//                    'default' => User::USERTYPE_NN,
//                    'values' => [
//                        User::USERTYPE_NN => '没有关注，没有hasShop',
//                        User::USERTYPE_SN => '有关注，没有hasShop',
//                        User::USERTYPE_NH => '没有关注，有hasShop',
//                        User::USERTYPE_SH => '有关注，有hasShop',
//                    ],
//                ],
//            ],
//        ],

        // 设置兴趣tag
        'ci_focus_tags' => [
            'input' => 'text',
            'label' => '设置兴趣',
            'desc' => '',
            'default' => ''
        ],

        // 热搜标签
        'ci_hot_tags' => [
            'input' => 'text',
            'label' => '热搜标签',
            'desc' => '',
            'default' => ''
        ],

        // 首页标签
        'ci_index_tags_def' => [
            'input' => 'subtable',
            'label' => '首页标签',
            'desc' => '',
            'default' => '',
            'fields' => [
                'tags_arr' => [
                    'input' => 'text',
                    'label' => '标签组',
                    'desc' => '',
                    'default' => '',
                ],
                'attr' => [
                    'input' => 'select',
                    'label' => '品牌',
                    'desc' => '',
                    'default' => 0,
                    'values' => Brand::brand_arr
                ]
            ]
        ],

        // 首页套课及活动
        'ci_cat_activity' => [
            'input' => 'subtable',
            'label' => '套课及活动',
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
                'header' => [
                    'input' => 'image',
                    'label' => '标题图',
                    'desc' => '',
                    'default' => '',
                ],
                'img' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => '',
                ],
                'type' => [
                    'input' => 'select',
                    'label' => '类型',
                    'desc' => '',
                    'default' => 0,
                    'values' => [ 0 => '套课', 1 => '活动']
                ],
                'displayorder' => [
                    'input' => 'text',
                    'label' => '序号',
                    'desc' => '',
                    'default' => '',
                ],
                'attr' => [
                    'input' => 'select',
                    'label' => '品牌',
                    'desc' => '',
                    'default' => 0,
                    'values' => Brand::brand_arr
                ]
            ]
        ],

        // 推荐课程
        'ci_recommend_def' => [
            'input' => 'subtable',
            'label' => '推荐课程',
            'desc' => '',
            'default' => '',
            'fields' => [
                'courses_arr' => [
                    'input' => 'text',
                    'label' => '课程组',
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
            ]
        ],

        // 最新课程
        'ci_new_course' => [
            'input' => 'text',
            'label' => '最新课程',
            'desc' => '',
            'default' => '',
        ],

        // 最热课程
        'ci_hot_course' => [
            'input' => 'text',
            'label' => '最热课程',
            'desc' => '',
            'default' => '',
        ],

        // 最新课程
        'ci_jinzhuang_new_course' => [
            'input' => 'text',
            'label' => '金装最新课程',
            'desc' => '',
            'default' => '',
        ],

        // 最热课程
        'ci_jinzhuang_hot_course' => [
            'input' => 'text',
            'label' => '金装最热课程',
            'desc' => '',
            'default' => '',
        ],

        // 最新课程
        'ci_qifu_new_course' => [
            'input' => 'text',
            'label' => '启赋最新课程',
            'desc' => '',
            'default' => '',
        ],

        // 最热课程
        'ci_qifu_hot_course' => [
            'input' => 'text',
            'label' => '启赋最热课程',
            'desc' => '',
            'default' => '',
        ],

        // 全部页面最热标签
        'ci_all_hot_tags' => [
            'input' => 'text',
            'label' => '全部页热门标签',
            'desc' => '',
            'default' => '',
        ],

        // 动态官方名称及图片配置
        'ci_dynamic' => [
            'input' => 'subtable',
            'label' => '官方名称及图片配置',
            'desc' => '',
            'default' => '',
            'fields' => [
                'name' => [
                    'input' => 'text',
                    'label' => '名称',
                    'desc' => '',
                    'default' => ''
                ],
                'avatar' => [
                    'input' => 'image',
                    'label' => '头像',
                    'desc' => '',
                    'default' => ''
                ]
            ]
        ],

        // 闪屏配置
        'ci_flash_pic' => [
            'input' => 'subtable',
            'label' => '闪屏配置',
            'desc' => '',
            'default' => '',
            'fields' => [
                'name' => [
                    'input' => 'text',
                    'label' => '名称',
                    'desc' => '',
                    'default' => '',
                ],
                'img' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => ''
                ],
                'link' => [
                    'input' => 'text',
                    'label' => '链接',
                    'desc' => '',
                    'default' => ''
                ],
                'displaystatus' => [
                    'input' => 'select',
                    'label' => '状态',
                    'desc' => '',
                    'default' => 1,
                    'values' => [
                        0 => '无效',
                        1 => '有效'
                    ]
                ],
                'displayorder' => [
                    'input' => 'text',
                    'label' => '序号',
                    'desc' => '',
                    'default' => '',
                ],
            ]
        ],

        // 闪屏配置
        'ci_course_flash_pic' => [
            'input' => 'subtable',
            'label' => '课页闪屏配置',
            'desc' => '',
            'default' => '',
            'fields' => [
                'name' => [
                    'input' => 'text',
                    'label' => '名称',
                    'desc' => '',
                    'default' => '',
                ],
                'img' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => ''
                ],
                'link' => [
                    'input' =>'text',
                    'label' => '链接',
                    'desc' => '',
                    'default' => ''
                ],
                'displaystatus' => [
                    'input' => 'select',
                    'label' => '状态',
                    'desc' => '',
                    'default' => 1,
                    'values' => [
                        0 => '无效',
                        1 => '有效'
                    ]
                ],
                'displayorder' => [
                    'input' => 'text',
                    'label' => '序号',
                    'desc' => '',
                    'default' => '',
                ],
            ]
        ],

        // 广告版本选择
        'ci_advertise_version' => [
            'input' => 'select',
            'label' => '广告版本选择',
            'desc' => '',
            'default' => '',
            'values' => [ 0 => 'A版', 1 => 'B版']
        ],
    ];
}