<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */
Route::group(['namespace' => 'Mobile', 'middleware' => ['oauth', 'share', 'resource.version', 'channel']], function () {
    Route::any('/', 'CourseController@index');
});

Route::any('welcome', function () {
    return view('mobile.welcome');
});

//微信公众号登录
Route::get('/wx_auth', 'WxAuthController@index');
//微信小程序登录
Route::any('/wx_auth/mini', 'WxAuthController@mini');

//token
Route::get('/token', 'TokenController@index');
// shorturl
Route::get('/url/{hash}', 'ShortUrlController@index');
//统计外链
Route::get('/link', 'ShortUrlController@link');
//设置params统计外链
Route::get('/params_link', 'ShortUrlController@params_link');
//生成短链接
Route::group(['prefix' => 'url', 'middleware' => 'cors'], function () {
    Route::post('get_short_link', 'ShortUrlController@get_short_link');
});

// 手机端
Route::group(['prefix' => 'mobile', 'namespace' => 'Mobile', 'middleware' => ['oauth', 'share', 'resource.version', 'channel']], function () {

    //课程首页
    Route::get('/', 'CourseController@index');
    Route::get('index', 'CourseController@index');
    Route::get('all', 'CourseController@all');
    Route::get('getMinePage', 'CourseController@getMinePage');
    Route::get('getDroploadData', 'CourseController@getDroploadData');
    Route::get('cat', 'CourseController@cat');
    Route::any('handle', 'CourseController@handle');
    Route::any('newClass', 'CourseController@jump');

    //最新课程
    Route::get('new', 'CourseController@new_course');

    //课程详情 报名
    Route::get('reg', 'CourseController@reg');
    Route::any('sign', 'CourseController@regOk');

    Route::get('attention', 'CourseController@attention');

    //非惠氏crm用户－报名页面
    Route::get('card', 'CourseController@card');

    //非惠氏crm用户－报名成功页面
    Route::get('sign_ok', 'CourseController@sign_ok');

    //惠氏crm用户－报名成功页面
    Route::get('course_ok', 'CourseController@course_ok');

    //课程结束页面
    Route::get('end', 'CourseController@end');
    Route::post('giveAReviewLike', 'CourseController@giveAReviewLike');
    Route::post('cancelAReviewLike', 'CourseController@cancelAReviewLike');
    Route::post('reviewLikesNum', 'CourseController@reviewLikesNum');
    Route::post('addCourseQuestion', 'CourseController@addCourseQuestion');
    Route::post('reviewRecord', 'CourseController@reviewRecord');
    Route::post('reviewTimeRecord', 'CourseController@reviewTimeRecord');

    //我的课程页面
    Route::get('mine', 'CourseController@mine');

    //课程中页面
    Route::get('living', 'UserLivingController@index');
    Route::post('getPlayStatus', 'UserLivingController@getPlayStatus');
    Route::get('qcloudTest', 'UserLivingController@qcloudTest');

    //保存用户网络状态
    Route::post('living/setNetWorkType', 'UserLivingController@setUserNetWorkType');

    // 送花
    Route::post('living/presentFlower', 'UserLivingController@presentFlower');
    Route::post('living/processEstimation', 'UserLivingController@processEstimation'); //直播后听课用户评论返回的数据

    //直播中页面
    Route::get('living2', 'UserLiving1Controller@index');

    Route::get('verify/teacher/{hash}', 'VerifyController@teacher');
    Route::get('verify/anchor/{hash}', 'VerifyController@anchor');
    //调查问卷
    Route::get('questionnaire', 'QuestionnaireController@index');
    Route::post('questionnaire/save', 'QuestionnaireController@save');
    //讲师直播
    Route::get('teacher/index', 'TeacherController@index');

    //分类页面
    Route::get('category', 'CategoryController@index');
    Route::post('category/search', 'CategoryController@search');

    //直播游戏页面game/signin/jump/{signin_id}
    Route::get('game/signin/jump/{id}', 'GameSigninController@jump'); //
    Route::get('game/signin/sign_page/{id}', 'GameSigninController@sign'); //签到页面
    Route::get('game/signin/insert', 'GameSigninController@sign_insert'); //签到页面
    Route::get('game/signin/success/{id}', 'GameSigninController@success'); //任务完成页
    Route::get('game/signin/fail/{id}', 'GameSigninController@fail'); //任务失败页
    Route::get('game/signin/user/{id}', 'GameSigninController@user_info'); //填写物流信息页
    Route::get('game/signin/user/submit/{id}', 'GameSigninController@user_submit'); //信息提交成功/失败页
    Route::get('game/signin/checkSign', 'GameSigninController@checkSign'); //判断用户是否创建过游戏 没有则创建
    Route::get('game/signin/getSignFriend', 'GameSigninController@get_the_most_sign_by_id_ajax'); //判获取当前游戏签到好友
    Route::get('game/signin/getSignOrder', 'GameSigninController@get_sign_order_by_id_ajax'); //获取排名
    Route::get('game/signin/getSign', 'GameSigninController@get_sign_ajax'); //获取排名
    Route::get('game/signin/sendCode', 'GameSigninController@sendCode'); //发送验证码 并存到cookie中
    Route::get('game/signin/userInsert', 'GameSigninController@user_info_insert'); //提交用户验证信息

    //搜索
    Route::get('search', 'CourseController@search'); //搜索结果页
    Route::post('search/update', 'CourseController@updateSearchInfo'); //搜索结果页

    //新版微信会员卡注册crm回调
    Route::get('crmCallback', 'CourseController@crmCallback');

    //跳转crm注册并回跳
    Route::get('registerCrm', 'CourseController@registerCrm');

    //活动
    Route::get('hd', 'HdController@index');
    Route::get('hd/login', 'HdController@login');
    Route::get('hd/qrcode', 'HdController@getQrCode');
    Route::any('hd/addShareLog', 'HdController@addShareLog');
    Route::get('breastActivity', 'HdController@breastActivity');
    Route::get('activityDetail', 'HdController@activityDetail');
    Route::get('columnActivity', 'HdController@columnActivity');
    Route::get('S26Card', 'HdController@S26Card');
    Route::get('goodMorning', 'HdController@goodMorning');
    Route::get('springSecret', 'HdController@springSecret');
    Route::get('springSecretCount', 'HdController@springSecretNum'); // 获取活动参加人数

    //重定向到转转乐地址，用于区分不同品牌的人跳转
    Route::get('hd/draw', 'HdController@draw');

    //用户绑定
    Route::get('userBind', 'UserBindController@index');


    //测试
    Route::get('test', 'TestController@test');
    Route::get('test/addChance', 'TestController@addChance');
    Route::get('action/{action}', 'TestController@action');
    //清除Cache
    Route::get('clearCache', 'TestController@clearCache');

    Route::get('hd/address', 'UserAddressController@index');
    Route::post('hd/save', 'UserAddressController@save');
});



// API接口
Route::group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => 'cors'], function () {

    Route::get('/', function () {
        header("HTTP/1.1 404 Not Found");
        exit;
    });

    //用户
    Route::get('user/course', 'UserController@course'); //用户课程表
    Route::post('user/friend', 'UserController@friend'); //用户好友
    Route::get('user/sign', 'UserController@signCourse'); //惠氏系统使用，用户批量报名课程
    Route::get('user/hounian', 'UserController@signHounian'); //惠氏系统使用，查询用户是否报名了三堂课程

    //课程
    Route::get('course', 'CourseController@index'); // 我的 列表
    Route::get('course/list', 'CourseController@getList'); // 全部 列表

    Route::get('course/crmSign', 'CourseController@crmSign'); //课程详情
    Route::post('course/sign', 'CourseController@sign'); //报名
    Route::get('course/code', 'CourseController@sendCode'); //发送验证码
    Route::post('course/log', 'CourseController@writeListenLog'); //发送验证码
    Route::post('course/share', 'CourseController@share'); //记录用户分享成功行为
    Route::get('course/ad_list', 'CourseController@adList'); //惠氏推广报名活动页面api

    //给慧摇的接口
    Route::get('course/getByCid', 'CourseController@getByCid'); //根据cid查课程
    Route::get('course/getListByEdc', 'CourseController@getListByEdc'); //根据edc查精品课程3条
    Route::post('course/xxjp', 'CourseController@xxjp'); //记录自动下行的推送数

    //给中台活动的接口
    Route::any('hd/qrcodeCreate', 'CIHdController@qrcodeCreate');
    Route::any('hd/listenCourse', 'CIHdController@listenCourse');
    Route::post('hd/pushCustomMessage', 'CIHdController@pushCustomMessage');
    Route::any('hd/sendNoticeTpl', 'CIHdController@sendNoticeTpl'); //发送操作提醒模板消息

    Route::any('hd/isCrmMember', 'CIHdController@isCrmMember');

    //根据场景值获取下行图文信息
    Route::any('hd/getMaterialByScene', 'CIHdController@getMaterialByScene');

    //孕期提醒发模板消息
    Route::any('pregnotice/sendTpl', 'CIHdController@pregnoticeSendTpl');

    Route::get('wechat/package', 'WechatController@getSignPackage'); //获取微信package

});

// 管理界面
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['admin.checkLogin', 'resource.version']], function () {

    //基础
    Route::get('/', 'IndexController@index');
    Route::get('/index', 'IndexController@index');
    Route::any('/login', 'IndexController@login');
    Route::get('/logout', 'IndexController@logout');
    Route::get('/anchorLogout', 'IndexController@anchorLogout');
    Route::get('/teacherLogout', 'IndexController@teacherLogout');
    Route::get('/welcome', 'IndexController@welcome');

    //课程
    Route::get('course/addUidToRC', 'CourseController@addUidToRC'); //更新recommend_course的uid
    Route::get('course', 'CourseController@index');
    Route::get('course/index', 'CourseController@index');
    Route::get('course/index/{page}', 'CourseController@index');
    Route::get('course/detail/{id}', 'CourseController@detail');
    Route::get('course/add', 'CourseController@edit');
    Route::get('course/edit/{id}', 'CourseController@edit');
    Route::post('course/store', 'CourseController@store');
//    Route::post('course/setTags', 'CourseController@setTags');  // 设置内容标签
//    Route::post('course/setTeachers', 'CourseController@setTeachers');  // 设置讲师标签
//    Route::post('course/setAgeTags', 'CourseController@setAgeTags');  // 设置月龄tag
//    Route::post('course/setDisplayTags', 'CourseController@setDisplayTags');  // 设置月龄tag
    Route::any('course/delete/{id}', 'CourseController@delete');
    Route::any('course/delete_all', 'CourseController@delete_all');
    Route::any('course/view_qrcode/{id}', 'CourseController@view_qrcode');
    Route::any('course/qr/{id}', 'CourseController@qr');
    Route::any('course/save_upload/{id}', 'CourseController@save_upload');
    Route::any('course/upload_att/{id}', 'CourseController@upload_att');
    Route::any('course/unbindt/{id}', 'CourseController@unbindt');
    Route::any('course/unbinda/{id}', 'CourseController@unbinda');
    Route::any('course/del_img/{id}', 'CourseController@del_img');
    Route::any('course/group/{id}', 'CourseController@group');
    Route::any('course/statistics/{id}', 'CourseController@statistics'); // 课程在线人数图表
    Route::any('course/notify_setting/{id}', 'CourseController@notify_setting'); // 设置开课通知
    Route::post('course/preview_tplmsg', 'CourseController@preview_tplmsg'); // 预览开课通知
    Route::post('course/tplmsg_save', 'CourseController@tplmsg_save'); // 保存模板消息设置
    Route::post('course/send_tplmsg', 'CourseController@send_tplmsg');
    Route::get('course/applyList', 'CourseController@applyList');
    Route::get('course/applyList/{page}', 'CourseController@applyList');
    Route::get('course/applyVerify', 'CourseController@applyVerify');
    Route::get('course/cat', 'CourseController@cat'); //套课|课程分类 3月22日 gaofei
    Route::get('course/cat_edit/{id}', 'CourseController@catEdit'); //套课 | 编辑
    Route::get('course/cat_add', 'CourseController@catAdd'); //套课 | 添加
    Route::post('course/cat_save', 'CourseController@catSave'); //套课 | 添加
    Route::post('course/cat_del', 'CourseController@catDel'); //套课 | 删除
    Route::post('course/sendSQTemplateMessage', 'CourseController@sendSQTemplateMessage'); //套课|课程分类 3月25日 gaofei
    Route::get('course/signin/{type}/{id}', 'GameSigninController@config'); //游戏编辑页面
    Route::get('signin/switch', 'GameSigninController@signSwitch');  //游戏开关
    Route::post('signin/config/save', 'GameSigninController@saveSignConfig'); //保存游戏配置信息
    Route::post('signin/config/create', 'GameSigninController@insertSignConfig'); //保存游戏配置信息
    Route::get('signin/victory/info', 'GameSigninController@victoryInfo'); //获取获奖用户信息
    Route::get('signin/index', 'GameSigninController@index'); //后台游戏首页
    Route::get('course/signin/{id}', 'GameSigninController@config'); //后台游戏edit
    Route::get('signin/list/{id}', 'GameSigninController@signList'); //游戏参与列表
    Route::get('signin/list/print/{cid}', 'GameSigninController@printSignList'); //打印某一游戏签到名单

    // TAG
    Route::controller('tag', 'TagController');
    Route::any('tags', 'TagController@index');
    Route::any('tags/add', 'TagController@add');
    Route::any('tags/edit', 'TagController@edit');
    Route::any('tags/delete', 'TagController@delete');
    Route::any('tags/exportCT', 'TagController@exportCourseTags');

    //显示标签相关
    Route::any('display_tags/search', 'DisplayTagsController@getSearch');
    Route::any('display_tags', 'DisplayTagsController@index');
    Route::any('display_tags/edit', 'DisplayTagsController@edit');
    Route::any('display_tags/delete', 'DisplayTagsController@delete');

    // lecturer 讲师管理
    Route::controller('lecturers', 'LecturerController');
    Route::any('lecturer', 'LecturerController@index');
    Route::any('lecturer/add/{id}', 'LecturerController@add');
    Route::get('lecturer/export','LecturerController@export');

    // 广告管理
    Route::any('advertise', 'AdvertiseController@index');
    Route::any('advertise/edit', 'AdvertiseController@edit');
    Route::any('advertise/delete', 'AdvertiseController@delete');

    // CMS管理
    Route::any('cms', 'CMSController@index');

    // 订单管理
    Route::any('consume', 'ConsumeController@index');
    Route::get('order', 'OrderController@index');

    //推送课程
    Route::get('course_push', 'CoursePushController@index');
    Route::get('course_push/index', 'CoursePushController@index');
    Route::post('course_push/add', 'CoursePushController@add');
    Route::post('course_push/edit', 'CoursePushController@edit');
    Route::post('course_push/delete', 'CoursePushController@delete');
    Route::post('course_push/detail','CoursePushController@detail');
    Route::get('huiyao_course_push', 'HuiYaoCoursePushController@index');  // 新慧摇推送
    Route::post('huiyao_course_push/detail', 'HuiYaoCoursePushController@detail');

    //项目推送
    Route::get('tpl_project', 'TplProjectController@index');
    Route::get('tpl_project/index', 'TplProjectController@index');
    Route::post('tpl_project/store', 'TplProjectController@store');
    Route::post('tpl_project/push', 'TplProjectController@tpl_project_push');
    Route::post('tpl_project/preview', 'TplProjectController@preview');


    //课程回顾
    Route::get('course_review', 'CourseReviewController@index');
    Route::get('course_review/index', 'CourseReviewController@index');
    Route::get('course_review/index/{page}', 'CourseReviewController@index');
    Route::any('course_review/add/{id}', 'CourseReviewController@add');
    Route::any('course_review/getuptoken', 'CourseReviewController@getUpToken');
    Route::any('course_review/edit/{id}', 'CourseReviewController@edit');
    Route::get('course_review/manage', 'CourseReviewController@manage');    // 课件管理
    Route::get('course_review/download/{id}', 'CourseReviewController@downloadZipByImageUrl');
    Route::get('course_review/download_html/{id}', 'CourseReviewController@downloadHtml');
    Route::any('course_review/delete/{id}', 'CourseReviewController@delete');
    Route::any('course_review/delete_all', 'CourseReviewController@delete_all');
    Route::post('course_review/questions', 'CourseReviewController@questions');
    Route::post('course_review/image_upload', 'CourseReviewController@imageUpload');
    Route::any('course_review/ueditor_image_upload', 'CourseReviewController@ueditorImageUpload');

    //课程数据
    Route::get('course_export', 'CourseExportController@index');
    Route::get('course_export/index', 'CourseExportController@index');
    Route::get('course_export/export', 'CourseExportController@export');
    Route::get('course_export_new','DataExportController@index');  //包含周报，月报，年报的数据
    Route::get('course_export_new/index','DataExportController@index');
    Route::post('course_export_new/generate','DataExportController@generate');
    Route::get('course_export_new/export','DataExportController@export');
    Route::get('course_details','CourseDetailsController@index');
    Route::post('course_details/update','CourseDetailsController@update');
    Route::get('course_data_active','IndexController@index');
    Route::get('course_data_active/search','IndexController@search');


    //微信用户
    Route::get('user', 'UserController@index');
    Route::get('user/index', 'UserController@index');
    Route::any('user/edit/{id}', 'UserController@edit');
    Route::any('user/delete/{id}', 'UserController@delete');
    Route::any('user/delete_all', 'UserController@delete_all');
    Route::get('year/index', 'YearController@index');
    Route::post('year/send', 'YearController@send');
    Route::get('user/qrcode', 'UserController@qrcode');
    Route::any('user/qrcode_add/{id}', 'UserController@qrcode_add');
    Route::get('user/qrcode_delete/{id}', 'UserController@qrcode_delete');
    Route::get('user/user_in_qrcode', 'UserController@user_in_qrcode');
    Route::get('user/userInQrcodeAdd', 'UserController@userInQrcodeAdd');
    Route::get('user/userInQrcodeEdit/{id}', 'UserController@userInQrcodeEdit');
    Route::get('user/userInQrcodeDelete/{id}', 'UserController@userInQrcodeDelete');
    Route::post('user/userInQrcodeSave', 'UserController@userInQrcodeSave');

    //区域城市
    Route::any('city', 'CityController@index');
    Route::any('city/add/{id}', 'CityController@add');
    Route::any('city/delete/{id}', 'CityController@delete');

    //课程申请
    Route::get('course_apply', 'CourseApplyController@index');
    Route::get('course_apply/index', 'CourseApplyController@index');
    Route::get('course_apply/index/{page}', 'CourseApplyController@index');
    Route::any('course_apply/add', 'CourseApplyController@add');
    Route::any('course_apply/edit/{id}', 'CourseApplyController@edit');
    Route::any('course_apply/delete/{id}', 'CourseApplyController@delete');
    Route::get('area_course', 'CourseController@area_course');
    Route::get('area_course/detail/{id}', 'CourseController@area_course_detail');

    //大平台物料管理
    Route::get('materiel', 'MaterielController@index');
    Route::get('materiel/download_html/{id}', 'MaterielController@downloadHtml');
    Route::any('materiel/edit/{id}', 'MaterielController@edit');
    Route::any('materiel/delete', 'MaterielController@delete');
    Route::get('platform', 'PlatformController@index');
    Route::any('platform/edit', 'PlatformController@edit');
    Route::any('platform/delete', 'PlatformController@delete');

    // 品牌管理
    Route::get('brand', 'BrandController@index');
    Route::any('brand/edit', 'BrandController@edit');

    // 奖品管理
    Route::get('prize', 'PrizeController@index');
    Route::any('prize/edit', 'PrizeController@edit');
    Route::any('prize/delete', 'PrizeController@delete');
    Route::any('prize/change', 'PrizeController@change');
    Route::any('prize/notice_edit', 'PrizeController@notice_edit');

    // 数据统计
    Route::get('statistics', "StatisticsController@index");

    // 事件管理
    Route::get('event', "EventController@index");
    Route::any('event/delete', "EventController@delete"); // 删除事件
    Route::any('event/search', "EventController@search");  //  搜索事件
    Route::get('event/get_list', "EventController@getEventList");  // 获取事件列表
    Route::get('event/detail/{id}', 'EventController@detail');
    Route::get('event/get_detail/{id}', 'EventController@getDetail'); // 获取某一事件的详情数据，返回data


    // 漏斗
    Route::get('funnel', "FunnelController@index");
    Route::post('funnel/add', 'FunnelController@add'); // 新增漏斗
    Route::post('funnel/update', 'FunnelController@update'); // 更新漏斗
    Route::get('funnel/edit/{id}','FunnelController@edit');
    Route::get('funnel/detail/{id}','FunnelController@detail'); // 漏斗详情，不返回界面
    Route::get('funnel/conversion/{id}','FunnelController@conversion');// 漏斗详情，返回界面，并且带有数据
    Route::post('funnel/delete', 'FunnelController@delete'); // 删除漏斗

    //关键词
    Route::controller('keyword', 'KeywordController');

    //帐号管理
    Route::any('account', 'AccountController@index');
    Route::any('account/add/{id}', 'AccountController@add');
    Route::any('account/delete/{id}', 'AccountController@delete');

    // 参数配置
    Route::resource('app_config/index', 'AppConfigIndexController');
    Route::resource('app_config/de_index', 'AppConfigDeIndexController');  // 无主首页配置
    Route::resource('app_config/other_index', 'AppConfigOtherIndexController');  // 其他配置
    Route::resource('app_config/activity', 'AppConfigActivityController');    // 活动配置
    Route::resource('app_config/alipay', 'AppConfigAlipayController');
    Route::resource('app_config/end', 'AppConfigEndController');
    Route::resource('app_config/program', 'AppConfigProgramController');
    // 参数配置 子表
    // list
    Route::get('app_config/index/{field}/subtable', 'AppConfigIndexController@subtable');
    // edit form
    Route::get('app_config/index/{field}/subtable/{id}/edit', 'AppConfigIndexController@subtable_edit');
    Route::get('app_config/index/{field}/subtable/{id}/delete', 'AppConfigIndexController@subtable_destroy');
    Route::get('app_config/alipay/{field}/subtable/{id}/edit', 'AppConfigAlipayController@subtableEdit');
    Route::get('app_config/alipay/{field}/subtable/{id}/delete', 'AppConfigAlipayController@subtableDestroy');
    Route::get('app_config/end/{field}/subtable/{id}/edit', 'AppConfigEndController@subtableEdit');
    Route::get('app_config/end/{field}/subtable/{id}/delete', 'AppConfigEndController@subtableDestroy');
    Route::get('app_config/de_index/{field}/subtable/{id}/edit', 'AppConfigDeIndexController@subtable_edit');
    Route::get('app_config/de_index/{field}/subtable/{id}/delete', 'AppConfigDeIndexController@subtable_destroy');
    Route::get('app_config/program/{field}/subtable/{id}/edit', 'AppConfigProgramController@subtable_edit');
    Route::get('app_config/program/{field}/subtable/{id}/delete', 'AppConfigProgramController@subtable_destroy');
    Route::get('app_config/activity/{field}/subtable/{id}/edit', 'AppConfigActivityController@subtable_edit');
    Route::get('app_config/activity/{field}/subtable/{id}/delete', 'AppConfigActivityController@subtable_destroy');
    Route::get('app_config/other_index/{field}/subtable/{id}/edit', 'AppConfigOtherIndexController@subtable_edit');
    Route::get('app_config/other_index/{field}/subtable/{id}/delete', 'AppConfigOtherIndexController@subtable_destroy');
    // store form save
    Route::post('app_config/index/{field}/subtable', 'AppConfigIndexController@subtable_update');
    Route::post('app_config/alipay/{field}/subtable', 'AppConfigAlipayController@subtableUpdate');
    Route::post('app_config/end/{field}/subtable', 'AppConfigEndController@subtableUpdate');
    Route::post('app_config/de_index/{field}/subtable', 'AppConfigDeIndexController@subtable_update');
    Route::post('app_config/program/{field}/subtable', 'AppConfigProgramController@subtable_update');
    Route::post('app_config/activity/{field}/subtable', 'AppConfigActivityController@subtable_update');
    Route::post('app_config/other_index/{field}/subtable', 'AppConfigOtherIndexController@subtable_update');
    // destroy
    Route::delete('app_config/index/{field}/subtable', 'AppConfigIndexController@subtable_destroy');
    Route::post('app_config/index/upload', 'AppConfigIndexController@upload');

    Route::resource('app_config/activity_column', 'ActivityColumnController@index');
    Route::post('app_config/activity_column/store', 'ActivityColumnController@store');

    // 侧边栏配置
    Route::any('app_config/siderbar', 'SiderbarController@index');

    //模板消息自动推送配置
    Route::get('app_config/auto_push', 'AutoPushController@index');
    Route::post('auto_push/save', 'AutoPushController@save');
    Route::post('auto_push/preview_tplmsg', 'AutoPushController@preview_tplmsg');
    

    //添加音频内容
    Route::get('course/indexTest', 'CourseController@indexTest');
    Route::post('course/addAudioDetail', 'CourseController@addAudioDetail');

    Route::get('query', 'QueryController@index');
    Route::get('query/', 'QueryController@index');
    Route::post('query/tplmsg2','QueryController@tplmsg2');
    Route::get('query/export', 'QueryController@export');
    Route::get('query/tplmsg', 'QueryController@tplmsg');
    Route::get('query/tplmsgCourse', 'QueryController@tplmsgCourse');
    Route::get('query/listenOpenid', 'QueryController@listenOpenid');
});


//为第三方提供的接口API
Route::group(['prefix' => 'api/service', 'namespace' => 'Api\Service', 'middleware' => ['cors']], function () {
    Route::get('user/sign', 'UserController@signCourse');
    Route::get('course/search', 'CourseController@search');
    Route::get('user/signMultCourse', 'UserController@signMultCourse');
});



// 微信或者QQ用户授权后，转向处理
Route::group(['prefix' => 'auth', 'namespace' => 'Auth', 'middleware' => []], function () {
    Route::get('qq', 'QQAuthController@process');
});

Route::group(['prefix' => 'wyeth', 'namespace' => 'Wyeth', 'middleware' => ['cors', 'jwt.user_token', 'channel']], function(){
    //获取首页数据
    Route::get('page/getHomePageData', 'PageController@getHomePageData');
    Route::get('page/getMiniHomePageData', 'PageController@getMiniHomePageData');
    //获取全部页面的列表数据
    Route::get('page/getAllPageData', 'PageController@getAllPageData');
    //获取我的页面数据
    Route::get('page/getMyPageData', 'PageController@getMyPageData');

    //课程相关
    Route::get('course/getHotCourse', 'CourseController@getHotCourse');
    Route::get('course/getRecomCourse', 'CourseController@getCourseRecommend');
    Route::get('course/getNewCourse', 'CourseController@getNewCourse');
    Route::get('course/getDetail', 'CourseController@getDetail');
    Route::get('course/signCourse', 'CourseController@signCourse');
    Route::get('course/signCat', 'CourseController@signCat');
    Route::get('course/like', 'CourseController@like');
    Route::get('course/getLikeNum', 'CourseController@getLikeNum');
    Route::get('course/save', 'CourseController@save');
    Route::get('course/getSaveNum', 'CourseController@getSaveNum');
    Route::get('course/likeCat', 'CourseController@likeCat');
    Route::get('course/getCatLikeNum', 'CourseController@getCatLikeNum');
    Route::get('course/saveCat', 'CourseController@saveCat');
    Route::get('course/getCatSaveNum', 'CourseController@getCatSaveNum');
    Route::get('course/likeOrSaveCourse', 'CourseController@likeOrSaveCourse');
    Route::get('course/getProCourse', 'CourseController@getProCourse');
    Route::get('course/getNoTagCourse', 'CourseController@getNoTagCourse');
    Route::any('course/courseListenAdd', 'CourseController@courseListenAdd');
    Route::get('course/shareCourse', 'CourseController@shareCourse');
    Route::get('course/cat', 'CourseController@cat');
    Route::get('course/getDetailCatCourse', 'CourseController@getDetailCatCourse');

    //标签相关
    Route::get('tag/getHomeTag', 'TagController@getHomeTag');
    Route::get('tag/getUserTag', 'UserTagController@getUserTag');
    Route::post('tag/increaseTag', 'UserTagController@increaseTag');
    Route::post('tag/decreaseTag', 'UserTagController@decreaseTag');
    Route::get('tag/getChooseTag', 'UserTagController@getChooseTag');
    Route::post('tag/chooseTag', 'UserTagController@chooseTag');
    Route::get('tag/getConcernTag', 'UserTagController@getConcernTag');
    Route::get('tag/getAllHotTag', 'TagController@getAllHotTag');
    Route::get('tag/getAllTag', 'TagController@getAllTag');

    //获取广告
    Route::get('ad/getAd', 'AdvertiseController@getAd');

    //用户相关
    Route::any('user/getLoginInfo', 'UserController@getLoginInfo');
    Route::get('user/getUserInfo', 'UserController@getUserInfo');
    Route::get('user/sign', 'UserController@sign');
    Route::get('user/getTraceCourse', 'UserCourseTraceController@getTraceCourse');
    Route::get('user/getTraceCourseByDate', 'UserCourseTraceController@getTraceCourseByDate');
    Route::get('user/setTraceCourse', 'UserCourseTraceController@setTraceCourse');
    Route::get('user/getUserDynamic', 'UserCourseController@getUserDynamic');
    Route::post('user/setPregdate', 'UserController@setPregdate');

    //积分模块
    Route::get('MQ/getConsumeList', 'UserMQController@getConsumeList');
    Route::get('mq/compensate', 'UserMQController@compensate');

    //讲师相关
    Route::get('pro/observePro', 'TeacherController@observePro');
    Route::get('pro/getProInfo', 'TeacherController@getProInfo');
    Route::get('pro/getTeacherCourse', 'TeacherController@getTeacherCourse');

    //图文相关
    Route::get('cms/main.php/json/article/getNewestArticle/{appkey}/{channel}/{page}/{limit}/{substrcount}', 'FindController@getNewestArticle');
    Route::get('article/getNewestArticle', 'FindController@getArticle');
    Route::get('article/getArticleDetail', 'FindController@getArticleDetail');
    Route::get('article/like', 'FindController@like');
    Route::get('article/comment', 'FindController@comment');
    Route::get('article/save', 'FindController@save');
    Route::get('article/share', 'FindController@share');
    Route::get('article/getSaveArticles', 'FindController@getSaveArticles');

    Route::get('article/getDynamicAndArticles', 'FindController@getDynamicAndArticles');

    //惠妈300问相关
    Route::get('question/getTagQuestion', 'TagQuestionController@getTagQuestion');

    //播放列表相关
    Route::get('play/getPlayList', 'PlayListController@getPlayList');

    //搜索相关
    Route::get('search/getSearchResult', 'SearchController@getSearchResult');
    Route::get('search/getSearchTag', 'SearchController@getSearchTag');
    Route::get('search/getCourseSearch', 'SearchController@getCourseSearch');
    Route::get('search/getQuestionSearch', 'SearchController@getQuestionSearch');

    //任务相关
    Route::get('task/getMq', 'TaskController@getMq');
    Route::get('task/getTask', 'TaskController@getTask');

    // MQ购买相关
    Route::post('userBuy/course', 'UserBuyController@buyCourse');
    Route::post('userBuy/paySuccess', 'UserBuyController@paySuccess');
    Route::get('userBuy/getTradeInfo', 'UserBuyController@getTradeInfo');
    Route::get('userBuy/getBoughtCat', 'UserBuyController@getBoughtCat');
    Route::get('userBuy/getBoughtCourse', 'UserBuyController@getBoughtCourse');

    //购买mq订单相关
    Route::post('order/createOrder', 'OrderController@createOrder');
    Route::post('order/queryOrder', 'OrderController@queryOrder');
    Route::post('order/createMiniOrder', 'OrderController@createMiniOrder');

    //活动相关
    Route::post('hd/addChance', 'HdController@addChance');
    Route::any('hd/getChance', 'HdController@getChance');
    Route::get('hd/getActivity', 'HdController@getActivity');

    //获取闪屏
    Route::get('flashPic/get', 'FlashPicController@get');

    //记录客户端错误日志
    Route::post('errorLog/add', 'ErrorLogController@add');
    Route::get('errorLog/getList', 'ErrorLogController@getList');

    //魔栗365新接口
    Route::get('moli/getMoLiData', 'MoLiDataController@getMoLiData');

    //微信接口相关
    Route::post('weixin/getWxCardMemberInfoByTicket', 'WeixinController@getWxCardMemberInfoByTicket');
    Route::post('weixin/getWxCardMemberInfo', 'WeixinController@getWxCardMemberInfo');
    Route::post('weixin/registerCrm', 'WeixinController@registerCrm');

    //测试
    Route::get('test', 'TestController@test');
    //记录首页加载时间
    Route::get('loadHome', 'CourseController@loadHome');
});

//不要登录验证的接口
Route::group(['prefix' => 'wyeth', 'namespace' => 'Wyeth', 'middleware' => ['cors']], function(){

    //支付
    Route::any('pay/payNotify', 'PayController@payNotify');
    Route::any('pay/refundNotify', 'PayController@refundNotify');
});

