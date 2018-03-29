<?php

namespace App\Console\Commands;

use App\CIService\Hd;
use App\Helpers\WyethUtil;
use App\Jobs\CreateTemplateMessageByOpenid;
use App\Jobs\SendTemplateMessage;
use App\Jobs\SendTemplateMessageByOpenid;
use App\Jobs\Test as TestJob;
use App\Models\Course;
use App\Models\CoursePush;
use App\Models\CourseReview;
use App\Models\CourseTag;
use App\Models\Invitation;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserMq;
use App\Services\Crm;
use App\Services\Email;
use App\Services\MqService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;

class TestQueue extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        ini_set('memory_limit', '500M');
        $file = '/opt/ci123/www/wyeth_dev/wyeth_xj/storage/users/2018-01-21~03-21jinzhuangwuzhucourse.txt';

        $this->sendOpenid($file, 499);
        exit();
    }


    //指定openid文件发模板消息
    private function sendOpenid($file_path, $cid, $template_id = 4)
    {
        ini_set('memory_limit', '220M');

        $content = file_get_contents($file_path);
        if (!$content) {
            $this->error('文件路径错误');
            exit;
        }
        $course = Course::find($cid);
        if (!$course) {
            $this->error('课程不存在');
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

        $count = count($openidArr);
        $confirm = "推送cid:{$cid} openid路径:{$file_path} 推送人数:{$count} template_id:{$template_id}";
        if (!$this->confirm($confirm . '确定吗?')) {
            exit();
        }

        $c = 0;

        //随机推。
        $random_cid = [499, 436];

        $openidsChunks = array_chunk($openidArr, 500);
        if ($openidsChunks) {
            foreach ($openidsChunks as $openidArr) {
                $cid = $random_cid[array_rand($random_cid)];
                $this->dispatch(new CreateTemplateMessageByOpenid($cid, $openidArr, $template_id, true));
                $c++;
            }
        }
        $this->info($c);
    }

    private function testQueue()
    {
        $params = [
            'data' => date('Y-m-d H:i:s')
        ];
        $job = (new TestJob($params))->delay(3600 * 48);
        $this->dispatch($job);
    }


    /**
     * 模板消息发送
     * @return [type] [description]
     */
    private function tplSend()
    {
        $cid = 0;
        $filePath = storage_path("app/tpls/{$cid}.txt");
        $openids = file($filePath);

        $course = Course::where('id', $cid)->first();
        $params = [
            'tpl_params' => [
                'title' => $course->notify_title,
                'content' => $course->notify_content,
                'odate' => $course->notify_odate ?: $course->start_date . ' ' . date("H:i", strtotime($course->start_time)),
                'address' => $course->notify_address,
                'remark' => "\n" . $course->notify_remark,
                'url' => $course->notify_url,
            ],
            'need_check' => false,
            'template_id' => 4,
        ];
        // print_r($params); exit;
        $i = 0;
        foreach ($openids as $value) {
            $i++;
            $openid = trim($value);
            $params['tpl_params']['openid'] = $openid;
            $job = (new SendTemplateMessageByOpenid($params));
            $this->dispatch($job);
            echo "$i $openid\n";
        }
        echo "Done\n";
    }

    private function tplTest($num = 0)
    {
        $this->info('aaaa');

        $params = [
            'openid' => 'owtN6jkVHmd-ctim1pBNtWSqmXkU',
            'title' => 'test',
            'content' => '测试限速',
            'odate' => '测试时间',
        ];

        $client = new Client([
                'base_uri' => 'http://wyethapi.etocrm.com/',
                'timeout' => 2.0,
                // disable throwing exceptions on an HTTP protocol errors
                'http_errors' => false
            ]
        );

        $queryParams = [
            'openid' => isset($params['openid']) ? $params['openid'] : '',
            'title' => isset($params['title']) ? $params['title'] : '',
            'content' => isset($params['content']) ? $params['content'] : '',
            'odate' => isset($params['odate']) ? $params['odate'] : '',
            'address' => isset($params['address']) ? $params['address'] : '',
            'remark' => isset($params['remark']) ? $params['remark'] : '感谢您对惠氏妈妈一直以来的支持',
            'title_color' => isset($params['title_color']) ? $params['title_color'] : '#0255fb',
            'content_color' => isset($params['content_color']) ? $params['content_color'] : '',
            'odate_color' => isset($params['odate_color']) ? $params['odate_color'] : '',
            'address_color' => isset($params['address_color']) ? $params['address_color'] : '',
            'remark_color' => isset($params['remark_color']) ? $params['remark_color'] : '#0255fb',
            'url' => isset($params['url']) ? $params['url'] : '',
            'template_id' => 4,
        ];

        //重新计算
        $str = 'etocrm1291_' . date('Y-m-d') . 'minclassNews';
        $queryParams['accessToken'] = md5($str);
        //增加新参数
        $queryParams['activity_no'] = 'minclassNews';

        $ret = $client->request('POST', 'wpi/wyeth_oapi/pushCustomMessageForNew', ['form_params' => $queryParams]);

        $statusCode = $ret->getStatusCode();
        $body = $ret->getBody();

        $this->info("第$num 次" . $body);
        $result = json_decode($body, true);

    }

    private function exportNotWeiketangToTxt($path){
        //每个月底推送给非微课堂注册的渠道，读取xlsx文件
        $total = 0;
        Excel::load($path, function ($reader) use (&$total, $path) {
            $results = $reader->all();
            foreach ($results as $item){
                $openid = $item->toArray()['wxopenid'];
                if ($openid){
                    file_put_contents(substr($path, 0, strpos($path, '.')).'.txt', $openid."\n", FILE_APPEND);
                    $total ++;
                }
            }
        });
        $this->info( "总计人数$total");
    }

    //替换课程url的域名
    private function replaceCourseUrl()
    {
        $old = 'http://mama-weiketang.e-shopwyeth.com';
        $new = 'http://mama-weiketang-wyeth.woaap.com';

        if (!$this->confirm("将 $old 替换为 $new")){
            exit();
        }

        $course_all = Course::where('id', '>', 0)->get();
        foreach ($course_all as $course){
            $course->notify_url = str_replace($old, $new, $course->notify_url);
            $course->save();
        }
    }

    private function youzhu()
    {
        $crm = new Crm();
//        $res = $crm->getMemberStatus('owtN6jjprrWeEn_qUu7sxuq_0758');
//        $res = $crm->getMemberBrand('owtN6jsbAaeQwtX9vWBWGN1UxiR8');
//////        $res = $crm->searchMemberInfo('owtN6jkVHmd-ctim1pBNtWSqmXkU');
//        dd($res);

        $users = User::where('created_at', '>', '2018-02-01')->where('unionid', '>', '')->get();

        $i = 0;
        $j = 0;
        $count = count($users);
        foreach ($users as $user){
            $i++;
            if (!$user && !$user->openid){
                continue;
            }
            $status = $crm->getMemberStatus($user->unionid);
            $a = isset($status['YouZhu']) ? $status['YouZhu'] : 0;

            $info = $crm->searchMemberInfo($user->openid);
            if ($info['IsHaveShop'] != $a){
                $j++;
                echo $user->openid . "\n";
                if (!isset($status['YouZhu'])){
                    echo "$i/$j/$count no\n";
                }else{
                    echo "$i/$j/$count\n";
                }

            }
        }
    }

    //查询邀请人数
    private function invitation()
    {
        $invitations = Invitation::where('id', '>', 0)->get();
        $users = [];
        foreach ($invitations as $item){
            $users[$item->uid][] = $item->invitee_id;
        }
        foreach ($users as $k => $v){
            $u = User::find($k);
            $openid = $u->openid;
            $nickname = $u->nickname;
            echo "$openid $nickname " . count($v) . "\n";
        }
    }

    private function selectUser()
    {
        $file = '/opt/ci123/www/wyeth_dev/wyeth_xj/storage/users/2018-01-21~03-21jinzhuangwuzhucourse.txt';
        $user = DB::connection('mysql_read')->table('user_events')
            ->select(DB::raw('DISTINCT uid'))
            ->where('created_at', '>', '2018-01-21')
            ->where('type', 'review_in')
            ->get();
        $result = [];
        foreach ($user as $item) {
            $uid = $item->uid;
            $res = DB::connection('mysql_read')->table('user')
                ->select('brand','openid')
                ->where('id', $uid)
                ->first();
            if ($res) {
                $brand = $res->brand;
                if (isset($result[$res->brand])) {
                    $result[$res->brand] ++;
                } else {
                    $result[$res->brand] = 0;
                }
                if ($brand <= 1 || $brand == 4) {
                    file_put_contents($file, $res->openid . "\n", FILE_APPEND);
                }
            }
        }
        echo count($user);
        var_dump($result);
    }
}
