<?php

namespace App\Console\Commands;

use App\CIService\CIDataRecommend;
use App\Models\Tag;
use App\Models\Task;
use App\Models\UserTag;
use Illuminate\Console\Command;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use DB;
use Log;

class UpdateUserTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usertag:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $domain = 'https://cidata-recommend.oneitfarm.com';

    protected $url = '/recommendation/get_intrests';

    protected $appkey;

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client([
            'base_uri' => $this->domain,
            'timeout' => 2,
        ]);
        $this->appkey = config('oneitfarm.appkey');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $log_add_data = [];
        $log_delete_data = [];
        $time = strtotime(date('Y-m-d'));
        $week = date('N', $time);
        if($week == 1){
            $last_monday = date('Y-m-d',strtotime('-1 monday', $time));
        }else{
            $last_monday = date('Y-m-d',strtotime('-2 monday', $time));
        }
        $today = '2017-11-03 17:00:00';
        //获取一周内签到的任务
        $tasks = Task::where('type', 'sign')->where('created_at', '>', $last_monday)->select(DB::raw('distinct uid'))->get();
        //存储user_id,保证每个user_id只执行一次
        foreach ($tasks as $task){
            $uid = $task->uid;
            if($uid){
                //获取CIData用户与tag之间的关注度数值
                $result = $this->get_user_interest($uid);
                $data = $result['data'];
                //过滤掉其中的非内容tag
                foreach ($data as $k => $v){
                    $tag = Tag::where('id', $k)->first();
                    if(!$tag || $tag->type != 0){
                        unset($data[$k]);
                    }else{
                        echo('each task' . "\n");
                        if(!array_key_exists($k, $log_add_data)){
                            echo('set keys' . "\n");
                            $log_add_data[$k] = 0;
                        }
                        if(!array_key_exists($k, $log_delete_data)){
                            echo('set keys' . "\n");
                            $log_delete_data[$k] = 0;
                        }
                    }
                }
                //排序
                arsort($data);
                //取出用户关注的所有内容tag
                $tags = [];
                $user_tags = UserTag::where('uid', $uid)->where('type', 0)->get();
                echo('before update: ' . count($user_tags) . "\n");
                foreach ($user_tags as $user_tag){
                    $tags[] = $user_tag->tid;
                }
                $i = 0;
                $j = 0;
                //取前三个内容tag,如果没有关注就关注,同时最后三个内容tag,如果已关注则取消关注
                foreach ($data as $k => $v){
                    echo('i:' . $i . ', tid:' . $k . ', uid' . $uid . "\n");
                    if($i < 3){
                        $user_tag = UserTag::where('uid', $uid)->where('tid', $k)->first();
                        if(!$user_tag){
                            $user_tag = new UserTag();
                            $user_tag->uid = $task->uid;
                            $user_tag->tid = $k;
                            $user_tag->type = 0;
                            $user_tag->save();
                            $log_add_data[$k]++;
                            echo('save: ' . $uid . '  ' . $k . "\n");
                        }
                    }elseif($j >= (count($tags) - 3) && in_array($k, $tags)){
                        $user_tag = UserTag::where('uid', $uid)->where('tid', $k)->first();
                        if($user_tag && $v < 0){
                            $user_tag->delete();
                            echo('delete: ' . $uid . '  ' . $k . "\n");
                            $log_delete_data[$k]++;
                        }
                    }
                    if(in_array($k, $tags)){
                        $j++;
                    }
                    $i++;
                }
            }
        }
        foreach ($log_add_data as $k => $v){
            Log::info('tid:' . $k . '  add num:' . $v);
            echo('tid:' . $k . '  add num:' . $v . "\n");
        }
        foreach ($log_delete_data as $k => $v){
            Log::info('tid:' . $k . '  delete num:' . $v);
            echo('tid:' . $k . '  delete num:' . $v . "\n");
        }
    }

    public function get_user_interest($user_id){
        $params = [
            'app_id' => $this->appkey,
            'user_id' => $user_id
        ];
        $ret = $this->client->request('POST', $this->url, [
            'form_params' => $params
        ]);
        $body = $ret->getBody();
        $result = json_decode($body, true);
        return $result;
    }
}
