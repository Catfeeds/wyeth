<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\WxWyeth;
use App\Models\WeekDataExport;
use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use GuzzleHttp;
use Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\CIService\CIDataQuery;

class SendExportSignal extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $state_path;
    protected $week_start;
    protected $week_end;
    protected $year_start;
    protected $month_start;
    protected $cid;
    protected $params;

    /**
     * Create a new job instance.
     *
     * return void
     */
    public function __construct($state_path, $week_start, $week_end, $cid, $params)

    {
        $this->params = $params;
        $this->state_path = $state_path;
        $this->week_start = $week_start; //$request->input('week_start');
        $this->week_end = $week_end; //$request->input('week_end');
        $this->cid = $cid;
    }

    /**
     * Execute the job.
     *
     * return void
     */
    public function handle()
    {
        ini_set('memory_limit', '300M');
        $csv_path = $this->params['file'];
        $state_path = $this->state_path;
        $start_day = $this->week_start;
        $end_day = $this->week_end;
        $week_start = date('Y-m-d', strtotime($this->week_start));
        $week_end = date('Y-m-d', strtotime($this->week_end)); //$request->input('week_end');
        $year_start = date('Y-m-d', strtotime(substr($week_start, 0, 4) . "-01-01"));
        $month_start = date('Y-m-1', strtotime($week_end));
        $cid = $this->cid;
        $week_new = explode(',', $cid);
        \Log::info("start,week_all_cousrse" . date('Y-m-d H:i:s'));
        $arr = array();
        $sql = "select id,title,teacher_name,start_day,stage_from,stage_to,yunqi from course where display_status=1 or id in(436,445,496,497,509,510,512,513,514,515)";
        $course = DB::connection('mysql_read')->select($sql);
        foreach ($course as $v) {
            $arr[] = $v->id;
        }
        /*==============================所有课程本周教育情况===========================*/
        file_put_contents($state_path, '1');
        $week_all = array("课程id", "主题", "上课日期", "老师", "月龄", "品牌", "更新对应品牌", "标签", "本周全平台教育人次", "H5教育人次", "其他平台教育人次", "目睹教育人次", "本周微课堂报名总数", "线上报名人数", "线上报名并教育人数", "线上报名上课率", "线下报名", "线下报名并教育", "线下报名上课率", "线上主动报名后推送", "线上主动报名后推送并教育", "转化率", "回顾推送", "回顾推送并教育", "转化率",
            "上课页面点击播放人数",'1分内人数','拖动一分内','1-3人数','1-3分','3-5人数','3-5分','5-7人数','5-7分','7以上','7分以上','30秒内人数','听课时长30秒内','30-60秒人数','30-60秒','1-3分人数','1-3分','3-7分人数','3-7分','7分以上人数','7分以上','完听人数','完听率','页面停留1分内','1分内占比','停留1-3','占比','停留3-5','占比','停留5-7','占比','停留7分以上','占比',"播放时长", "超过3min占比", "超过7min占比", "有主（占比）", "无主(占比)", "孕期(占比)", "0-12m(占比)", "12-24m占比", "24m+", "华东", "华中", "华北", "华西", "华南", "外国", "本月全平台教育人次", "本月H5教育人次", "本月其他平台教育人次", "本月目睹教育人次", "ytd全平台教育", "ytd月数", "ytd全平台月均教育","ytd其他平台教育", "ytdH5教育人次", "ytdH5月均教育人次(包含目睹)","上线至今月数", "上线至今总报名人次", "上线至今全平台教育人次", "其他平台教育人次", "上线至今H5教育人次","全平台月均教育人次",
            "转发量", "提问", "是否是精品课", "到期时间", "签约状态");
        $file_path = storage_path() . "/WeekData/all_course_week_$end_day.csv";
        $outputBuffer = fopen($file_path, 'w+');
        fwrite($outputBuffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($outputBuffer, $week_all);
        fclose($outputBuffer);
        // update 数据
        ini_set('auto_detect_line_endings', true);
        \Log::info('csv open');

        // 删除之前周的其他平台数据
        DB::table('course_detail')->where('cid','>',0)->update(['week_other' => 0]);
        $fp = fopen($csv_path, "r");
        while (!feof($fp)) {
            $data = fgetcsv($fp);
            if(!is_numeric($data[0])){
                continue;
            }
            $id = intval(trim($data[0]));
            \Log::info('xxxdata0xxx' . "$data[0]");
            $other = intval(empty($data[1]) ? 0 : trim($data[1]));
            $ask = 0;
            if (isset($data[2])) {
                $ask = intval(empty($data[2]) ? 0 : trim($data[2]));
            }

            if ($id > 0) {
                $course1 = DB::table('course_detail')->where('cid', $id)->first();
                if ($course1) {
                    DB::table('course_detail')->where('cid', $id)->update(['week_other' => $other, 'ask_lask_week' => $ask]);
                    \Log::info('update' . "===$id");
                } else {
                    $arr = array('cid' => $id, 'week_other' => $other, 'ask' => $ask);
                    DB::table('course_detail')->insert($arr);
                    \Log::info('insert' . "......$id");
                }
            } else {
                \Log::info('Do Nothing in csv');
            }

            if ($data[0] <= 0) {
                break;
            }
        }
        \Log::info('周数据更新至数据库');
        $num = 0;
        foreach ($arr as $k => $v) {
            $tmp = array();
            $tmp['id'] = $v;
            $tmp['title'] = $course[$k]->title;
            $tmp['start_day'] = $course[$k]->start_day;
            \Log::info("id" . "$v");
            $stage_from = $course[$k]->stage_from;
            $stage_to = $course[$k]->stage_to;
            $yunqi = $course[$k]->yunqi;
            $course_date = strtotime($tmp['start_day']);
            $stage = '';
            if ($course_date > strtotime($week_end)) {
                continue;
            }
            if($yunqi == 1){
                $stage = "孕期";
            }else{
                $temp = floor($stage_to / 100);
                $t = intval($stage_to % 100);
                if ($stage_from == 100) {
                    $stage = "备孕";
                    switch ($temp) {
                        case 1:
                            break;
                        case 2:
                            $stage .= "到孕" . $t . "个月";
                            break;
                        case 3:
                            $t = $t * 12;
                            $stage .= "到宝宝" . "$t 个月";
                            break;
                    }
                } elseif (floor($stage_from/100) == 2) {
                    $stage = "孕";
                    $t1 = $stage_from % 100;
                    $t2 = ($stage_to % 100) * 12;
                    switch ($temp) {
                        case 2:
                            $stage .= "$t1" . "个月到孕" . $t . "个月";
                            break;
                        case 3:
                            $stage .= "$t1" . "个月到宝宝" . "$t2" . "个月";
                            break;
                    }
                } elseif (floor($stage_from/100) == 3) {
                    $t3 = ($stage_from % 100) * 12;
                    $t = (floor($stage_to/100)) * 12;
                    $stage = $t3 . "-" . $t . "个月";
                }
            }

            $tmp['stage'] = $stage;
            $sql = "select brand from course WHERE id='$v'";
            $brand_id = DB::connection('mysql_read')->select($sql)[0]->brand;
            //$sql = "select brand from course WHERE cid='$v'";
            $old_brand_id = DB::table('course_detail')->where('cid',$v)->value('ext1');// 旧的品牌信息保存在detail表里,字段ext1
            //$update_brand = array(36,43,48,52,57,61,63,65,69,85,86,88,91,92,97,107,108,109,132,147,158,161,162,163,167,174,187,194,195,198,199,200,205,211,215,221,225,227,228,229,254,291,294,309,313,332,381,382,383,387,393,394,423,426,427);
            \Log::info("brand_id" . "$brand_id");
            if ($brand_id == 0) {
                $tmp['update_brand'] = "";
            } else {
                $sql = "select name from brand  WHERE id='$brand_id'";
                \Log::info('brand name');
                $brand_name = DB::connection('mysql_read')->select($sql)[0]->name;
                $tmp['update_brand'] = $brand_name;
            }
            if(empty($old_brand_id)){
                $tmp['brand'] = $tmp['update_brand'];
            } else {
                $sql = "select name from brand  WHERE id='$old_brand_id'";
                \Log::info('brand name');
                $brand_name = DB::connection('mysql_read')->select($sql)[0]->name;
                $tmp['brand'] = $brand_name;
            }
            \Log::info('tag');
            $sql = "select tid from course_tags where cid='$v' and type=3";
            $tid_arr = DB::connection('mysql_read')->select($sql);
            if (empty($tid_arr)) {
                $tmp['tag'] = "";
            } else {
                $tid = DB::connection('mysql_read')->select($sql)[0]->tid;
                $sql = "select name from tags where id='$tid'";
                $tag = DB::connection('mysql_read')->select($sql)[0]->name;
                $tmp['tag'] = $tag;
            }
            \Log::info('get tag');
            $tmp['teacher_name'] = $course[$k]->teacher_name;
            //本周线上报名
            $data = array();
            $data['from'] = $week_start;
            $data['to'] = $week_end;
            $data['cid'] = $v;
            $data = http_build_query($data);
            $opts = array(
                'http' => array(
                    'method' => "POST",
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                        "Content-length:" . strlen($data) . "\r\n" .
                        "Cookie: foo=bar\r\n" .
                        "\r\n",
                    'content' => $data,
                    'timeout' => 60,
                )
            );
            $cxContext = stream_context_create($opts);

            for($i = 0;$i<3;$i++){
                $sFile = file_get_contents("http://idg-jinjinyuan.tunnel.nibaguai.com/jinjinyuan/cidata-query/part.php", false, $cxContext);
                $sFile = json_decode($sFile, true);
                if(isset($sFile['week_sign'])){
                    break;
                }
            }
            for($i = 0;$i<3;$i++){
                $sFile_1 = file_get_contents("http://idg-jinjinyuan.tunnel.nibaguai.com/jinjinyuan/cidata-query/part_1.php", false, $cxContext);
                $sFile_1 = json_decode($sFile_1, true);
                if(isset($sFile_1['center_rate'])){
                    break;
                }
            }

            \Log::info("获得课程表数据============" . json_encode($sFile));
            // 占比
            $tmp['rate_3'] = $sFile['rate_3'];
            $tmp['rate_7'] = $sFile['rate_7'];
            $tmp['center_rate'] = $sFile_1['center_rate'];
            $tmp['north_rate'] = $sFile_1['north_rate'];
            $tmp['west_rate'] = $sFile_1['west_rate'];
            $tmp['east_rate'] = $sFile_1['east_rate'];
            $tmp['south_rate'] = $sFile_1['south_rate'];
            $tmp['foreign_rate'] = $sFile_1['foreign_rate'];

            // 统计月龄
            $t1 = 0;
            $t2 = 0;
            $t3 = 0;
            $t4 = 0;
            $t5 = 0;
            $t6 = 0;

            // 使用数据库的方法统计月龄，暂时保留

            $sql = "select distinct uid from user_events where cid = $v and created_at >'$week_start' and created_at<'$week_end' and type in ('review_in', 'review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause')";
            $total2 = DB::connection('mysql_read')->select($sql);
            //$total = array_merge($total1, $total2);
            $timestamp = time();
            foreach ($total2 as $KK => $VV) {
                $uid = $VV->uid;
                $sql = "select * from user where id = ".$uid;
                try {
                    $row = DB::connection('mysql_read')->select($sql)[0];
                }catch(\Exception $e){
                    $row = '';
                }
                if(!$row){
                    continue;
                }
                if ($row->channel == 'hongbao2016') {
                    $t1++;
                } else {
                    $t2++;
                }

                if ($row->baby_birthday != "0000-00-00 00:00:00") {
                    $baby_birthday = strtotime($row->baby_birthday);
                    $gap = $timestamp - $baby_birthday;
                    if ($timestamp < $baby_birthday) {
                        $t3++;
                    } else {
                        if ($gap < 365 * 24 * 3600) {
                            $t4++;
                        } elseif ($gap < 2 * 365 * 24 * 3600) {
                            $t5++;
                        } else {
                            $t6++;
                        }
                    }
                }
            }

            $tmp['yunqi'] = 0;
            $tmp['m_12'] = 0;
            $tmp['m_24'] = 0;
            $tmp['m_24_'] = 0;
            $all = $t1+$t2;
            if($all != 0) {
                $tmp['youzhu'] = number_format($t1*100/$all,2)."%";
                $tmp['wuzhu'] = number_format($t2*100/$all,2)."%";
            } else{
                $tmp['youzhu'] = 0;
                $tmp['wuzhu'] = 0;
            }
            $all = $t3+$t4+$t5+$t6;
            if($all != 0){
                $tmp['yunqi'] = number_format($t3*100/$all,2)."%";
                $tmp['m_12'] = number_format($t4*100/$all,2)."%";
                $tmp['m_24'] = number_format($t5*100/$all,2)."%";
                $tmp['m_24_'] = number_format($t6*100/$all,2)."%";
            }

            //报名后推送
            $tmp['week_sign'] = $sFile['week_sign'];
            $tmp['sign_send'] = $sFile['sign_send'];
            $tmp['sign_send_open'] = $sFile['sign_send_open'];
            $tmp['send'] = $sFile['send'];
            $tmp['send_open'] = $sFile['send_open'];
            if ($tmp['sign_send_open'] > $tmp['sign_send']) {
                $tmp['sign_send_open'] = $tmp['sign_send'];
            }
            if ($tmp['send_open'] > $tmp['send']) {
                $tmp['send_open'] = $tmp['send'];
            }
            $tmp['play'] = $sFile['play'];
            $sFile['week_h5'] = DB::select("select count(distinct uid) as total from user_events WHERE created_at>'$week_start' and created_at<'$week_end' and type='review_in' and cid='$v'")[0]->total;
            $tmp['week_h5'] = $sFile['week_h5'];
            $tmp['month_h5'] = $sFile['month_h5'];
            $tmp['week_sign_online'] = empty($sFile['week_sign_online']) ? 0 : intval($sFile['week_sign_online']);
            $week_sign_online = $tmp['week_sign_online'];
            $tmp['week_sign_offline'] = empty($sFile['week_sign_offline']) ? 0 : intval($sFile['week_sign_offline']);
            $tmp['sign_open_rate'] = empty($tmp['sign_send']) ? 0 : $tmp['sign_send_open'] / $tmp['sign_send'];
            $tmp['sign_send_rate'] = number_format($tmp['sign_open_rate']*100, 2)."%";
            $tmp['open_rate'] = empty($tmp['send']) ? 0 : $tmp['send_open'] / $tmp['send'];
            $tmp['send_rate'] = number_format($tmp['open_rate']*100, 2)."%";
            //$tmp['week_sign_edu_online']= empty($sFile['week_sign_edu_online'])?0:intval($sFile['week_sign_edu_online']);
            //$tmp['week_sign_edu_offline'] = empty($sFile['week_sign_edu_offline'])?0:intval($sFile['week_sign_edu_offline']);

            //本周线上报名 如果http无返回
            if (!isset($sFile['week_sign_online'])) {
                $sql = "SELECT count(DISTINCT uid) as total FROM `user_course` WHERE cid=$v and  channel !='hongbao2016' and created_at>'$week_start' and created_at<'$week_end'";
                $total = DB::connection('mysql_read')->select($sql)[0]->total;
                $week_sign_online = $total;
                $tmp['week_sign_online'] = $total;
                //本周线下报名
                $sql = "SELECT count(DISTINCT uid) as total FROM `user_course` WHERE cid=$v and  channel ='hongbao2016' and created_at>'$week_start' and created_at<'$week_end'";
                $total = DB::connection('mysql_read')->select($sql)[0]->total;
                $week_sign_offline = $total;
                $tmp['week_sign_offline'] = $total;
                $tmp['week_sign'] = $tmp['week_sign_offline'] + $tmp['week_sign_online'];
            }
            // 线上报名并上课
            $sql = "SELECT DISTINCT uid FROM `user_course` WHERE cid=$v and  channel !='hongbao2016' and created_at>'$week_start' and created_at<'$week_end'";
            $uids = DB::connection('mysql_read')->select($sql);
            $u_arr = array();
            foreach ($uids as $ks => $vs) {
                $u_arr[] = $vs->uid;
            }
            $total = 0;
            if ($u_arr) {
                $sql = "select count(DISTINCT uid) as total from user_events where cid=$v and uid in (" . implode(",", $u_arr) . ")" . " and type='review_in'";
                $total = DB::connection('mysql_read')->select($sql)[0]->total;
            }
            $week_sign_listen = $total;
            $tmp['week_sign_edu_online'] = $total;
            if ($week_sign_online !== 0) {
                $tmp['week_listen_rate_online'] = number_format($total*100/ $week_sign_online,2)."%";
            } else {
                $tmp['week_listen_rate_online'] = 0;
            }

            // 线下报名并教育
            $sql = "SELECT DISTINCT uid FROM `user_course` WHERE cid=$v and  channel ='hongbao2016' and created_at>'$week_start' and created_at<'$week_end'";
            $uids = DB::connection('mysql_read')->select($sql);
            $u_arr = array();
            foreach ($uids as $ks => $vs) {
                $u_arr[] = $vs->uid;
            }
            $total = 0;
            if ($u_arr) {
                $sql = "select count(DISTINCT uid) as total from user_events where cid=$v and uid in (" . implode(",", $u_arr) . ")" . " and type='review_in'";
                if($v != 381){
                    $total = DB::connection('mysql_read')->select($sql)[0]->total;
                }
            }
            $week_sign_offline_edu = $total;
            $tmp['week_sign_offline_edu'] = $total;
            $tmp['week_listen_rate_offline'] = empty($week_sign_offline_edu) ? 0 : number_format($total*100 / $tmp['week_sign_offline'],2)."%";

            // 获取并计算全平台和其他平台本周、本月、ytd、上线至今的人次
            \Log::info('start caculate');
            $start = date('Y-m-d', strtotime($week_start));
            $end = date('Y-m-d', strtotime($week_end));
            $all_data = DB::table('course_detail')->where(['cid' => $v])->first();
            $last_week_start = date('Y-m-d', strtotime('-7 days', strtotime($start)));
            \Log::info('last_week' . $last_week_start);
            $last_week_end = date('Y-m-d', strtotime('-7 days', strtotime($end)));
            //$all_data_last_week = DB::table('course_detail')->where(['cid' => $v,'start_day' => $last_week_start,'end_day' => $last_week_end]);

            \Log::info('all_data====' . json_encode($all_data));
            // 周其他平台
            $week_other = empty($all_data->week_other) ? 0 : $all_data->week_other;
            $week_mudu = empty($all_data->week_mudu) ? 0 : $all_data->week_mudu;
            // 周报名
            $week_sign = $tmp['week_sign'];
            // 本周h5教育人次
            $week_h5 = $sFile['week_h5'];
            $week_all = $week_other + $week_mudu + $week_h5;
            // 加入数组中

            // 先不管跨月
            $month_other = empty($all_data->month_other) ? $week_other : $all_data->month_other + $week_other;
            $month_mudu = empty($all_data->month_mudu) ? $week_mudu : $all_data->month_mudu;
            // 加入数组
            $month_h5 = $sFile['month_h5'];
            $month_all = $month_h5 + $month_mudu + $month_other;

            \Log::info("ytd");

            // ytd
            $ytd_h5 = empty($all_data->ytd_h5) ? $week_h5 : ($all_data->ytd_h5 + $week_h5);
            //var_dump($all_data->ytd_h5);
            $ytd_other = empty($all_data->ytd_other) ? $week_other : ($all_data->ytd_other + $week_other);
            //var_dump($all_data->ytd_other);

            $ytd_mudu = empty($all_data->ytd_mudu) ? $week_mudu : ($all_data->ytd_mudu + $week_mudu);

            $ytd_all = $ytd_h5 + $ytd_other + $ytd_mudu;
            $share = $sFile['share'];
            $tmp['share'] = $share + DB::table('course_detail')->where('cid',$v)->value('share');
            $ask_week = DB::table('course_detail')->where('cid', $v)->value('ask_lask_week');
            $tmp['ask'] = DB::table('course_detail')->where('cid', $v)->value('ask') + $ask_week;
            // 上线至今
            $now_all_sign = empty($all_data->now_all_sign) ? $week_sign : ($all_data->now_all_sign + $week_sign);
            $now_all_edu = empty($all_data->now_all_edu) ? $week_all : ($all_data->now_all_edu + $week_all);
            $now_h5 = empty($all_data->now_h5) ? $week_h5 : ($all_data->now_h5 + $week_h5);
            $expire = empty($all_data->ext) ? '': ($all_data->ext);
            \Log::info("上线至今数据=====总报名" . "$now_all_sign" . "总教育" . "==$now_h5");
            $now_other = empty($all_data->now_other) ? $week_other : ($all_data->now_other + $week_other);

            // ytd 月均
            $course_online = DB::connection('mysql_read')->table('course')->where('id', '=', $v)->value('start_day');
            \Log::info('____'.$course_online);
            $year = strtotime($year_start);
            if (strtotime($course_online) < $year) {
                $months = intval(date('m', strtotime($week_end)));
            } else {
                $months = intval(date('m', strtotime($week_end))) - intval(date('m', strtotime($course_online))) + 1;
            }
            if($months <= 0){
                $months = 1;
            }
            $ytd_all_avg = intval($ytd_all / $months);
            $ytd_h5_avg = intval($ytd_h5 / $months);
            $tmp['ytd_months'] = $months;
            $tmp['week_other'] = $week_other;
            $tmp['week_mudu'] = $week_mudu;
            $tmp['week_all'] = $week_all;
            $tmp['week_h5'] = $week_h5;
            $tmp['month_other'] = $month_other;
            $tmp['month_mudu'] = $month_mudu;
            $tmp['month_all'] = $month_all;
            $tmp['month_h5'] = $month_h5;
            //加入数组
            $tmp['ytd_h5'] = $ytd_h5;
            $tmp['ytd_other'] = $ytd_other;
            $tmp['ytd_mudu'] = $ytd_mudu;
            $tmp['ytd_all'] = $ytd_all;
            $tmp['ytd_all_avg'] = $ytd_all_avg;
            $tmp['ytd_h5_avg'] = $ytd_h5_avg;
            $ccid = DB::table('course')->where('id', '=', $v)->value('is_competitive');
            if($ccid == 1){
                $tmp['is_good'] = "是";
            }else{
                $tmp['is_good'] = "否";
            }
            $course_online = DB::connection('mysql_read')->table('course')->where('id', '=', $v)->value('start_day');
            $months = intval(date('Y', strtotime($week_end)) - intval(date('Y', strtotime($course_online)))) * 12 + intval(date('m', strtotime($week_end))) - intval(date('m', strtotime($course_online))) + 1;
            if($months <= 0){
                $months = 1;
            }

            $now_h5_avg = intval($now_h5 / $months);
            $now_all_edu_avg = intval($now_all_edu / $months);
            $now_other_avg = intval($now_other / $months);

            $tmp['now_months'] = $months;
            \Log::info("上线至今");

            //加入数组
            $tmp['now_all_sign'] = $now_all_sign;
            $tmp['now_h5'] = $now_h5;
            //$tmp['now_other'] = $ytd_other;
            //$tmp['now_mudu'] = $ytd_mudu;
            $tmp['now_all'] = $now_all_edu;  // 教育人次  月均
            $tmp['now_all_edu_avg'] = $now_all_edu_avg;
            $tmp['now_h5_avg'] = $now_h5_avg;
            $tmp['now_other'] = $now_other;
            $tmp['now_other_avg'] = $now_other_avg;
            $tmp['listen_time'] = number_format($sFile['listen_time'] / 60, 2);
            $tmp['min_3'] = $sFile['min_3'];
            $tmp['min_7'] = $sFile['min_7'];
            $tmp['listen_time'] = $sFile['listen_time'];
            $tmp['min_3'] = $sFile['min_3'];
            $tmp['min_7'] = $sFile['min_7'];
            \Log::info("data====start");
            // 是否签约
            $tmp['is_order'] = DB::table('course_detail')->where('cid', $v)->value('is_order');
            \Log::info('签约 ====='.$tmp['is_order']);
            // 拖拽信息和完听率
            $videos = array(136 => 581,144 => 754,147 => 514,150=>1131,171=>841,332=>183,381=>4646,382=>3515,433=>884,383=>3421,384=>3575,451=>416,454=>423,455=>392,456=>379,460=>609,465=>632,469=>694,478=>329,494=>453,495=>469,504=>3100,508=>110,509=>196,510=>165,511=>171,512=>189,513=>214,514=>196,515=>208);                $data = array();
            if(!in_array($v,array_keys($videos))){
                $data['type'] = 1;
                $time = DB::table('course_review')->where('cid',$cid)->value('audio_duration');
            }else{
                $data['type'] = 2;
                $time = $videos[$v];
            }
            $data['from'] = $week_start;
            $data['to'] = $week_end;
            $data['cid'] = $v;
            $data['time'] = $time;
            $data = http_build_query($data);
            $opts = array(
                'http' => array(
                    'method' => "POST",
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                        "Content-length:" . strlen($data) . "\r\n" .
                        "Cookie: foo=bar\r\n" .
                        "\r\n",
                    'content' => $data,
                    'timeout' => 60,
                )
            );
            $cxContext = stream_context_create($opts);
            $sFile = file_get_contents("http://idg-jinjinyuan.tunnel.nibaguai.com/jinjinyuan/cidata-query/part3.php", false, $cxContext);
            $sFile = json_decode($sFile, true);
            if(empty($sFile)){
                $this->failed();
                \Log::error('=======获取听课信息异常=====');
                die();
            }else{
                // 拖拽
                $drag = $sFile['drag'];
                $t11 = $drag[0];// <60s
                $t33 = $drag[1];// 1-3m
                $t55 = $drag[2];// 3-5m
                $t77 = $drag[3];// 5-7m
                $t88 = $drag[4];// >7m


                $drag_num = $sFile['drag_num'];
                $t1 = $drag_num[0];
                $t3 = $drag_num[1];
                $t5 = $drag_num[2];
                $t7 = $drag_num[3];
                $t8 = $drag_num[4];

                // 听课时长细分
                $listen = $sFile['time'];
                $l03 = $listen[0];// <30s
                $l1 = $listen[1];// 30-60s
                $l3 = $listen[2];// 1-3m
                $l7 = $listen[3];// 3-7m
                $l8 = $listen[4];// >7m
                $complete = $listen[5]; // 完听率

                $listen_num = $sFile['time_num'];
                $l03n = $listen_num[0];
                $l1n = $listen_num[1];
                $l3n = $listen_num[2];
                $l7n = $listen_num[3];
                $l8n = $listen_num[4];
                $complete_n = $listen_num[5];

                // 页面停留时长
                $stay = $sFile['stay'];
                $s1 = $stay[0];
                $s1_rate = $stay[1];
                $s3 = $stay[2];
                $s3_rate = $stay[3];
                $s5 = $stay[4];
                $s5_rate = $stay[5];
                $s7 = $stay[6];
                $s7_rate = $stay[7];
                $s8 = $stay[8];
                $s8_rate = $stay[9];
            }

            // 更新本周此数据
            $title = DB::table('course')->where("id", "=", $v)->value('title');
            $data = array();
            $data['cid'] = $v;
            $data['start_day'] = $week_start;
            $data['end_day'] = $week_end;
            $data['title'] = empty($title) ? '' : $title;
            $data['week_other'] = empty($tmp['week_other']) ? 0 : $tmp['week_other'];
            \Log::info('insert weekother ' . $data['week_other']);
            $data['week_mudu'] = empty($tmp['week_mudu']) ? 0 : $tmp['week_mudu'];
            \Log::info('insert week mudu' . $data['week_mudu']);
            $data['month_other'] = empty($tmp['month_other']) ? $week_other : $tmp['month_other'];
            $data['month_mudu'] = empty($tmp['month_mudu']) ? $week_mudu : $tmp['month_mudu'];
            $data['ytd_h5'] = empty($tmp['ytd_h5']) ? $month_h5 : $tmp['ytd_h5'];
            $data['ytd_other'] = empty($tmp['ytd_other']) ? $month_other : $tmp['ytd_other'];
            $data['ytd_mudu'] = empty($tmp['ytd_mudu']) ? $month_mudu : $tmp['ytd_mudu'];
            $data['now_all_sign'] = empty($tmp['now_all_sign']) ? $week_sign : $tmp['now_all_sign'];
            $data['now_all_edu'] = empty($tmp['now_all']) ? $month_all : $tmp['now_all'];
            $data['now_h5'] = empty($tmp['now_h5']) ? $month_h5 : $tmp['now_h5'];
            \Log::info("tmp 数据" . json_encode($tmp));
            $str_arr1 = array($tmp['id'], $tmp['title'], $tmp['start_day'], $tmp['teacher_name'], $tmp['stage'], $tmp['brand'], $tmp['update_brand'], $tmp['tag'], $tmp['week_all'], $tmp['week_h5'], $tmp['week_other'], $tmp['week_mudu'], $week_sign, $tmp['week_sign_online'], $tmp['week_sign_edu_online'], $tmp['week_listen_rate_online'], $tmp['week_sign_offline'], $tmp['week_sign_offline_edu'], $tmp['week_listen_rate_offline'], $tmp['sign_send'], $tmp['sign_send_open'], $tmp['sign_send_rate'], $tmp['send'], $tmp['send_open'], $tmp['send_rate'], $tmp['play'],
                $t1,$t11,$t3,$t33,$t5,$t55,$t7,$t77,$t8,$t88,$l03n,$l03,$l1n,$l1,$l3n,$l3,$l7n,$l7,$l8n,$l8,$complete_n,$complete,$s1,$s1_rate,$s3,$s3_rate,$s5,$s5_rate,$s7,$s7_rate,$s8,$s8_rate,$tmp['listen_time'], $tmp['rate_3'], $tmp['rate_7'],
                $tmp['youzhu'], $tmp['wuzhu'], $tmp['yunqi'], $tmp['m_12'], $tmp['m_24'], $tmp['m_24_'], $tmp['east_rate'], $tmp['center_rate'], $tmp['north_rate'], $tmp['west_rate'], $tmp['south_rate'], $tmp['foreign_rate'],
                $tmp['month_all'], $tmp['month_h5'], $tmp['month_other'], $tmp['month_mudu'], $tmp['ytd_all'], $tmp['ytd_months'], $tmp['ytd_all_avg'],$tmp['ytd_other'], $tmp['ytd_h5'], $tmp['ytd_h5_avg'], $tmp['now_months'], $tmp['now_all_sign'], $tmp['now_all'], $tmp['now_other'], $tmp['now_h5'],$tmp['now_all_edu_avg'], $tmp['share'], $tmp['ask'], $tmp['is_good'],$expire, $tmp['is_order'],
            );
            $file_path = storage_path() . "/WeekData/all_course_week_$end_day.csv";

            $outputBuffer = fopen($file_path, 'a+');
            fputcsv($outputBuffer, $str_arr1);
            fclose($outputBuffer);
        }
        $data = array();
        $data['start_day'] = $start_day;
        $data['end_day'] = $end_day;
        $data['all_course_week_url'] = $file_path;
        //$sql = "delete from week_data_export where end_day ='$week_end'";
        DB::table('week_data_export')
            ->where(['end_day' => $end_day, 'start_day' => $start_day])
            ->delete();
        $id = DB::table('week_data_export')->insertGetId($data);
        DB::update('UPDATE week_data_export SET all_course_week_url = ? WHERE id = ?', [$file_path, $id]);

        // =============================week summary==============================

        \Log::info("start,week_summary" . date('Y-m-d H:i:s'));



        //计算新课程报名数
        $data = array();
        $data['from'] = $week_start;
        $data['to'] = $week_end;
        $data['new'] = json_encode($week_new);
        \Log::info("周报summary发送数据=====" . json_encode($data));
        $data = http_build_query($data);
        $opts = array(
            'http' => array(
                'method' => "POST",
                'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Content-length:" . strlen($data) . "\r\n" .
                    "Cookie: foo=bar\r\n" .
                    "\r\n",
                'content' => $data,
                'timeout' => 60,
            )
        );
        $cxContext = stream_context_create($opts);
        $sFile = file_get_contents("http://idg-jinjinyuan.tunnel.nibaguai.com/jinjinyuan/cidata-query/part2.php", false, $cxContext);
        $sFile = json_decode($sFile, true);
        \Log::info("获取summary数据" . "============" . json_encode($sFile));

        $total = $this->getTotalReg($week_start, $week_end);
        $h5_sign_up =$total;

        $total1 = $this->getTotalReg($week_start, $week_end, "hongbao2016",false);
        $h5_sign_up_online = $total1;

        $total2 = $total - $total1;
        $h5_sign_up_offline = $total2;

        $sql = "select count(id) as total from tplmsgs  where created_at >'$week_start' and created_at<'$week_end'and cid in (".  implode(",", $week_new).")";

        $total = DB::connection('mysql_read')->select($sql)[0]->total;
        $week_new_sign = $total;

        \Log::info('线上线下听课');
        $t11 = 0; // 线上报名且听课
        $t12 = 0; // 线下报名且听课
        $sql = "select  distinct a.uid,a.cid,b.channel from user_events as a left join user as b on a.uid = b.id where a.created_at >'$week_start' and a.created_at<'$week_end' and a.type in ('review_in', 'review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause')";
        $total = DB::connection('mysql_read')->select($sql);
        foreach ($total as $vv) {
            if ($vv->channel == 'hongbao2016') {
                $t12++;
            } else {
                $t11++;
            }
        }
        \Log::info('时间导出成功');

        $sql = "select count(distinct uid) as total from user_events where  created_at >'".$week_start."' and created_at<'".$week_end."' and type in ('review_in', 'review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause')";
        $total2 = DB::connection('mysql_read')->select($sql)[0]->total;
        $t9= $total2;

        $index_pv = $sFile['all_pv'];
        $index_uv = $sFile['all_uv'];
        $page_index_pv = $sFile['page_index_pv'];
        $page_index_uv = $sFile['page_index_uv'];
        $page_index_pv_youzhu = $sFile['page_index_pv_youzhu'];
        $page_index_uv_youzhu = $sFile['page_index_uv_youzhu'];
        $page_index_pv_wuzhu = $sFile['page_index_pv_wuzhu'];
        $page_index_uv_wuzhu = $sFile['page_index_uv_wuzhu'];
        $class_pv = $sFile['class_pv'];
        $class_uv = $sFile['class_uv'];
        $class_pv_youzhu = $sFile['class_pv_youzhu'];
        $class_uv_youzhu = $sFile['class_uv_youzhu'];
        $class_pv_wuzhu = $sFile['class_pv_wuzhu'];
        $class_uv_wuzhu = $sFile['class_uv_wuzhu'];
        //$page_reg_pv = $sFile['page_reg_pv'];
        //$page_reg_uv = isset($sFile['page_reg_uv']) ? $sFile['page_reg_uv'] : 0;
        $people_num_offline = $t12; //
        $people_num_online = $t11;

        \Log::info("获取并得到数组");
        $arr = array('开始日期', '结束日期', '本周总pv', '本周总uv','周首页pv', '有主首页pv', '无主首页pv', '课页pv','有主课页pv','无主课页pv','周首页uv', '有主首页uv', '无主首页uv', '周课页uv','有主课页uv','无主课页uv','周H5推送课数total', '周无主课程推送总数', '周有主推送总数', '本周上线课程推送数','本周总教育人数','周线上受教育人数', '周线上受教育转化率', '周线下受教育人数', '周线下教育转化率');
        $ad = $sFile['ad'];
        $ad_arr = array('帧数', '点击');
        $file_path = storage_path() . "/WeekData/ad_$end_day.csv";
        $outputBuffer = fopen($file_path, 'w+');
        fwrite($outputBuffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($outputBuffer, $ad_arr);
        fclose($outputBuffer);
        if (!empty($ad)) {
            foreach ($ad as $k=>$v) {
                $ad_type = array("========".$k."========");
                $outputBuffer = fopen($file_path, 'a+');
                fputcsv($outputBuffer, $ad_type);
                fclose($outputBuffer);
                foreach ($v as $kk=>$vv) {
                    $ad_position = array("====".$kk."====");
                    $outputBuffer = fopen($file_path, 'a+');
                    fputcsv($outputBuffer, $ad_position);
                    fclose($outputBuffer);
                    foreach ($vv as $kkk=>$vvv){
                        $frame = array($kkk,$vvv['pv'],$vvv['uv']);
                        $outputBuffer = fopen($file_path, 'a+');
                        fputcsv($outputBuffer, $frame);
                        fclose($outputBuffer);
                    }
                }
            }
        }
        $file_path1 = storage_path() . "/WeekData/week_summary_$end_day.csv";
        $outputBuffer = fopen($file_path1, 'w+');
        fwrite($outputBuffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($outputBuffer, $arr);
        fclose($outputBuffer);
        \Log::info('close');
        $week_offline_rate = 0;
        $week_online_rate = 0;
        if ($h5_sign_up_online == 0) {
            $week_online_rate = 0;
        } else {
            $week_online_rate = number_format($people_num_online*100 / $h5_sign_up_online,2)."%";
        }
        if ($h5_sign_up_offline == 0) {
            $week_offline_rate = 0;
        } else {
            $week_offline_rate = number_format($people_num_offline*100 / $h5_sign_up_offline,2)."%";
        }
        $data_arr = array($start_day, $end_day, $index_pv,$index_uv, $page_index_pv, $page_index_pv_youzhu, $page_index_pv_wuzhu, $class_pv,$class_pv_youzhu, $class_pv_wuzhu, $page_index_uv, $page_index_uv_youzhu,$page_index_uv_wuzhu,$class_uv,$class_uv_youzhu,$class_uv_wuzhu, $h5_sign_up, $h5_sign_up_online, $h5_sign_up_offline, $week_new_sign, $t9, $people_num_online, $week_online_rate, $people_num_offline, $week_offline_rate);
        /*
        DB::insert('insert into week_summary (start_day,end_day,index_pv,index_uv,h5_sign_up,h5_sign_up_online,h5_sign_up_offline,
        people_times,people_number,people_times_offline,people_number_offline,large_platform_people_times,community_people_times,
        other,listen_hours,ask_times,week_active,week_active_online,new_member) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',$data_arr);
        */

        $outputBuffer = fopen($file_path1, 'a+');
        fputcsv($outputBuffer, $data_arr);
        // fputcsv($outputBuffer,array("$week_retain_rate"));
        fclose($outputBuffer);
        DB::update('UPDATE week_data_export SET week_summary_url = ? WHERE id = ?', [$file_path1, $id]);
        DB::update('UPDATE week_data_export SET week_new_course_url = ? WHERE id = ?', [$file_path, $id]);
        \Log::info("首页流量统计");
        $data = array();
        $data['from'] = $week_start;
        $data['to'] = $week_end;
        $data = http_build_query($data);
        $opts = array(
            'http' => array(
                'method' => "POST",
                'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Content-length:" . strlen($data) . "\r\n" .
                    "Cookie: foo=bar\r\n" .
                    "\r\n",
                'content' => $data,
                'timeout' => 60,
            )
        );
        $cxContext = stream_context_create($opts);
        $index_arr = array('分类', '当周pv', '当周uv');
        $file_path = storage_path() . "/WeekData/index_$end_day.csv";
        $outputBuffer = fopen($file_path, 'w+');
        fwrite($outputBuffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($outputBuffer, $index_arr);
        fclose($outputBuffer);
        // 首页流量分类 有主金装：liuliang_jin；有主启赋：liuliang_qifu; 无主+有主无品： liulinag_wuzhu
        $types = array('liuliang_jin'=>'有主金装','liuliang_qifu'=>'有主启赋','liuliang_wuzhu'=>'无主');
        foreach(array_keys($types) as $type) {
            $outputBuffer = fopen($file_path, 'a+');
            $type_arr = array("============================$types[$type]========================");
            fputcsv($outputBuffer, $type_arr);
            fclose($outputBuffer);
            $sFile = file_get_contents("http://idg-jinjinyuan.tunnel.nibaguai.com/jinjinyuan/cidata-query/".$type.".php", false, $cxContext);
            $sFile = json_decode($sFile, true);
            $type2 = array('home_tag', 'home_stage', 'home_more','home_course');
            $match = array('home_search' => '搜索',
                'home_stage' => '月龄标签',
                'home_tag' => '主题点击',
                'home_activity_1' => '专家点击',
                'home_activity_2' => '点击活动',
                'home_more' => '点击更多',
                'home_course' => '首页点击的课程');

            $tmp = array();
            $more_type = array('hot' => '最热课程', 'new' => '最新课程', 'recom' => '推荐课程', 'noparam' => "无参数课程");
            if(isset($sFile['home_search'])){
                foreach ($sFile as $k => $v) {
                    $type = $match[$k];
                    $outputBuffer = fopen($file_path, 'a+');
                    $type_arr = array($type, '', '');
                    fputcsv($outputBuffer, $type_arr);
                    fclose($outputBuffer);
                    if (in_array($k, $type2)) {
                        $tmp[$type] = array();
                        switch ($k) {
                            case 'home_more':
                                foreach ($v as $kk => $vv) {
                                    if (in_array($kk, array_keys($more_type))) {
                                        $kv = $more_type[$kk];
                                        $tmp[$type][$kv] = $vv;
                                        $outputBuffer = fopen($file_path, 'a+');
                                        $type_arr = array($kv, $vv['pv'], $vv['uv']);
                                        fputcsv($outputBuffer, $type_arr);
                                        fclose($outputBuffer);
                                    } else {
                                        $tmp[$type][$kk] = $vv;
                                        $outputBuffer = fopen($file_path, 'a+');
                                        $type_arr = array($kk, $vv['pv'], $vv['uv']);
                                        fputcsv($outputBuffer, $type_arr);
                                        fclose($outputBuffer);
                                    }
                                }
                                break;
                            case 'home_course':
                                foreach ($v as $kk => $vv) {
                                    if (is_numeric($kk)) {
                                        $title = DB::connection('mysql_read')->table('course')->where('id', $kk)->select('title')->get()[0]->title;
                                        $tmp[$type][$title] = $vv;
                                        $outputBuffer = fopen($file_path, 'a+');
                                        $title = $kk . " " . $title;
                                        $type_arr = array($title, $vv['pv'], $vv['uv']);
                                        fputcsv($outputBuffer, $type_arr);
                                        fclose($outputBuffer);
                                    } else {
                                        $tmp[$type][$kk] = $vv;
                                        $outputBuffer = fopen($file_path, 'a+');
                                        $type_arr = array($kk, $vv['pv'], $vv['uv']);
                                        fputcsv($outputBuffer, $type_arr);
                                        fclose($outputBuffer);
                                    }
                                }
                                break;
                            default:
                                foreach ($v as $kk => $vv) {
                                    if (is_numeric($kk)) {
                                        $tag = DB::table('tags')->where('id', $kk)->select('name')->get()[0]->name;
                                        $tmp[$type][$tag] = $vv;
                                        $outputBuffer = fopen($file_path, 'a+');
                                        $type_arr = array($tag, $vv['pv'], $vv['uv']);
                                        fputcsv($outputBuffer, $type_arr);
                                        fclose($outputBuffer);
                                    } else {
                                        $tmp[$type][$kk] = $vv;
                                        $outputBuffer = fopen($file_path, 'a+');
                                        $type_arr = array($kk, $vv['pv'], $vv['uv']);
                                        fputcsv($outputBuffer, $type_arr);
                                        fclose($outputBuffer);
                                    }
                                }
                                break;
                        }
                    } else {
                        $tmp[$type] = $v;
                        foreach ($v as $kk => $vv) {
                            $outputBuffer = fopen($file_path, 'a+');
                            $type_arr = array($kk, $vv['pv'], $vv['uv']);
                            fputcsv($outputBuffer, $type_arr);
                            fclose($outputBuffer);
                        }
                    }
                }
            }else{
                $outputBuffer = fopen($file_path, 'a+');
                $type_arr = array('生成数据失败，请重试');
                fputcsv($outputBuffer, $type_arr);
                fclose($outputBuffer);
            }

        }

        // 使用 diversion 字段存放首页流量数据
        DB::update('UPDATE week_data_export SET week_diversion_url = ? WHERE id = ?', [$file_path, $id]);

        // 自动下行统计
        $xx = (new WxWyeth())->getWxClassSendlog($week_start,$week_end);
        $num_arr = array();
        foreach ($xx['msg'] as $item) {
            //var_dump($item);
            $num_arr[$item['cid']]=$item['numbers'];
        }
        $ciataQuery = new CIDataQuery();
        $cid_array = array();
        $begin_time = strtotime($week_start);
        $end_time = strtotime($week_end);
        try{
            $result = @$ciataQuery->groupBy($begin_time, $end_time, "end", "wyeth_channel", "event_arg", "pv", "buildin", "descending", 1000, null,["__duration >= 0"]);
            $pv = 0;
            $uv = 0;
            $path_xxjp = storage_path()."/WeekData/xxjp_$end_day.csv";
            $arr = array('ID','课程名','下行数量','pv','uv');
            $fp = fopen($path_xxjp,'w+');
            fputcsv($fp,$arr);
            fclose($fp);
            foreach ($result as $item){
                $event = $item['event'];
                $label = $event['wyeth_channel'];
                if (strpos($label, 'xxjp') !== false){
                    echo "{$label},{$event['pv']},{$event['uv']}\n";
                    $cid = str_split($label,5)[1];
                    $cid_array[] = $cid;
                    $name = DB::connection('mysql_read')->table('course')->where('id',$cid)->value('title');
                    echo $name;
                    $pv += $event['pv'];
                    $uv += $event['uv'];
                    if(isset($num_arr[$cid])){
                        $num = $num_arr[$cid];
                    }else{
                        $num = 0;
                    }
                    $arr = array($cid,$name,$num,$event['pv'],$event['uv']);
                    $fp = fopen($path_xxjp,'a+');
                    fputcsv($fp,$arr);
                    fclose($fp);
                }
            }
            foreach ($num_arr as $a => $b){
                if(in_array($a,$cid_array)){
                    continue;
                }else{
                    $name = DB::connection('mysql_read')->table('course')->where('id',$a)->value('title');
                    if(isset($num_arr[$a])){
                        $num = $num_arr[$a];
                    }else{
                        $num = 0;
                    }
                    $arr = array($a,$name,$num,0,0);
                    $fp = fopen($path_xxjp,'a+');
                    fputcsv($fp,$arr);
                    fclose($fp);
                }
            }

        }catch (\Exception $e){
            $path_xxjp = storage_path()."/WeekData/xxjp_$end_day.csv";
            $arr = array('ID','课程名','下行数量','pv','uv');
            $fp = fopen($path_xxjp,'w+');
            fputcsv($fp,$arr);
            fclose($fp);
        }
        DB::update('UPDATE week_data_export SET signup_by_channel_url = ? WHERE id = ?', [$path_xxjp, $id]);

        file_put_contents($this->state_path, '0');
        \Log::info("all done");
    }

    public function getTotalReg($start,$end,$channel='',$isEqual=true){
        if($channel){
            if($isEqual){
                $sql = "select count(a.id) as total from tplmsgs as a left join user as b on a.openid = b.openid where a.created_at >'$start' and a.created_at<'$end' and b.channel = '$channel' ";
            }else{
                $sql = "select count(a.id) as total from tplmsgs as a left join user as b on a.openid = b.openid where a.created_at >'$start' and a.created_at<'$end' and b.channel != '$channel'";
            }
        }else{
            $sql = "select count(a.id) as total from tplmsgs as a where a.created_at >'$start' and a.created_at<'$end'";
        }
        $total = DB::connection('mysql_read')->select($sql)[0]->total;
        return $total;
    }
    public function failed()
    {
        \Log::ERROR('生成文件失败');
        file_put_contents($this->state_path,'0');
    }
}

