<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 11:51
 */

namespace app\Console\Commands;


use App\CIService\CIDataQuery;
use App\Helpers\CacheKey;
use App\Services\Email;
use Illuminate\Console\Command;
use Cache;
use App\CIService\CIDataOpenApi;
use Illuminate\Support\Facades\DB;

class StatisticCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistic:update {action} {arg1=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '添加首页累计设备基数和母乳卡相关统计';

    private $arg1;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
        $action = $this->argument('action');
        $this->arg1 = $this->argument('arg1');
        if ($action == 'add') {
            if(!is_numeric($this->arg1)){
                $this->warn('参数错误');
            }else{
                $this->add($this->arg1);
            }
        }elseif ($action == 'base') {
            $this->dailyCount();
        }elseif($action == 'decrease'){
            if(!is_numeric($this->arg1)){
                $this->warn('参数错误');
            }else{
                $this->decrease($this->arg1);
            }
        }elseif($action == 'S26'){
            $this->S26Card();
        }elseif($action == 'ResetS26'){
            $this->Reset_S26Card();
        }elseif($action == 'cidata_check'){
            $this->cidata_check();
        }elseif($action == 'reset_hd_card'){
            $this->reset_hd_card();
        }elseif($action == 'hd_card'){
            $this->hd_card();
        }elseif($action == 'query_spring'){
            $this->query_spring();
        }elseif($action == 'resetQuerySpring'){
            $this->resetQuerySpring();
        }else {
            $this->warn("WRONG INPUT !");
        }
    }

    // 增减操作
    public function add($num){
        $old_num = Cache::get(CacheKey::H5_COUNT_DEVICES);
        if($old_num){
            if($num >= 0){
                Cache::increment(CacheKey::H5_COUNT_DEVICES, $num);
            }else{
                Cache::decrement(CacheKey::H5_COUNT_DEVICES, $num);
            }
        }else{
            Cache::forever(CacheKey::H5_COUNT_DEVICES, $num);
        }
    }

    public function decrease($num){
        $old_num = Cache::get(CacheKey::H5_COUNT_DEVICES);
        if($old_num){
                Cache::decrement(CacheKey::H5_COUNT_DEVICES, $num);
        }else{
            $this->warn("WRONG INPUT !");
        }
    }


    // 每天凌晨1点跑自动脚本，增加累计用户数量
    public function dailyCount(){
        try{
            $cidata = new CIDataOpenApi();
            // 计算累计设备量（活跃+新增）
            $start_day_total = strtotime(date('Y-m-d',time() - 86400));
            $end_day_total = strtotime(date('Y-m-d'),time());
            $total_device_active_array = @$cidata->userCount($start_day_total, $end_day_total,"new", "all", null)['data'];

            $total_device = 0;
            $total_users = 0;
            foreach ($total_device_active_array as $item){
                $total_device += $item['device'];
                $total_users += $item['user'];
            }
            $this->add($total_device);
        }catch (\Exception $e){
            Email::SendEmail("每日新增设备","累加出现异常",Email::EMAIL_BOX_CIDATA);
        }
    }
    // 母乳卡和学霸卡重置为当前时间的数据
    public function Reset_S26Card(){
        // 清除缓存
        Cache::forget(CacheKey::S26_CARD_USERS);
        Cache::forget(CacheKey::XUE_BA_CARD_USERS);
        $cidata = new CIDataOpenApi();
        $start = strtotime('2018-1-20');
        $end = strtotime(date('Y-m-d H:i:s'),time() - 3600);
        $filters = array('type' => 'and',
            'filters' => [array('dimension' => array('type' => 'system','name' => 'platform'),
                'operator' => "=",'value' => 'web')]
        );
        $start_tmp = $start;
        $page_view = 0;
        while ($start_tmp < $end){
            $end_tmp = $start_tmp + 86400*7;
            echo date('Y-m-d',$end_tmp)."\n";
            if($end_tmp > $end){
                $end_tmp = $end;
            }
            $page_view += $cidata->eventCount($start_tmp,$end_tmp,"new","s26_card_channel","all", $filters)['data'][0]['user'];
            $start_tmp += 86400 *7;
        }
        $start_time = date('Y-m-d H:i:s',$start);
        $end_time = date('Y-m-d H:i:s',$end);
        $course_view = DB::select("select count(DISTINCT uid) as total from user_events WHERE cid in(385, 216, 209, 498, 320, 384, 389, 393, 436) and created_at>'$start_time' and created_at<'$end_time'")[0]->total;
        $s26 = $page_view + $course_view;
        echo "学霸卡：".$s26."\n";
        Cache::forever(CacheKey::S26_CARD_USERS,$s26);
        // 学霸卡
        $start_tmp = $start;
        $page_view = 0;
        while ($start_tmp < $end){
            $end_tmp = $start_tmp + 86400*7;
            echo date('Y-m-d',$end_tmp)."\n";
            if($end_tmp > $end){
                $end_tmp = $end;
            }
            $page_view += $cidata->eventCount($start_tmp,$end_tmp,"new","wyeth_breast_activity_count","all", $filters)['data'][0]['user'];
            $start_tmp += 86400 *7;
        }
        $course_view = DB::select("select count(DISTINCT uid) as total from user_events WHERE cid in(412, 457, 435, 407, 204, 410, 46, 419) and created_at>'$start_time' and created_at<'$end_time'")[0]->total;
        $xueba = $page_view + $course_view;
        echo "母乳卡 ".$xueba."\n";
        Cache::forever(CacheKey::XUE_BA_CARD_USERS,$xueba);
    }
    public function S26Card(){
        try{
            $cidata = new CIDataOpenApi();
            // 每小时更新一下
            $start = strtotime(date('Y-m-d H:i:s',time() - 7200));
            $end = strtotime(date('Y-m-d H:i:s'),time() - 3600);
            $filters = array('type' => 'and',
                'filters' => [array('dimension' => array('type' => 'system','name' => 'platform'),
                    'operator' => "=",'value' => 'web')]
                );
            // 母乳卡
            $page_view = $cidata->eventCount($start,$end,"new","s26_card_channel","all", $filters)['data'][0]['user'];
            $start_time = date('Y-m-d H:i:s',$start);
            $end_time = date('Y-m-d H:i:s',$end);
            $course_view = DB::select("select count(DISTINCT uid) as total from user_events WHERE cid in(385, 216, 209, 498, 320, 384, 389, 393, 436) and created_at>'$start_time' and created_at<'$end_time'")[0]->total;
            $s26 = $page_view + $course_view;
            $old_num = Cache::get(CacheKey::S26_CARD_USERS);
            if($old_num){
                Cache::increment(CacheKey::S26_CARD_USERS, $s26);
            }else{
                Cache::put(CacheKey::S26_CARD_USERS,21940,61);
                Email::SendEmail("S26Card","无累加数据",Email::EMAIL_BOX_CIDATA);
            }

            // 学霸卡
            $page_view = $cidata->eventCount($start,$end,"new","wyeth_breast_activity_count","all", $filters)['data'][0]['user'];
            $course_view = DB::select("select count(DISTINCT uid) as total from user_events WHERE cid in(412, 457, 435, 407, 204, 410, 46, 419) and created_at>'$start_time' and created_at<'$end_time'")[0]->total;
            $xueba = $page_view + $course_view;
            if($old_num){
                Cache::increment(CacheKey::XUE_BA_CARD_USERS, $xueba);
            }else{
                Cache::put(CacheKey::XUE_BA_CARD_USERS,21940,61);
                Email::SendEmail("学霸卡","无累加数据",Email::EMAIL_BOX_CIDATA);
            }
        }catch (\Exception $e){
            Email::SendEmail("S26Card","累加出现异常",Email::EMAIL_BOX_CIDATA);
        }
    }

    // 每小时更新活动卡页面的参与人数
    public function hd_card(){
        try{
            $cidata = new CIDataOpenApi();
            // 每小时更新一下
            $start = strtotime(date('Y-m-d H:i:s',time() - 7200));
            $end = strtotime(date('Y-m-d H:i:s'),time() - 3600);
            $filters = array('type' => 'and',
                'filters' => [array('dimension' => array('type' => 'system','name' => 'platform'),
                    'operator' => "=",'value' => 'server'),
                    array('dimension' => array('type' => 'event','name' => 'aid'),
                        'operator' => "in",'value' => [2,3,4])]
            );
            // 活动卡
            $page_view = $cidata->eventCount($start,$end,"active","hd_aid","all", $filters)['data'][0]['user'];
            $hd_card = $page_view;
            $old_num = Cache::get(CacheKey::HD_CARD_USERS);
            if($old_num){
                Cache::increment(CacheKey::HD_CARD_USERS, $hd_card);
            }else{
                Cache::put(CacheKey::HD_CARD_USERS,41115,61);
                Email::SendEmail("活动卡页面","无累加数据",Email::EMAIL_BOX_CIDATA);
            }
        }catch (\Exception $e){
            Email::SendEmail("活动卡页面","累加出现异常",Email::EMAIL_BOX_CIDATA);
        }
    }

    // 重置活动卡页面数据
    public function reset_hd_card(){
        // 清除缓存
        Cache::forget(CacheKey::HD_CARD_USERS);
        $cidata = new CIDataOpenApi();
        $start = strtotime('2018-2-13');
        $end = strtotime(date('Y-m-d H:i:s'),time() - 3600);
        $filters = array('type' => 'and',
            'filters' => [array('dimension' => array('type' => 'system','name' => 'platform'),
                'operator' => "=",'value' => 'server'),
                array('dimension' => array('type' => 'event','name' => 'aid'),
                    'operator' => "in",'value' => [2,3,4]),]
        );
        $start_tmp = $start;
        $page_view = 0;
        while ($start_tmp < $end){
            $end_tmp = $start_tmp + 86400*7;
            echo date('Y-m-d',$end_tmp)."\n";
            if($end_tmp > $end){
                $end_tmp = $end;
            }
            $page_view += $cidata->eventCount($start_tmp,$end_tmp,"active","hd_aid","all", $filters)['data'][0]['user'];
            $start_tmp += 86400 *7;
        }
        $hd_card = $page_view;
        echo "活动卡页面统计：".$hd_card."\n";
        Cache::forever(CacheKey::S26_CARD_USERS,$hd_card);
    }

    // 每小时检查一下cidata的统计是否挂掉
    public function cidata_check(){
        $cidata = new CIDataOpenApi();
        $time = time();
        $time_str = date('Y-m-d H:i:s',time() - 7200); // 五分钟内
        $start_s = strtotime(date('Y-m-d',$time)) + 10*3600; // 早上九点
        $end_s = $start_s + 14*3600; // 晚上十一点
        $start = $time - 7200;
        $end = $time - 3600;
        $filters = array('type' => 'and',
            'filters' => [array('dimension' => array('type' => 'system','name' => 'platform'),
                'operator' => "=",'value' => 'web')]
        );
        if($time >= $start_s && $time <= $end_s){
            // 前端埋点
            $res = $cidata->eventCount($start,$end,"active","visit","all", $filters);
            if(!$res){
                // 重试一次
                $res = @$cidata->eventCount($start,$end,"active","visit","all", $filters);
                //发邮件
                if(!$res) {
                    $content = $time_str . " cidata接口异常";
                    if (!config('app.debug')) {
                        Email::SendEmail('CIData', $content, Email::EMAIL_BOX_CIDATA);
                    }
                }
            }else{
                if($res['data'][0]['user'] == 0){
                    $content = $time_str . " 页面访问uv为0";
                    if (!config('app.debug')) {
                        Email::SendEmail('CIData', $content, Email::EMAIL_BOX_CIDATA);
                    }
                }
            }
            // 服务端埋点
            $filters = array('type' => 'and',
                'filters' => [array('dimension' => array('type' => 'system','name' => 'platform'),
                    'operator' => "=",'value' => 'server')]
            );
            $res = $cidata->eventCount($start,$end,"active","open_tplmsg","all",$filters);
            if($res['data'][0]['user'] == 0){
                $content = $time_str . " 模板消息打开量为0";
                if (!config('app.debug')) {
                    Email::SendEmail('CIData', $content, Email::EMAIL_BOX_CIDATA);
                }
            }
        }
    }

    public function query_spring(){
        $cidata = new CIDataOpenApi();
        $end = strtotime(date('Y-m-d',time() - 7200));
        $start_tmp = strtotime(date('Y-m-d',time() - 7200 - 86400));
        $start = date('Y-m-d',time() - 7200 - 86400);
        $filters = array('type' => 'and',
            'filters' => [array('dimension' => array('type' => 'system','name' => 'platform'),
                'operator' => "=",'value' => 'web')]
        );
        try{
            $num = DB::select("select distinct(uid,cid) as total from user_events WHERE created_at>'$start' and type='review_in' and cid in (43, 45, 49, 55, 63, 109, 253, 298)")[0]->total;
            $page_view = $cidata->eventCount($start_tmp,$end,"active","spring_secret_channel","all", $filters)['data'][0]['user'];
            $num += $page_view;
        }catch (\Exception $e){
            $num = 0;
            $content = '孕育指南累加异常';
            Email::SendEmail('CIData', $content, Email::EMAIL_BOX_CIDATA);
        }
        $old_num = Cache::get(CacheKey::QUERY_SPRING);
        if($old_num){
            Cache::increment(CacheKey::QUERY_SPRING, $num);
        }else{
            Email::SendEmail("孕育指南累加异常","无累加数据",Email::EMAIL_BOX_CIDATA);
        }
    }

    public function resetQuerySpring(){
        Cache::forget(CacheKey::QUERY_SPRING);
        $base = 29902 + 26833;  // 2017-7-10 ~ 2018-3-10 时间段内上过43, 45, 49, 55, 63, 109, 253, 298的人数
        $cidata = new CIDataOpenApi();
        $end = strtotime(date('Y-m-d',time() - 7200));
        $start_tmp = strtotime('2018-3-10');
        $filters = array('type' => 'and',
            'filters' => [array('dimension' => array('type' => 'system','name' => 'platform'),
                'operator' => "=",'value' => 'web')]
        );
        try{
            $num = DB::select("select count(distinct uid,cid) as total from user_events WHERE created_at>'2018-3-10' and type='review_in' and cid in (43, 45, 49, 55, 63, 109, 253, 298)")[0]->total;
            $page_view = 0;
            while ($start_tmp < $end){
                $end_tmp = $start_tmp + 86400*7;
                echo date('Y-m-d',$end_tmp)."\n";
                if($end_tmp > $end){
                    $end_tmp = $end;
                }
                $page_view = $cidata->eventCount($start_tmp,$end_tmp,"active","spring_secret_channel","all", $filters);
                if(!isset($page_view['data'][0]['user'])){
                    $page_view = $cidata->eventCount($start_tmp,$end_tmp,"active","spring_secret_channel","all", $filters)['data'][0]['user'];
                }
                $page_view = $page_view['data'][0]['user'];
                $start_tmp += 86400 *7;
            }
            $num += $page_view;
        }catch (\Exception $e){
            $num = 0;
            $content = '孕育指南累加异常';
            Email::SendEmail('CIData', $content, Email::EMAIL_BOX_CIDATA);
        }
        echo "DONE!!!";
        Cache::forever(CacheKey::QUERY_SPRING,$num + $base);
    }
}
