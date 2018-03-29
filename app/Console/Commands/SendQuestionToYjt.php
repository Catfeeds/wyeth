<?php

namespace App\Console\Commands;

use App\Models\CourseReviewQuestions;
use Illuminate\Console\Command;
use App\Models\Course;
use App\Services\BLogger;
use GuzzleHttp\Client;

class SendQuestionToYjt extends Command
{
    /**
     * The name and signature of the console command.
     * cid int  课程id
     * test bool 是否测试
     * @var string
     */
    protected $signature = 'send:review_question';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send course_review_question to yjt';

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
     * cid ke
     * @return mixed
     */
    /*public function handle()
    {
        $data = CourseReviewQuestions::where('is_send', 0)->get()->toArray();

        $yjtData = [];
        foreach ($data as $k => $v) {
            $title = Course::where('id', $v['cid'])->pluck('title');
            $params = [
                'user_unique' => $v['uid'],
                'class_theme' => $title,
                //'user_sex' => '',
                //'user_age' => '',
                'questions' => $v['content']
            ];
            ksort($params);
            $key = config('yjt.key');
            $params['sign'] = md5($key.http_build_query($params));
            $url = config('yjt.sendQuestionUrl');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            $resData = curl_exec($ch);
            curl_close($ch);
            $resData = json_decode($resData, true);
            if (isset($resData['code']) && $resData['code'] == 0 && !empty($resData['class_id'])) {
                $m = CourseReviewQuestions::find($v['id']);
                $m->is_send = 1;
                $m->yjt_qid = $resData['class_id'];
                $m->save();
            }
            $yjtData[] = $resData;
        }
        if (!empty($yjtData) && isset($title)) {
            $key = config('yjt.key');
            $time = time();
            $noce = uniqid();
            $sign = md5(md5($time.$key.$noce));
            $data = array(
                "time"  => $time,
                "sign"  => $sign,
                "noce"  => $noce,
                "class_name" => $title
            );
            $url = config('yjt.notifyDrUrl');
            $client = new Client();
            $res = $client->request('POST', $url, [
                'form_params' => $data
            ]);
            $resData = json_decode($res->getBody(), true);
            $yjtData[] = $resData;
        }
        $log = BLogger::getLogger('command');
        echo $log_info = '[' . date('Y-m-d H:i:s') . '] yjt_return:' . "\n" . var_export($yjtData, true);
        $log->info(__CLASS__, ['info'=>$log_info]);
    }*/

    public function handle()
    {
        //首先获取所有昨日提问的 并且没回答的问题
        $tomorrow = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
        $lastStart = date("Y-m-d H:i:s", strtotime($tomorrow . "+ 19 hours"));
        $todayStart = date("Y-m-d H:i:s", strtotime($tomorrow . "+1day + 19 hours"));
        $query = CourseReviewQuestions::select('uid')->where('created_at', '>=', $lastStart)->where('created_at', '<=', $todayStart)->where('is_send', 1)->where('yjt_qid', 0)->get()->toArray();
        $idArr = [];
        foreach ($query as $key => $item) {
            $idArr[$key] = $item['uid'];
        }
        $userIds = array_unique($idArr);
        foreach ($userIds as $userId) {
            $userQuestions = CourseReviewQuestions::where('created_at', '>=', $lastStart)->where('created_at', '<=', $todayStart)->where('is_send', 1)->where('yjt_qid', 0)->where('uid', $userId)->limit(3)->get();
            $params = [];
            $params['questions'] = '';
            $params['questions_id'] = '';
            $courseInfo = Course::where('id', $userQuestions[0]['cid'])->first();
            foreach ($userQuestions as $key=> $value) {
                $params['class_theme'] = $courseInfo->title;
                $params['questions'] .= "," . $value->content;
                $params['questions_id'] .= "-" . $value->id;
                $params['user_unique'] = $value->uid;
                $params['user_sex'] = 1;
                $params['type'] = 2;
                $params['user_age'] = strtotime($courseInfo->baby_birthday);
                $params['open_time'] = strtotime($courseInfo->start_day . ' ' . $courseInfo->start_time);
                if ($key == 0) {
                    $params['questions_time'] = strtotime($value->created_at);
                }
            }
            $params['questions'] = trim($params['questions'], ",");
            $params['question_id'] = trim($params['questions_id'], "-");
            $params['sign'] = $this->generateSign($params);
            $msgIds = explode('-', $params['questions_id']);
            $curl = curl_init();
            $url = "http://fywd.haoyisheng.com/api/issue/add";
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $params,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $resData = json_decode($response, true);
            if (isset($resData['code']) && $resData['code'] == 0 && !empty($resData['class_id'])) {
                $insertData = [];
                foreach ($msgIds as $v) {
                    $insertData[] = [
                        'msg_id' => $v,
                        'yjt_qid' => $resData['class_id'],
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
                $courseReviewQuestion = CourseReviewQuestions::whereIn('id', $msgIds)->update(['yjt_qid' => $resData['class_id']]);
                $this->info('Send Questions Success!');
            } else {
                $this->info('Send Questions faild!');
            }
        }
    }

    private function generateSign($data)
    {
        $key = 'yjt201603';
        $data['key'] = $key;
        ksort($data);
        $str = '';
        foreach ($data as $x => $x_value) {
            $str .= $x . "=" . $x_value . "&";
        }

        $str = trim($str, "&");
        return md5($str);
    }
}
