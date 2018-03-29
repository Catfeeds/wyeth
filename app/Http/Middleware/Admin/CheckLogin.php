<?php namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class CheckLogin
{
    public $request;

    public function handle($request, Closure $next)
    {
        $this->request = $request;
        $request->admin = new \stdClass();

        if (Session::has('admin_info') || $request->route()->getUri() == 'admin/login') {
            if (Session::has('admin_info')) {
                $check = $this->checkPrivilege();
                if (!$check) {
                    return view('admin.error', ['msg' => '无权限访问', 'url' => '/admin']);
                }
                $this->setMenu();
            }
            return $next($request);
        } else {
            return Redirect('/admin/login');
        }
    }

    public function setMenu()
    {
        // $this->load('uri');
        $route = Request::path();
        $route = rtrim(preg_replace('/\d*/', '', $route), '\/');

        $admin_info = Session::get('admin_info')->toArray();
        if ($admin_info['user_type'] == 0) {
            $menu_list = $this->getMenuData();
        } else if ($admin_info['user_type'] == 1) {
            $menu_list = $this->getSubAccountMenuData();
        } else if ($admin_info['user_type'] == 2) {
            $menu_list = $this->getTeacherAccountMenuData();
        } else if ($admin_info['user_type'] == 3) {
            $menu_list = $this->getContentAccountMenuData();
        } else if ($admin_info['user_type'] == 4) {
            $menu_list = $this->getMaterielManageMenuData();
        } else if ($admin_info['user_type'] == 5) {
            $menu_list = $this->getPrizeManageMenuData();
        }

        foreach ($menu_list as &$v) {
            if (isset($v['active']) && in_array($route, $v['active'])) {
                $v['active'] = 'active';
            } else {
                $v['active'] = '';
            }
            if (!empty($v['subMenu'])) {
                foreach ($v['subMenu'] as &$vv) {
                    if (isset($vv['active']) && in_array($route, $vv['active'])) {
                        $vv['active'] = 'active';
                        $v['active'] = 'active';
                    } else if ($this->request->segment(2, '') == 'app_config' && $this->request->segment(5) == 'subtable') {
                        // 子表配置
                        if (isset($vv['active']) && in_array('admin/app_config/index', $vv['active'])) {
                            $vv['active'] = 'active';
                            $v['active'] = 'active';
                        } else {
                            $vv['active'] = '';
                        }
                    } else {
                        $vv['active'] = '';
                    }
                }
            }
        }
        $this->request->admin->menu = $menu_list;

        Session::put('menu', $menu_list);
    }

    public function getMenuData()
    {
        return [
            [
                'name' => '面板',
                'class' => 'fa-dashboard',
                'href' => '/admin',
                'active' => ['admin', 'admin/index'],
            ],
            [
                'name' => '课程管理',
                'class' => 'fa-book',
                'subMenu' => [
                    [
                        'name' => '套课',
                        'href' => '/admin/course/cat',
                        'active' => ['admin/course/cat'],
                    ],
                    [
                        'name' => '添加课程',
                        'href' => '/admin/course/add',
                        'active' => ['admin/course/add'],
                    ],
                    [
                        'name' => '课程列表',
                        'href' => '/admin/course/index',
                        'active' => ['admin/course/index', 'admin/course', 'admin/course/edit', 'admin/course/notify_setting'],
                    ],
                    [
                        'name' => '课程定时推送',
                        'href' => '/admin/course_push/index',
                        'active' => ['admin/course_push/index', 'admin/course_push'],
                    ],
                    [
                        'name' => '新慧摇课程推送',
                        'href' => '/admin/huiyao_course_push',
                        'active' => ['admin/huiyao_course_push'],
                    ],
                    [
                        'name' => '课程申请列表',
                        'href' => '/admin/course/applyList',
                        'active' => ['admin/course/applyList'],
                    ],
                ],
            ],
//            [
//                'name' => '游戏管理',
//                'class' => 'fa-gamepad',
//                'subMenu' => [
//                    [
//                        'name' => '签到游戏',
//                        'href' => '/admin/signin/index',
//                        'active' => ['admin/signin/index','admin/course/signin', 'admin/signin/list'],
//                    ],
//                ],
//
//            ],
//            [
//                'name' => '回顾课程管理',
//                'class' => 'fa-book',
//                'subMenu' => [
//                    [
//                        'name' => '添加回顾课程',
//                        'href' => '/admin/course_review/add/0',
//                        'active' => ['admin/course_review/add'],
//                    ],
//                    [
//                        'name' => '回顾课程列表',
//                        'href' => '/admin/course_review/index',
//                        'active' => ['admin/course_review/index', 'admin/course_review', 'admin/course_review/edit'],
//                    ]
//                ],
//            ],
            [
                'name' => '标签管理',
                'class' => 'fa-tag',
                'subMenu' => [
                    [
                        'name' => '标签管理',
                        'href' => '/admin/tags',
                        'active' => ['admin/tags'],
                    ],
                    [
                        'name' => '显示标签管理',
                        'href' => '/admin/display_tags',
                        'active' => ['admin/display_tags'],
                    ],
                ]
            ],
//            [
//                'name' => '用户管理',
//                'class' => 'fa-user',
//                'subMenu' => [
//                    [
//                        'name' => '微信用户',
//                        'href' => '/admin/user/index',
//                        'active' => ['admin/user/index', 'admin/user', 'admin/user/edit'],
//                    ],
//                    [
//                        'name' => '用户群',
//                        'href' => '/admin/user/qrcode',
//                        'active' => ['admin/user/qrcode', 'admin/user/qrcode_add'],
//                    ],
//                    [
//                        'name' => '二维码',
//                        'href' => '/admin/user/user_in_qrcode',
//                        'active' => ['admin/user/user_in_qrcode', 'admin/user/user_in_qrcode_add'],
//                    ],
//                ],
//
//            ],
            // [
            //     'name' => '年会机器人开关',
            //     'class' => 'fa-user',
            //     'href' => '/admin/year/index',
            //     'active' => ['admin/year/index']
            // ],
            [
                'name' => '讲师管理',
                'class' => 'fa-user',
                'href' => '/admin/lecturer',
                'active' => ['admin/lecturer'],
            ],
            [
                'name' => '广告配置',
                'class' => 'fa-user',
                'href' => '/admin/advertise',
                'active' => ['admin/advertise'],
            ],
            [
                'name' => '课件管理',
                'class' => 'fa-book',
                'href' => '/admin/course_review/manage',
                'active' => ['admin/course_review/manage'],
            ],
//            [
//                'name' => '区域城市管理',
//                'class' => 'fa-map-marker',
//                'href' => '/admin/city',
//                'active' => ['admin/city/add', 'admin/city'],
//            ],
            [
                'name' => '平台物料管理',
                'class' => 'fa-tag',
                'subMenu' => [
                    [
                        'name' => '物料管理',
                        'class' => 'fa-book',
                        'href' => '/admin/materiel',
                        'active' => ['admin/materiel', 'admin/materiel/edit'],
                    ],
                    [
                        'name' => '平台管理',
                        'class' => 'fa-book',
                        'href' => '/admin/platform',
                        'active' => ['admin/platform'],
                    ],
                ]
            ],
            [
                'name' => '奖品管理',
                'class' => 'fa-book',
                'href' => '/admin/prize',
                'active' => ['admin/prize'],
            ],
            [
                'name' => '品牌管理',
                'class' => 'fa-book',
                'href' => '/admin/brand',
                'active' => ['admin/brand'],
            ],
            [
                'name' => '帐号管理',
                'class' => 'fa-user',
                'href' => '/admin/account',
                'active' => ['admin/account/add', 'admin/account'],
            ],
            [
                'name' => '订单管理',
                'class' => 'fa-user',
                'subMenu' => [
                    [
                        'name' => '订单管理',
                        'href' => '/admin/consume',
                        'active' => ['admin/consume']
                    ],
                    [
                        'name' => '流水管理',
                        'href' => '/admin/order',
                        'active' => ['admin/order']
                    ],
                ]
            ],
            [
                'name' => '参数配置',
                'class' => 'fa-user',
                'subMenu' => [
//                    [
//                        'name' => '首页',
//                        'href' => '/admin/app_config/index',
//                        'active' => ['admin/app_config/index'],
//                    ],
//                    [
//                        'name' => '新版首页',
//                        'href' => '/admin/app_config/ci_index',
//                        'active' => ['admin/app_config/ci_index'],
//                    ],
                    [
                        'name' => '首页',
                        'href' => '/admin/app_config/de_index',
                        'active' => ['admin/app_config/de_index'],
                    ],
                    [
                        'name' => '其他配置',
                        'href' => '/admin/app_config/other_index',
                        'active' => ['admin/app_config/other_index'],
                    ],
                    [
                        'name' => '小程序配置',
                        'href' => '/admin/app_config/program',
                        'active' => ['admin/app_config/program', 'admin/app_config/program/'],
                    ],
                    [
                        'name' => '活动配置',
                        'href' => '/admin/app_config/activity',
                        'active' => ['admin/app_config/activity']
                    ]
//                    [
//
//                        'name' => '支付宝服务窗',
//                        'href' => '/admin/app_config/alipay',
//                        'active' => ['admin/app_config/alipay'],
//                    ],
//                    [
//                        'name' => '回顾页面',
//                        'href' => '/admin/app_config/end',
//                        'active' => ['admin/app_config/end'],
//                    ],
//                    [
//                        'name' => '专栏活动配置',
//                        'href' => '/admin/app_config/activity_column',
//                        'active' => ['admin/app_config/activity_column'],
//                    ],
//                    [
//                        'name' => '模板消息自动推送',
//                        'href' => '/admin/app_config/auto_push',
//                        'active' => ['admin/app_config/auto_push'],
//                    ]
                ],
            ],
            [
                'name' => '数据统计',
                'class' => 'fa-user',
                'subMenu' => [
                    [
                        'name' => '周报',
                        'href' => '/admin/course_export_new',
                        'active' => ['admin/course_export_new'],
                    ],
                    [
                        'name' => '模板消息',
                        'href' => '/admin/query',
                        'active' => ['admin/query'],
                    ],
                ],
            ],
//            [
//                'name' => '可视化统计',
//                'class' => 'fa-bar-chart-o',
//                'subMenu' => [
//                    [
//                        'name' => '概况',
//                        'href' => '/admin/statistics',
//                        'active' => ['admin/statistics'],
//                    ],
//                    [
//                        'name' => '事件管理',
//                        'href' => '/admin/event',
//                        'active' => ['admin/event','admin/event/detail'],
//                    ],
//                    [
//                        'name' => '漏斗',
//                        'href' => '/admin/funnel',
//                        'active' => ['admin/funnel','admin/funnel/edit','admin/funnel/detail'],
//                    ],
//                ],
//            ],
        ];
    }

    public function getSubAccountMenuData()
    {
        return [
            [
                'name' => '面板',
                'class' => 'fa-dashboard',
                'href' => '/admin',
                'active' => ['admin', 'admin/index'],
            ],
            [
                'name' => '课程申请管理',
                'class' => 'fa-book',
                'subMenu' => [
                    [
                        'name' => '添加课程申请',
                        'href' => '/admin/course_apply/add',
                        'active' => ['admin/course_apply/add'],
                    ],
                    [
                        'name' => '课程申请列表',
                        'href' => '/admin/course_apply/index',
                        'active' => ['admin/course_apply/index', 'admin/course_apply', 'admin/course_apply/edit'],
                    ],
                ],
            ],
            [
                'name' => '课程列表',
                'class' => 'fa-book',
                'href' => '/admin/area_course',
                'active' => ['admin/area_course', 'admin/area_course/detail'],
            ],
        ];
    }

    public function getTeacherAccountMenuData(){
        return [
//            [
//                'name' => '面板',
//                'class' => 'fa-dashboard',
//                'href' => '/admin',
//                'active' => ['admin', 'admin/index'],
//            ],
            [
                'name' => '课件管理',
                'class' => 'fa-book',
                'href' => '/admin/course_review/manage',
                'active' => ['admin/course_review/manage'],
            ],
//            [
//                'name' => '课程定时推送',
//                'class' => 'fa-book',
//                'href' => '/admin/course_push/index',
//                'active' => ['admin/course_push/index', 'admin/course_push'],
//            ],
        ];
    }

    public function getContentAccountMenuData(){
        return [
            [
                'name' => '面板',
                'class' => 'fa-dashboard',
                'href' => '/admin',
                'active' => ['admin', 'admin/index'],
            ],
            [
                'name' => '课件管理',
                'class' => 'fa-book',
                'href' => '/admin/course_review/manage',
                'active' => ['admin/course_review/manage'],
            ],
            [
                'name' => '讲师管理',
                'class' => 'fa-user',
                'href' => '/admin/lecturer',
                'active' => ['admin/lecturer'],
            ],
        ];
    }

    public function getMaterielManageMenuData () {
        return [
            [
                'name' => '面板',
                'class' => 'fa-dashboard',
                'href' => '/admin',
                'active' => ['admin', 'admin/index'],
            ],
            [
                'name' => '大平台物料管理',
                'class' => 'fa-book',
                'href' => '/admin/materiel',
                'active' => ['admin/materiel'],
            ],
        ];
    }

    public function getPrizeManageMenuData () {
        return [
            [
                'name' => '奖品管理',
                'class' => 'fa-book',
                'href' => '/admin/prize',
                'active' => ['admin/prize'],
            ],
        ];
    }

    public function checkPrivilege()
    {
        $route = Request::path();
        $route = rtrim(preg_replace('/\d*/', '', $route), '\/');

        //子账号（运营人员）权限
        $privileges = ['admin', 'admin/index', 'admin/login', 'admin/logout',
            'admin/course_apply', 'admin/course_apply/index',
            'admin/course_apply/edit', 'admin/area_course',
            'admin/course_apply/add', 'admin/course_apply/delete',
            'admin/area_course/detail'];
        $teacherP = ['admin', 'admin/index', 'admin/login', 'admin/logout','admin/course_push/index', 'admin/course_push/detail',
            'admin/course_review/manage', 'admin/course_review/download', 'admin/course_review/download_html', 'admin/course/upload_att'];
        $contentP = ['admin', 'admin/index', 'admin/login', 'admin/logout',
            'admin/course_review/manage', 'admin/course_review/download', 'admin/course_review/download_html',
            'admin/lecturer', 'admin/lecturer/add', 'admin/lecturer/export', 'admin/course/upload_att',
            'admin/course_review/edit'];
        $materielP = ['admin', 'admin/index', 'admin/login', 'admin/logout', 'admin/materiel', 'admin/materiel/download_html', 'admin/materiel/edit', 'admin/materiel/delete', 'admin/keyword/search'];
        $prizeP = ['admin/login', 'admin/logout', 'admin/prize'];
        $admin_info = Session::get('admin_info')->toArray();
        if ($admin_info['user_type'] == 1) {
            if (!in_array($route, $privileges)) {
                return false;
            }
        } else if ($admin_info['user_type'] == 2) {
            if (!in_array($route, $teacherP)) {
                return false;
            }
        } else if ($admin_info['user_type'] == 3) {
            if (!in_array($route, $contentP)) {
                return false;
            }
        } else if ($admin_info['user_type'] == 4) {
            if (!in_array($route, $materielP)) {
                return false;
            }
        } else if ($admin_info['user_type'] == 5) {
            if (!in_array($route, $prizeP)) {
                return false;
            }
        }
        return true;
    }
}
