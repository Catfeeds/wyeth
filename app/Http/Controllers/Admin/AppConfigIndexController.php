<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use App\Services\Qnupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AppConfigIndexController extends Controller
{

    public function index(Request $request)
    {
        $user_info = Session::get('admin_info');
        $data = AppConfig::where('module', 'index')->get()->toArray();
        $setting = [];
        foreach ($data as $v) {
            $setting[$v['key']][] = $v;
        }
        if (!$setting) {
            return view('admin.module.config_index', ['fields' => $this->config, 'page'=>'index'])
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
        return view('admin.module.config_index', ['fields' => $this->config, 'page'=>'index'])
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
        return view('admin.error', ['msg' => '已更新', 'url' => "/admin/app_config/index"]);
    }

    public function subtable(Request $request, $field)
    {
        if (!$field) {
            return view('admin.error', ['msg' => '参数错误']);
        }
        $carousels = AppConfig::where(['module' => 'index', 'key' => $field])->get()->toArray();
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
        return view('admin.module.subtable_edit', ['fields' => $fields, 'id' => $id, 'field' => $field, 'domain' => $domain])
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
            $config->module = 'index';
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

        return view('admin.error', ['msg' => '已更新', 'url' => "/admin/app_config/index"]);
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

            return view('admin.error', ['msg' => '已删除', 'url' => "/admin/app_config/index"]);
        }
        return view('admin.error', ['msg' => '删除失败']);
    }

    protected $config = [
        'indexAlertPic' => [
            'input' => 'subtable',
            'label' => '首页弹窗广告',
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
                    'input' => 'link',
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
        'carousels1' => [
            'input' => 'subtable',
            'label' => '轮播图1',
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
                    'label' => '属性',
                    'desc' => '',
                    'default' => User::USERTYPE_NN,
                    'values' => [
                        User::USERTYPE_NN => '没有关注，没有hasShop',
                        User::USERTYPE_SN => '有关注，没有hasShop',
                        User::USERTYPE_NH => '没有关注，有hasShop',
                        User::USERTYPE_SH => '有关注，有hasShop',
                    ],
                ],
            ],
        ],
        'carousels2' => [
            'input' => 'subtable',
            'label' => '轮播图2',
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
                    'label' => '属性',
                    'desc' => '',
                    'default' => User::USERTYPE_NN,
                    'values' => [
                        User::USERTYPE_NN => '没有关注，没有hasShop',
                        User::USERTYPE_SN => '有关注，没有hasShop',
                        User::USERTYPE_NH => '没有关注，有hasShop',
                        User::USERTYPE_SH => '有关注，有hasShop',
                    ],
                ],
            ],
        ],

        // 热搜标签
        'tags' => [
            'input' => 'text',
            'label' => '热搜标签',
            'desc' => '',
            'default' => '',
        ],
        // 首页标签
        'index_tags' => [
            'input' => 'text',
            'label' => '首页标签',
            'desc' => '',
            'default' => ''
        ],
        'catCourses' => [
            'input' => 'subtable',
            'label' => '套课',
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
        'courses1' => [
            'input' => 'text',
            'label' => '推荐课程1',
            'desc' => '',
            'default' => '',
        ],
        'courses2' => [
            'input' => 'text',
            'label' => '推荐课程2',
            'desc' => '',
            'default' => '',
        ],

        //报名成功图
        'signSuccess' => [
            'input' => 'subtable',
            'label' => 'signSuccess',
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
                    'label' => '属性',
                    'desc' => '',
                    'default' => User::USERTYPE_NN,
                    'values' => [
                        User::USERTYPE_NN => '没有关注，没有hasShop',
                        User::USERTYPE_SN => '有关注，没有hasShop',
                        User::USERTYPE_NH => '没有关注，有hasShop',
                        User::USERTYPE_SH => '有关注，有hasShop',
                    ],
                ],
            ],
        ],

        //点击播放浮层
        'flatingLayer' => [
            'input' => 'subtable',
            'label' => '开始直播广告位',
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
                'type' => [
                    'input' => 'radio',
                    'label' => '广告类型',
                    'desc' => '',
                    'default' => '1',
                    'values' => [
                        '1' => '图片',
                        '2' => '视频'
                    ]
                ],
                'img' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => '',
                ],
                'cover' => [
                    'input' => 'image',
                    'label' => '视频封面',
                    'desc' => '',
                    'default' => '',
                    ],
                'video' => [
                    'input' => 'video',
                    'label' => '视频',
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
                    'label' => '属性',
                    'desc' => '',
                    'default' => User::USERTYPE_NN,
                    'values' => [
                        User::USERTYPE_NN => '没有关注，没有hasShop',
                        User::USERTYPE_SN => '有关注，没有hasShop',
                        User::USERTYPE_NH => '没有关注，有hasShop',
                        User::USERTYPE_SH => '有关注，有hasShop',
                    ],
                ],
            ],
        ],

        //课后评分显示图片
        'scorePicture' => [
            'input' => 'subtable',
            'label' => '课后评分广告位',
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
                    'label' => '属性',
                    'desc' => '',
                    'default' => User::USERTYPE_NN,
                    'values' => [
                        User::USERTYPE_NN => '没有关注，没有hasShop',
                        User::USERTYPE_SN => '有关注，没有hasShop',
                        User::USERTYPE_NH => '没有关注，有hasShop',
                        User::USERTYPE_SH => '有关注，有hasShop',
                    ],
                ],
            ],
        ],

        //母乳活动配置
        'breastMilk' => [
            'input' => 'subtable',
            'label' => '母乳活动',
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
                'thumb' => [
                    'input' => 'image',
                    'label' => '九宫格小图',
                    'desc' => '',
                    'default' => '',
                ],
                'img' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => '',
                ],
                'pic' => [
                    'input' => 'image',
                    'label' => '图片',
                    'desc' => '',
                    'default' => '',
                ],
                'content' => [
                    'input' => 'text',
                    'label' => '内容',
                    'desc' => '',
                    'default' => '',
                ],
                'displayorder' => [
                    'input' => 'text',
                    'label' => '序号',
                    'desc' => '',
                    'default' => '',
                ]
            ]
        ],

        // 最新课程
        'courseNew' => [
            'input' => 'text',
            'label' => '最新课程',
            'desc' => '',
            'default' => '',
        ],

        // 最热课程
        'courseHot' => [
            'input' => 'text',
            'label' => '最热课程',
            'desc' => '',
            'default' => '',
        ],
    ];
}
