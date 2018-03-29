<?php

namespace App\Console\Commands;

use App\Models\UserTag;
use Illuminate\Console\Command;
use App\Models\Tag;
use App\Models\Task;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use DB;
use Log;

class DeleteUserTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usertag:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $log_delete_data = [];

    protected $domain = 'https://cidata-recommend.oneitfarm.com';

    protected $url = '/recommendation/get_intrests';

    protected $appkey;

    protected $client;

    protected $time;

    protected $i = 0;

    protected $num = 0;

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
        $this->num = intval(UserTag::count() / 500) + 1;
        $this->time = microtime(true);
        UserTag::select(DB::raw('distinct uid'))->chunk(500, function($user_tags){
            echo('cycle:' . $this->i . '/' . $this->num . "\n");
            Log::info('cycle:' . $this->i . '/' . $this->num . "\n");
            $interval = microtime(true) - $this->time;
            echo($interval . "\n");
            Log::info($interval . "\n");
            $this->time = microtime(true);
            foreach ($user_tags as $user_tag){
                $uid = $user_tag->uid;
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
                            if(!array_key_exists($k, $this->log_delete_data)){
                                $this->log_delete_data[$k] = 0;
                            }
                        }
                    }
                    //排序
                    arsort($data);
                    //取出用户关注的所有内容tag
                    $tags = [];
                    $user_tags = UserTag::where('uid', $uid)->where('type', 0)->get();
                    foreach ($user_tags as $u_t){
                        $tags[] = $u_t->tid;
                    }
                    $j = 0;
                    //取前三个内容tag,如果没有关注就关注,同时最后三个内容tag,如果已关注则取消关注
                    foreach ($data as $k => $v){
                        if($j >= 6 && in_array($k, $tags)){
                            $user_tag = UserTag::where('uid', $uid)->where('tid', $k)->first();
                            if($user_tag){
                                $user_tag->delete();
                                $this->log_delete_data[$k]++;
                            }
                        }
                        if(in_array($k, $tags)){
                            $j++;
                        }
                    }
                }
            }
            $this->i++;
        });
        //存储user_id,保证每个user_id只执行一次
        foreach ($this->log_delete_data as $k => $v){
            Log::info('tid:' . $k . '  delete num:' . $v);
            echo('tid:' . $k . '  delete num:' . $v . "\n");
        }
    }

    public function get_user_interest($user_id){
        $params = [
            'app_id' => $this->appkey,
            'user_id' => $user_id
        ];
        try{
            $ret = $this->client->request('POST', $this->url, [
                'form_params' => $params
            ]);
        } catch (\Exception $e){
            $ret = $this->client->request('POST', $this->url, [
                'form_params' => $params
            ]);
        }
        $body = $ret->getBody();
        $result = json_decode($body, true);
        return $result;
    }
}
