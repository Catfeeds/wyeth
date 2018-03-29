<?php

namespace App\Console\Commands;

use App\Helpers\WyethUtil;
use App\Jobs\CreateTemplateMessageByOpenid;
use App\Jobs\CreateTplmsgFromAutoPushFuli;
use App\Jobs\CreateTplmsgFromAutoPushTjhg;
use App\Jobs\SendTemplateMessageByOpenid;
use App\Models\AppConfig;
use App\Models\Brand;
use App\Models\Course;
use App\Models\CoursePush;
use App\Models\User;
use App\Services\CourseService;
use App\Services\Crm;
use App\Services\Email;
use App\Services\WxWyeth;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Mail;

class AutoPushCommand extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:push {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '模板消息自动推送';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');

        $this->info('start');
        switch ($action) {
            case 'fuli':
                $this->fuli();
                break;
            case 'tjhg':
                $this->tjhg();
                break;
            case 'lose':
                $this->lose();
                break;
            case 'newUser':
                $this->newUser();
                break;
            case 'pushNotWeiketang':
                $this->pushNotWeiketang();
                break;
            case 'check':
                $this->check();
                break;
            case 'speed':
                $this->speed();
                break;
            case 'sendByOpenid':
                $this->sendByOpenid();
                break;
            case 'special':
                $this->special();
                break;
            case 'allUser':
                $this->allUser();
                break;
            default :
                $this->warn('action 不合法');
        }
        $this->info('end');
    }

    //每五分钟检查推送情况，若无推送则发邮件
    private function check()
    {
        $time = time();
        $time_str = date('Y-m-d H:i:s',time() - 5*60); // 五分钟内
        $start = strtotime(date('Y-m-d',$time)) + 8*3600 + 1800; // 早上八点半
        $end = $start + 15*3600 - 1800; //晚上十一点
        if($time >= $start && $time <= $end){
            $res = DB::table('tplmsgs')->where('created_at','>',$time_str)->count();
            if($res <=0){
                //发邮件
                $content = "在".$time_str."五分钟内没有推送数据";
                if (!config('app.debug')){
                    Email::SendEmail('推送挂了',$content,Email::EMAIL_BOX_COURSE_PUSH);
                }
            }
        }
    }
    //听课后，次日早上8点，推送1个福利模板消息，链接到俱乐部微商城（C菜单惠员购）
    private function fuli()
    {
        //昨天听课的人
        $users = $this->listenCourseUser();

        //获取今天转转乐的文案 AppConfig::where('module', $module)->where('key', $key)
        $weekday = date('w');
        $data = [];
        $config = AppConfig::where('module', AppConfig::MODULE_OTHER_INDEX)->where('key', AppConfig::KEY_OTHER_FULI_TEMPLATE)->get();
        foreach ($config as $item) {
            $data = $item->data;
            if ($data['time'] == $weekday) {
                break;
            }
        }

        foreach ($users as $u){
            $this->dispatch(new CreateTplmsgFromAutoPushFuli([$u], false, $data));
        }

        $count = count($users);

        $this->addToCoursePush(CoursePush::TYPE_FULI_DRAW, $count);
        $this->addToCoursePush(CoursePush::TYPE_FULI_DTC, $count);

        $this->log('推送福利模板' . $count . '人');

    }

    //听课后，次日晚9点，推送1个相同月龄+兴趣标签的课程模板消息 14
    private function tjhg()
    {
        //昨天听课的人
        $users = $this->listenCourseUser();

        $this->addToCoursePush(CoursePush::TYPE_TJHG, count($users));

        $this->log('自动推送:推送推荐课程');
        foreach ($users as $u) {
            //根据用户查推荐课的cid
            $cid = CourseService::recommendCourseByUser($u['uid']);
            if ($cid) {
                $this->log("{$u['uid']} $cid");
                $this->dispatch(new CreateTplmsgFromAutoPushTjhg($u['uid'], $u['openid'], $cid));
            }else{
                $this->log(__FUNCTION__."没有匹配到课程uid {$u['uid']}");
            }
        }

    }

    /**
     * 昨天听课的人，改成周一三五给前两天上课的人推
     * @param int -几天
     * @return array
     */
    private function listenCourseUser($day = -2)
    {
        $user = DB::connection('mysql_read')->table('user_events')
            ->select(DB::raw('DISTINCT uid'))
            ->where('created_at', '>', date('Y-m-d', strtotime("$day day")))
            ->where('created_at', '<', date('Y-m-d'))
            ->where('type', 'review_in')
            ->get();
        $result = [];
        foreach ($user as $item) {
            $uid = $item->uid;
            $res = DB::connection('mysql_read')->table('user')
                ->select('openid', 'nickname', 'crm_hasShop')
                ->where('id', $uid)
                ->first();
            if ($res && $res->openid){
                $u['uid'] = $uid;
                $u['openid'] = $res->openid;
                $u['nickname'] = $res->nickname;
                $result[] = $u;
            }
        }
        return $result;
    }

    //前60天到前30天上过课，但是近30天没有上过课的用户进行推送 type=15
    private function lose()
    {
        ini_set( 'memory_limit', '220M' );

        $type = CoursePush::TYPE_LOSE;

        //近30天上课的人
        $user_new_obj = DB::connection('mysql_read')->table('user_events')
            ->select(DB::raw('DISTINCT uid'))
            ->where('created_at', '>', date('Y-m-d', strtotime('-30 day')))
            ->whereIn('type', ['review_in', 'review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause'])
            ->get();
        $user_new_arr = [];
        foreach ($user_new_obj as $u){
            $user_new_arr[] = $u->uid;
        }
        unset($user_new_obj);
        $this->log('近30天上课的人' . count($user_new_arr));

        //前60天到前30天上过课的人
        $user_old_obj = DB::connection('mysql_read')->table('user_events')
            ->select(DB::raw('DISTINCT uid'))
            ->where('created_at', '>', date('Y-m-d', strtotime('-60 day')))
            ->where('created_at', '<', date('Y-m-d', strtotime('-30 day')))
            ->whereIn('type', ['review_in', 'review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause'])
            ->get();
        $user_old_arr = [];
        foreach ($user_old_obj as $u){
            $user_old_arr[] = $u->uid;
        }
        unset($user_old_obj);
        $this->log('前60天到前30天上过课的人' . count($user_old_arr));
        
        //取差集
        $result = array_diff($user_old_arr, $user_new_arr);
        $this->log('前60天到前30天上过课,但近30天没有上课'.count($result));

        $this->addToCoursePush($type, count($result));
        
        //开始推送
        $this->autoPushByUid($result, $type);
    }

    //30天内注册的新用户并且30天内未上课的人推送 type = 16
    private function newUser(){
        ini_set( 'memory_limit', '320M' );

        $type = CoursePush::TYPE_NEW_USER;

        //30天内注册的用户
        $user_new_obj = DB::connection('mysql_read')->table('user')
            ->select('id')
            ->where('created_at', '>', date('Y-m-d', strtotime('-30 day')))
            ->get();
        $user_new_arr = [];
        foreach ($user_new_obj as $u){
            $user_new_arr[] = $u->id;
        }
        unset($user_new_obj);
        $this->log('30天内注册的用户'.count($user_new_arr));

        //近30天上课的人
        $user_course_obj = DB::connection('mysql_read')->table('user_events')
            ->select(DB::raw('DISTINCT uid'))
            ->where('created_at', '>', date('Y-m-d', strtotime('-30 day')))
            ->whereIn('type', ['review_in', 'review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause'])
            ->get();
        $user_course_arr = [];
        foreach ($user_course_obj as $u){
            $user_course_arr[] = $u->uid;
        }
        unset($user_course_obj);
        $this->log('近30天上课的人' . count($user_course_arr));

        //取差集
        $result = array_diff($user_new_arr, $user_course_arr);
        $this->log('30天内注册的新用户并且30天内未上课的人'.count($result));

        $this->addToCoursePush($type, count($result));
        
        //开始推送
        $this->autoPushByUid($result, $type);
    }


    //一个月导出一次非微课堂注册的crm,给这些人推课
    private function pushNotWeiketang(){
        $type = CoursePush::TYPE_NEW_NOT_WEIKETANG;

        ini_set('memory_limit', '500M');
        $file_path = '/opt/ci123/www/wyeth_dev/wyeth_xj/storage/exports/2018-01_not_weiketang.txt';
        $content = file_get_contents($file_path);
        if (!$content) {
            $this->error('文件路径错误');
            exit;
        }
        $openid_list = explode("\n", $content);
        $openidArr = [];
        foreach ($openid_list as $k => $v) {
            $v = trim($v);
            if ($v && strlen($v) > 10 && strlen($v) < 60) {
                $openidArr[] = $v;
            }
        }

        //测试，xj wjj
//        $openidArr = [
//            'owtN6jkVHmd-ctim1pBNtWSqmXkU',
//            'owtN6jmOEj_wTck99vCkLoHZV1k0'
//        ];

        $count = count($openidArr);
        $confirm = "推送非微课堂渠道注册的人 openid路径:{$file_path} 推送人数:{$count}}";
        if (!$this->confirm($confirm . '确定吗?')) {
            exit();
        }

        $this->log('一个月导出一次非微课堂注册的crm,给这些人推课总数'.count($openidArr));

        $this->addToCoursePush($type, count($openidArr));

        $bar = $this->output->createProgressBar(count($openidArr));
        //394 463 427
        //2017-10-27 387 430
        //2017-12-2 387
        //2017-12-28 194
        //2018-02-01 194
        $arr = [194];
        foreach ($openidArr as $openid){
            $cid = $arr[array_rand($arr)];
            $this->dispatch(new CreateTplmsgFromAutoPushTjhg(null, $openid, $cid, true, $type));

            $bar->advance();
        }
        $bar->finish();

    }

    /**
     * 根据uid数组开始推送
     * @param $uids
     * @param int $type
     * @param bool $need_check
     */
    private function autoPushByUid($uids, $type = 14, $need_check = true){
        //开始推送
        $this->log('根据uid数组开始推送'.count($uids).' type:'.$type);
        foreach ($uids as $uid) {
            //根据用户查推荐课的cid
            $cid = CourseService::recommendCourseByUser($uid);
            if ($cid) {
                $user = User::find($uid);
                if ($user){
                    $openid = $user->openid;
                    $this->dispatch(new CreateTplmsgFromAutoPushTjhg($uid, $openid, $cid, $need_check, $type));
                }
            }
        }
    }


    private function log($message){
        \Log::info($message);
        $this->info($message);
    }

    //写入自动推送表里
    private function addToCoursePush($type, $push_num){
        $course_push = new CoursePush();
        $course_push->type = $type;
        $course_push->status = 1;
        $course_push->push_time = date('Y-m-d H:i:s');
        $course_push->push_num = $push_num;
        $course_push->save();
    }


    //一些工具方法
    //显示60s内的推送速度
    private function speed(){
        if ($this->arg1){
            $time = strtotime($this->arg1);
        }else{
            $time = time();
        }
        $total = 60;
        $all = 0;
        for ($i=$total;$i>0;$i--){
            $created = date('Y-m-d H:i:s', $time - $i);
            $count = DB::connection('mysql_read')->table('tplmsgs')
                ->where('created_at', $created)
                ->count();
            $this->info("$created $count 条");
            $all += $count;
        }
        $this->info('平均速度 '.$all/$total.'条/s');
    }

    //指定openid推送课程
    private function sendByOpenid()
    {
        //test path /opt/ci123/www/wyeth_dev/wyeth_xj/storage/users/test.txt
        ini_set('memory_limit', '500M');
        $file_path = $this->ask('输入文件路径');
        $cid = $this->ask('输入课程 cid');
        $template_id = $this->ask('输入模板消息类型 1:开课提醒 4:课程回顾');

        $course = Course::find($cid);
        if (!$course) {
            $this->error('课程不存在');
            exit;
        }

        $openid_arr = $this->getOpenidByFile($file_path);
        $count = count($openid_arr);

        $confirm = "推送cid:{$cid} openid路径:{$file_path} 推送人数:{$count} template_id:{$template_id}";
        if (!$this->confirm($confirm . '确定吗?')) {
            exit();
        }

        $openids_chunks = array_chunk($openid_arr, 500);
        if ($openids_chunks) {
            foreach ($openids_chunks as $openid_arr) {
                $this->dispatch(new CreateTemplateMessageByOpenid($cid, $openid_arr, $template_id, true));
            }
        }
    }

    private function special()
    {
        ini_set('memory_limit', '500M');
        $file_path = $this->ask('输入文件路径');
        $need_check = true;
        $test_path = '/opt/ci123/www/wyeth_dev/wyeth_xj/storage/users/test.txt';
        if ($file_path == 't') {
            $file_path = $test_path;
            $need_check = false;
        }
        $template_id = $this->ask("输入模板消息类型\n1:开课提醒\n4:课程回顾\n5:预约提醒");

        $openid_arr = $this->getOpenidByFile($file_path);
        $count = count($openid_arr);

        $action = 'activity';
        $url = 'http://mama-weiketang-wyeth.woaap.com/mobile/index?defaultPath=/activity1&forcejump=1';
        //url 加检测
//        $url = WyethUtil::getParamsLink($url, $action, ['wyeth_channel' => CoursePush::getTypeArray()[CoursePush::TYPE_SPECIAL]]);

        $tpl_params = [
            'openid' => 'owtN6jkVHmd-ctim1pBNtWSqmXkU', //test openid
            'title' => "亲爱的妈妈\n\n女性有两座神秘花园，一个是我们的脸，另一个则是卵巢。卵巢提前衰退，不仅会使我们面部失去光泽、发黄发暗，还会使我们身体臃肿、脾气变差，早早进入“黄脸婆”阶段。那么有没有办法可以改善这一情况？一起来看看专家是怎么说的~",
            'content' => '学会保养卵巢，做永远的“妙龄少女”',
            'odate' => date('Y-m-d'),
            'address' => '魔栗妈咪学院',
            'url' => $url,
            'remark' => '',
            'type' => CoursePush::TYPE_SPECIAL //写入 tplmsgs 中的type
        ];

        //发送预览
        $res = (new WxWyeth())->pushpushCustomMessage($tpl_params, $template_id, false);
        if (!$this->confirm("预览发送 {$res} ，确定推送 {$count} 人吗")) {
            exit();
        }

        //记录到course_push中
        $remark = $this->ask('本次推送添加备注');
        $course_push = (new CoursePush())->fill([
            'cid' => 0,
            'type' => CoursePush::TYPE_SPECIAL,
            'push_time' => date('Y-m-d H:i:s'),
            'status' => CoursePush::COURSE_PUSH_SEND,
            'push_num' => $count,
            'remark' => $remark,
            'action' => $action,
            'ext' => json_encode(['tpl_params' => $tpl_params])
        ]);
        $course_push->save();

        //进度条
        $bar = $this->output->createProgressBar($count);
        foreach ($openid_arr as $openid) {
            if ($template_id == 5) {
                //预约提醒需要名字
                $user = User::where('openid', $openid)->first();
                if (!$user) {
                    continue;
                }
                $tpl_params['content'] = $user->nickname;
            }
            $tpl_params['openid'] = $openid;

            $this->dispatch(new SendTemplateMessageByOpenid([
                'tpl_params' => $tpl_params,
                'need_check' => $need_check,
                'template_id' => $template_id
            ]));
            $bar->advance();
        }
        $bar->finish();
    }

    //2018-03-27 给定1700w openid推送，实际只有900w可以推，所有会员推一遍。
    public function allUser()
    {
        ini_set('memory_limit', '500M');
        //每天推10w金装(4)，10w启赋(10)，20wclub，club先推金装的再推一遍启赋
        $type = CoursePush::TYPE_ALL_USER;
        $base_path = '/opt/ci123/www/wyeth_dev/wyeth_xj/storage/users/split/';
        $num = date('d', strtotime('26 day ago'));

        $jinzhuang_path = $base_path . "jinzhuang.csv$num";
        $jinzhuang_user = $this->getOpenidByFile($jinzhuang_path);
        $jinzhuang_courses = CourseService::recommendCourseByBrand(4);

        $qifu_path = $base_path . "qifu.csv$num";
        $qifu_user = $this->getOpenidByFile($qifu_path);
        $qifu_courses = CourseService::recommendCourseByBrand(10);

        $club_num = sprintf('%02s',$num % 14);
        $club_path = $base_path . "club.csv$club_num";
        $club_user = $this->getOpenidByFile($club_path);
        $club_brand = $num < 14 ? 4 : 10; //先推金装再推启赋
        $club_courses = CourseService::recommendCourseByBrand($club_brand);

        $total = count($jinzhuang_user) + count($qifu_user) + count($club_user);

        $bar = $this->output->createProgressBar($total);

        foreach ($jinzhuang_user as $openid){
            $cid = $jinzhuang_courses[array_rand($jinzhuang_courses)];
            $this->dispatch(new CreateTplmsgFromAutoPushTjhg(null, $openid, $cid, true, $type));
            $bar->advance();
        }
        foreach ($qifu_user as $openid){
            $cid = $qifu_courses[array_rand($qifu_courses)];
            $this->dispatch(new CreateTplmsgFromAutoPushTjhg(null, $openid, $cid, true, $type));
            $bar->advance();
        }
        foreach ($club_user as $openid){
            $cid = $club_courses[array_rand($club_courses)];
            $this->dispatch(new CreateTplmsgFromAutoPushTjhg(null, $openid, $cid, true, $type));
            $bar->advance();
        }

        $bar->finish();

        $this->addToCoursePush($type, $total);
    }

    //根据文件获取openid
    private function getOpenidByFile($file_path)
    {
        $content = @file_get_contents($file_path);
        if (!$content) {
            $this->error('文件路径错误');
            exit;
        }

        $openid_list = explode("\n", $content);
        $openid_arr = [];
        foreach ($openid_list as $k => $v) {
            $v = trim($v);
            if ($v && strlen($v) > 10 && strlen($v) < 60) {
                $openid_arr[] = $v;
            }
        }
        return $openid_arr;
    }

}
