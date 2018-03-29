<?php

namespace App\Jobs;

use App\CIService\CIDataOpenApi;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\CIService\CIDataQuery;
use App\Models\CoursePush;


class getTplData extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $start;
    protected $end;
    protected $cids;
    protected $type;
    protected $tpl_1;
    protected $auto;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start,$end,$cids,$type,$tpl_1,$auto)
    {
        $this->start=$start;
        $this->end=$end;
        $this->cids=$cids;
        $this->type=$type;
        $this->tpl_1=$tpl_1;
        $this->auto=$auto;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info("=======start tplmsgs============");
       $cids=$this->cids;
       $start_date=$this->start;
       $end_date=$this->end;
       $type = $this->type;
       $tpl_1 = $this->tpl_1;
       $auto = $this->auto;
       \Log::info("是否选择模板1+1: ".$tpl_1);
       $cids = array();
       // 是否需要自动生成
        if(!$auto){
            $cids =explode(',',$cids);
        }else{
            $sql = "select distinct cid from user_enroll_push WHERE push_time>='$start_date' and push_time<'$end_date'";
            $data = DB::select($sql);
            foreach ($data as $item) {
                $cids[] = $item->cid;
            }
        }
        $path= storage_path()."/WeekData/Tpl.csv";
        $fp = fopen($path, "w+");
        $arr= array('id','老师','标题','推送时间','推送总数','送达','送达率','教育人数','转化率','wording');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp,$arr);
        fclose($fp);
        $state_path = storage_path()."/WeekData/tpl_state.txt";
        file_put_contents($state_path, '1');
        $tpl_info=storage_path()."/WeekData/tpl_info.txt";
        $str = $start_date."~".$end_date;
        file_put_contents($tpl_info,$str);
        if($type == 0){
            $condition = '(type = 1 or type = 20)';
        }elseif($type == 1){
            $condition = 'type=1';
        }elseif($type == 4){
            $condition = 'type=20';
        }
        foreach($cids as $k=>$v) {
            $cid = $v;
            $course = DB::connection('mysql_read')->table('course')->where('id',$cid)->get();
            $title = $course[0]->title;
            $teacher = $course[0]->teacher_name;
            $mark = $course[0]->notify_remark;
            $push_start = $start_date;
            $push_end = $end_date;
            \Log::info("id ".$cid);
            $sql = "SELECT count(*) as total FROM `tplmsgs` WHERE cid=$cid and created_at>'$push_start' AND created_at<'$push_end' and $condition";
            $total1 = DB::select($sql)[0]->total;
            $sql = "SELECT count(*) as total FROM `tplmsgs` WHERE cid=$cid and created_at>'$push_start' AND created_at<'$push_end' and status = 1 and $condition";
            $total2 =DB::select($sql)[0]->total;

            //$sql = "select count(DISTINCT uid) as total from user_events where cid = $cid and created_at >'$push_start' and created_at<'$push_end' and type in ('review_in', 'review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause')";
            //$total4 = DB::select($sql)[0]->total;

            $cidata = new CIDataQuery();
            $begin_time = date('Y-m-d H:i:s', strtotime($push_start));
            $end = date('Y-m-d H:i:s', strtotime($push_end)); // 一般统计三天内打开率
            $open = @$cidata->timeseries($begin_time, $end, "all", "open_tplmsg", null, ["cid = $cid","template_id = 1"]);
            $open2 = @$cidata->timeseries($begin_time, $end, "all", "open_tplmsg", null, ["cid = $cid","template_id = 4"]);
            if(empty($open) || !isset($open['data'][0])){
                $sql = "select count(DISTINCT uid) as total from user_events where cid = $cid and created_at >='$push_start' and created_at<'$push_end' and type in ('review_in', 'review_audio_begin', 'review_audio_pause', 'review_video_begin', 'review_video_pause')";
                $total4 = DB::select($sql)[0]->total;
            }else{
                $total4 = $open['data'][0]['uv'] + $open2['data'][0]['uv'];
            }
            $t9 = $total4; //教育人数
            $e_rate = 0;
            $s_rate = 0;
            if($total1>0){
                $s_rate=number_format(100*$total2/$total1,2)."%";
            }
            if($total2>0){
                $e_rate=number_format(100*$t9/$total2,2)."%";
            }
            $arr=array($cid,$teacher,$title,$push_start,$total1,$total2,$s_rate,$t9,$e_rate,$mark);
            $fp = fopen($path, "a+");
            fputcsv($fp,$arr);
            fclose($fp);
        }
        // 回顾推荐推送
        $tmp_cidata = new CIDataQuery();
        $hgtj_push = DB::select("select count(*) as total from tplmsgs WHERE created_at>'$start_date' and created_at<'$end_date' and type=2")[0]->total;
        $hgtj_success = DB::select("select count(*) as total from tplmsgs WHERE created_at>'$start_date' and created_at<'$end_date' and type=2 and status=1")[0]->total;
        $hgtj_open = @$tmp_cidata->timeSeries(strtotime($start_date),strtotime($end_date),"all","open_tplmsg",null,["template_id = 2"]);
        if(isset($hgtj_open)){
            $open_uv = $hgtj_open['data'][0]['uv'];
        }else{
            $open_uv = 0;
        }
        $hgtj_s_rate = 0;
        $hgtj_e_rate = 0;
        if($hgtj_push){
            $hgtj_s_rate = number_format(100*$hgtj_success/$hgtj_push,2)."%";
        }else{
            $hgtj_e_rate = number_format(100*$open_uv/$hgtj_success,2)."%";
        }
        $arr=array('','','回顾推荐',$start_date,$hgtj_push,$hgtj_success,$hgtj_s_rate,$open_uv,$hgtj_e_rate,'');
        $fp = fopen($path, "a+");
        fputcsv($fp,$arr);
        fclose($fp);
        if($tpl_1){
            $list = DB::table('course_push')->where('push_time','>',$start_date)->where('push_time','<',$end_date)->where('type','<>','0')
                ->orderBy('push_time', 'asc')->get();
            foreach ($list as $item) {
                //特殊推送类型
                $item->course_name = CoursePush::getTypeArray()[$item->type];
            }
            foreach ($list as $item) {
                $type = $item->type;
                $begin_time = $item->push_time;
                $cidata = new CIDataQuery();
                $end_time = date('Y-m-d H:i:s', strtotime($begin_time) + 86400 * 1);
                $end = date('Y-m-d H:i:s', strtotime($begin_time) + 86400 * 3); // 一般统计三天内打开率
                $data = DB::connection('mysql_read')->table('tplmsgs')
                    ->where('type', $type)
                    ->where('created_at', '>', $begin_time)
                    ->where('created_at', '<', $end_time);
                $all = $data->count();
                $defeat = $data->where('status', 0)->count();
                $success = $all - $defeat;
                $channel = CoursePush::getTypeChannel()[$type];
                $channel_id = '';
                if ($channel == 'wxtpl_night' || $channel == 'wxtpl_fuli_draw' || $channel == 'wxtpl_fuli_dtc') {
                    $end = date('Y-m-d H:i:s', strtotime($begin_time) + 86400 * 1); // 这个只统计一天
                }
                // 使用CIData统计
                if($channel !== 'wxtpl_fuli_dtc'){
                    $open_num = @$cidata->timeseries($begin_time, $end, "all", "open_tplmsg", null, ["wyeth_channel = $channel"])['data'][0];
                }else{
                    $open_num = @$cidata->timeseries($begin_time, $end, "all", "fuli_link", null,null)['data'][0];
                }

                // 使用数据库统计
//                 if($channel == 'wxtpl_night'){
//                     $channel_id = '14';
//                }elseif($channel == 'wxtpl_lose'){
//                     $channel_id = '15';
//                 }elseif($channel == 'wxtpl_new'){
//                     $channel_id ='16';
//                 }elseif($channel == 'wxtpl_not_weiketang'){
//                      $channel_id = '17';
//            }
                //$open_num_uv = DB::connection('mysql_read')->table('tplmsgs')->where('created_at','>',$begin_time)->where('created_at','<',$end)->where('type',$channel_id)->count();
                if(!empty($open_num)){
                    $open_num_uv = $open_num['uv'];
                }else{
                    $open_num_uv = 0;
                }
                if($all>0){
                    $success_rate = number_format($success*100/$all,2)."%";
                }else{
                    $success_rate = 0;
                }
                if(is_numeric($open_num_uv) && $success>0){
                    $open_rate = number_format(100*$open_num_uv/$success,2)."%";
                }else{
                    $open_rate ='0';
                }
                $arr = array('',$item->course_name,$item->push_time,'',$all,$success,$success_rate,$open_num_uv,$open_rate);
                $fp = fopen($path,'a+');
                fputcsv($fp,$arr);
                fclose($fp);
            }
        }
        \Log::info("=======tplmsgs DONE!!!============");
        file_put_contents($state_path,'0');
    }
    public function failed()
    {
        \Log::ERROR('生成文件失败!');
        $state_path = storage_path()."/WeekData/tpl_state.txt";
        file_put_contents($state_path, '0');
    }
}
