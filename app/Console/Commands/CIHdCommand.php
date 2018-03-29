<?php

namespace App\Console\Commands;

use App\CIService\Hd;
use App\Jobs\SendTemplateMessage;
use App\Models\User;
use App\Services\WxWyeth;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CIHdCommand extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cihd {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '中台活动';


    private $aid = 395;

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
        switch ($action)
        {
            case 'account':
                $this->account();
                break;
            case 'export':
                $this->export();
                break;
            case 'push':
                $this->push();
                break;
            case 'draw':
                $this->draw();
                break;
            case 'spring':
                $this->spring();
                break;
            default:
                $this->warn('action 不合法');
        }
        $this->info('end');
    }

    //给抱团成功的人,领取付费课程,发送模板消息
    private function account(){
        $hd = new Hd();
        $start = date('Y-m-d 19:00:00', strtotime('-1 day'));
        $end = date('Y-m-d 23:59:59');
        $page = 1;
        $limit = 1000;

        $accounts = [];
        //获取参团成功的人
        do{
            $res = $hd->joinAccounts($this->aid, $start, $end, $page, $limit);
            if ($res['ret'] == -1){
                \Log::error(__FUNCTION__, $res);
                exit();
            }

            $data = $res['data'];
            $accounts = array_merge($accounts, $data);
            $total = $res['total'];
            $page++;

        }while($total > ($page-1)*$limit);

        if (count($accounts) == 0){
            \Log::info('没有参团成功的人啦');
            exit();
        }

        $params = [
            'title' => "亲爱的妈妈：\n\t恭喜你抱团成功，魔栗妈咪学院为你献上四节聪明宝宝养成课，快戳开消息领取吧。记得常来听课，持续修炼哦！",         //预约标题
            'content' => '',     //预约人
            'odate' => '聪明宝宝养成课',         //预约项目
            'address' => date('Y-m-d'),     //预约时间
            'remark' => '点击查看详情',       //预约备注
            'url' => config('app.url') . "/mobile/columnActivity?_hw_c=hd{$this->aid}"
        ];

        foreach ($accounts as $account){
            $user = User::where('account_id', $account)->first();
            if (!$user){
                continue;
            }

            //领取付费课程
            $user_identify = DB::table('user_identify')
                ->where('uid', $user->id)
                ->first();
            if (!$user_identify){
                DB::table('user_identify')
                    ->insert([
                        'uid' => $user->id,
                        'aid' => $this->aid,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }

            //发模板消息
            $params['content'] = $user->nickname;
            $this->dispatch(new SendTemplateMessage($params, $user->openid, 1, 5));
        }
    }

    //导出中奖用户名单
    private function export(){
        $hd = new Hd();
        $res = $hd->actResult($this->aid, 'reward', 1, 1000);

        $reward = [];
        $prizes = $res['prizes'];
        foreach ($prizes as $item){
            $reward[$item['id']] = $item['level'];
        }

        $cell_data = [
            ['openid', '昵称', '奖品', '邮寄姓名', '邮寄电话', '邮寄地址', '参团人数']
        ];


        $list = $res['list'];
        foreach ($list as &$account){
            $user = DB::connection('mysql_read')
                ->table('user')
                ->where('account_id', $account['account_id'])
                ->first();
            if (!$user){
                continue;
            }
            $account['openid'] = $user->openid;
            $account['nickname'] = $user->nickname;
            $account['level'] = $reward[$account['u_reward']];
            $cell_data[] = [
                $account['openid'],
                $account['nickname'],
                $account['level'],
                $account['u_name'],
                $account['u_phone'],
                $account['u_address'],
                $account['point']
            ];
        }


        Excel::create(date('Y-m-d').'活动用户中奖名单',function ($excel) use ($cell_data){
            $excel->sheet('index', function ($sheet) use ($cell_data){
                $sheet->rows($cell_data);
            });
        })->store('xls')->export('xls');
    }

    //推送转转乐未填写收货地址的用户
    private function push(){
        ini_set('memory_limit','256M');

        $file_path = '/opt/ci123/www/wyeth_dev/wyeth_xj/storage/exports/openid_zzl.txt';

        if (!file_exists($file_path)){
            $this->exitError('文件不存在');
        }
        $content = file_get_contents($file_path);
        if (!$content){
            $this->exitError('文件没有内容');
        }

        $account_list = explode("\n", $content);
        $account_id_count = 0;
        $openid_arr = [];
        foreach ($account_list as $k => $v) {
            $v = trim($v);
            if ($v && strlen($v) > 10) {
                $account_id_count ++;
                $user = User::where('openid', $v)->first();
                if ($user){
                    $openid_arr[] = $user->openid;
                }
            }
        }
//        $openid_arr = [
//            'owtN6jkVHmd-ctim1pBNtWSqmXkU',
////            'owtN6jgs-xifl5tKD1NyUldYtlKw',
////            'owtN6joYbMfyqXcjfIChUttHmXuk',
//        ];

        $count = count($openid_arr);
        $confirm = "推送 openid路径:{$file_path} 推送人数:{$count} account_id人数:{$account_id_count}";
        if (!$this->confirm($confirm . '确定吗?')) {
            exit();
        }

        $params = [
            'title' => "亲爱的麻麻，您在魔栗转转乐上拿到的好礼竟还没有填写收货地址，请快手来补！\n",
            'content' => '转转乐中奖信息确认',
            'odate' => date('Y-m-d'),
            'address' => '',
            'remark' => "\n来魔栗妈咪学院坚持听课修炼，干货和MQ在手，转转乐好礼天天有！",
            'url' => config('app.url') . '/mobile/hd/draw?from=msg'
        ];

        $success = $fail = 0;
        foreach ($openid_arr as $u) {
//            $params['openid'] = $u;
//            $wxWyeth = new WxWyeth();
//            $res = $wxWyeth->pushpushCustomMessage($params, 6, false);
            $this->dispatch(new SendTemplateMessage($params, $u, 1, 6, false));
//            if ($res == 1){
//                $success ++;
//            }else{
//                $fail ++;
//            }
        }
        echo "总推送：{$count} 成功：{$success} 失败：{$fail}";
    }
    private function exitError($error){
        $this->error($error);
        exit();
    }

    //导出转转乐中奖名单
    public function draw()
    {
        $item_id = 177; //奖品库id
        $end = '2018-02-28'; //截止日期
        $file_name = "截止{$end}中奖{$item_id}";
        $hd = new Hd();
        $params = [
//            'reward_status' => 'NEED_INFO',
            'item_id' => $item_id,
            'send_time<' => date('Y-m-d', strtotime($end) + 86400),
            'send_time>' => '2018-02-01',
            'limit' => 300
        ];
        $res = $hd->query('hs_user_reward', $params);
        $data = $res['data'];
        $result = [
            ['account_id','邮寄电话','邮寄姓名','邮寄地址','备注','openid','昵称','宝宝生日']
        ];
        //没填收货地址的
        $no_address = [];

        foreach ($data as $item){
            $user = User::where('account_id', $item['account_id'])->first();
            $result[] = [
                $item['account_id'],
                $item['u_phone'],
                $item['u_name'],
                $item['u_city'] . $item['u_address'],
                $item['u_remark'],
                $user->openid,
                $user->nickname,
                $user->baby_birthday
            ];
            if (!$item['u_phone']){
                $no_address[] = $item['account_id'];
            }
            $reward_info = json_decode($item['reward_info'], true);
            $item_name = $reward_info['name'];
        }
        $file_name .= $item_name;

        Excel::create($file_name, function ($excel) use ($result){
            $excel->sheet('index', function ($sheet) use ($result){
                $sheet->rows($result);
            });
        })->store('xls');

        //下载文件
        echo "\nscp wyeth:/opt/ci123/www/wyeth_dev/wyeth_xj/storage/exports/{$file_name}.xls ~/Downloads/\n";

        var_dump($no_address);
    }

    // 早春课推送
    private function spring()
    {
        $aid = 5;
        $day = date('d') -4;

        $path = "/opt/ci123/www/wyeth_dev/wyeth_xj/storage/exports/spring_openid_$day.txt";

        $test_path = "/opt/ci123/www/wyeth_dev/wyeth_xj/storage/exports/test_openid.txt";

        if (!file_exists($path)){
            return;
        }
        $fp = fopen($path, "r");
        $i = 0;
        while(!feof($fp)) {
            $line = fgets($fp);
            $openid = trim($line);
            if (!($openid && strlen($openid) > 10 && strlen($openid) < 60)) {
                continue;
            }
            $i += 1;
            $title = "  怀孕体重一定会飙升30斤？产后腰上“游泳圈”怎么都甩不掉？一起来看看今天的魔栗指南《“准辣妈”最重要的tips》,生完娃也能不“月半”，做辣妈超简单~\n";
            $content = "魔栗孕育指南之为宝宝，立志做辣妈";
            $params = [
                'title' => $title,
                'content' => $content,
                'odate' => date('Y-m-d'),
                'address' => '魔栗妈咪学院',
                'remark' => '点击立即查看',
                'url' => config('app.url') . "/mobile/hd?aid=$aid"
            ];
            $this->dispatch(new SendTemplateMessage($params, $openid, 4, 1, false));
        }
        \Log::info("共推送： ".$i."条活动模板消息");
    }
}
