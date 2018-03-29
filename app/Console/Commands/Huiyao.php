<?php

namespace App\Console\Commands;

use App\CIService\CIDataQuery;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Huiyao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'huiyao:xxjp {action} {pv=0} {uv=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '慧摇下行数据统计 export pv uv';

    private $pv;
    private $uv;

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
        $this->pv = $this->argument('pv');
        $this->uv = $this->argument('uv');

        $this->info('start');
        if ($action == 'init'){
            $this->init();
        }elseif ($action == 'update'){
            $this->update();
        }elseif ($action == 'export'){
            $this->export();
        }else{
            $this->warn('action 不合法');
        }
        $this->info('end');
    }

    //定时任务,慧摇每天8点发送昨天的数据,9点更新昨天线上报名人数
    private function update(){
        $cidata = new CIDataQuery();
        $yesterday = date("Y-m-d",strtotime("-1 day"));
        $data = DB::table('huiyao_xxjp')
            ->where('t_date', $yesterday)
            ->first();
        $pv = 0;
        $uv = 0;
        // 使用CIData统计pv、uv
        $begin_time = strtotime($yesterday);
        $end_time = $begin_time + 86400;
        $result = $cidata->groupBy($begin_time, $end_time, "class", "label", "event_arg", "pv", "buildin", "descending", 1000);
        foreach($result as $item){
            if(!isset($item['pv'])){
                break;
            }
            $channel = $item['label'];
            if(strstr($channel,'xxjp')){
                $pv += $item['pv'];
                $uv += $item['uv'];
            }
        }
        if ($data){
            $sign_num = $this->getSignNum($yesterday);
            DB::table('huiyao_xxjp')
                ->where('t_date', $yesterday)
                ->update([
                    'sign_num' => $sign_num,
                    'pv' => $pv,
                    'uv' => intval($uv),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
    }

    //导出表格数据
    private function export(){
        if ($this->pv && $this->uv){
            //参数有昨天pv,uv的话存入数据库,然后导出数据
            $yesterday = date("Y-m-d",strtotime("-1 day"));
            $data = DB::table('huiyao_xxjp')
                ->where('t_date', $yesterday)
                ->first();
            if ($data){
                DB::table('huiyao_xxjp')
                    ->where('t_date', $yesterday)
                    ->update([
                        'pv' => $this->pv,
                        'uv' => $this->uv,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
        }

        $all = DB::table('huiyao_xxjp')
            ->orderBy('t_date')
            ->get();
        $cell_data = [
            ['日期', '线下报名人数', '图文推送总人数', '图文推送总条数', '打开pv', '打开uv', '打开率(pv/总条数)
', 'uv/总人数']
        ];
        foreach ($all as $item){
            $cell_data[] = [
                date('Y-m-d', strtotime($item->t_date)),
                $item->sign_num,
                $item->push_num,
                $item->push_count ? $item->push_count : '',
                $item->pv,
                $item->uv,
                $item->push_count ? round(($item->pv / $item->push_count)*100, 2).'%' : '',
                $item->uv ? round(($item->uv / $item->push_num)*100, 2).'%' : ''
            ];
        }
        Excel::create(date('Y-m-d').'惠摇下行数据',function ($excel) use ($cell_data){
            $excel->sheet('index', function ($sheet) use ($cell_data){
                $sheet->rows($cell_data);
            });
        })->store('xls')->export('xls');

    }

    //把之前的数据初始化一下
    private function init(){

        $all = DB::table('huiyao_xxjp')
            ->where('t_date', '>', '0000-00-00 00:00:00')
            ->orderBy('id')
            ->get();
        foreach ($all as $item){
            $t_date = $item->t_date;
            $sign_num = $this->getSignNum($t_date);
            DB::table('huiyao_xxjp')
                ->where('id', $item->id)
                ->update([
                    'sign_num' => $sign_num,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
        $this->info('初始化报名人数结束');

        $data = [
            ['date'=>'2017-07-31', 'pv'=>164, 'uv'=>152],
            ['date'=>'2017-08-01', 'pv'=>139, 'uv'=>130],
            ['date'=>'2017-08-02', 'pv'=>93,  'uv'=>79],
            ['date'=>'2017-08-03', 'pv'=>137, 'uv'=>127],
            ['date'=>'2017-08-04', 'pv'=>190, 'uv'=>163],
            ['date'=>'2017-08-05', 'pv'=>161, 'uv'=>147],
            ['date'=>'2017-08-06', 'pv'=>195, 'uv'=>179],
            ['date'=>'2017-08-07', 'pv'=>171, 'uv'=>156],
            ['date'=>'2017-08-08', 'pv'=>175, 'uv'=>163],
        ];
        foreach ($data as $item){
            DB::table('huiyao_xxjp')
                ->where('t_date', $item['date'].' 00:00:00')
                ->update([
                    'pv' => $item['pv'],
                    'uv' => $item['uv']
                ]);
        }
        $this->info('初始化pv uv');
    }

    //获取某一条的报名人数
    private function getSignNum($start){
        $end = date('Y-m-d H:i:s', strtotime($start) + 24*3600);
        $sign_num = DB::connection('mysql_read')
            ->table('user_course')
            ->where('channel', 'hongbao2016')
            ->where('created_at', '>', $start)
            ->where('created_at', '<', $end)
            ->count(DB::raw('DISTINCT uid'));
        return $sign_num;
    }
}
