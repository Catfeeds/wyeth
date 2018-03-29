<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Response;

class CourseExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:export {method}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'print last week course or review';

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
        $method = $this->argument('method');
        if ($method == 'course') {
            //课程记录打印
            $beginLastweek = date('Y-m-d H:i:s', mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y')));
            $endLastweek = date('Y-m-d H:i:s', mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y')));
            $filename = date('Y').'-'.date('W').'-course.csv';
            $path = storage_path() . "/excel/".$filename;
            $fileHandle = fopen($path, 'w');
            $item = [
                '听课id',
                '用户openid',
                '首次进入',
                '听课来源',
                '是否分享直播页',
                '分享被点击次数',
                '有主无主',
                '用户性别',
                '微信国家',
                '微信省份',
                '微信城市',
                '惠氏省份',
                '惠氏城市',
                '手机号码',
                '用户设备',
                '预产期或宝宝生日'
            ];
            $this->str_putcsv($fileHandle, $item);
            $query = DB::table('course_stat')
                ->Join('user', 'user.id', '=', 'course_stat.uid')
                ->where('course_stat.listen_time', '>', 0)
                ->whereBetween('course_stat.in_class_time', [$beginLastweek , $endLastweek])
                ->select('course_stat.id','course_stat.cid', 'user.openid', 'course_stat.in_class_time', 'course_stat.channel', 'course_stat.share_sign_page', 'course_stat.share_sign_page_clicks', 'user.crm_hasShop', 'user.sex', 'user.country', 'user.province', 'user.city', 'user.crm_province', 'user.crm_city', 'user.mobile', 'course_stat.device', 'user.baby_birthday')
                ->orderBy('course_stat.id', 'asc');;
            $perPage = 1000;
            $maxId = 0;
            while($items = $query->where('course_stat.id', '>', $maxId)->limit($perPage)->get()){
                $lastItem = last($items);
                $maxId = $lastItem->id;
                foreach ($items as $item) {
                    $item = (array)$item;
                    unset($item['id']);
                    $this->str_putcsv($fileHandle, $item);
                }
            }
            if(is_resource($fileHandle)){
                fclose($fileHandle);
                $this->info('Print Course Of Last Week Success!');
            }
        } elseif ($method == 'review') {
            //回顾记录打印
            $beginLastweek = date('Y-m-d H:i:s', mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y')));
            $endLastweek = date('Y-m-d H:i:s', mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y')));
            $filename = date('Y').'-'.date('W').'-review.csv';
            $path = storage_path() . "/excel/".$filename;
            $fileHandle = fopen($path, 'w');
            $item = [
                '课程id',
                'openid',
                '最后进入回顾时间',
                '听课来源',
                '是否分享回顾页',
                '分享的回顾页被点击的次数',
                '有主无主',
                '性别',
                '微信国家',
                '微信省份',
                '微信城市',
                '惠氏省份',
                '惠氏城市',
                '手机号',
                '用户设备',
                '预产期或宝宝生日'
            ];
            $this->str_putcsv($fileHandle, $item);
            $query = DB::table('course_stat')
                ->join('user', 'user.id', '=', 'course_stat.uid')
                ->whereBetween('course_stat.in_review_time', [$beginLastweek , $endLastweek])
                ->select('course_stat.id', 'course_stat.cid', 'user.openid','course_stat.in_review_time','course_stat.channel','course_stat.share_review_page','course_stat.share_review_page_clicks','crm_hasShop','user.sex','user.country', 'user.province', 'user.city', 'user.crm_province', 'user.crm_city', 'user.mobile','course_stat.device','user.baby_birthday')
                ->orderBy('course_stat.id', 'asc');
            $perPage = 1000;
            $maxId = 0;
            while($items = $query->where('course_stat.id', '>', $maxId)->limit($perPage)->get()){
                $lastItem = last($items);
                $maxId = $lastItem->id;
                foreach ($items as $item) {
                    $item = (array)$item;
                    unset($item['id']);
                    $this->str_putcsv($fileHandle, $item);
                }
            }
            if(is_resource($fileHandle)){
                fclose($fileHandle);
                $this->info('Print Review Of Last Week Success!');
            }
        }
    }

    private function str_putcsv($fileHandle, $input, $delimiter = ',', $enclosure = '"')
    {
        $item = [];
        foreach ($input as $v) {
            $item[] = mb_convert_encoding($v, 'GB18030');
        }
        $fp = $fileHandle;
        fputcsv($fp, $item, $delimiter, $enclosure);
    }
}
